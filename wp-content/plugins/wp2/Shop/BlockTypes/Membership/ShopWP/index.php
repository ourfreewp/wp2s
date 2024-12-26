<?php

function modal_content()
{
?>

    <div class="modal micromodal-slide" id="modal-membership" aria-hidden="true">
        <div class="modal__overlay" tabindex="-1">
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-membership-title">
                <div class="modal__header">
                    <div class="modal__title" id="modal-membership-title">
                        Membership
                    </div>
                    <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </div>

                <div class="modal__content" id="modal-membership-content">
                    <div class="shop-forms">
                        <?php

                        $memberships = [
                            'yearly' => [
                                'merchandiseId' => '44600087183577',
                                'sellingPlanId' => '3532095705',
                                'freq_label' => 'Yearly',
                            ],
                            'monthly' => [
                                'merchandiseId' => '44600087216345',
                                'sellingPlanId' => '3532062937',
                                'freq_label' => 'Monthly',
                            ],
                        ];

                        $membership_buttons = '';

                        foreach ($memberships as $key => $value) {
                            $button = '';
                            $button .= '<!-- wp:button {"attributesForBlocks":{"data-shopify-merchandiseId":"' . $value['merchandiseId'] . '","data-shopify-sellingPlanId":"' . $value['sellingPlanId'] . '"},"className":"membership-button"} -->';
                            $button .= '<div class="wp-block-button membership-button" data-shopify-merchandiseId="' . $value['merchandiseId'] . '" data-shopify-sellingPlanId="' . $value['sellingPlanId'] . '">';
                            $button .= '<button class="wp-block-button__link wp-element-button">' . $value['freq_label'] . '</button>';
                            $button .= '</div>';
                            $button .= '<!-- /wp:button -->';
                            $membership_buttons .= $button;
                        }

                        $membership_buttons_wrapper = '<!-- wp:buttons -->';
                        $membership_buttons_wrapper .= '<div class="wp-block-buttons">';
                        $membership_buttons_wrapper .= $membership_buttons;
                        $membership_buttons_wrapper .= '</div>';
                        $membership_buttons_wrapper .= '<!-- /wp:buttons -->';

                        $membership_buttons = $membership_buttons_wrapper;

                        echo do_blocks($membership_buttons);

                        ?>

                    </div>
                </div>

                <div class="modal__footer">
                    Already a member? <a href="<?php echo esc_attr(home_url('contact')); ?>">Contact</a>
                </div>

            </div>
            <div class="modal__close_modal_overlay" aria-label="Close modal" data-micromodal-close>
                <span class="screen-reader-text">Close</span>
            </div>
        </div>
    </div>

<?php
}
