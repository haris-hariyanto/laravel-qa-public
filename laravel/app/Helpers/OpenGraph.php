<?php

namespace App\Helpers;

class OpenGraph
{
    public $openGraph = [];
    public $title;
    public $description;
    public $url;
    public $fbImage;
    public $fbImageData;
    public $type;
    public $locale;
    public $siteName;
    public $twitterCard;
    public $twitterSite;
    public $twitterCreator;
    public $twitterImage;
    public $twitterImageAlt;

    public function __construct()
    {
        $this->title = false;
        $this->description = false;
        $this->url = false;
        $this->fbImage = false;
        $this->fbImageData = [];
        $this->type = 'website';
        $this->locale = config('app.og_locale');
        $this->siteName = config('app.name');
        $this->twitterCard = 'summary';
        $this->twitterSite = config('app.app_twitter');
        $this->twitterCreator = false;
        $this->twitterImage = false;
        $this->twitterImageAlt = false;
    }

    public function url($url = '')
    {
        $this->url = $url;
    }

    public function type($type = '')
    {
        $this->type = $type;
    }

    public function title($title = '')
    {
        $this->title = $title;
    }

    public function desc($desc = '')
    {
        $this->description = $desc;
    }

    public function locale($locale = '')
    {
        $this->locale = $locale;
    }

    public function siteName($siteName = '')
    {
        $this->siteName = $siteName;
    }

    /**
     * $fbImageData = [
     *  'type' => 'image/jpeg', // Optional
     *  'width' => 1200, // Optional
     *  'height' => 630, // Optional
     *  'alt' => 'What is in the image', // Optional
     * ]
     */
    public function fbImage($fbImage, $fbImageData = [])
    {
        $this->fbImage = $fbImage;
        $this->fbImageData = $fbImageData;
    }

    public function twitterCardSummary()
    {
        $this->twitterCard = 'summary';
    }

    public function twitterCardSummaryLarge()
    {
        $this->twitterCard = 'summary_large_image';
    }

    public function twitterSite($account = false) {
        $this->twitterSite = $account;
    }

    public function twitterCreator($account = false)
    {
        $this->twitterCreator = $account;
    }

    /**
     * Twitter Card Summary Large
     * - Ratio 2:1
     * - Minimum 300 * 157
     * 
     * Twitter Card Summary
     * - Ratio 1:1
     * - Minimum 144 * 144
     */
    public function twitterImage($twitterImage, $twitterImageAlt = false)
    {
        $this->twitterImage = $twitterImage;
        $this->twitterImageAlt = $twitterImageAlt;
    }

    public function render()
    {
        if ($this->title) {
            $this->openGraph[] = '<meta property="og:title" content="' . htmlspecialchars($this->title, ENT_COMPAT) . '">';
        }
        if ($this->description) {
            $this->openGraph[] = '<meta property="og:description" content="' . htmlspecialchars($this->description, ENT_COMPAT) . '">';
        }
        if ($this->url) {
            $this->openGraph[] = '<meta property="og:url" content="' . $this->url . '">';
        }
        if ($this->fbImage) {
            $this->openGraph[] = '<meta property="og:image" content="' . $this->fbImage . '">';
            $this->openGraph[] = '<meta property="og:image:secure_url" content="' . $this->fbImage . '">';

            if (!empty($this->fbImageData['type'])) {
                $this->openGraph[] = '<meta property="og:image:type" content="' . $this->fbImageData['type'] . '">';
            }
    
            if (!empty($this->fbImageData['width'])) {
                $this->openGraph[] = '<meta property="og:image:width" content="' . $this->fbImageData['width'] . '">';
            }
    
            if (!empty($this->fbImageData['height'])) {
                $this->openGraph[] = '<meta property="og:image:height" content="' . $this->fbImageData['height'] . '">';
            }
    
            if (!empty($this->fbImageData['alt'])) {
                $this->openGraph[] = '<meta property="og:image:alt" content="' . $this->fbImageData['alt'] . '">';
            }
        }

        $this->openGraph[] = '<meta property="og:type" content="' . $this->type . '">';
        $this->openGraph[] = '<meta property="og:locale" content="' . $this->locale . '">';
        $this->openGraph[] = '<meta property="og:site_name" content="' . $this->siteName . '">';

        if ($this->twitterCard) {
            $this->openGraph[] = '<meta name="twitter:card" content="' . $this->twitterCard . '">';
        }
        if ($this->twitterSite) {
            $this->openGraph[] = '<meta name="twitter:site" content="' . $this->twitterSite . '">';
        }
        if ($this->twitterCreator) {
            $this->openGraph[] = '<meta name="twitter:creator" content="' . $this->twitterCreator . '">';
        }
        if ($this->title) {
            $this->openGraph[] = '<meta name="twitter:title" content="' . htmlspecialchars($this->title, ENT_COMPAT) . '">';
        }
        if ($this->description) {
            $this->openGraph[] = '<meta name="twitter:description" content="' . htmlspecialchars($this->description, ENT_COMPAT) . '">';
        }
        if ($this->twitterImage) {
            $this->openGraph[] = '<meta name="twitter:image" content="' . $this->twitterImage . '">';

            if ($this->twitterImageAlt) {
                $this->openGraph[] = '<meta name="twitter:image:alt" content="' . $this->twitterImageAlt . '">';
            }
        }

        if (count($this->openGraph) > 0) {
            return implode('', $this->openGraph);
        }
    }
}