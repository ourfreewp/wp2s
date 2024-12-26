<?php
$attributes    = isset($attributes) ? $attributes : [];
$merchandiseId = isset($attributes['merchandiseId']) ? $attributes['merchandiseId'] : '';
?>

<div useBlockProps>

	<div class="wp-block-buttons">

		<div class="wp-block-button">

			<a class="wp-block-button__link" data-merchandiseId="<?php echo esc_attr( $merchandiseId ); ?>">

				<RichText attribute="button_text" placeholder="Button Text" tag="span" />

			</a>

		</div>

	</div>

</div>