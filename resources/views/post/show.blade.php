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
                    <div class="flex items-center space-x-6">
                        <!-- Clap Button -->
                        <x-clap-button :post="$post"/>
                        <!-- Comment Button -->
                        <x-comment-button/>
                    </div>
                </header>
                <!-- Featured Image (nếu có) -->
                @if ($post->image)
                <div class="mb-8">
                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto rounded-lg shadow-sm">
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
                            <x-comment-button/>
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
</x-app-layout>
