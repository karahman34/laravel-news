<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;

class RoleRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255|unique:roles,name'
        ];

        if ($this->role) {
            $this->authorize('update', $this->role);

            $rules['name'] .= ',' . $this->role->id;
        } else {
            $this->authorize('create', Role::class);
        }
        
        return $rules;
    }
}
