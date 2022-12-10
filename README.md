# Gutenberg - ChatGPT integration

Tired of writing your own blog posts? Don't have any inspiration? No more! Use ChatGPT to write your next post. Simply type in your prompt and let the magic of ✨AI✨ do the work for you!

## 📋 TL;DR
> - A small plugin that adds a 'ChatGPT' block to the Gutenberg editor
> - Type in your prompt and it will put the output of ChatGPT in a paragraph for you
> - Requires an OpenAI API key (see [installation](#-installation))

## 🔧 Installation
0. Signup for OpenAI [here](https://beta.openai.com/signup) and request your API key
1. Download the latest release from [the releases page](https://github.com/sanderdekroon/Gutenberg-ChatGPT/releases) and install this plugin
2. Activate the plugin
3. Set your API key by adding a line of code (for example in your `functions.php`):

```php
gutenbergGPTContainer()->set('openai.api_key', 'your_api_key_here');
```

## 🧑‍💻 Development
Want to contribute? Go ahead and create a pull request to submit your changes. To get setup, you will need to download [Composer](https://getcomposer.org/) and [NPM](https://www.npmjs.com/).

1. Clone this repository to your machine and/or WordPress installation
2. Use Composer (`composer install`) and NPM (`npm i`) to install the required dependencies
3. Run `npm run watch` to automatically rebuild assest whenever a file changes or run `npm run build` for a one-off build. 

To create an optimized and zipped build, run the `package.sh` script. This also requires Composer and NPM to run.


