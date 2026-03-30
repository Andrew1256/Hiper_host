<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'title'      => ['required', 'string', 'min:5', 'max:70'],
            // Slug is immutable after publication — skip unique check if same value
            'slug'       => ['nullable', 'string', 'alpha_dash', Rule::unique('posts', 'slug')->ignore($post->id)],
            'body'       => ['required', 'string'],
            'image'      => ['nullable', 'image', 'max:2048'],
            'image_url'  => ['nullable', 'url'],
            'status'     => ['required', Rule::in([Post::STATUS_DRAFT, Post::STATUS_SCHEDULED, Post::STATUS_PUBLISHED])],
            'publish_at' => ['nullable', 'date', 'after:now', Rule::requiredIf($this->input('status') === Post::STATUS_SCHEDULED)],
        ];
    }
}
