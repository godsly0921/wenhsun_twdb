<?php

declare(strict_types=1);

namespace Wenhsun\Tool;

class FIleTool
{
    public function upload(array $fileInfo, $dest, $fileName): string
    {
        $tempFile = $fileInfo['tmp_name'];
        $pathInfo = pathinfo($fileInfo['name']);

        $fullFileName = "{$dest}/{$fileName}.{$pathInfo['extension']}";

        if (!move_uploaded_file($tempFile, $fullFileName)) {
            throw new FileToolException("update file failed");
        }

        return $fullFileName;
    }
}