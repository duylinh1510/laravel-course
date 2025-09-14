@props(['user' => null, 'size' => 'w-12 h-12'])

@if ($user && $user->image)
    <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}" class="{{ $size }}rounded-full object-cover">
@elseif ($user)
    <div class="{{ $size }} bg-gray-300 rounded-full flex items-center justify-center">
        <span class="text-gray-600 font-semibold text-xs">
            {{ substr($user->name, 0, 1) }}
        </span>
    </div>
@else
    <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
        <span class="text-gray-600 font-semibold text-xs">?</span>
    </div>
@endif