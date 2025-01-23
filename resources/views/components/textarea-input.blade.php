@props(['name', 'value' => '', 'readonly' => null, 'rows' => 5])

@php
    $inputClasses = 'w-full p-1 mt-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
    if ($errors->has($name)) {
        $inputClasses .= ' border-red-500';
    }
@endphp

<textarea
    {{ $attributes->merge(['class' => $inputClasses]) }}
    data-mark="input-field"
    rows="{{ $rows ?? 5 }}"
    name="{{ $name }}"
    id="{{ $name }}"
    @if($readonly) readonly @endif
>{{ old($name, $value) }}</textarea>

@include('components.form._form-error-handling')