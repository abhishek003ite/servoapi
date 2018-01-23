<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIndividualRequest extends FormRequest
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
        return [
            'bank_account_name'     =>      'required|max:200',
            'bank_account_num'      =>      'required|max:200',
            'ifsc_code'             =>      'required',
            'pan_num'               =>      'required',
            'gst_num'               =>      'required'
        ];
    }
}
