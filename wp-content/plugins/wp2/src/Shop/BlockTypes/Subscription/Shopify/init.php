<?php

/**
 * 
 * Plugin Name: Shopify Subscriptions
 * 
 * 
 * // You must specify the number of arguments to be accepted (in this case, 2).
 * add_action( 'purchase_notification', 'send_purchase_notification', 10, 2 );
 * 
 * // When scheduling the action, provide the arguments as an array.
 * as_schedule_single_action( time(), 'purchase_notification', array(
 *     'bob@foo.bar',
 *     'Learning Action Scheduler (e-book)',
 * ) );
 * 
 * // Your callback should accept the appropriate number of parameters (again, in this case, 2).
 * function send_purchase_notification( $customer_email, $purchased_item ) {
 *     wp_mail( 
 *         $customer_email,
 *         'Thank you!',
 *         "You purchased $purchased_item successfully."
 *     );
 * }
 * 
 * Function Reference / as_enqueue_async_action()
 * Description
 * Enqueue an action to run one time, as soon as possible.
 * 
 * Usage
 * as_enqueue_async_action( $hook, $args, $group, $unique, $priority );
 * Parameters
 * $hook (string)(required) Name of the action hook.
 * $args (array) Arguments to pass to callbacks when the hook triggers. Default: array().
 * $group (string) The group to assign this job to. Default: '’.
 * $unique (boolean) Whether the action should be unique. Default: false.
 * $priority (integer) Lower values take precedence over higher values. Defaults to 10, with acceptable values falling in the range 0-255.
 * Return value
 * (integer) the action’s ID. Zero if there was an error scheduling the action. The error will be sent to error_log.
 * 
 */

// include scheduled actions


function vsg_shopify_subscriptions_init()
{
    global $shopify_subscriptions;

    add_action('init', [$shopify_subscriptions, 'enqueue_main_js'], 999);

    add_action('init', [$shopify_subscriptions, 'enqueue_styles'], 999);

    add_action('wp_head', [$shopify_subscriptions, 'global_variables'], 99);

    add_action('wp_footer', [$shopify_subscriptions, 'modal_content']);

    add_action('wp_login', [$shopify_subscriptions, 'on_login']);

    add_action('admin_menu', [$shopify_subscriptions, 'admin_menus']);

    add_action('rest_api_init', [$shopify_subscriptions, 'register_endpoints']);

    add_action('plugins_loaded', [$shopify_subscriptions, 'roles']);

    add_action('plugins_loaded', [$shopify_subscriptions, 'capabilities']);

    add_action('init', [$shopify_subscriptions, 'register_shopify_customer_tag_taxonomy']);
}


class VSG_ShopifySubscriptions
{

    public function enqueue_main_js()
    {
        wp_enqueue_script(
            'vsg-shopify-subscriptions-main-js',
            plugins_url('assets/js/main.js', __FILE__),
            [],
            time(),
            true
        );
    }

    public function enqueue_styles()
    {
        wp_enqueue_style('vsg-shopify-subscriptions-frontend', plugin_dir_url(__FILE__) . 'assets/css/main.css', [], time());
        wp_enqueue_block_style('vsg-shopify-subscriptions-blocks', plugin_dir_url(__FILE__) . 'assets/css/main.css', [], time());
    }

    public function user_meta_fields()
    {
        // Register 'shopify_customer' field for users

        register_meta('user', 'shopify_customer_profile', [
            'type' => 'object',
            'description' => 'Shopify Customer Profile',
            'single' => true,
            'show_in_rest' => true,
        ]);

        // Register 'shopify_customer_id' field for users

        register_meta('user', 'shopify_customer_id', [
            'type' => 'string',
            'description' => 'Shopify Customer ID',
            'single' => true,
            'show_in_rest' => true,
        ]);

        // Register 'is_member' field for users

        register_meta('user', 'shopify_membership_status', [
            'type' => 'boolean',
            'description' => 'Membership Status',
            'single' => true,
            'show_in_rest' => true,
        ]);

        // Register 'shopify_membership_state' field for users

        register_meta('user', 'shopify_membership_state', [
            'type' => 'boolean',
            'description' => 'Membership State',
            'single' => true,
            'show_in_rest' => true,
        ]);
    }
    public function modal_content()
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

