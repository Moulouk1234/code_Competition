<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateQuestion extends Model
{
    protected $fillable = ['user_id', 'answer_id', 'question_id', 'rating'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
