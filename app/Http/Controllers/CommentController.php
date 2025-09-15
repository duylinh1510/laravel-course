<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = $post->allComments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Response added successfully!');
    }

    public function destroy(Comment $comment)
    {
        // Chỉ cho phép user xóa comment của chính họ hoặc Author của post
        if($comment->user_id !== Auth::id() && $comment->post->user_id !== Auth::id())
        {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return back()->with('success', 'Response deleted successfully!');
    }
}
