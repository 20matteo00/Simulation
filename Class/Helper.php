<?php

class Helper
{
    public $menu = [
        'group',
        'team',
        'modality',
    ];

    public function getAccess($loggato, $level = null){
        if ($loggato && $level == 1) {
            return [
                'reserved',
                'profile',
                'logout',
            ];
        } elseif ($loggato) {
            return [
                'profile',
                'logout',
            ];
        } else {
            return [
                'login',
                'register',
            ];
        }
    } 

    public $lang = [
        'it',
        'en',
    ];
    public function loadLanguage($langCode = 'it')
    {
        $path = "Language/$langCode.json";

        if (!file_exists($path)) {
            return []; // oppure lancia un errore
        }

        $json = file_get_contents($path);
        return json_decode($json, true);
    }
}
