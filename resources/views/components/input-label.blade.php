@props(['value'])
<label {{ $attributes->merge(['class' => 'block font-bold text-xs uppercase tracking-wider text-gray-600 mb-1']) }}>
    {{ $value ?? $slot }}
</label>
