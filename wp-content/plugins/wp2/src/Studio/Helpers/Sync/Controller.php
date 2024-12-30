<?php

namespace WP2\Studio\Helpers\Sync;

use WP2\Studio\Helpers\Profiler;

class Controller
{
    /**
     * Initialize the SyncManager by setting up WordPress hooks.
     */
    public function __construct()
    {
        add_action('load-edit.php', [$this, 'sync_studio_on_list_page']);
        add_action('save_post', [$this, 'sync_studio_on_save'], 10, 3);
        add_action('pre_get_posts', [$this, 'maybe_trigger_sync_on_search']);
    }

    /**
     * Sync studios when visiting the admin list page.
     */
    public function sync_studio_on_list_page()
    {
        global $typenow;

        if ($typenow && strpos($typenow, WP2_PREFIX) === 0) {
            $studio_key = str_replace(WP2_PREFIX, '', $typenow);

            if ($studio_key === 'studio') {
                $this->sync_all_studios();
            } else {
                $this->sync_studio($studio_key);
            }
        }
    }

    /**
     * Sync studio on post save.
     */
    public function sync_studio_on_save($post_id, $post, $update)
    {
        $post_type = get_post_type($post_id);

        if (strpos($post_type, WP2_PREFIX) === 0) {
            $studio_key = str_replace(WP2_PREFIX, '', $post_type);

            if ($studio_key === 'studio') {
                $this->sync_self_studio($post_id);
            } else {
                $this->sync_block($studio_key, $post_id);
            }
        }
    }

    /**
     * Trigger sync on search query.
     */
    public function maybe_trigger_sync_on_search($query)
    {
        if (is_admin() && $query->is_search() && $query->is_main_query()) {
            global $typenow;

            if ($typenow && strpos($typenow, WP2_PREFIX) === 0) {
                $studio_key = str_replace(WP2_PREFIX, '', $typenow);

                if ($this->should_sync_on_search($studio_key, $query->query_vars['s'])) {
                    ($studio_key === 'studio')
                        ? $this->sync_all_studios()
                        : $this->sync_studio($studio_key);
                }
            }
        }
    }

    /**
     * Determine if sync should be triggered based on search.
     */
    private function should_sync_on_search($studio_key, $search_term)
    {
        $last_sync_time = $this->get_last_sync_time($studio_key);

        $relevant_keywords = ['update', 'change', 'new'];
        $recent_timeframe = 3600; // 1 hour

        return in_array(strtolower($search_term), $relevant_keywords)
            || (time() - $last_sync_time) > $recent_timeframe;
    }

    /**
     * Get the last sync time for a studio.
     */
    private function get_last_sync_time($studio_key)
    {
        $studio_path = WP_PLUGIN_DIR . '/' . WP2_NAMESPACE . '/' . $studio_key . '/studio.json';

        return file_exists($studio_path) ? filemtime($studio_path) : 0;
    }

    /**
     * Sync all studios.
     */
    public function sync_all_studios()
    {
        Profiler::start('sync_all_studios');

        $studios = studio();
        foreach ($studios as $studio_key => $studio_data) {
            $this->sync_studio($studio_key);
        }

        $this->sync_studio_system();
        Profiler::stop('sync_all_studios');
    }

    /**
     * Sync individual studio by key.
     */
    public function sync_studio($studio_key)
    {
        Profiler::start('sync_studio_' . $studio_key);

        $studio_path = WP_PLUGIN_DIR . '/' . WP2_NAMESPACE . '/' . $studio_key . '/studio.json';

        if ($this->needs_sync($studio_path)) {
            $this->sync_json_file($studio_path);
        }

        Profiler::stop('sync_studio_' . $studio_key);
    }

    /**
     * Sync individual studio description.
     */
    public function sync_self_studio($post_id)
    {
        Profiler::start('sync_self_studio_' . $post_id);

        $studio_key = get_post_field('post_name', $post_id);
        $studio_path = WP_PLUGIN_DIR . '/' . WP2_NAMESPACE . '/' . $studio_key . '/studio.json';

        $this->sync_json_file($studio_path);
        Profiler::stop('sync_self_studio_' . $post_id);
    }

    /**
     * Sync a block within a studio.
     */
    public function sync_block($studio_key, $post_id)
    {
        Profiler::start('sync_block_' . $post_id);

        $block_name = get_post_field('post_name', $post_id);
        $block_path = WP_PLUGIN_DIR . '/' . WP2_NAMESPACE . '/' . $studio_key . '/' . $block_name . '/block.json';

        $this->sync_json_file($block_path);
        Profiler::stop('sync_block_' . $post_id);
    }

    /**
     * Sync studio system.
     */
    public function sync_studio_system()
    {
        Profiler::start('sync_studio_system');

        $system_path = WP_PLUGIN_DIR . '/' . WP2_NAMESPACE . '/studios.json';
        $this->sync_json_file($system_path);

        Profiler::stop('sync_studio_system');
    }

    /**
     * Check if a JSON file needs syncing.
     */
    private function needs_sync($path)
    {
        return !file_exists($path) || (time() - filemtime($path)) > 3600;
    }

    /**
     * Perform JSON sync.
     */
    private function sync_json_file($path)
    {
        touch($path);
        do_action('qm/info', 'Synced JSON at ' . $path);
    }
}