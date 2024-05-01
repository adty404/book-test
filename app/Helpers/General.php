<?php

// @TODO implement DONE:
if (!function_exists('usd_to_rupiah_format')) {
    function usd_to_rupiah_format($usd)
    {
        $prefix = 'Rp ';
        if ($usd === null || $usd == 0) {
            return $prefix . '0,00';
        }
        return $prefix . number_format($usd * 14000, 2, ',', '.');
    }
}
