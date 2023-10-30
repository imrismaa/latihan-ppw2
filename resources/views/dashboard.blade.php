<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="p-6 text-center text-gray-900 dark:text-gray-900">
            {{ __("Welcome to your dashboard page, Risma!") }}
        </div>
    </div>
</x-app-layout>
