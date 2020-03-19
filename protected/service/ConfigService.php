<?php

class ConfigService
{
    public function findByConfigName($name)
    {
        $config = Config::model()->findAll([
            'select' => 'config_value',
            'condition'=>'config_name = :config_name',
            'params'=> [
                ':config_name' => $name,
            ]
        ]);

        return $config;
    }
}
