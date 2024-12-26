<?php
// Determine the community URL with validation
$community = (!empty($a['communityUrl']) && filter_var($a['communityUrl'], FILTER_VALIDATE_URL)) ?
    $a['communityUrl'] : (defined('WP_COMMUNITY_BETTERMODE_COMMUNITY_URL') && filter_var(WP_COMMUNITY_BETTERMODE_COMMUNITY_URL, FILTER_VALIDATE_URL) ?
        WP_COMMUNITY_BETTERMODE_COMMUNITY_URL :
        'https://bettermode.com/hub');

// Set default values for title and layout
$title = isset($a['title']) && !empty($a['title']) ? $a['title'] : 'A community.';
$layout = isset($a['layout']) && !empty($a['layout']) ? $a['layout'] : 'basic';

// Prepare query parameters for the iframe source URL
$params = array(
    "layout" => $layout,
);
$src = add_query_arg($params, $community);

// Ensure $isEditor is defined and a boolean
$isEditor = isset($isEditor) && is_bool($isEditor) ? $isEditor : false;
?>
<div useBlockProps>
    <?php if (!$isEditor) : ?>
        <!-- Render the iframe if not in editor mode -->
        <iframe
            src="<?php echo esc_url($src); ?>"
            frameborder="0"
            title="<?php echo esc_attr($title); ?>">
        </iframe>
    <?php else : ?>
        <!-- Render a placeholder for the editor -->
        <div class="placeholder">
            <div class="placeholder__title">
                <?php echo esc_html($title); ?>
            </div>
            <div class="placeholder__description">
                This block is not supported in the editor preview.
            </div>
        </div>
    <?php endif; ?>
</div>