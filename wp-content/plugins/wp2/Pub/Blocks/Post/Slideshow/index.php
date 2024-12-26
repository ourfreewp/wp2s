<?php

// Post Data
$post = get_post($post_id);
$post_meta = get_post_meta($post_id);

// Slide Data
$slides_meta_key = 'slideshow-slides';

$slide_data = rwmb_get_value($slides_meta_key, [], $post_id);

$slide_field_key = 'slideshow-slide';

if (empty($slide_data)) {
    return;
}

$slides = [];

$prefix = 'slideshow-';

foreach ($slide_data as $slide) {

    $slide_index = array_search($slide, $slide_data) + 1;

    $slide_title = isset($slide[$prefix . 'slide-title']) ? $slide[$prefix . 'slide-title'] : '';

    $slide_content = isset($slide[$prefix . 'slide-content']) ? $slide[$prefix . 'slide-content'] : '';

    $slide_image = isset($slide[$prefix . 'slide-image'][0]) ? $slide[$prefix . 'slide-image'][0] : '';

    $slides[] = [
        'title' => $slide_title,
        'content' => $slide_content,
        'position' => $slide_index,
        'image' => $slide_image,
    ];
}

$slide_count = count($slides);

$last_slide = oddnews_get_last_slide_data($post_id, $slide_count);

if (!empty($last_slide)) {
    $slides[] = $last_slide;
}

foreach ($slides as $key => $slide) {

    $slides[$key]['name'] = 'Position ' . ($key + 1);

    $slide_position = $key + 1;

    $slides[$key]['position'] = $slide_position;

    $slide_title = isset($slide['title']) ? $slide['title'] : '';

    if ($slide_title) {
        $slides[$key]['hash'] = sanitize_title($slide_title);
    } else {
        $slides[$key]['hash'] = 'slide-' . $slide_position;
    }
}

$primary_options = [
    'type' => 'slide',
    'rewind' => false,
    'arrows' => true,
    'pagination' => false,
    'autoplay' => false,
    'lazyLoad' => 'nearby',
    'height' => 'auto',
    'updateOnMove' => true,
    'mediaQuery' => 'max',
    'breakpoints' => [
        '768' => [
            'pagination' => false,
        ],
    ]
];

$secondary_options = [
    'rewind' => false,
    'pagination' => false,
    'arrows' => false,
    'isNavigation' => true,
    'updateOnMove' => true,
    'mediaQuery' => 'max',
    'height' => '90px',
    'trimSpace' => true,
    'perPage' => 5,
    'breakpoints' => [
        '576' => [
            'perPage' => 4,
            'height' => '50px',
        ],
    ]
];

$tertiary_options = [
    'type' => 'fade',
    'speed' => 0,
    'easing' => 'cubic-bezier(0.25, 1, 0.5, 1)',
    'rewind' => false,
    'arrows' => false,
    'drag' => false,
    'autoHeight' => true,
    'height' => 'auto',
    'lazyLoad' => false,
    'pagination' => false,
];

?>

<div useBlockProps class="slideshow">

    <div class="slideshow__inner">

        <div class="slideshow__primary splide" id="slideshow-primary-<?php echo esc_attr($post_id); ?>"
            data-splide='<?php echo esc_attr(wp_json_encode($primary_options)); ?>'>

            <div class="splide__track">

                <ul class="splide__list">

                    <?php foreach ($slides as $slide): ?>

                        <?php
                        $slide_hash = isset($slide['hash']) ? $slide['hash'] : '';
                        $slide_image_id = isset($slide['image']) ? $slide['image'] : '';
                        $slide_image_attachment = wp_get_attachment_image_src($slide_image_id, 'large');
                        $slide_image_attachment_caption = wp_get_attachment_caption($slide_image_id);
                        $slide_image_attachment_byline = get_post_meta($slide_image_id, 'byline', true);
                        ?>

                        <li class="splide__slide" data-splide-hash="<?php echo esc_attr($slide_hash); ?>">

                            <div class="splide__slide__container">
                                <div class="splide__slide__container__inner">
                                    <div class="splide__slide__image">
                                        <?php echo wp_get_attachment_image($slide_image_id, "large"); ?>
                                    </div>
                                    <?php if ($slide_image_attachment_caption || $slide_image_attachment_byline): ?>
                                        <div class="splide__slide__attribution">
                                            <?php if ($slide_image_attachment_caption): ?>
                                                <div class="splide__slide__caption">
                                                    <?php echo $slide_image_attachment_caption; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($slide_image_attachment_byline): ?>
                                                <div class="splide__slide__byline">
                                                    <?php echo $slide_image_attachment_byline; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

        <div class="slideshow__secondary splide d-none" id="slideshow-secondary-<?php echo esc_attr($post_id); ?>"
            data-splide='<?php echo esc_attr(wp_json_encode($secondary_options)); ?>'>

            <div class="splide__track">

                <ul class="splide__list">

                    <?php foreach ($slides as $slide): ?>
                        <?php
                        $slide_hash = isset($slide['hash']) ? $slide['hash'] : '';
                        $slide_image_id = isset($slide['image']) ? $slide['image'] : '';
                        $slide_image_thumbnail_id = isset($slide['image_thumbnail']) ? $slide['image_thumbnail'] : '';

                        if (!empty($slide_image_thumbnail_id)) {
                            $slide_image_id = $slide_image_thumbnail_id;
                        }
                        ?>

                        <li class="splide__slide" data-splide-hash="<?php echo esc_attr($slide_hash); ?>">
                            <?php echo wp_get_attachment_image($slide_image_id, "thumbnail"); ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

        <div class="slideshow__tertiary splide" id="slideshow-tertiary-<?php echo esc_attr($post_id); ?>"
            data-splide='<?php echo esc_attr(wp_json_encode($tertiary_options)); ?>'>

            <div class="splide__track">

                <ul class="splide__list">

                    <?php foreach ($slides as $slide): ?>

                        <?php
                        $slide_hash = isset($slide['hash']) ? $slide['hash'] : '';
                        $slide_title = isset($slide['title']) ? $slide['title'] : '';
                        $slide_content = isset($slide['content']) ? $slide['content'] : '';
                        ?>

                        <li class="splide__slide" data-splide-hash="<?php echo esc_attr($slide_hash); ?>">

                            <div class="splide__slide__content">

                                <div class="splide__slide__content__main">

                                    <div class="splide__slide__content__header">

                                        <?php if ($slide_title): ?>

                                            <h2 class="splide__slide__title">
                                                <?php echo esc_html($slide_title); ?>
                                            </h2>

                                        <?php endif; ?>

                                    </div>

                                    <?php if ($slide_content): ?>
                                        <div class="splide__slide__content__body">
                                            <?php echo wp_kses($slide_content, 'post'); ?>
                                        </div>
                                    <?php endif; ?>

                                </div>

                            </div>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    </div>

</div>