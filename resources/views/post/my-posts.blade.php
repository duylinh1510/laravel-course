<x-app-layout>
    <div class="py-3">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900">
                    <h2 class="text-xl font-semibold mb-4">My Posts</h2>
                </div>
            </div>
            <div class="mt-8 text-gray-300">
                @forelse ($posts as $post)
                    <x-post-item :post="$post"> </x-post-item>
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