<?php
// Path: wp-content/plugins/wp2s/src/Interfaces/CodeEditor/init.php
namespace WP2\Interfaces\CodeEditor;

class Controller
{
    public function __construct()
    {
        add_filter('block_editor_settings_all', [$this, 'restrict_code_editor_for_non_admins']);
    }

    /**
     * Restrict code editor to administrators.
     *
     * @param array $settings The editor settings.
     * @return array Modified settings with code editing disabled for non-admins.
     */
    public function restrict_code_editor_for_non_admins($settings)
    {
        if (!current_user_can('activate_plugins')) {
            $settings['codeEditingEnabled'] = false;
        }
        return $settings;
    }

}

new Controller();


   
