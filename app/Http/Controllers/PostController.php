<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Console\View\Components\Warn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Nullable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PostUpdateRequest;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        //query tất cả bài viết, lấy mới nhất trước
        //nếu chưa đăng nhập thì hiển thị tất cả bài viết mới nhất
        $query = Post::published() // Chỉ lấy posts đã publish
            ->with(['user', 'media'])
            ->withCount('claps')
            ->latest('published_at'); // Sort theo published_at thay vì created_at

        $user = Auth::user(); //Lấy ra user hiện đang đăng nhập
        if($user)//nếu user có đăng nhập
        {
            $ids = $user->following()->pluck('users.id'); //lấy ra những user_id mà user đăng nhập hiện tại đang follow
            $query->whereIn('user_id', $ids); //thêm điều kiện chỉ lấy ra những bài viết được viết bởi user_id đó
        }


        $posts = $query->paginate(10);
        return view('post.index', [ 
            'posts'=>$posts //truyền biến $posts sang view với key là posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('post.create',[
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        // Nếu không có published_at, set là thời điểm hiện tại
        if (!$data['published_at']) {
            $data['published_at'] = now();
        }

        $post = Post::create($data);

        if ($request->hasFile('image')) {
            $post->addMediaFromRequest('image')
                ->toMediaCollection();
        }

        return redirect()->route('dashboard')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $username, Post $post)
    {
            return view('post.show', [
                'post' => $post,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Kiểm tra quyền: chỉ author mới được edit
        if($post->user_id !== Auth::id())
        {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::get();
        return view('post.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        //Kiểm tra quyền: chỉ author mới được edit
        if($post->user_id !== Auth::id())
        {
            abort(403, 'Unauthorized action.');
        }
        
        $data = $request->validate([
            'title' =>'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'published_at' => 'nullable|datetime',
            'image' => 'nullable|image|mimes:webp,jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // tạo slug mới nếu title thay đổi
        if($data['title'] !== $post->title) {
            $baseSlug= Str::slug($data['title']);
            $slug= $baseSlug;
            $counter = 1;
            
            while(Post::where('slug', $slug)->where('id', '!=', $post->id)->exists())
            {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $data['slug'] = $slug;
        }

        $post->update($data);

        //xử lí ảnh mới nếu có
        if($request->hasFile('image'))
        {
            //xóa ảnh cũ
            $post->clearMediaCollection();

            //thêm ảnh mới
            $post->addMediaFromRequest('image')
                 ->toMediaCollection();
        }

        return redirect()->route('post.show', ['username' => $post->user->username, 'post' => $post->slug])
                     ->with('success', 'Post updated successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Kiểm tra quyền: chỉ author mới được xóa
        if($post->user_id !== Auth::id())
        {
            abort(403, 'Unauthorized Action.');
        } 

        // Xóa claps liên quan
        $post->claps()->delete();

        // Xóa tất cả media liên quan
        $post->clearMediaCollection();

        // Xóa post
        $post->delete();

        return redirect()->route('my.posts')->with('success', 'Post deleted successfully!');
    }

    public function category(Category $category)
    {
        $posts = $category->posts()
            ->published() // Chỉ lấy posts đã publish
            ->with(['user', 'media'])
            ->withCount('claps')
            ->latest('published_at')
            ->simplePaginate(5);

        return view('post.index', [
            'posts' => $posts,
        ]);
    }

    function myPosts()
    {
        $posts = Auth::user()->posts()
            ->with(['user', 'media'])
            ->withCount('claps')
            ->latest('created_at') // Sort theo created_at để thấy tất cả posts
            ->paginate(10);
        return view('post.my-posts', compact('posts'));
    }
}

