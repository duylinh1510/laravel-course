<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ClapController extends Controller
{
    public function clap(Post $post)
    {
        // Kiểm tra user đã clap chưa
        $existingClap = $post->claps()->where('user_id', Auth::id())->first();
        
        if ($existingClap) {
            // Đã clap → xóa clap (unclap)
            $existingClap->delete();
        } else {
            // Chưa clap → tạo clap mới
            $post->claps()->create([
                'user_id' => Auth::id(),
            ]);
        }

        return response()->json([
            'count' => $post->claps()->count(),
        ]);
    }
}
