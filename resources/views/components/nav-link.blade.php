@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#ff8d2a] text-sm font-bold leading-5 text-white focus:outline-none focus:border-[#ffb970] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-white/75 hover:text-white hover:border-[#ff8d2a] focus:outline-none focus:text-white focus:border-[#ff8d2a] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
