<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngredientUpdateRequest extends FormRequest
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
            'name' => 'sometimes|string|max:50',
            'stockQuantity' => 'sometimes|numeric',
            'addQuantity' => 'sometimes|numeric',
            'unit.id' => 'sometimes|numeric|exists:units,id',
        ];
    }
}
