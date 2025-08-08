<?php


namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\PoliciyCategory;

class UniqueCategoryName implements Rule
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function passes($attribute, $value)
    {
        return PoliciyCategory::where('name', $value)
            ->when($this->categoryId, function ($query) {
                $query->where('id', '!=', $this->categoryId);
            })
            ->doesntExist();
    }

    public function message()
    {
        return 'The category name is already taken.';
    }
}
