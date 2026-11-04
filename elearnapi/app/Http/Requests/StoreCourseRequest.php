<?php
// app/Http/Requests/StoreCourseRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'teacher_id'  => ['required', 'exists:users,id'],
            'pdf'         => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}