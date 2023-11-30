<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingModel extends Model
{
    use HasFactory;

    protected $table = 'rating';

    protected $fillable = [
        'user_id', 
        'buku_id', 
        'rating'
    ];

    public function buku() {
        return $this->belongsTo(BukuModel::class);
    }
}
