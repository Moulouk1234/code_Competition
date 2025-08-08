<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoliciyCategory extends Model
{
    protected $fillable = ['name'];

    public function privacyPolicies()
    {
        return $this->hasMany(PrivacyPolicy::class, 'category_id');
    }


}
