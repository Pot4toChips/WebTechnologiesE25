<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipePost extends Model
{
    use HasFactory;

    protected $keyType = 'int';
    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
    ];

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'title',
        'author_id',
        'image',
        'ingredients',
        'instructions',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
