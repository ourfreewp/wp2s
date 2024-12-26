<?php
/*
 * Name: Instawp Template Items Count
 */
$block = isset($block) ? $block : null;

$template_data_json = get_option('newsplicity_template_data');

// ensure is string

if (!is_string($template_data_json)) {
	$template_data_json = '';
}

$attributes = isset($attributes) ? $attributes : null;

$item = isset($attributes['item']) ? $attributes['item'] : '';

$item_slug = sanitize_title($item);

$heading_tag = isset($attributes['heading_tag']) ? $attributes['heading_tag'] : 'h2';

$template_data = json_decode($template_data_json);

$inner_blocks = wp_json_encode(
	[
		[
			'core/heading',
			[
				'placeholder' => 'Title',
				'style' => [
					'spacing' => [
						'margin' => [
							'top' => '0',
						]
					]
				],
			]
		],
		[
			'core/paragraph',
			['placeholder' => 'Summary']
		]
	]
);
?>

<div useBlockProps class="is-<?= esc_attr($item_slug); ?>">

	<InnerBlocks template="<?php echo esc_attr($inner_blocks); ?>" templateLock="all"
		allowedBlocks="<?php echo esc_attr(wp_json_encode(['core/heading', 'core/paragraph'])); ?>" />

	<?php if ($isEditor): ?>
		<?= $item; ?>
	<?php endif; ?>

	<?php if (!$isEditor): ?>
		<div class="wp-block-instawp-template-items-summary-content">

			<?php
			/**
			 * block_types
			 */
			?>
			<?php if ("block_types" === $item): ?>


				<?php
				$block_types = $template_data->block_types;

				$groups = [];

				foreach ($block_types as $block_type) {
					$groups[explode('/', $block_type->name)[0]][] = $block_type;
				}

				$grouped_block_types = [];

				foreach ($groups as $group => $block_types) {

					// sort block types
					usort($block_types, function ($a, $b) {
						return $a->title <=> $b->title;
					});

					$grouped_block_types[] = [
						'group' => ucfirst($group),
						'block_types' => $block_types
					];
				}

				?>

				<div class="wp-block-instawp-template-badge-groups">

					<?php foreach ($grouped_block_types as $grouped_block_type): ?>
						<div class="wp-block-instawp-template-badge-group">
							<div class="wp-block-instawp-template-badge-group-title">
								<?php echo esc_html($grouped_block_type['group']); ?>
							</div>
							<div class="wp-block-instawp-template-badges">
								<?php foreach ($grouped_block_type['block_types'] as $block_type): ?>
									<span class="wp-block-instawp-template-badge">
										<?php
										$name = explode('/', $block_type->name)[1];
										?>
										<?php echo esc_html($name); ?>
									</span>
								<?php endforeach; ?>
							</div>

						</div>
					<?php endforeach; ?>

				</div>



			<?php endif; ?>

			<?php
			/**
			 * block_visibility_presets
			 */
			?>
			<?php if ("block_visibility_presets" === $item): ?>

				<?php
				$block_visibility_presets = $template_data->block_visibility_presets;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($block_visibility_presets as $block_visibility_preset): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($block_visibility_preset->title); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * code_snippets
			 */
			?>
			<?php if ("code_snippets" === $item): ?>

				<?php
				$code_snippets = $template_data->code_snippets;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($code_snippets as $code_snippet): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($code_snippet->title); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * content_experiences
			 */
			?>
			<?php if ("content_experiences" === $item): ?>

				<?php
				$content_experiences = $template_data->content_experiences;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($content_experiences as $content_experience): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($content_experience); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * custom_templates
			 */
			?>
			<?php if ("custom_templates" === $item): ?>
				
				<?php
				$custom_templates = $template_data->custom_templates;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($custom_templates as $custom_template): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($custom_template->title); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * dashboards
			 */
			?>
			<?php if ("dashboards" === $item): ?>

				<?php
				$dashboards = $template_data->dashboards;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($dashboards as $dashboard): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($dashboard->title); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * databases
			 */
			?>
			<?php if ("databases" === $item): ?>

				<?php
				$databases = $template_data->databases;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($databases as $database): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($database->title); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * fields
			 */
			?>
			<?php if ("fields" === $item): ?>

				<?php
				$fields = $template_data->fields;
				?>

			<?php endif; ?>

			<?php
			/**
			 * pages
			 */
			?>
			<?php if ("pages" === $item): ?>
				
				<?php
				$pages = $template_data->pages;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($pages as $page): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($page->post_title); ?>
						</span>
					<?php endforeach; ?>
				</div>
			
			<?php endif; ?>

			<?php
			/**
			 * php_extensions
			 */
			?>
			<?php if ("php_extensions" === $item): ?>

				<?php
				$php_extensions = $template_data->php_extensions;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($php_extensions as $php_extension): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($php_extension->name); ?>
						</span>
					<?php endforeach; ?>
				</div>
			
				

			<?php endif; ?>

			<?php
			/**
			 * plugins
			 */
			?>
			<?php if ("plugins" === $item): ?>

				<?php
				$plugins = $template_data->plugins;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($plugins as $plugin): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($plugin->Name); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * post_statuses
			 */
			?>
			<?php if ("post_statuses" === $item): ?>

				<?php
				$post_statuses = $template_data->post_statuses;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($post_statuses as $post_status): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($post_status->name); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * post_types
			 */
			?>
			<?php if ("post_types" === $item): ?>

				<?php
				$post_types = $template_data->post_types;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($post_types as $post_type): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($post_type->name); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * shortcodes
			 */
			?>
			<?php if ("shortcodes" === $item): ?>

				<?php
				$shortcodes = $template_data->shortcodes;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($shortcodes as $shortcode): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($shortcode); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * sites
			 */
			?>
			<?php if ("sites" === $item): ?>

				<?php
				$sites = $template_data->sites;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($sites as $site): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($site->name); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * rewrite_rules
			 */
			?>
			<?php if ("rewrite_rules" === $item): ?>

				<?php
				$rewrite_rules = $template_data->rewrite_rules;
				?>

				<div class="wp-block-instawp-template-badges" style="max-height: 250px; overflow-y: scroll;">
					<?php foreach ($rewrite_rules as $rewrite_rule): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($rewrite_rule); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * taxonomies
			 */
			?>
			<?php if ("taxonomies" === $item): ?>

				<?php
				$taxonomies = $template_data->taxonomies;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($taxonomies as $taxonomy): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($taxonomy->name); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * template_parts
			 */
			?>
			<?php if ("template_parts" === $item): ?>

				<?php
				$template_parts = $template_data->template_parts;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($template_parts as $template_part): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($template_part); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * template_part_areas
			 */
			?>
			<?php if ("template_part_areas" === $item): ?>

				<?php
				$template_part_areas = $template_data->template_part_areas;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($template_part_areas as $template_part_area): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($template_part_area->label); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * themes
			 */
			?>
			<?php if ("themes" === $item): ?>

				<?php
				$themes = $template_data->themes;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($themes as $theme): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($theme->name); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * Tools
			 */
			?>
			<?php if ("tools" === $item): ?>

				<?php
				$tools = $template_data->tools;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($tools as $tool): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($tool->title); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>

			<?php
			/**
			 * Uploads
			 */
			?>
			<?php if ("media_uploads" === $item): ?>

				<?php
				$uploads = $template_data->media_uploads;
				?>

				<div class="wp-block-instawp-template-badges">
					<?php foreach ($uploads as $upload): ?>
						<span class="wp-block-instawp-template-badge">
							<?php echo esc_html($upload->title); ?>
						</span>
					<?php endforeach; ?>
				</div>

			<?php endif; ?>


		</div>
	<?php endif; ?>

</div>