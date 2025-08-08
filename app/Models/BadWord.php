<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class BadWord extends Model
{
    protected $fillable = ['word'];

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
}

