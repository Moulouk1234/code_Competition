<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tip extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description','contenu','category_id','rate'];
    protected $attributes = [
        'rate' => 0 // Définition de la valeur par défaut pour 'count'
    ];
    public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}
}
