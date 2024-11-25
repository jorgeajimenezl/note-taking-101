<x-site-layout-base title="{{$task->title}}">
    <x-site-layout-menu />

    @if(session()->has('success'))
    <div class="bg-green-100 text-green-500 p-2">
        {!! session()->get('success') !!}
    </div>
    @endif

    <header class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="title-section flex items-center justify-between">
                <input id="task-title" class="text-3xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow" value="{{$task->title}}" name="title" readonly size="{{ strlen($task->title) }}">
                <form id="save-task-form" action="{{ route('task.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="title" id="hidden-title"/>
                    <input type="hidden" name="description" id="hidden-description"/>
                    <button id="save-button" class="btn btn-primary ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out" type="submit">Save Changes</button>
                </form>
                <form id="delete-task-form" action="{{ route('task.destroy', $task) }}" method="DELETE">
                    @csrf
                    @method('DELETE')
                    <button id="delete-button" class="btn btn-danger ml-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-700 transition duration-300 ease-in-out" type="submit">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <x-form-text-area id="task-content" label="Description" name="description" value="{{$task->description}}" placeholder="Description" readonly=true />
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
    document.getElementById('task-title').addEventListener('dblclick', function() {
        this.removeAttribute('readonly');
        this.classList.add('editable');
        this.focus();
    });

    document.getElementById('task-title').addEventListener('blur', function() {
        this.setAttribute('readonly', true);
        this.classList.remove('editable');
    });

    document.getElementById('task-content').addEventListener('dblclick', function() {
        this.removeAttribute('readonly');
        this.classList.add('editable');
        this.focus();
    });

    document.getElementById('task-content').addEventListener('blur', function() {
        this.setAttribute('readonly', true);
        this.classList.remove('editable');
    });

    document.getElementById('save-task-form').addEventListener('submit', function() {
        document.getElementById('hidden-title').value = document.getElementById('task-title').value;
        document.getElementById('hidden-description').value = document.getElementById('task-content').value;
    });
</script>