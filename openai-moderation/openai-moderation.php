<?php
/*
Plugin Name: OpenAI Moderation
Plugin URI: https://github.com/RAHB-REALTORS-Association/OpenAI-Moderation-WP
Description: A simple plugin that filters input fields in text areas using the <a href="https://platform.openai.com/docs/guides/moderation/overview" target="_blank">OpenAI Moderation API</a>.
Version: 1.2
Author: RAHB
Author URI: https://github.com/RAHB-REALTORS-Association
License: GPLv2
Text Domain: openai-moderation
*/

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Define the plugin path.
define('OPENAI_MODERATION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('OPENAI_MODERATION_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include the plugin's files.
require_once OPENAI_MODERATION_PLUGIN_DIR . 'includes/openai-moderation-settings.php';
require_once OPENAI_MODERATION_PLUGIN_DIR . 'includes/openai-moderation-moderate.php';
require_once OPENAI_MODERATION_PLUGIN_DIR . 'includes/openai-moderation-utils.php';

class OpenAIModeration
{
    public function __construct()
    {
        add_action('plugins_loaded', [OpenAIModeration_Utils::class, 'load_text_domain']);
        new OpenAIModeration_Settings();
        new OpenAIModeration_Moderate();
    }
}

new OpenAIModeration();