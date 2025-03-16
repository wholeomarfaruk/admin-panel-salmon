<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreDataRequest extends FormRequest
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

        // dd($this->all());

        return [
            'title' => ['required', 'string'],
            'sub_title' => ['nullable', 'string'],
            'status' => ['required', 'integer', 'in:0,1'], // Replace 0,1 with valid status values
            'type' => ['required', 'integer', 'in:1,2'], // 1: Commercial, 2: Residential
            'start_date' => ['nullable', 'date'],
            'target_to_complete_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'location' => ['nullable', 'string', 'max:255'],
            'landmark_lat_long' => ['nullable', 'json'],
            'project_extra_data' => ['nullable', 'json'],
            'videos' => ['nullable', 'json'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,gif,webp', 'max:2048'],
            'images' => ['sometimes', 'array'],
            'images.*' => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ];
    }
}
