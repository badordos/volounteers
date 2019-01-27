<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStepThree extends FormRequest
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
            'steps.*.title' => 'string|min:3|max:120',
            'steps.*.description' => 'string|min:3|max:1000',
            'steps.*.vote.title' => 'string|min:3|max:120',
            'steps.*.vote.description' => 'string|min:3|max:1000',
            'steps.*.vote.variants.*' => 'string|min:1|max:60',
        ];
    }
}
