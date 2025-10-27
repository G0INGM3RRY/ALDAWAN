@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-200 dark:border-blue-300 text-sm font-medium leading-5 text-white dark:text-white focus:outline-none focus:border-blue-100 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white dark:text-white hover:text-blue-200 dark:hover:text-blue-200 hover:border-blue-300 dark:hover:border-blue-300 focus:outline-none focus:text-blue-200 dark:focus:text-blue-200 focus:border-blue-300 dark:focus:border-blue-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
