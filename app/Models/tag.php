<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class tag extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];
    public function categories()
    {return $this->belongsToMany(Category::class);}

    public function articls()
    {return $this->belongsToMany(articl::class);}

    public function questions()
    {return $this->belongsToMany(Question::class);}

}
