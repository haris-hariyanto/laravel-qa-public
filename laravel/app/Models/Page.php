<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use voku\helper\HtmlDomParser;

class Page extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function content()
    {
        // Safelists all used classes in purgecss.config.js

        $content= strip_tags($this->content, '<h2><h3><p><a><br><ul><ol><li>');
        $dom = HtmlDomParser::str_get_html($content);

        $h2s = $dom->find('h2');
        foreach ($h2s as $h2) {
            $h2->setAttribute('class', 'fs-4');
        }

        $h3s = $dom->find('h3');
        foreach ($h3s as $h3) {
            $h3->setAttribute('class', 'fs-5');
        }

        return $dom;
    }
}
