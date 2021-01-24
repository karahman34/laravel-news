<?php

namespace App\Http\Requests;

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
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'path' => 'string|max:255|unique:menus,path',
            'has_sub_menus' => 'required|string|in:Y,N'
        ];

        if ($this->menu) {
            $rules['path'] = 'nullable|' . $rules['path'];
            $rules['path'] .= ',' . $this->menu->id;
        } else {
            $rules['path'] = 'required|' . $rules['path'];
        }

        return $rules;
    }
}
