<?php
namespace Gugusd999\Web;

class Currency {
    
    public static function rp($number=0, $decimals = 2) {
        // Format number to currency with Indonesian format
        $formatted = number_format($number, $decimals, ',', '.');
        return 'Rp ' . $formatted;
    }
    
    public static function dollar($number=0, $decimals = 2) {
        // Format number to currency with Indonesian format
        $formatted = number_format($number, $decimals, ',', '.');
        return '$ ' . $formatted;
    }
    
}
