<?php
class OpenAIModeration_Utils
{
    public static function load_text_domain()
    {
        load_plugin_textdomain('openai-moderation', false, basename(dirname(__FILE__)) . '/languages');
    }
}