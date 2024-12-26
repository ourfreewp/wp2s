<?php
/**
 * Modal Controller
 *
 * Handles modals, redirections, and script enqueuing.
 *
 * @package WP2\One\BlockTypes\Modal
 */

namespace WP2\One\BlockTypes\Modal;

// Prevent direct access
if (! defined('ABSPATH')) {
    exit;
}

class Controller
{
    public function __construct()
    {
        add_action('wp_footer', [$this, 'render_modals']);
        add_action('template_redirect', [$this, 'redirect_for_signup']);
        add_action('template_redirect', [$this, 'redirect_for_signout']);
    }

    /**
     * Render the modals in the footer
     */
    public function render_modals()
    {
        ?>
        <!-- Sign In Modal -->
        <div class="modal micromodal-slide" id="modal-signin" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1">
                <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-signin-title">
                    <div class="modal__header">
                        <h2 class="modal__title" id="modal-signin-title">Sign In</h2>
                        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                    </div>
                    <div class="modal__content">
                        <?php $this->render_login_form(); ?>
                    </div>
                    <div class="modal__footer">
                        Need an account? <a href="#" data-custom-trigger="modal-signup">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sign Up Modal -->
        <div class="modal micromodal-slide" id="modal-signup" aria-hidden="true">
            <div class="modal__overlay" tabindex="-1">
                <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-signup-title">
                    <div class="modal__header">
                        <h2 class="modal__title" id="modal-signup-title">Sign Up</h2>
                        <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                    </div>
                    <div class="modal__content">
                        <div class="auth-forms">
                            <?php echo do_shortcode('[ws_form id="2"]'); ?>
                            <?php echo do_shortcode('[nextend_social_login labeltype="register"]'); ?>
                        </div>
                    </div>
                    <div class="modal__footer">
                        Have an account? <a href="#" data-custom-trigger="modal-signin">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render the login form inside the modal
     */
    private function render_login_form()
    {
        $form_html = wp_login_form(['echo' => false]);
        $form_tags = new \WP_HTML_Tag_Processor($form_html);

        if ($form_tags->next_tag(['class_name' => 'button-primary', 'tag_name' => 'input'])) {
            $form_tags->remove_class('button-primary');
            $form_tags->add_class('wp-element-button');
        }

        if (! is_user_logged_in()) {
            echo '<div class="login-form">' . $form_tags . '</div>';
        } else {
            echo '<div class="message">You are logged in</div>';
        }
    }

    /**
     * Redirect logged-in users away from the signup page
     */
    public function redirect_for_signup()
    {
        if (is_page('signup') && is_user_logged_in() && !current_user_can('administrator')) {
            wp_redirect(home_url());
            exit;
        }
    }

    /**
     * Redirect logged-out users from the signout page and log them out if necessary
     */
    public function redirect_for_signout()
    {
        if (is_page('signout')) {
            if (! is_user_logged_in()) {
                wp_redirect(home_url('signup'));
                exit;
            }

            wp_logout();
            wp_set_current_user(0);
            wp_redirect(add_query_arg('logout', '1', home_url()));
            exit;
        }
    }
}