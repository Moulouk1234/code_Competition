<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socialwork extends Model
{
    use HasFactory;


    protected $fillable = ['icon', 'link'];
}
