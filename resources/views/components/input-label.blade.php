@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-green-900 dark:text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
