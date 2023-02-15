<?php
defined('BASEPATH') or exit('no access allowed');
function konversi($tanggal)
{
    $bulan = array(
        1 => 'Januari',
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
            if ($split[1] == "00") {
                return $tanggal . ' ' . $split[1] . ' ' . $split[0];
            } else {
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

function jam($tanggal)
{
    if ($tanggal == null || empty($tanggal)) {
        return "00:00:00";
    } else {
        $split = explode(' ', $tanggal);
        return $split[1];
    }
}

function konversi_tgl_jam($tanggal)
{
    if ($tanggal != '0000-00-00 00:00:00' || $tanggal == NULL) {
        $bulan = array(
            1 => 'Januari',
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

function get_hari_from_date($date = null)
{ // yyyy-mm-dd
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

if (!function_exists('convertDateIDNFormat')) {
    function convertDateIDNFormat($date, $delimeters, $replacer)
    {
        $dates = explode($delimeters, $date);
        return $dates[2] . $replacer . $dates[0] . $replacer . $dates[1];
    }
}

if (!function_exists('weekofMonth')) {
    /**
     * weekOfMonth
     * get current week from current day
     * @param  string $date date('d')
     * @return integer
     */
    function weekOfMonth($date = "")
    {
        $dated = ($date != "" ? strtotime(date('Y-' . $date . '-d')) : strtotime(date('Y-m-d')));
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $dated));
        //Apply above formula.
        $data['weekOfYear']     = weekOfYear($dated);
        $data['weekOfMonth']    = weekOfYear($dated) - weekOfYear($firstOfMonth) + 1;

        return $data;
    }
}


function weekOfYear($date = "")
{
    $weekOfYear = intval(date("W", $date));
    if (date('n', $date) == "1" && $weekOfYear > 51) {
        // It's the last week of the previos year.
        return 0;
    } else if (date('n', $date) == "12" && $weekOfYear == 1) {
        // It's the first week of the next year.
        return 53;
    } else {
        // It's a "normal" week.
        return $weekOfYear;
    }
}

function getStartAndEndDate($week = '', $year = null)
{
    $dated = weekOfMonth();
    $weeked = ($week != '' ? $week : $dated['weekOfYear']);

    $dto = new DateTime();
    $dto->setISODate($year, $weeked);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}

// Function to get all the dates in given range
function getDatesFromRange($start = '', $end = '', $format = 'Y-m-d')
{
    $dated = getStartAndEndDate('', date('Y'));
    $started  = ($start != '' ? $start : $dated['week_start']);
    $ended  = ($end != '' ? $end : $dated['week_end']);

    // Declare an empty array
    $array = array();

    // Variable that store the date interval
    // of period 1 day
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($ended);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($started), $interval, $realEnd);

    // Use loop to store date into array
    foreach ($period as $date) {
        $array[] = $date->format($format);
    }

    // Return the array elements
    return $array;
}
