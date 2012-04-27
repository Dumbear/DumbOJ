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
            'rules' => 'trim|required|min_length[3]|max_length[30]|alpha_dash|is_unique[users.username]'
        ),
        array(
            'field' => 'password',
            'label' => '"Password"',
            'rules' => 'required|min_length[6]|max_length[30]'
        ),
        array(
            'field' => 'confirm_password',
            'label' => '"Confirm password"',
            'rules' => 'required|matches[password]'
        ),
        array(
            'field' => 'real_name',
            'label' => '"Real name"',
            'rules' => 'trim|max_length[120]'
        ),
        array(
            'field' => 'school',
            'label' => '"School"',
            'rules' => 'trim|max_length[120]'
        ),
        array(
            'field' => 'email',
            'label' => '"Email"',
            'rules' => 'trim|max_length[120]|valid_email'
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
            'rules' => 'trim|required|exact_length[32]|check_register'
        )
    ),
    'update_profile' => array(
        array(
            'field' => 'old_password',
            'label' => '"Old password"',
            'rules' => 'required'
        ),
        array(
            'field' => 'new_password',
            'label' => '"New password"',
            'rules' => 'min_length[6]|max_length[30]|matches[confirm_password]'
        ),
        array(
            'field' => 'confirm_password',
            'label' => '"Confirm password"',
            'rules' => 'matches[new_password]'
        ),
        array(
            'field' => 'real_name',
            'label' => '"Real name"',
            'rules' => 'trim|max_length[120]'
        ),
        array(
            'field' => 'school',
            'label' => '"School"',
            'rules' => 'trim|max_length[120]'
        ),
        array(
            'field' => 'email',
            'label' => '"Email"',
            'rules' => 'trim|max_length[120]|valid_email'
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
            'field' => 'salt',
            'label' => '"Salt"',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'old_key',
            'label' => '"Old key"',
            'rules' => 'trim|required|exact_length[32]|check_update_profile'
        ),
        array(
            'field' => 'new_key',
            'label' => '"New key"',
            'rules' => 'trim|exact_length[32]'
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
            'rules' => 'required|min_length[32]|max_length[65530]'
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
            'rules' => 'required|min_length[32]|max_length[65530]'
        )
    ),
    //TODO
    'add_contest' => array(
        array(
            'field' => 'title',
            'label' => '"Title"',
            'rules' => 'trim|required|max_length[120]'
        ),
        array(
            'field' => 'start_time_d',
            'label' => '"Start date"',
            'rules' => 'trim|required|valid_date'
        ),
        array(
            'field' => 'start_time_h',
            'label' => '"Start hour"',
            'rules' => 'trim|required|is_natural|less_than[24]'
        ),
        array(
            'field' => 'start_time_i',
            'label' => '"Start minute"',
            'rules' => 'trim|required|is_natural|less_than[60]'
        ),
        array(
            'field' => 'duration_d',
            'label' => '"Duration days"',
            'rules' => 'trim|is_natural|less_than[30]'
        ),
        array(
            'field' => 'duration_h',
            'label' => '"Duration hours"',
            'rules' => 'trim|is_natural|less_than[24]'
        ),
        array(
            'field' => 'duration_i',
            'label' => '"Duration minutes"',
            'rules' => 'trim|is_natural|less_than[60]'
        ),
        array(
            'field' => 'password',
            'label' => '"Password"',
            'rules' => 'max_length[32]'
        ),
        array(
            'field' => 'description',
            'label' => '"Description"',
            'rules' => 'trim|max_length[65530]'
        ),
        array(
            'field' => 'announcement',
            'label' => '"Announcement"',
            'rules' => 'trim|max_length[65530]'
        ),
        array(
            'field' => 'sites[]',
            'label' => '"Problem sites"',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'ids[]',
            'label' => '"Problem IDs"',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'key',
            'label' => '"Key"',
            'rules' => 'trim|required|exact_length[32]|check_add_contest'
        )
    )
);

?>
