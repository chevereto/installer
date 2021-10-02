<?php

final class RequirementsCheck
{
    public Requirements $requirements;

    public Runtime $runtime;

    public array $errors = [];

    public array $missed = [];

    const EXTENSIONS_MAP = array(
        'curl' => 'book.curl',
        'hash' => 'book.hash',
        'json' => 'book.json',
        'mbstring' => 'book.mbstring',
        'PDO' => 'book.pdo',
        'PDO_MYSQL' => 'ref.pdo-mysql',
        'session' => 'book.session',
        'zip' => 'book.zip',
    );

    const CLASSES_MAP = array(
        'DateTime' => 'class.datetime',
        'DirectoryIterator' => 'class.directoryiterator',
        'Exception' => 'class.exception',
        'PDO' => 'class.pdo',
        'PDOException' => 'class.pdoexception',
        'RegexIterator' => 'class.regexiterator',
        'RecursiveIteratorIterator' => 'class.recursiveiteratoriterator',
        'ZipArchive' => 'class.ziparchive',
    );

    public function __construct(Requirements $requirements, Runtime $runtime)
    {
        $this->checkPHPVersion($requirements->phpVersions);
        $this->checkPHPProfile($requirements->phpExtensions, $requirements->phpClasses);
        $this->checkWorkingPaths($runtime->workingPaths);
        $this->checkFileUploads();
        $this->checkApacheModRewrite();
        $this->checkUtf8Functions();
        $this->checkCurl();
        $this->checkImageLibrary();
        if (!$this->isMissing('cURL')) {
            $this->checkSourceAPI();
        }
    }

    public function checkImageLibrary(): void
    {
        $image_lib = [
            'gd' => extension_loaded('gd') && function_exists('gd_info'),
            'imagick' => extension_loaded('imagick'),
        ];
        if(!$image_lib['gd'] && !$image_lib['imagick']) {
            $this->addMissing('GD', 'https://www.php.net/manual/en/book.image.php', 'No %l library support in this PHP installation.');
            $this->addMissing('Imagick', 'https://www.php.net/manual/en/book.imagick.php', 'No %l library support in this PHP installation.');
            $this->addMissing('PHP', '', 'Enable either Imagick extension or GD extension to perform image processing.');
        }
    }

    public function checkPHPVersion(array $phpVersions): void
    {
        if (version_compare(PHP_VERSION, $phpVersions[0], '<')) {
            $this->addMissing('PHP', 'https://php.net', 'Use a newer %l version (%c ' . $phpVersions[0] . ' required, ' . $phpVersions[1] . ' recommended)');
        }
    }

