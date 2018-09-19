<?php
/* --------------------------------------------------------------------

    Chevereto web installer (universal)
    http://chevereto.com/

    @version 1.1.0
    @author	Rodolfo Berrios A. <http://rodolfoberrios.com/>

    No copyright restrictions. I don't give a damn, but you can't touch this:

      /$$$$$$  /$$                                                           /$$
     /$$__  $$| $$                                                          | $$
    | $$  \__/| $$$$$$$   /$$$$$$  /$$    /$$ /$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$    /$$$$$$
    | $$      | $$__  $$ /$$__  $$|  $$  /$$//$$__  $$ /$$__  $$ /$$__  $$|_  $$_/   /$$__  $$
    | $$      | $$  \ $$| $$$$$$$$ \  $$/$$/| $$$$$$$$| $$  \__/| $$$$$$$$  | $$    | $$  \ $$
    | $$    $$| $$  | $$| $$_____/  \  $$$/ | $$_____/| $$      | $$_____/  | $$ /$$| $$  | $$
    |  $$$$$$/| $$  | $$|  $$$$$$$   \  $/  |  $$$$$$$| $$      |  $$$$$$$  |  $$$$/|  $$$$$$/
     \______/ |__/  |__/ \_______/    \_/    \_______/|__/       \_______/   \___/   \______/

     /$$$$$$$  /$$   /$$ /$$       /$$$$$$$$  /$$$$$$  /$$
    | $$__  $$| $$  | $$| $$      | $$_____/ /$$__  $$| $$
    | $$  \ $$| $$  | $$| $$      | $$      | $$  \__/| $$
    | $$$$$$$/| $$  | $$| $$      | $$$$$   |  $$$$$$ | $$
    | $$__  $$| $$  | $$| $$      | $$__/    \____  $$|__/
    | $$  \ $$| $$  | $$| $$      | $$       /$$  \ $$
    | $$  | $$|  $$$$$$/| $$$$$$$$| $$$$$$$$|  $$$$$$/ /$$
    |__/  |__/ \______/ |________/|________/ \______/ |__/

  --------------------------------------------------------------------- */

error_reporting(E_ALL ^ E_NOTICE);
@ini_set('log_errors', true);
@ini_set('default_charset', 'utf-8');
@set_time_limit(0);
setlocale(LC_ALL, 'en_US.UTF8');
define('__SESSION_START__', @session_start()); // Avoid premature headers false-positive

class Settings
{
    public static $chevereto = array(
        'url' => 'https://chevereto.com',
    );
    public static $editions = array(
        'paid' => array(
            'zipball' => 'https://chevereto.com/api/download/latest',
            'folder' => 'chevereto',
        ),
        'free' => array(
            'zipball' => 'https://api.github.com/repos/Chevereto/Chevereto-Free/zipball',
            'folder' => 'Chevereto-Chevereto-Free-',
        )
    );
    private static $instance;
    public function __construct()
    {
        $chevereto_url = self::$chevereto['url'];
        foreach (array('api', 'src') as $v) {
            self::$chevereto[$v . '_url'] = $chevereto_url . '/' . $v . ($v == 'src' ? '/img/installer' : null);
        }
        self::$instance = $this;
    }
    public static function get($var)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        $return = self::$$var;
        if (is_array($return)) {
            $json = json_encode($return);
            if ($json) {
                $return = json_decode($json);
            }
        }
        return $return;
    }
}



define('__ROOT_PATH__', rtrim(str_replace('\\', '/', __DIR__), '/') . '/');
define('__ROOT_PATH_RELATIVE__', rtrim(@dirname($_SERVER['SCRIPT_NAME']), '\/') . '/');
define('__INSTALLER_FILE__', basename(__FILE__));
define('__INSTALLER_FILEPATH__', __ROOT_PATH__ . __INSTALLER_FILE__);

define('__HTTP_HOST__', $_SERVER['HTTP_HOST']);
define('__HTTP_PROTOCOL__', 'http' . (((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 's' : null));
define('__ROOT_URL__', __HTTP_PROTOCOL__ . "://" . __HTTP_HOST__ . __ROOT_PATH_RELATIVE__);

define('__SERVER_STRING__', 'Server ' . __HTTP_HOST__ . ' PHP ' . phpversion());
define('__SERVER_REWRITE__', null);

/**
 * Generate a random string using the best available method.
 *
 * @param int $length Length of the generated random string.
 * @return array An array with the generated random values.
 * @autor Baba <http://stackoverflow.com/a/17267718>
 */
function randomString($length=8)
{
    switch (true) {
        case function_exists('mcrypt_create_iv'):
            $r = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        break;
        case function_exists('openssl_random_pseudo_bytes'):
            $r = openssl_random_pseudo_bytes($length);
        break;
        case is_readable('/dev/urandom'): // deceze
            $r = file_get_contents('/dev/urandom', false, null, 0, $length);
        break;
        default: // Fallback
            $i = 0;
            $r = '';
            while ($i ++ < $length) {
                $r .= chr(mt_rand(0, 255));
            }
        break;
    }
    return substr(bin2hex($r), 0, $length);
}

/**
 * Get URL content.
 *
 * Fetchs the contents of the target URL using file_get_contents.
 * Fallbacks to cURL if file_get_contents isn't available.
 *
 * The downloaded content can be returned as a string or save it as a file.
 *
 * @param string $url 		Target URL to fetch.
 * @param string $file_path File where the downloaded content should be saved.
 *
 * @throws Exception
 *
 * @return mixed File as string or boolean if $file_path was set.
 */
function getUrlContent($url, $options=null)
{
    if (!$url) {
        throw new Exception('Missing $url');
    }
    if (!function_exists('curl_init')) {
        throw new Exception("cURL isn't installed");
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    if ($options && is_array($options)) {
        foreach ($options as $k => $v) {
            curl_setopt($ch, $k, $v);
        }
    }
    // Try to always save this as a tmp file
    $temp_file_path = @tempnam(sys_get_temp_dir(), 'download');
    if (!$temp_file_path || !@is_writable($temp_file_path)) {
        unset($temp_file_path);
    }
    if ($temp_file_path) {
        $out = @fopen($temp_file_path, 'wb');
        if (!$out) {
            throw new Exception("Can't open temp file for read and write in " . __FUNCTION__ . '()');
        }
        curl_setopt($ch, CURLOPT_FILE, $out);
        @curl_exec($ch);
        fclose($out);
    } else {
        $file_get_contents = @curl_exec($ch);
    }
    $transfer = curl_getinfo($ch);
    if (curl_errno($ch)) {
        $curl_error = curl_error($ch);
        curl_close($ch);
        throw new Exception('Curl error ' . $curl_error);
    }
    curl_close($ch);
    $return = array('transfer' => $transfer);
    if ($temp_file_path) {
        $return['tmp_file_path'] = $temp_file_path;
    } else {
        $return['contents'] = $file_get_contents;
    }
    if (!isset($return['contents']) && bytesToMb($transfer['size_download']) < 0.5) {
        $return['contents'] = file_get_contents($temp_file_path);
    }
    return $return;
}

/**
 * Sets HTTP status header from a HTTP status code.
 *
 * Taken (sort of) from WordPress.
 *
 * @param int $code A HTTP status code.
 *
 * @return bool TRUE if the HTTP status header has been properly set.
 */
function setHttpStatusCode($code)
{
    $desc = getHttpStatusDesc($code);
    if (empty($desc)) {
        return false;
    }
    $protocol = $_SERVER['SERVER_PROTOCOL'];
    if ('HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol) {
        $protocol = 'HTTP/1.0';
    }
    $setstatusheader = "$protocol $code $desc";
    return @header($setstatusheader, true, $code);
}

/**
 * Gets the HTTP header description corresponding to its code.
 *
 * Taken (sort of) from WordPress.
 *
 * @param string $code A HTTP status code.
 *
 * @return string HTTP status code description.
 */
function getHttpStatusDesc($code)
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
        510 => 'Not Extended'
    );
    if (array_key_exists($code, $codes_to_desc)) {
        return $codes_to_desc[$code];
    }
}

/**
 * Converts bytes to human readable representation.
 *
 * @param string $bytes Bytes to be formatted.
 * @param int    $round How many decimals you want to get, default 1.
 *
 * @return string Formatted size string like 10 MB.
 */
function formatBytes($bytes, $round=1)
{
    if (!is_numeric($bytes)) {
        return false;
    }
    if ($bytes < 1000) {
        return "$bytes B";
    }
    $units = array('KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    foreach ($units as $k => $v) {
        $multiplier = pow(1000, $k + 1);
        $threshold = $multiplier * 1000;
        if ($bytes < $threshold) {
            $size = round($bytes / $multiplier, $round);
            return "$size $v";
        }
    }
}

/**
 * Converts bytes to MB.
 *
 * @param string $bytes Bytes to be formatted.
 *
 * @return float MB representation.
 */
function bytesToMb($bytes, $round=2)
{
    $mb = $bytes / pow(10, 6);
    if ($round) {
        $mb = round($mb, $round);
    }
    return $mb;
}

if (@class_exists('ZipArchive')) {
    class ZipArchiveExt extends ZipArchive
    {
        public function extractSubdirTo($destination, $subdir)
        {
            $errors = array();
            // Prepare dirs
            $destination = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $destination);
            $subdir = str_replace(array('/', '\\'), '/', $subdir);
            if (substr($destination, mb_strlen(DIRECTORY_SEPARATOR, 'UTF-8') * -1) != DIRECTORY_SEPARATOR) {
                $destination .= DIRECTORY_SEPARATOR;
            }
            if (substr($subdir, -1) != '/') {
                $subdir .= '/';
            }
            // Extract files
            for ($i = 0; $i < $this->numFiles; $i++) {
                $filename = $this->getNameIndex($i);
                if (substr($filename, 0, mb_strlen($subdir, 'UTF-8')) == $subdir) {
                    $relativePath = substr($filename, mb_strlen($subdir, 'UTF-8'));
                    $relativePath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $relativePath);
                    if (mb_strlen($relativePath, 'UTF-8') > 0) {
                        if (substr($filename, -1) == '/') { // Directory
                            // New dir
                            if (!is_dir($destination . $relativePath)) {
                                if (!@mkdir($destination . $relativePath, 0755, true)) {
                                    $errors[$i] = $filename;
                                }
                            }
                        } else {
                            if (dirname($relativePath) != '.') {
                                if (!is_dir($destination . dirname($relativePath))) {
                                    // New dir (for file)
                                    @mkdir($destination . dirname($relativePath), 0755, true);
                                }
                            }
                            // New file
                            if (@file_put_contents($destination . $relativePath, $this->getFromIndex($i)) === false) {
                                $errors[$i] = $filename;
                            }
                        }
                    }
                }
            }
            return $errors;
        }
    }
}