    public function global_variables()
    {
    ?>
        <script>
            var wp_shopify = {
                userEmail: '<?php echo esc_attr(wp_get_current_user()->user_email); ?>',
                shopToken: '<?php echo esc_attr(rwmb_meta('shopify_shop_token', ['object_type' => 'setting'], 'web-multipliers')); ?>',
                shopDomain: '<?php echo esc_attr(rwmb_meta('shopify_shop_domain', ['object_type' => 'setting'], 'web-multipliers')); ?>',
            };
        </script>
<?php
    }
    // On Login
    public function on_login()
    {
        $user       = wp_get_current_user();
        $user_id    = $user->ID;
        $user_email = $user->user_email;

        if ($user_email) {
            $customer_profile = $this->set_customer_profile($user_id, $user_email);
        }

        if ($customer_profile) {
            return true;
        } else {
            return false;
        }
    }

    public function set_customer_profile($user_id, $user_email)
    {

        $customer_id = $this->get_customer_id_by_email($user_email);

        update_user_meta($user_id, 'shopify_customer_id', $customer_id);

        $customer_profile = $this->get_customer_profile($customer_id);

        update_user_meta($user_id, 'shopify_customer_profile', json_encode($customer_profile));

        if ($customer_profile) {

            $customer_tags = $customer_profile->tags;

            $product_subscriber_status = $customer_profile->productSubscriberStatus;

            $this->set_customer_tags($user_id, $customer_tags);

            $this->set_product_subscriber_status($user_id, $product_subscriber_status);
        }

        return get_user_meta($user_id, 'shopify_customer_profile', true);
    }

    public function set_product_subscriber_status($user_id, $product_subscriber_status)
    {
        update_user_meta($user_id, 'shopify_product_subscriber_status', $product_subscriber_status);
    }

    public function set_customer_tags($user_id, $customer_tags)
    {

        $customer_tags = $customer_tags;

        if (empty($customer_tags)) {

            $new_tags = [];

            update_user_meta($user_id, 'shopify_customer_tags', $new_tags);
        } else {

            $tag_taxonomy = 'shopify-customer-tag';

            // tag all customer tag entries and make into a tag if not already and the return an array of the newly formed slugs

            $new_tags = [];

            foreach ($customer_tags as $tag) {

                $tag_exists = term_exists($tag, $tag_taxonomy);

                if ($tag_exists) {
                    $new_tags[] = $tag;
                } else {
                    if (is_string($tag)) {
                        $tag_id = wp_insert_term($tag, $tag_taxonomy);
                    } else {
                        // Handle the case when $tag is an array
                        $tag_id = wp_insert_term($tag['slug'], $tag_taxonomy);
                    }

                    $new_tags[] = $tag_id['slug'];
                }
            }

            // new tags as string and csv

            // new tags to sring

            $new_tags_string = strval($new_tags);

            $new_tags_string = implode(',', $new_tags);

            update_user_meta($user_id, 'shopify_customer_tags', $new_tags_string);
        }

        $new_tags = get_user_meta($user_id, 'shopify_customer_tags', true);

        return $new_tags;
    }

    public function get_customer_id_by_email($user_email)
    {
        $admin_token = rwmb_meta('shopify_admin_token', ['object_type' => 'setting'], 'web-multipliers');

        $headers = [
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $admin_token,
        ];

        $query = <<<GQL
		query {
			customers(first: 1, query: "email:$user_email") {
				edges {
					node {
						id
					}
				}
			}
		}
		GQL;

        $request_body = json_encode(['query' => $query]);

        $request_args = [
            'body' => $request_body,
            'headers' => $headers,
        ];

        $shop_domain = rwmb_meta('shopify_shop_domain', ['object_type' => 'setting'], 'web-multipliers');

        $response = wp_remote_post('https://' . $shop_domain . '/admin/api/2024-01/graphql.json', $request_args);

        $response_body = wp_remote_retrieve_body($response);

        $response_data = json_decode($response_body, true);

        $customer_id = $response_data['data']['customers']['edges'][0]['node']['id'];

        if ($customer_id) {
            return $customer_id;
        } else {
            return false;
        }
    }

