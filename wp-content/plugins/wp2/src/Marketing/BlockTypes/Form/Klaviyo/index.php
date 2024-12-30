<?php
$form_id = isset($a['form_id']) ? $a['form_id'] : '';
?>

<?php if (!$isEditor) : ?>
    <div useBlockProps class="klaviyo-form-<?php echo esc_attr($form_id); ?>"></div>
<?php else : ?>
    <div useBlockProps>
        <div class="placeholder placeholder-klaviyo-form">
            <div class="placeholder__label">Klaviyo Form</div>
            <div class="placeholder__content">
                <p>A form will be displayed here on the front end</p>
            </div>
        </div>
    </div>
<?php endif; ?>