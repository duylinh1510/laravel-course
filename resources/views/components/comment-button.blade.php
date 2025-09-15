@props(['post'])

<div class="flex items-center space-x-2 text-gray-500 hover:text-gray-700 transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.955 8.955 0 01-4.906-1.476L3 21l2.524-5.094A8.955 8.955 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z"></path>
    </svg>
    <span class="text-sm">{{ $post->allComments()->count() }}</span>
</div>