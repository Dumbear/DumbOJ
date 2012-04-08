<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Template {
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function display($view, $data = array(), $return = false) {
        $data['content'] = $this->CI->load->view($view, $data, true);
        return $this->CI->load->view('template', $data, $return);
    }

    public function display_contest($view, $data = array(), $return = false) {
        $data['content'] = $this->CI->load->view($view, $data, true);
        $data['content'] = $this->CI->load->view('contest_template', $data, true);
        return $this->CI->load->view('template', $data, $return);
    }
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */
?>
