<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReceberAvaliacaoRequest extends FormRequest
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
            'atendimento' =>'required',
            'avaliacao' =>'required|max:10',
            'comentario' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'atendimento.required' => 'O atendimento é obrigatório.',
            'avaliacao.required' => 'O campo avaliacao é obrigatório.',
            'avaliacao.max' => 'O campo avaliacao não pode ser maior que 10.',
            'comentario.required' => 'O campo :attribute não pode ser vazio.',
        ];
    }


    public function withValidator($validator){
        if($validator->fails()){
            throw new HttpResponseException(response()->json([
                'msg' => 'Ocorreu um erro!',
                'status' => false,
                'errors' => $validator->errors(),
            ], 400));
        }
    }

}
