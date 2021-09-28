<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImgFormRequest extends FormRequest
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
            'image' => 'required|image|max:10240|mimes:jpg,jpeg,png|dimensions:min_width=700,min_height=500'
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'Вы забыли загрузить файл перед его отправкой !!!',
            'image.image' => 'Загружаемый файл должен быть картиркой !',
            'image.max' => 'Загружаемый файл должен должен быть не больше 10 Мегабайт',
            'image.mimes' => 'Поддерживаемые типы изображения: jpg,jpeg,png',
            'image.dimensions' => 'Ошибка в размерах файла ! Минимальная ширина изображения 700px, минимальная высота 500px',
        ];
    }
}
