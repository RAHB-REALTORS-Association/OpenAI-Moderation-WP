<?php
/*
Plugin Name: OpenAI Moderation
Plugin URI: https://github.com/RAHB-REALTORS-Association/openai-moderation-wp
Description: A simple plugin that filters input fields in text areas using the OpenAI Moderation API.
Version: 1.0
Author: RAHB
Author URI: https://www.rahb.ca/
License: GPLv2
Text Domain: openai-moderation
*/

if (!defined('ABSPATH')) {
    exit;
}

class OpenAIModeration
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_settings_submenu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('plugins_loaded', [$this, 'load_text_domain']);
        add_filter('preprocess_comment', [$this, 'moderate_comment']);
    }

    public function load_text_domain()
    {
        load_plugin_textdomain('openai-moderation', false, basename(dirname(__FILE__)) . '/languages');
    }

    public function register_settings_submenu()
    {
        add_options_page(
            __('OpenAI Moderation', 'openai-moderation'),
            __('OpenAI Moderation', 'openai-moderation'),
            'manage_options',
            'openai-moderation',
            [$this, 'settings_page']
        );
    }

    public function register_settings()
    {
        register_setting('openai-moderation', 'openai_api_key');
        register_setting('openai-moderation', 'openai_classifications', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_classifications')
        ));
        register_setting('openai-moderation', 'openai_plugin_enabled');
    }

    public function settings_page() {
        $allowed_classifications_options = array(
            'hate' => __('Hate', 'openai-moderation'),
            'hate/threatening' => __('Hate/Threatening', 'openai-moderation'),
            'self-harm' => __('Self-Harm', 'openai-moderation'),
            'sexual/minors' => __('Sexual/Minors', 'openai-moderation'),
            'violence' => __('Violence', 'openai-moderation'),
            'violence/graphic' => __('Violence/Graphic', 'openai-moderation'),
        );

        $stored_classifications = get_option('openai_classifications', array());
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('openai-moderation');
                do_settings_sections('openai-moderation');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="openai_api_key"><?php _e('OpenAI API Key', 'openai-moderation'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="openai_api_key" id="openai_api_key" value="<?php echo esc_attr(get_option('openai_api_key')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Allowed Classifications', 'openai-moderation'); ?></th>
                        <td>
                            <?php foreach ($allowed_classifications_options as $classification_key => $classification_label): ?>
                                <input type="checkbox" name="openai_classifications[]" id="openai_classifications_<?php echo $classification_key; ?>" value="<?php echo 
esc_attr($classification_key); ?>" <?php checked(in_array($classification_key, $stored_classifications), true); ?> />
                                <label for="openai_classifications_<?php echo $classification_key; ?>"><?php echo esc_html($classification_label); ?></label><br />
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php _e('Enable Plugin', 'openai-moderation'); ?>
                        </th>
                        <td>
                            <input type="checkbox" name="openai_plugin_enabled" id="openai_plugin_enabled" value="1" <?php checked(1, get_option('openai_plugin_enabled'), true); ?> />
                            <label for="openai_plugin_enabled"><?php _e('Enable OpenAI Moderation', 'openai-moderation'); ?></label>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function moderate_content($content)
    {
        $api_key = get_option('openai_api_key');
        if (!$api_key || !get_option('openai_plugin_enabled')) {
            return false;
        }

        $url = 'https://api.openai.com/v1/moderations';
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_key
        );
        $body = array(
            'input' => $content
        );

        $response = wp_remote_post($url, array(
            'headers' => $headers,
            'body' => json_encode($body),
            'timeout' => 30
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        if (!$response_body || !isset($response_body['results']) || !isset($response_body['results'][0])) {
            return false;
        }

        $moderation_result = $response_body['results'][0];
        return $moderation_result;
    }

    public function moderate_comment($comment_data)
    {
        $content = $comment_data['comment_content'];
        $moderation_result = $this->moderate_content($content);

        if (!$moderation_result || !$moderation_result['flagged']) {
            return $comment_data;
        }

        $allowed_classifications = explode(',', get_option('openai_classifications'));
        $allowed_classifications = array_map('trim', $allowed_classifications);

        foreach ($moderation_result['categories'] as $category => $flagged) {
            if ($flagged && !in_array($category, $allowed_classifications)) {
                wp_die(__('Your comment could not be posted as it contains content that violates our policies.', 'openai-moderation'));
            }
        }

        return $comment_data;
    }

    public function sanitize_classifications($classifications)
    {
        if (!is_array($classifications)) {
            return array();
        }

        $allowed_classifications = array(
            'hate', 'hate/threatening', 'self-harm','sexual/minors', 'violence', 'violence/graphic'
        );

        return array_values(array_filter($classifications, function ($classification) use ($allowed_classifications) {
            return in_array($classification, $allowed_classifications, true);
        }));
    }
}

new OpenAIModeration();
