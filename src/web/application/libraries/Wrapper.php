<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wrapper {
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    //^_^
    protected function send_request_to_daemon($request) {
        log_message('info', "Wrapper receive request: {$request}");
        for ($i = 3; $i > 0; --$i) {
            //Connect to daemon and send request
            $errno = 0;
            $errstr = '';
            $fp = fsockopen(
                $this->CI->config->item('dumboj_daemon_ip'),
                $this->CI->config->item('dumboj_daemon_port'),
                $errno,
                $errstr,
                $this->CI->config->item('dumboj_timeout')
            );
            if (!$fp) {
                log_message('error', "Wrapper connect to daemon error: {$errno}-{$errstr}");
                continue;
            }
            fwrite($fp, "{$request}\n");
            $result = fgets($fp);
            fclose($fp);

            //Check result
            if (trim($result) !== 'Accepted') {
                log_message('error', "Wrapper receive unknown result from daemon: {$result}");
                continue;
            }
            log_message('info', "Wrapper send request successfully: {$request}");

            return true;
        }
        return false;
    }

    //^_^
    public function add_problem($site, $id_from, $id_to) {
        //Check parameters
        if ($site === 'All' || !in_array($site, get_available_sites())) {
            return false;
        }
        if ($id_from === false || $id_to === false) {
            return false;
        }
        $id_from = preg_replace('/\W/', '', $id_from);
        $id_to = preg_replace('/\W/', '', $id_to);
        if ($id_from === '' && $id_to === '') {
            return false;
        } else if ($id_from === '') {
            $id_from = $id_to;
        } else if ($id_to === '') {
            $id_to = $id_from;
        }
        log_message('info', "Wrapper receive problem: {$site}-{$id_from}:{$id_to}");

        //Send request like "AddProblem POJ 1000 1010"
        return $this->send_request_to_daemon("AddProblem\t{$site}\t${id_from}\t${id_to}");
    }

    //^_^
    public function submit_problem($id) {
        //Fetch submission
        $submission = $this->CI->problems_model->get_submission($id);
        if ($submission === null) {
            log_message('error', "Wrapper receive bad submission: {$id}");
            return false;
        }
        log_message('info', "Wrapper receive submission: {$id}");

        $id = $submission->id;
        $site = $submission->original_site;
        $problem_id = $submission->original_problem_id;
        $language = $submission->language_value;
        $source_code = $submission->source_code;

        //Write source code
        $filename = $this->CI->config->item('dumboj_cache_path') . "/source_code_{$id}";
        $fp = fopen($filename, 'x');
        if (!$fp) {
            //Probably file exists and this submission is being judged right now
            log_message('error', "Wrapper open source code file error: {$id}");
            return true;
        }
        if (fwrite($fp, $source_code) === false) {
            log_message('error', "Wrapper write source code error: {$id}");
            return false;
        }
        fclose($fp);
        log_message('info', "Wrapper write source code successfully: {$id}");

        //Reset submission
        if ($submission->contest_id === null) {
            $this->CI->problems_model->reset_submission($id);
        } else {
            $this->CI->contests_model->reset_submission($id);
        }

        //Send request like "JudgeProblem 1 POJ 1000 0"
        return $this->send_request_to_daemon("JudgeSubmission\t{$id}\t{$site}\t{$problem_id}\t{$language}");
    }
}

/* End of file Wrapper.php */
/* Location: ./application/libraries/Wrapper.php */
?>
