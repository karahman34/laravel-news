<?php

namespace App\Http\Requests;

use App\Models\News;
use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'title' => 'required|string|max:255|regex:/^[ a-zA-Z0-9]+$/',
            'summary' => 'required|string|max:150',
            'content' => 'required|string',
            'is_headline' => 'required|string|in:Y,N',
            'banner_image' => 'required|mimes:png,jpg,jpeg|max:4096',
            'status' => 'required|string|in:publish,pending,draft',
            'tags' => 'required|array',
            'tags.*' => 'string|max:255'
        ];

        if ($this->news) {
            $this->authorize('update', $this->news);

            $rules['banner_image'] = str_replace('required', 'nullable', $rules['banner_image']);
        } else {
            $this->authorize('create', News::class);
        }

        return $rules;
    }
}
