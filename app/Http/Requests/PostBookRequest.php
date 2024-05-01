<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator; // Import Validator
use Illuminate\Http\Exceptions\HttpResponseException; // Import Exception for handling response
use Illuminate\Support\Facades\Log; // Import Log facade

class PostBookRequest extends FormRequest
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
        // @TODO implement DONE:
        return [
            'isbn' => ['bail', 'string', 'required', 'digits:13', 'unique:books,isbn'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'authors' => ['required', 'array'],
            'authors.*' => ['integer', 'exists:authors,id'],
            'published_year' => ['required', 'int', 'between:1900,2020'],
            'price' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
        ];
    }

    public function messages()
    {
        return [
            'isbn.array' => 'ISBN must be an array',
            'isbn.required' => 'ISBN is required',
            'isbn.string' => 'ISBN must be a string',
            'isbn.digits' => 'ISBN must be 13 digits',
            'isbn.unique' => 'ISBN already exists',
            'title.required' => 'Title is required',
            'title.string' => 'Title must be a string',
            'description.required' => 'Description is required',
            'description.string' => 'Description must be a string',
            'authors.required' => 'Authors is required',
            'authors.array' => 'Authors must be an array',
            'authors.*.integer' => 'Authors must be an integer',
            'authors.*.exists' => 'Authors must be exists',
            'published_year.required' => 'Published year is required',
            'published_year.int' => 'Published year must be an integer',
            'published_year.between' => 'Published year must be between 1900 and 2020',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be greater than or equal to 0',
            'price.regex' => 'Price must be in decimal format',
        ];
    }
}
