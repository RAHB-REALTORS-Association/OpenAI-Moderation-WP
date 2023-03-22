<?php
/*
Plugin Name: OpenAI Moderation
Plugin URI: https://github.com/RAHB-REALTORS-Association/OpenAI-Moderation-WP
Description: A simple plugin that filters input fields in text areas using the <a href="https://platform.openai.com/docs/guides/moderation/overview" target="_blank">OpenAI Moderation API</a>.
Version: 1.1
Author: RAHB
Author URI: https://github.com/RAHB-REALTORS-Association
License: GPLv2
Text Domain: openai-moderation
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/openai-moderation-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/openai-moderation-moderate.php';
require_once plugin_dir_path(__FILE__) . 'includes/openai-moderation-utils.php';

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