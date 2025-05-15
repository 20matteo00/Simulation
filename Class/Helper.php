<?php

class Helper
{
    public $menu = [
        'teams',
        'modality',
        'competitions',
    ];

    public $lang = [
        'it',
        'en',
    ];

    public $modality = [
        'simple_league' => 'simple_league_desc',
        'league_with_playoffs' => 'league_with_playoffs_desc',
        'league_with_relegation' => 'league_with_relegation_desc',
        'direct_elimination' => 'direct_elimination_desc',
        'group_stage' => 'group_stage_desc',
        'champions_league' => 'champions_league_desc',
    ];

    public function getAccess($loggato, $level = null)
    {
        if ($loggato && $level == 1) {
            return [
                'reserved' => 'bi bi-shield-lock',
                'profile'  => 'bi bi-person-circle',
                'logout'   => 'bi bi-box-arrow-left',
            ];
        } elseif ($loggato) {
            return [
                'profile'  => 'bi bi-person-circle',
                'logout'   => 'bi bi-box-arrow-left',
            ];
        } else {
            return [
                'login'    => 'bi bi-box-arrow-in-right',
                'register' => 'bi bi-person-plus',
            ];
        }
    }


    public function loadLanguage($langCode = 'it')
    {
        $path = "Language/$langCode.json";

        if (!file_exists($path)) {
            return []; // oppure lancia un errore
        }

        $json = file_get_contents($path);
        return json_decode($json, true);
    }

    public function getAllGroups($teams)
    {
        $allGroups = [];
        foreach ($teams as $t) {
            $p = json_decode($t['params'], true);
            if (!empty($p['groups'])) {
                $allGroups = array_merge($allGroups, $p['groups']);
            }
        }
        $allGroups = array_unique($allGroups);
        sort($allGroups, SORT_STRING);
        return $allGroups;
    }

    public function getModalityParams($mod)
    {
        $params = [];
        switch ($mod) {
            case 'simple_league':
                $params = [
                    'points_for_win' => [
                        'type' => 'number',
                        'default' => 3,
                    ],
                    'points_for_draw' => [
                        'type' => 'number',
                        'default' => 1,
                    ],
                    'points_for_loss' => [
                        'type' => 'number',
                        'default' => 0,
                    ],
                    'rounds' => [
                        'type' => 'number',
                        'default' => 2,
                    ],
                ];
                break;
            case 'league_with_playoffs':
                $params = [
                    'points_for_win' => [
                        'type' => 'number',
                        'default' => 3,
                    ],
                    'points_for_draw' => [
                        'type' => 'number',
                        'default' => 1,
                    ],
                    'points_for_loss' => [
                        'type' => 'number',
                        'default' => 0,
                    ],
                    'rounds' => [
                        'type' => 'number',
                        'default' => 2,
                    ],
                    
                ];
                break;
            // Aggiungi altri casi per le altre modalità
            default:
                $params = [];
                break;
        }
        return $params;
    }
}
