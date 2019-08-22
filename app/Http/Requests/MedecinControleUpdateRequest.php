<?php

namespace App\Http\Requests;

use App\Rules\EmailExistRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;

class MedecinControleUpdateRequest extends FormRequest
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
        $id = $this->route()->parameter('medecinControle');

        return [
            "specialite_id"=>'required|integer|exists:specialites,id',

            "numero_ordre"=>'required|string|min:2',
            "civilite"=>["required",Rule::in(['M.','Mme/Mlle.','Dr.','Pr.'])],
            "user_id"=>'sometimes|integer|exists:users,id',

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Request::merge(['error'=>$validator->errors()->getMessages()]);
    }
}