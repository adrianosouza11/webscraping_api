<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class WebScrapingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'urlOrigin' => ['required','string','regex:/https?:\/\//i'],
            'slc_title' => 'string',
            'slc_description' => 'string',
            'slc_price' => 'string',
            'slc_image' => 'string'
        ];
    }

    public function messages() : array
    {
        return [
          'required' => "O parâmetro ':attribute' é obrigatório",
          'regex' => "O parâmetro ':attribute' deve ser válido, sendo uma URL válida",
        ];
    }


}
