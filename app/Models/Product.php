<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'advantages',
        'disadvantages',
        'price',
        'image',
        'affiliate_link',
        'category_id',
        'occasion_id',
        'views',
        'status',
        'user_id',
        'approval_status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function occasion()
    {
        return $this->belongsTo(Occasion::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
