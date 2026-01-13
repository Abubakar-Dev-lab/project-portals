@props(['name', 'label', 'options' => [], 'selected' => ''])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-bold text-gray-700 mb-1">
        {{ $label }}
    </label>

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500 ' . ($errors->has($name) ? 'border-red-500' : '')
        ]) }}
    >
        <option value="">-- Select {{ $label }} --</option>
        @foreach($options as $id => $display)
            <option value="{{ $id }}" {{ old($name, $selected) == $id ? 'selected' : '' }}>
                {{ $display }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
    @enderror
</div>
