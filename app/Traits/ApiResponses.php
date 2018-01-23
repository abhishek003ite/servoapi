<?php
namespace app\Traits;

trait ApiResponses {
    protected function successResponse($info = []) {
        return response([
            'status' => 'ok',
            'info' => $info
        ]);
    }

    protected function errorResponse($info = []) {
        return response([
            'status' => 'error',
            'info' => $info
        ]);
    }
}