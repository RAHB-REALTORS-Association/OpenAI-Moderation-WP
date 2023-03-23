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
                            <input type="text" name="openai_api_key" id="openai_api_key" value="<?php echo esc_attr(get_option('openai_api_key')); ?>" /><br />
                            <p>You can obtain an API key <a href="https://platform.openai.com/account/api-keys" target="_blank">here</a>.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Disallowed Classifications', 'openai-moderation'); ?></th>
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
                            <label for="openai_plugin_enabled"><?php _e('Enable Moderation', 'openai-moderation'); ?></label>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
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