<x-app-layout title="{{$task->title}}">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session()->has('success'))
        <div class="bg-green-100 text-green-500 p-2 mb-4">
            {!! session()->get('success') !!}
        </div>
        @endif

        <form id="task-form" action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white shadow rounded-lg">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <div class="title-section flex items-center justify-between">
                        <x-text-input id="task-title" data-mark="input-field" class="text-3xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow" value="{{$task->title}}" name="title" readonly />                        
                        @if($role === 'editor' || $role === 'owner')
                            <x-primary-button id="save-button" class="ml-2">
                                <i class="fas fa-save"></i> 
                                <span class="ml-1">Save</span>
                            </x-primary-button>
                            @if($role === 'owner')        
                                <x-danger-button id="delete-button" class="ml-2">
                                    <i class="fas fa-trash-alt"></i> 
                                    <span class="ml-1">Delete</span>
                                </x-danger-button>
                                <div class="separator mx-2"></div>
                                <x-primary-button id="share-button" class="ml-1" type="button" onclick="toggleContributorDialog(true)">
                                    <i class="fas fa-share-alt"></i> 
                                    <span class="ml-1">Share</span>
                                </x-primary-button>
                            @endif
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <x-contributor-selector-dialog :task="$task"/>
                <main>
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <x-tag-selector id="task-tags" name="tags" :tags="$task->tags->all()" :allTags="$allTags->all()" :readonly="$role === 'viewer'"/>
                        <div class="mt-5"></div>
                        <x-textarea-input id="task-description" name="description" value="{{$task->description}}" placeholder="Description" readonly=true />
                        <div class="mt-5">
                            <p>Author: <a href="mailto:{{ $task->author->email }}">{{ $task->author->name }}</a></p>
                        </div>
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
    .separator {
        width: 1px;
        height: 24px;
        background-color: #000;
    }
</style>

@if($role !== 'viewer')
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
                document.getElementsByName('_method')[0].value = 'DELETE';
                form.submit();
                form.action = '{{ route('tasks.update', $task) }}';
                document.getElementsByName('_method')[0].value = 'PUT';
            }
        });
    </script>
@endif