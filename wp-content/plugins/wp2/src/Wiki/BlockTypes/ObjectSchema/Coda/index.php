<?php
$post_id = isset($block['postId']) ? $block['postId'] : null;

$post = get_post($post_id);

$post_title = $post->post_title;

$singular = get_post_meta($post_id, 'newsplicity_schema_singular', true);

$schema_const = str_replace(' ', '', ucwords($singular));

?>

<pre useBlockProps>
	<code class="language-typescript">
const <?= esc_html($schema_const) ?>Schema = coda.makeObjectSchema({	
	properties: {		
		// Properties
	},	
	displayProperty: "$2",}
);	
	</code>
</pre>
