<x-app-layout>
    <div class="py-3">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h2 class="text-xl font-semibold mb-6">Edit Post</h2>
                
                <form action="{{ route('post.update', $post) }}" enctype="multipart/form-data" method="post">
                    @csrf 
                    @method('PUT')
                    
                    <!-- Current Image -->
                    @if($post->getFirstMedia())
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="w-48 h-32 object-cover rounded-lg">
                    </div>
                    @endif
                    
                    <!-- New Image -->
                    <div>
                        <x-input-label for="image" :value="__('New Image (optional)')" />
                        <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" autofocus/>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>
                    
                    <!-- Title -->
                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $post->title)" required autofocus/>
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    
                    <!-- Category -->
                    <div class="mt-4">
                        <x-input-label for="category_id" :value="__('Category')" />
                        <select name="category_id" id="category_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)> 
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    </div>
                    
                    <!-- Content -->
                    <div class="mt-4">
                        <x-input-label for="content" :value="__('Content')"/>
                        <x-input-textarea id="content" class="block mt-1" name="content" required>{{ old('content', $post->content) }}</x-input-textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2"/>
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>
                            Update Post
                        </x-primary-button>
                        
                        <a href="{{ route('post.show', ['username' => $post->user->username, 'post' => $post->slug]) }}" 
                           class="text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>