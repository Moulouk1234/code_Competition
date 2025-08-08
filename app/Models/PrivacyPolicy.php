<?php

namespace App\Models;
use App\Models\PoliciyCategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivacyPolicy extends Model
{
    protected $fillable = ['content', 'category_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PoliciyCategory::class);
    }

}
