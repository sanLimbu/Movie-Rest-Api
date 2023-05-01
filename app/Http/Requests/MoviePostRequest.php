<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class MoviePostRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'user_id'       => 'required|integer|exists:users,id',
            'description'   => 'required|string',
            'image'         => 'required|string|max:255',
            'release_date'  => 'required|date',
            'rating'        => 'required|string|max:255',
            'award_winning' => 'required|boolean',
            'genres'        => 'required|array',
            'genres.*'      => 'integer|exists:genres,id',
            'actors'        => 'required|array',
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
