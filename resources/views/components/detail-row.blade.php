@props(['label', 'value'])

<div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 border-b border-gray-100 last:border-0">
    <dt class="text-sm font-medium text-gray-500">
        {{ $label }}
    </dt>
    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
        {{ $value ?? $slot }}
    </dd>
</div>
