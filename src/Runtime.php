<?php

final class Runtime
{
    public array $settings;

    private Logger $logger;

    public string $absPath;

    public string $relPath;

    public string $installerFilepath;

    public string $httpHost;

    public string $httpProtocol;

    public string $rootUrl;

    public string $serverString;

    public array $workingPaths;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setSettings(array $settings): void
    {
        $this->settings = $settings;
    }

    public function setServer(array $server): void
    {
        $this->server = $server;
    }

    public function run(): void
    {
        error_reporting($this->settings['error_reporting']);
        $this->applyPHPSettings($this->settings);
        $this->processContext();
    }

    private function applyPHPSettings(array $settings): void
    {
        $runtimeTable = [
            'log_errors' => ini_set('log_errors', (string) $settings['log_errors']),
            'display_errors' => ini_set('display_errors', (string) $settings['display_errors']),
            'error_log' => ini_set('error_log', $settings['error_log']),
            'time_limit' => @set_time_limit($settings['time_limit']),
            'ini_set' => ini_set('default_charset', $settings['default_charset']),
            'setlocale' => setlocale(LC_ALL, $settings['LC_ALL']),
        ];
        $messageTemplate = 'Unable to set %k value %v (FALSE return value)';
        foreach ($runtimeTable as $k => $v) {
            if (false === $v) {
                $this->logger->addMessage(strtr($messageTemplate, [
                    '%k' => $k,
                    '%v' => var_export($settings[$k] ?? '', true),
                ]));
            }
        }
    }

    private function processContext(): void
    {
        if (!isset($this->server)) {
            $this->setServer($_SERVER);
        }
        $this->php = phpversion();
        $this->absPath = rtrim(str_replace('\\', '/', dirname(INSTALLER_FILEPATH)), '/') . '/';
        $this->relPath = rtrim(dirname($this->server['SCRIPT_NAME']), '\/') . '/';
        $this->installerFilename = basename(INSTALLER_FILEPATH);
        $this->installerFilepath = INSTALLER_FILEPATH;
        $this->httpHost = $this->server['HTTP_HOST'] ?? 'null';
        $this->serverSoftware = $this->server['SERVER_SOFTWARE'] ?? 'null';
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

    private function setWorkingPaths(array $workingPaths): void
    {
        $this->workingPaths = $workingPaths;
    }
}
