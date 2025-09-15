@props(['comment', 'post'])

<div class="border-l-2 border-gray-100 pl-4 mb-6">
    <!-- Comment Header -->
    <div class="flex items-center space-x-3 mb-3">
        <x-user-avatar :user="$comment->user" size="w-8 h-8" />
        <div>
            <a href="{{ route('profile.show', $comment->user) }}" 
               class="font-medium text-gray-900 hover:underline">
                {{ $comment->user->name }}
            </a>
            <div class="text-sm text-gray-500">
                {{ $comment->created_at->diffForHumans() }}
            </div>
        </div>
        
        <!-- Delete Button -->
        @auth
            @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
            <form action="{{ route('comment.destroy', $comment) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this response?')"
                  class="ml-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-gray-400 hover:text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
            @endif
        @endauth
    </div>

    <!-- Comment Content -->
    <div class="text-gray-700 mb-4 leading-relaxed">
        {!! nl2br(e($comment->content)) !!}
    </div>

    <!-- Reply Button -->
    @auth
    <button onclick="toggleReplyForm({{ $comment->id }})" 
            class="text-sm text-gray-500 hover:text-gray-700 mb-4">
        Reply
    </button>
    @endauth

    <!-- Reply Form (Hidden by default) -->
    @auth
    <div id="reply-form-{{ $comment->id }}" class="hidden mb-4">
        <form action="{{ route('comment.store', $post) }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="content" 
                      placeholder="Write a response..." 
                      class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      rows="3" required></textarea>
            <div class="flex justify-end space-x-2">
                <button type="button" 
                        onclick="toggleReplyForm({{ $comment->id }})"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Respond
                </button>
            </div>
        </form>
    </div>
    @endauth

    <!-- Nested Replies -->
    @if($comment->replies->count() > 0)
    <div class="mt-4 space-y-4">
        @foreach($comment->replies as $reply)
            <x-comment-item :comment="$reply" :post="$post" />
        @endforeach
    </div>
    @endif
</div>