class RequirementsCheck
{
    public function __construct()
    {
        $this->missing = array();
        @ini_set('session.gc_divisor', 100);
        @ini_set('session.gc_probability', true);
        @ini_set('session.use_trans_sid', false);
        @ini_set('session.use_only_cookies', true);
        @ini_set('session.hash_bits_per_character', 4);
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->addMissing('PHP', 'https://php.net', 'Use a newer %l version (%c 5.4+ required, 7.0+ recommended)');
        }
        $this->detectMissingPHP();
        if (function_exists('date_default_timezone_get')) {
            $tz = @date_default_timezone_get();
            $dtz = @date_default_timezone_set($tz);
            if (!$dtz && !@date_default_timezone_set('America/Santiago')) {
                $this->addMissing(array('timezone', 'date.timezone'), array('http://php.net/manual/en/timezones.php', 'http://php.net/manual/en/datetime.configuration.php#ini.date.timezone'), '<b>'. $tz .'</b> is not a valid %l0 identifier in %l1');
            }
        }
        $rw_fn = array('read' => 'is_readable', 'write' => 'is_writeable');
        $session_link = 'http://php.net/manual/en/book.session.php';
        if (!__SESSION_START__) {
            $this->addMissing('sessions', $session_link, 'Enable %l support (session_start)');
        }
        $session_save_path = @realpath(@session_save_path());
        if ($session_save_path) {
            if (!is_writable($session_save_path)) {
                $session_errors[] = $k;
            }
            if (isset($session_errors)) {
                $this->addMissing(array('session', 'session.save_path'), array($session_link, 'http://php.net/manual/en/session.configuration.php#ini.session.save-path'), str_replace('%s', implode('/', $session_errors), 'Missing PHP <b>%s</b> permission in <b>'.$session_save_path.'</b> (%l1)'));
            }
        }
        $_SESSION['G'] = true;
        if (!$_SESSION['G']) {
            $this->addMissing('sessions', $session_link, 'Any server setting related to %l support (%c are not working)');
        }
        foreach (array(__ROOT_PATH__, __INSTALLER_FILEPATH__) as $var) {
            foreach (array('read','write') as $k => $v) {
                if (!@$rw_fn[$v]($var)) {
                    $permissions_errors[] =  $k;
                }
            }
            if (isset($permissions_errors)) {
                $error = implode('/', $permissions_errors);
                $component = $var . ' ' . $error . ' permission' . (count($permissions_errors) > 1 ? 's' : null);
                $message = 'No PHP <b>' . $error . '</b> permission in <b>' . $var . '</b>';
                $this->addMissing($component, null, $message);
                unset($permissions_errors);
            }
        }
        if (!@extension_loaded('gd') && !function_exists('gd_info')) {
            $this->addMissing('GD Library', 'http://php.net/manual/en/book.image.php', 'Enable %l');
        } else {
            foreach (array('PNG','GIF','JPG','WBMP') as $k => $v) {
                if (!imagetypes() & constant('IMG_' . $v)) {
                    $this->addMissing('GD Library', 'http://php.net/manual/en/book.image.php', 'Enable %l ' . $v .' image support');
                }
            }
        }
        if (@!ini_get('file_uploads')) {
            $this->addMissing('file_uploads', 'http://php.net/manual/en/ini.core.php#ini.file-uploads', 'Enable %l (needed for file uploads)');
        }
        if (preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) && function_exists('apache_get_modules') && !in_array('mod_rewrite', apache_get_modules())) {
            $this->addMissing('mod_rewrite', 'http://httpd.apache.org/docs/current/mod/mod_rewrite.html', 'Enable %l (needed for URL rewriting)');
        }
        $utf8_errors = array();
        foreach (array('utf8_encode', 'utf8_decode') as $v) {
            if (!function_exists($v)) {
                $utf8_errors['c'][] = $v;
                $utf8_errors['l'][] = 'http://php.net/manual/en/function.' . str_replace('_', '-', $v) . '.php';
            }
        }
        if ($utf8_errors) {
            $this->addMissing($utf8_errors['c'], $utf8_errors['l'], count($utf8_errors['c']) == 1 ? 'Enable %l function' : 'Enable %l0 and %l1 functions');
        }
        if (!function_exists('curl_init')) {
            $this->addMissing('cURL', 'http://php.net/manual/en/book.curl.php', 'Enable PHP %l');
        } else {
            $headers = @get_headers(Settings::get('chevereto')->api_url, true);
            if ($headers) {
                $http_statusCode = substr($headers[0], 9, 3);
                if ($http_statusCode != 200) {
                    $http_error_link = '<a href="https://en.wikipedia.org/wiki/HTTP_' . $http_statusCode . '" target="_blank">HTTP ' . $http_statusCode . '</a>';
                    $this->addMissing('Chevereto API', Settings::get('chevereto')->api_url, "An $http_error_link error occurred when trying to connect to %l");
                }
            } else {
                $api_parse_url = parse_url(Settings::get('chevereto')->api_url);
                $api_offline_link = '<a href="https://isitdownorjust.me/' . $api_parse_url['host'] . '" target="_blank">offline</a>';
                $this->addMissing('Chevereto API', Settings::get('chevereto')->api_url, "Can't connect to %l. Check for any outgoing network blocking or maybe our server is $api_offline_link at this time");
            }
        }
    }
    private function detectMissingPHP()
    {
        $core = array(
            'extensions' => array(
                'curl' => 'book.curl',
                'hash' => 'book.hash',
                'json' => 'book.json',
                'mbstring' => 'book.mbstring',
                'PDO' => 'book.pdo',
                'PDO_MYSQL' => 'ref.pdo-mysql',
                'session' => 'book.session',
                //'zip' => 'book.zip', // Not needed isn't?
            ),
            'classes' => array(
                'DateTime' => 'class.datetime',
                'DirectoryIterator' => 'class.directoryiterator',
                'Exception' => 'class.exception',
                'PDO' => 'class.pdo',
                'PDOException' => 'class.pdoexception',
                'RegexIterator' => 'class.regexiterator',
                'RecursiveIteratorIterator' => 'class.recursiveiteratoriterator',
                'ZipArchive' => 'class.ziparchive',
            )
        );
        $nouns = array(
            'extensions' => array('extension', 'extensions'),
            'classes' => array('class', 'classes'),
        );
        $core_check_function = array(
            'extensions' => array('get_loaded_extensions', 'extension_loaded'),
            'classes' => array('get_declared_classes', 'class_exists'),
        );
        foreach ($core as $type => $array) {
            $n = $nouns[$type];
            $core_check = $core_check_function[$type];
            $missing = array();
            $loaded = @$core_check[0]();
            if ($loaded) {
                foreach ($loaded as $k => &$v) {
                    $v = strtolower($v);
                }
            } else {
                $function = create_function('$var', 'return @' . $core_check[1] . '($var);');
            }
            foreach ($array as $k => $v) {
                if (($loaded && !in_array(strtolower($k), $loaded)) || ($function && $function($k))) {
                    $missing['c'][] = $k;
                    $missing['l'][] = 'http://www.php.net/manual/'.$v.'.php';
                }
            }
            if ($missing) {
                $l = array();
                $c = array();
                $message = 'Enable %l PHP <b>%n</b>';
                if (count($missing['c']) == 1) {
                    $missing_strtr = array('%n' => $n[0]);
                } else {
                    foreach ($missing['l'] as $k => $v) {
                        $l[] = '%l' . $k;
                    }
                    $last = array_pop($l);
                    $missing_strtr['%l'] = implode(', ', $l) . ' and ' . $last;
                    $missing_strtr['%n'] = $n[1];
                }
                $message = strtr($message, $missing_strtr);
                $this->addMissing($missing['c'], $missing['l'], $message);
            }
        }
    }
    public function addMissing()
    {
        //$components, $links, $msgtpl
        $args = func_get_args();
        $placeholders = array();
        foreach (array('c', 'l') as $k => $v) {
            $key = '%' . $v;
            if (gettype($args[$k]) == 'string') {
                $args[$k] = array($args[$k]);
            }
            if (gettype($args[$k]) == 'string' || count($args[$k]) == 1) {
                $args[2] = str_replace($key, $key . '0', $args[2]);
            }
            if (is_array($args[$k])) {
                foreach ($args[$k] as $k_ => $v_) {
                    if ($v == 'l') {
                        $v_ = '<a href="' . $args[1][$k_] . '" target="_blank">' . $args[0][$k_] . '</a>';
                    }
                    $placeholders[$key . $k_] = $v_;
                }
            }
        }
        $message = strtr($args[2], $placeholders);
        $this->missing[] = array(
            'components' => $args[0],
            'message' => $message
        );
    }
}
$RequirementsCheck = new RequirementsCheck;

