<?php

final class ZipArchiveExt extends ZipArchive
{
    public function extractSubDirTo($destination, $subDir)
    {
        $errors = array();
        $destination = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $destination);
        $subDir = str_replace(array('/', '\\'), '/', $subDir);
        if (substr($destination, mb_strlen(DIRECTORY_SEPARATOR, 'UTF-8') * -1) != DIRECTORY_SEPARATOR) {
            $destination .= DIRECTORY_SEPARATOR;
        }
        $inputSubDir = $subDir;
        $subDir = rtrim($subDir, '/') . '/';
        $folderExists = false;
        for ($i = 0; $i < $this->numFiles; ++$i) {
            $filename = $this->getNameIndex($i);
            if (!$folderExists && $filename == $subDir) {
                $folderExists = true;
            }
            if (substr($filename, 0, mb_strlen($subDir, 'UTF-8')) == $subDir) {
                $relativePath = substr($filename, mb_strlen($subDir, 'UTF-8'));
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
            throw new Exception(sprintf("Folder %s doesn't exists in zip file", $inputSubDir));
        }

        return $errors;
    }
}
