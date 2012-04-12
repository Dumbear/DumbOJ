<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contests_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //^_^
    public function get_current_or_upcoming_contests() {
        $now = date('Y-m-d H:i:s');
        $this->db->select('contests.id, user_id, username, title, contests.password, start_time, end_time');
        $this->db->from('contests');
        $this->db->join('users', 'contests.user_id = users.id', 'inner');
        $this->db->where('end_time >', $now);
        $query = $this->db->get();
        return $query->result();
    }

    //^_^
    public function get_past_contests($limit, $offset) {
        $now = date('Y-m-d H:i:s');
        $this->db->select('contests.id, user_id, username, title, contests.password, start_time, end_time');
        $this->db->from('contests');
        $this->db->join('users', 'contests.user_id = users.id', 'inner');
        $this->db->where('end_time <=', $now);
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    //^_^
    public function count_past_contests() {
        $now = date('Y-m-d H:i:s');
        $this->db->from('contests');
        $this->db->where('end_time <=', $now);
        return $this->db->count_all_results();
    }

    //^_^
    public function get_contest($id) {
        $this->db->select('contests.id, user_id, username, title, description, contests.password, start_time, end_time, announcement');
        $this->db->from('contests');
        $this->db->join('users', 'contests.user_id = users.id', 'inner');
        $this->db->where('contests.id', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    //^_^
    public function get_problems($id) {
        $this->db->select('problems.id, flag, title, original_site, original_id, original_url, submissions, solutions');
        $this->db->from('contest_problems');
        $this->db->join('problems', 'contest_problems.problem_id = problems.id', 'inner');
        $this->db->where('contest_id', $id);
        $this->db->order_by('flag');
        $query = $this->db->get();
        return $query->result();
    }

    //^_^
    public function get_problem($id, $flag) {
        $this->db->select('problems.id, flag, title, source, time_limit, memory_limit, original_site, original_id, original_url, submissions, solutions');
        $this->db->from('contest_problems');
        $this->db->join('problems', 'contest_problems.problem_id = problems.id', 'inner');
        $this->db->where('contest_id', $id);
        $this->db->where('flag', $flag);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    public function get_problem_by_id($problem_id, $contest_id) {
        $this->db->select('*');
        $this->db->from('contest_problems');
        $this->db->where('problem_id', $problem_id);
        $this->db->where('contest_id', $contest_id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    //^_^
    public function get_submissions($conditions, $limit, $offset) {
        $this->db->select('submissions.id, problem_id, original_site, original_problem_id, contest_id, user_id, username, language, time, memory, result, result_key, LENGTH(`source_code`) AS `length`, is_shared, submission_time');
        $this->db->from('submissions');
        $this->db->join('users', 'submissions.user_id = users.id', 'inner');
        $this->db->where($conditions);
        $this->db->order_by('submissions.id DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    //^_^
    public function count_submissions($conditions) {
        $this->db->from('submissions');
        if (isset($conditions['username'])) {
            $this->db->join('users', 'submissions.user_id = users.id', 'inner');
        }
        $this->db->where($conditions);
        return $this->db->count_all_results();
    }

    //^_^
    public function add_submission($submission, $time_span, $privilege) {
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
            //Update number of submissions of current user
            $this->db->set('submissions', '`submissions` + 1', false);
            $this->db->where('id', $submission['user_id']);
            $this->db->update('users');

            //Update number of submissions of current contest problem
            $this->db->set('submissions', '`submissions` + 1', false);
            $this->db->where('problem_id', $submission['problem_id']);
            $this->db->where('contest_id', $submission['contest_id']);
            $this->db->update('contest_problems');

            //Fetch current contestant's json data
            $json = array();
            $this->db->select('json');
            $this->db->from('contestants');
            $this->db->where('contest_id', $submission['contest_id']);
            $this->db->where('user_id', $submission['user_id']);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $json = json_decode($query->row()->json, true);
            } else {
                //Insert current contestant into `contestants` for the first submission
                $contestant = array(
                    'contest_id' => $submission['contest_id'],
                    'user_id' => $submission['user_id'],
                    'json' => json_encode($json)
                );
                $this->db->insert('contestants', $contestant);
            }

            //Add data to json
            if ($submission_id !== null) {
                $json[$submission_id] = array(
                    'k' => (int)$submission['problem_id'],
                    'r' => $submission['result_key'],
                    't' => $time_span
                );
            }

            //Update json of current contestant
            $this->db->set('json', json_encode($json));
            $this->db->where('contest_id', $submission['contest_id']);
            $this->db->where('user_id', $submission['user_id']);
            $this->db->update('contestants');
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

            //If need to update the number of solutions
            if (((int)$old->result_key === $key) !== ($submission['result_key'] === $key)) {
                //update current user
                $this->db->set(
                    'solutions',
                    "(SELECT COUNT(DISTINCT `problem_id`) FROM `submissions` WHERE `user_id` = {$old->user_id} AND `result_key` = {$key})",
                    false
                );
                $this->db->where('id', $old->user_id);
                $this->db->update('users');

                //Update current contest problem
                $op = ((int)$old->result_key === $key ? '-' : '+');
                $this->db->set('solutions', "`solutions` {$op} 1", false);
                $this->db->where('problem_id', $old->problem_id);
                $this->db->where('contest_id', $old->contest_id);
                $this->db->update('contest_problems');
            }

            //If need to update contestant
            if ($submission['result_key'] !== (int)$old->result_key) {
                //Fetch current contestant's json data for update
                $json = $this->db->query("SELECT `json` FROM `contestants` WHERE `contest_id` = {$old->contest_id} AND `user_id` = {$old->user_id} FOR UPDATE");
                if (!($json->num_rows() > 0)) {
                    log_message('error', "Cannot fetch current contestant's json data: {$id}");
                    $this->db->trans_complete();
                    return false;
                }
                $json = $json->row();
                $json = json_decode($json->json, true);
                if (!isset($json[$id])) {
                    log_message('error', "Wrong current contestant's json data: {$id}");
                    $this->db->trans_complete();
                    return false;
                }

                //Add data to current contestant
                $contestant = array();
                $json[$id]['r'] = $submission['result_key'];
                $contestant['json'] = json_encode($json);
                //If need to update solutions and penalty
                if (((int)$old->result_key === $key) !== ($submission['result_key'] === $key)) {
                    //Calculate solutions and penalty
                    $info = array();
                    ksort($json);
                    foreach ($json as $item) {
                        if ((int)$item['r'] === get_result_key('Accepted')) {
                            if (isset($info[$item['k']])) {
                                if ($info[$item['k']] < 0) {
                                    $info[$item['k']] = (int)$item['t'] - $info[$item['k']];
                                }
                            } else {
                                $info[$item['k']] = (int)$item['t'];
                            }
                        } else {
                            if (isset($info[$item['k']])) {
                                if ($info[$item['k']] < 0) {
                                    $info[$item['k']] -= 1200;
                                }
                            } else {
                                $info[$item['k']] = -1200;
                            }
                        }
                    }
                    $contestant['solutions'] = 0;
                    $contestant['penalty'] = 0;
                    foreach ($info as $item) {
                        if ($item >= 0) {
                            ++$contestant['solutions'];
                            $contestant['penalty'] += $item;
                        }
                    }
                }

                //Update current contestant
                $this->db->where('contest_id', $old->contest_id);
                $this->db->where('user_id', $old->user_id);
                $this->db->update('contestants', $contestant);
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
    public function get_contestants($id, $limit, $offset) {
        $this->db->select('users.id, username, contestants.solutions, penalty, json');
        $this->db->from('contestants');
        $this->db->join('users', 'contestants.user_id = users.id', 'inner');
        $this->db->where('contest_id', $id);
        $this->db->order_by('contestants.solutions DESC, penalty, username');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    //^_^
    public function count_contestants($id) {
        $this->db->from('contestants');
        $this->db->where('contest_id', $id);
        return $this->db->count_all_results();
    }

    //^_^
    public function get_privilege($id) {
        $this->db->select('privilege');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    public function add_contest($contest, $problems) {
        $this->db->trans_start();

        //Insert contest into `contests`
        $this->db->insert('contests', $contest);
        $contest_id = $this->db->insert_id();

        //Insert problems into `contest_problems`
        foreach ($problems as $flag => $item) {
            $problem = array(
                'problem_id' => $item,
                'contest_id' => $contest_id,
                'flag' => chr(ord('A') + $flag)
            );
            $this->db->insert('contest_problems', $problem);
        }

        $this->db->trans_complete();

        return $this->db->trans_status() !== false;
    }
}

/* End of file contests_model.php */
/* Location: ./application/models/contests_model.php */
?>
