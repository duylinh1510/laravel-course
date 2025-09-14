<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    // $fillable là một mảng danh sách các cột (fields) trong bảng database được cho phép gán hàng loạt (Mass Assignment).
    // Giúp bạn có thể dùng $model->create([...]) hoặc $model->update([...]) mà không sợ bị lỗi bảo mật.
    protected $fillable = [
        'image',
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
        'published_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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
}
