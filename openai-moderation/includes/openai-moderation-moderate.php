<?php
class OpenAIModeration_Moderate
{
    public function __construct()
    {
        add_filter('preprocess_comment', [$this, 'moderate_comment']);
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

        $allowed_classifications = get_option('openai_classifications');
        $allowed_classifications = array_map('trim', $allowed_classifications);

        $violates_policies = false;
        foreach ($moderation_result['categories'] as $category => $flagged) {
            if ($flagged && in_array($category, $allowed_classifications)) {
                $violates_policies = true;
                break;
            }
        }
        
        if ($violates_policies) {
            $error_page_id = get_option('openai_error_page');
            if ($error_page_id) {
                $error_page_url = get_permalink($error_page_id);
                if ($error_page_url) {
                    wp_safe_redirect($error_page_url);
                    exit;
                }
            }
        }        

        return $comment_data;
    }
}