<?php
/**
 * 
 * Plugin Name: VSG Micromodal
 * 
 */

$micromodal = new VSG_Micromodal();

function vsg_micromodal_init()
{
	global $micromodal;

	add_action('wp_enqueue_scripts', [$micromodal, 'enqueue_script']);
	add_action('enqueue_block_assets', [$micromodal, 'enqueue_script']);

	add_action('wp_enqueue_scripts', [$micromodal, 'enqueue_styles']);
	add_action('enqueue_block_assets', [$micromodal, 'enqueue_styles']);

}

add_action('init', 'vsg_micromodal_init');

class VSG_Micromodal
{

	public function enqueue_script()
	{
		wp_enqueue_script('micromodal-script', 'https://unpkg.com/micromodal/dist/micromodal.min.js', [], time(), true);
		wp_enqueue_script('micromodal-init-script', plugin_dir_url(__FILE__) . 'assets/js/main.js', [], time(), true);
	}

	public function enqueue_styles()
	{
		wp_enqueue_style('micromodal-styles', plugin_dir_url(__FILE__) . 'assets/css/main.css', [], time());
	}

}