<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Problems_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //^_^
    public function get_problems($site, $limit, $offset) {
        $this->db->select('id, title, source, original_site, original_id, original_url, creation_time');
        $this->db->from('problems');

        //If the site is specified
        if ($site !== '' && $site !== 'All') {
            $this->db->where('original_site', $site);
        }

        $this->db->order_by('original_site, original_id');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    //^_^
    public function count_problems($site) {
        $this->db->from('problems');

        //If the site is specified
        if ($site !== '' && $site !== 'All') {
            $this->db->where('original_site', $site);
        }

        return $this->db->count_all_results();
    }

    //^_^
    public function get_problem($id) {
        $this->db->select('*');
        $this->db->from('problems');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    //^_^
    public function get_problem_content($problem_id, $user_id = 1) {
        $this->db->select('*');
        $this->db->from('problem_contents');
        $this->db->where('problem_id', $problem_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    //^_^
    public function get_problem_key($original_site, $original_id) {
        $this->db->select('id');
        $this->db->from('problems');
        $this->db->where('original_site', $original_site);
        $this->db->where('original_id', $original_id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    //^_^
    public function get_problem_content_key($problem_id, $user_id) {
        $this->db->select('problem_id, user_id');
        $this->db->from('problem_contents');
        $this->db->where('problem_id', $problem_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    //^_^
    public function insert_or_update_problem($problem) {
        $key = $this->get_problem_key($problem['original_site'], $problem['original_id']);
        if ($key === null) {
            //Insert it
            $this->db->insert('problems', $problem);
            return $this->get_problem_key($problem['original_site'], $problem['original_id']);
        } else {
            //Update it
            $this->db->where('id', $key->id);
            $this->db->update('problems', $problem);
            return $key;
        }
    }

    //^_^
    public function insert_or_update_problem_content($problem_content) {
        $key = $this->get_problem_content_key($problem_content['problem_id'], $problem_content['user_id']);
        if ($key === null) {
            //Insert it
            $this->db->insert('problem_contents', $problem_content);
            return $this->get_problem_content_key($problem_content['problem_id'], $problem_content['user_id']);
        } else {
            //Update it
            $this->db->where('problem_id', $key->problem_id);
            $this->db->where('user_id', $key->user_id);
            $this->db->update('problem_contents', $problem_content);
            return $key;
        }
    }

    //^_^
    public function get_submissions($conditions, $limit, $offset) {
        $now = date('Y-m-d H:i:s');
        $this->db->select('submissions.id, problem_id, original_site, original_problem_id, contest_id, submissions.user_id, username, language, time, memory, result, result_key, LENGTH(`source_code`) AS `length`, is_shared, submission_time');
        $this->db->from('submissions');
        $this->db->join('contests', 'submissions.contest_id = contests.id', 'left');
        $this->db->join('users', 'submissions.user_id = users.id', 'inner');
        $this->db->where($conditions);
        $this->db->where("`contests`.`password` IS NULL AND (`contest_id` IS NULL OR `end_time` <= '{$now}')");
        $this->db->order_by('submissions.id DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    //^_^
    public function count_submissions($conditions) {
        $now = date('Y-m-d H:i:s');
        $this->db->from('submissions');
        $this->db->join('contests', 'submissions.contest_id = contests.id', 'left');
        if (isset($conditions['username'])) {
            $this->db->join('users', 'submissions.user_id = users.id', 'inner');
        }
        $this->db->where($conditions);
        $this->db->where("`contests`.`password` IS NULL AND (`contest_id` IS NULL OR `end_time` <= '{$now}')");
        return $this->db->count_all_results();
    }

    //^_^
    public function get_submission($id) {
        $this->db->select('submissions.*, LENGTH(`source_code`) AS `length`, username');
        $this->db->from('submissions');
        $this->db->join('users', 'submissions.user_id = users.id', 'inner');
        $this->db->where('submissions.id', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    //^_^
    public function add_submission($submission, $privilege) {
        $languages = get_available_languages($submission['original_site']);
        $submission['language'] = $languages[$submission['language_value']];
        $submission['language_key'] = get_language_key($submission['language']);
        $submission['result_key'] = get_result_key($submission['result']);

        $this->db->trans_start();

        //Insert submission into `submissions`
        $submission_id = null;
        if ($this->db->insert('submissions', $submission) === true) {
            $submission_id = $this->db->insert_id();
        }

        if (!can_hide($privilege)) {
            //Update the number of submissions of current user
            $this->db->set('submissions', '`submissions` + 1', false);
            $this->db->where('id', $submission['user_id']);
            $this->db->update('users');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return null;
        } else {
            return $submission_id;
        }
    }

    //^_^
    public function update_submission($id, $submission) {
        $id = (int)$id;
        $submission['result_key'] = get_result_key($submission['result']);

        $this->db->trans_start();

        //Fetch old submission for update
        $old = $this->db->query("SELECT * FROM `submissions` WHERE `id` = {$id} FOR UPDATE");
        if (!($old->num_rows() > 0)) {
            log_message('error', "Cannot fetch submission for update: {$id}");
            $this->db->trans_complete();
            return false;
        }
        $old = $old->row();

        //Update submission
        $this->db->where('id', $id);
        $this->db->update('submissions', $submission);

        //Fetch privilege of current user
        $privilege = $this->get_privilege($old->user_id);
        if ($privilege === null) {
            log_message('error', "Cannot fetch current user's privilege to update submission: {$id}");
            $this->db->trans_complete();
            return false;
        }
        $privilege = (int)$privilege->privilege;

        //If should update current user
        if (!can_hide($privilege)) {
            $key = get_result_key('Accepted');

            //If need to update the number of solutions of current user
            if (((int)$old->result_key === $key) !== ($submission['result_key'] === $key)) {
                $this->db->set(
                    'solutions',
                    "(SELECT COUNT(DISTINCT `problem_id`) FROM `submissions` WHERE `user_id` = {$old->user_id} AND `result_key` = {$key})",
                    false
                );
                $this->db->where('id', $old->user_id);
                $this->db->update('users');
            }
        }

        $this->db->trans_complete();

        return $this->db->trans_status() !== false;
    }

    //^_^
    public function reset_submission($id, $result = 'Queuing') {
        $submission = array(
            'original_id' => null,
            'time' => null,
            'memory' => null,
            'result' => $result,
            'additional_info' => null
        );
        if (!$this->update_submission($id, $submission)) {
            log_message('error', "Reset submission error: {$id}");
        }
    }

    //^_^
    public function get_privilege($id) {
        $this->db->select('privilege');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }
}

/* End of file problems_model.php */
/* Location: ./application/models/problems_model.php */
?>
