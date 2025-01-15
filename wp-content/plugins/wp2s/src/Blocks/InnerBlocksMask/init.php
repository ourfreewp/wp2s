<?php
// Path: wp-content/plugins/wp2s/src/Blocks/InnerBlocksMask/init.php
namespace WP2S\Blocks\InnerBlocksMask;

class Controller {

    private $dir;

    public function __construct() {
        $this->dir = rtrim(WP2S_PLUGIN_DIR, '/') . '/src/Blocks/InnerBlocksMask/svgs';
    }

    /**
     * Get SVG content for the InnerBlocksMask block with rotation support.
     *
     * @param int $rotation Rotation angle (multiples of 45).
     * @return string SVG content or empty string if not found.
     */
    public function get_svg($rotation = 0) {
        $valid_rotations = [0, 45, 90, 135, 180, 225, 270, 315];
        
        // Default to 0 if invalid rotation is passed
        if (!in_array($rotation, $valid_rotations)) {
            $rotation = 0;
        }

        $path = "{$this->dir}/vector.svg";
        
        if (file_exists($path)) {
            $svg = file_get_contents($path);
            $class = "wp2s-vector--{$rotation}";
            $svg = preg_replace('/<svg/', '<svg class="' . esc_attr($class) . '"', $svg, 1);
            return $this->sanitize_svg($svg);
        }

        return '';
    }
    
    /**
     * Sanitize SVG content for safe output.
     *
     * @param string $svg SVG markup.
     * @return string Sanitized SVG.
     */
    public function sanitize_svg($svg) {
        $allowed_tags = array(
            'svg'   => array(
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewBox'         => true
            ),
            'g'     => array('fill' => true),
            'path'  => array('d' => true, 'fill' => true),
            'title' => array()
        );

        return wp_kses($svg, $allowed_tags);
    }
}