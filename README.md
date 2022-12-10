# Gutenberg - ChatGPT integration

Tired of writing your own blog posts? Don't have any inspiration? No more! Use ChatGPT to write your next post. Simply type in your prompt and let the magic of ✨AI✨ do the work for you!

## 📋 TL;DR
> - A small plugin that adds a 'ChatGPT' block to the Gutenberg editor
> - Type in your prompt and it will put the output of ChatGPT in a paragraph for you
> - Requires an OpenAI API key (see [installation](#-installation))

## 🔧 Installation
0. Signup for OpenAI [here](https://beta.openai.com/signup) and request your API key
1. Download and install this plugin
2. Activate the plugin
3. Set the API key through `functions.php`:

```php
gutenbergGPTContainer()->set('openai.api_key', 'your_api_key_here');
```
