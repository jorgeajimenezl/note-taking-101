<x-site-layout-base title="{{$task->title}}">
    <x-site-layout-menu />

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-500 p-2">
        {!! session()->get('success') !!}
    </div>
    @endif

    <form id="task-form" action="{{ route('task.update', $task) }}" method="POST">
        @csrf
        @method('PUT')
        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="title-section flex items-center justify-between">
                    <input id="task-title" data-mark="input-field" class="text-3xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow" value="{{$task->title}}" name="title" readonly size="{{ strlen($task->title) }}">
                        <button id="save-button" class="btn btn-primary ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out" type="submit">Save Changes</button>
                    </form>
                    <button id="delete-button" class="btn btn-danger ml-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition duration-300 ease-in-out" type="submit">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
            <main>
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">             
                    <x-tag-selector id="task-tags" name="tags" label="Tags" :tags="$task->tags->all()" :allTags="$allTags->all()"/>
                    <div class="mt-2" />
                    <x-form-text-area id="task-description" label="Description" name="description" value="{{$task->description}}" placeholder="Description" readonly=true />
                </div>
            </main>
        </form>

        <x-site-layout-footer />
</x-site-layout-base>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .editable {
        border: 1px solid #ccc;
        padding: 2.5px;
        border-radius: 5px;
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
            form.action = '{{ route('task.destroy', $task) }}';
            form.method = 'DELETE';
            form.submit();
            form.action = '{{ route('task.update', $task) }}';
            form.method = 'PUT';
        }
    });
</script>