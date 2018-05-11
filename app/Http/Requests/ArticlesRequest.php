<?php

namespace App\Http\Requests;

use App\Attachment;
use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
{
    protected $dontFlash = ['files'];

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
        return [
            'title' => ['required'],
            'content' => ['required', 'min:10'],
            'tags' => ['required', 'array'], // 'tags' => 'required|array'와 같음
            'files' => ['array'],
            'files.*' => ['mimes:jpg,png,zip,tar', 'max:30000'], // 30,000 KB
        ];
    }

    public function messages(){
        return [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'min' => ':attribute은(는) 최소 :min글자 이상이 필요합니다.'
        ];
    }

    public function attributes(){
        return [
            'title' => '제목',
            'content' => '본문'
        ];
    }

    public function getAttachments()
    {
        return Attachment::whereIn(
            'id',
            $this->input('attachments', [])
        )->get();
    }
}
