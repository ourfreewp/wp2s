<?php

namespace WP2\Pro;

class Controller
{
    public static function init()
    {
        add_filter('the_content', [__CLASS__, 'add_content_gate_support_to_post_content']);
        add_action('save_post', [__CLASS__, 'set_default_access_level_on_save']);
    }

    public static function add_content_gate_support_to_post_content($content)
    {
        $contentGateMarker = '<!-- content-gate -->';

        if (strpos($content, $contentGateMarker) !== false) {
            return self::process_content_gate($content, $contentGateMarker);
        } else {
            return $content;
        }
    }

    private static function process_content_gate($content, $contentGateMarker)
    {
        $unprotected_content = self::construct_unprotected_content($content, $contentGateMarker);
        $protected_content = self::construct_protected_content($content, $contentGateMarker);
        $content_gate = self::construct_content_gate_block();

        $user_id = get_current_user_id();
        $member = get_user_meta($user_id, 'member', true);
        $terms = get_the_terms(get_the_ID(), 'access-level');

        $include_protected_content = ($member == 1 && is_array($terms) && in_array('member', wp_list_pluck($terms, 'slug'))) ||
            (is_user_logged_in() && is_array($terms) && in_array('user', wp_list_pluck($terms, 'slug'))) ||
            (empty($terms) || in_array('visitor', wp_list_pluck($terms, 'slug')));

        if (!$include_protected_content) {
            return $unprotected_content . $content_gate;
        } else {
            return $unprotected_content . $content_gate . $protected_content;
        }
    }

    private static function construct_unprotected_content($content, $contentGateMarker)
    {
        $gate_position = strpos($content, $contentGateMarker);
        return ($gate_position !== false) ? substr($content, 0, $gate_position) : '';
    }

    private static function construct_protected_content($content, $contentGateMarker)
    {
        $gate_position = strpos($content, $contentGateMarker);
        return ($gate_position !== false) ? substr($content, $gate_position + strlen($contentGateMarker)) : '';
    }

    private static function construct_content_gate_block()
    {
        $terms = get_the_terms(get_the_ID(), 'access-level');
        $content_gate = '';

        if ($terms && !is_wp_error($terms)) {
            $first_term = reset($terms);
            $content_gate_post_id = rwmb_meta('content_gate', ['object_type' => 'term'], $first_term->term_id);

            if ($content_gate_post_id) {
                $term_slug = $first_term->slug;
                $content_gate = render_block([
                    'blockName' => 'altis/broadcast',
                    'attrs' => [
                        'clientId' => 'content-gate-' . $term_slug,
                        'broadcast' => $content_gate_post_id,
                    ],
                ]);
            } else {
                $content_gate = self::get_default_content_gate_block();
            }
        } else {
            $content_gate = self::get_default_content_gate_block();
        }

        return $content_gate;
    }

    private static function get_default_access_level_term_id()
    {
        $term_args = [
            'taxonomy' => 'access-level',
            'meta_query' => [
                [
                    'key' => 'default',
                    'value' => 1,
                    'compare' => '=',
                ],
            ],
        ];

        $default_term = get_terms($term_args);
        return (!empty($default_term) && !is_wp_error($default_term)) ? $default_term[0]->term_id : false;
    }

    private static function get_default_content_gate_block()
    {
        $default_access_level_term_id = self::get_default_access_level_term_id();

        if ($default_access_level_term_id !== false) {
            $term_slug = get_term($default_access_level_term_id)->slug;
            $default_content_gate_post_id = rwmb_meta('content_gate', ['object_type' => 'term'], $default_access_level_term_id);

            if ($default_content_gate_post_id) {
                return render_block([
                    'blockName' => 'altis/broadcast',
                    'attrs' => [
                        'clientId' => 'content-gate-' . $term_slug,
                        'broadcast' => $default_content_gate_post_id,
                    ],
                ]);
            }
        }

        return 'No default content gate found.';
    }

    public static function set_default_access_level_on_save($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!in_array(get_post_type($post_id), ['post', 'page'])) return;

        $access_levels = wp_get_post_terms($post_id, 'access-level');

        if (empty($access_levels)) {
            $default_term_id = self::get_default_access_level_term_id();

            if ($default_term_id) {
                wp_set_post_terms($post_id, [$default_term_id], 'access-level');
            }
        }
    }
}

Controller::init();
