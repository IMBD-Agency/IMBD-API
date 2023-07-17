<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class AppActivityStoreRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'app_name' => 'required|string',
            'duration' => 'required|decimal:1,9',
        ];
    }
}