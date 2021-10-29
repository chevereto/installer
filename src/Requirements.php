<?php

final class Requirements
{

    public function __construct(
        public string $phpMinimum,
        public string $phpRecommended,
        public array $phpExtensions,
        public array $phpClasses
    )
    {
    }
}
