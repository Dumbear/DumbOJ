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
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
?>
