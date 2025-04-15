<?php

namespace App\Http\Requests;

class ResellerRegsiterRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name'=>'required|string',
            'email'=>'required|email|unique:resellers,email',
            'adresse'=>'',
            'credit'=>'numeric',
            'dette'=>'numeric',
        ];
    }
}
