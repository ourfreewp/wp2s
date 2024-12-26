<?php

$login_form = wp_login_form([
    'echo'              => false,
    'label_username'    => __('Username'),
    'label_password'    => __('Password'),
    'label_remember'    => __('Remember'),
    'label_log_in'      => __('Sign In'),
    'redirect'          => '',
    'form_id'           => '',
    'id_username'       => '',
    'id_password'       => '',
    'id_remember'       => '',
    'id_submit'         => '',
    'value_username'    => '',
    'remember'          => true,
    'value_remember'    => false,
    'required_username' => false,
    'required_password' => false,
]);

?>


<?php echo $login_form; ?>