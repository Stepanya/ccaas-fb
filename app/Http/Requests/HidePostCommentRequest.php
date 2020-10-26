<?php

namespace App\Http\Requests;

use App\Traits\FbPageTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class HidePostCommentRequest extends FormRequest
{
    use FbPageTrait;

    public function __construct() {
        $this->access_token = '';
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'https://graph.facebook.com/v8.0/']);
        $this->api_key = 'AX3DTdEQKUitb3nb';
    }
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return $this->api_key === $this->header('X-API-KEY');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'page_id' => [
                'required',
                // Check if page_id is valid
                function ($attribute, $value, $fail) {
                    $access_token_check = $this->getPageAccessToken($value);

                    if (!isset($access_token_check)) {
                        $fail('The '. $attribute.' parameter provided is either invalid or the page does not exist.');
                    }
                },
            ],
            'comment_id' => [
                'required',
                // Check if comment_id is valid
                function ($attribute, $value, $fail) {
                    $comment_id = $this->input('comment_id');
                    $this->access_token = $this->getPageAccessToken($this->input('page_id'));

                    if (!isset($this->access_token)) {
                        $fail('No access token has been found for accessing this comment.');
                    } else {
                        $check_comment_id_request = $this->client->get($comment_id.'?access_token='.$this->access_token, ['http_errors' => false]);
                        $check_comment_id_sc = $check_comment_id_request->getStatusCode();
    
                        if ($check_comment_id_sc !== 200) {
                            $check_comment_id_response = json_decode($check_comment_id_request->getBody()->getContents());
                            $fail($check_comment_id_response->error->message);
                        }
                    }
                },
            ]
        ];
    }

    public function messages() {
        return [
            'page_id.required' => 'The page_id parameter is required.',
            'comment_id.required' => 'The comment_id parameter is required.'
        ];
    }

    protected function failedAuthorization() {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'The API key sent is invalid.'
        ], 403));
    }

    protected function failedValidation(Validator $validator) {
        $err_msg = implode(' ', $validator->errors()->all());
        
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $err_msg
        ], 422));
    }
}