    public function get_customer_profile($customer_id)
    {
        $admin_token = rwmb_meta('shopify_admin_token', ['object_type' => 'setting'], 'web-multipliers');

        $headers = [
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $admin_token,
        ];

        $query = <<<GQL
		query {
			customer(id: "$customer_id") {
				amountSpent {
					amount
					currencyCode
				}
				canDelete
				createdAt
				email
				emailMarketingConsent {
					consentUpdatedAt
					marketingOptInLevel
					marketingState
				}
				id
				image {
					altText
					height
					id
					url
					width
				}
				lifetimeDuration
				locale
				mergeable {
					errorFields
					isMergeable
					mergeInProgress {
						customerMergeErrors {
							errorFields
							message
						}
						jobId
						status
						resultingCustomerId
					}
					reason
				}
				multipassIdentifier
				note
				productSubscriberStatus
				smsMarketingConsent {
					consentCollectedFrom
					consentUpdatedAt
					marketingOptInLevel
					marketingState
				}
				state
				statistics {
					predictedSpendTier
				}
				tags
				taxExempt
				taxExemptions
				unsubscribeUrl
				updatedAt
				validEmailAddress
				verifiedEmail
			}
		}
		GQL;

        $request_body = json_encode(['query' => $query]);

        $request_args = [
            'body' => $request_body,
            'headers' => $headers,
        ];

        $shop_domain = rwmb_meta('shopify_shop_domain', ['object_type' => 'setting'], 'web-multipliers');

        $response = wp_remote_post('https://' . $shop_domain . '/admin/api/2024-01/graphql.json', $request_args);

        $response_body = wp_remote_retrieve_body($response);

        $response_data = json_decode($response_body, true);

        if (!isset($response_data['data'])) {
            return false;
        }

        $customer_profile = $response_data['data']['customer'];

        if (!$customer_profile) {
            return false;
        }
        // validate is json

        $customer_profile = json_encode($customer_profile);

        if (json_last_error() === JSON_ERROR_NONE) {
            $customer_profile = json_decode($customer_profile);
        }

        return $customer_profile;
    }

    public function set_customer_subscription_contracts($user_id, $customer_id)
    {

        $subscription_contracts = $this->get_customer_subscription_contracts($customer_id);

        if ($subscription_contracts) {
            update_user_meta($user_id, 'shopify_subscription_contracts', json_encode($subscription_contracts));
        } else {
            return false;
        }

        return get_user_meta($user_id, 'shopify_subscription_contracts', true);
    }

    public function get_customer_subscription_contracts($customer_id)
    {
        $admin_token = rwmb_meta('shopify_admin_token', ['object_type' => 'setting'], 'web-multipliers');

        $headers = [
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $admin_token,
        ];

        // customer is connected to subscriptionContracts which is A list of the customer's subscription contracts, return array of id and status

        $query = <<<GQL
		query {
			customer(id: "$customer_id") {
				subscriptionContracts(first: 10) {
					edges {
						node {
							id
							status
						}
					}
				}
			}
		}
		GQL;

        $request_body = json_encode(['query' => $query]);

        $request_args = [
            'body' => $request_body,
            'headers' => $headers,
        ];

        $shop_domain = rwmb_meta('shopify_shop_domain', ['object_type' => 'setting'], 'web-multipliers');

        $response = wp_remote_post('https://' . $shop_domain . '/admin/api/2024-01/graphql.json', $request_args);

        $response_body = wp_remote_retrieve_body($response);

        $response_data = json_decode($response_body, true);

        $subscription_contracts = $response_data['data']['customer']['subscriptionContracts']['edges'];

        return $subscription_contracts;
    }

