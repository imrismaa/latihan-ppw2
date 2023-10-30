@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-rose-900 dark:focus:border-rose-900 focus:ring-rose-900 dark:focus:ring-rose-900 rounded-md shadow-md']) !!}>
