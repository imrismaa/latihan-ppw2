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

    public function galeri(): HasMany {
        return $this->hasMany(GaleriModel::class, 'buku_id');
    }

    public function rating()
    {
        return $this->hasMany(RatingModel::class);
    }

    public function averageRating()
    {
        return $this->rating->avg('rating');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'buku_id', 'user_id');
    }


    public function getAvgRatingAttribute()
{
    $totalRating = $this->rating_1 + $this->rating_2 + $this->rating_3 + $this->rating_4 + $this->rating_5;
    $jumlahRating = $this->rating_1 + $this->rating_2 + $this->rating_3 + $this->rating_4 + $this->rating_5;

    return ($jumlahRating > 0) ? $totalRating / $jumlahRating : 0;
}
}
