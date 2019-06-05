<?php

namespace App\Http\Requests\Files;

use Illuminate\Foundation\Http\FormRequest;

class FileCreateRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:40',
            'recipient_id' => 'required|integer|exists:users,id',
            'hash' => 'required|string|size:64',
            'price' => 'nullable|numeric|has_bitcoin_address|min_decimal:'.minPrice()
        ];
    }
}
