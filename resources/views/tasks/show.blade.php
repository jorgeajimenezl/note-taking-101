<x-app-layout title="{{$task->title}}">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session()->has('success'))
        <div class="bg-green-100 text-green-500 p-2 mb-4">
            {!! session()->get('success') !!}
        </div>
        @endif

        <livewire:task-details :task="$task"/>
    </div>
</x-app-layout>