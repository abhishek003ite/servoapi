<?php

namespace App\Library;

Class Common 
{
    public static function getValidationMessageFormat()
    {
        return [
            'required'               => 'The :attribute can not be empty.',
            'unique:table,column'    => 'This :attribute is already in use.',
            'email'                  => 'The :attribute isn\'t a valid email.',
            'file'                   => 'The file :attribute failed to upload.',
            'image'                  => 'The :attribute must be a valid image.',
            'numeric'                => 'The :attribute must contain numbers only.',
            'min'                    => 'The :attribute value must be at least :min.',
            'date'                   => 'The :attribute must be in the format yyyy-mm-dd.',
            'max'                    => 'The :attribute value can not be grater than :max.',
            'digits_between:min,max' => 'The :attribute length must be between :min and :max.'
        ];
    }
}
