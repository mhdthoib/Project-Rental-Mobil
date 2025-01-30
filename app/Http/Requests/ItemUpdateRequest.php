<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type_id' => 'required|integer|exists:types,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'photos' => 'required|array',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'feature' => 'nullable|string',
            'price' => 'nullable|numeric',
            'star' => 'nullable|numeric',
            'review' => 'nullable|numeric',
        ];
    }
}
