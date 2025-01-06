<?php
// Path: wp-content/plugins/extend-ws-form/src/Form/index.php

namespace WP2\Extend\WSForm\Form;

// Retrieve and sanitize attributes with fallback values
$form_id = $attributes['ws_form'] ?? '';

$element_id = sanitize_title($attributes['element_id'] ?? '');

// wsf_form_get_all

$forms = wsf_form_get_all();

do_action( 'qm/debug', 'forms: ' . print_r($a, true) );

// Construct element argument if element_id exists
$form_element_arg = $element_id ? ' element_id="' . esc_attr($element_id) . '"' : '';

?>
<div useBlockProps class="wp2-form">

	<?php echo do_shortcode(sprintf('[ws_form id="%s"%s]', esc_attr($form_id), $form_element_arg)); ?>

</div>