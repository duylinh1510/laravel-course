<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'image',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('avatar')
            ->width(128)
            ->crop(128, 128);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    //quan hệ Many-to-Many giữa nhiều User
    //Tức là một user có thể theo dõi nhiều user khác, và một user có thể được nhiều user theo dõi.
    //followers là bảng trung gian (pivot)
    //user_id là foreign key trong bảng pivot trỏ đến user được theo dõi.
    //follower_id là foreign key trong bảng pivot trỏ đến user đi theo dõi (follower).
    public function following() //->Lấy danh sách người mình theo dõi
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }


    //lấy danh sách người đang theo dõi mình.
    //user_id là chính mình
    //follower_id là những người theo dõi mình
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function imageUrl()
    {
        $media = $this->getFirstMedia('avatar');
        if (!$media) {
            return null;
        }
        if ($media->hasGeneratedConversion('avatar')) {
            return $media->getUrl('avatar');
        }
        return $media->getUrl();
    }

    public function isFollowedBy(?User $user)
    {
        if(!$user)
        {
            return false;
        }
        // gọi tới quan hệ followers(), danh sách những người đang theo dõi $this user.
        // kiểm tra xem trong tập hợp (collection) này có chứa $user được truyền vào không.
        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    // kiểm tra xem người dùng đã clap bài viết này chưa
    // nếu có thì trả về true, nếu không thì trả về false
    public function hasClapped(Post $post)
    {
        return $post->claps()->where('user_id', $this->id)->exists();
    }
}
