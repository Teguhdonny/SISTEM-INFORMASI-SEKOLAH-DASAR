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
     */
    public function download(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $temporaryPath = tempnam(sys_get_temp_dir(), 'school-xlsx-');
            if ($temporaryPath === false) {
                throw new InvalidArgumentException('File Excel sementara tidak dapat dibuat.');
            }

            try {
                $this->writeWorkbook($temporaryPath, $headers, $rows);
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
            $sheetInfo = $archive->statName('xl/worksheets/sheet1.xml');
            if ($sheetInfo === false || $sheetInfo['size'] > 10_000_000) {
                throw new InvalidArgumentException('Ukuran lembar Excel tidak valid atau terlalu besar.');
            }
            $sheetContents = $archive->getFromName('xl/worksheets/sheet1.xml');
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
        foreach (array_slice($sheetRows, 1) as $sheetRow) {
            $values = $this->rowValues($sheetRow, $sharedStrings);
            $row = [];
            foreach ($headers as $column => $header) {
                $row[$header] = trim($values[$column] ?? '');
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

    /**
     * @param  array<int, string>  $headers
     * @param  iterable<array<int, scalar|null>>  $rows
     */
    private function writeWorkbook(string $path, array $headers, iterable $rows): void
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
        $archive->addFromString('xl/worksheets/sheet1.xml', $this->worksheetXml($sheetRows));
        $archive->close();
    }

    /** @param array<int, array<int, string>> $rows */
    private function worksheetXml(array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        foreach ($rows as $rowIndex => $cells) {
            $excelRow = $rowIndex + 1;
            $xml .= '<row r="'.$excelRow.'">';
            foreach ($cells as $columnIndex => $cellValue) {
                $cellReference = $this->columnName($columnIndex + 1).$excelRow;
                $escapedValue = htmlspecialchars($cellValue, ENT_XML1 | ENT_COMPAT, 'UTF-8');
                $xml .= '<c r="'.$cellReference.'" t="inlineStr"><is><t xml:space="preserve">'.$escapedValue.'</t></is></c>';
            }
            $xml .= '</row>';
        }

        return $xml.'</sheetData></worksheet>';
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
    private function rowValues(SimpleXMLElement $row, array $sharedStrings): array
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
            '</Types>';
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
            '</Relationships>';
    }
}
