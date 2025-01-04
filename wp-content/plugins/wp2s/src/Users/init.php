<?php
namespace WP2S\Users;

class Controller {

    protected $user;

    public function __construct($user_id = null) {
        $this->user = $user_id ? get_userdata($user_id) : wp_get_current_user();
    }

    // Display Name
    public function get_user_display_name() {
        return $this->user->display_name ?? __('Guest', 'wp2s');
    }

    // First Name
    public function get_user_first_name() {
        return $this->user->first_name ?? '';
    }

    // Last Name
    public function get_user_last_name() {
        return $this->user->last_name ?? '';
    }

    // Nickname
    public function get_user_nickname() {
        return $this->user->nickname ?? '';
    }

    // Email
    public function get_user_email() {
        return $this->user->user_email ?? '';
    }

    // User Role(s)
    public function get_user_role() {
        return implode(', ', $this->user->roles ?? []);
    }

    // User Bio / Description
    public function get_user_bio() {
        return get_user_meta($this->user->ID, 'description', true) ?? '';
    }

    // User Website
    public function get_user_website() {
        return $this->user->user_url ?? '';
    }

    // User Photo (Avatar)
    public function get_user_photo() {
        return get_avatar_url($this->user->ID);
    }

    // User Registration Date
    public function get_user_registered() {
        return date_i18n(get_option('date_format'), strtotime($this->user->user_registered));
    }

    // User Application Passwords
    public function get_user_application_passwords() {
        return WP_Application_Passwords::get_user_application_passwords($this->user->ID);
    }

    // User Capabilities
    public function get_user_capabilities() {
        return array_keys($this->user->allcaps ?? []);
    }

    // User Handle (Username)
    public function get_user_handle() {
        return $this->user->user_login ?? '';
    }
}

new Controller();