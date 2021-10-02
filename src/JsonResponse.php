<?php

final class JsonResponse
{
    public string $status;

    public int $code;

    public string $message;

    public array $data = [];

    public function setResponse(string $message, int $httpCode = 200): void
    {
        $this->code = (int) $httpCode;
        $this->message = $message;
        $this->status = $this->getHttpStatusDesc($httpCode);
    }

    public function getHttpStatusDesc($httpCode): string
    {
        return get_set_status_header_desc($httpCode);
    }

    public function setStatusCode(int $httpCode): void
    {
        http_response_code($httpCode);
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function addData($key, $var = null): void
    {
        $this->data[$key] = $var;
    }

    public function send(int $exitCode): void
    {
        @ini_set('display_errors', '0');
        if (ob_get_level() === 0 and !ob_start('ob_gzhandler')) {
            ob_start();
        }
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/json; charset=UTF-8');
        $json = json_encode($this, JSON_FORCE_OBJECT);
        if (!$json) {
            $this->setResponse("Data couldn't be encoded", 500);
            $this->data = [];
        }
        if (isset($this->code)) {
            $this->setStatusCode($this->code);
        }
        echo $this->data !== []
            ? $json 
            : json_encode($this, JSON_FORCE_OBJECT);
        if(PHP_SAPI === 'cli') {
            echo "\n";
        }
        die($exitCode);
    }
}
