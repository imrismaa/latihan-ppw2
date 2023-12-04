<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingModel extends Model
{
    use HasFactory;

    protected $table = 'new_rating';
    protected $fillable = ['id', 'buku_id', 'user_id', 'rating'];

    public function buku():BelongsTo
    {
        return $this->belongsTo(BukuModel::class);
    }
}
