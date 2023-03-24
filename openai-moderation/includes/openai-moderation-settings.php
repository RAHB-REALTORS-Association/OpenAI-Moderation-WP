<?php
class OpenAIModeration_Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_settings_submenu']);
        add_action('admin_init', [$this, 'register_settings']);
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
        register_setting('openai-moderation', 'openai_error_page');
    }

    public function settings_page()
    {
        $allowed_classifications_options = array(
            'hate' => __('Hate', 'openai-moderation'),
            'hate/threatening' => __('Hate/Threatening', 'openai-moderation'),
            'self-harm' => __('Self-Harm', 'openai-moderation'),
            'sexual/minors' => __('Sexual/Minors', 'openai-moderation'),
            'violence' => __('Violence', 'openai-moderation'),
            'violence/graphic' => __('Violence/Graphic', 'openai-moderation'),
        );

        $stored_classifications = get_option('openai_classifications', array());
        include OPENAI_MODERATION_PLUGIN_DIR . 'partials/settings-page.php';
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