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
        return $this->hasMany(GaleriModel::class);
    }
}
