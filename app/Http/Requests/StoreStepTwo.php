<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStepTwo extends FormRequest
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
            'main_image' => 'image | mimes:jpeg,jpg,png,bmp,gif, | max:10000',
            'main_video_preview' => 'image | mimes:jpeg,jpg,png,bmp, | max:10000',
            'main_video' => 'mimes:mp4,webm, | max:50000',
            'files.*' => 'image | mimes:jpeg,jpg,png,bmp,gif, | max:10000',
        ];
    }
}
