<?php

namespace WP2\REST\Settings\Helpers;

class Controller
{
    public static function get_custom_fields()
    {
        $default_site_fields = self::get_default_site_fields();

        $custom_fields = [
            "site" => $default_site_fields,
            "post" => [],
            "term" => [],
            "user" => [],
            "comment" => [],
            "media" => [],
            "link" => [],
        ];

        return $custom_fields;
    }

    public static function get_default_site_fields()
    {
        $prefix = 'newsplicity_';
        
        return [
            [
                'name'        => $prefix . 'klaviyo_api_privateKey',
                'title'       => 'Klaviyo Private API Key',
                'description' => 'The private API key for Klaviyo',
                'private'     => true,
                'link'        => 'https://www.klaviyo.com/account#api-keys-tab',
            ],
            [
                'name'        => $prefix . 'klaviyo_api_publicKey',
                'title'       => 'Klaviyo Public API Key',
                'description' => 'The public API key for Klaviyo',
                'private'     => true,
                'link'        => 'https://www.klaviyo.com/account#api-keys-tab',
            ],
            [
                'name'        => $prefix . 'microsoftClarity_api_projectId',
                'title'       => 'Microsoft Clarity Project ID',
                'description' => 'The project id for Microsoft Clarity',
                'private'     => false,
                'link'        => 'https://www.klaviyo.com/account#api-keys-tab',
            ],
        ];
    }

    public static function get_setting_meta($option_name)
    {
        $custom_fields = self::get_custom_fields();
        $site_fields = $custom_fields['site'];

        $setting_meta = array_filter($site_fields, function ($field) use ($option_name) {
            return $field['name'] === $option_name;
        });

        return array_shift($setting_meta);
    }

    public static function check_for_settings()
    {
        return [
            self::create_setting('klaviyo', 'privateKey', ['api']),
            self::create_setting('klaviyo', 'publicKey', ['api']),
        ];
    }

    public static function create_setting($field_group, $field, $field_subgroups = [])
    {
        $prefix = 'newsplicity_';
        $subgroup_str = !empty($field_subgroups) ? '_' . implode('_', $field_subgroups) : '';
        $setting_name = $prefix . $field_group . $subgroup_str . '_' . $field;

        if (get_option($setting_name) === false) {
            add_option($setting_name, '');
        }

        return $setting_name;
    }

    public static function get_setting_details($option_name, $option_value)
    {
        $meta = self::get_setting_meta($option_name);
        $title = $meta['title'] ?? $option_name;
        $description = $meta['description'] ?? '';
        $private = $meta['private'] ?? false;
        $link = $meta['link'] ?? '';

        $masked_value = $private ? substr($option_value, 0, 3) . '********' : $option_value;
        
        $option_key_parts = explode('_', $option_name);
        $field_group = $option_key_parts[1];
        $field_name = end($option_key_parts);
        $subgroups = array_slice($option_key_parts, 2, -1);

        return [
            'title' => $title,
            'description' => $description,
            'field_group' => $field_group,
            'field_subgroups' => $subgroups,
            'field_name' => $field_name,
            'private' => $private,
            'name' => $option_name,
            'link' => $link,
            'value' => $masked_value,
            'id' => hash('sha256', get_site_url() . '_' . $option_name),
        ];
    }
}