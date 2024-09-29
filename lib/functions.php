<?php

if (!function_exists('capitalize')) {
    function capitalize(string $text) 
    {
        return strtoupper($text[0]) . substr($text, 1);
    }
}