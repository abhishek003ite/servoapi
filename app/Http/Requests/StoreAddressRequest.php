<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            // 'building_num'      =>  'required|max:191',
            // 'street_address'    =>  'required|max:191',
            // 'region'            =>  'required|max:191',
            // 'city'              =>  'required|max:191',
            // 'state'             =>  'required|max:191',
            // 'pincode'           =>  'required|max:191',
            // 'lat'               =>  'required',
            // 'long'              =>  'required'
        ];
    }
}
