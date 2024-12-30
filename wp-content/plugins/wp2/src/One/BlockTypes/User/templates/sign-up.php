<?php
$form_id    = isset($inherit_form) ? $inherit_form : null;
$post_slug  = isset($post_slug) ? $post_slug : null;
$element_id = 'user-page-form-' . $post_slug;
$shortcode  = '[ws_form id="%s" element_id="%s"]';
$shortcode  = sprintf($shortcode, $form_id, $element_id);
echo do_shortcode($shortcode);
