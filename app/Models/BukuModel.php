<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BukuModel extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $fillable = [
        'judul',
        'penulis',
        'harga',
        'tgl_terbit',
        'filename',
        'filepath'
    ];

    public function galeri(): HasMany
    {
        return $this->hasMany(GaleriModel::class, 'buku_id');
    }

    public function ratings()
    {
        return $this->hasMany(RatingModel::class, 'buku_id', 'id');
    }

    public function averageRating()
    {
        return $this->ratings->avg('rating');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'new_favorites', 'buku_id', 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ReviewModel::class, 'buku_id', 'id');
    }
}
