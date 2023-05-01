<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class MovieUpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'          => 'string|max:255',
            'description'   => 'string',
            'image'         => 'string|max:255',
            'release_date'  => 'date',
            'rating'        => 'string|max:255',
            'award_winning' => 'boolean',
            'genres'        => 'array',
            'genres.*'      => 'integer|exists:genres,id',
            'actors'        => 'array',
            'actors.*'      => 'integer|exists:actors,id',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}