    public function checkPHPProfile(array $extensions, array $classes): void
    {
        $core = array(
            'extensions' => array_intersect_key(static::EXTENSIONS_MAP, array_flip($extensions)),
            'classes' => array_intersect_key(static::CLASSES_MAP, array_flip($classes)),
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
                $function = function($var) use ($core_check) {
                    return @$core_check[1]($var);
                };
            }
            foreach ($array as $k => $v) {
                if (($loaded && !in_array(strtolower($k), $loaded)) || (isset($function) && $function($k))) {
                    $missing['c'][] = $k;
                    $missing['l'][] = 'http://www.php.net/manual/' . $v . '.php';
                }
            }
            if ($missing) {
                $l = array();
                $c = array();
                $message = 'Enable PHP %n %l.';
                if (count($missing['c']) == 1) {
                    $missing_strtr = array('%n' => $n[0]);
                    $message = strtr($message, $missing_strtr);
                    $this->addMissing($missing['c'][0], $missing['l'][0], $message);
                } else {
                    foreach ($missing['l'] as $k => $v) {
                        $l[] = '%l' . $k;
                    }
                    $last = array_pop($l);
                    $missing_strtr['%l'] = implode(', ', $l) . ' and ' . $last;
                    $missing_strtr['%n'] = $n[1];
                    $message = strtr($message, $missing_strtr);
                    $this->addBundleMissing($missing['c'], $missing['l'], $message);
                }
            }
        }
    }

    public function checkWorkingPaths(array $workingPaths): void
    {
        $rw_fn = array('read' => 'is_readable', 'write' => 'is_writeable');
        foreach ($workingPaths as $var) {
            foreach (array('read', 'write') as $k => $v) {
                if (!@$rw_fn[$v]($var)) {
                    $permissions_errors[] = $v;
                }
            }
            if (isset($permissions_errors)) {
                $error = implode('/', $permissions_errors);
                $message = "PHP don't have  %l permission in <code>" . $var . '</code>';
                $this->addMissing($error, 'https://unix.stackexchange.com/questions/35711/giving-php-permission-to-write-to-files-and-folders', $message);
                unset($permissions_errors);
            }
        }
    }

    public function checkFileUploads(): void
    {
        if (!ini_get('file_uploads')) {
            $this->addMissing('file_uploads', 'http://php.net/manual/en/ini.core.php#ini.file-uploads', 'Enable %l (needed for file uploads)');
        }
    }

    public function checkApacheModRewrite(): void
    {
        if (isset($_SERVER['SERVER_SOFTWARE']) && preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) && function_exists('apache_get_modules') && !in_array('mod_rewrite', apache_get_modules())) {
            $this->addMissing('mod_rewrite', 'http://httpd.apache.org/docs/current/mod/mod_rewrite.html', 'Enable %l (needed for URL rewriting)');
        }
    }

    public function checkUtf8Functions(): void
    {
        $utf8_errors = array();
        foreach (array('utf8_encode', 'utf8_decode') as $v) {
            if (!function_exists($v)) {
                $utf8_errors['c'][] = $v;
                $utf8_errors['l'][] = 'http://php.net/manual/en/function.' . str_replace('_', '-', $v) . '.php';
            }
        }
        if ($utf8_errors) {
            $this->addBundleMissing($utf8_errors['c'], $utf8_errors['l'], count($utf8_errors['c']) == 1 ? 'Enable %l function' : 'Enable %l0 and %l1 functions');
        }
    }

    public function checkCurl(): void
    {
        if (!function_exists('curl_init')) {
            $this->addMissing('cURL', 'http://php.net/manual/en/book.curl.php', 'Enable PHP %l');
        }
    }

    public function checkSourceAPI(): void
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, VENDOR['apiUrl']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $headers = curl_exec($ch);
        $http_statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($headers) {
            if ($http_statusCode != 200) {
                $http_error_link = '<a href="https://en.wikipedia.org/wiki/HTTP_' . $http_statusCode . '" target="_blank">HTTP ' . $http_statusCode . '</a>';
                $this->addMissing('Chevereto API', VENDOR['apiUrl'], "An $http_error_link error occurred when trying to connect to %l");
            }
        } else {
            $api_parse_url = parse_url(VENDOR['apiUrl']);
            $api_offline_link = '<a href="https://isitdownorjust.me/' . $api_parse_url['host'] . '" target="_blank">offline</a>';
            $this->addMissing('Chevereto API', VENDOR['apiUrl'], "Can't connect to %l. Check for any outgoing network blocking or maybe our server is $api_offline_link at this time");
        }
    }

    protected function addMissing(string $component, string $url, string $msgTpl): void
    {
        $this->addBundleMissing([$component], [$url], strtr($msgTpl, ['%c' => '%c0', '%l' => '%l0']));
    }

    /**
     * @param array  $components ['component1', 'component2',]
     * @param array  $urls       ['component1_url', 'component2_url',]
     * @param string $msgTpl     The message template. Use %l0 and %c0 placeholders
     */
    protected function addBundleMissing(array $components, array $urls, string $msgTpl): void
    {
        $placeholders = array();
        foreach ($components as $k => $v) {
            $this->missed[] = $v;
            $placeholders['%c' . $k] = $v;
            $placeholders['%l' . $k] = '<a href="' . $urls[$k] . '" target="_blank">' . $v . '</a>';
        }
        $message = strtr($msgTpl, $placeholders);
        $this->errors[] = $message;
    }

    public function isMissing(string $key): bool
    {
        return in_array($key, $this->missed);
    }
}
