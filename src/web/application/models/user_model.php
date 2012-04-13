<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_login_info($username) {
        $this->db->select('id, username, password, share_code, privilege');
        $this->db->from('users');
        $this->db->where('username', $username);
        $this->db->where('enabled', 1);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    public function register($user) {
        $user['registration_time'] = date('Y-m-d H:i:s');
        if ($this->db->insert('users', $user) === true) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function get_profile($username) {
        $this->db->select('id, username, real_name, school, email, share_email, share_code, privilege, submissions, solutions, registration_time, enabled');
        $this->db->from('users');
        $this->db->where('username', $username);
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->row() : null;
    }

    public function get_rank($submissions, $solutions, $username) {
        $this->db->from('users');
        $this->db->where('enabled', 1);
        $this->db->where('solutions >', $solutions);
        $this->db->or_where('enabled', 1);
        $this->db->where('solutions', $solutions);
        $this->db->where('submissions <', $submissions);
        $this->db->or_where('enabled', 1);
        $this->db->where('solutions', $solutions);
        $this->db->where('submissions', $submissions);
        $this->db->where('username <', $username);
        return $this->db->count_all_results() + 1;
    }

    public function get_distinct_solutions($user_id) {
        $this->db->select('problem_id, original_site, original_problem_id');
        $this->db->distinct();
        $this->db->from('submissions');
        $this->db->where('user_id', $user_id);
        $this->db->where('result_key', get_result_key('Accepted'));
        $this->db->order_by('original_site, original_problem_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function search_users($conditions, $limit = 100) {
        $where = '`enabled` = 1';
        if (isset($conditions['name'])) {
            $name = $this->db->escape_like_str($conditions['name']);
            $where .= " AND (`username` LIKE '%{$name}%' OR `real_name` LIKE '%{$name}%')";
        }
        if (isset($conditions['school'])) {
            $school = $this->db->escape_like_str($conditions['school']);
            $where .= " AND `school` LIKE '%{$school}%'";
        }
        $this->db->select('id, username, real_name, school, email, share_email, submissions, solutions');
        $this->db->from('users');
        $this->db->where($where);
        $this->db->order_by('solutions DESC, submissions, username');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_ranklist($limit, $offset) {
        $this->db->select('id, username, real_name, school, submissions, solutions');
        $this->db->from('users');
        $this->db->where('enabled', 1);
        $this->db->order_by('solutions DESC, submissions, username');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_users() {
        $this->db->from('users');
        $this->db->where('enabled', 1);
        return $this->db->count_all_results();
    }
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */
?>
