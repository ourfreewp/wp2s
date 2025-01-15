<?php
// Path: init.php

namespace WP2S\Manifests;

use DirectoryIterator;
use WP_Filesystem;

class Controller
{
    private $text_domain = 'wp2s';
    private $definitions_dir = WP2S_PLUGIN_DIR . 'src/Types/definitions/';
    private $dir = WP2S_PLUGIN_DIR . 'src/Manifests/';
    private $post_type = 'wp2s_manifest';

    public function __construct()
    {
        add_action('init', [$this, 'initialize_directories'], 10);
        add_action('load-edit.php', [$this, 'conditional_sync'], 999);
    }

    /**
     * Initialize directories based on JSON definitions.
     */
    public function initialize_directories()
    {
        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        $definition_files = glob($this->definitions_dir . '*.json');

        foreach ($definition_files as $definition_file) {
            $this->process_definition($definition_file);
        }
    }

    /**
     * Process a single JSON definition file to create a directory and files.
     *
     * @param string $file Path to the definition JSON file.
     */
    private function process_definition(string $file)
    {
        global $wp_filesystem;

        $content = file_get_contents($file);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || empty($data['singular'])) {
            error_log("Invalid JSON or missing 'singular' key in file: $file");
            return;
        }

        $singular = preg_replace('/\s+/', '', $data['singular']); // Remove whitespace
        $directory = $this->dir . $singular;

        if (!is_dir($directory)) {
            if (!$wp_filesystem->mkdir($directory, FS_CHMOD_DIR)) {
                error_log("Failed to create directory: $directory");
                return;
            }
        }

        // Create block.json
        $this->create_block_json($directory, $singular);

