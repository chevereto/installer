<?php
set_error_handler(function (int $severity, string $message, string $file, int $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
}, $phpSettings['error_reporting']);

set_exception_handler(function (Throwable $e) {
    $device = isDocker() ? 'stderr' : 'error_log';
    $debug_level = 3;
    $internal_code = 500;
    $internal_error = '<b>Aw, snap!</b>';
    $table = [
        0 => "debug is disabled",
        1 => "debug @ $device",
        2 => "debug @ print",
        3 => "debug @ print,$device",
    ];
    $internal_error .= ' [' . $table[$debug_level] . '] - https://chv.to/v3debug';
    $message = [$internal_error];
    $message[] = '';
    $message[] = '<b>Fatal error [' . $e->getCode() . ']:</b> ' . strip_tags($e->getMessage());
    $message[] = 'Triggered in ' . $e->getFile() . ':' . $e->getLine() . "\n";
    $message[] = '<b>Stack trace:</b>';
    $rtn = '';
    $count = 0;
    foreach ($e->getTrace() as $frame) {
        $args = '';
        if (isset($frame['args'])) {
            $args = array();
            foreach ($frame['args'] as $arg) {
                switch (true) {
                    case is_string($arg):
                        $args[] = "'" . $arg . "'";
                        break;
                    case is_array($arg):
                        $args[] = 'Array';
                        break;
                    case is_null($arg):
                        $args[] = 'NULL';
                        break;
                    case is_bool($arg):
                        $args[] = ($arg) ? 'true' : 'false';
                        break;
                    case is_object($arg):
                        $args[] = get_class($arg);
                        break;
                    case is_resource($arg):
                        $args[] = get_resource_type($arg);
                        break;
                    default:
                        $args[] = $arg;
                        break;
                }
            }
            $args = join(', ', $args);
        }
        $rtn .= sprintf(
            "#%s %s(%s): %s(%s)\n",
            $count,
            $frame['file'] ?? 'unknown file',
            $frame['line'] ?? 'unknown line',
            (isset($frame['class'])) ? $frame['class'] . $frame['type'] . $frame['function'] : $frame['function'],
            $args
        );
        ++$count;
    }
    $message[] = $rtn;
    $message = implode("\n", $message);
    $newLines = nl2br($message);
    $plainLines = "\n" . strip_tags($newLines);
    set_status_header($internal_code);
    if(in_array($debug_level, [2, 3])) {
        echo PHP_SAPI !== 'cli'  ? $newLines : $plainLines;
    }
    if(in_array($debug_level, [1, 3]) && $device === 'error_log') {
        error_log($plainLines);
    }
    if(isDocker()) {
        writeToStderr($plainLines);
    }

    die(255);
});
