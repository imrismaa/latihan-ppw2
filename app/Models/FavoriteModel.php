<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BukuModel;

class FavoriteModel extends Model
{
    use HasFactory;

    protected $table = 'new_favorites';

    public function buku()
    {
        return $this->belongsTo(BukuModel::class, 'buku_id', 'id');
    }
}
