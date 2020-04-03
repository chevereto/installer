<?php
/* --------------------------------------------------------------------

    Chevereto Installer
    http://chevereto.com/

    @author	Rodolfo Berrios A. <http://rodolfoberrios.com/>

      /$$$$$$  /$$                                                           /$$
     /$$__  $$| $$                                                          | $$
    | $$  \__/| $$$$$$$   /$$$$$$  /$$    /$$ /$$$$$$   /$$$$$$   /$$$$$$  /$$$$$$    /$$$$$$
    | $$      | $$__  $$ /$$__  $$|  $$  /$$//$$__  $$ /$$__  $$ /$$__  $$|_  $$_/   /$$__  $$
    | $$      | $$  \ $$| $$$$$$$$ \  $$/$$/| $$$$$$$$| $$  \__/| $$$$$$$$  | $$    | $$  \ $$
    | $$    $$| $$  | $$| $$_____/  \  $$$/ | $$_____/| $$      | $$_____/  | $$ /$$| $$  | $$
    |  $$$$$$/| $$  | $$|  $$$$$$$   \  $/  |  $$$$$$$| $$      |  $$$$$$$  |  $$$$/|  $$$$$$/
     \______/ |__/  |__/ \_______/    \_/    \_______/|__/       \_______/   \___/   \______/

  --------------------------------------------------------------------- */

declare(strict_types=1);

/* --- Begins: Dev editable --- */
const APP_NAME = 'Chevereto Installer';
const APP_VERSION = '2.0.0';
const APP_URL = 'https://github.com/chevereto/installer';

const PHP_VERSION_MIN = '7.0';
const PHP_VERSION_RECOMMENDED = '7.3';

const VENDOR = [
    'name' => 'Chevereto',
    'url' => 'https://chevereto.com',
    'apiUrl' => 'https://chevereto.com/api',
    'apiLicense' => 'https://chevereto.com/api/license/check',
];

const APPLICATIONS = [
    'chevereto' => [
        'name' => 'Chevereto',
        'license' => 'Paid',
        'url' => 'https://chevereto.com',
        'zipball' => 'https://chevereto.com/api/download/latest',
        'folder' => 'chevereto',
        'vendor' => VENDOR,
    ],
    'chevereto-free' => [
        'name' => 'Chevereto-Free',
        'license' => 'Open Source',
        'url' => 'https://github.com/Chevereto/Chevereto-Free',
        'zipball' => 'https://api.github.com/repos/Chevereto/Chevereto-Free/releases/latest',
        'folder' => 'Chevereto/Chevereto-Free-',
        'vendor' => VENDOR,
    ],
];

$patterns = [
    'username_pattern' => '^[\w]{3,16}$',
    'user_password_pattern' => '^.{6,128}$',
    'email_pattern' => "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$",
];

$phpSettings = [
    'error_reporting' => E_ALL ^ E_NOTICE,
    'log_errors' => true,
    'display_errors' => true,
    'error_log' => __DIR__ . '/installer.error.log',
    'time_limit' => 0,
    'default_charset' => 'utf-8',
    'LC_ALL' => 'en_US.UTF8',
];

$phpExtensions = [
    'curl',
    'hash',
    'json',
    'mbstring',
    'PDO',
    'PDO_MYSQL',
    'session',
];

$phpClasses = [
    'DateTime',
    'DirectoryIterator',
    'Exception',
    'PDO',
    'PDOException',
    'RegexIterator',
    'RecursiveIteratorIterator',
    'ZipArchive',
];

$themeColor = '#ecf0f1';

/** @var string a base64 image */
$shortcutIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAMAAABOo35HAAAAM1BMVEUjqOD////J6vcxreKR1PDy+v1avuit3/PW7/nk9PtMuOY/s+R2yexow+q75PWf2fKEzu6NmHjuAAAHmklEQVR4XuzAAQ0AAADCIPundu8BCwAAAAAAAAAAAAAAAAAAAAAAAADA2bGbHQdhIAbA45n8ECCB93/aldrLVkVbc9hDFX+PYIHjpB8ND37smxGmtdaM35bdLknsDW9atzcSKeNSDXslj6iuLS9pSW8AmLRkG/jbsCexM+OT56EoMfBZDhOztYFxmtgOTjOpYK2qK5D0H8YCXlVWPNcxyFtsYpFxj02sAPqyaBk0dVZVWLwOmqZD4JZkU2u4o+teSNLd8NTM4hXNLN6mw/AG8PRSmsErNjkHz2gKyxWW9jvPtd95A7Sw2VVNUp6DdSgsV2XxXJXFc76yxPnKksZXloAVJnxlycZXlpQJKmsrKQ33BgDunlKJfw7LvtMPdde26DoKQrcoeE/y/187TzM9s3vaRCDQxudEsxRYImBLG4bnp+bJACxJfVmdRkoA8N+QCuxH+wykZn7jB8ZMDLDYvqw+dng5kvkJuW0nTx33eGjoedbqyUiSJ9kY5ZqNpxsIPP5G6uQF58S7cTKRzHS3xwRcztuJqTJG4pSwdd5q0+akx0lG4gelknUIi89QBqszoPJB6wjrz1ClWXV1gT8amC8rGVpysPJCLobnPmliCPehdSzsdehkWfkf+u+B+2DT46Txget6qz5JSPq6NV92z2T+QJJLYo2+toCLfxtBMA6MRok1otaUnMpNOGvJACsMwgd0aBb+ZNk4qglW0kYqYfDblI5jfgFWAVRo1iEeSv4A3V7SJKK9srXWtSVTgvSp3ljh3s8LWGQWzdJv3TdqKsUrMossyqvfpidvL+2iPE216NtPJQ9j3akGLDkUayP/3XRjGJfBUq1SDPwdNbEyEh0iR7X27wdrY5EWjtKirwfr4BE8jmod3w5WY3bLqaeWvh2swnN3RM4wc/hu6rAzvS7EAQu+G6zG7TRxtmVoBtZ0EMKybkLBgGb5ZKIntn8KGWBxmANASilD8C/400/kIjP+G9TKhWAe7fEumBhDvnXCzliSmxJzgN9qh9BVvxO/y3LykpQ55C46e2rmyQ4YOZu8qcActs7RGo+GDguLtUWKYuZQSRqxkh0WFkcKi5g57G/6rV4sa/Cnp7PqVrRLEkRytzTaZxz1N2NmvUVy1UxOblISVGOvLIKT5P8ZnWwh8OWeeEfSu8IRls+hYRf0uPGYc1H4Tx8pzPweO29hRA2b7yOFyJeHzNtlHBqhli5SOPipkZ15Q0dVUFnkUnQL+D0CL0xyaCwK8ghz6PweJw+reGVhbQpgbebHX2X9p0uXx88cCsHOw9qbHI5lmCEqpEY3OVgYzaWwLW7w8NCIUkaFyKhsbgvr4rtbV0ms2BQiw8k+umEp+DQT48CNZ/Q94iORqySf09rg6AwVyVRZ3cH53sRUZSYAgJwoKiaRo0JOWTcnDuhT9wLkqIN9QBb43IiS5MZw2PtIk8HUsAxZta+MFE3nJ+kduXeHUx2y5CqkeOQ+5Kc6+nMdXa5wSuJYMvQoq+qT7TnFdG13KFFYDeaFs56jR1CWHVk5VBO5psEk+4E1dFMEd4c40m4FVkPd+yoKVwob7rcZc/DAKpBUZdU3A8EPBysupvKLVdb+ziLTXTQLPEpEFLHKmu8GMg3AMsMqZLHKiu9eS3ZgybGS6/fIW5o53AtWcMAq0D0qKwcDsKyxCmKVNV5gdTdYzR6rKlZZ9AKru8Ga5liFTUyl/4qVAVjJvkxL0j/qjCUYgCWKrSC8J+Utr5rwWIIJWCjaO7Nal7rf8zNWNmCFZl0xEGUq65k5tBKswNqZqh3+joSC/32sfaFhMAOralbBG0VhdvKSIA/Uip0kRiAs3y2K7Uejv7qwOAn0DqzolkzQuL0sXqqw2elXszk77ZXRgajjMHQYQ+nXwrEFKkt+GiJP6Vj6etzfxeQqOLN2txqAhfF5VvF4iMwI4/Mhm9U933QrvPftPd6ksNmRYYVdeHyvxkwTnqxNkm926MbqqTpdYxNd35AWlHNnTK/RpfDx6uqdp7DDBSlOcmOYHQu8Fh3HCcEljZfkrA4MlLvcEMNrURxwcU0mgyxDBgdiRCszb7LpO17WdpunMcSmGVR63p5u4aL0Xo5r086KsDOEckKM+aD47w1wgOdKYhGsKLVIcrLoVC90i6vaGZlcxyqdDgzr9ILcxcEdi3UMsXwuQf5PfIJlvLTEF6yBXFj0sfJfWhBZ9Js4LNo0S3Mzqw2a5KEV4FzjvKO6CDJDlivDT2KdVj60GYOgnNJZmzyszAVR7qDoDOmVsVIk2wtR5Mvq0ao8ZqewFMInqq1KoiXclFM1IH7uJU4hRZl2VN6fpQ++8ip3ocYB1YwppM+9pA+IYUo45LHLlae8RRBDdd5IQWwywybrtySGSsbAiZHd4nc9ObEoRG46dhejkn4tRpf8JlwlC0dU8vpj07FGdRjgxLg0HTOD9BHKJacVJlT6cNWLSE09SwK0Osi/I04/5s883SzC0SS68f/frzvjY/33nGIe8cfliTO/rOi3HaTw/QRQQgHYBxv2duwAGALAlh5f8WmRUoY/ICuQ06B/2oFjAgAAAIRB9k9thv2wCgAAAAAAAAAAAAAAAAAAAAAAAADgRsdoeIKK/iEAAAAASUVORK5CYII=';

/* --- Ends: Dev editable --- */

define('ERROR_LOG_FILEPATH', $phpSettings['error_log']);
const INSTALLER_FILEPATH = __FILE__;

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
const ERROR_TABLE = [
    E_ERROR => 'Fatal error',
    E_WARNING => 'Warning',
    E_PARSE => 'Parse error',
    E_NOTICE => 'Notice',
    E_CORE_ERROR => 'Core error',
    E_CORE_WARNING => 'Core warning',
    E_COMPILE_ERROR => 'Compile error',
    E_COMPILE_WARNING => 'Compile warning',
    E_USER_ERROR => 'Fatal error',
    E_USER_WARNING => 'Warning',
    E_USER_NOTICE => 'Notice',
    E_STRICT => 'Strict standars',
    E_RECOVERABLE_ERROR => 'Recoverable error',
    E_DEPRECATED => 'Deprecated',
    E_USER_DEPRECATED => 'Deprecated',
];

set_error_handler(function (int $severity, string $message, string $file, int $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}, $phpSettings['error_reporting']);

set_exception_handler(function (Throwable $e) {
    $trace = $e->getTrace();
    $traceTemplate = "#%k% %file%:%line%\n%class%%type%%function%%args%";
    $argsTemplate = "Arg#%k%\n%arg%";

    switch (true) {
        case $e instanceof ErrorException:
            $type = ERROR_TABLE[$e->getSeverity()];
            $thrown = $e->getMessage();

            array_shift($trace);
            array_shift($trace);
            break;
        case $e instanceof Error:
            $type = 'PHP';
            $thrown = $e->getMessage();
            break;
        default:
            $type = 'Exception';
            $thrown = $type . ' thrown';
            break;
    }
    $message = 'in ' . $e->getFile() . ':' . $e->getLine();
    $retrace = [];
    foreach ($trace as $k => $v) {
        $args = [];
        foreach ($v['args'] as $ak => $av) {
            $arg = var_export($av, true);
            $args[] = strtr($argsTemplate, [
                '%k%' => $ak,
                '%arg%' => $arg,
            ]);
        }
        $retrace[] = strtr($traceTemplate, [
            '%k%' => $k,
            '%file%' => $v['file'] ?? '',
            '%line%' => $v['line'] ?? '',
            '%class%' => $v['class'] ?? '',
            '%type%' => $v['type'] ?? '',
            '%function%' => $v['function'] ?? '',
            '%args%' => empty($args) ? '' : ("\n--\n" . implode("\n--\n", $args)),
        ]);
    }
    $cols = 80;
    $hypens = str_repeat('-', $cols);
    $halfHypens = substr($hypens, 0, $cols / 2);
    $stack = implode("\n$halfHypens\n", $retrace);
    $tags = [
        '%type%' => $type,
        '%datetime%' => date('Y-m-d H:i:s'),
        '%thrown%' => $thrown,
        '%message%' => $message,
        '%stack%' => $stack,
        '%trace%' => empty($retrace) ? '' : "Trace:\n"
    ];
    $screenTpl = '<h1>[%type%] %thrown%</h1><p>%message%</p>' . "\n\n" . "%trace%<pre><code>%stack%</code></pre>";
    $textTpl = "%datetime% [%type%] %thrown%: %message%\n\n%trace%%stack%";

    $text = "$hypens\n" . strtr($textTpl, $tags) . "\n$hypens\n\n";

    append(ERROR_LOG_FILEPATH, $text);

    echo strtr($screenTpl, $tags);
    die();
});
class Logger
{
    /** @var string */
    public $name;
    /** @var array */
    public $log;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->log = array();
    }

    public function addMessage(string $message)
    {
        $this->log[] = $message;
    }
}
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
        $inputSubdir = $subdir;
        $subdir = rtrim($subdir, '/') . '/';
        // Extract files
        $folderExists = false;
        for ($i = 0; $i < $this->numFiles; ++$i) {
            $filename = $this->getNameIndex($i);
            if (!$folderExists && $filename == $subdir) {
                $folderExists = true;
            }
            if (substr($filename, 0, mb_strlen($subdir, 'UTF-8')) == $subdir) {
                $relativePath = substr($filename, mb_strlen($subdir, 'UTF-8'));
                $relativePath = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $relativePath);
                if (mb_strlen($relativePath, 'UTF-8') > 0) {
                    if (substr($filename, -1) == '/') {
                        if (!is_dir($destination . $relativePath)) {
                            if (!mkdir($destination . $relativePath, 0755, true)) {
                                $errors[$i] = $filename;
                            }
                        }
                    } else {
                        if (dirname($relativePath) != '.') {
                            if (!is_dir($destination . dirname($relativePath))) {
                                // New dir (for file)
                                mkdir($destination . dirname($relativePath), 0755, true);
                            }
                        }
                        if (file_put_contents($destination . $relativePath, $this->getFromIndex($i)) === false) {
                            $errors[$i] = $filename;
                        }
                    }
                }
            }
        }

        if (!$folderExists) {
            throw new Exception(sprintf("Folder %s doesn't exists in zip file", $inputSubdir));
        }

        return $errors;
    }
}
class Requirements
{
    /** @var array */
    public $phpVersions;

