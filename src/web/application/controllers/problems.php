<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Problems extends CI_Controller {
    public $buffer;

    public function __construct() {
        parent::__construct();
        $this->load->model('problems_model');
        $buffer = array();
    }

	public function index($site = 'All', $offset = 0) {
        $data = array();
        $data['current_site'] = rawurldecode(html_entity_decode($site));
        if (!in_array($data['current_site'], get_available_sites())) {
            redirect('/problems');
        }
        $this->load->library('pagination');
        $config = array(
            'base_url' => site_url("problems/index/{$site}"),
            'uri_segment' => 4,
            'total_rows' => $this->problems_model->count_problems($data['current_site']),
            'per_page' => 50,
            'num_links' => 4
        );
        $this->pagination->initialize($config);
        $data['problems'] = $this->problems_model->get_problems($data['current_site'], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->display('problems', $data);
	}

    public function add() {
        $referrer = $this->agent->referrer();

        //If need to login
        if ($this->session->userdata('user_id') === false) {
            $this->session->set_flashdata('referrer', $referrer);
            $this->session->set_flashdata('need_to_login', 'true');
            redirect('/user/login');
        }

        $site = rawurldecode($this->input->post('original_site'));
        $id_from = $this->input->post('original_id_from');
        $id_to = $this->input->post('original_id_to');

        //Add them
        if (!$this->wrapper->add_problem($site, $id_from, $id_to)) {
            $this->session->set_flashdata('message', 'Error encountered, please try again later!');
        } else {
            $this->session->set_flashdata('message', 'Your problems is being added!');
        }
        redirect($referrer);
    }

    public function view($id = null) {
        if ($id === null) {
            show_404();
        }
        $data = array();

        //Fetch problem and content
        $data['problem'] = $this->problems_model->get_problem($id);
        if ($data['problem'] === null) {
            show_404();
        }
        $data['problem_content'] = $this->problems_model->get_problem_content($id);
        if ($data['problem_content'] === null) {
            show_404();
        }

        $this->template->display('view_problem', $data);
    }

    public function submit($id = null) {
        if ($id === null) {
            show_404();
        }
        $data = array();

        //Fetch problem
        $data['problem'] = $this->problems_model->get_problem($id);
        if ($data['problem'] === null) {
            show_404();
        }

        //If need to login
        if ($this->session->userdata('user_id') === false) {
            $this->session->set_flashdata('referrer', current_url());
            $this->session->set_flashdata('need_to_login', 'true');
            redirect('/user/login');
        }

        //Validate form
        $this->load->library('form_validation');
        $this->buffer['languages'] = get_available_languages($data['problem']->original_site);
        if ($this->form_validation->run('submit_problem') === false) {
            $data['languages'] = $this->buffer['languages'];
            $this->template->display('submit_problem', $data);
        } else {
            //Add submission
            $submission = array(
                'problem_id' => $data['problem']->id,
                'original_site' => $data['problem']->original_site,
                'original_problem_id' => $data['problem']->original_id,
                'user_id' => $this->session->userdata('user_id'),
                'language_value' => $this->input->post('language'),
                'result' => 'Queuing',
                'source_code' => $this->input->post('source_code'),
                'is_shared' => ($this->input->post('share_code') === 'true' ? 1 : 0),
                'submission_time' => date('Y-m-d H:i:s')
            );
            $submission_id = $this->problems_model->add_submission($submission, $this->session->userdata('privilege'));
            if ($submission_id === null) {
                $this->session->set_flashdata('message', 'Error encountered, please try again later!');
            } else {
                //Submit it
                if (!$this->wrapper->submit_problem($submission_id)) {
                    $this->problems_model->reset_submission($submission_id, 'DumbJudge Error');
                }
                $this->session->set_flashdata('message', 'Your solution has been submitted!');
            }
            redirect('/problems/status');
        }
    }

    public function status($filter = '::::', $offset = 0) {
        $data = array();

        //Parse filter conditions
        $data['conditions'] = parse_conditions(
            rawurldecode(html_entity_decode($filter)),
            array('original_site', 'original_problem_id', 'username', 'language_key', 'result_key')
        );
        if (isset($data['conditions']['original_site']) && $data['conditions']['original_site'] === 'All') {
            unset($data['conditions']['original_site']);
        }

        $this->load->library('pagination');
        $config = array(
            'base_url' => site_url("problems/status/{$filter}"),
            'uri_segment' => 4,
            'total_rows' => $this->problems_model->count_submissions($data['conditions']),
            'per_page' => 50,
            'num_links' => 4
        );
        $this->pagination->initialize($config);
        $data['submissions'] = $this->problems_model->get_submissions($data['conditions'], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->display('status', $data);
    }

    public function submission($id = null) {
        if ($id === null) {
            show_404();
        }
        $data = array();

        //Fetch submission
        $data['submission'] = $this->problems_model->get_submission($id);
        if ($data['submission'] === null) {
            show_404();
        }

        //If is a contest submission
        if ($data['submission']->contest_id !== null) {
            redirect("/contests/{$data['submission']->contest_id}/submission/{$data['submission']->id}");
        }

        $data['meta_sh'] = true;
        $this->template->display('submission', $data);
    }

    public function resubmit($id = null) {
        $referrer = $this->agent->referrer();
        $submission = $this->problems_model->get_submission($id);
        if ($submission === null) {
            redirect($referrer);
        }
        if ((int)$submission->result_key !== get_result_key('System Error') && !can_admin($this->session->userdata('privilege'))) {
            redirect($referrer);
        }
        $this->load->model('contests_model');
        if (!$this->wrapper->submit_problem($id)) {
            $this->problems_model->reset_submission($id, 'DumbJudge Error');
        }
        redirect($referrer);
    }
}

/* End of file problems.php */
/* Location: ./application/controllers/problems.php */
?>
