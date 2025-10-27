@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-200 dark:border-blue-300 text-start text-base font-medium text-white dark:text-white bg-blue-700 dark:bg-blue-800 focus:outline-none focus:text-blue-100 dark:focus:text-blue-100 focus:bg-blue-800 dark:focus:bg-blue-900 focus:border-blue-100 dark:focus:border-blue-200 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-white dark:text-white hover:text-blue-200 dark:hover:text-blue-200 hover:bg-blue-700 dark:hover:bg-blue-700 hover:border-blue-300 dark:hover:border-blue-300 focus:outline-none focus:text-blue-200 dark:focus:text-blue-200 focus:bg-blue-700 dark:focus:bg-blue-700 focus:border-blue-300 dark:focus:border-blue-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
