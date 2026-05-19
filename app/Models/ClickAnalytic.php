<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClickAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'item_type',
        'item_id',
        'page_url',
        'user_agent',
        'clicked_at',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class, 'item_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'id');
    }
}
