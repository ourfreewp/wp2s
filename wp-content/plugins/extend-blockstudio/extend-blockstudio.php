<?php
// Path: wp-content/plugins/extend-blockstudio/extend-blockstudio.php
/**
 * Plugin Name: Extend — Blockstudio
 * Description: A utility plugin for extending Blockstudio plugin.
 * Version: 1.0
 * Author: Vinny S. Green
 **/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

define('EXT_BLOCKSTUDIO_NAMESPACE', 'ext_blockstudio');
define('EXT_BLOCKSTUDIO_PREFIX', 'ext_blockstudio_');
define('EXT_BLOCKSTUDIO', 'extend-blockstudio');

define('EXT_BLOCKSTUDIO_PATH', plugin_dir_path(__FILE__));
define('EXT_BLOCKSTUDIO_URL', plugin_dir_url(__FILE__));

class Extend_Blockstudio {

    private $textdomain = EXT_BLOCKSTUDIO;
    private $prefix     = EXT_BLOCKSTUDIO_PREFIX;
    private $studio_dirs    = [];

    public function __construct() {
        add_action('init', [$this, 'init_studios']);
        add_filter('blockstudio/settings/users/ids', [$this, 'filter_user_ids']);
    }

    public function init_studios() {
        if (!defined('BLOCKSTUDIO')) {
            return;
        }
    
        $this->load_studio_directories();
        $this->initialize_studios();
    }
    
    private function load_studio_directories() {
        $theme_dirs    = $this->get_theme_directories();
        $declared_dirs = $this->get_declared_directories();
    
        $this->studio_dirs = array_merge($theme_dirs, $declared_dirs);
    }
    
    private function initialize_studios() {
        foreach ($this->studio_dirs as $studio_dir) {
            $path = EXT_BLOCKSTUDIO_PATH . $studio_dir;
            
            if (is_dir($path)) {
                Blockstudio\Build::init([
                    'dir' => $path,
                ]);
            }
        }
    }

    public function get_declared_directories() {
        return [];
    }

    public function get_theme_directories() {
        $themes = $this->get_blockstudio_themes();
        return $this->extract_theme_directories($themes);
    }
    
    private function get_blockstudio_themes(): array {
        $all_themes = wp_get_themes();
    
        return array_filter($all_themes, function ($theme) {
            $tags = $theme->get('Tags');
            return is_array($tags) && in_array('blockstudio', $tags, true);
        });
    }
    
    private function extract_theme_directories(array $themes): array {
        $theme_dirs = [];
    
        foreach ($themes as $theme) {
            $theme_dir = $theme->get_stylesheet_directory();
            if (is_dir($theme_dir)) {
                $theme_dirs[] = $theme_dir;
            }
        }
    
        return $theme_dirs;
    }

    public function filter_user_ids($user_ids) {
        return array_merge($user_ids, defined('WP2_BLOCKSTUDIO_USERS') ? WP2_BLOCKSTUDIO_USERS : []);
    }

}

// Initialize the plugin
new Extend_Blockstudio();