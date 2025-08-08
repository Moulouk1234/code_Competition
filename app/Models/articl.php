<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class articl extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description','contenu','category_id'];
    public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function tags()
      {return $this->belongsToMany(tag::class);}


      public function Comments()
      {
          return $this->hasMany(comments::class);
      }
      public function likes()
      {return $this->belongsToMany(Like::class);}

}
