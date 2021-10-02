<?php

function password(int $length)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?';
    return substr(str_shuffle($chars), 0, $length);
}
function dump()
{
    echo '<pre>';
    foreach (func_get_args() as $value) {
        print_r($value);
    }
    echo '</pre>';
}
function append(string $filename, string $contents)
{
    prepareDirFor($filename);
    if (false === @file_put_contents($filename, $contents, FILE_APPEND)) {
        throw new RuntimeException('Unable to append content to file ' . $filename);
    }
}
function put(string $filename, string $contents)
{
    prepareDirFor($filename);
    if (false === @file_put_contents($filename, $contents)) {
        throw new RuntimeException('Unable to put content to file ' . $filename);
    }
}
function prepareDirFor(string $filename)
{
    if (!file_exists($filename)) {
        $dirname = dirname($filename);
        if (!file_exists($dirname)) {
            createPath($dirname);
        }
    }
}
function createPath(string $path): string
{
    if (!mkdir($path, 0777, true)) {
        throw new RuntimeException('Unable to create path ' . $path);
    }
    return $path;
}
function logger(string $message)
{
    if(PHP_SAPI !== 'cli') {
        return;
    }
    fwrite(fopen('php://stdout', 'r+'), $message);
}
function set_status_header($code)
{
    if(headers_sent()) {
        return;
    }
    $desc = get_set_status_header_desc($code);
    if (empty($desc)) {
        return false;
    }
    $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
    if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol) {
        $protocol = 'HTTP/1.0';
    }
    $set_status_header = "$protocol $code $desc";
    header($set_status_header, true, $code);
}
function get_set_status_header_desc($code)
{
    $codes_to_desc = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        510 => 'Not Extended',
    );
    return $codes_to_desc[$code] ?? 'n/a';
}
function writeToStderr(string $message) {
    fwrite(fopen('php://stderr', 'wb'), $message . "\n");
}
function isDocker(): bool {
    return getenv('CHEVERETO_SERVICING') == 'docker';
}
function isDatabaseEnvProvided(): bool {
    if(isDocker()) {
        return true;
    }
    return getenv('CHEVERETO_DB_HOST') !== false
        && getenv('CHEVERETO_DB_PORT') !== false
        && getenv('CHEVERETO_DB_NAME') !== false
        && getenv('CHEVERETO_DB_USER') !== false
        && getenv('CHEVERETO_DB_PASS') !== false;
}
function get_ini_bytes($size)
{
    return get_bytes($size, -1);
}
function get_bytes($size, $cut = null)
{
    if ($cut == null) {
        $suffix = substr($size, -3);
        $suffix = preg_match('/([A-Za-z]){3}/', $suffix) ? $suffix : substr($size, -2);
    } else {
        $suffix = substr($size, $cut);
    }
    $number = (int) str_replace($suffix, '', $size);
    $suffix = strtoupper($suffix);
    $units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']; // Default dec units
    if (strlen($suffix) == 3) { // Convert units to bin
        foreach ($units as &$unit) {
            $split = str_split($unit);
            $unit = $split[0] . 'I' . $split[1];
        }
    }
    if (strlen($suffix) == 1) {
        $suffix .= 'B'; // Adds missing "B" for shorthand ini notation (Turns 1G into 1GB)
    }
    if (!in_array($suffix, $units)) {
        return $number;
    }
    $pow_factor = array_search($suffix, $units) + 1;
    return $number * pow(strlen($suffix) == 2 ? 1000 : 1024, $pow_factor);
}