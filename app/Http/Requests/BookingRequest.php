<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            'item_id' => 'required|integer|exists:item_id',  
            'user_id' => 'required|integer|exists:user_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ];
    }
}
