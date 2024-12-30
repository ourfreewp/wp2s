<?php

namespace WP2\REST\Run\Snapshots;

use WP2\REST\Run\Meta\Controller as MetaController;

class Controller
{
    public function __construct()
    {
        add_action('coda_pack_snapshot_scrape', [$this, 'perform_scrape'], 10, 2);
    }

    public function perform_scrape($url, $content_id)
    {
        error_log('Performing scrape for URL: ' . $url);

        $html = $this->scrape_page($url);
        if (!$html) {
            error_log('Scrape failed.');
            return;
        }

        $snapshot = [
            'html' => $html,
            'created_at' => current_time('mysql'),
        ];

        $snapshot_data = get_post_meta($content_id, MetaController::META_DATA_KEY, true) ?: [
            'snapshot_count' => 0,
            'snapshots' => [],
        ];

        $snapshot_data['snapshot_count'] += 1;
        $snapshot_data['last_snapshot'] = current_time('mysql');
        $snapshot_data['snapshots'][] = $snapshot;

        update_post_meta($content_id, MetaController::META_DATA_KEY, $snapshot_data);
    }

    private function scrape_page($url)
    {
        $query = http_build_query([
            'access_key' => WM_SCRAPESTACK_API_KEY,
            'url' => $url,
        ]);

        $response = wp_remote_get('http://api.scrapestack.com/scrape?' . $query);
        if (is_wp_error($response)) {
            return false;
        }

        return wp_remote_retrieve_body($response);
    }
}

new SnapshotController();