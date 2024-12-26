<?php

/**
 * Mission Statement Shortcode
 *
 * @package FreeWP\Core\Shortcodes
 */

namespace FreeWP\Core\Shortcodes;

/**
 * Class Mission_Statement
 *
 * Handles the [MissionStatement] shortcode.
 */
class Mission_Statement
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->register_shortcode();
        $this->register_setting();
    }

    /**
     * Registers the shortcode with WordPress.
     */
    public function register_shortcode()
    {
        add_shortcode('MissionStatement', [$this, 'render']);
    }

    /**
     * Renders the mission statement.
     *
     * @param array  $atts Shortcode attributes (not used here).
     * @param string $content Content enclosed within the shortcode (optional).
     * @return string Output for the shortcode.
     */
    public function render($atts, $content = null)
    {
        // Replace the mission statement string below.
        $mission_statement = $this->get_mission_statement();

        // Return the mission statement wrapped in a <span> element (optional).
        return sprintf('<span class="freewp-inline">%s</span>', esc_html($mission_statement));
    }


    /**
     * Register Mission Statement Setting
     * 
     * @return void
     */
    public function register_setting()
    {
        add_filter(
            'rwmb_meta_boxes',
            function ($meta_boxes) {
                $prefix = FREEWP_PREFIX;
                $text_domain = FREEWP_TEXT_DOMAIN;
                $settings_id = FREEWP_MB_SETTINGS_ID;
                $meta_boxes[] = [
                    'settings_pages' => [$settings_id],
                    'fields'         => [
                        [
                            'name'              => __('Mission Statement', $text_domain),
                            'id'                => $prefix . 'mission_statement',
                            'type'              => 'textarea',
                            'rows'              => 3,
                            'required'          => false,
                            'disabled'          => false,
                            'readonly'          => false,
                            'clone'             => false,
                            'clone_empty_start' => false,
                            'hide_from_rest'    => false,
                            'hide_from_front'   => false,
                        ],
                    ],
                ];

                return $meta_boxes;
            }
        );
    }

    public function get_mission_statement()
    {
        $prefix = FREEWP_PREFIX;
        $option = FREEWP_MB_SETTINGS_OPTION;
        $field = 'mission_statement';
        $key = $prefix . $field;
        $value = rwmb_meta($key, ['object_type' => 'setting'], $option);
        return $value;
    }
}


new Mission_Statement();
