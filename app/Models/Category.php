<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'order'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function getCardsCountAttribute()
    {
        return $this->cards()->count();
    }
}
