<?php

namespace WP2\Style\Brand;

/**
 * Manages the creation and retrieval of brand kit options.
 */
class Controller
{
    /**
     * Create default brand kit options if not already set.
     *
     * @return array|void The existing or newly created options.
     */
    public static function create_brand_kit_options()
    {
        if (false == get_option('wp2_style_brand_kit_options')) {
            $default_brand_kit_options = [
                "assets" => [
                    "logos" => [[]],
                    "images" => [[]],
                    "documents" => [[]],
                    "icons" => [[]],
                ],
                "styles" => [
                    "colors" => [
                        "palettes" => [[]],
                        "gradients" => [[]],
                        "duotones" => [[]],
                    ],
                    "typography" => [
                        "fontFamilies" => [[]],
                        "fontSizes" => [[]],
                    ],
                    "borders" => [[]],
                    "shadows" => [[]],
                ],
                "links" => [[]],
                "guidelines" => [
                    "photography" => "",
                    "illustrations" => "",
                    "icons" => "",
                    "stationery" => "",
                    "video" => "",
                    "audio" => "",
                    "images" => "",
                    "documents" => "",
                    "packaging" => "",
                    "logos" => "",
                    "print" => "",
                    "web" => "",
                    "social" => "",
                    "email" => "",
                    "advertising" => "",
                ],
            ];

            add_option('wp2_style_brand_kit_options', $default_brand_kit_options);
        } else {
            return get_option('wp2_style_brand_kit_options');
        }
    }
}