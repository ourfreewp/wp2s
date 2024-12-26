<?php
add_action('wp_loaded', function () {
	unregister_block_pattern('ws-form/signup-1');
	unregister_block_pattern('ws-form/signup-2');
});
