<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('problems_model');
        $this->load->model('contests_model');
        $buffer = array();
    }

	public function index() {
        $data = array();
        $data['title'] = "DumbOJ - A Virtual Online Judge System";

        $data['now'] = new DateTime();
        $data['contests'] = $this->contests_model->get_current_contests();
        $data['problems'] = $this->problems_model->get_recent_problems(10);

        $this->template->display('home', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
?>
