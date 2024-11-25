@include('components.form._form-label')
<textarea {{ isset($id) ? "id=$id" : '' }} placeholder="{{$placeholder}}" name="{{$name}}" class="w-full p-1 rounded-lg border border-gray-200 @error($name) border-red-500 @enderror mt-2" rows="5" {{$readonly ? 'readonly' : ''}}>{{ old($name, $value) }}</textarea>
@include('components.form._form-error-handling')
<br/><br/>
