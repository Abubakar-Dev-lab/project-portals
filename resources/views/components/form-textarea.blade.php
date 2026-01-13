@props(['name', 'label', 'value' => ''])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-bold text-gray-700 mb-1">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}" rows="5"
        {{ $attributes->merge([
            'class' =>
                'w-full rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500 ' .
                ($errors->has($name) ? 'border-red-500' : ''),
        ]) }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>
