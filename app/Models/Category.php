<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ur',
        'image',
        'status',
    ];

    protected $appends = ['emoji'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getEmojiAttribute(): string
    {
        $name = strtolower($this->name_en ?? $this->name_ur ?? '');

        if (str_contains($name, 'pizza')) {
            return '🍕';
        }

        if (str_contains($name, 'burger')) {
            return '🍔';
        }

        if (str_contains($name, 'shawarma') || str_contains($name, 'roll')) {
            return '🌯';
        }

        if (str_contains($name, 'pasta')) {
            return '🍝';
        }

        if (str_contains($name, 'fries') || str_contains($name, 'chips')) {
            return '🍟';
        }

        if (str_contains($name, 'wing') || str_contains($name, 'chicken')) {
            return '🍗';
        }

        if (str_contains($name, 'drink') || str_contains($name, 'shake')) {
            return '🥤';
        }

        if (str_contains($name, 'deal') || str_contains($name, 'combo')) {
            return '🏷️';
        }

        return '🍽️';
    }
}
