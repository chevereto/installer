<?php

$min_memory = '256M';
$memory_limit = ini_get('memory_limit');
$memory_limit_bytes = isset($memory_limit) ? get_ini_bytes($memory_limit) : 0;
if ($memory_limit_bytes < get_ini_bytes($min_memory)) {
    @ini_set('memory_limit', $min_memory);
}