<?php
if ( !function_exists( 'checkNonZeroValues' ) ) {
    function checkNonZeroValues( $values ) {
        foreach ( $values as $value ) {
            if ( $value === 0 || $value === 0.00 || $value === '0' || $value === '0.00' ) {
                return false;
            }
        }
        return true;
    }
}