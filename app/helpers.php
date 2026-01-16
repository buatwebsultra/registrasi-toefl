<?php

if (!function_exists('terbilang')) {
    function terbilang($number)
    {
        $number = (int)$number;
        
        if ($number < 0) {
            return 'minus ' . terbilang(abs($number));
        }
        
        $huruf = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
        
        if ($number < 12) {
            return $huruf[$number];
        } elseif ($number < 20) {
            return $huruf[$number - 10] . ' Belas';
        } elseif ($number < 100) {
            $puluhan = (int)($number / 10);
            $sisa = $number % 10;
            if ($sisa == 0) {
                return $huruf[$puluhan] . ' Puluh';
            } else {
                return $huruf[$puluhan] . ' Puluh ' . terbilang($sisa);
            }
        } elseif ($number < 200) {
            return 'Seratus ' . terbilang($number - 100);
        } elseif ($number < 1000) {
            $ratusan = (int)($number / 100);
            $sisa = $number % 100;
            if ($sisa == 0) {
                return $huruf[$ratusan] . ' Ratus';
            } else {
                return $huruf[$ratusan] . ' Ratus ' . terbilang($sisa);
            }
        } elseif ($number < 2000) {
            return 'Seribu ' . terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $ribuan = (int)($number / 1000);
            $sisa = $number % 1000;
            if ($sisa == 0) {
                return terbilang($ribuan) . ' Ribu';
            } else {
                if ($ribuan == 1) {
                    return 'Seribu ' . terbilang($sisa);
                } else {
                    return terbilang($ribuan) . ' Ribu ' . terbilang($sisa);
                }
            }
        } elseif ($number < 1000000000) {
            $jutaan = (int)($number / 1000000);
            $sisa = $number % 1000000;
            if ($sisa == 0) {
                return terbilang($jutaan) . ' Juta';
            } else {
                return terbilang($jutaan) . ' Juta ' . terbilang($sisa);
            }
        } else {
            return 'Angka terlalu besar';
        }
    }
}