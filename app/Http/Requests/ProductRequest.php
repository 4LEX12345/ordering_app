<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'brand_id' => ['required', 'numeric'],
            'product_category_id' => ['required', 'numeric'],
            'flavor_id' => ['required', 'numeric'],
            // 'image' => ['required','image','mimes:jpeg,png,jpg,gif','max:2048'],
        ];
    }

    public function messages(){
        return [
            'name.required' => 'The product name is required.',
            'description.required' => 'Please provide a description for the product.',
            'brand_id.required' => 'You must select a brand.',
            'brand_id.numeric' => 'The brand ID must be a number.',
            'product_category_id.required' => 'You must select a product category.',
            'product_category_id.numeric' => 'The product category ID must be a number.',
            'flavor_id.required' => 'You must select a flavor.',
            'flavor_id.numeric' => 'The flavor ID must be a number.',
            // 'image.required' => 'An image is required for the product.',
            // 'image.image' => 'The uploaded file must be an image.',
            // 'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            // 'image.max' => 'The image must not be greater than 2MB.',
        ];
    }
}
