<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;
use SimpleXMLElement;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class ExcelWorkbook
{
    /**
     * @param  array<int, string>  $headers
     * @param  iterable<array<int, scalar|null>>  $rows
     * @param  array<int, int>  $textColumns  Zero-based column indexes to format as text, with index 0 as column A.
     */
    public function download(string $filename, array $headers, iterable $rows, array $textColumns = [], bool $isTemplate = false): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows, $textColumns, $isTemplate): void {
            $temporaryPath = tempnam(sys_get_temp_dir(), 'school-xlsx-');
            if ($temporaryPath === false) {
                throw new InvalidArgumentException('File Excel sementara tidak dapat dibuat.');
            }

            try {
                $this->writeWorkbook($temporaryPath, $headers, $rows, $textColumns, $isTemplate);
                readfile($temporaryPath);
            } finally {
                @unlink($temporaryPath);
            }
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /** @return array<int, array<string, string>> */
    public function read(UploadedFile $file): array
    {
        $archive = new ZipArchive;
        if ($archive->open($file->getRealPath()) !== true) {
            throw new InvalidArgumentException('File Excel tidak dapat dibuka.');
        }

        try {
            $sheetPath = $this->firstWorksheetPath($archive);
            $sheetInfo = $archive->statName($sheetPath);
            if ($sheetInfo === false || $sheetInfo['size'] > 10_000_000) {
                throw new InvalidArgumentException('Ukuran lembar Excel tidak valid atau terlalu besar.');
            }
            $sheetContents = $archive->getFromName($sheetPath);
            if ($sheetContents === false) {
                throw new InvalidArgumentException('Lembar pertama pada file Excel tidak ditemukan.');
            }
            $sharedStrings = $this->sharedStrings($archive);
        } finally {
            $archive->close();
        }

        $sheet = $this->parseXml($sheetContents, 'Isi lembar Excel tidak valid.');
        $sheetRows = $sheet->xpath('//*[local-name()="row"]') ?: [];
        if ($sheetRows === []) {
            throw new InvalidArgumentException('File Excel tidak memiliki baris data.');
        }

        $headers = [];
        foreach ($this->rowValues($sheetRows[0], $sharedStrings) as $column => $header) {
            $normalizedHeader = Str::slug(trim($header), '_');
            if ($normalizedHeader !== '') {
                $headers[$column] = $normalizedHeader;
            }
        }
        if ($headers === []) {
            throw new InvalidArgumentException('Baris pertama harus berisi nama kolom.');
        }

        $rows = [];
        foreach (array_slice($sheetRows, 1) as $index => $sheetRow) {
            $cellTypes = [];
            $values = $this->rowValues($sheetRow, $sharedStrings, $cellTypes);
            $row = [];
            foreach ($headers as $column => $header) {
                $row[$header] = trim($values[$column] ?? '');

                if ($row[$header] !== ''
                    && in_array($header, ['nip', 'nik', 'nisn', 'no_whatsapp'], true)
                    && ! in_array($cellTypes[$column] ?? '', ['inlineStr', 's', 'str'], true)) {
                    throw new InvalidArgumentException('Baris '.($index + 2).': kolom '.Str::upper(str_replace('_', ' ', $header)).' harus disimpan sebagai teks. Atur sel ke format Teks, lalu ketik ulang nilainya sebelum mengimpor.');
                }
            }

            if (count(array_filter($row, static fn (string $value): bool => $value !== '')) === 0) {
                continue;
            }
            $rows[] = $row;
            if (count($rows) > 500) {
                throw new InvalidArgumentException('Maksimal 500 baris dapat diimpor sekaligus.');
            }
        }
        if ($rows === []) {
            throw new InvalidArgumentException('File Excel belum berisi baris data.');
        }

        return $rows;
    }

    private function firstWorksheetPath(ZipArchive $archive): string
    {
        $workbookContents = $archive->getFromName('xl/workbook.xml');
        $relationshipsContents = $archive->getFromName('xl/_rels/workbook.xml.rels');
        if ($workbookContents === false || $relationshipsContents === false) {
            throw new InvalidArgumentException('Struktur workbook Excel tidak lengkap.');
        }

        $workbook = $this->parseXml($workbookContents, 'Struktur workbook Excel tidak valid.');
        $relationships = $this->parseXml($relationshipsContents, 'Relasi workbook Excel tidak valid.');
        $firstSheet = $workbook->xpath('//*[local-name()="sheets"]/*[local-name()="sheet"]')[0] ?? null;
        if (! $firstSheet instanceof SimpleXMLElement) {
            throw new InvalidArgumentException('File Excel tidak memiliki lembar kerja.');
        }

        $relationshipId = '';
        foreach ($firstSheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships') as $name => $value) {
            if ($name === 'id') {
                $relationshipId = (string) $value;
                break;
            }
        }
        if ($relationshipId === '') {
            throw new InvalidArgumentException('Relasi lembar kerja Excel tidak ditemukan.');
        }

        foreach ($relationships->xpath('//*[local-name()="Relationship"]') ?: [] as $relationship) {
            if ((string) $relationship['Id'] !== $relationshipId
                || (string) $relationship['TargetMode'] === 'External') {
                continue;
            }

            $target = (string) $relationship['Target'];
            $path = str_starts_with($target, '/')
                ? ltrim($target, '/')
                : 'xl/'.ltrim($target, '/');
            $segments = [];
            foreach (explode('/', $path) as $segment) {
                if ($segment === '' || $segment === '.') {
                    continue;
                }
                if ($segment === '..') {
                    array_pop($segments);

                    continue;
                }
                $segments[] = $segment;
            }
            $resolvedPath = implode('/', $segments);
            if (str_starts_with($resolvedPath, 'xl/worksheets/')) {
                return $resolvedPath;
            }
        }

        throw new InvalidArgumentException('Lembar pertama pada file Excel tidak ditemukan.');
    }

    /**
     * @param  array<int, string>  $headers
     * @param  iterable<array<int, scalar|null>>  $rows
     * @param  array<int, int>  $textColumns
     */
    private function writeWorkbook(string $path, array $headers, iterable $rows, array $textColumns, bool $isTemplate): void
    {
        $archive = new ZipArchive;
        if ($archive->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new InvalidArgumentException('File Excel tidak dapat dibuat.');
        }

        $sheetRows = [$headers];
        foreach ($rows as $row) {
            $sheetRows[] = array_map(static fn (mixed $value): string => (string) ($value ?? ''), array_values($row));
        }
        $archive->addFromString('[Content_Types].xml', $this->contentTypesXml());
        $archive->addFromString('_rels/.rels', $this->rootRelationshipsXml());
        $archive->addFromString('xl/workbook.xml', $this->workbookXml());
        $archive->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationshipsXml());
        $archive->addFromString('xl/worksheets/sheet1.xml', $this->worksheetXml($sheetRows, $textColumns, $isTemplate));
        $archive->addFromString('xl/styles.xml', $this->stylesXml());
        $archive->close();
    }

    /** @param array<int, array<int, string>> $rows */
    private function worksheetXml(array $rows, array $textColumns, bool $isTemplate): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        if ($isTemplate) {
            $xml .= '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>';
        }
        if ($textColumns !== []) {
            $xml .= '<cols>';
            if ($isTemplate) {
                $widths = [30, 24, 30, 34, 38];
                for ($columnIndex = 0; $columnIndex < count($rows[0] ?? []); $columnIndex++) {
                    $columnNumber = $columnIndex + 1;
                    $style = in_array($columnIndex, $textColumns, true) ? ' style="1" customFormat="1"' : '';
                    $width = $widths[$columnIndex] ?? 24;
                    $xml .= '<col min="'.$columnNumber.'" max="'.$columnNumber.'" width="'.$width.'" customWidth="1"'.$style.'/>';
                }
            } else {
                foreach ($textColumns as $columnIndex) {
                    $columnNumber = $columnIndex + 1;
                    $xml .= '<col min="'.$columnNumber.'" max="'.$columnNumber.'" style="1" customFormat="1"/>';
                }
            }
            $xml .= '</cols>';
        }
        $xml .= '<sheetData>';
        $lastRowIndex = max(count($rows) - 1, $textColumns === [] ? 0 : 500);
        for ($rowIndex = 0; $rowIndex <= $lastRowIndex; $rowIndex++) {
            $cells = $rows[$rowIndex] ?? [];
            $excelRow = $rowIndex + 1;
            $xml .= '<row r="'.$excelRow.'">';
            foreach ($cells as $columnIndex => $cellValue) {
                $cellReference = $this->columnName($columnIndex + 1).$excelRow;
                $escapedValue = htmlspecialchars($cellValue, ENT_XML1 | ENT_COMPAT, 'UTF-8');
                $styleIndex = $isTemplate && $rowIndex === 0
                    ? 2
                    : (in_array($columnIndex, $textColumns, true) ? 1 : 0);
                $style = $styleIndex === 0 ? '' : ' s="'.$styleIndex.'"';
                $xml .= '<c r="'.$cellReference.'"'.$style.' t="inlineStr"><is><t xml:space="preserve">'.$escapedValue.'</t></is></c>';
            }
            if ($rowIndex > 0) {
                foreach ($textColumns as $columnIndex) {
                    if (! array_key_exists($columnIndex, $cells)) {
                        $cellReference = $this->columnName($columnIndex + 1).$excelRow;
                        $xml .= '<c r="'.$cellReference.'" s="1"/>';
                    }
                }
            }
            $xml .= '</row>';
        }
        $xml .= '</sheetData>';
        if ($isTemplate) {
            $lastColumn = $this->columnName(count($rows[0] ?? []));
            $xml .= '<autoFilter ref="A1:'.$lastColumn.'501"/>';
            $textValidations = [];
            foreach ($textColumns as $columnIndex) {
                $header = strtoupper(trim($rows[0][$columnIndex] ?? ''));
                $digitCount = match ($header) {
                    'NIP' => 18,
                    'NIK' => 16,
                    'NISN' => 10,
                    default => null,
                };
                if ($digitCount === null) {
                    continue;
                }

                $columnName = $this->columnName($columnIndex + 1);
                $textValidations[] = '<dataValidation type="custom" allowBlank="1" showInputMessage="1" showErrorMessage="1" errorTitle="'.$header.' tidak valid" error="Isi '.$header.' sebagai teks tepat '.$digitCount.' digit." promptTitle="'.$header.'" prompt="Ketik atau tempel '.$digitCount.' digit. Jangan gunakan notasi ilmiah." sqref="'.$columnName.'2:'.$columnName.'501"><formula1>AND(ISTEXT('.$columnName.'2),LEN('.$columnName.'2)='.$digitCount.')</formula1></dataValidation>';
            }
            foreach ($rows[0] ?? [] as $columnIndex => $header) {
                if (strtoupper(trim($header)) !== 'JENIS KELAMIN') {
                    continue;
                }

                $columnName = $this->columnName($columnIndex + 1);
                $textValidations[] = '<dataValidation type="list" allowBlank="1" showInputMessage="1" showErrorMessage="1" errorTitle="Jenis kelamin tidak valid" error="Pilih Laki-laki atau Perempuan." promptTitle="Jenis Kelamin" prompt="Pilih Laki-laki atau Perempuan dari daftar." sqref="'.$columnName.'2:'.$columnName.'501"><formula1>"Laki-laki,Perempuan"</formula1></dataValidation>';
            }
            if ($textValidations !== []) {
                $xml .= '<dataValidations count="'.count($textValidations).'">'.implode('', $textValidations).'</dataValidations>';
            }
        }

        return $xml.'</worksheet>';
    }

    /** @return array<int, string> */
    private function sharedStrings(ZipArchive $archive): array
    {
        $stringsInfo = $archive->statName('xl/sharedStrings.xml');
        if ($stringsInfo !== false && $stringsInfo['size'] > 10_000_000) {
            throw new InvalidArgumentException('Daftar teks Excel terlalu besar.');
        }
        $contents = $archive->getFromName('xl/sharedStrings.xml');
        if ($contents === false) {
            return [];
        }

        $xml = $this->parseXml($contents, 'Daftar teks Excel tidak valid.');
        $strings = [];
        foreach ($xml->xpath('//*[local-name()="si"]') ?: [] as $item) {
            $text = '';
            foreach ($item->xpath('.//*[local-name()="t"]') ?: [] as $textNode) {
                $text .= (string) $textNode;
            }
            $strings[] = $text;
        }

        return $strings;
    }

    /** @return array<int, string> */
    private function rowValues(SimpleXMLElement $row, array $sharedStrings, ?array &$cellTypes = null): array
    {
        $values = [];
        foreach ($row->xpath('./*[local-name()="c"]') ?: [] as $cell) {
            $reference = (string) $cell['r'];
            if (preg_match('/^([A-Z]+)/', $reference, $matches) !== 1) {
                continue;
            }
            $columnIndex = 0;
            foreach (str_split($matches[1]) as $letter) {
                $columnIndex = ($columnIndex * 26) + ord($letter) - 64;
            }
            $columnIndex--;

            $cellTypes[$columnIndex] = (string) $cell['t'];
            $value = '';
            if ((string) $cell['t'] === 'inlineStr') {
                foreach ($cell->xpath('./*[local-name()="is"]//*[local-name()="t"]') ?: [] as $textNode) {
                    $value .= (string) $textNode;
                }
            } else {
                $rawValue = (string) ($cell->xpath('./*[local-name()="v"]')[0] ?? '');
                $value = (string) $cell['t'] === 's'
                    ? ($sharedStrings[(int) $rawValue] ?? '')
                    : $rawValue;
            }
            $values[$columnIndex] = $value;
        }

        return $values;
    }

    private function parseXml(string $contents, string $message): SimpleXMLElement
    {
        $previousErrorSetting = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($contents, SimpleXMLElement::class, LIBXML_NONET | LIBXML_NOBLANKS);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorSetting);
        if (! $xml instanceof SimpleXMLElement) {
            throw new InvalidArgumentException($message);
        }

        return $xml;
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $name = chr(65 + $remainder).$name;
            $number = intdiv($number - 1, 26);
        }

        return $name;
    }

    private function contentTypesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'.
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'.
            '<Default Extension="xml" ContentType="application/xml"/>'.
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'.
            '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'.
            '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'.
            '</Types>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.
            '<fonts count="1"><font><sz val="11"/><name val="Calibri"/><family val="2"/></font></fonts>'.
            '<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills>'.
            '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'.
            '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'.
            '<cellXfs count="2"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/></cellXfs>'.
            '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'.
            '</styleSheet>';
    }

    private function rootRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'.
            '</Relationships>';
    }

    private function workbookXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'.
            '<sheets><sheet name="Data" sheetId="1" r:id="rId1"/></sheets>'.
            '</workbook>';
    }

    private function workbookRelationshipsXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'.
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'.
            '</Relationships>';
    }

    private function stylesXml(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.
            '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><color rgb="FFFFFFFF"/><sz val="11"/><name val="Calibri"/></font></fonts>'.
            '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FF1D4ED8"/><bgColor indexed="64"/></patternFill></fill></fills>'.
            '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'.
            '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'.
            '<cellXfs count="3"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/><xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/><xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/></cellXfs>'.
            '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'.
            '</styleSheet>';
    }
}
