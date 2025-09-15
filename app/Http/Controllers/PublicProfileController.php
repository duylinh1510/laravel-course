<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {   
        $posts = $user->posts()
            ->published() // Chỉ hiển thị posts đã publish
            ->latest('published_at')
            ->paginate();

        // check xem hiện tại user đang có bao nhiêu followers
        // dd($user->followers);

        return view('profile.show',[
            'user' => $user,
            'posts'=> $posts
        ]);
    }
}
