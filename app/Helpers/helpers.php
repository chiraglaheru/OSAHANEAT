<?php

if (!function_exists('currency')) {
    function currency($amount) {
        return '₹' . number_format($amount, 2);
    }
}
