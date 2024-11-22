<x-site-layout-base>
    <x-site-layout-menu />
    <header class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <input id="task-title" class="text-3xl font-bold tracking-tight text-gray-900 bg-transparent border-none focus:outline-none transition duration-300 ease-in-out" value="{{$task->title}}" readonly size="{{ strlen($task->title) }}">
        </div>
    </header>
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Task Details</h3>
                    <div class="mt-5 border-t border-gray-200">
                        <dl class="divide-y divide-gray-200">
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{$task->description}}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Is Completed</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{$task->isCompleted() ? 'Yes' : 'No'}}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{$task->created_at}}</dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{$task->updated_at}}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
</script>