<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostApproval extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',   // người thực hiện hành động (từ chối hoặc duyệt)
        'reason',    // lý do từ chối
    ];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
