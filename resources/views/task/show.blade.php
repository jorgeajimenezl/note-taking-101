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
                    <input id="task-title" class="text-3xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out flex-grow" value="{{$task->title}}" name="title" readonly size="{{ strlen($task->title) }}">
                    <button id="save-button" class="btn btn-primary ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out" type="submit">Save Changes</button>
                </div>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <x-form-text-area id="task-content" label="Description" name="description" value="{{$task->description}}" placeholder="Description" readonly=true />
            </div>
        </main>
    </form>
    <x-site-layout-footer />
</x-site-layout-base>

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

    document.getElementById('task-form').addEventListener('submit', function() {
        document.getElementById('task-title').removeAttribute('readonly');
        document.getElementById('task-content').removeAttribute('readonly');
    });
</script>