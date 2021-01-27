<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:tags,name'
        ];

        if ($this->tag) {
            $this->authorize('update', $this->tag);

            $rules['name'] .= ',' . $this->tag->id;
        } else {
            $this->authorize('create', Tag::class);
        }

        return $rules;
    }
}
