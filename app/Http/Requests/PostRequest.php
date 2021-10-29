<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:200',
            'body'  => 'required',
            'category_id' => 'required|integer|exists:categories,id',
            'tags' => 'array|exists:tags,id'
        ];
    }

    public function messages () {

        return [
            'title.required' => 'Title tidak boleh kosong'
        ];
    }
}