        // Create index.php
        $this->create_index_php($directory, $singular);
    }


    /**
     * Sync blocks and ensure posts exist when on the item list screen.
     */
    public function conditional_sync()
    {
        if ($this->is_list_screen()) {
            $this->initialize_directories();
            $this->sync_blocks();
        }
    }

    /**
     * Check if the current screen is the item list screen.
     *
     * @return bool
     */
    private function is_list_screen(): bool
    {
        $post_type = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_STRING);
        return $post_type === $this->post_type;
    }

    /**
     * Loop through directories and ensure block.json files exist and are valid.
     */
    private function sync_blocks()
    {
        if (!is_dir($this->dir)) {
            return;
        }

        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }

        $iterator = new DirectoryIterator($this->dir);

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                $childDir = $fileInfo->getPathname();
                $blockJsonPath = $childDir . '/block.json';
                $indexPhpPath = $childDir . '/index.php';

                // $fileInfo->getFilename() is PascalCase and we need it in kebab-case for multiple words
                $blockName = preg_replace('/(?<!^)[A-Z]/', '-$0', $fileInfo->getFilename());
                $blockName = strtolower($blockName);

                // Handle block.json creation/validation
                if (!file_exists($blockJsonPath)) {
                    $this->create_block_json($childDir, $blockName);
                } else {
                    $this->validate_block_json($blockJsonPath);
                }

                // Handle index.php creation
                if (!file_exists($indexPhpPath)) {
                    $this->create_index_php($childDir, $blockName);
                }
            }
        }
    }

    /**
     * Create a block.json file using the WordPress Filesystem API.
     *
     * @param string $directory Path to the child directory.
     * @param string $fileName  Name of the directory (used for block name).
     */
    private function create_block_json(string $directory, string $fileName)
    {
        global $wp_filesystem;

        $blockName = strtolower($fileName);
        $blockJson = [
            '$schema'      => 'https://app.blockstudio.dev/schema',
            'name'         => 'wp2s/' . $blockName . '-manifest',
            'title'        => ucfirst($fileName),
            'icon'         => 'embed-generic',
            'description'  => '',
            'supports'     => [
                'className'        => false,
                'customClassName'  => true,
                'align'            => ['full', 'wide'],
                'renaming'         => true,
                'color'            => [
                    'background' => true,
                    'text'       => true,
                ],
            ],
            'blockstudio'  => true,
        ];

        $category = $this->get_manifest_category($blockName);
        if ($category && $category !== 'uncategorized') {
            $blockJson['category'] = $category;
        }

        if ($category !== 'uncategorized') {
            $blockJson['category'] = $category;
        }

        $jsonContent = wp_json_encode($blockJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $blockJsonPath = trailingslashit($directory) . 'block.json';

        if (!$wp_filesystem->put_contents($blockJsonPath, $jsonContent, FS_CHMOD_FILE)) {
            error_log("Failed to create block.json in directory: $directory");
        }
    }
    /**
     * Get the manifest category for a given block name.
     *
     * @param string $block_name The block name.
     * @return string The category for the block.
     */
    private function get_manifest_category($block_name)
    {
        $default_category = 'uncategorized';

        // Define the mapping of block names to categories
        $category_map = [
            "ad" => "Ads",
            "advertiser" => "Ads",
            "agency" => "Directory",
            "alert" => "FYI",
            "announcement" => "FYI",
            "api" => "Engineering",
            "archive" => "Directory",
            "asset" => "Design",
            "badge" => "One",
            "beacon" => "Support",
            "block" => "Directory",
            "board" => "Work",
            "bookmark" => "Directory",
            "brand" => "Design",
            "budget" => "Finance",
            "bundle" => "Shop",
            "cache" => "Systems",
            "campaign" => "Marketing",
            "capability" => "One",
            "charter" => "Management",
            "check" => "Health",
            "collection" => "Info",
            "command" => "Systems",
            "component" => "Work",
            "conversation" => "Chat",
            "dashboard" => "Management",
            "database" => "Systems",
            "definedterm" => "Support",
            "doc" => "Wiki",
            "endpoint" => "Engineering",
            "entitlement" => "One",
            "enum" => "Engineering",
            "environment" => "Dev",
            "event" => "Directory",
            "extension" => "Directory",
            "feature" => "Directory",
            "flow" => "Directory",
            "folder" => "Wiki",
            "form" => "Directory",
            "fund" => "Shop",
            "gist" => "Directory",
            "group" => "Directory",
            "guide" => "Directory",
            "host" => "Directory",
            "inbox" => "Support",
            "industry" => "Directory",
            "integration" => "Directory",
            "interface" => "Directory",
            "issue" => "Work",
            "jobposting" => "Directory",
            "link" => "Directory",
            "list" => "Marketing",
            "log" => "Directory",
            "maker" => "Directory",
            "manifest" => "Directory",
            "member" => "Community",
            "metric" => "Marketing",
            "model" => "Directory",
            "module" => "Directory",
            "network" => "Directory",
            "news" => "Press",
            "newsletter" => "Pub",
            "offer" => "Shop",
            "page" => "Directory",
            "part" => "Directory",
            "partner" => "Directory",
            "pattern" => "Directory",
            "perk" => "Pro",
            "pixel" => "Marketing",
            "place" => "Directory",
            "placement" => "Marketing",
            "platform" => "Directory",
            "plugin" => "Directory",
            "podcast" => "Pub",
            "post" => "Blog",
            "priority" => "Work",
            "product" => "Shop",
            "productvariant" => "Shop",
            "profile" => "Bio",
            "program" => "Pro",
            "project" => "Work",
            "property" => "Engineering",
            "publisher" => "Directory",
            "question" => "Support",
            "reference" => "Support",
            "report" => "Pub",
            "review" => "Pub",
            "roadmap" => "Work",
            "role" => "One",
            "searchresult" => "Search",
            "segment" => "Marketing",
            "server" => "Systems",
            "service" => "Directory",
            "setting" => "Systems",
            "shop" => "Systems",
            "shortcode" => "Directory",
            "site" => "Systems",
            "sitemap" => "Systems",
            "sku" => "Shop",
            "space" => "Directory",
            "sponsor" => "Directory",
            "statement" => "Info",
            "status" => "Work",
            "studio" => "Dev",
            "style" => "Design",
            "subdomain" => "Directory",
            "table" => "Systems",
            "team" => "Directory",
            "term" => "Pub",
            "theme" => "Directory",
            "timeline" => "Work",
            "tld" => "Directory",
            "token" => "Pro",
            "tool" => "Directory",
            "update" => "FYI",
            "upload" => "Media",
            "value" => "Info",
            "variable" => "Engineering",
            "vault" => "Security",
            "video" => "Pub",
            "wiki" => "Wiki",
            "work" => "Work",
            "zone" => "Systems",
        ];

        // Return the category if it exists in the map, else 'uncategorized'
        return $category_map[$block_name] ?? $default_category;
    }

    /**
     * Create an index.php file using the WordPress Filesystem API.
     *
     * @param string $directory Path to the child directory.
     * @param string $fileName  Name of the directory (used for namespace).
     */
    private function create_index_php(string $directory, string $fileName)
    {
        global $wp_filesystem;

        $namespace = 'WPS2\\Blocks\\' . ucfirst($fileName) . 'Manifest';

        $className = strtolower($fileName);

        $phpContent = <<<PHP
        <?php
        // Path: {$directory}/index.php

        namespace {$namespace};

        ?>

        <div class="wp2s-mainfest wp2s-mainfest--{$className}">


        </div>
        PHP;

        $indexPhpPath = trailingslashit($directory) . 'index.php';

        if (!$wp_filesystem->put_contents($indexPhpPath, $phpContent, FS_CHMOD_FILE)) {
            error_log("Failed to create index.php in directory: $directory");
        }
    }

    /**
     * Validate and update the block.json file.
     *
     * @param string $path Path to the block.json file.
     */
    private function validate_block_json(string $path)
    {
        // Read and decode the JSON file
        $content = file_get_contents($path);
        $data = json_decode($content, true);

        // Check for JSON parsing errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Invalid JSON in file: $path");
            return;
        }

        $updated = false;

        // Ensure required top-level keys are set
        $requiredKeys = [
            '$schema'      => 'https://schemas.wp.org/block.json',
            'name'         => 'block/name',
            'title'        => 'Default Title',
            'icon'         => 'embed-generic',
            'description'  => 'Default Description',
            'supports'     => [],
            'ancestor'    => [],
            'blockstudio'  => ['attributes' => []],
        ];

        foreach ($requiredKeys as $key => $default) {
            if (!isset($data[$key])) {
                $data[$key] = $default;
                error_log("Set missing key '{$key}' to default value in block.json: $path");
                $updated = true;
            }
        }

        // Ensure 'supports' section is an array
        if (!isset($data['supports']) || !is_array($data['supports'])) {
            $data['supports'] = [];
        }

        // Ensure 'supports' section has required keys
        $supportsKeys = [
            'className'        => true,
            'customClassName'  => true,
            'align'            => ['full', 'wide'],
            'renaming'         => true,
            'color'            => ['background' => true, 'text' => true],
        ];

        foreach ($supportsKeys as $key => $default) {
            if (!isset($data['supports'][$key])) {
                $data['supports'][$key] = $default;
                error_log("Set missing 'supports.{$key}' to default value in block.json: $path");
                $updated = true;
            }
        }

        // ensure 'ancestor' section is an array
        if (!isset($data['ancestor']) || !is_array($data['ancestor'])) {
            $data['ancestor'] = [];
        }

        // ensure 'ancestor' section has required keys
        $ancestors = [
            'wp2s/manifests'
        ];

        foreach ($ancestors as $ancestor) {
            if (!in_array($ancestor, $data['ancestor'])) {
                $data['ancestor'][] = $ancestor;
                error_log("Added missing ancestor '{$ancestor}' to block.json: $path");
                $updated = true;
            }
        }

        // Ensure 'blockstudio' is an object
        if (!isset($data['blockstudio']) || !is_array($data['blockstudio'])) {
            $data['blockstudio'] = ['attributes' => []];
            error_log("Set 'blockstudio' to default structure in block.json: $path");
            $updated = true;
        }

        // Ensure 'blockstudio.attributes' is an array
        if (!isset($data['blockstudio']['attributes']) || !is_array($data['blockstudio']['attributes'])) {
            $data['blockstudio']['attributes'] = [];
            error_log("Set 'blockstudio.attributes' to default array in block.json: $path");
            $updated = true;
        }

        // Define required blockstudio attributes
        $requiredAttributes = [
            ['id' => 'name', 'type' => 'text', 'label' => 'Name'],
            ['id' => 'description', 'type' => 'textarea', 'label' => 'Description'],
            ['id' => 'id', 'type' => 'text', 'label' => 'Id'],
            ['id' => 'slug', 'type' => 'text', 'label' => 'Slug'],
            ['id' => 'type', 'type' => 'text', 'label' => 'Type'],
        ];

        // Validate and add missing attributes in blockstudio
        foreach ($requiredAttributes as $requiredAttr) {
            $exists = false;

            foreach ($data['blockstudio']['attributes'] as $attr) {
                if (
                    isset($attr['id'], $attr['type'], $attr['label']) &&
                    $attr['id'] === $requiredAttr['id'] &&
                    $attr['type'] === $requiredAttr['type'] &&
                    $attr['label'] === $requiredAttr['label']
                ) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $data['blockstudio']['attributes'][] = $requiredAttr;
                error_log("Added missing attribute '{$requiredAttr['id']}' to blockstudio.attributes in block.json: $path");
                $updated = true;
            }
        }

        // If changes were made, write the updated data back to the file
        if ($updated) {
            file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            error_log("Updated block.json file at: $path");
        } else {
            error_log("No changes required for block.json: $path");
        }
    }
}

new Controller();
