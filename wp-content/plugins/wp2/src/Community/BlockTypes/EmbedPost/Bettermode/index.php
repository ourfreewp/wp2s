<?php
$item_id           = isset($a['item_id']) ? $a['layout'] : 'item_id';
$read_only         = isset($a['read_only']) ? $a['read_only'] : 'true';
$view              = isset($a['view']) ? $a['view'] : 'true';
$show_full_content = isset($a['show_full_content']) ? $a['show_full_content'] : 'true';
$community         = isset($a['community']) ? $a['community'] : 'true';

$path = "/embed/post/$item_id";

$url = $community . $path;

$title = isset($a['title']) ? $a['title'] : '';

$params = array(
    'readonly' => $read_only,
    'view' => $view,
    'show_full_content' => $show_full_content,
);

$src = add_query_arg($params, $src);

?>
<div useBlockProps>
    <iframe
        src="<?php echo esc_attr($src); ?>"
        frameBorder="0"
        title="<?php echo esc_attr($title); ?>">
    </iframe>
</div>