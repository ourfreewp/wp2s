<?php
// Path: wp-content/plugins/wp2s/src/Blocks/Brand/init.php
namespace WPS2\Blocks\Brand;

class Controller {

    private $textdomain = 'wp2s';
    private $dir;

    public function __construct() {
        $this->dir = rtrim(WP2S_PLUGIN_DIR, '/') . '/src/Blocks/Brand';
    }

    /**
     * Get the SVG or PNG file content based on identity, kind, theme, and type.
     * 
     * @param string $identity  The identity, either 'Parent' or 'Site'.
     * @param string $kind      The type of icon/logo (e.g., Icon, Logo, ShortLogo).
     * @param string $theme     The theme, either 'light' or 'dark'.
     * @param string $type      The file type, either 'svg' or 'png'.
     * @return string           The file contents or an empty string if not found.
     */
    public function get_brand_asset($identity = 'Site', $kind = 'Icon', $theme = 'light', $type = 'svg') {

        $base = "{$identity}/{$kind}/svg/{$theme}.{$type}";
        $path = $this->dir . '/' . $base;  

        if (file_exists($path)) {

            if ($type === 'svg') {
                return $this->wp_kses_svg(file_get_contents($path));
            }


            return file_get_contents($path);
        }

        return '';
    }
    
    public function wp_kses_svg($svg) {
        $svg_args = array(
            'svg'   => array(
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true
            ),
            'g'     => array( 'fill' => true ),
            'title' => array( 'title' => true ),
            'path'  => array( 
                'd'               => true, 
                'fill'            => true  
            )
        );

        return wp_kses($svg, $svg_args);
    }
    
}