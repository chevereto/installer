<?php

include __DIR__ . '/functions.php';
include __DIR__ . '/Make.php';

new Make(
    dirname(__DIR__) . '/app.php',
    dirname(__DIR__) . '/build/installer.php'
);