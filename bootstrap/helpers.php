<?php

function normalizeUrl($url)
{
    $urlParts = parse_url($url);
    return "{$urlParts['scheme']}://{$urlParts['host']}";
}