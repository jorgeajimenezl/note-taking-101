@php
    $colors = ['bg-red-500', 'bg-green-500', 'bg-blue-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];
    $backgroundColor = $colors[array_rand($colors)];
@endphp

<div class="flex items-center space-x-2">
    @if($profilePicture)
        <img src="{{ $profilePicture }}" alt="{{ $name }}" class="w-8 h-8 rounded-full">
    @else
        <div class="w-8 h-8 rounded-full {{ $backgroundColor }} flex items-center justify-center text-white">
            {{ $initials }}
        </div>
    @endif
</div>