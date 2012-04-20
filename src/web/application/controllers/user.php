<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('user_model');
    }

	public function login() {
        if ($this->session->userdata('user_id') !== false) {
            redirect('/home');
        }
        $referrer = $this->session->flashdata('referrer');
        if ($referrer === false) {
            $referrer = $this->agent->referrer();
        }
        $this->load->library('form_validation');
        if ($this->form_validation->run('login') === false) {
            $data = array();
            $data['salt'] = generate_salt();
            $this->session->set_userdata('salt', $data['salt']);
            $this->session->set_flashdata('referrer', $referrer);
            $this->template->display('login', $data);
        } else {
            $this->session->set_flashdata('message', 'Welcome back, ' . $this->session->userdata('username') . '!');
            redirect($referrer);
        }
	}

    public function logout() {
        if ($this->session->userdata('user_id') === false) {
            redirect('/home');
        }
        $referrer = $this->session->flashdata('referrer');
        if ($referrer === false) {
            $referrer = $this->agent->referrer();
        }
        $this->session->set_flashdata('message', 'See you later, ' . $this->session->userdata('username') . '!');
        $this->session->set_logout();
        redirect($referrer);
    }

    public function register() {
        if ($this->session->userdata('user_id') !== false) {
            redirect('/home');
        }
        $this->load->library('form_validation');
        if ($this->form_validation->run('register') === false) {
            $this->template->display('register');
        } else {
            $this->session->set_flashdata('message', 'You have successfully registered! You can login now!');
            redirect('/user/login');
        }
    }

    public function profile($username = null) {
        if ($username === null) {
            if ($this->session->userdata('user_id') === false) {
                $this->session->set_flashdata('referrer', current_url());
                $this->session->set_flashdata('need_to_login', 'true');
                redirect('/user/login');
            }
            $username = $this->session->userdata('username');
        }
        $data = array();
        $data['profile'] = $this->user_model->get_profile($username);
        if ($data['profile'] === null) {
            show_404();
        }
        $data['is_self'] = ((int)$data['profile']->id === $this->session->userdata('user_id'));
        if ((int)$data['profile']->enabled === 0) {
            $data['rank'] = 'N/A';
        } else {
            $data['rank'] = $this->user_model->get_rank(
                $data['profile']->submissions,
                $data['profile']->solutions,
                $data['profile']->username
            );
        }
        $data['solutions'] = $this->user_model->get_distinct_solutions($data['profile']->id);
        $this->template->display('profile', $data);
    }

    public function search($filter) {
        $data = array();

        //Parse filter conditions
        $data['conditions'] = parse_conditions(rawurldecode(html_entity_decode($filter)), array('name', 'school'));

        //Fetch matched users
        $data['users'] = $this->user_model->search_users($data['conditions']);

        $this->template->display('search_users', $data);
    }

    public function ranklist($offset = 0) {
        $this->load->library('pagination');
        $config = array(
            'base_url' => site_url("user/ranklist"),
            'uri_segment' => 3,
            'total_rows' => $this->user_model->count_users(),
            'per_page' => 50,
            'num_links' => 4
        );
        $this->pagination->initialize($config);
        $data = array();
        $data['offset'] = $offset;
        $data['ranklist'] = $this->user_model->get_ranklist($config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->display('ranklist', $data);
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */
?>
