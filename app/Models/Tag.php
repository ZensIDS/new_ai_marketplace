<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'related_keywords'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag');
    }

    public function getRelatedKeywordsArrayAttribute(): array
    {
        return collect(preg_split('/[,\n]+/', (string) $this->related_keywords))
            ->map(fn ($keyword) => trim($keyword))
            ->filter()
            ->values()
            ->all();
    }
}
