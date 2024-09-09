@props(['active'])

@php
$defaultClasses = 'text-sm font-bold text-blue p-2 flex gap-x-3 rounded-md leading-6 hover:bg-orange/45 hover:text-dark-blue transition duration-300 ease-in-out ';

$classes = ($active ?? false)
            ? $defaultClasses .= 'bg-[#f0f8ff]'
            : $defaultClasses;
@endphp

<li>
    <a {{ $attributes(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>