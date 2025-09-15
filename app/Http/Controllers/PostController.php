<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Nullable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        //query tất cả bài viết, lấy mới nhất trước
        //nếu chưa đăng nhập thì hiển thị tất cả bài viết mới nhất
        $query = Post::latest(); 

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

        // $image = $data['image'];
        // unset($data['image']);
        $data['user_id'] = Auth::id();

        // $imagePath = $image->store('posts', 'public');
        // $data['image'] = $imagePath;

        $post = Post::create($data);

        $post->addMediaFromRequest('image')
            ->toMediaCollection();

        return redirect()->route('dashboard');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function category(Category $category)
    {
        $posts = $category->posts()->latest()->simplePaginate(5);

        return view('post.index', [
            'posts' => $posts,
        ]);
    }
}

