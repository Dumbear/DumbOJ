<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('problems_model');
        $this->load->model('contests_model');
    }

    //Add a problem and content
    public function add_problem() {
        //TODO check if it is sent by daemon
        $problem = array(
            'title' => $this->input->post('title'),
            'source' => $this->input->post('source'),
            'time_limit' => parse_null($this->input->post('time_limit')),
            'memory_limit' => parse_null($this->input->post('memory_limit')),
            'original_site' => $this->input->post('original_site'),
            'original_id' => $this->input->post('original_id'),
            'original_url' => $this->input->post('original_url'),
            'creation_time' => date('Y-m-d H:i:s')
        );
        $key = $this->problems_model->insert_or_update_problem($problem);
        if ($key === null) {
            log_message('error', "Adding problem error: {$problem['original_site']} - {$problem['original_id']}");
            return;
        }
        $problem_content = array(
            'problem_id' => $key->id,
            'user_id' => 1,
            'description' => parse_null($this->input->post('description')),
            'input' => parse_null($this->input->post('input')),
            'output' => parse_null($this->input->post('output')),
            'sample_input' => parse_null($this->input->post('sample_input')),
            'sample_output' => parse_null($this->input->post('sample_output')),
            'hint' => parse_null($this->input->post('hint')),
            'remark' => $this->input->post('remark'),
            'creation_time' => date('Y-m-d H:i:s')
        );
        $key = $this->problems_model->insert_or_update_problem_content($problem_content);
        if ($key === null) {
            log_message('error', "Adding problem content error: {$problem_content['problem_id']}");
            return;
        }
        echo 'Accepted';
    }

    //^_^
    public function update_submission() {
        //TODO check if it is sent by daemon
        $id = $this->input->post('id');
        $submission = array(
            'original_id' => nullable_input($this->input->post('original_id')),
            'time' => nullable_input($this->input->post('time')),
            'memory' => nullable_input($this->input->post('memory')),
            'result' => $this->input->post('result'),
            'additional_info' => nullable_input($this->input->post('additional_info'))
        );

        //Fetch old submission
        $old = $this->problems_model->get_submission($id);
        if ($old === null) {
            log_message('error', "Admin received unknown updating submission: {$id}");
            return;
        }
        log_message('info', "Admin received updating submission: {$id}");

        //Update submission for problem or contest problem
        if ($old->contest_id === null) {
            if (!$this->problems_model->update_submission($id, $submission)) {
                log_message('error', "Admin updating submission error: {$id}");
                return;
            }
        } else {
            if (!$this->contests_model->update_submission($id, $submission)) {
                log_message('error', "Admin updating submission error: {$id}");
                return;
            }
        }

        log_message('info', "Admin updated submission successfully: {$id}");
        echo 'Accepted';
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
?>