class Output
{
    public function __construct()
    {
        $this->status = null;
        $this->response = null;
        $this->request = $_REQUEST;
    }
    public function setHttpStatus($code)
    {
        $this->status = array(
            'code' => $code,
            'description' => getHttpStatusDesc($code),
        );
    }
    public function setResponse($message, $code=null)
    {
        $this->response = array(
            'code' => $code,
            'message' => $message,
        );
    }
    public function addData($prop, $var=null)
    {
        if (!isset($this->data)) {
            $this->data = new stdClass;
        }
        if (is_array($var)) {
            // $var = json_encode($var, JSON_FORCE_OBJECT);
        }
        $this->data->{$prop} = $var;
    }
    public function exec()
    {
        error_reporting(0);
        @ini_set('display_errors', false);
        if (ob_get_level() === 0 and !ob_start('ob_gzhandler')) {
            ob_start();
        }
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').'GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Content-type: application/json; charset=UTF-8');
        if (!isset($this->data) && !isset($this->response)) {
            $this->setHttpStatus(400);
        } else {
            if (!isset($this->status['code'])) {
                $this->setHttpStatus(200);
            }
        }
        if (!isset($this->response)) {
            $this->setResponse($this->status['description'], $this->status['code']);
        } else {
            if (!isset($this->response['code'])) {
                $this->response['code'] = $this->status['code'];
            }
        }
        $json = json_encode($this, JSON_FORCE_OBJECT);
        if (!$json) {
            $this->setHttpStatus(500);
            $this->setResponse("Data couldn't be encoded", 500);
            $this->data = null;
        }
        if (is_int($this->status['code'])) {
            setHttpStatusCode($this->status['code']);
        }
        print $this->data ? $json : json_encode($this, JSON_FORCE_OBJECT);
        die();
    }
}

class processAction
{
    public function __construct()
    {
        $action = $_REQUEST['action'];
        $actions = array('download', 'extract');
        $editions = array('free', 'paid');
        if (!in_array($_REQUEST['action'], $actions)) {
            return;
        }
        $edition = $_REQUEST['edition'] ? Settings::get('editions')->{$_REQUEST['edition']} : null;
        $Output = new Output;
        try {
            switch ($action) {
                case 'download':
                    if (!$edition) {
                        throw new Exception('Missing edition', 4000);
                    }
                    $zipball = $edition->zipball;
                    $file_basename = 'chevereto-pkg-' . randomString(8) . '.zip';
                    $file_path =  __ROOT_PATH__ . $file_basename;
                    if (file_exists($file_path)) {
                        @unlink($file_path);
                    }
                    $options = array(CURLOPT_USERAGENT => 'Chevereto web installer');
                    if ($_REQUEST['edition'] == 'paid') {
                        $options[CURLOPT_POST] = 1;
                        $options[CURLOPT_POSTFIELDS] = 'license=' . $_REQUEST['license'];
                    }
                    $download = getUrlContent($zipball, $options);
                    $transfer = $download['transfer'];
                    if ($transfer['http_code'] !== 200) {
                        $Output->setHttpStatus($transfer['http_code']);
                        $json = json_decode($download['contents']);
                        if (!$json) {
                            throw new Exception($download['contents'], 4001);
                        } else {
                            $message = $_REQUEST['edition'] == 'free' ? $json->message : $json->error->message;
                            throw new Exception($message, 4002);
                        }
                    } else {
                        if (!@rename($download['tmp_file_path'], $file_basename)) {
                            throw new Exception("Can't save downloaded file " . $file_path, 5001);
                        }
                        @unlink($download['tmp_file_path']);
                        $file_size = filesize($file_path);
                        $Output->addData('download', array(
                            'fileBasename' => $file_basename,
                        ));
                        $Output->setResponse(strtr('Downloaded %f (%w @%s)', array(
                            '%f' => $file_basename,
                            '%w' => formatBytes($file_size),
                            '%s' => bytesToMb($transfer['speed_download']) . 'MB/s.'
                        )));
                    }
                break;
                case 'extract':
                    $file_path = __ROOT_PATH__ . $_REQUEST['fileBasename'];
                    if (!is_readable($file_path)) {
                        throw new Exception(sprintf("Can't read %s", $_REQUEST['fileBasename']), 5002);
                    }
                    // Unzip .zip
                    $ZipArchive = new ZipArchiveExt;
                    $time_start = microtime(true);
                    $open = $ZipArchive->open($file_path);
                    if ($open === true) {
                        $num_files = $ZipArchive->numFiles - 1; // because of tl folder
                        $folder = $edition->folder;
                        if ($_REQUEST['edition'] == 'free') {
                            $comment = $ZipArchive->getArchiveComment();
                            $folder .= substr($comment, 0, 7);
                        }
                        $ZipArchive->extractSubdirTo(__ROOT_PATH__, $folder);
                        $ZipArchive->close();
                        $time_taken = round(microtime(true) - $time_start, 2);
                        @unlink($file_path);
                        // Also remove some free edition docs
                        if ($_REQUEST['edition'] == 'paid') {
                            foreach (array('AGPLv3', 'LICENSE', 'README.md') as $v) {
                                @unlink(__ROOT_PATH__ . $v);
                            }
                        }
                    } else {
                        throw new Exception(strtr("Can't extract %f - %m", array(
                            '%f' => $file_path,
                            '%m' => 'ZipArchive ' . $open . ' error'
                        )), 5003);
                    }
                    $Output->setResponse(strtr('Extraction completeted (%n files in %ss)', array(
                        '%n' => $num_files,
                        '%s' => $time_taken
                    )));
                    // My job here is done. My planet needs me.
                    if (__INSTALLER_FILE__ != 'index.php') {
                        @unlink(__INSTALLER_FILEPATH__);
                    }
                break;
            }
            $Output->exec();
        } catch (Exception $e) {
            if (!isset($Output->status['code'])) {
                $Output->setHttpStatus(500);
            }
            $Output->setResponse($e->getMessage(), $e->getCode());
            $Output->exec();
        }
    }
}

if (isset($_REQUEST['action'])) {
    if ($RequirementsCheck->missing) {
        $Output = new Output;
        $missing = array();
        foreach ($RequirementsCheck->missing as $k => $v) {
            $missing[] = $v;
        }
        $Output->addData('missing', $missing);
        $Output->setHttpStatus(500);
        $Output->setResponse('Missing server requirements', 500);
        $Output->exec();
    }
    $processAction = new processAction();
}

$page = $RequirementsCheck->missing ? 'error' : 'install';

