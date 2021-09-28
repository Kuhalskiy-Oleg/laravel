<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginationFormRequest extends FormRequest
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
            'page'    => 'numeric|regex:/^[0-9]*$/|min:1',
            'per_page' => 'numeric|regex:/^[0-9]*$/|max:100|min:1',
        ];
    }


    public function messages()
    {
        return [
            'page.numeric'  => 'номер страницы должен быть числом',
            'page.regex'    => 'номер страницы должен быть числом без символов',
            'page.min'    => 'номер страницы должен быть >= 1',
            'per_page.numeric'  => 'передаваемое кол-во элементов на странице должно быть числом',
            'per_page.regex'    => 'передаваемое кол-во элементов на странице должно быть числом без символов',
            'per_page.min'  => 'передаваемое кол-во элементов на странице должно быть >= 1',
        ];
    }
}