    public function register_endpoints()
    {

        // Subscription Contract Created
        // The Subscription contract created trigger starts a workflow when a user in your organization or a third-party app creates a subscription contract.
        // full url is https://example.com/wp-json/shopify/v1/subscription

        register_rest_route('shopify/v1', '/subscription', [
            'methods' => 'POST',
            'permission_callback' => [$this, 'subscription_permission_callback'],
            'callback' => [$this, 'subscription_contract_created'],
        ]);


        // Subscription Contract Updated
        // The Subscription contract updated trigger starts a workflow when a user in your organization or a third-party app updates a subscription contract.

        register_rest_route('shopify/v1', '/subscription', [
            'methods' => 'PUT',
            'permission_callback' => [$this, 'subscription_permission_callback'],
            'callback' => [$this, 'subscription_contract_updated'],
        ]);
    }

    public function subscription_permission_callback($request)
    {

        $authorization = $request->get_header('Authorization');

        $authorization = explode(' ', $authorization);

        $authorization = $authorization[1];

        $authorization = base64_decode($authorization);

        $auth_parts = explode(':', $authorization);

        $username = $auth_parts[0];

        $password = $auth_parts[1];

        $user = get_user_by('login', $username);

        $authed_user = wp_authenticate_application_password($user, $username, $password);

        if ($authed_user) {
            $authed_user_id = $authed_user->ID;
            $can_manage_options = user_can($authed_user_id, 'manage_options');
        }

        if ($can_manage_options) {
            return new WP_REST_Response(['success' => 'Permission Callback Success'], 200);
        } else {
            return new WP_REST_Response(['error' => 'Permission Callback Error'], 400);
        }
    }

    public function subscription_contract_created(WP_REST_Request $request)
    {
        $request_body = $request->get_body();

        $request_body = json_decode($request_body);

        $request_body->event_type = 'created';

        return $this->set_membership_status($request_body);
    }

    public function subscription_contract_updated(WP_REST_Request $request)
    {
        $request_body = $request->get_body();

        $request_body = json_decode($request_body);

        $request_body->event_type = 'updated';

        return $this->set_membership_status($request_body);
    }

    public function set_membership_status($request_body)
    {

        if (!$request_body) {
            return new WP_REST_Response(['error' => 'No Request Body'], 400);
        }

        $customer_email = $request_body->customer_email;

        if (!$customer_email) {
            return new WP_REST_Response(['error' => 'No Customer Email'], 400);
        }

        $user = get_user_by('email', $customer_email);

        if (!$user) {
            $user_id = wp_create_user($customer_email, wp_generate_password(), $customer_email);
        } else {
            $user_id = $user->ID;
        }

        if (!$user_id) {
            return new WP_REST_Response(['error' => 'No User ID'], 400);
        }

        $lines = $request_body->lines;

        if (!$lines) {
            return new WP_REST_Response(['error' => 'No Lines'], 400);
        }

        $has_product = false;

        foreach ($lines as $line) {
            if (strpos($line->product_id, '8505473040601') !== false) {
                $has_product = true;
            }
        }

        if (!$has_product) {
            return new WP_REST_Response(['error' => 'No Membership Product'], 400);
        }

        $subscription_contract_status = $request_body->subscription_contract_status;

        if (!$subscription_contract_status) {
            return new WP_REST_Response(['error' => 'No Subscription Contract Status'], 400);
        }

        $membership_state = 0;

        // ACTIVE
        // The contract is active and continuing per its policies.

        // CANCELLED
        // The contract was ended by an unplanned customer action.

        // EXPIRED
        // The contract has ended per the expected circumstances. All billing and deliverycycles of the subscriptions were executed.

        // FAILED
        // The contract ended because billing failed and no further billing attempts are expected.

        // PAUSED
        // The contract is temporarily paused and is expected to resume in the future.

        switch ($subscription_contract_status) {
            case 'ACTIVE':
                $membership_state = 1;
                break;
            case 'CANCELLED':
                $membership_state = 0;
                break;
            case 'EXPIRED':
                $membership_state = 0;
                break;
            case 'FAILED':
                $membership_state = 0;
                break;
            case 'PAUSED':
                $membership_state = 0;
                break;
            default:
                $membership_state = 0;
                break;
        }

        if ($has_product) {
            update_user_meta($user_id, 'shopify_membership_status', $subscription_contract_status);
            update_user_meta($user_id, 'shopify_membership_state', $membership_state);

            if ($membership_state) {
                //
                $user->add_role('member');
            } else {
                $user->remove_role('member');
            }
        }

        $customer_profile = $this->set_customer_profile($user_id, $customer_email);

        if ($customer_profile) {
            return new WP_REST_Response(['success' => 'Membership Updated'], 200);
        } else {
            return new WP_REST_Response(['error' => 'Membership Update Failed'], 400);
        }
    }