if ($page == 'install' && !isset($_REQUEST['UpgradeToPaid']) && preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE'])) {
    $nginx = '<p>Make sure that you add the following rules to your <a href="https://www.digitalocean.com/community/tutorials/understanding-the-nginx-configuration-file-structure-and-configuration-contexts" target="_blank">nginx.conf</a> server block before installing:</p>
<textarea class="pre" ondblclick="this.select()">#Chevereto: Disable access to sensitive files
location ~* ' . __ROOT_PATH_RELATIVE__ . '(app|content|lib)/.*\.(po|php|lock|sql)$ {
	deny all;
}
#Chevereto: CORS headers
location ~* ' . __ROOT_PATH_RELATIVE__ . '.*\.(ttf|ttc|otf|eot|woff|woff2|font.css|css|js) {
	add_header Access-Control-Allow-Origin "*";
}
#Chevereto: Upload path for image content only and set 404 replacement
location ^~ ' . __ROOT_PATH_RELATIVE__ . 'images/ {
	location ~* (jpe?g|png|gif) {
		log_not_found off;
		error_page 404 ' . __ROOT_PATH_RELATIVE__ . 'content/images/system/default/404.gif;
	}
	return 403;
}
#Chevereto: Pretty URLs
location ' . __ROOT_PATH_RELATIVE__ . ' {
	index index.php;
	try_files $uri $uri/ ' . __ROOT_PATH_RELATIVE__ . 'index.php?$query_string;
}</textarea>';
}
?>
<!DOCTYPE HTML>
<html xml:lang="en" lang="en" dir="ltr" id="<?php echo $page; ?>">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <meta name="theme-color" content="#ecf0f1">
   <title>Chevereto installer</title>
   <link rel="shortcut icon" type="image/png" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACABAMAAAAxEHz4AAAAMFBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABaPxwLAAAAD3RSTlMAECAwQGBwgI+fr7/P3+/Lm0b7AAABXElEQVR4Xu3WP0oDQRiG8dWN6vq/tLQTu21sJYUHEDyAHkHs7PQGwcYyHsFeBG8Qe4sIHiA2goFkHxlMeMOGwMyXLn5vtZnix5OFhcn+w3yN5nzAUmewNxewCpdzAWtw5YADDjjggAMOOOCAA7U54IADDhyX00B+Hg+s8DMNtDmLBnagVQcK+IgGdqFfB9rwFQ2sM0oQUAC3WfTa0Begk+gVQEuADpISBOh3WoIABSQlCFBAakIAFGBICIACDAkBUIAhIQAKMCQEQAGGhAAowJDwADcKMCRU0FOAJQEUYEoABRgSFGBOUIA9wR6gz9C+QgHWhIiATWjOTpgd0IAnAYYtHuCAAw5swKXxHv04fnizANvjK2AO3xbgAso/qktVpgN5j6Goz3TgROFbwH0qcAS8joDlHvD+nLQXoDpQjWn633kXw4YTb34fw+6yiR12SNzguvZWTxOXLdJ8v+uv3HMGDU3uAAAAAElFTkSuQmCC">
	<style>
		body, html {
			height: 100%;
			width: 100%;
		}

		body {
			font-family: Helvetica, Arial, sans-serif;
			font-size: 15px;
			line-height: 1.2;
			margin: 0;
			font-family: sans-serif;
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		* {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			-ms-box-sizing: border-box;
			box-sizing: border-box;
			outline: 0;
		}

		*:before, *:after {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}

		a {
			color: #3498db;
			outline: 0;
			text-decoration: none;
		}
		a:hover {
			text-decoration: underline;
		}

		p, ul > li {
			line-height: 140%;
		}

		.soft-hidden {
			display: none;
		}

		input, button {
			font-family: inherit;
			padding: 10px;
			border: 1px solid rgba(0,0,0,.2);
			background: none;
			color: rgba(0,0,0,.8);
		}
		input:focus {
			background: rgba(255,255,255,.6);
		}
		input:focus, button:hover {
			border-color: #3498db;
		}
		/* Go home Chrome, you are drunk */
		input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus input:-webkit-autofill, textarea:-webkit-autofill, textarea:-webkit-autofill:hover textarea:-webkit-autofill:focus, select:-webkit-autofill, select:-webkit-autofill:hover, select:-webkit-autofill:focus {
			-webkit-text-fill-color: inherit;
			-webkit-box-shadow: 0 0 0px 1000px transparent inset;
			transition: background-color 5000s ease-in-out 0s;
		}

		code {
			font-family: monospace;
		}
		.pre {
			display: block;
			background-color: rgba(0,0,0,.1);
			overflow: auto;
			height: 180px;
			font-size: 0.87em;
			white-space: nowrap;
			width: 100%;
			resize: none;
			border: none;
			margin: 1em 0;
			-moz-tab-size : 4;
      -o-tab-size : 4;
      tab-size : 4;
		}

		#preloader {
			position: fixed;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			opacity: 0;
		}
		.body--slowload #preloader {
			opacity: 1;
			z-index: 1;
		}
		.body--splash #preloader {
			display: none;
		}
		/* https://codepen.io/WebSonata/ */
		.spinner {
			position: relative;
			left: 50%;
			top: 50%;
			width: 80px;
			height: 80px;
			margin: -40px 0 0 -40px;
			border-radius: 50%;
			border: 3px solid transparent;
			border-top-color: rgba(0,0,0,.2);
			-webkit-animation: spin 2s linear infinite;
			animation: spin 2s linear infinite;
		}
		.spinner:before {
			content: "";
			position: absolute;
			top: 5px;
			left: 5px;
			right: 5px;
			bottom: 5px;
			border-radius: 50%;
			border: 3px solid transparent;
			border-top-color: rgba(0,0,0,.2);
			-webkit-animation: spin 3s linear infinite;
			animation: spin 3s linear infinite;
		}
		.spinner:after {
			content: "";
			position: absolute;
			top: 15px;
			left: 15px;
			right: 15px;
			bottom: 15px;
			border-radius: 50%;
			border: 3px solid transparent;
			border-top-color: rgba(0,0,0,.2);
			-webkit-animation: spin 1.5s linear infinite;
			animation: spin 1.5s linear infinite;
		}
		@-webkit-keyframes spin {
			0%   {
				-webkit-transform: rotate(0deg);
				-ms-transform: rotate(0deg);
				transform: rotate(0deg);
			}
			100% {
				-webkit-transform: rotate(360deg);
				-ms-transform: rotate(360deg);
				transform: rotate(360deg);
			}
		}
		@keyframes spin {
			0%   {
				-webkit-transform: rotate(0deg);
				-ms-transform: rotate(0deg);
				transform: rotate(0deg);
			}
			100% {
				-webkit-transform: rotate(360deg);
				-ms-transform: rotate(360deg);
				transform: rotate(360deg);
			}
		}

		.loader {
			display: inline-block;
			border: .15em solid #DDD;
			border-top: .15em solid #3498db;
			border-radius: 50%;
			width: 1em;
			height: 1em;
			animation: spin 2s linear infinite;
			z-index: 1;
		}
		.box--install .loader {
			font-size: 30px;
			position: absolute;
			top: 20px;
			right: 20px;
			margin: 0;
			opacity: 0;
		}
		.body--working .box--install .loader {
			opacity: 1;
		}

		.animate {
			-webkit-backface-visibility: hidden;
			transition: all 200ms ease;
		}
		.animate--slow {
			transition-duration: 400ms;
		}
		.animate--delayed {
			transition-delay: 400ms;
		}

		.free--show, .paid--show {
			display: none;
		}

		.body--free .free--show,
		.body--paid .paid--show {
			display: block;
		}

		.body--free .edition-hide,
		.body--paid .edition-hide {
			display: none;
		}

		.body--background:not(.body--free):not(.body--paid) .body-background-vhide {
			visibility: hidden;
		}

		.background {
			position: fixed;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			background-size: cover;
			background: #f7f7f7;
			background: linear-gradient(to bottom, rgba(255,255,255,1) 0%,rgba(247,247,247,1) 100%);
		}
		.background img {
			opacity: 0;
			position: absolute;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			object-fit: cover;
			transition-delay: 50ms;
		}
		.body--free .background-free,
		.body--paid .background-paid,
		.body--background.body--free .background-placeholder,
		.body--background.body--paid .background-placeholder {
			opacity: 1;
		}

		.text-align-center {
			text-align: center;
		}

		button {
			font-weight: bold;
			padding-right: 15px;
			padding-left: 15px;
			line-height: 1;
			outline: 0;
			cursor: pointer;
			text-shadow: 1px 1px 0 rgba(255,255,255,.1);
		}
		button:hover {
			background-color: rgba(0,0,0,.05);
		}
		button:active {
			border-color: rgba(0,0,0,.3);
			box-shadow: inset 0 2px 5px rgba(0,0,0,.3);
		}

		.flex {
			display: flex;
		}

		.flex--full {
			min-height: 100%;
			overflow: hidden;
		}

		.container {
			margin: auto;
			display: none;
			flex-wrap: wrap;
			flex-direction: row;
			justify-content: center;
			opacity: 0;
			transform: scale(.8);
		}
		.container--error {
			opacity: 1;
			display: flex;
			transform: scale(1);
		}

		.body--slowload .container {
			visibility: hidden;
		}
		.body--splash .container--splash,
		.body--install .container--install,
		.body--installing .container--installing {
			animation-name: fadeInFromNone;
			animation-fill-mode: forwards;
			animation-duration: 400ms;
			display: flex;
			visibility: visible;
		}

		@keyframes fadeInFromNone {
			0% {
				display: none;
				opacity: 0;
			}
			100% {
				opacity: 1;
				transform: scale(1);
			}
		}

		.flex-box {
			background: #FFF;
			background-size: cover;
			background-position: center;
			box-shadow: 0 5px 20px 0 rgba(0, 0, 0, .2);
			border-radius: 6px;
			min-width: 270px;
			position: relative;
			margin: 20px;
			flex: 1 0 0; /* ms ladies and gentlemen */
		}

		.flex-box a {
			color: inherit;
			font-weight: bold;
		}

		.flex-box > div {
			margin: 20px;
		}

		.flex-box + .flex-box {
			margin-top: 0;
		}

		.products-container {
			position: relative;
			display: flex;
			flex-direction: column;
		}

		@media (min-width: 680px) {
			.products-container {
				flex-direction: row;
			}
		}

		.product-box {
			color: #FFF;
			cursor: pointer;
			-webkit-tap-highlight-color: transparent;
		}

		.product-box > div {
			margin: 40px 20px;
		}

		.body--free .product-box--paid,
		.body--paid .product-box--free {
			transform: scale(0.95);
		}
		.product-box:hover {
			transform: scale(1);
			z-index: 1;
		}

		@media (min-width: 680px) {
			.product-box:hover {
				transform: scale(1.05);
			}
		}

		.product-box:hover {
			box-shadow: 0 5px 40px 0 rgba(0, 0, 0, .4) !important;
		}

		.product-box-title {
			font-size: 1.7em;
		}

		.product-box-title, .product-box-edition, .product-box-button {
			line-height: 1;
		}

		.product-box-edition {
			margin: 20px 0;
			opacity: .75;
			font-size: 1.125em;
			margin-top: -10px;
		}

		.product-box-button {
			text-transform: uppercase;
			background: none;
			border: 1px solid #FFF;
			border-radius: .1em;
			margin: 20px;
			color: #FFF;
			padding: 10px 20px;
		}

		.product-box-description {
			font-size: 1em;
			background: rgba(0, 0, 0, .4);
			border-radius: 6px;
			margin: -20px;
			margin-top: -40px;
			margin-bottom: -40px;
			padding: 40px 20px;
			background: -moz-linear-gradient(top, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 100%);
			background: -webkit-linear-gradient(top, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 100%);
			background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#000000', GradientType=0);
		}

		.product-box {
			background-color: #333;
			max-width: 320px;
		}

		.product-box--paid .product-box-logo {
			border-radius: 8%;
		}

		.product-box-logo {
			max-width: 100%;
			max-height: 150px;
			margin: 20px auto;
			display: block;
		}

		@media (min-height: 680px) {
			.product-box-logo {
				max-height: 180px;
			}
		}

		.box--install {
			background: rgba(255,255,255,.94);
		}
		.box--install input {
			font-family: monospace;
			font-size: 1em;
			width: 100%;
		}

		.install-log {
			background: rgba(0,0,0,.1);
			overflow: auto;
			max-height: 84px;
		}
		.install-log p {
			margin: 0;
		}

		.radius {
			border-radius: 3px;
		}

		.error-box {
			background: none;
			box-shadow: none;
		}
		.error-box a {
			font-weight: normal;
			text-decoration: underline;
			text-decoration-color: rgba(0,0,0,.25);
		}
		.error-box a:hover {
			text-decoration-style: solid;
			text-decoration-color: #000;
		}
		.error-box-code {
			opacity: .4;
			font-size: 0.9em;
			border-top: 1px solid rgba(0,0,0,.2);
			padding-top: 10px;
		}

		@media (min-width: 680px) {
			.col-8 {
				width: 310px;
			}
			.col-16 {
				width: 630px;
			}
			.flex-box + .flex-box {
				margin-top: 20px;
				margin-left: 0;
			}
		}

		.header {
			display: block;
			position: relative;
			flex-basis: 100%;
			text-align: center;
		}
		.body--free .header,
		.body--paid .header {
			color: #FFF;
		}

		.header-logo {
			height: 30px;
			width: auto;
			max-height: 100%;
			margin: 20px auto;
			display: block;
			fill: #25a7e0;
		}
		.body--free .header-logo,
		.body--paid .header-logo {
			fill: #FFF;
			filter: drop-shadow(1px 1px 1px rgba(0,0,0,.15));
		}

		.header-heading {
			margin: 20px;
			margin-bottom: 0;
			font-weight: 100;
			font-size: 1.6em;
		}

		.body--background .header-heading,
		.product-box {
			text-shadow: 1px 1px 1px rgba(0,0,0,.15);
			backface-visibility: hidden;
		}
	</style>
