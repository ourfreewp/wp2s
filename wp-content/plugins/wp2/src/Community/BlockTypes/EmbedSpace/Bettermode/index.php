<?php
$space_id      = isset($a['space_id']) ? $a['layout'] : 'space_id';
$read_only     = isset($a['read_only']) ? $a['read_only'] : 'true';
$show_header   = isset($a['show_header']) ? $a['show_header'] : 'true';
$show_composer = isset($a['show_composer']) ? $a['show_composer'] : 'true';
$show_about    = isset($a['show_about']) ? $a['show_about'] : 'true';
$tag           = isset($a['tag']) ? $a['tag'] : 'true';
$community     = isset($a['community']) ? $a['community'] : 'true';

$path = "/embed/space/$space_id";

$url = $community . $path;

$title = isset($a['title']) ? $a['title'] : '';

$params = array(
    'readonly' => $read_only,
    'header' => $show_header,
    'composer' => $show_composer,
    'about' => $show_about,
    'tag' => $tag
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