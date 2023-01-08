<?php

namespace App\Helpers;

class MetaData
{
    public $metaData = [];

    public function desc($str = '')
    {
        $this->metaData[] = '<meta name="description" content="' . htmlspecialchars($str, ENT_COMPAT) . '">';
    }

    public function canonical($url = '')
    {
        $this->metaData[] = '<link rel="canonical" href="' . $url . '">';
    }

    public function linkRelNext($url = '')
    {
        $this->metaData[] = '<link rel="next" href="' . $url . '">';
    }

    public function linkRelPrev($url = '')
    {
        $this->metaData[] = '<link rel="prev" href="' . $url . '">';
    }

    public function render()
    {
        if (count($this->metaData) > 0) {
            return implode('', $this->metaData);
        }
    }
}