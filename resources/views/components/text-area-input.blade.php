@include('components.form._form-label')

@php
    $inputClasses = 'w-full p-1 rounded-lg border border-gray-200 mt-2';
    if ($errors->has($name)) {
        $inputClasses .= ' border-red-500';
    }
@endphp

<textarea 
    @isset($id) id="{{ $id }}" @endisset 
    data-mark="input-field" 
    {{ $attributes->merge(['class' => $inputClasses]) }}
    rows="{{ $rows ?? 5 }}"
    @if($readonly) readonly @endif
>{{ old($name, $value) }}</textarea>

@include('components.form._form-error-handling')

<br/><br/>
