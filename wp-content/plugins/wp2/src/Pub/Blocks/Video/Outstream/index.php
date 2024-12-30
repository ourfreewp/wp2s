<?php
$player_type = isset($a['player_type']) ? $a['player_type'] : 'jwplayer';
$player_id   = isset($a['player_id']) ? $a['player_id'] : '';

if ($player_type === 'jwplayer') {
    $player_src = "https://cdn.jwplayer.com/libraries/" . $player_id . ".js";
    $container_id = "jwp-outstream-unit";
    wp_enqueue_script('jwplayer-script-' . $player_id, $player_src, [], null, true);
}

?>

<?php if (!$isEditor) : ?>
    <div useBlockProps id="<?php echo esc_attr($container_id); ?>"></div>
<?php else : ?>
    <div useBlockProps>
        <div class="placeholder placeholder-outstream">
            <div class="placeholder__label">Outstream Video</div>
            <div class="placeholder__content">
                <p>A player will be displayed here on the front end</p>
            </div>
        </div>
    </div>
<?php endif; ?>