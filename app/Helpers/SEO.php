<?php

namespace App\Helpers;

class SEO {
    private static $pages = [
        'pt-br' => [
            'home' => [
                'title' => 'XXXX',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/',
            ],
            'cliente' => [
                'title' => 'xxxx | Área do Cliente',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/cliente',
            ],
            'institucional' => [
                'title' => 'xxxx | Institucional',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/institucional',
            ],
            'responsabilidade-social' => [
                'title' => 'XXXX | Responsabilidade Social',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/responsabilidade-social',
            ],
            'haroldojacobovicz' => [
                'title' => 'XXXXX | XXXXXX',
                'description' => '',
                'ogimage' => '/xxx_globo.svg',
                'canonical' => '/xxxxxxxx',
            ],
            'contato' => [
                'title' => 'XXXX | Contato',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/contato',
            ],
            'seja-um-parceiro' => [
                'title' => 'XXXX | Seja um Parceiro',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/seja-um-parceiro',
            ],
            'documentoslegais' => [
                'title' => 'xxxx | Documentos Legais',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/documentoslegais',
            ],
            'produtos' => [
                'title' => 'XXXX | ',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/',
            ],
            'produto' => [
                'title' => 'XXXX | ',
                'description' => '',
                'ogimage' => '/xxxx_globo.svg',
                'canonical' => '/',
            ],
        ],
    ];

	public static function get($slug, String $other_slug = null, String $language = 'pt-br') {
        $slug = isset($slug) ? $slug : 'home';
        $language = 'pt-br';
        if (isset($other_slug))
            return self::$pages[$language]['produto'];
        if (isset(self::$pages[$language][$slug]))
            return self::$pages[$language][$slug];
        return self::$pages[$language]['produtos'];
    }
}