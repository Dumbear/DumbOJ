<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('problems_model');
        $this->load->model('contests_model');
    }

    //Add problem and content from daemon
    public function add_problem() {
        if ($this->input->post('key') !== $this->config->item('dumboj_daemon_key')) {
            log_message('error', 'Adding problem error: Wrong daemon key');
            show_404();
        }
        $problem = array(
            'title' => $this->input->post('title'),
            'source' => $this->input->post('source'),
            'time_limit' => nullable_input($this->input->post('time_limit')),
            'memory_limit' => nullable_input($this->input->post('memory_limit')),
            'original_site' => $this->input->post('original_site'),
            'original_id' => $this->input->post('original_id'),
            'original_url' => $this->input->post('original_url'),
            'creation_time' => date('Y-m-d H:i:s')
        );
        $problem_content = array(
            'user_id' => 1,
            'description' => nullable_input($this->input->post('description')),
            'input' => nullable_input($this->input->post('input')),
            'output' => nullable_input($this->input->post('output')),
            'sample_input' => nullable_input($this->input->post('sample_input')),
            'sample_output' => nullable_input($this->input->post('sample_output')),
            'hint' => nullable_input($this->input->post('hint')),
            'remark' => $this->input->post('remark'),
            'creation_time' => date('Y-m-d H:i:s')
        );
        foreach ($problem as $field) {
            if ($field === false) {
                log_message('error', 'Adding problem error: Uncompleted problem');
                return;
            }
        }
        foreach ($problem_content as $field) {
            if ($field === false) {
                log_message('error', 'Adding problem error: Uncompleted problem content');
                return;
            }
        }

        $key = $this->problems_model->insert_or_update_problem($problem);
        if ($key === null) {
            log_message('error', "Adding problem error: {$problem['original_site']} - {$problem['original_id']}");
            return;
        }
        $problem_content['problem_id'] = $key->id;
        $key = $this->problems_model->insert_or_update_problem_content($problem_content);
        if ($key === null) {
            log_message('error', "Adding problem content error: {$problem_content['problem_id']}");
            return;
        }

        log_message('info', "Add problem successfully: {$problem_content['problem_id']}");
        echo 'Accepted';
    }

    //Update submission from daemon
    public function update_submission() {
        if ($this->input->post('key') !== $this->config->item('dumboj_daemon_key')) {
            log_message('error', 'Updating submission error: Wrong daemon key');
            show_404();
        }
        $id = $this->input->post('id');
        $submission = array(
            'original_id' => nullable_input($this->input->post('original_id')),
            'time' => nullable_input($this->input->post('time')),
            'memory' => nullable_input($this->input->post('memory')),
            'result' => $this->input->post('result'),
            'additional_info' => nullable_input($this->input->post('additional_info'))
        );
        if ($id === false || $submission['result'] === false) {
            log_message('error', 'Updating submission error: Uncompleted submission');
            return;
        }

        //Fetch old submission
        $old = $this->problems_model->get_submission($id);
        if ($old === null) {
            log_message('error', "Updating submission error: Unknown submission {$id}");
            return;
        }

        //Update submission for problem or contest problem
        if ($old->contest_id === null) {
            if (!$this->problems_model->update_submission($id, $submission)) {
                log_message('error', "Updating submission error: {$id}");
                return;
            }
        } else {
            if (!$this->contests_model->update_submission($id, $submission)) {
                log_message('error', "Updating submission error: {$id}");
                return;
            }
        }

        log_message('info', "Update submission successfully: {$id}");
        echo 'Accepted';
    }
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */
?>
