<?php

namespace WP2\REST\Info;

use WP_Error;
use WP_REST_Response;
use DirectoryIterator;

class Controller
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'handle_admin_tasks']);
        add_action('rest_api_init', [$this, 'register_routes']);
        add_action('wp_footer', [$this, 'handle_footer_tasks']);

        add_filter('blockstudio/settings/users/roles', function () {
            return ["administrator"];
        });
    }

    /**
     * Register REST API Endpoints
     */
    public function register_routes()
    {
        register_rest_route('newsplicity/v1', '/template', [
            'methods' => 'GET',
            'callback' => [$this, 'get_template_data'],
            'permission_callback' => [$this, 'check_permissions'],
        ]);
    }

    /**
     * Handle Admin Tasks
     */
    public function handle_admin_tasks()
    {
        $this->check_blockstudio();
        $this->process_plugin_directories();
    }

    /**
     * Handle Footer Tasks
     */
    public function handle_footer_tasks()
    {
        $this->write_template_data();
        $this->create_readme_files();
    }

    /**
     * Check if Blockstudio is Defined
     */
    private function check_blockstudio()
    {
        if (!defined("BLOCKSTUDIO")) {
            add_action('admin_notices', function () {
                ?>
                <div class="notice notice-error is-dismissible">
                    <p>Blockstudio is not defined.</p>
                </div>
                <?php
            });
        }
    }

    /**
     * Process Plugin Directories and Initialize Blockstudio Build
     */
    private function process_plugin_directories()
    {
        if (defined("BLOCKSTUDIO")) {
            $plugin_dir = plugin_dir_path(__FILE__);

            $folders = new DirectoryIterator($plugin_dir);

            foreach ($folders as $folder) {
                if ($folder->isDir() && !$folder->isDot()) {
                    Blockstudio\Build::init([
                        'dir' => $plugin_dir . '/' . $folder->getFilename(),
                    ]);
                }
            }
        }
    }

    /**
     * REST API Permission Callback
     */
    public function check_permissions($request)
    {
        $authorization = $request->get_header('Authorization');

        if (!$authorization) {
            return false;
        }

        $token = explode(' ', $authorization)[1] ?? '';
        $decoded = base64_decode($token);

        return $decoded === rwmb_meta('sync_token', ['object_type' => 'setting'], 'instawp-template');
    }

    /**
     * Fetch Template Data for REST API
     */
    public function get_template_data()
    {
        $template_data = [
            'block_types' => $this->get_template_block_types(),
            'plugins'     => $this->get_template_plugin_data(),
            'tools'       => $this->get_template_tools(),
            'themes'      => $this->get_template_theme_data(),
        ];

        return new WP_REST_Response(json_encode($template_data, JSON_PRETTY_PRINT), 200);
    }

    /**
     * Write Template Data to JSON File
     */
    public function write_template_data()
    {
        $template_data = $this->get_template_data();

        $plugin_dir = plugin_dir_path(__FILE__) . '/template.json';

        file_put_contents($plugin_dir, $template_data->data);
    }

    /**
     * Create Readme Files in Folders
     */
    public function create_readme_files()
    {
        $directories = [
            'experiences' => WP_CONTENT_DIR . '/plugins/newsplicity/experiences',
            'vendors'     => WP_CONTENT_DIR . '/plugins/newsplicity/vendors',
            'tools'       => WP_CONTENT_DIR . '/plugins/newsplicity/tools',
        ];

        foreach ($directories as $directory) {
            $this->create_folder_readmes($directory);
        }
    }

    /**
     * Create README Files if Missing
     */
    private function create_folder_readmes($directory)
    {
        if (!is_dir($directory)) {
            return;
        }

        $folders = array_filter(scandir($directory), function ($folder) {
            return $folder !== '.' && $folder !== '..';
        });

        foreach ($folders as $folder) {
            $path = $directory . '/' . $folder . '/README.md';

            if (!file_exists($path)) {
                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0777, true);
                }
                file_put_contents($path, "#");
            }
        }
    }

    /**
     * Fetch Installed Block Types
     */
    private function get_template_block_types()
    {
        $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

        return array_map(function ($block_type) {
            return [
                'title'        => $block_type->title,
                'name'         => $block_type->name,
                'category'     => $block_type->category,
                'api_version'  => $block_type->api_version,
            ];
        }, $block_types);
    }

    /**
     * Fetch Active Plugins
     */
    private function get_template_plugin_data()
    {
        $all_plugins = get_plugins();
        $active_plugins = array_filter($all_plugins, function ($plugin_path) {
            return is_plugin_active($plugin_path);
        }, ARRAY_FILTER_USE_KEY);

        return array_values($active_plugins);
    }

    /**
     * Fetch Tools
     */
    private function get_template_tools()
    {
        $dir = WP_CONTENT_DIR . '/plugins/newsplicity/tools';
        return $this->scan_directory_for_readme($dir);
    }

    /**
     * Fetch Theme Data
     */
    private function get_template_theme_data()
    {
        $theme = wp_get_theme();
        return [['name' => $theme->get('TextDomain')]];
    }

    /**
     * Scan Directory for README Files
     */
    private function scan_directory_for_readme($dir)
    {
        if (!is_dir($dir)) {
            return [];
        }

        $folders = array_filter(scandir($dir), function ($folder) {
            return $folder !== '.' && $folder !== '..';
        });

        return array_map(function ($folder) use ($dir) {
            $readme_path = $dir . '/' . $folder . '/README.md';
            $content = file_exists($readme_path) ? file_get_contents($readme_path) : '';
            return ['title' => $folder, 'content' => $content];
        }, $folders);
    }
}

// Initialize the controller
new Controller();