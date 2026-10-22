@props([
    'title' => '',
    'description' => '',
    'icon' => 'fa-info-circle',
    'color' => 'blue',
    'href' => '#'
])

@php
    $colors = [
        'blue' => [
            'bg' => 'bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white',
            'arrow' => 'bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white',
            'border' => 'hover:border-blue-200'
        ],
        'green' => [
            'bg' => 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white',
            'arrow' => 'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white',
            'border' => 'hover:border-emerald-200'
        ],
        'yellow' => [
            'bg' => 'bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white',
            'arrow' => 'bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white',
            'border' => 'hover:border-amber-200'
        ],
        'purple' => [
            'bg' => 'bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white',
            'arrow' => 'bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white',
            'border' => 'hover:border-purple-200'
        ],
    ];
    $c = $colors[$color] ?? $colors['blue'];
@endphp

<a href="{{ $href }}" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 {{ $c['border'] }} hover:shadow-md transition-all duration-300 flex items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl {{ $c['bg'] }} flex items-center justify-center text-xl shrink-0 transition-colors duration-300">
            <i class="fas {{ $icon }}"></i>
        </div>
        <div>
            <h3 class="text-base font-extrabold text-blue-950 group-hover:text-blue-700 transition">{{ $title }}</h3>
            <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $description }}</p>
        </div>
    </div>
    <div class="w-9 h-9 rounded-full {{ $c['arrow'] }} flex items-center justify-center text-xs shrink-0 transition-all duration-300 transform group-hover:translate-x-1">
        <i class="fas fa-arrow-right"></i>
    </div>
</a>