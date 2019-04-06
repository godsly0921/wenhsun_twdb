<?php

declare(strict_types=1);

namespace Wenhsun\Transform;

class MultiColumnTransformer
{
    public function toJson(string $split, ?string $text): string
    {
        if ($text === '' || $text === null) {
            return json_encode([]);
        }

        $data = explode($split, $text);

        $r = json_encode($data);

        return $r;
    }

    public function toText(string $split, string $json): string
    {
        $data = json_decode($json);

        if (json_last_error()) {
            return '';
        }

        $text = implode($split, $data);

        return $text;
    }
}