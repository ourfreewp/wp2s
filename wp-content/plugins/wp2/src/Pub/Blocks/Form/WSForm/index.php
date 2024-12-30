<?php
$form_id = isset($attributes['formId']) ? $attributes['formId'] : '1';
$form_element_id = isset($attributes['formElementId']) ? $attributes['formElementId'] : '';

$form_element_id = sanitize_title($form_element_id);

if ($form_element_id) {
	$form_element_arg = ' element_id="' . $form_element_id . '"';
} else {
	$form_element_arg = '';
}

?>
<div useBlockProps>

	<?php echo do_shortcode('[ws_form id="' . $form_id . '"' . $form_element_arg . ']'); ?>

</div>