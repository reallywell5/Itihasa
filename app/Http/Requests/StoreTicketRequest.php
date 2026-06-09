<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'museum_id' => 'required|exists:museums,id',
            'ticket_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'slot' => 'required|integer',
        ];
    }
}