    public function admin_menus()
    {
        add_submenu_page(
            'users.php',
            'Customer Tags',
            'Customer Tags',
            'manage_options',
            'edit-tags.php?taxonomy=shopify-customer-tag'
        );
    }

    public function capabilities()
    {
        $role = get_role('member');

        $role->add_cap('read');
    }

    public function roles()
    {
        add_role('member', 'Member', [
            'read' => true,
            'read_ad_free' => true,
        ]);
    }


    public function register_shopify_customer_tag_taxonomy()
    {
        $labels = [
            'name'                       => esc_html__('Tags', ''),
            'singular_name'              => esc_html__('Tag', ''),
            'menu_name'                  => esc_html__('Tags', ''),
            'search_items'               => esc_html__('Search Tags', ''),
            'popular_items'              => esc_html__('Popular Tags', ''),
            'all_items'                  => esc_html__('All Tags', ''),
            'parent_item'                => esc_html__('Parent Tag', ''),
            'parent_item_colon'          => esc_html__('Parent Tag:', ''),
            'edit_item'                  => esc_html__('Edit Tag', ''),
            'view_item'                  => esc_html__('View Tag', ''),
            'update_item'                => esc_html__('Update Tag', ''),
            'add_new_item'               => esc_html__('Add New Tag', ''),
            'new_item_name'              => esc_html__('New Tag Name', ''),
            'separate_items_with_commas' => esc_html__('Separate tags with commas', ''),
            'add_or_remove_items'        => esc_html__('Add or remove tags', ''),
            'choose_from_most_used'      => esc_html__('Choose most used tags', ''),
            'not_found'                  => esc_html__('No tags found.', ''),
            'no_terms'                   => esc_html__('No tags', ''),
            'filter_by_item'             => esc_html__('Filter by tag', ''),
            'items_list_navigation'      => esc_html__('Tags list pagination', ''),
            'items_list'                 => esc_html__('Tags list', ''),
            'most_used'                  => esc_html__('Most Used', ''),
            'back_to_items'              => esc_html__('&larr; Go to Tags', ''),
            'text_domain'                => esc_html__('', ''),
        ];
        $args = [
            'label'              => esc_html__('Tags', ''),
            'labels'             => $labels,
            'description'        => '',
            'public'             => true,
            'publicly_queryable' => true,
            'hierarchical'       => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_nav_menus'  => true,
            'show_in_rest'       => true,
            'show_tagcloud'      => true,
            'show_in_quick_edit' => true,
            'show_admin_column'  => false,
            'query_var'          => true,
            'sort'               => false,
            'meta_box_cb'        => 'post_tags_meta_box',
            'rest_base'          => '',
            'rewrite'            => [
                'with_front'   => false,
                'hierarchical' => false,
            ],
        ];
        register_taxonomy('shopify-customer-tag', [], $args);
    }
}
