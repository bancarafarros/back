<?php

class Tanggalindo {

    function konversi($tanggal) {
        $bulan = array(1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        if ($tanggal == null || empty($tanggal)) {
            return "0000-00-00";
        } else {
            $split = explode('-', $tanggal);
            if (count($split) > 0) {
                $tanggal = substr($split[2], 0, 2);
                if($split[1]=="00"){
                    return $tanggal . ' ' . $split[1] . ' ' . $split[0];
                }else{
               return $tanggal . ' ' . $bulan[(int) $split[1]] . ' ' . $split[0];
                }
            } else {
                return "0000-00-00";
            }
        }
    }

    function get_tahun($tanggal)
    {
        $thn = explode("-", $tanggal);
        return $thn[0];
    }

    function jam($tanggal) {
        if ($tanggal == null || empty($tanggal)) {
            return "00:00:00";
        } else {
            $split = explode(' ', $tanggal);
            return $split[1];
        }
    }

    function konversi_tgl_jam($tanggal)
    {
        if($tanggal != '0000-00-00 00:00:00' || $tanggal == NULL) {
            $bulan = array(1 => 'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
            $split = explode('-', $tanggal);
            $tanggal = substr($split[2], 0, 2);
            $jam = substr($split[2], 3);
            return $tanggal . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0] . ' ' . $jam;
        } else
            return NULL;
    }

     function get_hari_from_date($date = null) { // yyyy-mm-dd
        $day = date('D', strtotime($date));
        $dayList = array(
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        );
        return $dayList[$day];
    }

}
