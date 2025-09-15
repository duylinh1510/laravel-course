<x-app-layout>
    <div class="py-3">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">
                    <h2 class="text-xl font-semibold mb-4">My Posts</h2>
                </div>
            </div>
            <div class="mt-8 text-gray-300">
                @forelse ($posts as $post)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm mb-8 p-5">
                        @if($post->isScheduled())
                            <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium mb-3 inline-block">
                                Scheduled for {{ $post->published_at->format('M d, Y H:i') }}
                            </div>
                        @elseif($post->isPublished())
                            <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium mb-3 inline-block">
                                Published
                            </div>
                        @endif
                        
                        <x-post-actions :post="$post" />
                        <x-post-item :post="$post" />
                    </div>
                @empty
                    <div class="text-center text-gray-400 py-16">
                        <p>You haven't created any posts yet.</p>
                        <a href="{{ route('post.create') }}" class="text-blue-500 hover:underline">Create your first post</a>
                    </div>
                @endforelse
            </div>
            {{ $posts->links() }} 
        </div>
    </div>
</x-app-layout>