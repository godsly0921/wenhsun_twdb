<?php

declare(strict_types=1);

namespace Wenhsun\Tool;

class ModelErrorBuilder
{
    public function modelErrors2Html($errors): string
    {
        $errString = "";
        foreach ($errors as $errField => $err) {
            $errString .= $err[0] . "<br>";
        }

        return $errString;
    }
}