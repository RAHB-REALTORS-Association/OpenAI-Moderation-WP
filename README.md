# OpenAI Moderation WordPress Plugin

This WordPress plugin filters input fields in text areas using the OpenAI Moderation API. It helps ensure that the content complies with OpenAI's usage policies and allows you to 
control which content categories are allowed on your website.

## Features

- Configure the OpenAI API key and allowed classifications.
- Enable or disable the plugin easily from the settings page.
- Moderate comments before they are saved in the database.
- Show an error message to users when their comment contains content that violates the allowed classifications.

## Installation

1. Download the plugin's ZIP file and extract it to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to the 'Settings' > 'OpenAI Moderation' screen to configure the plugin.

## Configuration

1. Obtain an OpenAI API key by signing up at https://beta.openai.com/signup/.
2. Go to the 'Settings' > 'OpenAI Moderation' screen in your WordPress admin area.
3. Enter your OpenAI API key in the 'OpenAI API Key' field.
4. Select the content categories you want to allow in the 'Allowed Classifications' field.
5. Check the 'Enable OpenAI Moderation' checkbox to enable the plugin.
6. Click 'Save Changes' to save your settings.

## Usage

Once the plugin is enabled and configured, it will automatically moderate comments on your website. If a user tries to post a comment that contains content that violates the allowed 
classifications, they will see an error message and the comment will not be posted.

You can extend the plugin to moderate other types of content, such as user-submitted posts, by using the appropriate hooks and filters in WordPress.

## Troubleshooting

If the plugin is not working as expected, make sure that your OpenAI API key is valid and that the 'Enable OpenAI Moderation' checkbox is checked in the plugin settings.

## Contributing

If you would like to contribute to the development of this plugin, feel free to submit a pull request or create an issue in the GitHub repository.

## License

This plugin is licensed under the MIT license. See the LICENSE file for more information.
