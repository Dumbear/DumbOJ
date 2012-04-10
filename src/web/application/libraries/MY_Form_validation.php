<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
    public function check_login() {
        $session_salt = $this->CI->session->userdata('salt');
        $this->CI->session->unset_userdata('salt');
        if (count($this->_error_array) !== 0) {
            return true;
        }
        $username = trim($this->CI->input->post('username'));
        $salt = trim($this->CI->input->post('salt'));
        $key = trim($this->CI->input->post('key'));
        if ($session_salt !== $salt) {
            $this->set_message('check_login', 'Invalid login, please try again later.');
            log_message('error', 'Salt is not valid, suspicious request');
            return false;
        }
        $login_info = $this->CI->user_model->get_login_info($username);
        if ($login_info === null) {
            $this->set_message('check_login', 'Invalid Username or Password.');
            return false;
        }
        if ($key !== md5($login_info->password . $salt)) {
            $this->set_message('check_login', 'Invalid Username or Password.');
            return false;
        }
        $this->CI->session->set_login($login_info);
        return true;
    }

    public function check_register() {
        if (count($this->_error_array) !== 0) {
            return true;
        }
        $user = array(
            'username' => trim($this->CI->input->post('username')),
            'password' => trim($this->CI->input->post('key')),
            'real_name' => trim($this->CI->input->post('real_name')),
            'school' => trim($this->CI->input->post('school')),
            'email' => trim($this->CI->input->post('email')),
            'share_email' => ($this->CI->input->post('share_email') === 'true' ? 1 : 0),
            'share_code' => ($this->CI->input->post('share_code') === 'true' ? 1 : 0)
        );
        if ($this->CI->user_model->register($user) === false) {
            $this->set_message('check_register', 'Invalid register, please try again later.');
            return false;
        } else {
            return true;
        }
    }

    public function check_submit_language($language) {
        if (!array_key_exists($language, $this->CI->buffer['languages'])) {
            $this->set_message('check_submit_language', 'Invalid submit, please try again later.');
            return false;
        } else {
            return true;
        }
    }

    public function check_contest_submit_flag($flag) {
        if ($this->CI->buffer['flag'] !== $flag) {
            $this->set_message('check_contest_submit_flag', 'Invalid submit, please try again later.');
            return false;
        } else {
            return true;
        }
    }

    public function check_contest_submit_language($language) {
        if (!array_key_exists($language, $this->CI->buffer['languages'])) {
            $this->set_message('check_contest_submit_language', 'Invalid submit, please try again later.');
            return false;
        } else {
            return true;
        }
    }

    public function valid_date($date) {
        $date = parse_conditions($date, array('year', 'month', 'day'), '-');
        if (isset($date['year']) && isset($date['month']) && isset($date['day'])) {
            if (checkdate($date['month'], $date['day'], $date['year'])) {
                return true;
            }
        }
        $this->set_message('valid_date', 'The %s field must contain a valid date');
        return false;
    }

    public function check_add_contest() {
        if (count($this->_error_array) !== 0) {
            return true;
        }

        //Check length of sites and ids
        $sites = $this->CI->input->post('sites');
        $ids = $this->CI->input->post('ids');
        if (count($sites) !== count($ids)) {
            $this->set_message('check_add_contest', 'Invalid contest, please try again later.');
            return false;
        }

        //Check length of problems
        if (count($sites) <= 0 || count($sites) > 26) {
            $this->set_message('check_add_contest', 'The number of problems must be between 1 to 26');
            return false;
        }

        //Check existence of problems
        $problems = array();
        for ($i = 0; $i < count($sites); ++$i) {
            $sites[$i] = trim($sites[$i]);
            $ids[$i] = trim($ids[$i]);
            $key = $this->CI->problems_model->get_problem_key($sites[$i], $ids[$i]);
            if ($key === null) {
                $this->set_message('check_add_contest', 'All the problems must exist');
                return false;
            }
            $problems[$i] = $key->id;
        }

        //Check duplicated problems
        for ($i = 0; $i < count($problems); ++$i) {
            for ($j = $i + 1; $j < count($problems); ++$j) {
                if ($problems[$i] === $problems[$j]) {
                    $this->set_message('check_add_contest', 'All the problems must be distinct');
                    return false;
                }
            }
        }

        //Check start time
        $now = new DateTime();
        $start_time = sprintf(
            '%s %s:%s:00',
            $this->CI->input->post('start_time_d'),
            $this->CI->input->post('start_time_h'),
            $this->CI->input->post('start_time_i')
        );
        $start_time = new DateTime($start_time);
        if ($start_time <= $now) {
            $this->set_message('check_add_contest', 'The contest must start later than right now');
            return false;
        }
        if (get_time_span($now, $start_time) >= 30 * 24 * 60 * 60) {
            $this->set_message('check_add_contest', 'The contest must start within 30 days');
            return false;
        }

        //Check duration
        $duration = 0;
        if ($this->CI->input->post('duration_d') !== false) {
            $duration += (int)$this->CI->input->post('duration_d');
        }
        $duration *= 24;
        if ($this->CI->input->post('duration_h') !== false) {
            $duration += (int)$this->CI->input->post('duration_h');
        }
        $duration *= 60;
        if ($this->CI->input->post('duration_i') !== false) {
            $duration += (int)$this->CI->input->post('duration_i');
        }
        $duration *= 60;
        if ($duration <= 0) {
            $this->set_message('check_add_contest', 'The contest duration cannot be 0');
            return false;
        }
        if ($duration >= 30 * 24 * 60 * 60) {
            $this->set_message('check_add_contest', 'The contest duration must be shorter than 30 days');
            return false;
        }
        $end_time = new DateTime();
        $end_time->setTimestamp($start_time->getTimeStamp() + $duration);

        //Add contest and problems
        $contest = array(
            'user_id' => $this->CI->session->userdata('user_id'),
            'title' => trim($this->CI->input->post('title')),
            'description' => nullable_input(trim($this->CI->input->post('description'))),
            'password' => nullable_input($this->CI->input->post('password')),
            'start_time' => $start_time->format('Y-m-d H:i:s'),
            'end_time' => $end_time->format('Y-m-d H:i:s'),
            'announcement' => nullable_input(trim($this->CI->input->post('announcement')))
        );
        if ($contest['password'] !== null) {
            $contest['password'] = trim($this->CI->input->post('key'));
        }
        if (!$this->CI->contests_model->add_contest($contest, $problems)) {
            $this->set_message('check_add_contest', 'Invalid contest, please try again later.');
            return false;
        }
        return true;
    }
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
?>
