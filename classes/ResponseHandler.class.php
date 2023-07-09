<?php
ini_set('display_errors', 1);
error_reporting(~0);
class ResponseHandler
{
    // Constants
    const STATUS_OK = "ok";
    const STATUS_ERROR = "error";

    const ERROR_METHOD_NOT_ALLOWED = "405";
    const ERROR_INVALID_DATA = "200";
    const ERROR_INCOMPLETE_OR_INVALID_FORMAT = "400";
    const ERROR_INTERNAL_SERVER_ERROR = "500";
    const ERROR_UNAUTHORIZED = "401";

    private $response = [
        'status' => self::STATUS_OK,
        "result" => [],
    ];

    public function getResponse(): array
    {
        return $this->response;
    }

    public function errorMethodNotAllowed(): array
    {
        $this->response['status'] = self::STATUS_ERROR;
        $this->response['result'] = [
            "error_id" => self::ERROR_METHOD_NOT_ALLOWED,
            "error_msg" => "Method not allowed",
        ];
        return $this->response;
    }

    public function errorInvalidData($value = "Invalid data"): array
    {
        $this->response['status'] = self::STATUS_ERROR;
        $this->response['result'] = [
            "error_id" => self::ERROR_INVALID_DATA,
            "error_msg" => $value,
        ];
        return $this->response;
    }

    public function errorIncompleteOrInvalidFormat(): array
    {
        $this->response['status'] = self::STATUS_ERROR;
        $this->response['result'] = [
            "error_id" => self::ERROR_INCOMPLETE_OR_INVALID_FORMAT,
            "error_msg" => "Incomplete or invalid format of sent data",
        ];
        return $this->response;
    }

    public function errorInternalServerError($value = "Internal server error"): array
    {
        $this->response['status'] = self::STATUS_ERROR;
        $this->response['result'] = [
            "error_id" => self::ERROR_INTERNAL_SERVER_ERROR,
            "error_msg" => $value,
        ];
        return $this->response;
    }

    public function errorUnauthorized($value = "Unauthorized"): array
    {
        $this->response['status'] = self::STATUS_ERROR;
        $this->response['result'] = [
            "error_id" => self::ERROR_UNAUTHORIZED,
            "error_msg" => $value,
        ];
        return $this->response;
    }
}