    /** @var array */
    public $phpExtensions;

    /** @var array */
    public $phpClasses;

    /**
     * @param array $phpVersions an array listing the minimum PHP version followed by the recommended PHP version
     */
    public function __construct(array $phpVersions)
    {
        $this->phpVersions = $phpVersions;
    }

    public function setPHPExtensions(array $phpExtensions)
    {
        $this->phpExtensions = $phpExtensions;
    }

    public function setPHPClasses(array $phpClasses)
    {
        $this->phpClasses = $phpClasses;
    }
}
class RequirementsCheck
{
    /** @var Requirements */
    public $requirements;

    /** @var Runtime */
    public $runtime;

    /** @var array Error messages */
    public $errors;

    /** @var array Missed compontents array used for internal awareness */
    public $missed;

    /** @var array Maps PHP extension name to its documentation identifier */
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

    /** @var array Maps PHP extension name to its documentation identifier */
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
        $this->errors = array();
        $this->checkPHPVersion($requirements->phpVersions);
        $this->checkPHPProfile($requirements->phpExtensions, $requirements->phpClasses);
        $this->checkTimezone();
        $this->checkSessions();
        $this->checkWorkingPaths($runtime->workingPaths);
        $this->checkImageLibrary();
        $this->checkFileUploads();
        $this->checkApacheModRewrite();
        $this->checkUtf8Functions();
        $this->checkCurl();
        if (!$this->isMissing('cURL')) {
            $this->checkSourceAPI();
        }
    }

    public function checkPHPVersion(array $phpVersions)
    {
        if (version_compare(PHP_VERSION, $phpVersions[0], '<')) {
            $this->addMissing('PHP', 'https://php.net', 'Use a newer %l version (%c ' . $phpVersions[0] . ' required, ' . $phpVersions[1] . ' recommended)');
        }
    }

    public function checkPHPProfile(array $extensions, array $classes)
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
                $function = create_function('$var', 'return @' . $core_check[1] . '($var);');
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

    public function checkTimezone()
    {
        if (function_exists('date_default_timezone_get')) {
            $tz = @date_default_timezone_get();
            $dtz = @date_default_timezone_set($tz);
            if (!$dtz && !@date_default_timezone_set('America/Santiago')) {
                $this->addBundleMissing(array('timezone', 'date.timezone'), array('http://php.net/manual/en/timezones.php', 'http://php.net/manual/en/datetime.configuration.php#ini.date.timezone'), '<b>' . $tz . '</b> is not a valid %l0 identifier in %l1');
            }
        }
    }

    public function checkSessions()
    {
        $session_link = 'http://php.net/manual/en/book.session.php';
        if (session_status() == PHP_SESSION_DISABLED) {
            $this->addMissing('sessions', $session_link, 'Enable %l support (session_start)');
        }
        $session_save_path = @realpath(@session_save_path());
        if ($session_save_path) {
            if (!is_writable($session_save_path)) {
                $session_errors[] = $k;
            }
            if (isset($session_errors)) {
                $this->addBundleMissing(array('session', 'session.save_path'), array($session_link, 'http://php.net/manual/en/session.configuration.php#ini.session.save-path'), str_replace('%s', implode('/', $session_errors), 'Missing PHP <b>%s</b> permission in <b>' . $session_save_path . '</b> (%l1)'));
            }
        }
        $_SESSION['chevereto-installer'] = true;
        if (!$_SESSION['chevereto-installer']) {
            $this->addMissing('sessions', $session_link, 'Any server setting related to %l support (%c are not working)');
        }
    }

    public function checkWorkingPaths(array $workingPaths)
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
                // $component = $var . ' ' . $error . ' permission' . (count($permissions_errors) > 1 ? 's' : null);
                $message = "PHP don't have  %l permission in <code>" . $var . '</code>';
                $this->addMissing($error, 'https://unix.stackexchange.com/questions/35711/giving-php-permission-to-write-to-files-and-folders', $message);
                unset($permissions_errors);
            }
        }
    }

    public function checkImageLibrary()
    {
        if (!@extension_loaded('gd') && !function_exists('gd_info')) {
            $this->addMissing('GD Library', 'http://php.net/manual/en/book.image.php', 'Enable %l');
        } else {
            foreach (array('PNG', 'GIF', 'JPG', 'WBMP') as $k => $v) {
                if (!imagetypes() & constant('IMG_' . $v)) {
                    $this->addMissing('GD Library', 'http://php.net/manual/en/book.image.php', 'Enable %l ' . $v . ' image support');
                }
            }
        }
    }

    public function checkFileUploads()
    {
        if (!ini_get('file_uploads')) {
            $this->addMissing('file_uploads', 'http://php.net/manual/en/ini.core.php#ini.file-uploads', 'Enable %l (needed for file uploads)');
        }
    }

    public function checkApacheModRewrite()
    {
        if (isset($_SERVER['SERVER_SOFTWARE']) && preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) && function_exists('apache_get_modules') && !in_array('mod_rewrite', apache_get_modules())) {
            $this->addMissing('mod_rewrite', 'http://httpd.apache.org/docs/current/mod/mod_rewrite.html', 'Enable %l (needed for URL rewriting)');
        }
    }

    public function checkUtf8Functions()
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

    public function checkCurl()
    {
        if (!function_exists('curl_init')) {
            $this->addMissing('cURL', 'http://php.net/manual/en/book.curl.php', 'Enable PHP %l');
        }
    }

    public function checkSourceAPI()
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

    /**
     * @param string $component
     * @param string $link
     * @param string $msgtpl    The message template. Use %l and %c placeholders
     */
    protected function addMissing(string $component, string $url, string $msgtpl)
    {
        $this->addBundleMissing([$component], [$url], strtr($msgtpl, ['%c' => '%c0', '%l' => '%l0']));
    }

    /**
     * Same as addMissing, but for bundled requirements like utf8_encode/utf8_decode where multiple requirements are
     * linked to the same resource.
     *
     * @param array  $components ['component1', 'component2',]
     * @param array  $urls       ['component1_url', 'component2_url',]
     * @param string $msgtpl     The message template. Use %l0 and %c0 placeholders
     */
    protected function addBundleMissing(array $components, array $urls, string $msgtpl)
    {
        $placeholders = array();
        foreach ($components as $k => $v) {
            $this->missed[] = $v;
            $placeholders['%c' . $k] = $v;
            $placeholders['%l' . $k] = '<a href="' . $urls[$k] . '" target="_blank">' . $v . '</a>';
        }
        $message = strtr($msgtpl, $placeholders);
        $this->errors[] = $message;
    }

    /**
     * @return bool
     */
    public function isMissing(string $key)
    {
        return is_array($this->missed) ? in_array($key, $this->missed) : false;
    }
}
class Runtime
{
    /** @var array Runtime settings */
    public $settings;

    /** @var Logger */
    protected $logger;

    /** @var string Working path (absolute) */
    public $absPath;

    /** @var string Working path (relative) */
    public $relPath;

    /** @var string Path to this installer file (absolute) */
    public $installerFilepath;

    /** @var string HTTP hostname */
    public $httpHost;

    /** @var string HTTP protocol (http, https) */
    public $httpProtocol;

    /** @var string Root URL for the current project */
    public $rootUrl;

    /** @var string Human-readable server information */
    public $serverString;

    /** @var array */
    public $workingPaths;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    public function setServer(array $server)
    {
        $this->server = $server;
    }

    public function run()
    {
        error_reporting($this->settings['error_reporting']);
        $this->applyPHPSettings($this->settings);
        $this->processContext();
    }

    protected function applyPHPSettings(array $settings)
    {
        $runtimeTable = [
            'log_errors' => ini_set('log_errors', (string) $settings['log_errors']),
            'display_errors' => ini_set('display_errors', (string) $settings['display_errors']),
            'error_log' => ini_set('error_log', $settings['error_log']),
            'set_time_limit' => set_time_limit($settings['time_limit']),
            'ini_set' => ini_set('default_charset', $settings['default_charset']),
            'setlocale' => setlocale(LC_ALL, $settings['LC_ALL']),
        ];
        $messageTemplate = 'Unable to set %k value %v (FALSE return value)';
        foreach ($runtimeTable as $k => $v) {
            if (false === $v) {
                $this->logger->addMessage(strtr($messageTemplate, [
                    '%k' => $k,
                    '%v' => var_export($settings[$k], true),
                ]));
            }
        }
    }

    protected function processContext()
    {
        if (!isset($this->server)) {
            $this->setServer($_SERVER);
        }
        $this->php = phpversion();
        $this->absPath = rtrim(str_replace('\\', '/', dirname(INSTALLER_FILEPATH)), '/') . '/';
        $this->relPath = rtrim(dirname($this->server['SCRIPT_NAME']), '\/') . '/';
        $this->installerFilename = basename(INSTALLER_FILEPATH);
        $this->installerFilepath = INSTALLER_FILEPATH;
        $this->httpHost = $this->server['HTTP_HOST'];
        $this->serverSoftware = $this->server['SERVER_SOFTWARE'];
        $httpProtocol = 'http';
        $isHttpsOn = !empty($this->server['HTTPS']) && strtolower($this->server['HTTPS']) == 'on';
        $isHttpsX = isset($this->server['HTTP_X_FORWARDED_PROTO']) && $this->server['HTTP_X_FORWARDED_PROTO'] == 'https';
        if ($isHttpsOn || $isHttpsX) {
            $httpProtocol .= 's';
        }
        $this->httpProtocol = $httpProtocol;
        $this->rootUrl = $this->httpProtocol . '://' . $this->httpHost . $this->relPath;
        $this->serverString = 'Server ' . $this->httpHost . ' PHP ' . phpversion();
        $this->setWorkingPaths([INSTALLER_FILEPATH, $this->absPath]);
    }

    protected function setWorkingPaths(array $workingPaths)
    {
        $this->workingPaths = $workingPaths;
    }
}
/**
 * A PHP client for cPanel UAPI.
 */
class Cpanel
{
    /** @var string cPanel UAPI module/function */
    public $action;

    /** @var array */
    public $response;

    /** @var string */
    public $errorMessage;

    /** @var string user:password */
    protected $userpwd;

    /** @var string */
    protected $mysqlPrefix;

    /** @var int */
    protected $mysqlMaxDbNamelength;

    /** @var int */
    protected $mysqlMaxUsernameLength;

    public function __construct(string $user, string $password)
    {
        $this->userpwd = "$user:$password";
    }

