<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Session extends CI_Session {
    public function set_login($login_info) {
        $this->set_userdata(
            array(
                'user_id' => (int)$login_info->id,
                'username' => $login_info->username,
                'share_code' => (int)$login_info->share_code,
                'privilege' => (int)$login_info->privilege
            )
        );
    }

    public function set_logout() {
        $this->unset_userdata(
            array(
                'user_id' => 0,
                'username' => '',
                'share_code' => 0,
                'privilege' => 0
            )
        );
    }
}

/* End of file MY_Session.php */
/* Location: ./application/libraries/MY_Session.php */
?>
