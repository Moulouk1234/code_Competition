<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'articl_id', 'liked'];

    // Define relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articl(): BelongsTo
    {
        return $this->belongsTo(articl::class);
    }
}
