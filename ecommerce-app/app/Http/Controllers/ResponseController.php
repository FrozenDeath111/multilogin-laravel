<?php

namespace App\Http\Controllers;

class ResponseController extends Controller
{
    private $data = [];

    private $code = 200;

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setStatusCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function sendResponse($message = '', $status = 'success')
    {
        $response = [
            'status' => $status,
            'code' => $this->code
        ];

        if (!empty($message))
            $response['message'] = $message;

        if (!!$this->data) {
            $response['data'] = $this->data;
        }

        return response()->json($response, $this->code);
    }

    public function sendError($message = 'Something went wrong! Please try again.', $status = 'error') //, $code = 400
    {
        $response = [
            'message' => $message,
            'status' => $status,
            'code' => $this->code
        ];

        if (!!$this->data) {
            $response['errors'] = $this->data;
        }

        return response()->json($response, $this->code);
    }
}
