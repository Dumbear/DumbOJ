<?php

$config = array(
    'login' => array(
        array(
            'field' => 'username',
            'label' => '"Username"',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'salt',
            'label' => '"Salt"',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'key',
            'label' => '"Password"',
            'rules' => 'trim|required|check_login'
        )
    ),
    'register' => array(
        array(
            'field' => 'username',
            'label' => '"Username"',
            'rules' => 'trim|required|min_length[3]|max_length[32]|alpha_dash|is_unique[users.username]'
        ),
        array(
            'field' => 'password',
            'label' => '"Password"',
            'rules' => 'required|min_length[6]|max_length[32]'
        ),
        array(
            'field' => 'confirm_password',
            'label' => '"Confirm password"',
            'rules' => 'required|matches[password]'
        ),
        array(
            'field' => 'real_name',
            'label' => '"Real name"',
            'rules' => 'trim|max_length[64]'
        ),
        array(
            'field' => 'school',
            'label' => '"School"',
            'rules' => 'trim|max_length[64]'
        ),
        array(
            'field' => 'email',
            'label' => '"Email"',
            'rules' => 'trim|max_length[64]|valid_email'
        ),
        array(
            'field' => 'share_email',
            'label' => '"Share email"',
            'rules' => 'trim'
        ),
        array(
            'field' => 'share_code',
            'label' => '"Share code"',
            'rules' => 'trim'
        ),
        array(
            'field' => 'key',
            'label' => '"Key"',
            'rules' => 'trim|required|check_register'
        )
    ),
    'submit_problem' => array(
        array(
            'field' => 'language',
            'label' => '"Language"',
            'rules' => 'required|check_submit_language'
        ),
        array(
            'field' => 'share_code',
            'label' => '"Share code"',
            'rules' => 'trim'
        ),
        array(
            'field' => 'source_code',
            'label' => '"Source code"',
            'rules' => 'required|min_length[32]|max_length[65535]'
        )
    ),
    'submit_contest_problem' => array(
        array(
            'field' => 'flag',
            'label' => '"Problem"',
            'rules' => 'required|check_contest_submit_flag'
        ),
        array(
            'field' => 'language',
            'label' => '"Language"',
            'rules' => 'required|check_contest_submit_language'
        ),
        array(
            'field' => 'share_code',
            'label' => '"Share code"',
            'rules' => 'trim'
        ),
        array(
            'field' => 'source_code',
            'label' => '"Source code"',
            'rules' => 'required|min_length[32]|max_length[65535]'
        )
    )
);

?>
