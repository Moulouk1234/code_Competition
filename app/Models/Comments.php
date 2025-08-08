<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comments extends Model
{
    protected $fillable = ['contenu','rate', 'articl_id', 'user_id'];


    public function badWordsComment(): BelongsToMany
    {
        return $this->belongsToMany(BadWordComments::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

}
