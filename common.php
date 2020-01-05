<?php

if ( ! function_exists( 'jlog' ) ) {
    function jlog( $var ) {
        echo '<pre>';
        print_r( $var );
        echo '</pre>';
    }
}
