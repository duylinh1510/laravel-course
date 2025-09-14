<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class FollowerController extends Controller
{


    // $user → đây là người sẽ được follow hoặc unfollow (truyền từ route).
    // $user->followers() → quan hệ followers() trong model User, tức là danh sách những người đang follow $user.
    // toggle(Auth::user()) → Eloquent sẽ kiểm tra:
    // Nếu bản ghi (user_id = $user->id, follower_id = Auth::id()) chưa tồn tại → nó sẽ thêm mới (follow).
    // Nếu đã tồn tại → nó sẽ xóa đi (unfollow).
    // Nếu bản ghi quan hệ chưa tồn tại → toggle() sẽ thêm (insert vào bảng pivot).
    // Nếu bản ghi quan hệ đã tồn tại → toggle() sẽ xóa (delete khỏi bảng pivot).
    public function followUnfollow(User $user)
    {
        $user->followers()->toggle(Auth::user());

        return response()->json([
            'followersCount' => $user->followers()->count(),
        ]);
    }
}
