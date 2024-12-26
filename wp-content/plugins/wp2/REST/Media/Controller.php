<?php

namespace WP2\REST\Media;

use WP_REST_Request;
use WP_Error;

/**
 * Class Controller to handle media uploads via REST API.
 */
class Controller
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'register_endpoints']);
    }

    /**
     * Register REST API Endpoints for media uploads.
     */
    public function register_endpoints()
    {
        register_rest_route('wp2/v1', '/media/upload', [
            'methods'  => 'POST',
            'callback' => [$this, 'upload_image'],
            'permission_callback' => function () {
                return current_user_can('upload_files');
            },
        ]);
    }

    /**
     * Handle the image upload from a remote URL.
     *
     * @param WP_REST_Request $request
     * @return array|WP_Error
     */
    public function upload_image(WP_REST_Request $request)
    {
        global $wp_filesystem;
        WP_Filesystem();

        $body = json_decode($request->get_body(), true);
        $image_url = $body['file'] ?? '';

        if (empty($image_url)) {
            return new WP_Error('no_file', 'No file provided', ['status' => 400]);
        }

        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            return new WP_Error('invalid_url', 'Invalid URL provided', ['status' => 400]);
        }

        $image_info = getimagesize($image_url);
        if (!$image_info) {
            return new WP_Error('invalid_image', 'Could not retrieve image from URL', ['status' => 400]);
        }

        // Extract MIME type and create unique filename
        $mime_type = $image_info['mime'];
        $extension = explode('/', $mime_type)[1];
        $filename = date('dmY') . (int) microtime(true) . '.' . $extension;

        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'] . '/' . $filename;
        $contents = file_get_contents($image_url);

        if (!$contents) {
            return new WP_Error('download_failed', 'Failed to download image', ['status' => 500]);
        }

        file_put_contents($upload_path, $contents);

        $wp_filetype = wp_check_filetype($filename);
        $attachment = [
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attach_id = wp_insert_attachment($attachment, $upload_path);

        if (is_wp_error($attach_id)) {
            return new WP_Error('attachment_failed', 'Failed to create attachment', ['status' => 500]);
        }

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $upload_path);
        wp_update_attachment_metadata($attach_id, $attach_data);

        return [
            'id'  => $attach_id,
            'src' => wp_get_attachment_url($attach_id),
        ];
    }
}