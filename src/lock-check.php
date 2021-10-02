<?php

if(file_exists(LOCK_FILEPATH)) {
    if(PHP_SAPI === 'cli') {
        logger("Locked (" . LOCK_FILEPATH . ")\n");
    } else {
        set_status_header(403);
    }
    die(255);
}