<?php

final class Requirements
{
    public array $phpVersions;

    public array $phpExtensions;

    public array $phpClasses;

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
