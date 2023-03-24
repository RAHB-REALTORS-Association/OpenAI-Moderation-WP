# OpenAI Moderation WordPress Plugin

This WordPress plugin filters input fields in text areas using the [OpenAI Moderation API](https://platform.openai.com/docs/guides/moderation/overview). It helps ensure that user-submitted content is respectful and allows you to control which content classifications are blocked on your website.

### Forever Free, No Pro Version!

We’re proud to announce that this plugin will always be 100% free, with no plans for a “Pro” or paid version. Our goal is to provide a reliable and accessible solution for the community, and we’re committed to keeping it that way.

### Get It From WordPress.org:
### [wordpress.org/plugins/openai-moderation](https://wordpress.org/plugins/openai-moderation/)
#
## Features

- Configure the OpenAI API key and allowed classifications.
- Enable or disable the plugin easily from the settings page.
- Moderate comments before they are saved in the database.
- Redirect users to a selected page when their comment contains content that violates the disallowed classifications.

## Installation

1. Download the plugin's ZIP file and extract it to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the **Plugins** screen in WordPress.
3. Navigate to the **Settings > OpenAI Moderation** screen to configure the plugin.

## Configuration
![Admin Settings Screen](screenshots/admin_settings.png)

1. Obtain an OpenAI API key by signing up at [beta.openai.com/signup](https://beta.openai.com/signup/), and going to this page: [platform.openai.com/account/api-keys](https://platform.openai.com/account/api-keys).
2. Go to the **Settings > OpenAI Moderation** screen in your WordPress admin area.
3. Enter your OpenAI API key in the **OpenAI API Key** field.
4. Select the content categories you want to block in the **Disallowed Classifications** field.
5. Check the **Enable OpenAI Moderation** checkbox to enable the plugin.
6. Select the page to redirect to in the **Redirect Page** dropdown.
7. Click **Save Changes** to save your settings.

## Usage

Once the plugin is enabled and configured, it will automatically moderate comments on your website. If a user tries to post a comment that contains content that violates the allowed 
classifications, they will be redirected to a page and the comment will not be posted.

You can extend the plugin to moderate other types of content, such as user-submitted posts, by using the appropriate hooks and filters in WordPress.

## Troubleshooting

If the plugin is not working as expected, make sure that your OpenAI API key is valid and that the 'Enable OpenAI Moderation' checkbox is checked in the plugin settings.

## Support and Contributions

While we are thrilled to receive your pull requests and issue reports, please note that we cannot guarantee support or address all feedback. Our team will prioritize security-related reports, as outlined in our [security policy](SECURITY.md). We appreciate your understanding, and we encourage you to contribute to the ongoing improvement of this plugin.

Thank you for using and supporting our plugin!

## License

This plugin is licensed under the GPLv2 license. See the [LICENSE](LICENSE) file for more information.
