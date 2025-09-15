<x-app-layout>
    <div class="py-4">
        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-6 py-8">
            <article class="max-w-3xl mx-auto">
                <!-- Title -->
                <header class="mb-8">
                    <h1 class="text-2xl mb-4">
                        {{ $post->title }}
                    </h1>
                    <!-- Author Info -->
                    <div class="flex items-center space-x-4 pb-6 border-b border-gray-200">
                        <!-- Author Avatar-->
                        <x-user-avatar :user="$post->user"/>
                        <!-- Author Details -->
                        <div>
                            <a href="{{ route('profile.show', $post->user)}}" class=" hover:underline text-lg font-semibold text-gray-900">{{ $post->user->name }}</a>
                            <div class="flex items-center text-lg text-gray-500 space-x-2">
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                                <span>·</span>
                                <span>{{ $post->readTime() }} min read</span>
                            </div>  
                        </div>
                        @auth
                            @if(auth()->id() !== $post->user->id)
                            <x-follow-ctr :user="$post->user">
                                <button 
                                    @click="follow()" 
                                    x-text="following ? 'Unfollow' : 'Follow'"
                                    :class="following 
                                        ? 'bg-red-600 hover:bg-red-700' 
                                        : 'bg-green-600 hover:bg-green-700'"
                                    class="px-4 py-2 text-white text-sm font-medium rounded-full transition-colors duration-200">
                                </button>
                            </x-follow-ctr>
                            @endif
                        @endauth
                    </div>
                    <!-- Edit, Delete Post -->
                    <div class="mt-8">
                        <x-post-actions :post="$post" />
                    </div>
                    <div class="flex items-center space-x-6 border-b border-t border-gray-200">
                        <!-- Clap Button -->
                        <x-clap-button :post="$post"/>
                        <!-- Comment Button -->
                        <x-comment-button :post="$post"/>
                    </div>
                </header>
                <!-- Featured Image (nếu có) -->
                @if ($post->getFirstMedia())
                <div class="mb-8">
                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="w-full h-auto rounded-lg shadow-sm">
                </div>
                @endif

                <!-- Article Content -->
                <div class="mb-12">
                    <div class="text-gray-900 leading-relaxed text-lg">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
                <!-- Category Section -->
                <div class="mb-8">
                    @if($post->category)
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-block bg-white border border-gray-300 hover:border-gray-400 text-gray-700 px-4 py-2 rounded-full text-sm font-medium transition-all cursor-pointer">
                            {{ $post->category->name }}
                        </span>
                    </div>
                    @endif
                </div>
                <!-- Clap Section -->
                <div class="border-t border-gray-200 pt-8 mt-8">
                    <div class="flex items-center justify-between">
                        <!-- Left side: Clap & Comment -->
                        <div class="flex items-center space-x-6">
                            <!-- Clap Button -->
                            <x-clap-button :post="$post"/>
                            <!-- Comment Button -->
                            <x-comment-button :post="$post"/>
                        </div>
                        
                        <!-- Right side: Share & Bookmark -->
                        <div class="flex items-center space-x-2">
                            <!-- Bookmark -->
                            <x-bookmark-button/>
                            <!-- Share -->
                            <x-share-button/>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>

<!-- Comments Section -->
<div class="max-w-4xl mx-auto px-6 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="border-t border-gray-200 pt-8">
            <h3 class="text-xl font-semibold mb-6">
                Responses ({{ $post->allComments()->count() }})
            </h3>

            <!-- Comment Form -->
            @auth
            <div class="mb-8">
                <form action="{{ route('comment.store', $post) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex space-x-3">
                        <x-user-avatar :user="auth()->user()" size="w-10 h-10" />
                        <div class="flex-1">
                            <textarea name="content" 
                                      placeholder="What are your thoughts?" 
                                      class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition">
                            Respond
                        </button>
                    </div>
                </form>
            </div>
            @endauth

            @guest
            <div class="mb-8 text-center py-8">
                <p class="text-gray-600 mb-4">Sign in to respond to this story.</p>
                <a href="{{ route('login') }}" 
                   class="px-6 py-2 bg-gray-900 text-white rounded-full hover:bg-gray-800 transition">
                    Sign In
                </a>
            </div>
            @endguest

            <!-- Comments List -->
            <div class="space-y-6">
                @forelse($post->comments as $comment)
                    <x-comment-item :comment="$comment" :post="$post" />
                @empty
                    <div class="text-center text-gray-400 py-8">
                        <p>No responses yet. Be the first to respond!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Reply Forms -->
<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('hidden');
    
    if (!form.classList.contains('hidden')) {
        form.querySelector('textarea').focus();
    }
}
</script>
</x-app-layout>
