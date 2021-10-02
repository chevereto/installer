<?php

final class Controller
{
    public array $params;

    public string $response;

    public array $data;

    public Runtime $runtime;

    public int $code;

    public function __construct(array $params, Runtime $runtime)
    {
        $this->runtime = $runtime;
        if (!isset($params['action'])) {
            throw new Exception('Missing action parameter', 400);
        }
        $this->params = $params;
        $method = $this->params['action'] . 'Action';
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid action ' . $this->params['action'], 400);
        }
        $this->{$method}($this->params);
    }

    public function checkLicenseAction(array $params): void
    {
        if(!isset($params['license'])) {
            throw new InvalidArgumentException('Missing license parameter');
        }
        $post = $this->curl(VENDOR['apiLicense'], [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(['license' => $params['license']]),
        ]);
        if (isset($post->json->error)) {
            throw new Exception($post->json->error->message, 403);
        }
        if($post->json->data->version != APPLICATION['version']) {
            throw new Exception(
                strtr(
                    '%required% license required (V%provided% license provided)',
                    [
                        '%required%' => APPLICATION_FULL_NAME,
                        '%provided%' => $post->json->data->version,
                    ]
                ),
                404);
        }
        $this->response = 200 == $this->code
            ? 'Valid license key'
            : 'Unable to check license';
    }

    public function checkDatabaseAction(array $params): void
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

    public function lockAction(): void
    {
        put(LOCK_FILEPATH, '');
        if(file_exists(LOCK_FILEPATH)) {
            $this->code = 200;
            $this->response = 'installer locked';
        } else {
            $this->code = 500;
            $this->response = 'unable to lock installer';
        }
    }

    public function selfDestructAction(): void
    {
        unlink(INSTALLER_FILEPATH);
        if(!file_exists(LOCK_FILEPATH)) {
            $this->code = 200;
            $this->response = 'installer destroyed';
        } else {
            $this->code = 500;
            $this->response = 'unable to destroy installer';
        }
    }

    public function downloadAction(array $params): void
    {
        $fileBasename = 'chevereto-pkg-' . substr(bin2hex(random_bytes(8)), 0, 8) . '.zip';
        $filePath = $this->runtime->absPath . $fileBasename;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        $isPost = false;
        $zipBall = APPLICATION['zipball'];
        $tag = $params['tag'] ?? 'latest';
        $zipBall = str_replace('%tag%', $tag, $zipBall);
        $isPost = true;
        $curl = $this->downloadFile($zipBall, $params, $filePath, $isPost);
        if (isset($curl->json->error)) {
            throw new RuntimeException($curl->json->error->message, $curl->json->status_code);
        }
        if (200 != $curl->transfer['http_code']) {
            throw new RuntimeException('[HTTP ' . $curl->transfer['http_code'] . '] ' . $zipBall, $curl->transfer['http_code']);
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

    public function extractAction(array $params): void
    {
        $software = APPLICATION;
        if (!isset($params['workingPath'])) {
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
        $numFiles = $zipExt->numFiles - 1;
        $folder = $software['folder'];
        $extraction = $zipExt->extractSubDirTo($workingPath, $folder);
        if (!empty($extraction)) {
            throw new Exception(implode(', ', $extraction));
        }
        $zipExt->close();
        $timeTaken = round(microtime(true) - $timeStart, 2);
        @unlink($filePath);
        $this->code = 200;
        $this->response = strtr('Extraction completed (%n files in %ss)', ['%n' => $numFiles, '%s' => $timeTaken]);
    }

    public function createSettingsAction(array $params): void
    {
        if(!isset($params['filePath'])) {
            throw new InvalidArgumentException('Missing filePath');
        }
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

    public function downloadFile(string $url, array $params, string $filePath, bool $post = true): object
    {
        $fp = @fopen($filePath, 'wb+');
        if (!$fp) {
            throw new Exception("Can't open temp file " . $filePath . ' (wb+)');
        }
        $ops = [
            CURLOPT_FILE => $fp,
        ];
        if ($params !== []) {
            $ops[CURLOPT_POSTFIELDS] = http_build_query($params);
        }
        if ($post) {
            $ops[CURLOPT_POST] = true;
        }
        $curl = $this->curl($url, $ops);
        fclose($fp);

        return $curl;
    }

    public function curl(string $url, array $curlOpts = []): object
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Chevereto Installer');
        $fp = false;
        foreach ($curlOpts as $k => $v) {
            if (CURLOPT_FILE == $k) {
                $fp = $v;
            }
            curl_setopt($ch, $k, $v);
        }
        logger("Fetching $url\n");
        $file_get_contents = @curl_exec($ch);
        logger("\n");
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

    public function getFormatBytes($bytes, int $round = 1): string
    {
        if (!is_numeric($bytes)) {
            return (string) $bytes;
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

    public function getBytesToMb($bytes, int $round = 2): float
    {
        $mb = $bytes / pow(10, 6);
        if ($round) {
            $mb = round($mb, $round);
        }

        return $mb;
    }
}
