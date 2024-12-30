<?php
$template_data_json = get_option('newsplicity_template_data');

// ensure is string

if (!is_string($template_data_json)) {
	$template_data_json = '';
}

$template_data = json_decode($template_data_json);

?>

<div useBlockProps class="wp-block-code">

	<pre>
		<code class="language-json">
			<?php echo esc_html(json_encode($template_data, JSON_PRETTY_PRINT)); ?>
		</code>
	</pre>

</div>