<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['nom', 'description','count'];

    protected $attributes = [
        'count' => 0 // Définition de la valeur par défaut pour 'count'
    ];
    public function articls()
    {
        return $this->hasMany(articl::class);
    }
    public function tags()
    {
        return $this->belongsToMany(tag::class);
    }
    public function tips()
    {
        return $this->hasMany(Tip::class);
    }
    public function questions() // Define the relationship with Question
    {
        return $this->hasMany(Question::class);
    }
}
