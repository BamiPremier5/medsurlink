<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToDoListRequest extends FormRequest
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
            "listable_type"=>'required|nullable|string',
            "listable_id"=>'required|nullable|integer',
            "intitule"=>'required|string',
            "description"=>'sometimes|nullable|string',
            "statut"=>'sometimes|nullable|string'
        ];
    }
}
