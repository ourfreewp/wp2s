<?php

namespace WP2\Studio\Helpers\Validators;

use WP2\Studio\Helpers\Logger;
use WP2\Studio\Helpers\StudioConfig;

class Controller
{
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        $this->validate_studios();
    }

    public function validate_studios()
    {
        Logger::log('Starting studio validation.', 'info');

        $studios = StudioConfig::get();
        $paths = [];

        foreach ($studios as $studio) {
            $path = WP_PLUGIN_DIR . '/' . WP2_NAMESPACE . '/' . $studio;
            $results = $this->validate_studio($path);

            if ($results['dir']) {
                $paths[] = $path;
                Logger::log("Validated studio: {$studio}", 'info');
            } else {
                Logger::log("Validation failed for studio: {$studio}", 'warning');
            }
        }

        return $paths;
    }

    private function validate_studio($path)
    {
        return [
            'dir'        => $this->validate_dir($path),
            'init_php'   => $this->validate_init_php($path),
            'schema_json'=> $this->validate_schema_json($path),
            'studio_json'=> $this->validate_studio_json($path),
            'studio_php' => $this->validate_studio_php($path),
            'readme_md'  => $this->validate_readme_md($path),
        ];
    }

    private function validate_dir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
            Logger::log("Created directory: {$path}", 'info');
        }
        return is_dir($path);
    }

    private function validate_init_php($path)
    {
        $filePath = $path . '/init.php';

        if (!file_exists($filePath)) {
            file_put_contents($filePath, '<?php ' . PHP_EOL . '// Studio Initialization');
            Logger::log("Created init.php at {$filePath}", 'info');
        }

        return file_exists($filePath);
    }

    private function validate_schema_json($path)
    {
        $filePath = $path . '/schema.json';

        if (!file_exists($filePath)) {
            file_put_contents($filePath, json_encode([], JSON_PRETTY_PRINT));
            Logger::log("Created schema.json at {$filePath}", 'info');
        }

        return file_exists($filePath);
    }

    private function validate_studio_json($path)
    {
        $filePath = $path . '/studio.json';

        if (!file_exists($filePath)) {
            file_put_contents($filePath, json_encode([], JSON_PRETTY_PRINT));
            Logger::log("Created studio.json at {$filePath}", 'info');
        }

        return file_exists($filePath);
    }

    private function validate_studio_php($path)
    {
        $filePath = $path . '/studio.php';

        if (!file_exists($filePath)) {
            file_put_contents($filePath, '<?php ' . PHP_EOL . '// Studio Logic');
            Logger::log("Created studio.php at {$filePath}", 'info');
        }

        return file_exists($filePath);
    }

    private function validate_readme_md($path)
    {
        $filePath = $path . '/README.md';

        if (!file_exists($filePath)) {
            $content = '# Studio' . PHP_EOL . 'Studio description and details.';
            file_put_contents($filePath, $content);
            Logger::log("Created README.md at {$filePath}", 'info');
        }

        return file_exists($filePath);
    }
}