</head>
<body>
	<div class="flex flex--full wrapper">
	  <?php
            if ($page == 'error') {
                ?>
		<div class="container container--error">
			<div class="flex-box error-box col-16" >
				<div>
					<h1>Aw, Snap!</h1>
					<p>Your websever lacks some requirements that must be fixed to install Chevereto.</p>
					<p>Please check:</p>
					<ul>
						<?php
                            foreach ($RequirementsCheck->missing as $k => $v) {
                                ?>
						<li><?php echo $v['message']; ?></li>
						<?php
                            } ?>
					</ul>
					<p>If you already fixed your web server then make sure to restart it to apply changes. If the problem persists contact your server administrator. Check our <a href="https://chevereto.com/hosting" target="_blank">hosting</a> offer if you don't want to worry about this.</p>
					<p class="error-box-code">Server <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
				</div>
			</div>
		</div>
	  <?php
            } else {
                ?>
	  <div class="container container--splash animate animate--slow">
			<div class="header">
				<svg class="header-logo" xmlns="http://www.w3.org/2000/svg" width="501.76" height="76.521" viewBox="0 0 501.76 76.521"><path d="M500.264 40.068c-.738 0-1.422.36-1.814.963-1.184 1.792-2.36 3.53-3.713 5.118-1.295 1.514-5.34 4.03-8.7 4.662l-1.33.25.16-1.35.15-1.28c.11-.91.22-1.78.29-2.65.55-6.7-.03-11.69-1.89-16.2-1.68-4.08-3.94-6.57-7.11-7.85-1.18-.48-2.28-.72-3.26-.72-2.17 0-3.93 1.17-5.39 3.58-.15.25-.29.5-.46.78l-.67 1.18-.91-.75c-.42-.34-.82-.67-1.23-1.01-.95-.79-1.86-1.54-2.8-2.26-.76-.57-1.64-1.07-2.56-1.59-2-1.13-4.09-1.71-6.23-1.71-3.87 0-7.81 1.898-10.81 5.22-4.91 5.42-7.86 12.11-8.77 19.86-.11.988-.39 2.278-1.48 3.478-3.63 3.98-7.97 8.45-13.69 11.29-1.23.61-2.73 1.01-4.34 1.18-.18.02-.36.03-.52.03-.85 0-1.5-.26-1.95-.76-.48-.54-.66-1.3-.55-2.32.26-2.26.59-4.67 1.26-6.99 1.08-3.75 2.27-7.53 3.43-11.19.6-1.91 1.2-3.83 1.79-5.74.33-1.09 1.01-1.6 2.2-1.648 1.47-.06 2.89-.13 4.23-.45 1.96-.45 3.37-1.37 4.08-2.65.72-1.31.75-3.03.09-4.99-.06-.17-.12-.33-.19-.49l-7.18.69.28-1.33c.13-.65.27-1.27.4-1.88.3-1.36.58-2.66.8-3.94.38-2.22.59-4.81-.65-7.19-1.38-2.64-4.22-4.28-7.42-4.28-.71 0-1.43.08-2.14.25-5.3 1.24-9.3 4.58-12.23 7.472l1.76 9.7-1 .16c-.5.09-.96.16-1.39.22-.86.13-1.6.24-2.31.42-1.852.46-3.04 1.23-3.55 2.29-.51 1.05-.36 2.47.43 4.22.14.33.31.64.47.94l6.39-1.15-.26 1.42c-.15.82-.28 1.63-.41 2.42-.5 3.15-.98 6.13-2.72 8.97-5.55 9.07-11.52 15.36-18.76 19.79-2.17 1.33-5.11 2.91-8.52 3.33-.73.09-1.45.14-2.14.14-3.55 0-6.56-1.14-8.7-3.29-2.12-2.13-3.22-5.13-3.2-8.69l.01-1.33 1.28.38c.4.13.8.25 1.2.38.75.23 1.48.46 2.23.67 1.58.432 3.22.65 4.85.65 10.22-.01 18.46-8.11 18.76-18.46.18-6.32-2.4-10.77-7.66-13.25-2.14-1-4.41-1.49-6.97-1.49-1.3 0-2.69.14-4.13.4-7.34 1.35-13.38 5.54-18.48 12.83-1.97 2.81-3.57 6.02-5.18 10.42-.58 1.58-1.48 3.22-2.75 5.01-2.09 2.96-4.72 6.32-8.29 8.82-1.36.96-2.86 1.65-4.33 2.01-.34.08-.69.12-1.02.12-1.04 0-1.96-.4-2.61-1.12-.65-.73-.94-1.73-.81-2.81.31-2.67.858-4.9 1.67-6.84.9-2.15 1.938-4.27 2.95-6.32.818-1.66 1.67-3.37 2.42-5.08 1.42-3.2 1.96-6.22 1.648-9.21-.51-4.88-3.73-7.79-8.6-7.79-.23 0-.46.01-.69.02-4.13.23-7.65 2.102-10.89 3.99-1.23.72-2.44 1.51-3.73 2.36-.62.41-1.26.83-1.94 1.27l-3.05 1.96 1.61-3.25.3-.62c.16-.33.29-.59.43-.84 1.98-3.67 3.93-7.67 4.76-11.97.28-1.43.35-2.91.21-4.26-.21-2.16-1.398-3.34-3.34-3.34-.43 0-.9.06-1.39.18-2.14.52-4.19 1.67-6.26 3.51-5.9 5.27-8.87 11.09-9.07 17.81-.1 3.61.95 6.16 3.63 8.812l.55.55-.39.67c-.41.7-.82 1.41-1.22 2.12-.91 1.59-1.84 3.23-2.87 4.8-4.81 7.33-10.32 12.82-16.84 16.77-2.35 1.43-5.21 2.93-8.53 3.32-.71.08-1.42.12-2.1.12-7.03 0-11.61-4.38-11.96-11.44-.01-.22.02-.39.05-.53l.03-.16.19-1.12 1.09.33c.41.13.82.26 1.22.39.85.272 1.65.53 2.46.73 1.51.38 3.04.57 4.57.57 5.5 0 10.75-2.47 14.39-6.78 3.57-4.23 5.1-9.76 4.18-15.17-1-5.92-5.9-10.45-11.92-11.01-.89-.08-1.77-.13-2.64-.13-7.96 0-14.79 3.6-20.89 11-2.38 2.88-4.05 6.21-5.83 9.95-1.62 3.4-4.72 5.48-6.9 6.75-2.02 1.16-3.8 1.7-5.61 1.7-.19 0-.38 0-.57-.01l-1.25-.08.35-1.2c.25-.82.5-1.64.74-2.44.55-1.79 1.07-3.47 1.5-5.2 1.29-5.29 1.44-9.6.47-13.57-1.08-4.36-3.94-6.77-8.07-6.77-.44 0-.9.03-1.37.09-2.13.24-3.89 1.46-5.36 3.71-2.4 3.69-3.45 8.14-3.28 14.02.16 5.512 1.48 10.012 4.03 13.73.36.53.52 1.48.16 2.12-1.64 2.79-3.59 5.6-6.77 7.2-1.34.67-2.68 1.01-3.99 1.01-2.72 0-5.11-1.44-6.74-4.06-1.76-2.83-2.68-6.14-2.82-10.13-.27-7.69 1.44-14.86 5.08-21.33l.06-.11c.09-.19.23-.48.5-.71.89-.77.87-1.33-.1-3-1.64-2.85-4.5-4.55-7.66-4.55-2.64 0-5.19 1.17-7.16 3.28-2.98 3.19-4.91 7.32-6.08 12.99-.34 1.65-.54 3.37-.74 5.04-.1.9-.21 1.8-.33 2.69-.08.52-.2 1.12-.53 1.63-5.58 8.48-11.85 14.45-19.18 18.28-2.98 1.55-5.75 2.31-8.48 2.31-1.44 0-2.88-.22-4.3-.64-4.8-1.46-7.88-6.03-7.65-11.38l.06-1.29 1.24.37c.39.12.77.24 1.16.37.75.23 1.5.47 2.26.68 1.58.43 3.21.65 4.84.65 10.23-.01 18.47-8.11 18.77-18.45.18-6.33-2.4-10.78-7.66-13.25-2.14-1.01-4.41-1.5-6.97-1.5-1.3 0-2.69.14-4.12.4-7.35 1.35-13.39 5.54-18.49 12.818-2.24 3.2-3.94 6.66-5.05 10.28-.91 2.93-2.81 5.13-4.66 7.26l-.08.1c-2.25 2.6-4.84 4.94-6.83 6.68-.8.69-2.03 1.15-3.67 1.35-.18.03-.34.04-.5.04-.99 0-1.56-.408-1.86-.76-.47-.54-.64-1.28-.51-2.2.31-2.228.71-3.988 1.25-5.54.71-2.028 1.49-4.068 2.24-6.04.92-2.398 1.87-4.89 2.69-7.358 1.65-4.92 1.24-9.02-1.24-12.56-2.04-2.92-5.1-4.28-9.62-4.28h-.25c-5.89.07-12.67.82-18.42 6.23-.22.21-.43.55-.67.87-.31.44-.14.21-.51.76l-.62.87-.01.05-.01-.02.02-.03.15-.56 1.02-3.63c.78-2.772 1.58-5.63 2.28-8.46l.31-1.24c.67-2.65 1.36-5.392 1.53-8.07.28-4.2-2.6-7.6-6.83-8.08-.21-.02-.38-.09-.52-.17h-2.23c-4.61 1.09-8.87 3.61-13.03 7.7-.06.06-.14.19-.18.29 1.58 4.22 1.42 8.61 1.05 12.35-.6 6.12-1.43 12.64-2.6 20.49-.25 1.64-1.26 3.12-2.17 4.46-5.48 8.01-11.74 13.82-19.14 17.75-3.46 1.84-6.46 2.71-9.5 2.72-5.04 0-9.46-3.61-10.51-8.6-1.06-4.98-.4-10.14 2.08-16.21 1.23-3.04 3.11-6.9 6.67-9.73.94-.75 2.14-1.34 3.38-1.66.5-.12.99-.19 1.45-.19 1.22 0 2.28.46 2.97 1.29.77.92 1.04 2.23.78 3.7-.37 2.04-1.07 4.02-1.82 6.04-.45 1.21-1.12 2.49-1.98 3.8-.24.36-.29.48.16.96 1.09 1.16 2.45 1.73 4.17 1.73.38 0 .8-.03 1.22-.09 3.31-.47 6.13-2.16 7.95-4.76 1.84-2.64 2.47-5.93 1.76-9.26-1.59-7.46-7.19-11.73-15.35-11.73-.24 0-.49 0-.74.01-7.16.22-13.41 3.26-18.56 9.05-7.46 8.37-10.91 17.96-10.26 28.49.5 8.02 4.09 13.48 10.67 16.21 2.57 1.07 5.31 1.59 8.38 1.59 1.5 0 3.11-.13 4.78-.38 8.69-1.33 16.43-5.43 24.38-12.88.89-.83 1.8-1.63 2.61-2.34l.93-.82 1.8-1.6-.14 2.41c-.03.51-.07 1.07-.12 1.65-.11 1.398-.23 2.978-.19 4.52.05 1.59.33 3.17.81 4.58.96 2.77 3.34 4.29 6.78 4.29 2.56-.01 4.76-.71 6.51-2.06.26-.2.44-.49.46-.61.47-2.51.91-5.03 1.36-7.54.69-3.92 1.41-7.98 2.2-11.95.63-3.16 1.42-6.33 2.19-9.39.28-1.09.55-2.19.82-3.29.11-.43.38-1.22.99-1.66 3.13-2.23 6.01-3.27 9.09-3.27h.12c1.6.02 2.93.54 3.86 1.5.88.908 1.33 2.158 1.29 3.59-.07 2.39-.39 4.85-.95 7.318-.51 2.23-1.1 4.46-1.67 6.62-.65 2.45-1.32 4.98-1.86 7.49-.63 2.9-.41 5.83.65 8.47 1.18 2.95 3.54 4.55 7 4.76.3.02.59.03.89.03 3.36 0 6.64-1.12 10.33-3.53 3.9-2.54 7.44-5.94 11.48-11.02l.15-.19c.14-.19.29-.37.45-.56.25-.28.56-.35.62-.36l.95-.34.33.96c.2.61.39 1.21.58 1.82.41 1.32.79 2.56 1.33 3.73 2.65 5.75 7.27 8.94 14.11 9.78 1.26.16 2.53.23 3.78.23 5.41 0 10.79-1.392 16.45-4.26 6.83-3.472 12.86-8.602 17.92-15.25.19-.262.4-.5.58-.71l1.07-1.312.63 1.58c.41 1.03.8 2.08 1.2 3.14.88 2.35 1.8 4.79 2.9 7.08 1.67 3.45 4.11 6.07 7.24 7.81 2.49 1.37 5.1 2.07 7.77 2.07 2.29 0 4.7-.51 7.17-1.53 5.5-2.26 9.33-6.57 12.06-10.08.94-1.2 1.81-2.52 2.65-3.79.54-.82 1.08-1.64 1.64-2.44.09-.12.86-1.17 1.94-1.17h.01c.61.04 1.22.07 1.83.07 3.92 0 7.35-.87 10.49-2.66l1.3-.74.19 1.48c.09.73.17 1.45.24 2.16.16 1.5.3 2.92.63 4.28 2.12 8.97 8.068 13.76 17.69 14.23.538.03 1.068.04 1.59.04 5.51 0 11.048-1.44 16.468-4.27 11.81-6.18 20.342-15.86 26.06-29.59.23-.54.41-1.1.612-1.69.18-.55.36-1.09.568-1.63.23-.57.8-1.25 1.49-1.38.54-.1 1.08-.21 1.61-.32 1.75-.35 3.55-.71 5.38-.76l.17-.01c1.56 0 2.92.6 3.83 1.68.94 1.12 1.29 2.65 1 4.3-.36 2.01-.96 4.02-1.78 5.96-1.85 4.39-3.65 9.16-4.21 14.26-.48 4.28.14 7.26 2 9.67 1.7 2.21 4.05 3.24 7.4 3.24.52 0 1.07-.02 1.64-.07 3.51-.31 6.9-1.66 11-4.4 3.74-2.49 7.25-5.69 10.73-9.79.22-.26.45-.51.7-.81l1.65-1.87.5 1.78c.13.46.24.92.36 1.35.23.88.45 1.72.73 2.5 2.45 6.92 7.36 10.73 15 11.64 1.21.14 2.44.21 3.65.21 5.38 0 10.77-1.39 16.46-4.27 6.108-3.09 11.47-7.45 16.4-13.32.14-.17.278-.33.49-.56l2.188-2.49v2.65c0 .7-.02 1.38-.038 2.03-.04 1.34-.08 2.61.08 3.8.3 2.17.67 4.46 1.53 6.45 1.43 3.3 4.288 5.2 7.83 5.2 1.458 0 2.968-.32 4.49-.96 6.548-2.75 11.858-7.34 15.76-11.03 1.708-1.61 3.298-3.28 4.99-5.05.76-.8 1.52-1.59 2.288-2.39l1.13-1.16.53 1.54c.19.54.37 1.08.54 1.63.39 1.18.79 2.39 1.25 3.54 2.75 6.78 6.98 11.11 12.94 13.24 2.44.87 4.93 1.31 7.4 1.31 2.648 0 5.33-.5 7.98-1.51 7.84-2.97 13.78-8.08 17.68-15.21.88-1.6 2.01-2.06 3.45-2.24 6.88-.89 11.662-7.093 14.27-11.316.683-1.117 1.253-2.35 1.804-3.55.244-.526.482-1.054.738-1.567v-.334c-.324-.462-.86-.725-1.488-.725zM356.498 45.45c1.54-5.56 3.69-11.22 8.97-15.04.8-.58 1.81-1.05 3.02-1.39.47-.13.89-.19 1.28-.19 1.5 0 2.5.9 2.98 2.68.78 2.92-.09 5.63-.81 7.41-2.1 5.22-6.212 8.09-11.562 8.09-1 0-2.05-.11-3.11-.31l-1.06-.21.292-1.04zm-106.55.09c1.55-5.62 3.71-11.36 9.07-15.19.76-.55 1.76-.99 3.038-1.35.42-.12.82-.18 1.2-.18 1.54 0 2.63.99 2.9 2.63.29 1.76.29 3.49-.01 5.01-1.25 6.33-6.23 10.6-12.41 10.62-.66 0-1.3-.08-1.98-.17-.3-.04-.62-.07-.94-.11l-1.18-.12.31-1.14zm-115.21 0c1.55-5.62 3.72-11.36 9.06-15.19.77-.55 1.77-.99 3.04-1.35.42-.12.83-.18 1.21-.18 1.54 0 2.63.98 2.9 2.62.29 1.77.29 3.5-.01 5.01-1.24 6.34-6.22 10.61-12.4 10.63-.66 0-1.29-.08-1.96-.16-.31-.04-.64-.08-.97-.12l-1.19-.11.32-1.15zm334.02 5.43c-.67 4.82-2.8 8.46-6.32 10.8-1.52 1.01-3.17 1.55-4.77 1.55-3.22 0-5.97-2.19-7.17-5.73-1.48-4.38-1.37-9.13.33-14.54 1.52-4.818 3.93-8.318 7.38-10.71 1.73-1.198 3.92-1.92 5.85-1.92.1 0 .2 0 .3.01l.96.03v.97c0 .772-.01 1.54-.02 2.312-.03 1.67-.062 3.39.05 5.06.23 3.59 1.21 7.03 2.92 10.22.26.488.6 1.208.49 1.948z"/></svg>
				<h1 class="header-heading body-background-vhide edition-hide animate">Select what you want to install</h1>
				<h1 class="header-heading free--show soft-hidden animate">Free edition, just essential features</h1>
				<h1 class="header-heading paid--show soft-hidden animate">The complete package, all features included</h1>
			</div>
			<div class="products-container">
			  <div class="flex-box product-box product-box--free text-align-center col-8 animate" data-action="choose" data-arg="free">
			     <div>
			        <h2 class="product-box-title">Chevereto Free</h2>
			        <div class="product-box-edition">Free edition</div>
			        <img class="product-box-logo">
			        <p class="product-box-description">For personal use, Open Source and upgradable to paid edition.</p>
			     </div>
			  </div>
			  <div class="flex-box product-box product-box--paid text-align-center col-8 animate" data-action="choose" data-arg="paid">
			     <div>
			        <h2 class="product-box-title">Chevereto</h2>
			        <div class="product-box-edition">Paid edition</div>
			        <img class="product-box-logo">
			        <p class="product-box-description">For big websites, frequent updates and premium features.</p>
			     </div>
			  </div>
			</div>
	  </div>
	  <div class="container container--install animate animate--slow">
			<div class="flex-box box--install col-16">
				<div class="free--show">
					<h1>Install Chevereto Free</h1>
					<p>This installer will download and extract the latest <a href="https://chevereto.com/free" target="_blank">Chevereto Free</a> release in <code><?php echo __ROOT_PATH__; ?></code></p>
					<p>Your installation will be upgradable to our paid edition at any time from your dashboard panel.</p>
					<?php
                        if ($nginx) {
                            echo $nginx;
                        } ?>
					<div>
						<button class="radius" data-action="install" data-arg="free">Install Chevereto Free</button>
						<button class="radius" data-action="splash">Back</button>
					</div>
				</div>
				<div class="paid--show">
					<h1>Install Chevereto</h1>
					<p>This installer will download and extract the latest <a href="https://chevereto.com/" target="_blank">Chevereto</a> release in <code><?php echo __ROOT_PATH__; ?></code></p>
					<?php
                        if (isset($_REQUEST['UpgradeToPaid'])) {
                            ?>
					<p>All previous uploads won't get altered in any way.  The database schema will be upgraded. Backup your changes.</p>
					<?php
                        } ?>
					<?php
                        if ($nginx) {
                            echo $nginx;
                        } ?>
					<p>To proceed, enter your <a href="https://chevereto.com/panel/license" target="_blank">license key</a> below:</p>
					<p><input class="radius" type="text" name="key" id="key" placeholder="Paste license key here"></p>
					<div>
						<button class="radius" data-action="install" data-arg="paid">Install Chevereto</button>
						<?php
                            if (!isset($_REQUEST['UpgradeToPaid'])) {
                                ?>
						<button class="radius" data-action="splash">Back</button>
						<?php
                            } ?>
					</div>
				</div>
			</div>
	  </div>
	  <div class="container container--installing animate animate--slow">
	    <div class="flex-box box--install col-16">
	      <div class="free--show">
	        <h1>Installing Chevereto Free</h1>
	        <p>The software is being installed in <code>/home/var/public_html/</code>. Don't close this window until the process gets completed.</p>
	        <p>Install log:</p>
	        <div class="install-log"></div>
	      </div>
	      <div class="paid--show">
	        <h1>Installing Chevereto</h1>
	        <p>The software is being installed in <code>/home/var/public_html/</code>. Don't close this window until the process gets completed.</p>
	        <p>Install log:</p>
	        <div class="install-log"></div>
	      </div>
	    </div>
	  <?php
            }
      ?>
	</div>
	<script>
		var rootUrl = '<?php echo __ROOT_URL__; ?>';
		var installerFile = '<?php echo __INSTALLER_FILE__; ?>';
		var serverStr = '<?php echo __SERVER_STRING__; ?>';
		var remoteSrcUrl = '<?php echo Settings::get('chevereto')->src_url; ?>';
		var editions = {paid: 'Chevereto'};
		var onLeaveMessage = 'The installation is not yet completed. Are you sure that you want to leave?';
		var UpgradeToPaid = getParameterByName('UpgradeToPaid') == '';
		if(!UpgradeToPaid) {
			editions.free = 'Chevereto Free';
		}
		var title = document.title;
		var screens = {
			splash: {
				callee: 'splash',
				title: 'Chevereto Installer'
			},
			install: {
				callee: 'choose',
				title: 'Install %s'
			},
			installing: {
				callee: 'install',
				title: 'Installing %s'
			}
		}
		var products = {};
		for(var k in editions) {
			products[k] = {logo: remoteSrcUrl + '/' + k + '/logo.png', image: remoteSrcUrl + '/' + k + '/bkg.jpg'};
		}
		var html = document.documentElement;
		var page = html.getAttribute('id');
		var body = document.getElementsByTagName('body')[0];
		var wrapper = document.querySelector('.wrapper');
		var key = document.getElementById('key');
		var background = document.createElement('div');

		function getParameterByName(name, url) {
			if (!url) {
				var url = window.location.href;
			}
			var name = name.replace(/[\[\]]/g, '\\$&');
			var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
			var results = regex.exec(url);
			if (!results) {
				return null;
			}
			if (!results[2]) {
				return '';
			}
			return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}

		var installer = {
			init: function() {
				var self = this;
				var state = {
					screen: UpgradeToPaid ? 'install' : 'splash'
				};
				if(UpgradeToPaid) {
					state.arg = 'paid';
				}
				history.replaceState(state, title);
				background.setAttribute('class', 'background');
				wrapper.insertBefore(background, wrapper.firstChild);
				if(page != 'error') {
			  	this.preload();
			  	this.bindActions();
			  }
				var resizeTimer;
				window.onresize = function(e) {
					var nodes = background.childNodes;
					for(i = 0; i < nodes.length; i++) {
						var node = nodes[i];
						if(node.classList.contains('animate')) {
							node.setAttribute('style', 'transition: none;')
						}
					}
					clearTimeout(resizeTimer);
					  resizeTimer = setTimeout(function() {
							for(i = 0; i < nodes.length; i++) {
								nodes[i].removeAttribute('style');
							}
					  }, 250);
				};
				window.onbeforeunload = function (e) {
					if(!body.classList.contains('body--working')) {
						return;
					}
					e.returnValue = onLeaveMessage;
					return onLeaveMessage;
			  };
				window.onpopstate = function(e) {
					if(body.classList.contains('body--working')) {
						if(confirm(onLeaveMessage)) {
							installer.XHR.abort();
						} else {
							return;
						}
					}
					var state = e.state;
					var screen = state.screen;
					var arg = state.arg;
					var screen2Action = {
						splash: 'splash',
						install: 'choose',
						installing: 'install'
					}
					var action = screens[screen].callee;
					self.actions[action](arg, false);
				};
			},
			bindActions: function() {
				var self = this;
				var triggers = document.querySelectorAll('[data-action]');
				for(var i=0; i<triggers.length; i++) {
					var trigger = triggers[i];
					trigger.addEventListener('click', function(e) {
						var el = e.currentTarget;
						var action = el.dataset.action;
						var arg = el.dataset.arg;
						self.actions[action](arg);
					});
				}
			},
			history: function(push, screen, edition) {
				if(typeof push == typeof undefined) {
					var push = true;
				}
				document.title = screens[screen].title.replace(/%s/g, editions[edition]);
				if(push) {
					history.pushState({screen: screen, arg: edition}, screen);
				}
			},
			actions: {
				splash: function(arg, push) {
					body.classList = '';
					body.classList.add('body--splash');
					installer.history(push, 'splash');
				},
				choose: function(arg, push) {
					if(!arg) {
						return;
					}
					body.classList = '';
					body.classList.add('body--install', 'body--' + arg);
					installer.history(push, 'install', arg);
        },
        install: function(arg, push) {
					if(!arg) {
						return;
					}
					if(arg == 'paid' && !installer.getKeyValue()) {
						key.focus();
						return;
					}
					var edition = installer.getActiveEdition();

					installer.history(push, 'installing', arg);
					body.classList = '';
					body.classList.add('body--installing', 'body--' + edition.id);

					installer.setWorking(true);

					if(typeof installer.XHR == typeof undefined) {
						installer.log(serverStr);
					}

					// Yo dawg! I put a promise on top of a promise so you can promise while you promise
					installer.log('Trying to download latest ' + edition.label + ' release');
					var downloadParams = {edition: arg};
					if(edition.id == 'paid') {
						downloadParams.license = installer.getKeyValue();
					}
					installer.request('download', downloadParams).then(
						function(response) {
							if(response.status.code != 200) {
								installer.setWorking(false);
								document.title = 'Download failed';
								installer.log(document.title + ': ' + response.response.message + ' - Installation aborted');
								return;
							}
							installer.log(response.response.message);
							installer.log('Trying to extract ' + response.data.download.fileBasename);
							installer.request('extract', {fileBasename: response.data.download.fileBasename}).then(
								function(response) {
									if(response.status.code != 200) {
										installer.setWorking(false);
										document.title = 'Extraction failed';
										installer.log(document.title + ': ' + response.response.message + ' - Installation aborted');
										return;
									}
									installer.log(response.response.message);
									var s = 3;
									var to = UpgradeToPaid ? 'install' : 'setup';
									installer.log('Redirecting to ' + to + ' form in ' + s + 's');
									setTimeout(function() {
										installer.log('Redirecting now!');
										installer.setWorking(false);
										var redirectUrl = rootUrl;
										if(UpgradeToPaid) {
											redirectUrl += 'install';
										}
										window.location.replace(redirectUrl);
									}, 1000*s);
								},
								function(error) {
									installer.setWorking(false);
									installer.log(error);
								}
							);
					  },
						function(error) {
							installer.setWorking(false);
							installer.log(error);
					  }
					);
        }
      },
			setWorking: function(bool) {
				body.classList[bool ? 'add' : 'remove']('body--working');
			},
			preload: function() {
				var self = this;
				body.classList.add('body--loading');
				var preloader = document.createElement('div');
				preloader.setAttribute('id', 'preloader');
				preloader.setAttribute('class', 'animate');
				var spinner = document.createElement('div');
				spinner.setAttribute('class', 'spinner');
				preloader.insertBefore(spinner, preloader.firstChild);
				wrapper.insertBefore(preloader, wrapper.firstChild);
				var loadImages = [];
				for (var k in products) {
					var product = products[k];
					var oppo = installer.getOppositeEdition(k);
					var bkgs = [
						{
							placeholder: {
								src: oppo in products ? products[oppo].image : null,
								classes: ['background-placeholder'],
							}
						},
						{
							img: {
								src: product.image,
								classes: ['background-' + k, 'animate', 'animate--slow']
							}
						}
					];
					for (var i = 0; i < bkgs.length; i++) {
						for (var image in bkgs[i]) {
							var props = bkgs[i][image];
							if(!props.src) {
								continue;
							}
							var img = document.createElement('img');
							img.classList.add(...props.classes);
							img.setAttribute('src', props.src);
							loadImages.push(img);
							background.appendChild(img);
						}
					}
					var productBox = document.querySelector('.product-box--' + k);
					if(productBox) {
						var productBoxLogo = productBox.querySelector('.product-box-logo');
						productBox.style.backgroundImage = 'url(' + product.image + ')';
						productBoxLogo.setAttribute('src', product.logo);
						loadImages.push(productBoxLogo);
					}
				}
				var loadedImages = 0;
				function completeImage() {
					++loadedImages;
					if (loadImages.length == loadedImages) {
						if(UpgradeToPaid) {
							self.actions.choose('paid', false);
						} else {
							body.classList.add('body--splash');
							self.chooser();
						}
						body.classList.remove('body--loading', 'body--slowload');
					}
				}
				for (var i = 0; i < loadImages.length; i++) {
					var img = loadImages[i];
					if (img.complete) {
						completeImage();
					} else {
						img.addEventListener('load', completeImage, true);
						img.addEventListener('error', completeImage, true);
					}
				}
				if (loadImages.length != loadedImages) {
					setTimeout(function() {
						if (loadImages.length != loadedImages) {
							body.classList.add('body--slowload');
						}
					}, 2000);
				}
				var loader = document.createElement('div');
				loader.classList.add('loader', 'animate');
				var boxInstall = document.querySelector('.container--installing .box--install');
				boxInstall.insertBefore(loader, boxInstall.firstChild);
			},
			chooser: function() {
				var productBoxes = document.querySelectorAll('.product-box');
				var bkgTime = 400;
				for (var i=0; i < productBoxes.length; i++) {
					var productEl = productBoxes[i];
					productEl.onmouseenter = productEl.onmouseleave = function(e) {
						if (!body.classList.contains('body--splash')) {
							return;
						}
						var el = e.currentTarget;
						var product = el.classList.contains('product-box--paid') ? 'paid' : 'free';
						body.classList.remove('body--free', 'body--paid');
						if (e.type == 'mouseenter') {
							body.classList.add('body--' + product);
							setTimeout(function() {
								if (body.classList.contains('body--free') || body.classList.contains('body--paid')) {
									body.classList.add('body--background');
								}
							}, bkgTime);
						} else {
							setTimeout(function() {
								if (!body.classList.contains('body--free') || !body.classList.contains('body--paid')) {
									body.classList.remove('body--background');
								}
							}, bkgTime);
						}
					}
				}
				var productsContainer = document.querySelector('.products-container');
				if(productsContainer) {
					productsContainer.onmouseleave = function(e) {
						body.classList.remove('body--background');
					}
				}
			},
			getKeyValue: function() {
				return key.value.replace(/\s/g, '');
			},
			getOppositeEdition(edition) {
				return edition.toLowerCase() == 'free' ? 'paid' : 'free';
			},
			getActiveEdition: function() {
				var ret;
				var edition;
				for(var k in editions) {
					ret = body.classList.contains('body--' + k);
					if(ret) {
						return {
							id: k,
							label:editions[k]
						}
					}
				}
				return;
			},
			log: function(message) {
				var date = new Date();
				var t = {
					h: date.getHours(),
					m: date.getMinutes(),
					s: date.getSeconds(),
				}
				for(var k in t) {
					if(t[k] < 10) {
						t[k] = '0' + t[k];
					}
				}
				var time = t.h + ':' + t.m + ':' + t.s;
				var el = document.querySelector('.' + installer.getActiveEdition().id + '--show .install-log');
				var p = document.createElement('p');
				var t = document.createTextNode(time + ' ' + message);
				p.appendChild(t);
				el.appendChild(p);
				el.scrollTop = el.scrollHeight;
			},
			request: function(action, args) {
				return new Promise(function(resolve, reject) {
					var edition = installer.getActiveEdition();
					var postData = new FormData();
					args.action = action;
					args.edition = edition.id;
					for(var i in args) {
						postData.append(i, args[i])
					}
					installer.XHR = new XMLHttpRequest();
					installer.XHR.responseType = 'json';
					installer.XHR.open('POST', installerFile, true);
					installer.XHR.addEventListener('load', function(e) {
						resolve(e.currentTarget.response);
					});
					installer.XHR.addEventListener('abort', function(e) {
						reject('Process aborted by user');
					});
					installer.XHR.addEventListener('error', function(e) {
						reject('Transfer failed');
					});
					installer.XHR.send(postData);
				});
			}
		};
		installer.init();
	</script>
</body>
</html>