    public function sendRequest(string $action, array $params = [])
    {
        // cPanel UAPI accepts session login (cookie needed).
        // cPanel API Tokens aren't widely supported yet

        $url = 'https://localhost:2083';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (200 != $httpCode) {
            throw new Exception(strtr('Unable to connect to cPanel at %s [HTTP %c]', [
                '%s' => $url,
                '%c' => $httpCode,
            ]), 503);
        }

        $endpoint = $url . '/execute/' . $action;

        $url = $endpoint . '?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->userpwd);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        if ($result == false) {
            throw new Exception('curl_exec threw error "' . curl_error($ch) . "\" for $action");
        }
        curl_close($ch);
        $array = json_decode($result, false);
        if (!$array) {
            throw new Exception("Can't authenticate to cPanel host (wrong username:password)", 403);
        }
        $this->action = $action;
        $this->response = $array;
        $this->isSuccess = 1 == $array->status;
        if (!$this->isSuccess) {
            $this->errorMessage = implode('-', $this->response->errors);
        }
    }

    /**
     * Creates a MySQL database, its user and set privileges.
     *
     * @param string $prefix vendor prefix
     */
    public function setupMysql(string $prefix = null)
    {
        $this->sendRequest('Mysql/get_restrictions');
        // ^^^ response->data:
        // prefix => chevereto_
        // max_database_name_length => 64
        // max_username_length => 47

        if (!$this->isSuccess) {
            throw new Exception($this->errorMessage);
        }

        $this->mysqlPrefix = $this->response->data->prefix;
        $this->mysqlMaxDbNamelength = $this->response->data->max_database_name_length;
        $this->mysqlMaxUsernameLength = $this->response->data->max_username_length;

        $dbPrefix = $this->mysqlPrefix . $prefix;

        for ($i = 0; $i < 5; ++$i) {
            $dbName = static::getDbRandomName($dbPrefix, $this->mysqlMaxDbNamelength);
            $this->sendRequest('Mysql/check_database', ['name' => $dbName]);
            if (!$this->isSuccess) { // No DB = profit
                break;
            } else {
                if ($i == 4) {
                    throw new Exception('Unable to determine a valid MySQL database name', 201);
                }
            }
        }

        $this->sendRequest('Mysql/create_database', ['name' => $dbName]);
        if (!$this->isSuccess) {
            throw new Exception($this->errorMessage);
        }

        $dbUserPassword = password(16);
        for ($i = 0; $i < 5; ++$i) {
            $dbUser = static::getDbRandomName($dbPrefix, $this->mysqlMaxUsernameLength);
            $this->sendRequest('Mysql/create_user', [
                'name' => $dbUser,
                'password' => $dbUserPassword,
            ]);
            if ($this->isSuccess) {
                break;
            } else {
                if ($i == 4) {
                    throw new Exception('Unable to create the MySQL database user', 202);
                }
            }
        }

        $this->sendRequest('Mysql/set_privileges_on_database', [
            'user' => $dbUser,
            'database' => $dbName,
            'privileges' => 'ALL PRIVILEGES',
        ]);
        if (!$this->isSuccess) {
            throw new Exception($this->errorMessage);
        }

        return [
            'host' => 'localhost',
            'port' => '3306',
            'name' => $dbName,
            'user' => $dbUser,
            'userPassword' => $dbUserPassword,
        ];
    }

    public static function getHtaccessHandlers(string $filepath)
    {
        $contents = file_get_contents($filepath);
        preg_match_all('/# php -- BEGIN cPanel-generated handler, do not edit[\s\S]+# php -- END cPanel-generated handler, do not edit/', $contents, $matches);
        if ($matches) {
            return $matches[0][0];
        }
    }

    public static function getDbRandomName(string $prefix, int $maxLength)
    {
        $maxRandomLength = $maxLength - strlen($prefix);
        if ($maxRandomLength <= 0) {
            return $prefix;
        }
        $randomLength = min(5, $maxRandomLength);

        return $prefix . substr(bin2hex(random_bytes($randomLength)), 0, $randomLength);
    }
}
class JsonResponse
{
    /** @var array [code => , description =>,] */
    protected $status;

    /** @var string */
    public $code;

    /** @var string */
    public $message;

    const HTTP_CODES = [
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
    ];

    public function setResponse(string $message, $httpCode = 200)
    {
        $this->code = $httpCode;
        $this->message = $message;
        $this->status = $this->getHttpStatusDesc($httpCode);
    }

    public function getHttpStatusDesc($httpCode)
    {
        if (array_key_exists($httpCode, static::HTTP_CODES)) {
            return static::HTTP_CODES[$httpCode];
        }
    }

    public function setStatusCode($httpCode)
    {
        http_response_code($httpCode);
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function addData($key, $var = null)
    {
        if (!isset($this->data)) {
            $this->data = new stdClass();
        }
        $this->data->{$key} = $var;
    }

    public function send()
    {
        // if (headers_sent()) {
        //     throw new Exception('Headers have been already sent.');
        // }
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
            $this->data = null;
        }
        if (isset($this->code) and isset(static::HTTP_CODES[$this->code])) {
            $this->setStatusCode($this->code);
        }
        echo isset($this->data) ? $json : json_encode($this, JSON_FORCE_OBJECT);
        die();
    }
}
class Database
{
    const PRIVILEGES = ['ALTER', 'CREATE', 'DELETE', 'DROP', 'INDEX', 'INSERT', 'SELECT', 'TRIGGER', 'UPDATE'];

    /** @var string */
    protected $host;

    /** @var string */
    protected $port;

    /** @var string */
    protected $name;

    /** @var string */
    protected $user;

    /** @var string */
    protected $userPassword;

    /** @var PDO */
    private $pdo;

    public function __construct(string $host, string $port, string $name, string $user, string $userPassword)
    {
        $pdoAttrs = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $userPassword, $pdoAttrs);
        $this->host = $host;
        $this->port = $port;
        $this->name = $name;
        $this->user = $user;
        $this->userPassword = $userPassword;
    }

    public function checkEmpty()
    {
        $query = $this->pdo->query("SHOW TABLES FROM `$this->name`;");
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($tables)) {
            throw new Exception(sprintf('Database "%s" is not empty. Use another database or DROP (remove) all the tables in the target database.', $this->name));
        }
    }

    public function checkPrivileges()
    {
        $query = $this->pdo->query('SHOW GRANTS FOR CURRENT_USER;');
        $tables = $query->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($tables as $v) {
            if (false === preg_match_all('#^GRANT ([\w\,\s]*) ON (.*)\.(.*) TO *#', $v, $matches)) {
                continue;
            }
            $database = $this->unquote($matches[2][0]);
            if (in_array($database, ['%', '*'])) {
                $database = $this->name;
            }
            if ($database != $this->name) {
                continue;
            }
            $privileges = $matches[1][0];
            if ($privileges == 'ALL PRIVILEGES') {
                return;
            } else {
                $missed = [];
                $privileges = explode(', ', $matches[1][0]);
                foreach (static::PRIVILEGES as $privilege) {
                    if (!in_array($privilege, $privileges)) {
                        $missed[] = $privilege;
                    }
                }
                if (empty($missed)) {
                    return;
                }
            }
        }
        throw new Exception(strtr('Database user `%user%` doesn\'t have %privilege% privilege on the `%dbName%` database.', [
            '%user%' => $this->user,
            '%privilege%' => implode(', ', $missed),
            '%dbName%' => $this->name,
        ]));
    }

    private function unquote(string $quoted)
    {
        return str_replace(['`', "'"], '', stripslashes($quoted));
    }
}
class Controller
{
    /** @var array */
    public $params;

    /** @var string */
    public $response;

    /** @var array */
    public $data;

    /** @var Runtime */
    public $runtime;

    public function __construct(array $params, Runtime $runtime)
    {
        $this->runtime = $runtime;
        if (!$params['action']) {
            throw new Exception('Missing action parameter', 400);
        }
        $this->params = $params;
        $method = $params['action'] . 'Action';
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid action ' . $params['action'], 400);
        }
        $this->{$method}($this->params);
    }

    public function checkLicenseAction(array $params)
    {
        $post = $this->curl(VENDOR['apiLicense'], [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['license' => $params['license']]),
        ]);
        if ($post->json->error) {
            throw new Exception($post->json->error->message, 403);
        }
        $this->response = 200 == $this->code ? 'Valid license key' : 'Unable to check license';
    }

    public function checkDatabaseAction(array $params)
    {
        try {
            $database = new Database(
                $params['host'],
                $params['port'],
                $params['name'],
                $params['user'],
                $params['userPassword']
            );
            $database->checkEmpty();
            $database->checkPrivileges();
            $this->code = 200;
            $this->response = sprintf('Database %s OK', $params['name']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 503);
        }
    }

    public function cPanelProcessAction(array $params)
    {
        try {
            $cpanel = new Cpanel($params['user'], $params['password']);
            $createDb = $cpanel->setupMysql();
            $this->code = 200;
            $this->response = 'cPanel process completed';
            $this->data['db'] = $createDb;
            // [name] =>
            // [user] =>
            // [user_password] =>
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 503);
        }
    }

    public function cPanelHtaccessHandlersAction(array $params)
    {
        $filePath = $this->runtime->absPath . '.htaccess';
        if (!@is_readable($filePath)) {
            $this->code = 404;
            $this->response = 'No .htaccess found';

            return;
        }
        try {
            if ($handlers = Cpanel::getHtaccessHandlers($filePath)) {
                $this->code = 200;
                $this->response = 'cPanel .htaccess handlers found';
                $this->data['handlers'] = trim($handlers);
            } else {
                $this->code = 404;
                $this->response = 'No cPanel .htaccess handlers found';
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 503);
        }
    }

    public function downloadAction(array $params)
    {
        $fileBasename = 'chevereto-pkg-' . substr(bin2hex(random_bytes(8)), 0, 8) . '.zip';
        $filePath = $this->runtime->absPath . $fileBasename;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        $isPost = false;
        $zipball = APPLICATIONS[$params['software']]['zipball'];
        if (!$zipball) {
            throw new Exception('Invalid software parameter', 400);
        }
        if ($params['software'] == 'chevereto') {
            $isPost = true;
        } else {
            $params = null;
            $get = $this->curl($zipball);
            $zipball = $get->json->zipball_url;
        }
        $curl = $this->downloadFile($zipball, $params, $filePath, $isPost);
        // Default chevereto.com API handling
        if ($curl->json->error) {
            throw new Exception($curl->json->error->message, $curl->json->status_code);
        }
        // Everybody else
        if (200 != $curl->transfer['http_code']) {
            throw new Exception('[HTTP ' . $curl->transfer['http_code'] . '] ' . $zipball, $curl->transfer['http_code']);
        }
        $fileSize = filesize($filePath);
        $this->response = strtr('Downloaded %f (%w @%s)', array(
            '%f' => $fileBasename,
            '%w' => $this->getFormatBytes($fileSize),
            '%s' => $this->getBytesToMb($curl->transfer['speed_download']) . 'MB/s.',
        ));
        $this->data['fileBasename'] = $fileBasename;
        $this->data['filePath'] = $filePath;
    }

    public function extractAction(array $params)
    {
        if (!$params['software']) {
            throw new Exception('Missing software parameter', 400);
        } elseif (!isset(APPLICATIONS[$params['software']])) {
            throw new Exception(sprintf('Unknown software %s', $params['software']), 400);
        }

        $software = APPLICATIONS[$params['software']];

        if (!$params['workingPath']) {
            throw new Exception('Missing workingPath parameter', 400);
        }
        $workingPath = $params['workingPath'];
        if (!file_exists($workingPath) && !@mkdir($workingPath)) {
            throw new Exception(sprintf("Working path %s doesn't exists and can't be created", $workingPath), 503);
        }
        if (!is_readable($workingPath)) {
            throw new Exception(sprintf('Working path %s is not readable', $workingPath), 503);
        }

        $filePath = $params['filePath'];
        if (!is_readable($filePath)) {
            throw new Exception(sprintf("Can't read %s", basename($filePath)), 503);
        }
        $zipExt = new ZipArchiveExt();
        $timeStart = microtime(true);
        $zipOpen = $zipExt->open($filePath);
        if (true !== $zipOpen) {
            throw new Exception(strtr("Can't extract %f - %m (ZipArchive #%z)", array(
                '%f' => $filePath,
                '%m' => 'ZipArchive ' . $zipOpen . ' error',
                '%z' => $zipOpen,
            )), 503);
        }
        $numFiles = $zipExt->numFiles - 1; // because of top level folder
        $folder = $software['folder'];
        if ($params['software'] == 'chevereto-free') {
            $comment = $zipExt->getArchiveComment();
            $folder = str_replace('/', '-', $folder) . substr($comment, 0, 7);
        }
        $extraction = $zipExt->extractSubdirTo($workingPath, $folder);
        if (!empty($extraction)) {
            throw new Exception(implode(', ', $extraction));
        }
        $zipExt->close();
        $timeTaken = round(microtime(true) - $timeStart, 2);
        @unlink($filePath);

        $htaccessFiepath = $workingPath . '.htaccess';
        if ($params['appendHtaccess'] && file_exists($htaccessFiepath)) {
            file_put_contents($htaccessFiepath, "\n\n" . $params['appendHtaccess'], FILE_APPEND | LOCK_EX);
        }

        $this->code = 200;
        $this->response = strtr('Extraction completeted (%n files in %ss)', ['%n' => $numFiles, '%s' => $timeTaken]);
    }

    public function createSettingsAction(array $params)
    {
        $settings = [];
        foreach ($params as $k => $v) {
            $settings["%$k%"] = $v;
        }
        $template = '<' . "?php
\$settings['db_host'] = '%host%';
\$settings['db_port'] = '%port%';
\$settings['db_name'] = '%name%';
\$settings['db_user'] = '%user%';
\$settings['db_pass'] = '%userPassword%';
\$settings['db_table_prefix'] = 'chv_';
\$settings['db_driver'] = 'mysql';
\$settings['db_pdo_attrs'] = [];
\$settings['debug_level'] = 1;";
        $php = strtr($template, $settings);
        put($params['filePath'], $php);
        $this->code = 200;
        $this->response = 'Settings file OK';
    }

    public function submitInstallFormAction(array $params)
    {
        $installUrl = $this->runtime->rootUrl . 'install';
        if (0 === strpos($this->runtime->server['SERVER_SOFTWARE'], 'PHP')) {
            throw new Exception('Unable to submit the installation form under PHP development server. Go to ' . $installUrl . ' to complete the process.', 501);
        }
        $post = $this->curl($installUrl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params),
        ]);
        if ($post->json->error) {
            throw new Exception($post->json->error->message, $post->json->error->code);
        }
        if (preg_match('/system error/i', $post->raw)) {
            throw new Exception('System error :(', 400);
        }
        if (preg_match('/<p class="highlight\s.*">(.*)<\/p>/', $post->raw, $post_errors)) {
            throw new Exception(strip_tags(str_replace('<br><br>', ' ', $post_errors[1])), 400);
        }
        $this->code = 200;
        $this->response = 'Setup complete';
    }

    public function selfDestructAction()
    {
        $filePath = $this->runtime->installerFilepath;
        $basename = basename($filePath);
        $isDone = 'app.php' == $basename ?: @unlink($filePath);
        if ($isDone) {
            $this->code = 200;
            $this->response = 'Installer removed';
        } else {
            $this->code = 503;
            $this->response = 'Unable to remove installer file at ' . $filePath;
        }
    }

    /**
     * @param string $url      Target download URL
     * @param string $params   Request params
     * @param string $filePath Location to save the downloaded file
     * @param bool   $post     TRUE to download using a POST request
     * @param return curl handle
     */
    public function downloadFile(string $url, array $params = null, string $filePath, bool $post = true)
    {
        $fp = @fopen($filePath, 'wb+');
        if (!$fp) {
            throw new Exception("Can't open temp file " . $filePath . ' (wb+)');
        }
        $ops = [
            CURLOPT_FILE => $fp,
        ];
        if ($params) {
            $ops[CURLOPT_POSTFIELDS] = http_build_query($params);
        }
        if ($post) {
            $ops[CURLOPT_POST] = true;
        }
        $curl = $this->curl($url, $ops);
        fclose($fp);

        return $curl;
    }

    /**
     * @return array [transfer =>, tmp_file_path =>, raw =>, json =>,]
     */
    public function curl(string $url, array $curlOpts = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Chevereto Installer');
        foreach ($curlOpts as $k => $v) {
            if (CURLOPT_FILE == $k) {
                $fp = $v;
            }
            curl_setopt($ch, $k, $v);
        }
        $file_get_contents = @curl_exec($ch);
        $transfer = curl_getinfo($ch);
        if (curl_errno($ch)) {
            $curl_error = curl_error($ch);
            curl_close($ch);
            throw new Exception('Curl error ' . $curl_error, 503);
        }
        curl_close($ch);
        $return = new stdClass();
        if (is_resource($fp)) {
            rewind($fp);
            $return->raw = stream_get_contents($fp);
        } else {
            $return->raw = $file_get_contents;
        }
        if (false !== strpos($transfer['content_type'], 'application/json')) {
            $return->json = json_decode($return->raw);
            if (is_resource($fp)) {
                $meta_data = stream_get_meta_data($fp);
                @unlink($meta_data['uri']);
            }
        }
        $this->code = $transfer['http_code'];
        if (200 != $this->code && !isset($return->json)) {
            $return->json = new stdClass();
            $return->json->error = new stdClass();
            $return->json->error->message = 'Error performing HTTP request';
            $return->json->error->code = $this->code;
        }
        $return->transfer = $transfer;

        return $return;
    }

    /**
     * @param string $bytes bytes to be formatted
     * @param int    $round how many decimals you want to get, default 1
     *
     * @return string formatted size string like 10 MB
     */
    public function getFormatBytes($bytes, $round = 1)
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
     * @param string $bytes bytes to be formatted
     *
     * @return float MB representation
     */
    public function getBytesToMb($bytes, $round = 2)
    {
        $mb = $bytes / pow(10, 6);
        if ($round) {
            $mb = round($mb, $round);
        }

        return $mb;
    }
}

