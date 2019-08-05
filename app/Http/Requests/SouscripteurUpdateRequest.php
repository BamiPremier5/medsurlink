<?php

namespace App\Http\Requests;

use App\Rules\EmailExistRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SouscripteurUpdateRequest extends FormRequest
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
        $id = $this->route()->parameter('souscripteur');

        return [
            "user_id"=>'sometimes|integer|exists:users,id',
            "nom"=>'required|string|min:2',
            "prenom"=>'sometimes|nullable|string|min:2',
            "sexe"=>["required",Rule::in(['M','F'])],
            "date_de_naissance"=>'required|date',
            "nationalite"=>'required|string|min:4',
            "ville"=>'required|string|min:2',
            "pays"=>'required|string|min:2',
            "telephone"=>'required|string|min:9',
            "email"=>['required','string',new EmailExistRule($id,'Souscripteur')],
            "quartier"=>'sometimes|nullable|string|min:1',
            "code_postal"=>'sometimes|integer',
        ];
    }
}
