@props(['name', 'label', 'options' => [], 'selected' => ''])

<div class="mb-4">
    <label class="block text-gray-700 font-bold mb-2">{{ $label }}</label>
    <select name="{{ $name }}" class="w-full border rounded px-3 py-2 @error($name) border-red-500 @enderror">
        <option value="">-- Select {{ $label }} --</option>
        @foreach ($options as $id => $display)
            <option value="{{ $id }}" {{ old($name, $selected) == $id ? 'selected' : '' }}>
                {{ $display }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