$logger = new Logger(APP_NAME . ' ' . APP_VERSION);

$requirements = new Requirements([PHP_VERSION_MIN, PHP_VERSION_RECOMMENDED]);
$requirements->setPHPExtensions($phpExtensions);
$requirements->setPHPClasses($phpClasses);

$runtime = new Runtime($logger);
$runtime->setSettings($phpSettings);
// $runtime->setServer([
//     'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
//     'HTTPS' => 'On',
//     'SERVER_SOFTWARE' => 'php-cli',
//     'SERVER_PROTOCOL' => 'PHP/CLI',
//     'HTTP_HOST' => 'php-cli',
//     'HTTP_X_FORWARDED_PROTO' => null,
// ]);
$runtime->run();

$requirementsCheck = new RequirementsCheck($requirements, $runtime);

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $jsonResponse = new JsonResponse();
    if ($requirementsCheck->errors) {
        $errorsPlain = array_map(function ($v) {
            return trim(strip_tags($v));
        }, $requirementsCheck->errors);
        $jsonResponse->setResponse('Missing server requirements', 500);
        $jsonResponse->addData('errors', $errorsPlain);
    } else {
        try {
            $controller = new Controller($_POST, $runtime);
            $jsonResponse->setResponse($controller->response, $controller->code);
            if ($controller->data) {
                $jsonResponse->setData($controller->data);
            }
        } catch (Throwable $e) {
            $jsonResponse->setResponse($e->getMessage(), $e->getCode());
        }
    }
    $jsonResponse->send();
    die();
} else {
    if (isset($_GET['getNginxRules'])) {
        header('Content-Type: text/plain');
        printf('# Chevereto NGINX generated rules for ' . $runtime->rootUrl . '

# Context limits
client_max_body_size 20M;

# Disable access to sensitive files
location ~* ' . $runtime->relPath . '(app|content|lib)/.*\.(po|php|lock|sql)$ {
  deny all;
}

# Image not found replacement
location ~ \.(jpe?g|png|gif|webp)$ {
    log_not_found off;
    error_page 404 ' . $runtime->relPath . 'content/images/system/default/404.gif;
}

# CORS header (avoids font rendering issues)
location ~* ' . $runtime->relPath . '.*\.(ttf|ttc|otf|eot|woff|woff2|font.css|css|js)$ {
  add_header Access-Control-Allow-Origin "*";
}

# Pretty URLs
location ' . $runtime->relPath . ' {
  index index.php;
  try_files $uri $uri/ /index.php$is_args$query_string;
}

# END Chevereto NGINX rules
');
        die();
    }
    $pageId = $requirementsCheck->errors ? 'error' : 'install';
    $doctitle = APP_NAME;
    $css = 'html,
body {
  height: 100%;
}

body {
  margin: 0;
  background: #3498db;
  background: -moz-linear-gradient(top, #3498db 0%, #8e44ad 100%);
  background: -webkit-linear-gradient(top, #3498db 0%, #8e44ad 100%);
  background: linear-gradient(to bottom, #3498db 0%, #8e44ad 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#3498db", endColorstr="#8e44ad", GradientType=0)
}

html#error body {
  background: #ecf0f1;
}

html {
  color: #000;
  font: 16px Helvetica, Arial, sans-serif;
  line-height: 1.3;

}

.body--block {
  margin: 20px
}

.body--flex {
  margin: 0;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  flex-direction: column;
}

.user-select-none {
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none
}

.force-select {
  -webkit-user-select: all;
  /* Chrome 49+ */
  -moz-user-select: all;
  /* Firefox 43+ */
  -ms-user-select: all;
  /* No support yet */
  user-select: all;
  /* Likely future */
}

main {
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
  border: 0;
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow-y: auto;
  flex: 1;
}

@media (min-width: 768px) {
  main {
    padding: 20px
  }
}

main>div {
  width: 630px;
}

.main--stack {
  width: 100%;
  max-width: 900px
}

* {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  -ms-box-sizing: border-box;
  box-sizing: border-box;
  outline: 0;
}

a {
  color: #3498db;
  outline: 0;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

p,
ul>li {
  line-height: 140%;
}

.soft-hidden {
  display: none;
}

.p {
  margin-top: 20px;
  margin-bottom: 20px;
}

.highlight,
.alert,
.log {
  font-size: 0.9em;
  padding: 1em;
}

.highlight:empty,
.alert:empty {
  display: none;
}

.highlight {
  background: #ecf0f1;
  border-left: 2px solid #8e44ad;
}

.alert {
  position: relative;
  background: rgba(241, 196, 15, .3);
  border-left: 2px solid #f1c40f;
  padding-right: 2em;
}

.alert pre {
  overflow: auto;
}

.alert pre,
.alert code {
  background: rgba(241, 196, 15, .3);
}

.alert pre code {
  background: transparent;
}

.shake {
  animation: shake 0.5s cubic-bezier(.36, .07, .19, .97) both;
  transform: translate3d(0, 0, 0);
  backface-visibility: hidden;
  perspective: 1000px;
}

.alert-close {
  cursor: pointer;
  position: absolute;
  right: 1em;
  top: 1em;
  width: 1em;
  height: 1em;
  opacity: 0.3;
}

.alert-close:hover {
  opacity: 1;
}

.alert-close:before,
.alert-close:after {
  position: absolute;
  left: 7.5px;
  content: \' \';
  height: 16px;
  width: 2px;
  background-color: #333;
}

.alert-close:before {
  transform: rotate(45deg);
}

.alert-close:after {
  transform: rotate(-45deg);
}

input,
select,
button,
.button {
  font-family: Helvetica, Arial, sans-serif;
  padding: 10px;
  border: 1px solid rgba(0, 0, 0, .2);
  background: #FFF;
  color: rgba(0, 0, 0, .8);
}

input:focus,
select:focus {
  border-color: #3498db;
}

/* Go home Chrome, you are drunk */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus input:-webkit-autofill,
textarea:-webkit-autofill,
textarea:-webkit-autofill:hover textarea:-webkit-autofill:focus,
select:-webkit-autofill,
select:-webkit-autofill:hover,
select:-webkit-autofill:focus {
  -webkit-text-fill-color: inherit;
  -webkit-box-shadow: 0 0 0px 1000px transparent inset;
  transition: background-color 5000s ease-in-out 0s;
}

button,
.button {
  display: inline-block;
  font-size: 0.83em;
  font-weight: bold;
  padding-right: 15px;
  padding-left: 15px;
  line-height: 1;
  outline: 0;
  cursor: pointer;
  text-shadow: 1px 1px 0 rgba(255, 255, 255, .1);
  text-decoration: none;
}

button:hover,
button:focus,
.button:hover,
.button:focus {
  text-decoration: none;
  background-color: rgba(0, 0, 0, .05);
}

button:active,
.button:active {
  border-color: rgba(0, 0, 0, .3);
  box-shadow: inset 0 2px 5px rgba(0, 0, 0, .3);
}

button.action,
.button.action {
  text-shadow: 1px 1px 0 rgba(0, 0, 0, .05);
  color: #FFF;
  border-color: #27ae60;
  background: #27ae60;
}

button.action:hover,
button.action:focus,
.button.action:hover,
.button.action:focus {
  background: #2ecc71;
}

button[disabled],
input[disabled] {
  cursor: wait;
}

input[data-disabled] {
  cursor: not-allowed;
}

input {
  background: #FFF;
}

input[disabled] {
  background: #ecf0f1;
}

.input-label label {
  font-size: 0.9em;
  display: block;
  font-weight: bold;
}

h1 {
  line-height: 1em;
}

code {
  font-size: 0.9em;
  font-family: monospace;
  background: #ecf0f1;
}

.pre {
  display: block;
  background-color: rgba(0, 0, 0, .1);
  overflow: auto;
  height: 180px;
  font-size: 0.9em;
  white-space: nowrap;
  width: 100%;
  resize: none;
  border: none;
  margin: 1em 0;
  -moz-tab-size: 4;
  -o-tab-size: 4;
  tab-size: 4;
}

@-webkit-keyframes spin {
  0% {
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
  0% {
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

.flex-box .loader {
  display: inline-block;
  border: .15em solid #ecf0f1;
  border-top: .15em solid #3498db;
  border-radius: 50%;
  width: 1em;
  height: 1em;
  animation: spin 2s linear infinite;
  z-index: 1;
  font-size: 32px;
  position: absolute;
  top: 20px;
  right: 20px;
  margin: 0;
  opacity: 0;
}

.flex-box .loader--show,
body.body--installing .flex-box .loader {
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

.sel--chevereto .chevereto--hide,
.sel--chevereto-free .chevereto-free--hide {
  display: none;
}

.text-align-center {
  text-align: center;
}



.flex {
  display: flex;
}

.flex--full {
  min-height: 100%;
  overflow: hidden;
}

.screen {
  margin: auto;
  display: none;
  flex-wrap: wrap;
  flex-direction: row;
  justify-content: center;
  opacity: 0;
  transform: scale(.8);
}

.screen--error {
  opacity: 1;
  display: flex;
  transform: scale(1);
}

.screen--show {
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

.flex-item {
  flex: 1 0 100%;
  justify-content: center;
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
  flex: 1 0 0;
  /* ms ladies and gentlemen */
}

.flex-box>div {
  margin: 20px;
}

.flex-box+.flex-box {
  margin-top: 0;
}

.log {
  background: #ecf0f1;
  overflow: auto;
  max-height: 10em;
  margin: 0;
  padding: 0;
}

.log:empty {
  display: none;
}

.log p {
  margin: 0;
  padding: 5px;
}

.log p:nth-child(even) {
  background: rgba(255, 255, 255, .5);
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
  text-decoration: none;
}

.error-box a:hover {
  text-decoration-style: solid;
  text-decoration-color: #000;
}

.error-box-code {
  opacity: .4;
  font-size: 0.9em;
  border-top: 1px solid rgba(0, 0, 0, .2);
  padding-top: 10px;
}

@media (min-width: 680px) {
  .col-8 {
    width: 310px;
  }

  .col-width {
    width: 630px;
  }

  .flex-box+.flex-box {
    margin-top: 20px;
    margin-left: 0;
  }
}

.width-100p {
  width: 100%;
}

.header>svg {
  height: 30px;
  width: auto;
  max-height: 100%;
  margin: 20px auto;
  display: block;
  filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, .15));
}

.header>svg path {
  fill: #FFF;
}

.install-details {
  font-size: 0.9em;
  font-family: monospace;
}

.install-details pre {
  margin: 0;
  font-family: inherit;
}

@keyframes shake {

  10%,
  90% {
    transform: translate3d(-1px, 0, 0);
  }

  20%,
  80% {
    transform: translate3d(2px, 0, 0);
  }

  30%,
  50%,
  70% {
    transform: translate3d(-4px, 0, 0);
  }

  40%,
  60% {
    transform: translate3d(4px, 0, 0);
  }
}';
    $script = 'var onLeaveMessage =
  "The installation is not yet completed. Are you sure that you want to leave?";

var page = document.documentElement.getAttribute("id");
var screenEls = document.querySelectorAll(".screen");
var screens = {};
for (let i = 0; i < screenEls.length; i++) {
  let el = screenEls[i];
  screens[el.id.replace("screen-", "")] = {
    title: el.querySelector("h1").innerText
  };
}

/**
 * This function is case insensitive since Chrome (and maybe others) change the qs case on manual input.
 * @param {string} name The parameter name in the query string.
 * @return {boolean} True if the name is present in the query string.
 */
function locationHasParameter(name) {
  var queryString = window.location.search.substring(1);
  if (queryString) {
    var paramArray = queryString.split("&");
    for (let paramPair of paramArray) {
      var param = paramPair.split("=")[0];
      console.log(
        param,
        "^" + param + "$",
        new RegExp("^" + param + "$", "i").test(name)
      );
      if (new RegExp("^" + param + "$", "i").test(name)) {
        return true;
      }
    }
  }
  return false;
}

function escapeHtml(unsafe) {
  return unsafe
       .replace(/&/g, "&amp;")
       .replace(/</g, "&lt;")
       .replace(/>/g, "&gt;")
       .replace(/"/g, "&quot;")
       .replace(/\'/g, "&#039;");
}

var installer = {
  uid: false,
  data: {},
  isCpanelDone: false,
  isUpgradeToPaid: locationHasParameter("UpgradeToPaid"),
  process: "install",
  defaultScreen: "welcome",
  init: function() {
    installer.log(runtime.serverString);
    if (this.isUpgradeToPaid) {
      this.process = "upgrade";
      this.defaultScreen = "upgrade";
    }
    var self = this;
    this.popScreen(this.defaultScreen);
    this.history.replace(this.defaultScreen);
    if (page != "error") {
      var inputEmailEls = document.querySelectorAll("input[type=email]");
      for (let inputEmailEl of inputEmailEls) {
        inputEmailEl.pattern = patterns.email_pattern;
      }
      this.bindActions();
    }
    document.addEventListener(
      "click",
      function(event) {
        if (!event.target.matches(".alert-close")) return;
        event.preventDefault();
        installer.popAlert();
      },
      false
    );
    window.onpopstate = function(e) {
      var isBack = installer.uid > e.state.uid;
      var isForward = !isBack;
      installer.uid = e.state.uid;

      var state = e.state;
      var form = installer.getShownScreenEl("form");
      if (isForward && form) {
        if (form.checkValidity()) {
          installer.actions[form.dataset.trigger](form.dataset.arg);
          return;
        } else {
          history.go(-1);
          var tmpSubmit = document.createElement("button");
          form.appendChild(tmpSubmit);
          tmpSubmit.click();
          form.removeChild(tmpSubmit);
          return;
        }
      }
      self.popScreen(state.view);
    };
    var forms = document.querySelectorAll("form");
    for (let i = 0; i < forms.length; i++) {
      forms[i].addEventListener(
        "submit",
        function(e) {
          e.preventDefault();
          e.stopPropagation();
          installer.actions[forms[i].dataset.trigger](forms[i].dataset.arg);
        },
        false
      );
    }
  },
  getCurrentScreen: function() {
    return this.getShownScreenEl("").id.replace("screen-", "");
  },
  getShownScreenEl: function(query) {
    return document.querySelector(".screen--show " + query);
  },
  shakeEl: function(el) {
    el.classList.remove("shake");
    setTimeout(function() {
      el.classList.add("shake");
    }, 1);
    setTimeout(function() {
      el.classList.remove("shake");
    }, 500);
  },
  pushAlert: function(message) {
    var pushiInnerHTML =
      "<span>" + message + \'</span><a class="alert-close"></a>\';
    var el = this.getShownScreenEl(".alert");
    var html = el.innerHTML;
    if (pushiInnerHTML == html) {
      this.shakeEl(el);
    } else {
      el.innerHTML = pushiInnerHTML;
    }
  },
  popAlert: function() {
    var el = this.getShownScreenEl(".alert");
    if (el) {
      el.innerHTML = "";
    }
  },
  getFormData: function() {
    var form = installer.getShownScreenEl("form");
    if (!form) {
      return;
    }
    var screen = this.getCurrentScreen();
    var inputEls = form.getElementsByTagName("input");
    var data = {};
    for (let inputEl of inputEls) {
      var id = inputEl.id.replace(screen, "");
      var key = id.charAt(0).toLowerCase() + id.slice(1);
      data[key] = inputEl.value;
    }
    return data;
  },
  writeFormData: function(screen, data) {
    installer.data[screen] = data ? data : this.getFormData();
  },
  bindActions: function() {
    var self = this;
    var triggers = document.querySelectorAll("[data-action]");
    for (let i = 0; i < triggers.length; i++) {
      var trigger = triggers[i];
      trigger.addEventListener("click", function(e) {
        var dataset = e.currentTarget.dataset;
        self.actions[dataset.action](dataset.arg);
      });
    }
  },
  history: {
    push: function(view) {
      this.writter("push", { view: view });
    },
    replace: function(view) {
      this.writter("replace", { view: view });
    },
    writter: function(fn, data) {
      data.uid = new Date().getTime();
      installer.uid = data.uid;
      switch (fn) {
        case "push":
          history.pushState(data, data.view);
          break;
        case "replace":
          history.replaceState(data, data.view);
          break;
      }
      document.title = screens[data.view].title; // Otherwise the titles at the browser bar could fail
      console.log("history.writter:", fn, data);
    }
  },
  /**
   *
   * @param {string} action
   * @param {object} params
   * @param {object} callback {success: fn(data), error: fn(data),}
   */
  fetch: function(action, params, callback = {}) {
    var data = new FormData();
    data.append("action", action);
    for (var key in params) {
      data.append(key, params[key]);
    }
    var disableEls = document.querySelectorAll("button, input:not([data-disabled])");
    for (let disableEl of disableEls) {
      disableEl.disabled = true;
    }
    var box = this.getShownScreenEl(".flex-box");
    var loader = this.getShownScreenEl(".loader");
    if (!loader) {
      var loader = document.createElement("div");
      loader.classList.add("loader", "animate");
      box.insertBefore(loader, box.firstChild);
    }
    setTimeout(function() {
      loader.classList.add("loader--show");
    }, 1);
    ["always", "error"].forEach(function(value) {
      if (!(value in callback)) {
        let callbackFn =
          "fetchOn" + value.charAt(0).toUpperCase() + value.slice(1);
        callback[value] = installer[callbackFn];
      }
    });
    return fetch(runtime.installerFilename, {
      method: "POST",
      body: data
    })
      .then(function(response) {
        return response.text();
      })
      .then(text => {
        try {
            return JSON.parse(text);
        } catch (e) {
            throw Error("Unable to parse server response. The installer is expecting a JSON response, but your server thrown this:<pre><code>" + escapeHtml(text) + "</code></pre> This is not normal and you should report it to our <a href=\'"+appUrl+"\' target=\'_blank\'>GitHub repository</a>.");
        }
      })
      .catch(error => {
        installer.pushAlert(error);
      })
      
      .then(function(data) {
        loader.classList.remove("loader--show");
        for (let disableEl of disableEls) {
          disableEl.disabled = false;
        }
        callback.always(data);
        let callbackRes;
        if (200 == data.code) {
          installer.popAlert();
          if ("success" in callback) {
            callbackRes = callback.success(data);
          }
        } else {
          callbackRes = callback.error(data);
          if(true !== callbackRes) {
            installer.pushAlert(data.message);
            return new Promise(function(resolve, reject) {
              reject(data);
            });
          }
        }
        if(200 == data.code || true == callbackRes) {
          return new Promise(function(resolve, reject) {
            resolve(data);
          });
        }
      });
      // .catch(error => {
      //   installer.pushAlert(error);
      // });
  },
  popScreen: function(screen) {
    console.log("popScreen:" + screen);
    var shownScreens = document.querySelectorAll(".screen--show");
    shownScreens.forEach(a => {
      a.classList.remove("screen--show");
    });
    document.querySelector("#screen-" + screen).classList.add("screen--show");
  },
  checkLicense: function(key, callback) {
    return this.fetch("checkLicense", { license: key }, callback);
  },
  fetchOnError: function(data) {
    if (installer.isInstalling()) {
      installer.abortInstall();
    }
  },
  fetchOnAlways: function(data) {
    installer.log(data.message);
  },
  fetchCommonInit: function() {
    this.log("Detecting existing cPanel .htaccess handlers");
    return this
      .fetch("cPanelHtaccessHandlers", null, {
        error: function() {
          return true;
        }
      })
      .then(json => {
        installer.data.cPanelHtaccessHandlers = "data" in json ? json.data.handlers : "";
      })
      .then(json => {
        installer.log("Downloading latest " + installer.data.software + " release");
        return installer.fetch("download", {
          software: installer.data.software,
          license: installer.data.license
        });
      })
      .then(json => {
        installer.log("Extracting " + json.data.fileBasename);
        return installer.fetch("extract", {
          software: installer.data.software,
          filePath: json.data.filePath,
          workingPath: runtime.absPath,
          appendHtaccess: installer.data.cPanelHtaccessHandlers,
        });
      });
  },
  fillInstallDetails: function(data) {
    let text = "+===================================+" + "\\n" +
    "| Chevereto installation            |" + "\\n" +
    "+===================================+" + "\\n" +
    "| URL: " + runtime.rootUrl + "\\n" +
    "| Software: " + data.software + "\\n" +
    "| --" + "\\n" +
    "| # Admin" + "\\n" +
    "| Email: " + data.admin.email + "\\n" +
    "| Username: " + data.admin.username +  "\\n" +
    "| Password: " + data.admin.password + "\\n" +
    "| --" + "\\n" +
    "| # Database" + "\\n" +
    "| Host: " + data.db.host + "\\n" +
    "| Port: " + data.db.port + "\\n" +
    "| Name: " + data.db.name + "\\n" +
    "| User: " + data.db.user + "\\n" +
    "| User password: " + data.db.userPassword + "\\n" +
    "+===================================+";
    let el = document.createElement("pre");
    el.innerHTML = text;
    document.querySelector(".install-details").appendChild(el);
  },
  actions: {
    show: function(screen) {
      installer.popScreen(screen);
      if (history.state.view != screen) {
        installer.history.push(screen);
      }
    },
    setLicense: function(elId) {
      var licenseEl = document.getElementById(elId);
      var license = licenseEl.value;
      if (!license) {
        licenseEl.focus();
        installer.shakeEl(licenseEl);
        return;
      }
      installer.checkLicense(license, {
        success: function() {
          installer.data.license = license;
          installer.actions.setSoftware("chevereto");
        },
        error: function() {
          installer.data.license = null;
        }
      });
    },
    setSoftware: function(software) {
      document.body.classList.remove("sel--chevereto", "sel--chevereto-free");
      document.body.classList.add("sel--" + software);
      installer.data.software = software;
      installer.log("Software has been set to: " + software);
      // Note: 7.3 support should be added in the next version
      if("chevereto-free" == software && runtime.php.indexOf("7.3") == 0) {
        this.show("sorry");
      } else {
        this.show("cpanel");
      }
    },
    setUpgrade: function() {
      console.log("setUpgrade");
      document.body.classList.remove("sel--chevereto-free");
      document.body.classList.add("sel--chevereto");
      var license = document.getElementById("upgradeKey").value;
      installer.checkLicense(license, {
        success: function() {
          installer.data.license = license;
          installer.actions.setSoftware("chevereto");
          installer.actions.show("ready-upgrade");
        },
        error: function() {
          installer.data.license = null;
        }
      });
    },
    cPanelProcess: function() {
      if(installer.isCpanelDone) {
        installer.actions.show("admin");
        return;
      }
      var els = {
        user: document.getElementById("cpanelUser"),
        password: document.getElementById("cpanelPassword")
      };
      var params = {};
      for (let key in els) {
        let el = els[key];
        if (!el.value) {
          el.focus();
          installer.shakeEl(el);
          return;
        } else {
          params[key] = el.value;
        }
      }
      installer.fetch("cPanelProcess", params, {
        error: function(data) {
          installer.isCpanelDone = false;
        }
      })
      .then(json => {
        for (let key in els) {
          els[key].setAttribute("data-disabled", "");
          els[key].disabled = true;
        }
        installer.writeFormData("db", json.data.db);
        installer.isCpanelDone = true;
        installer.actions.show("admin");
      });
    },
    setDb: function() {
      var params = installer.getFormData();
      installer.fetch("checkDatabase", params, {
        success: function(response, json) {
          installer.writeFormData("db", params);
          installer.actions.show("admin");
        },
        error: function(response, json) {
        }
      });
    },
    setAdmin: function() {
      installer.writeFormData("admin");
      this.show("emails");
    },
    setEmails: function() {
      installer.writeFormData("email");
      this.show("ready");
    },
    setReadyUpgrade() {
      this.show("ready-upgrade");
    },
    setReady: function() {
      this.show("ready");
    },
    upgrade: function() {
      installer.setBodyInstalling(true);
      this.show("upgrading");
      installer.log(
        "Downloading latest " + installer.data.software + " release"
      );
      installer
        .fetchCommonInit()
        .then(data => {
          installer.log(
            "Removing installer file at " + runtime.installerFilepath
          );
          return installer.fetch("selfDestruct", null, {
            error: function(data) {
              var todo =
                "Remove the installer file at " +
                runtime.installerFilepath +
                " and open " +
                runtime.rootUrl +
                " to continue the process.";
              installer.pushAlert(todo);
              installer.abortInstall(false);
              return false;
            }
          });
        })
        .then(data => {
          installer.setBodyInstalling(false);
          installer.log("Upgrade completed");
          setTimeout(function() {
            installer.actions.show("complete-upgrade");
          }, 1000);
        });
    },
    install: function() {
      installer.setBodyInstalling(true);
      this.show("installing");

      installer
        .fetchCommonInit()
        .then(data => {
          installer.log("Creating app/settings.php file");
          let = params = Object.assign({filePath: runtime.absPath + "app/settings.php"}, installer.data.db)
          return installer.fetch("createSettings", params);
        })
        .then(data => {
          installer.log("Performing system setup");
          let params = {
            username: installer.data.admin.username,
            email: installer.data.admin.email,
            password: installer.data.admin.password,
            email_from_email: installer.data.email.emailNoreply,
            email_incoming_email: installer.data.email.emailInbox,
            website_mode: \'community\',
          };
          return installer.fetch("submitInstallForm", params);          
        })
        .then(data => {
          installer.log(
            "Removing installer file at " + runtime.installerFilepath
          );
          return installer.fetch("selfDestruct", null, {
            error: function(data) {
              var todo =
                "Remove the installer file at " +
                runtime.installerFilepath +
                " and open " +
                runtime.rootUrl +
                " to continue the process.";
              installer.pushAlert(todo);
              installer.abortInstall(false);
              return false;
            }
          });
        })
        .then(data => {
          installer.setBodyInstalling(false);
          installer.log("Installation completed");
          installer.fillInstallDetails(installer.data);
          setTimeout(function() {
            installer.actions.show("complete");
          }, 1000);
        });
    }
  },
  setBodyInstalling: function(bool) {
    document.body.classList[bool ? "add" : "remove"]("body--installing");
  },
  isInstalling: function() {
    return document.body.classList.contains("body--installing");
  },
  abortInstall: function(message) {
    this.log(message ? message : "Process aborted");
    this.setBodyInstalling(false);
  },
  log: function(message) {
    var date = new Date();
    var t = {
      h: date.getHours(),
      m: date.getMinutes(),
      s: date.getSeconds()
    };
    for (var k in t) {
      if (t[k] < 10) {
        t[k] = "0" + t[k];
      }
    }
    var time = t.h + ":" + t.m + ":" + t.s;
    var el = document.querySelector(".log--" + (installer.isUpgradeToPaid ? "upgrade" : "install"));
    var p = document.createElement("p");
    var t = document.createTextNode(time + " " + message);
    p.appendChild(t);
    el.appendChild(p);
    el.scrollTop = el.scrollHeight;
  }
};
if ("error" != document.querySelector("html").id) {
  installer.init();
}';
    $svgLogo = '<svg xmlns="http://www.w3.org/2000/svg" width="501.76" height="76.521" viewBox="0 0 501.76 76.521"><path d="M500.264 40.068c-.738 0-1.422.36-1.814.963-1.184 1.792-2.36 3.53-3.713 5.118-1.295 1.514-5.34 4.03-8.7 4.662l-1.33.25.16-1.35.15-1.28c.11-.91.22-1.78.29-2.65.55-6.7-.03-11.69-1.89-16.2-1.68-4.08-3.94-6.57-7.11-7.85-1.18-.48-2.28-.72-3.26-.72-2.17 0-3.93 1.17-5.39 3.58-.15.25-.29.5-.46.78l-.67 1.18-.91-.75c-.42-.34-.82-.67-1.23-1.01-.95-.79-1.86-1.54-2.8-2.26-.76-.57-1.64-1.07-2.56-1.59-2-1.13-4.09-1.71-6.23-1.71-3.87 0-7.81 1.898-10.81 5.22-4.91 5.42-7.86 12.11-8.77 19.86-.11.988-.39 2.278-1.48 3.478-3.63 3.98-7.97 8.45-13.69 11.29-1.23.61-2.73 1.01-4.34 1.18-.18.02-.36.03-.52.03-.85 0-1.5-.26-1.95-.76-.48-.54-.66-1.3-.55-2.32.26-2.26.59-4.67 1.26-6.99 1.08-3.75 2.27-7.53 3.43-11.19.6-1.91 1.2-3.83 1.79-5.74.33-1.09 1.01-1.6 2.2-1.648 1.47-.06 2.89-.13 4.23-.45 1.96-.45 3.37-1.37 4.08-2.65.72-1.31.75-3.03.09-4.99-.06-.17-.12-.33-.19-.49l-7.18.69.28-1.33c.13-.65.27-1.27.4-1.88.3-1.36.58-2.66.8-3.94.38-2.22.59-4.81-.65-7.19-1.38-2.64-4.22-4.28-7.42-4.28-.71 0-1.43.08-2.14.25-5.3 1.24-9.3 4.58-12.23 7.472l1.76 9.7-1 .16c-.5.09-.96.16-1.39.22-.86.13-1.6.24-2.31.42-1.852.46-3.04 1.23-3.55 2.29-.51 1.05-.36 2.47.43 4.22.14.33.31.64.47.94l6.39-1.15-.26 1.42c-.15.82-.28 1.63-.41 2.42-.5 3.15-.98 6.13-2.72 8.97-5.55 9.07-11.52 15.36-18.76 19.79-2.17 1.33-5.11 2.91-8.52 3.33-.73.09-1.45.14-2.14.14-3.55 0-6.56-1.14-8.7-3.29-2.12-2.13-3.22-5.13-3.2-8.69l.01-1.33 1.28.38c.4.13.8.25 1.2.38.75.23 1.48.46 2.23.67 1.58.432 3.22.65 4.85.65 10.22-.01 18.46-8.11 18.76-18.46.18-6.32-2.4-10.77-7.66-13.25-2.14-1-4.41-1.49-6.97-1.49-1.3 0-2.69.14-4.13.4-7.34 1.35-13.38 5.54-18.48 12.83-1.97 2.81-3.57 6.02-5.18 10.42-.58 1.58-1.48 3.22-2.75 5.01-2.09 2.96-4.72 6.32-8.29 8.82-1.36.96-2.86 1.65-4.33 2.01-.34.08-.69.12-1.02.12-1.04 0-1.96-.4-2.61-1.12-.65-.73-.94-1.73-.81-2.81.31-2.67.858-4.9 1.67-6.84.9-2.15 1.938-4.27 2.95-6.32.818-1.66 1.67-3.37 2.42-5.08 1.42-3.2 1.96-6.22 1.648-9.21-.51-4.88-3.73-7.79-8.6-7.79-.23 0-.46.01-.69.02-4.13.23-7.65 2.102-10.89 3.99-1.23.72-2.44 1.51-3.73 2.36-.62.41-1.26.83-1.94 1.27l-3.05 1.96 1.61-3.25.3-.62c.16-.33.29-.59.43-.84 1.98-3.67 3.93-7.67 4.76-11.97.28-1.43.35-2.91.21-4.26-.21-2.16-1.398-3.34-3.34-3.34-.43 0-.9.06-1.39.18-2.14.52-4.19 1.67-6.26 3.51-5.9 5.27-8.87 11.09-9.07 17.81-.1 3.61.95 6.16 3.63 8.812l.55.55-.39.67c-.41.7-.82 1.41-1.22 2.12-.91 1.59-1.84 3.23-2.87 4.8-4.81 7.33-10.32 12.82-16.84 16.77-2.35 1.43-5.21 2.93-8.53 3.32-.71.08-1.42.12-2.1.12-7.03 0-11.61-4.38-11.96-11.44-.01-.22.02-.39.05-.53l.03-.16.19-1.12 1.09.33c.41.13.82.26 1.22.39.85.272 1.65.53 2.46.73 1.51.38 3.04.57 4.57.57 5.5 0 10.75-2.47 14.39-6.78 3.57-4.23 5.1-9.76 4.18-15.17-1-5.92-5.9-10.45-11.92-11.01-.89-.08-1.77-.13-2.64-.13-7.96 0-14.79 3.6-20.89 11-2.38 2.88-4.05 6.21-5.83 9.95-1.62 3.4-4.72 5.48-6.9 6.75-2.02 1.16-3.8 1.7-5.61 1.7-.19 0-.38 0-.57-.01l-1.25-.08.35-1.2c.25-.82.5-1.64.74-2.44.55-1.79 1.07-3.47 1.5-5.2 1.29-5.29 1.44-9.6.47-13.57-1.08-4.36-3.94-6.77-8.07-6.77-.44 0-.9.03-1.37.09-2.13.24-3.89 1.46-5.36 3.71-2.4 3.69-3.45 8.14-3.28 14.02.16 5.512 1.48 10.012 4.03 13.73.36.53.52 1.48.16 2.12-1.64 2.79-3.59 5.6-6.77 7.2-1.34.67-2.68 1.01-3.99 1.01-2.72 0-5.11-1.44-6.74-4.06-1.76-2.83-2.68-6.14-2.82-10.13-.27-7.69 1.44-14.86 5.08-21.33l.06-.11c.09-.19.23-.48.5-.71.89-.77.87-1.33-.1-3-1.64-2.85-4.5-4.55-7.66-4.55-2.64 0-5.19 1.17-7.16 3.28-2.98 3.19-4.91 7.32-6.08 12.99-.34 1.65-.54 3.37-.74 5.04-.1.9-.21 1.8-.33 2.69-.08.52-.2 1.12-.53 1.63-5.58 8.48-11.85 14.45-19.18 18.28-2.98 1.55-5.75 2.31-8.48 2.31-1.44 0-2.88-.22-4.3-.64-4.8-1.46-7.88-6.03-7.65-11.38l.06-1.29 1.24.37c.39.12.77.24 1.16.37.75.23 1.5.47 2.26.68 1.58.43 3.21.65 4.84.65 10.23-.01 18.47-8.11 18.77-18.45.18-6.33-2.4-10.78-7.66-13.25-2.14-1.01-4.41-1.5-6.97-1.5-1.3 0-2.69.14-4.12.4-7.35 1.35-13.39 5.54-18.49 12.818-2.24 3.2-3.94 6.66-5.05 10.28-.91 2.93-2.81 5.13-4.66 7.26l-.08.1c-2.25 2.6-4.84 4.94-6.83 6.68-.8.69-2.03 1.15-3.67 1.35-.18.03-.34.04-.5.04-.99 0-1.56-.408-1.86-.76-.47-.54-.64-1.28-.51-2.2.31-2.228.71-3.988 1.25-5.54.71-2.028 1.49-4.068 2.24-6.04.92-2.398 1.87-4.89 2.69-7.358 1.65-4.92 1.24-9.02-1.24-12.56-2.04-2.92-5.1-4.28-9.62-4.28h-.25c-5.89.07-12.67.82-18.42 6.23-.22.21-.43.55-.67.87-.31.44-.14.21-.51.76l-.62.87-.01.05-.01-.02.02-.03.15-.56 1.02-3.63c.78-2.772 1.58-5.63 2.28-8.46l.31-1.24c.67-2.65 1.36-5.392 1.53-8.07.28-4.2-2.6-7.6-6.83-8.08-.21-.02-.38-.09-.52-.17h-2.23c-4.61 1.09-8.87 3.61-13.03 7.7-.06.06-.14.19-.18.29 1.58 4.22 1.42 8.61 1.05 12.35-.6 6.12-1.43 12.64-2.6 20.49-.25 1.64-1.26 3.12-2.17 4.46-5.48 8.01-11.74 13.82-19.14 17.75-3.46 1.84-6.46 2.71-9.5 2.72-5.04 0-9.46-3.61-10.51-8.6-1.06-4.98-.4-10.14 2.08-16.21 1.23-3.04 3.11-6.9 6.67-9.73.94-.75 2.14-1.34 3.38-1.66.5-.12.99-.19 1.45-.19 1.22 0 2.28.46 2.97 1.29.77.92 1.04 2.23.78 3.7-.37 2.04-1.07 4.02-1.82 6.04-.45 1.21-1.12 2.49-1.98 3.8-.24.36-.29.48.16.96 1.09 1.16 2.45 1.73 4.17 1.73.38 0 .8-.03 1.22-.09 3.31-.47 6.13-2.16 7.95-4.76 1.84-2.64 2.47-5.93 1.76-9.26-1.59-7.46-7.19-11.73-15.35-11.73-.24 0-.49 0-.74.01-7.16.22-13.41 3.26-18.56 9.05-7.46 8.37-10.91 17.96-10.26 28.49.5 8.02 4.09 13.48 10.67 16.21 2.57 1.07 5.31 1.59 8.38 1.59 1.5 0 3.11-.13 4.78-.38 8.69-1.33 16.43-5.43 24.38-12.88.89-.83 1.8-1.63 2.61-2.34l.93-.82 1.8-1.6-.14 2.41c-.03.51-.07 1.07-.12 1.65-.11 1.398-.23 2.978-.19 4.52.05 1.59.33 3.17.81 4.58.96 2.77 3.34 4.29 6.78 4.29 2.56-.01 4.76-.71 6.51-2.06.26-.2.44-.49.46-.61.47-2.51.91-5.03 1.36-7.54.69-3.92 1.41-7.98 2.2-11.95.63-3.16 1.42-6.33 2.19-9.39.28-1.09.55-2.19.82-3.29.11-.43.38-1.22.99-1.66 3.13-2.23 6.01-3.27 9.09-3.27h.12c1.6.02 2.93.54 3.86 1.5.88.908 1.33 2.158 1.29 3.59-.07 2.39-.39 4.85-.95 7.318-.51 2.23-1.1 4.46-1.67 6.62-.65 2.45-1.32 4.98-1.86 7.49-.63 2.9-.41 5.83.65 8.47 1.18 2.95 3.54 4.55 7 4.76.3.02.59.03.89.03 3.36 0 6.64-1.12 10.33-3.53 3.9-2.54 7.44-5.94 11.48-11.02l.15-.19c.14-.19.29-.37.45-.56.25-.28.56-.35.62-.36l.95-.34.33.96c.2.61.39 1.21.58 1.82.41 1.32.79 2.56 1.33 3.73 2.65 5.75 7.27 8.94 14.11 9.78 1.26.16 2.53.23 3.78.23 5.41 0 10.79-1.392 16.45-4.26 6.83-3.472 12.86-8.602 17.92-15.25.19-.262.4-.5.58-.71l1.07-1.312.63 1.58c.41 1.03.8 2.08 1.2 3.14.88 2.35 1.8 4.79 2.9 7.08 1.67 3.45 4.11 6.07 7.24 7.81 2.49 1.37 5.1 2.07 7.77 2.07 2.29 0 4.7-.51 7.17-1.53 5.5-2.26 9.33-6.57 12.06-10.08.94-1.2 1.81-2.52 2.65-3.79.54-.82 1.08-1.64 1.64-2.44.09-.12.86-1.17 1.94-1.17h.01c.61.04 1.22.07 1.83.07 3.92 0 7.35-.87 10.49-2.66l1.3-.74.19 1.48c.09.73.17 1.45.24 2.16.16 1.5.3 2.92.63 4.28 2.12 8.97 8.068 13.76 17.69 14.23.538.03 1.068.04 1.59.04 5.51 0 11.048-1.44 16.468-4.27 11.81-6.18 20.342-15.86 26.06-29.59.23-.54.41-1.1.612-1.69.18-.55.36-1.09.568-1.63.23-.57.8-1.25 1.49-1.38.54-.1 1.08-.21 1.61-.32 1.75-.35 3.55-.71 5.38-.76l.17-.01c1.56 0 2.92.6 3.83 1.68.94 1.12 1.29 2.65 1 4.3-.36 2.01-.96 4.02-1.78 5.96-1.85 4.39-3.65 9.16-4.21 14.26-.48 4.28.14 7.26 2 9.67 1.7 2.21 4.05 3.24 7.4 3.24.52 0 1.07-.02 1.64-.07 3.51-.31 6.9-1.66 11-4.4 3.74-2.49 7.25-5.69 10.73-9.79.22-.26.45-.51.7-.81l1.65-1.87.5 1.78c.13.46.24.92.36 1.35.23.88.45 1.72.73 2.5 2.45 6.92 7.36 10.73 15 11.64 1.21.14 2.44.21 3.65.21 5.38 0 10.77-1.39 16.46-4.27 6.108-3.09 11.47-7.45 16.4-13.32.14-.17.278-.33.49-.56l2.188-2.49v2.65c0 .7-.02 1.38-.038 2.03-.04 1.34-.08 2.61.08 3.8.3 2.17.67 4.46 1.53 6.45 1.43 3.3 4.288 5.2 7.83 5.2 1.458 0 2.968-.32 4.49-.96 6.548-2.75 11.858-7.34 15.76-11.03 1.708-1.61 3.298-3.28 4.99-5.05.76-.8 1.52-1.59 2.288-2.39l1.13-1.16.53 1.54c.19.54.37 1.08.54 1.63.39 1.18.79 2.39 1.25 3.54 2.75 6.78 6.98 11.11 12.94 13.24 2.44.87 4.93 1.31 7.4 1.31 2.648 0 5.33-.5 7.98-1.51 7.84-2.97 13.78-8.08 17.68-15.21.88-1.6 2.01-2.06 3.45-2.24 6.88-.89 11.662-7.093 14.27-11.316.683-1.117 1.253-2.35 1.804-3.55.244-.526.482-1.054.738-1.567v-.334c-.324-.462-.86-.725-1.488-.725zM356.498 45.45c1.54-5.56 3.69-11.22 8.97-15.04.8-.58 1.81-1.05 3.02-1.39.47-.13.89-.19 1.28-.19 1.5 0 2.5.9 2.98 2.68.78 2.92-.09 5.63-.81 7.41-2.1 5.22-6.212 8.09-11.562 8.09-1 0-2.05-.11-3.11-.31l-1.06-.21.292-1.04zm-106.55.09c1.55-5.62 3.71-11.36 9.07-15.19.76-.55 1.76-.99 3.038-1.35.42-.12.82-.18 1.2-.18 1.54 0 2.63.99 2.9 2.63.29 1.76.29 3.49-.01 5.01-1.25 6.33-6.23 10.6-12.41 10.62-.66 0-1.3-.08-1.98-.17-.3-.04-.62-.07-.94-.11l-1.18-.12.31-1.14zm-115.21 0c1.55-5.62 3.72-11.36 9.06-15.19.77-.55 1.77-.99 3.04-1.35.42-.12.83-.18 1.21-.18 1.54 0 2.63.98 2.9 2.62.29 1.77.29 3.5-.01 5.01-1.24 6.34-6.22 10.61-12.4 10.63-.66 0-1.29-.08-1.96-.16-.31-.04-.64-.08-.97-.12l-1.19-.11.32-1.15zm334.02 5.43c-.67 4.82-2.8 8.46-6.32 10.8-1.52 1.01-3.17 1.55-4.77 1.55-3.22 0-5.97-2.19-7.17-5.73-1.48-4.38-1.37-9.13.33-14.54 1.52-4.818 3.93-8.318 7.38-10.71 1.73-1.198 3.92-1.92 5.85-1.92.1 0 .2 0 .3.01l.96.03v.97c0 .772-.01 1.54-.02 2.312-.03 1.67-.062 3.39.05 5.06.23 3.59 1.21 7.03 2.92 10.22.26.488.6 1.208.49 1.948z"/></svg>';
    $svgCpanelLogo = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1136.12 240"><defs><style>.cls-1{fill:#fff;}</style></defs><title>cPanelAsset 14@1x</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M89.69,59.1h67.8L147,99.3a25.38,25.38,0,0,1-9,13.5,24.32,24.32,0,0,1-15.3,5.1H91.19a30.53,30.53,0,0,0-19,6.3,33,33,0,0,0-11.55,17.1,31.91,31.91,0,0,0-.45,15.3A33.1,33.1,0,0,0,66,169.35a30.29,30.29,0,0,0,10.8,8.85,31.74,31.74,0,0,0,14.4,3.3h19.2a10.8,10.8,0,0,1,8.85,4.35,10.4,10.4,0,0,1,2,9.75l-12,44.4h-21a84.77,84.77,0,0,1-39.75-9.45A89.78,89.78,0,0,1,18.29,205.5,88.4,88.4,0,0,1,1.94,170,87.51,87.51,0,0,1,3,129l1.2-4.5A88.69,88.69,0,0,1,35.84,77.25a89.91,89.91,0,0,1,25-13.35A87,87,0,0,1,89.69,59.1Z"/><path class="cls-1" d="M123.89,240,183,18.6a25.38,25.38,0,0,1,9-13.5A24.32,24.32,0,0,1,207.29,0H270a84.77,84.77,0,0,1,39.75,9.45,89.21,89.21,0,0,1,46.65,60.6,83.8,83.8,0,0,1-1.2,41l-1.2,4.5a89.88,89.88,0,0,1-12,26.55,87.65,87.65,0,0,1-73.2,39.15h-54.3l10.8-40.5a25.38,25.38,0,0,1,9-13.2,24.32,24.32,0,0,1,15.3-5.1H267a31.56,31.56,0,0,0,30.6-23.7A29.39,29.39,0,0,0,298,84a33.1,33.1,0,0,0-5.85-12.75,31.76,31.76,0,0,0-10.8-9A30.61,30.61,0,0,0,267,58.8h-33.6l-43.8,162.9a25.38,25.38,0,0,1-9,13.2,23.88,23.88,0,0,1-15,5.1Z"/><path class="cls-1" d="M498,121.8l.9-3.3a4.41,4.41,0,0,0-.75-4,4.58,4.58,0,0,0-3.75-1.65h-97.5a24,24,0,0,1-11.4-2.7,24.94,24.94,0,0,1-8.4-7,24.6,24.6,0,0,1-4.5-10,25.5,25.5,0,0,1,.3-11.7l6-22.8h132a47.39,47.39,0,0,1,22.5,5.4,51.93,51.93,0,0,1,17,14.1,50.34,50.34,0,0,1,9.3,20,49.79,49.79,0,0,1-.45,23.25l-23.7,88.2a40.62,40.62,0,0,1-39.6,30.3l-97.5-.3A51.59,51.59,0,0,1,357,219.15a54.4,54.4,0,0,1-9.6-21A49.48,49.48,0,0,1,348,174l1.2-4.5a47.58,47.58,0,0,1,7.05-15.6,54,54,0,0,1,11.55-12.3,52.06,52.06,0,0,1,14.7-7.95,51.14,51.14,0,0,1,17.1-2.85h81.9l-6,22.5a25.49,25.49,0,0,1-9,13.2,23.92,23.92,0,0,1-15,5.1h-36.6q-5.11,0-6.6,5.1a6.13,6.13,0,0,0,1.2,5.85,6.65,6.65,0,0,0,5.4,2.55H474a9.27,9.27,0,0,0,5.7-1.8,7.76,7.76,0,0,0,3-4.8l.6-2.4Z"/><path class="cls-1" d="M672.59,59.1a85.39,85.39,0,0,1,40,9.45,89.82,89.82,0,0,1,30.16,25,88.39,88.39,0,0,1,16.34,35.7,85.78,85.78,0,0,1-1.34,41.1l-15,56.4a16.53,16.53,0,0,1-6.45,9.6,18.22,18.22,0,0,1-11,3.6H693a11,11,0,0,1-10.81-14.1l18-68.1a29.39,29.39,0,0,0,.45-14.7,33.23,33.23,0,0,0-5.84-12.75,32,32,0,0,0-10.8-9,30.67,30.67,0,0,0-14.4-3.45H636L606.88,226.8a16.4,16.4,0,0,1-6.45,9.6,18.65,18.65,0,0,1-11.25,3.6h-32.1a10.78,10.78,0,0,1-8.84-4.35,10.43,10.43,0,0,1-2-9.75l44.4-166.8Z"/><path class="cls-1" d="M849.28,116.25a15.34,15.34,0,0,0-5.1,7.35l-13.5,51a9,9,0,0,0,8.7,11.4h124.2L954,221.7a25.38,25.38,0,0,1-9,13.2,23.88,23.88,0,0,1-15,5.1H816.88a48.43,48.43,0,0,1-22.5-5.25,49.48,49.48,0,0,1-17-14.1,51.48,51.48,0,0,1-9.3-20.1,46,46,0,0,1,.75-23l18.3-68.1a67.5,67.5,0,0,1,9.3-20.4,67.3,67.3,0,0,1,34-26.25,65.91,65.91,0,0,1,22.05-3.75h80.1a47.34,47.34,0,0,1,22.5,5.4,51.83,51.83,0,0,1,17,14.1,48.65,48.65,0,0,1,9.15,20.1,50.2,50.2,0,0,1-.6,23.1l-5.4,20.4A39.05,39.05,0,0,1,960.73,164,40.08,40.08,0,0,1,936,172.2h-90.6l6-22.2a23.78,23.78,0,0,1,8.7-13.2,24.32,24.32,0,0,1,15.3-5.1H912q5.1,0,6.6-5.1l1.2-4.5a6.92,6.92,0,0,0-6.6-8.7h-55.8A12.71,12.71,0,0,0,849.28,116.25Z"/><path class="cls-1" d="M963.28,240l60.3-226.5A17.06,17.06,0,0,1,1030,3.75,18.14,18.14,0,0,1,1041.28,0h32.1a11.11,11.11,0,0,1,9.15,4.35,10.43,10.43,0,0,1,2,9.75l-45,167.1a74.52,74.52,0,0,1-10.65,24,78.66,78.66,0,0,1-17.4,18.45,81.65,81.65,0,0,1-22.35,12A76.85,76.85,0,0,1,963.28,240Z"/><path class="cls-1" d="M1094.83,21.06a20.4,20.4,0,0,1,2.75-10.29A20.6,20.6,0,0,1,1115.48.42a20.39,20.39,0,0,1,10.29,2.74,20.13,20.13,0,0,1,7.58,7.55,20.73,20.73,0,0,1,.11,20.51,20.67,20.67,0,0,1-36,0A20.37,20.37,0,0,1,1094.83,21.06Zm2.88,0a17.76,17.76,0,0,0,8.91,15.39,17.67,17.67,0,0,0,17.73,0,17.89,17.89,0,0,0,6.49-6.47,17.21,17.21,0,0,0,2.4-8.91,17.18,17.18,0,0,0-2.39-8.86,17.89,17.89,0,0,0-6.46-6.5,17.7,17.7,0,0,0-17.78,0,17.87,17.87,0,0,0-6.49,6.46A17.17,17.17,0,0,0,1097.71,21.06Zm26.14-5a6.64,6.64,0,0,1-1.17,3.88,6.79,6.79,0,0,1-3.28,2.51l6.54,10.85h-4.61l-5.69-9.72h-3.7v9.72h-4.07V8.85H1115c3,0,5.26.59,6.68,1.78A6.69,6.69,0,0,1,1123.85,16.07Zm-11.91,4.14h3a5.24,5.24,0,0,0,3.53-1.14,3.63,3.63,0,0,0,1.33-2.89,3.44,3.44,0,0,0-1.18-2.95,6.19,6.19,0,0,0-3.73-.9h-2.91Z"/></g></g></svg>';
    $jsVars = $runtime;
}
?>
<!DOCTYPE html>
<html lang="en" id="<?php echo $pageId; ?>">

<head>
    <meta name="generator" content="<?php echo APP_NAME . ' v' . APP_VERSION; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="<?php echo $themeColor; ?>">
    <title><?php echo $doctitle; ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo $shortcutIcon; ?>">
    <style>
        <?php echo $css; ?>
    </style>
    <script>
        const appUrl = <?php echo json_encode(APP_URL); ?>;
        const runtime = <?php echo json_encode($jsVars); ?>;
        const patterns = <?php echo json_encode($patterns); ?>;
    </script>
</head>

<body class="body--flex">
    <main>
        <?php if ($pageId == 'error') { ?>
  <div id="screen-error" class="screen screen--error">
    <div class="flex-box error-box">
      <div>
        <h1>Aw, Snap!</h1>
        <p>Your web server lacks some requirements that must be fixed to install Chevereto.</p>
        <p>Please check:</p>
        <ul>
          <?php
            foreach ($requirementsCheck->errors as $v) {
                ?>
            <li><?php echo $v; ?></li>
          <?php
            } ?>
        </ul>
        <p>If you already fixed your web server then make sure to restart it to apply changes. If the problem persists, contact your server administrator.</p>
        <p>Check our <a href="https://chevereto.com/hosting" target="_blank">hosting</a> offer if you don't want to worry about this.</p>
        <p class="error-box-code">Server <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
      </div>
    </div>
  </div>
<?php } else { ?>
  <div id="screen-welcome" class="screen screen--show animate animate--slow">
    <div class="header flex-item"><?php echo $svgLogo; ?></div>
    <div class="flex-box flex-item">
      <div>
        <h1>Chevereto Installer</h1>
        <p>This tool will guide you through the process of installing Chevereto. To proceed, check the information below.</p>
        <ul>
          <li>Server path <code><?php echo $runtime->absPath; ?></code></li>
          <li>Website url <code><?php echo $runtime->rootUrl; ?></code></li>
        </ul>
        <p>Confirm that the above details match to where you want to install Chevereto and that there's no other software installed.</p>
        <?php
          if (preg_match('/nginx/i', $runtime->serverSoftware)) { ?>
          <p class="alert">Add the following <a href="<?php echo $runtime->rootUrl . $runtime->installerFilename . '?getNginxRules'; ?>" target="_blank">server rules</a> to your <a href="https://www.digitalocean.com/community/tutorials/understanding-the-nginx-configuration-file-structure-and-configuration-contexts" target="_blank">nginx.conf</a> server block. <b>Restart the server to apply changes</b>. Once done, come back here and continue the process.</p>
        <?php } ?>
        <div>
          <button class="action radius" data-action="show" data-arg="license">Continue</button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-license" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Enter license key</h1>
        <p>A license key is required to install our main edition. You can purchase a license from our <a href="https://chevereto.com/pricing" target="_blank">website</a> if you don't have one yet.</p>
        <p></p>
        <p>Skip this to install <a href="https://chevereto.com/free" target="_blank">Chevereto-Free</a>, which is our Open Source edition.</p>
        <p class="highlight">The paid edition has more features, gets more frequent updates, and provides additional support assistance.</p>
        <p class="p alert"></p>
        <div class="p input-label">
          <label for="installKey">License key</label>
          <input class="radius width-100p" type="text" name="installKey" id="installKey" placeholder="Paste your license key here" autofill="off" autocomplete="off">
          <div><small>You can find the license key at your <a href="https://chevereto.com/panel/license" target="_blank">client panel</a>.</small></div>
        </div>
        <div>
          <button class="action radius" data-action="setLicense" data-arg="installKey">Enter license key</button>
          <button class=" radius" data-action="setSoftware" data-arg="chevereto-free">Skip – Use Chevereto-Free</button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-sorry" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>No PHP 7.3 support</h1>
        <p>We're sorry, but Chevereto-Free doesn't support PHP 7.3 yet. Switch to PHP 7.2 or install our paid edition.</p>
        <div>
          <button class="radius" data-action="show" data-arg="license">Back</button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-upgrade" class="screen animate animate--slow">
    <div class="header flex-item"><?php echo $svgLogo; ?></div>
    <div class="flex-box col-width">
      <div>
        <h1>Upgrade</h1>
        <p>A license key is required to upgrade to our main edition. You can purchase a license from our <a href="https://chevereto.com/pricing" target="_blank">website</a> if you don't have one yet.</p>
        <p>The system database schema will change, and the system files will get replaced. Don't forget to backup.</p>
        <p>Your system settings, previous uploads, and all user-generated content will remain there.</p>
        <p class="p alert"></p>
        <div class="p input-label">
          <label for="upgradeKey">License key</label>
          <input class="radius width-100p" type="text" name="upgradeKey" id="upgradeKey" placeholder="Paste your license key here">
          <div><small>You can find the license key at your <a href="https://chevereto.com/panel/license" target="_blank">client panel</a>.</small></div>
        </div>
        <div>
          <button class="action radius" data-action="setUpgrade" data-arg="upgradeKey">Enter license key</button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-cpanel" class="screen animate animate--slow">
    <div class="header flex-item"><?php echo $svgCpanelLogo; ?></div>
    <div class="flex-box col-width">
      <div>
        <h1>cPanel access</h1>
        <p>This installer can connect to a cPanel backend using the <a href="https://documentation.cpanel.net/display/DD/Guide+to+UAPI" target="_blank">cPanel UAPI</a> to create the database, its user, and grant database privileges.</p>
        <?php if ('https' == $runtime->httpProtocol) { ?>
          <p class="highlight">You are not browsing using HTTPS. For extra security, change your cPanel password once the installation gets completed.</p>
        <?php } ?>
        <p>The cPanel credentials won't be stored either transmitted to anyone.</p>
        <p class="highlight">Skip this if you don't run cPanel or if you want to setup the database requirements manually.</p>
        <p class="p alert"></p>
        <div class="p input-label">
          <label for="cpanelUser">User</label>
          <input class="radius width-100p" type="text" name="cpanelUser" id="cpanelUser" placeholder="username" autocomplete="off">
        </div>
        <div class="p input-label">
          <label for="cpanelPassword">Password</label>
          <input class="radius width-100p" type="password" name="cpanelPassword" id="cpanelPassword" placeholder="password" autocomplete="off">
        </div>
        <div>
          <button class="action radius" data-action="cPanelProcess">Connect to cPanel</button>
          <button class="radius" data-action="show" data-arg="db">Skip</button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-db" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Database</h1>
        <p>Chevereto requires a MySQL 8 (MySQL 5.6 min) database. It will also work with MariaDB 10.</p>
        <form method="post" name="database" data-trigger="setDb" autocomplete="off">
          <p class="p alert"></p>
          <div class="p input-label">
            <label for="dbHost">Host</label>
            <input class="radius width-100p" type="text" name="dbHost" id="dbHost" placeholder="localhost" value="localhost" required>
            <div><small>If you are using Docker, enter the MySQL/MariaDB container hostname or its IP.</small></div>
          </div>
          <div class="p input-label">
            <label for="dbPort">Port</label>
            <input class="radius width-100p" type="number" name="dbPort" id="dbPort" value="3306" placeholder="3306" required>
          </div>
          <div class="p input-label">
            <label for="dbName">Name</label>
            <input class="radius width-100p" type="text" name="dbName" id="dbName" placeholder="mydatabase" required>
          </div>
          <div class="p input-label">
            <label for="dbUser">User</label>
            <input class="radius width-100p" type="text" name="dbUser" id="dbUser" placeholder="username" required>
            <div><small>The database user must have ALL PRIVILEGES on the target database.</small></div>
          </div>
          <div class="p input-label">
            <label for="dbUserPassword">User password</label>
            <input class="radius width-100p" type="password" name="dbUserPassword" id="dbUserPassword" placeholder="password">
          </div>
          <div>
            <button class="action radius">Set database</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="screen-admin" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Administrator</h1>
        <p>Fill in your administrator user details. You can edit this account or add more administrators later.</p>
        <form method="post" name="admin" data-trigger="setAdmin" autocomplete="off">
          <p class="p alert"></p>
          <div class="p input-label">
            <label for="adminEmail">Email</label>
            <input class="radius width-100p" type="email" name="adminEmail" id="adminEmail" placeholder="username@domain.com" autocomplete="off" required>
            <div><small>Make sure that this email is working or you won't be able to recover the account if you lost the password.</small></div>
          </div>
          <div class="p input-label">
            <label for="adminUsername">Username</label>
            <input class="radius width-100p" type="text" name="adminUsername" id="adminUsername" placeholder="admin" pattern="<?php echo $patterns['username_pattern']; ?>" autocomplete="off" required>
            <div><small>3 to 16 characters. Letters, numbers and underscore.</small></div>
          </div>
          <div class="p input-label">
            <label for="adminPassword">Password</label>
            <input class="radius width-100p" type="password" name="adminPassword" id="adminPassword" placeholder="password" pattern="<?php echo $patterns['user_password_pattern']; ?>" autocomplete="off" required>
            <div><small>6 to 128 characters.</small></div>
          </div>
          <div>
            <button class="action radius">Set administrator</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="screen-emails" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Email addresses</h1>
        <p>Fill in the email addresses that will be used by the system. You can edit this later.</p>
        <form method="post" name="emails" data-trigger="setEmails">
          <p class="p alert"></p>
          <div class="p input-label">
            <label for="no-reply">No-reply</label>
            <input class="radius width-100p" type="email" name="emailNoreply" id="emailNoreply" placeholder="no-reply@domain.com" required>
            <div><small>This address will be used as FROM email address when sending transactional emails (account functions, singup, alerts, etc.)</small></div>
          </div>
          <div class="p input-label">
            <label for="inbox">Inbox</label>
            <input class="radius width-100p" type="email" name="emailInbox" id="emailInbox" placeholder="inbox@domain.com" required>
            <div><small>This address will be used to get contact form messages.</small></div>
          </div>
          <div>
            <button class="action radius">Set emails</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="screen-ready" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Ready to install</h1>
        <p>The installer is ready to download and install the latest <span class="chevereto-free--hide">Chevereto</span><span class="chevereto--hide">Chevereto-Free</span> release in <code><?php echo $runtime->absPath; ?></code></p>
        <p class="highlight chevereto-free--hide">By installing is understood that you accept the <a href="https://chevereto.com/license" target="_blank">Chevereto EULA</a>.</p>
        <p class="highlight chevereto--hide">By installing is understood that you accept the Chevereto-Free <a href="<?php echo APPLICATIONS['chevereto-free']['url'] . '/blob/master/LICENSE'; ?>" target="_blank">AGPLv3 license</a>.</p>
        <div>
          <button class="action radius" data-action="install">Install <span class="chevereto-free--hide">Chevereto</span><span class="chevereto--hide">Chevereto-Free</span></button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-ready-upgrade" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Ready to upgrade</h1>
        <p>The installer is ready to download and upgrade to the latest Chevereto release in <code><?php echo $runtime->absPath; ?></code></p>
        <p class="highlight">By upgrading is understood that you accept the <a href="https://chevereto.com/license" target="_blank">Chevereto EULA</a>.</p>
        <div>
          <button class="action radius" data-action="upgrade">Upgrade Chevereto</button>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-installing" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Installing</h1>
        <p>The software is being installed. Don't close this window until the process gets completed.</p>
        <p class="p alert"></p>
        <div class="log log--install p"></div>
      </div>
    </div>
  </div>

  <div id="screen-upgrading" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Upgrading</h1>
        <p>The software is being upgraded. Don't close this window until the process gets completed.</p>
        <p class="p alert"></p>
        <div class="log log--upgrade p"></div>
      </div>
    </div>
  </div>

  <div id="screen-complete" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Installation completed</h1>
        <p>Chevereto has been installed. You can now login to your dashboard panel to configure your website to fit your needs.</p>
        <p class="alert">The installer has self-removed its file at <code><?php echo INSTALLER_FILEPATH; ?></code></p>
        <p>Take note on the installation details below.</p>
        <div class="install-details p highlight font-size-80p"></div>
        <p>Hope you enjoy using Chevereto as much I care in creating it. Help development by providing feedback and recommend my software.</p>
        <div>
          <a class="button action radius" href="<?php echo $runtime->rootUrl; ?>dashboard" target="_blank">Open dashboard</a>
          <a class="button radius" href="<?php echo $runtime->rootUrl; ?>" target="_blank">Open homepage</a>
        </div>
      </div>
    </div>
  </div>

  <div id="screen-complete-upgrade" class="screen animate animate--slow">
    <div class="flex-box col-width">
      <div>
        <h1>Upgrade prepared</h1>
        <p>The system files have been upgraded. You can now install the upgrade which will perform the database changes needed and complete the process.</p>
        <p class="alert">The installer has self-removed its file at <code><?php echo INSTALLER_FILEPATH; ?></code></p>
        <div>
          <a class="button action radius" href="<?php echo $runtime->rootUrl; ?>install">Install upgrade</a>
        </div>
      </div>
    </div>
  </div>

<?php } ?>
    </main>
    <script>
        <?php echo $script; ?>
    </script>
</body>

</html>