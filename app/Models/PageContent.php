<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class PageContent extends Model
{
    protected $fillable = [
        'page',
        'key',
        'value',
    ];

    public static function pageKeyFromRoute(?string $routeName = null): ?string
    {
        $routeName ??= Route::currentRouteName();

        $page = match ($routeName) {
            'home' => 'home',
            'blog' => 'blog',
            'article.show' => 'article',
            'about' => 'about',
            'team' => 'team',
            'portfolio' => 'portfolio',
            'services', 'profile' => 'profile',
            'search' => 'search',            'occasions' => 'occasions',            default => null,
        };

        if ($page) {
            return $page;
        }

        return match (request()->path()) {
            '/', 'center' => 'home',
            default => null,
        };
    }

    public static function valuesForPage(?string $page): array
    {
        if (!$page || !Schema::hasTable('page_contents')) {
            return [];
        }

        return static::query()
            ->where('page', $page)
            ->pluck('value', 'key')
            ->toArray();
    }

    public static function value(string $key, string $default = '', ?string $page = null): string
    {
        $page ??= static::pageKeyFromRoute();
        $values = static::valuesForPage($page);

        return $values[$key] ?? $default;
    }
}
