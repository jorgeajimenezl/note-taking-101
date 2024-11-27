<x-app-layout title="{{$task->title}}">
    @if(session()->has('success'))
    <div class="bg-green-100 text-green-500 p-2">
        {!! session()->get('success') !!}
    </div>
    @endif

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <form id="task-form" action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white shadow rounded-lg">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="title-section flex items-center justify-between">
                        <x-text-input id="task-title" data-mark="input-field" class="text-3xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow" value="{{$task->title}}" name="title" readonly />
                        <x-primary-button id="save-button" class="ml-4">Save Changes</x-primary-button>
                        <x-danger-button id="delete-button" class="ml-2">
                            <i class="fas fa-trash-alt"></i> 
                            <span class="ml-1">Delete</span>
                        </x-danger-button>
                    </div>
                </div>
                <main>
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <x-tag-selector id="task-tags" name="tags" label="Tags" :tags="$task->tags->all()" :allTags="$allTags->all()" />
                        <div class="mt-5" />
                        <x-text-area-input id="task-description" label="Description" name="description" value="{{$task->description}}" placeholder="Description" readonly=true />
                    </div>
                </main>
            </div>
        </form>
    </div>
</x-app-layout>
<style>
    .editable {
        border: 1px solid #ccc;
    }
    #task-title {
        box-shadow: none;
    }
</style>
<script>
    let originalInputs = {};
    document.addEventListener("DOMContentLoaded", function() {
        var inputs = document.querySelectorAll('[data-mark="input-field"]');
        inputs.forEach(function(input) {
            originalInputs[input] = input.value;
            input.addEventListener('dblclick', function() {
                this.removeAttribute('readonly');
                this.classList.add('editable');
                this.focus();
            });
            input.addEventListener('blur', function() {
                this.setAttribute('readonly', true);
                this.classList.remove('editable');
                // checkForChanges();
            });
            input.addEventListener('input', function() {
                // checkForChanges();
            });
        });
    });
    function checkForChanges() {
        var inputs = document.querySelectorAll('[data-mark="input-field"]');
        var saveButton = document.getElementById('save-button');
        var hasChanges = false;
        if (inputs.length !== originalInputs.length) {
            hasChanges = true;
        } else {
            inputs.forEach(function(input) {
                if (input.value !== originalInputs[input]) {
                    hasChanges = true;
                    return;
                }
            });
        }
        saveButton.classList.toggle('hidden', !hasChanges);
    }
    document.getElementById('delete-button').addEventListener('click', function(event) {
        event.preventDefault();
        const form = document.getElementById('task-form');
        if (confirm('Are you sure you want to delete this task?')) {
            form.action = '{{ route('tasks.destroy', $task) }}';
            form.method = 'DELETE';
            form.submit();
            form.action = '{{ route('tasks.update', $task) }}';
            form.method = 'PUT';
        }
    });
</script>