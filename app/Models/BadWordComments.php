<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class BadWordComments extends Model
{
    protected $fillable = ['word'];

    public function comments()
    {
        return $this->belongsToMany(Comments::class);
    }
}

