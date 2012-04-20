<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//0th bit of privilege
if (!function_exists('can_admin')) {
    function can_admin($privilege) {
        return is_int($privilege) && (($privilege >> 0) & 1) === 1;
    }
}

//1st bit of privilege
if (!function_exists('can_view_code')) {
    function can_view_code($privilege) {
        return is_int($privilege) && (($privilege >> 1) & 1) === 1;
    }
}

//2nd bit of privilege
if (!function_exists('can_hide')) {
    function can_hide($privilege) {
        return is_int($privilege) && (($privilege >> 2) & 1) === 1;
    }
}

if (!function_exists('generate_salt')) {
    function generate_salt($length = 8) {
        $table = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $salt = '';
        for ($i = 0; $i < $length; ++$i) {
            $salt .= $table{mt_rand(0, 35)};
        }
        return $salt;
    }
}

if (!function_exists('get_available_sites')) {
    function get_available_sites() {
        //Could add more sites here.
        return array(
            '' => 'All',
            'http://poj.org' => 'POJ'
        );
    }
}

if (!function_exists('get_available_languages')) {
    function get_available_languages($site) {
        //Could add more languages here.
        switch ($site) {
            case 'POJ':
                return array(
                    0 => 'G++',
                    1 => 'GCC',
                    2 => 'Java',
                    3 => 'Pascal',
                    4 => 'C++',
                    5 => 'C',
                    6 => 'Fortran'
                );
            default:
                return array();
        }
    }
}

if (!function_exists('get_all_results')) {
    function get_all_results() {
        //Could add more results here.
        return array(
            '' => 'All',
            0 => 'Accepted',
            1 => 'Wrong Answer',
            2 => 'Time Limit Exceeded',
            3 => 'Memory Limit Exceeded',
            4 => 'Output Limit Exceeded',
            5 => 'Compile Error',
            6 => 'Presentation Error',
            7 => 'Runtime Error',
            8 => 'System Error'
        );
    }
}

if (!function_exists('get_all_languages')) {
    function get_all_languages() {
        //Could add more languages here.
        return array(
            '' => 'All',
            0 => 'C',
            1 => 'C++',
            2 => 'Pascal',
            3 => 'Java',
            4 => 'Fortran'
        );
    }
}

if (!function_exists('get_result_key')) {
    function get_result_key($result) {
        switch (strtolower(trim($result))) {
            case 'accepted':
                return 0;
            case 'wrong answer':
                return 1;
            case 'time limit exceeded':
                return 2;
            case 'memory limit exceeded':
                return 3;
            case 'output limit exceeded':
                return 4;
            case 'compile error':
                return 5;
            case 'presentation error':
                return 6;
            case 'runtime error':
                return 7;
            case 'system error': case 'dumbjudge error':
                return 8;
            default:
                return -1;
        }
    }
}

if (!function_exists('get_language_key')) {
    function get_language_key($language) {
        switch (strtolower(trim($language))) {
            case 'c': case 'gcc':
                return 0;
            case 'c++': case 'g++':
                return 1;
            case 'pascal':
                return 2;
            case 'java':
                return 3;
            case 'fortran':
                return 4;
            default:
                return -1;
        }
    }
}

if (!function_exists('get_brush')) {
    function get_brush($language) {
        switch ((int)$language) {
            case get_language_key('C'): case get_language_key('C++'):
                return 'brush: cpp';
            case get_language_key('Pascal'):
                return 'brush: pascal';
            case get_language_key('Java'):
                return 'brush: java';
            default:
                return '';
        }
    }
}

if (!function_exists('parse_null')) {
    function parse_null($input) {
        return $input === false || trim($input) === '' ? null : $input;
    }
}

if (!function_exists('nullable_input')) {
    function nullable_input($input) {
        return $input === false || trim($input) === '' ? null : $input;
    }
}

if (!function_exists('parse_conditions')) {
    function parse_conditions($value, $names, $separator = ':') {
        $result = array();
        if (substr_count($value, $separator) !== count($names) - 1) {
            return $result;
        }
        $values = explode($separator, $value);
        for ($i = 0; $i < count($values); ++$i) {
            if ($values[$i] !== '') {
                $result[$names[$i]] = $values[$i];
            }
        }
        return $result;
    }
}

if (!function_exists('parse_conditions2')) {
    function parse_conditions2($names, $values) {
        $result = array();
        for ($i = 0; $i < count($values); ++$i) {
            if ($values[$i] !== '') {
                $result[$names[$i]] = $values[$i];
            }
        }
        return $result;
    }
}

if (!function_exists('get_contest_status')) {
    function get_contest_status($start_time, $end_time, $now = null) {
        if (!($start_time instanceof DateTime)) {
            $start_time = new DateTime($start_time);
        }
        if (!($end_time instanceof DateTime)) {
            $end_time = new DateTime($end_time);
        }
        if ($now === null) {
            $now = new DateTime();
        } else if (!($now instanceof DateTime)) {
            $now = new DateTime($now);
        }
        if ($now < $start_time) {
            return 'Upcoming';
        } else if ($now < $end_time) {
            return 'Running';
        } else {
            return 'Ended';
        }
    }
}

if (!function_exists('get_time_span')) {
    function get_time_span($start_time, $end_time) {
        if (!($start_time instanceof DateTime)) {
            $start_time = new DateTime($start_time);
        }
        if (!($end_time instanceof DateTime)) {
            $end_time = new DateTime($end_time);
        }
        return $end_time->getTimestamp() - $start_time->getTimestamp();
    }
}

/* End of file dumboj_helper.php */
/* Location: ./application/helpers/dumboj_helper.php */
?>
