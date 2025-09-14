<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 1. Gán dữ liệu hợp lệ từ request vào model User hiện tại
        // $request->validated() → lấy dữ liệu sau khi đã qua validate từ ProfileUpdateRequest.
        // $request->user() → trả về user đang đăng nhập (thông qua Auth).
        // fill(...) → gán các field hợp lệ vào model User (nhưng chưa lưu ngay).
        
        $data=$request->validated();
        $image=$data['image'] ?? null;

        if($image){
            // lưu file image vào thư mục storage/app/public/avatars
            $data['image'] = $image->store('avatars', 'public');
        }
        
        // gán dữ liệu đã xử lý vào user
        Auth::user()->fill($data);

        // 2. Kiểm tra xem email có thay đổi không
        // Nếu email đổi → set lại email_verified_at = null để buộc user phải xác minh lại email mới.
        if (Auth::user()->isDirty('email')) {
            Auth::user()->email_verified_at = null;
        }

        // 3. Lưu thông tin vào database
        Auth::user()->save();
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
