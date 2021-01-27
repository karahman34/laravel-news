<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            $this->authorize("create", User::class);
        } else {
            $this->authorize("update", $this->user);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255|confirmed'
        ];

        if ($this->user) {
            $rules['email'] .= ',' . $this->user->id;
            $rules['password'] = str_replace('required', 'nullable', $rules['password']);
        }

        return $rules;
    }
}
