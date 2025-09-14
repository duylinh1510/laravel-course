@if($post)
<div class="flex bg-white border border-gray-200 rounded-lg shadow-sm white mb-8">
    <div class="p-5 flex-1">
        <a href="{{ route('post.show', [
        'username'=>$post->user->username, 
        'post'=>$post->slug]) }}">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-700 dark:text-gray-900">{{ $post->title ?? 'Không có tiêu đề' }}</h5>
        </a>
        <div class="mb-3 font-normal text-gray-700 dark:text-gray-900">{{ Str::words($post->content ?? 'Không có nội dung', 15) }}</div>
        <a href="#">
            <x-primary-button>
            Read more
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
            </x-primary-button>
        </a>
    </div>
    <a href="#">
        <img class="w-48 h-full object-cover rounded-r-lg" src="{{ Storage::url($post->image) }}" alt="" />
    </a>
</div>
@else
<div class="text-center text-gray-400 py-8">
    <p>Không thể hiển thị bài viết</p>
</div>
@endif