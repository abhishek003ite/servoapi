<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Traits\ApiResponses;
use Validator;
use App\Library\Common;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ApiResponses;

    public function validateOrError($request, $rules) {
        $validator = Validator::make(
                        $request->all(), 
                        $rules, 
                        Common::getValidationMessageFormat()
                    );

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return [
                'passed' => false,
                'message' => $first_error
            ];
        }

        return [ 'passed' => true ];
    }
}
