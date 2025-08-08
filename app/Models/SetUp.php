<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class SetUp extends Model
{
    protected $table = 'setups';

    protected $fillable = ['title','description'];

   
}

