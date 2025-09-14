<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    // Sử dụng User Factory để tạo 1 user trong bảng users.
    // Factory sẽ lo generate các field còn lại (password, remember_token, …).
    // User này thường để test login.
    public function run(): void
    {
        User::factory()->create([
            'name' => "Test User",
            'username' => "testuser",
            'email' => "test@example.com"
        ]);

        //Tạo 6 bản ghi trong bảng categories.
        // Mỗi category có 1 tên (Technology, Health, …).
        // Giúp có sẵn dữ liệu danh mục để post liên kết vào.
        $categories = [
            'Technology',
            'Health',
            'Science',
            'Sports',
            'Politics',
            'Entertainment',
        ];

        foreach($categories as $category){
            Category::create(['name'=>$category]);
        }

        // Sinh 100 bài post ngẫu nhiên bằng Post Factory.

        // Mỗi bài viết sẽ có title, content, slug, category_id, … theo định nghĩa trong PostFactory.

        // Đây là dữ liệu giả (fake data) để test ứng dụng.
        // Post::factory(100)->create();

    }
}
