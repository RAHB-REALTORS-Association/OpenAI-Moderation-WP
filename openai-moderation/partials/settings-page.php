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
            <tr>
                <th scope="row">
                    <label for="openai_error_page"><?php _e('Error Page', 'openai-moderation'); ?></label>
                </th>
                <td>
                    <?php
                    $selected_page_id = get_option('openai_error_page');
                    wp_dropdown_pages(array(
                        'name' => 'openai_error_page',
                        'id' => 'openai_error_page',
                        'selected' => $selected_page_id,
                        'show_option_none' => __('- Select a page -', 'openai-moderation'),
                        'option_none_value' => ''
                    ));
                    ?>
                    <p><?php _e('Select a page to redirect users when their comment violates the policies.', 'openai-moderation'); ?></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>