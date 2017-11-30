<?php
Class LocationController
{
    /**
     * Navigate to new location
     * @param {string} $url
     */
    public static function go( $url ) {
        header( "Location: $url" );
        exit;
    }
}
