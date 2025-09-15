<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasSlug;

    // $fillable là một mảng danh sách các cột (fields) trong bảng database được cho phép gán hàng loạt (Mass Assignment).
    // Giúp bạn có thể dùng $model->create([...]) hoặc $model->update([...]) mà không sợ bị lỗi bảo mật.
    protected $fillable = [
        // 'image',
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'published_at',
    ];

    // Thêm thuộc tính này
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate(); // Không tự động update slug khi edit
    }

    //Đây là hook method được Spatie gọi mỗi khi bạn thêm media vào model.
    //Bạn định nghĩa ở trong model (ví dụ Post, User) để khai báo các conversions (phiên bản đã xử lý) của file.
    public function registerMediaConversions(?Media $media = null): void
    {   
        //Tạo một conversion mới, tên là preview.
        //Conversion = phiên bản file được xử lý từ file gốc (ví dụ resize, crop, watermark...).
        //Sau khi khai báo, mỗi khi bạn upload file, Media Library sẽ tự động sinh thêm file preview dựa trên media gốc.
        $this
            ->addMediaConversion('avatar')
            //Đặt chiều rộng cho ảnh conversion là 400px.
            //Chiều cao sẽ được tính tự động để giữ tỉ lệ.
            ->crop(128,128);
            //Mặc định conversions được chạy bằng queue (để không chặn request người dùng).
            //Nhưng với nonQueued(), conversion sẽ được xử lý ngay lập tức khi file được upload.
            //Thường dùng cho những conversion quan trọng cần có sẵn ngay (ví dụ ảnh thumbnail).
        $this
            ->addMediaConversion('large')
            ->width(1200);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function claps()
    {
        return $this->hasMany(Clap::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->parent()->with('user', 'replies');
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class);
    }

    public function readTime($wordsPerMinute=200)
    {
        // 1. Đếm số từ trong content (sau khi bỏ hết thẻ HTML)
        // strip_tags($this->content) → bỏ hết thẻ HTML để chỉ còn lại chữ.
        // str_word_count(...) → đếm số lượng từ.
        $wordCount = str_word_count(strip_tags($this->content)); 

        // 2. Mặc định tốc độ đọc là 200 từ/phút (thông số phổ biến).
        // ceil(...) → làm tròn lên (nếu 201 từ thì tính thành 2 phút thay vì 1.005 phút).
        $minutes = ceil($wordCount/$wordsPerMinute); 

         // 3. Trả về số phút đọc, nếu ít hơn 1 phút thì hiện 1 p
        return max(1, $minutes);
    }

    // định nghĩa hàm lấy Url của ảnh
    public function imageUrl($conversionName = '') // $conversionName là tên của conversion (ví dụ: "preview", "thumb") → mặc định rỗng ('').
    {
        # lấy ra model đầu tiên gắn với model hiện tại (collection mặc định)
        $media = $this->getFirstMedia();
        if (!$media) {
            return null; # Nếu model chưa có ảnh->$medial=null
        }

        //Kiểm tra xem media có conversion với tên $conversionName không.
        if ($media->hasGeneratedConversion($conversionName)) {
            return $media->getUrl($conversionName); //Nếu có conversion → trả về URL của file conversion đó.
        }
        return $media->getUrl(); // Nếu không có conversion → fallback về URL file gốc.
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    public function scopeScheduled($query)
    {
        return $query->where('published_at', '>', now());
    }

    // Helper method để kiểm tra post đã publish chưa
    public function isPublished()
    {
        return $this->published_at <= now();
    }

    public function isScheduled()
    {
        return $this->published_at > now();
    }
}
