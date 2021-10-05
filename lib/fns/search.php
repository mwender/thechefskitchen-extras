<?php

add_filter( 'gettext', function( $text ) {
    if ( 'Search Results for: %s' === $text ) {
        $text = '%s';
    }

    return $text;
} );