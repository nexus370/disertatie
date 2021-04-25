<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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
            'vat' => 'sometimes|numeric',
            'color' => 'sometimes|string',
            'discount' => ['sometimes'],
            'discount.id' => ['required_with:discounts','numeric', 'exists:discounts,id'],
            'discount.fromDate' => ['required_with:discounts', 'date'],
            'discount.toDate' => ['required_with:discounts', 'date'],
        ];
    }
}
