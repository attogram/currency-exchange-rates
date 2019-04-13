# Attogram Currency Exchange Rates

Beta Release
[![Maintainability](https://api.codeclimate.com/v1/badges/c93e67dac8f094b3608f/maintainability)](https://codeclimate.com/github/attogram/currency-exchange-rates/maintainability)

Currency Exchange Rates Website with data from:

* The European Central Bank
* The Swiss National Bank
* The Bank of Israel
* The Central Bank of the Russian Federation

Live Demo: **<https://getitdaily.com/rates/>**

## Info

* Stack: PHP 7, SQLite
* Git: <https://github.com/attogram/currency-exchange-rates>
* Composer: <https://packagist.org/packages/attogram/currency-exchange-rates>

## Install

### Install with Composer

* `composer create-project attogram/currency-exchange-rates your-install-directory`

### Install with Git

* `git clone https://github.com/attogram/currency-exchange-rates.git your-install-directory`
* `cd your-install-directory`
* `composer install`

### Install manually

* Download source at <https://github.com/attogram/currency-exchange-rates/archive/master.zip>
* unzip to your-install-directory
* `cd your-install-directory`
* `composer install`

## Setup

* create custom config: `cp custom/config.example.php custom/config.php`
  * modify: Site Title and Administrator IP
* point web server to `public/` directory

## Open Source

_Currency Exchange Rates_ is an Open Source project
brought to you by the [Attogram Project](https://github.com/attogram).
