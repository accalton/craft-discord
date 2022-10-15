<?php

namespace imageoptimization\twigextensions;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class TwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('optimize', [$this, 'optimize'])
        );
    }

    public function optimize($filename, $params = [])
    {
        $cloudflareUrl = getenv('AWS_CLOUDFLARE_URL');
        $imageRequest = json_encode([
            'bucket' => getenv('AWS_S3_BUCKET'),
            'key' => 'public/' . $filename,
            'edits' => $params
        ]);

        $url = $cloudflareUrl . '/' . base64_encode($imageRequest);
        
        return $url;
    }
}