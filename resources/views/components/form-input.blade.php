@props(['name', 'label', 'type' => 'text', 'value' => ''])

<div class="mb-4">
    <label class="block text-gray-700 font-bold mb-2">{{ $label }}</label>
    <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full border rounded px-3 py-2 ' . ($errors->has($name) ? 'border-red-500' : 'border-gray-300')]) }}>
    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
