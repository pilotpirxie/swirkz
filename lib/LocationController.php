<?php
Class LocationController
{
    public static function go($url) {
        header("Location: $url");
        exit;
    }
}
