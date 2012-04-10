<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contests extends CI_Controller {
    public $buffer;

    public function __construct() {
        parent::__construct();
        $this->load->model('contests_model');
        $this->load->model('problems_model');
        $buffer = array();
    }

    //^_^
    public function index() {
        $data = array();
        $data['contests'] = $this->contests_model->get_current_or_upcoming_contests();
        $this->template->display('contests', $data);
    }

    //^_^
    public function past($offset = 0) {
        $this->load->library('pagination');
        $config = array(
            'base_url' => site_url('contests/past'),
            'uri_segment' => 3,
            'total_rows' => $this->contests_model->count_past_contests(),
            'per_page' => 50,
            'num_links' => 4
        );
        $this->pagination->initialize($config);
        $data = array();
        $data['contests'] = $this->contests_model->get_past_contests($config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        $this->template->display('past_contests', $data);
    }

    //^_^
    public function view($id = null) {
        if ($id === null) {
            show_404();
        }
        $data = array();

        //Fetch contest
        $data['contest'] = $this->contests_model->get_contest($id);
        if ($data['contest'] === null) {
            show_404();
        }

        $data['need_password'] = $this->need_password($data['contest']);
        $data['problems'] = $this->contests_model->get_problems($data['contest']->id);
        $data['current_time'] = new DateTime();
        $data['status'] = get_contest_status(
            $data['contest']->start_time,
            $data['contest']->end_time,
            $data['current_time']
        );
        $data['module'] = 'Overview';
        $this->template->display_contest('view_contest', $data);
    }

    //^_^
    public function view_problem($id = null, $flag = null) {
        if ($id === null || $flag === null) {
            show_404();
        }
        $data = array();

        //Fetch contest
        $data['contest'] = $this->contests_model->get_contest($id);
        if ($data['contest'] === null) {
            show_404();
        }

        //If need password
        if ($this->need_password($data['contest'])) {
            redirect("/contests/{$data['contest']->id}");
        }

        //Check contest status
        $data['status'] = get_contest_status($data['contest']->start_time, $data['contest']->end_time);
        if ($data['status'] === 'Upcoming') {
            $this->session->set_flashdata('message', 'This contest has not started yet!');
            redirect("/contests/{$data['contest']->id}");
        }

        //Fetch problem and content
        $data['problem'] = $this->contests_model->get_problem($id, $flag);
        if ($data['problem'] === null) {
            show_404();
        }
        $data['problem_content'] = $this->problems_model->get_problem_content($data['problem']->id);
        if ($data['problem_content'] === null) {
            show_404();
        }

        $data['problems'] = $this->contests_model->get_problems($id);
        $data['module'] = 'Problems';
        $this->template->display_contest('view_contest_problem', $data);
    }

    //^_^
    public function submit_problem($id = null, $flag = null) {
        if ($id === null || $flag === null) {
            show_404();
        }
        $data = array();

        //Fetch contest
        $data['contest'] = $this->contests_model->get_contest($id);
        if ($data['contest'] === null) {
            show_404();
        }

        //If need password
        if ($this->need_password($data['contest'])) {
            redirect("/contests/{$data['contest']->id}");
        }

        //Fetch problem
        $data['problem'] = $this->contests_model->get_problem($id, $flag);
        if ($data['problem'] === null) {
            show_404();
        }

        //Check contest status
        $data['now'] = new DateTime();
        $data['status'] = get_contest_status($data['contest']->start_time, $data['contest']->end_time, $data['now']);
        if ($data['status'] === 'Upcoming') {
            $this->session->set_flashdata('message', 'This contest has not started yet!');
            redirect("/contests/{$data['contest']->id}");
        }
        if ($data['status'] === 'Ended') {
            $this->session->set_flashdata('message', 'This contest has already ended!');
            redirect("/problems/view/{$data['problem']->id}");
        }

        //If need to login
        if ($this->session->userdata('user_id') === false) {
            $this->session->set_flashdata('referrer', current_url());
            $this->session->set_flashdata('need_to_login', 'true');
            redirect('/user/login');
        }

        //Validate form
        $this->load->library('form_validation');
        $this->buffer['flag'] = $data['problem']->flag;
        $this->buffer['languages'] = get_available_languages($data['problem']->original_site);
        if ($this->form_validation->run('submit_contest_problem') === false) {
            $data['problems'] = $this->contests_model->get_problems($id);
            $data['languages'] = $this->buffer['languages'];
            $data['module'] = 'Submit';
            $this->template->display_contest('submit_contest_problem', $data);
        } else {
            //Add submission
            $submission = array(
                'problem_id' => $data['problem']->id,
                'original_site' => $data['problem']->original_site,
                'original_problem_id' => $data['problem']->original_id,
                'contest_id' => $data['contest']->id,
                'user_id' => $this->session->userdata('user_id'),
                'language_value' => $this->input->post('language'),
                'result' => 'Queuing',
                'source_code' => $this->input->post('source_code'),
                'is_shared' => ($this->input->post('share_code') === 'true' ? 1 : 0),
                'submission_time' => $data['now']->format('Y-m-d H:i:s')
            );
            $submission_id = $this->contests_model->add_submission(
                $submission,
                get_time_span($data['contest']->start_time, $data['now']),
                $this->session->userdata('privilege')
            );
            if ($submission_id === null) {
                $this->session->set_flashdata('message', 'Error encountered, please try again later!');
            } else {
                //Submit it
                if (!$this->wrapper->submit_problem($submission_id)) {
                    $this->contests_model->reset_submission($submission_id, 'DumbJudge Error');
                }
                $this->session->set_flashdata('message', 'Your solution has been submitted!');
            }
            redirect("/contests/{$data['contest']->id}/status");
        }
    }

    //^_^
    public function status($id = null, $filter = ':::', $offset = 0) {
        if ($id === null) {
            show_404();
        }
        $data = array();

        //Fetch contest
        $data['contest'] = $this->contests_model->get_contest($id);
        if ($data['contest'] === null) {
            show_404();
        }

        //If need password
        if ($this->need_password($data['contest'])) {
            redirect("/contests/{$data['contest']->id}");
        }

        //Check contest status
        $data['status'] = get_contest_status($data['contest']->start_time, $data['contest']->end_time);
        if ($data['status'] === 'Upcoming') {
            $this->session->set_flashdata('message', 'This contest has not started yet!');
            redirect("/contests/{$data['contest']->id}");
        }

        //Fetch problems
        $data['problems'] = $this->contests_model->get_problems($data['contest']->id);

        //Parse filter conditions
        $data['conditions'] = parse_conditions(
            rawurldecode(html_entity_decode($filter)),
            array('problem_id', 'username', 'language_key', 'result_key')
        );
        $data['conditions']['contest_id'] = $data['contest']->id;

        $this->load->library('pagination');
        $config = array(
            'base_url' => site_url("contests/{$data['contest']->id}/status/{$filter}"),
            'uri_segment' => 5,
            'total_rows' => $this->contests_model->count_submissions($data['conditions']),
            'per_page' => 50,
            'num_links' => 4
        );
        $this->pagination->initialize($config);
        $data['submissions'] = $this->contests_model->get_submissions($data['conditions'], $config['per_page'], $offset);
        $data['pagination'] = $this->pagination->create_links();
        $data['module'] = 'Status';
        $this->template->display_contest('contest_status', $data);
    }

    //^_^
    public function standings($id = null, $offset = 0) {
        if ($id === null) {
            show_404();
        }
        $data = array();

        //Fetch contest
        $data['contest'] = $this->contests_model->get_contest($id);
        if ($data['contest'] === null) {
            show_404();
        }

        //If need password
        if ($this->need_password($data['contest'])) {
            redirect("/contests/{$data['contest']->id}");
        }

        //Check contest status
        $data['status'] = get_contest_status($data['contest']->start_time, $data['contest']->end_time);
        if ($data['status'] === 'Upcoming') {
            $this->session->set_flashdata('message', 'This contest has not started yet!');
            redirect("/contests/{$data['contest']->id}");
        }

        //Fetch problems
        $data['problems'] = $this->contests_model->get_problems($data['contest']->id);

        $this->load->library('pagination');
        $config = array(
            'base_url' => site_url("contests/{$data['contest']->id}/standings"),
            'uri_segment' => 4,
            'total_rows' => $this->contests_model->count_contestants($id),
            'per_page' => 50,
            'num_links' => 4
        );
        $this->pagination->initialize($config);
        $data['contestants'] = $this->contests_model->get_contestants($id, $config['per_page'], $offset);
        $data['offset'] = $offset;
        $data['pagination'] = $this->pagination->create_links();
        $data['module'] = 'Standings';
        $this->template->display_contest('contest_standings', $data);
    }

    //TODO
    public function add() {
        //If need to login
        if ($this->session->userdata('user_id') === false) {
            $this->session->set_flashdata('referrer', current_url());
            $this->session->set_flashdata('need_to_login', 'true');
            redirect('/user/login');
        }

        $data = array();
        $data['count'] = $this->input->post('sites') === false ? 0 : count($this->input->post('sites'));

        //Validate form
        $this->load->library('form_validation');
        if ($this->form_validation->run('add_contest') === false) {
            $this->template->display('add_contest', $data);
        } else {
            redirect('/contests');
        }
    }

    //^_^
    private function need_password($contest) {
        if ($contest->password === null) {
            return false;
        } else if ($this->session->userdata('user_id') === (int)$contest->user_id) {
            return false;
        } else if (can_admin($this->session->userdata('privilege'))) {
            return false;
        } else if ($this->session->userdata('contest_id') === (int)$contest->id) {
            return false;
        } else if ($this->input->post('password') === $contest->password) {
            $this->session->set_userdata('contest_id', (int)$contest->id);
            return false;
        }
        return true;
    }
}

/* End of file contests.php */
/* Location: ./application/controllers/contests.php */
?>
