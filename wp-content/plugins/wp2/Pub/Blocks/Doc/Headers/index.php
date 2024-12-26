<?php

// is post type archive or post type 'doc'

if ( ! is_singular( 'doc' ) && ! is_post_type_archive( 'doc' ) ) {
	return;
}

$context_edit = isset( $_GET['context'] ) && 'edit' == $_GET['context'];

$post_id = get_the_ID();

if ( is_post_type_archive( 'doc' ) ) {
	$post_id = get_page_by_path( 'docs' )->ID;
}

$post_content = get_post_field( 'post_content', $post_id );

$blocks = parse_blocks( $post_content );

$headings = array();

foreach ( $blocks as $block ) {
	if ( 'core/heading' == $block['blockName'] ) {
		$headings[] = $block;
	}
}
?>

<div class="newsplicity-docs-toc">

	<?php if ( $context_edit ) : ?>

		<div>Only shown on front end</div>

	<?php else : ?>

		<?php if ( $headings ) : ?>

			<div class="newsplicity-docs-toc-title">
				Table of Contents
			</div>

			<ul class="newsplicity-docs-toc-list">

				<?php foreach ( $headings as $heading ) : ?>

					<?php
					$heading_html = $heading['innerHTML'];	

					$heading_tag_processor = new WP_HTML_Tag_Processor( $heading_html );

					$heading_tag = $heading_tag_processor->next_tag();

					if ( $heading_tag ) {
						$heading_id = $heading_tag_processor->get_attribute( 'id' );
						$heading_level = substr( $heading_tag, 1 );	
					}

					$heading_text = strip_tags( $heading_html );
					?>

					<li class="newsplicity-docs-toc-item is-level-<?php echo esc_attr($heading_level);?>">

						<a href="#<?php echo esc_attr($heading_id); ?>">
							<?php echo esc_html($heading_text); ?>
						</a>
						
					</li>

				<?php endforeach; ?>

			</ul>

		<?php endif; ?>

	<?php endif; ?>

</div>