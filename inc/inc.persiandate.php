<?php
class biiq_PersianDate{
    private static $jalali = true;
    private static $convert = true;
    private static $timezone = 'Asia/Tehran';
    private static $temp = array();
    public function __construct($convert = null, $jalali = null, $timezone = null){
        if ($jalali !== null)
            self::$jalali = (bool) $jalali;
        if ($convert !== null)
            self::$convert = (bool) $convert;
        if ($timezone !== null)
            self::$timezone = $timezone;
    }
    public static function date($format, $stamp = false, $jalali = null, $timezone = null){
        $stamp    = ($stamp !== false) ? $stamp : time();
        $timezone = ($timezone != null) ? $timezone : ((self::$timezone != null) ? self::$timezone : date_default_timezone_get());
        $obj      = new DateTime('@' . $stamp, new DateTimeZone($timezone));
        $obj->setTimezone(new DateTimeZone($timezone));
        if ((self::$jalali === false && $jalali === null) || $jalali === false) {
            return $obj->format($format);
        } else {
            $chars  = (preg_match_all('/([a-zA-Z]{1})/', $format, $chars)) ? $chars[0] : array();
            $intact = array(
                'B',
                'h',
                'H',
                'g',
                'G',
                'i',
                's',
                'I',
                'U',
                'u',
                'Z',
                'O',
                'P'
            );
            $intact = self::filterArray($chars, $intact);
            $intactValues = array();
            foreach ($intact as $k => $v) {
                $intactValues[$k] = $obj->format($v);
            }
            list($year, $month, $day) = array(
                $obj->format('Y'),
                $obj->format('n'),
                $obj->format('j')
            );
            list($jyear, $jmonth, $jday) = self::toJalali($year, $month, $day);
            $keys   = array(
                'd',
                'D',
                'j',
                'l',
                'N',
                'S',
                'w',
                'z',
                'W',
                'F',
                'm',
                'M',
                'n',
                't',
                'L',
                'o',
                'Y',
                'y',
                'a',
                'A',
                'c',
                'r',
                'e',
                'T'
            );
            $keys  = self::filterArray($chars, $keys, array(
                'z'
            ));
            $values = array();
            foreach ($keys as $k => $key) {
                $v = '';
                switch ($key) {
                    case 'd':
                        $v = sprintf('%02d', $jday);
                        break;
                    case 'D':
                        $v = self::getDayNames($obj->format('D'), true);
                        break;
                    case 'j':
                        $v = $jday;
                        break;
                    case 'l':
                        $v = self::getDayNames($obj->format('l'));
                        break;
                    case 'N':
                        $v = self::getDayNames($obj->format('l'), false, 1, true);
                        break;
                    case 'S':
                        $v = 'ام';
                        break;
                    case 'w':
                        $v = self::getDayNames($obj->format('l'), false, 1, true) - 1;
                        break;
                    case 'z':
                        if ($jmonth > 6) {
                            $v = 186 + (($jmonth - 6 - 1) * 30) + $jday;
                        } else {
                            $v = (($jmonth - 1) * 31) + $jday;
                        }
                        self::$temp['z'] = $v;
                        break;
                    case 'W':
                        $v = is_int(self::$temp['z'] / 7) ? (self::$temp['z'] / 7) : intval(self::$temp['z'] / 7 + 1);
                        break;
                    case 'F':
                        $v = self::getMonthNames($jmonth);
                        break;
                    case 'm':
                        $v = sprintf('%02d', $jmonth);
                        break;
                    case 'M':
                        $v = self::getMonthNames($jmonth, true);
                        break;
                    case 'n':
                        $v = $jmonth;
                        break;
                    case 't':
                        if ($jmonth >= 1 && $jmonth <= 6)
                            $v = 31;
                        else if ($jmonth >= 7 && $jmonth <= 11)
                            $v = 30;
                        else if ($jmonth == 12 && $jyear % 4 == 3)
                            $v = 30;
                        else if ($jmonth == 12 && $jyear % 4 != 3)
                            $v = 29;
                        break;
                    case 'L':
                        $tmpObj = new DateTime('@' . (time() - 31536000));
                        $v      = $tmpObj->format('L');
                        break;
                    case 'o':
                    case 'Y':
                        $v = $jyear;
                        break;
                    case 'y':
                        $v = $jyear % 100;
                        break;
                    case 'a':
                        $v = ($obj->format('a') == 'am') ? 'ق.ظ' : 'ب.ظ';
                        break;
                    case 'A':
                        $v = ($obj->format('A') == 'AM') ? 'قبل از ظهر' : 'بعد از ظهر';
                        break;
                    case 'c':
                        $v = $jyear . '-' . sprintf('%02d', $jmonth) . '-' . sprintf('%02d', $jday) . 'T';
                        $v .= $obj->format('H') . ':' . $obj->format('i') . ':' . $obj->format('s') . $obj->format('P');
                        break;
                    case 'r':
                        $v = self::getDayNames($obj->format('D'), true) . ', ' . sprintf('%02d', $jday) . ' ' . self::getMonthNames($jmonth, true);
                        $v .= ' ' . $jyear . ' ' . $obj->format('H') . ':' . $obj->format('i') . ':' . $obj->format('s') . ' ' . $obj->format('P');
                        break;
                    case 'e':
                        $v = $obj->format('e');
                        break;
                    case 'T':
                        $v = $obj->format('T');
                        break;
                }
                $values[$k] = $v;
            }
            $keys   = array_merge($intact, $keys);
            $values = array_merge($intactValues, $values);
            return strtr($format, array_combine($keys, $values));
        }
    }
    public static function gDate($format, $stamp = false, $timezone = null){
        return self::date($format, $stamp, false, false, $timezone);
    }
    public static function strftime($format, $stamp = false, $convert = null, $jalali = null, $timezone = null){
        $str_format_code  = array(
            '%a',
            '%A',
            '%d',
            '%e',
            '%j',
            '%u',
            '%w',
            '%U',
            '%V',
            '%W',
            '%b',
            '%B',
            '%h',
            '%m',
            '%C',
            '%g',
            '%G',
            '%y',
            '%Y',
            '%H',
            '%I',
            '%l',
            '%M',
            '%p',
            '%P',
            '%r',
            '%R',
            '%S',
            '%T',
            '%X',
            '%z',
            '%Z',
            '%c',
            '%D',
            '%F',
            '%s',
            '%x',
            '%n',
            '%t',
            '%%'
        );
        $date_format_code = array(
            'D',
            'l',
            'd',
            'j',
            'z',
            'N',
            'w',
            'W',
            'W',
            'W',
            'M',
            'F',
            'M',
            'm',
            'y',
            'y',
            'y',
            'y',
            'Y',
            'H',
            'h',
            'g',
            'i',
            'A',
            'a',
            'h:i:s A',
            'H:i',
            's',
            'H:i:s',
            'h:i:s',
            'H',
            'H',
            'D j M H:i:s',
            'd/m/y',
            'Y-m-d',
            'U',
            'd/m/y',
            '\n',
            '\t',
            '%'
        );
        $format = str_replace($str_format_code, $date_format_code, $format);
        return self::date($format, $stamp, $convert, $jalali, $timezone);
    }
    public static function mktime($hour, $minute, $second, $month, $day, $year, $jalali = null, $timezone = null){
        $month = (intval($month) == 0) ? self::date('m') : $month;
        $day   = (intval($day) == 0) ? self::date('d') : $day;
        $year  = (intval($year) == 0) ? self::date('Y') : $year;
        if ($jalali === true || ($jalali === null && self::$jalali === true)) {
            list($year, $month, $day) = self::toGregorian($year, $month, $day);
        }
        $date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $day) . ' ' . $hour . ':' . $minute . ':' . $second;
        if (self::$timezone != null || $timezone != null) {
            $obj = new DateTime($date, new DateTimeZone(($timezone != null) ? $timezone : self::$timezone));
        } else {
            $obj = new DateTime($date);
        }
        return $obj->format('U');
    }
    public static function checkdate($month, $day, $year, $jalali = null){
        $month = (intval($month) == 0) ? self::date('n') : intval($month);
        $day   = (intval($day) == 0) ? self::date('j') : intval($day);
        $year  = (intval($year) == 0) ? self::date('Y') : intval($year);
        if ($jalali === true || ($jalali === null && self::$jalali === true)) {
            $epoch = self::mktime(0, 0, 0, $month, $day, $year);
            if (self::date('Y-n-j', $epoch, false) == "$year-$month-$day") {
                $ret = true;
            } else {
                $ret = false;
            }
        } else {
            $ret = checkdate($month, $day, $year);
        }
        return $ret;
    }
    private static function filterArray($needle, $heystack, $always = array()){
        return array_intersect(array_merge($needle, $always), $heystack);
    }
    private static function getDayNames($day, $shorten = false, $len = 1, $numeric = false){
        $days = array(
            'sat' => array(
                1,
                'شنبه'
            ),
            'sun' => array(
                2,
                'یکشنبه'
            ),
            'mon' => array(
                3,
                'دوشنبه'
            ),
            'tue' => array(
                4,
                'سه شنبه'
            ),
            'wed' => array(
                5,
                'چهارشنبه'
            ),
            'thu' => array(
                6,
                'پنجشنبه'
            ),
            'fri' => array(
                7,
                'جمعه'
            )
        );
        $day  = substr(strtolower($day), 0, 3);
        $day  = $days[$day];
        return ($numeric) ? $day[0] : (($shorten) ? self::substr($day[1], 0, $len) : $day[1]);
    }
    public static function getMonthNames($month, $shorten = false, $len = 3){
        $months = array(
            'فروردین',
            'اردیبهشت',
            'خرداد',
            'تیر',
            'مرداد',
            'شهریور',
            'مهر',
            'آبان',
            'آذر',
            'دی',
            'بهمن',
            'اسفند'
        );
        $ret   = $months[$month - 1];
        return ($shorten) ? self::substr($ret, 0, $len) : $ret;
    }
    private static function div($a, $b){
        return (int) ($a / $b);
    }
    private static function substr($str, $start, $len){
        if (function_exists('mb_substr')) {
            return mb_substr($str, $start, $len, 'UTF-8');
        } else {
            return substr($str, $start, $len * 2);
        }
    }
    public static function toJalali($g_y, $g_m, $g_d){
        $g_days_in_month = array(
            31,
            28,
            31,
            30,
            31,
            30,
            31,
            31,
            30,
            31,
            30,
            31
        );
        $j_days_in_month = array(
            31,
            31,
            31,
            31,
            31,
            31,
            30,
            30,
            30,
            30,
            30,
            29
        );
        $gy              = $g_y - 1600;
        $gm              = $g_m - 1;
        $gd              = $g_d - 1;
        $g_day_no        = 365 * $gy + self::div($gy + 3, 4) - self::div($gy + 99, 100) + self::div($gy + 399, 400);
        for ($i = 0; $i < $gm; ++$i)
            $g_day_no += $g_days_in_month[$i];
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
            $g_day_no++;
        $g_day_no += $gd;
        $j_day_no = $g_day_no - 79;
        $j_np     = self::div($j_day_no, 12053);
        $j_day_no = $j_day_no % 12053;
        $jy       = 979 + 33 * $j_np + 4 * self::div($j_day_no, 1461);
        $j_day_no %= 1461;
        if ($j_day_no >= 366) {
            $jy += self::div($j_day_no - 1, 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }
        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
            $j_day_no -= $j_days_in_month[$i];
        $jm = $i + 1;
        $jd = $j_day_no + 1;
        return array(
            $jy,
            $jm,
            $jd
        );
    }
    public static function toGregorian($j_y, $j_m, $j_d){
        $g_days_in_month = array(
            31,
            28,
            31,
            30,
            31,
            30,
            31,
            31,
            30,
            31,
            30,
            31
        );
        $j_days_in_month = array(
            31,
            31,
            31,
            31,
            31,
            31,
            30,
            30,
            30,
            30,
            30,
            29
        );
        $j_y = intval($j_y);
        $jy              = $j_y - 979;
        $jm              = $j_m - 1;
        $jd              = $j_d - 1;
        $j_day_no        = 365 * $jy + self::div($jy, 33) * 8 + self::div($jy % 33 + 3, 4);
        for ($i = 0; $i < $jm; ++$i)
            $j_day_no += $j_days_in_month[$i];
        $j_day_no += $jd;
        $g_day_no = $j_day_no + 79;
        $gy       = 1600 + 400 * self::div($g_day_no, 146097);
        $g_day_no = $g_day_no % 146097;
        $leap     = true;
        if ($g_day_no >= 36525) {
            $g_day_no--;
            $gy += 100 * self::div($g_day_no, 36524);
            $g_day_no = $g_day_no % 36524;
            if ($g_day_no >= 365)
                $g_day_no++;
            else
                $leap = false;
        }
        $gy += 4 * self::div($g_day_no, 1461);
        $g_day_no %= 1461;
        if ($g_day_no >= 366) {
            $leap = false;
            $g_day_no--;
            $gy += self::div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }
        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++){
            $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        }
        $gm = $i + 1;
        $gd = $g_day_no + 1;
        return array(
            $gy,
            $gm,
            $gd
        );
    }
    public static function UnixToAgo($unix){
        try{
            $datetime = gmdate("Y-m-d\TH:i:s\Z", $unix);
            return self::ToPersianHumanDateTime($datetime);
        }catch(Exception $er){}
    }
    public static function ToPersianHumanDateTime($datetime, $full = false, $lang = 'fa'){
        try{
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $ago_text_en = 'ago';
            $ago_text_fa = 'پیش';
            if($ago > $now){
                $_ago = $ago;
                $ago = $now;
                $now = $_ago;
                $ago_text_en = 'remaining';
                $ago_text_fa = 'دیگر';
            }
            $diff = $now->diff($ago);

            //$diff->w = floor($diff->d / 7);
            //$diff->d -= floor($diff->d / 7) * 7;
            $string = array(
                'y' => 'سال',
                'm' => 'ماه',
                //'w' => 'هفته',
                'd' => 'روز',
                'h' => 'ساعت',
                'i' => 'دقیقه',
                's' => 'ثانیه',
            );
            if($lang == 'en'){
                $string = array(
                    'y' => 'year',
                    'm' => 'month',
                    //'w' => 'week',
                    'd' => 'day',
                    'h' => 'hour',
                    'i' => 'minutes',
                    's' => 'second',
                );
            }
            foreach($string as $k => &$v){
                if($diff->$k){
                    $v = $diff->$k.' '.$v;
                }else{
                    unset($string[$k]);
                }
            }
            if (!$full) $string = array_slice($string, 0, 1);
            if($lang == 'en'){
                return $string ? implode(', ', $string) . ' '.$ago_text_en : 'moments '.$ago_text_en;
            }
            return $string ? ConvertToPersianNumber(implode(', ', $string) . ' '.$ago_text_fa) : 'اندکی '.$ago_text_fa;
        }catch(Exception $er){
            return 'خطا';
        }
    }
    public static function CurrentYear(){
        $f = new self();
        return $f->date("Y");
    }
    public static function CurrentMonth(){
        $f = new self();
        return $f->date("m");
    }
    public function Today(){
        return $this->date("l j F Y");
    }
    public function today_english_numbers(){
        return $this->date("Y/m/j");
    }
    public static function ToPersianDate($UnixTime){
        return self::date("l j F Y ساعت H:i:s", $UnixTime);
    }
    public function ToPersianDateByFormat($x, $format = "l j F Y ساعت H:i:s"){
        return $this->date($format, strtotime($x));
    }
}
?>