<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

class MenuPermissionRequest extends FormRequest
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
        if (strtolower(request()->method()) === 'post') {
            $this->authorize('create', Permission::class);
        } else {
            $this->authorize('update', $this->permission);
        }
        

        return [
            'name' => 'required|string|max:255|regex:/^[_a-zA-Z]+$/'
        ];
    }
}
