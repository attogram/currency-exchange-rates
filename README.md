# Attogram Currency Exchange Rates

[![Maintainability](https://api.codeclimate.com/v1/badges/c93e67dac8f094b3608f/maintainability)](https://codeclimate.com/github/attogram/currency-exchange-rates/maintainability)
[![Latest Stable Version](https://poser.pugx.org/attogram/currency-exchange-rates/v/stable)](https://packagist.org/packages/attogram/currency-exchange-rates)

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
* make directory writeable: `db/`
* point web server to `public/` directory

## Admin

### Update via web

* The `/admin/` page has a list of feeds.
  click a feed to update data.

### Upate via command line

* CLI script `cli/update.php`
* usage:  `php update.php FeedCode`

## Open Source

_Attogram Currency Exchange Rates_ is an Open Source project
brought to you by the [Attogram Project](https://github.com/attogram).
