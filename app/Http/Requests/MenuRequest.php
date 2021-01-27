<?php

namespace App\Http\Requests;

use App\Models\Menu;
use App\Rules\PathRule;
use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'parent_id' => 'nullable|numeric|gte:1',
            'name' => 'required|string|max:255|regex:/^[ a-zA-Z0-9]+$/',
            'icon' => 'nullable|string|max:255',
            'path' => 'required|string|max:255',
            'has_sub_menus' => 'required|string|in:Y,N'
        ];

        $menuId = null;
        if ($this->menu) {
            $this->authorize('update', $this->menu);

            $menuId = $this->menu->id;
            $rules['path'] .= ',' . $this->menu->id;
            $rules['path'] = str_replace('required', 'nullable', $rules['path']);
        } else {
            $this->authorize('create', Menu::class);
        }

        $rules['path'] = explode('|', $rules['path']);
        $rules['path'][] = new PathRule($this->request->get('parent_id'), $menuId);

        return $rules;
    }
}
