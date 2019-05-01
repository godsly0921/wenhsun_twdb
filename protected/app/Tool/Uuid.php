<?php

declare(strict_types=1);

namespace Wenhsun\Tool;

class Uuid
{
    public static function gen(): string
    {
        $t = explode(" ", microtime());
        $rawId = md5(uniqid((string) rand(), true));
        $r1 = substr($rawId, 0, 8);

        return sprintf(
            '%08s%08s%04s%04x%04x',
            $r1,
            substr("00000000".dechex($t[1]), -8),
            substr("0000" . dechex(round($t[0] * 65536)), -4), // get 4HEX of microtime
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}