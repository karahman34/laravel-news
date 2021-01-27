<?php

namespace App\Rules;

use App\Helpers\MenuHelper;
use App\Models\Menu;
use Illuminate\Contracts\Validation\Rule;

class PathRule implements Rule
{
    private $parentId;
    private $menuId;

    /**
     * Create a new rule instance.
     *
     * @param   string|int $parentId
     * @param   string|int $menuId
     * @return void
     */
    public function __construct($parentId, $menuId = null)
    {
        $this->parentId = $parentId;
        $this->menuId = $menuId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $path = MenuHelper::setMenuPath($value, $this->parentId);

        $exists = Menu::where('path', $path)
                        ->when(!is_null($this->menuId) && strlen($this->menuId) > 0, function ($query) {
                            return $query->where('id', '!=', $this->menuId);
                        })
                        ->exists();

        return !$exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute has been taken.';
    }
}
