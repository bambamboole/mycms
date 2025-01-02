---
title: Connect Laravel to Lokalise the right way
slug: connect-laravel-to-lokalise-the-right-way
excerpt: Learn how to manage your Laravel translations the right way if you are using lokalise.
published_at: 2024-07-01 00:00:00
---

# Introducing Laravel-Lokalise: Streamline Your Laravel Translations with Lokalise

In the ever-expanding global market, providing localized content is paramount for engaging users from different regions.
Using a translation provider like Lokalise ensures that your application can effortlessly manage translations, enabling
you to deliver a personalized experience to your international audience. However, integrating Lokalise with Laravel has
its challenges. That's why I developed [laravel-lokalise](https://github.com/bambamboole/laravel-lokalise), a package
designed to seamlessly sync your Laravel translations with Lokalise, ensuring a smooth and efficient workflow.

## Why Use Laravel-Lokalise?

Lokalise claims to support Laravel translations out of the box, but in reality, it falls short. It does not accommodate
Laravel's placeholders or pluralization. Additionally, Laravel's approach to translation files—utilizing multiple PHP
files per locale with nested keys and a JSON file per locale using the base locale as the key—poses a challenge for
straightforward synchronization. The [laravel-lokalise](https://github.com/bambamboole/laravel-lokalise), package
addresses these gaps, providing an elegant solution that requires no changes to your existing translations or Lokalise
settings. It just works!

## How Does It Work?

Our package is designed to offer the best out-of-the-box experience by performing several key functions:

1. **Separate Checks for JSON and PHP Translations**: The package differentiates between JSON and PHP translation files,
   ensuring accurate processing.
2. **Prefixed Dotted Translation Keys**: Dotted translation keys are prefixed by their respective file names to maintain
   consistency.
3. **Format Conversion for Lokalise Compatibility**: The package dynamically converts Laravel placeholders and
   pluralizations into formats compatible with Lokalise.
4. **Seamless Uploads**: Translations are uploaded to Lokalise effortlessly.
5. **API-Based Downloads**: Since Lokalise converts placeholders to a non-reversible format during downloads, the
   package leverages the translation keys API to fetch keys file-by-file and converts placeholders back to Laravel
   format before dumping them into their respective files.

## Installation

Getting started with laravel-lokalise is straightforward. You can install the package via Composer:

```bash
composer require bambamboole/laravel-lokalise
```

Next, add the following environment variables to your `.env` file:

```bash
LOKALISE_API_TOKEN=your-lokalise-api-token
LOKALISE_PROJECT_ID=your-lokalise-project-id
```

## Usage

Currently, the package is in its early stages of development, meaning it is quite opinionated and not very flexible.
However, it covers the essential functions needed for efficient translation management.

To upload your translations to Lokalise, use the following command:

```bash
php artisan lokalise:upload
```

To download your translations from Lokalise, run:

```bash
php artisan lokalise:download
```

## Conclusion

Managing translations in a Laravel application can be complex, especially when integrating with external services like
Lokalise. The [laravel-lokalise](https://github.com/bambamboole/laravel-lokalise), package simplifies this process,
ensuring your translations are synchronized accurately and efficiently. By bridging the gaps in Lokalise's support for
Laravel, this package helps you maintain a seamless localization workflow. Try it out today and experience the ease of
managing your Laravel translations with Lokalise.

---

Feel free to reach out for any questions or support regarding the package. I am looking forward to hearing your feedback
and continuously improving the integration to meet our localization needs. Happy translating!
