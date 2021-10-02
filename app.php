<?php
/* --------------------------------------------------------------------

    Chevereto Installer
    http://chevereto.com/

    @author	Rodolfo Berrios A. <http://rodolfoberrios.com/>
          __                        __     
     ____/ /  ___ _  _____ _______ / /____ 
    / __/ _ \/ -_) |/ / -_) __/ -_) __/ _ \ 
    \__/_//_/\__/|___/\__/_/  \__/\__/\___/

  --------------------------------------------------------------------- */

declare(strict_types=1);

const APP_NAME = 'Chevereto Installer';
const APP_VERSION = '3.0.0';
const APP_URL = 'https://github.com/chevereto/installer';
const PHP_VERSION_MIN = '7.4';
const PHP_VERSION_RECOMMENDED = '7.4';
const VENDOR = [
    'name' => 'Chevereto',
    'url' => 'https://chevereto.com',
    'apiUrl' => 'https://chevereto.com/api',
    'apiLicense' => 'https://chevereto.com/api/license/check',
];
const APPLICATION = [
    'name' => 'Chevereto',
    'version' => '3',
    'license' => 'Paid',
    'url' => 'https://chevereto.com',
    'zipball' => 'https://chevereto.com/api/download/%tag%',
    'folder' => 'chevereto',
    'vendor' => VENDOR,
];
const APPLICATION_FULL_NAME = APPLICATION['name'] . ' V' . APPLICATION['version'];

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
$shortcutIcon = 'data:image/png;base64,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';
define('ERROR_LOG_FILEPATH', $phpSettings['error_log']);
const INSTALLER_FILEPATH = __FILE__;
const LOCK_FILEPATH = __DIR__ . '/installer.lock';
include 'src/functions.php';
include 'src/ini-set.php';
include 'src/setErrorExceptionHandler.php';
include 'src/Logger.php';
include 'src/ZipArchiveExt.php';
include 'src/Requirements.php';
include 'src/RequirementsCheck.php';
include 'src/Runtime.php';
include 'src/JsonResponse.php';
include 'src/Database.php';
include 'src/Controller.php';
$logger = new Logger(APP_NAME . ' ' . APP_VERSION);
$requirements = new Requirements([PHP_VERSION_MIN, PHP_VERSION_RECOMMENDED]);
$requirements->setPHPExtensions($phpExtensions);
$requirements->setPHPClasses($phpClasses);
$runtime = new Runtime($logger);
$runtime->setSettings($phpSettings);
$runtime->run();
include 'src/lock-check.php';
$opts = getopt('a:') ?? null;
if(!empty($_POST)) {
    $params = $_POST;
} else if(!empty($opts)) {
    $action = $opts['a'];
    $params = ['action' => $action];
    switch($action) {
        case 'checkLicense':
            $opts = getopt('a:l:');
            $params['license'] = $opts['l'] ?? null;
            break;
        case 'checkDatabase':
            $opts = getopt('a:h:p:n:u:x:');
            $params['host'] = $opts['h'] ?? null;
            $params['port'] = $opts['p'] ?? null;
            $params['name'] = $opts['n'] ?? null;
            $params['user'] = $opts['u'] ?? null;
            $params['userPassword'] = $opts['x'] ?? null;
            break;
        case 'download':            
            $opts = getopt('a:t::l::');
            $params['tag'] = $opts['t'] ?? null;
            $params['license'] = $opts['l'] ?? null;
            break;
        case 'extract':            
            $opts = getopt('a:p:f:');
            $params['workingPath'] = $opts['p'] ?? null;
            $params['filePath'] = $opts['f'] ?? null;
            break;
        case 'createSettings':            
            $opts = getopt('a:h:p:n:u:x:f:');
            $params['host'] = $opts['h'] ?? null;
            $params['port'] = $opts['p'] ?? null;
            $params['name'] = $opts['n'] ?? null;
            $params['user'] = $opts['u'] ?? null;
            $params['userPassword'] = $opts['x'] ?? null;
            $params['filePath'] = $opts['f'] ?? null;
            break;
        case 'lock':
        case 'selfDestruct':
            break;
    }
}
$requirementsCheck = new RequirementsCheck($requirements, $runtime);
if (isset($params)) {
    $jsonResponse = new JsonResponse();
    if (!empty($requirementsCheck->errors)) {
        $errorsPlain = array_map(function ($v) {
            return trim(strip_tags($v));
        }, $requirementsCheck->errors);
        $jsonResponse->setResponse('Missing server requirements', 500);
        $jsonResponse->addData('errors', $errorsPlain);
    } else {
        try {
            $controller = new Controller($params, $runtime);
            $jsonResponse->setResponse($controller->response, $controller->code);
            if (!empty($controller->data)) {
                $jsonResponse->setData($controller->data);
            }
        } catch (Throwable $e) {
            error_log($e->getMessage() . '***' . $e->getTraceAsString());
            $jsonResponse->setResponse($e->getMessage(), $e->getCode());
            $jsonResponse->send(255);
        }
    }
    $jsonResponse->send(0);
} else {
    if (isset($_GET['getNginxRules'])) {
        require 'template/nginx.php';
        die();
    }
    if(!empty($opts)) {
        die();
    }
    $pageId = $requirementsCheck->errors ? 'error' : 'install';
    $doctitle = APP_NAME;
    $css = file_get_contents('html/style.css');
    $script = file_get_contents('html/script.js');
    $svgLogo = file_get_contents('html/logo.svg');
    $jsVars = $runtime;
    ob_start();
    require 'template/content.php';
    $content = ob_get_clean();
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
        const applicationFullName = <?php echo json_encode(APPLICATION_FULL_NAME); ?>;
    </script>
</head>
<body class="body--flex">
    <main>
        <?php echo $content; ?>
    </main>
    <script>
        <?php echo $script; ?>
    </script>
</body>
</html>