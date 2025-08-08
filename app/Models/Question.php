<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['title', 'details', 'tried', 'expected', 'id_tag', 'count', 'file_path', 'user_id', 'category_id']; // Add 'category_id' to the fillable array

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
    public function badWords(): BelongsToMany
    {
        return $this->belongsToMany(BadWord::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category() // Define the relationship with Category
    {
        return $this->belongsTo(Category::class);
    }

}
