# Digital Nomads Portugal
> Drupal 8 Codebase for Digital Nomads Portugal.

## Installation instructions

To install the site dependencies you have to execute

	composer install

Next, set up your `settings.local.php` file

	cp web/sites/default/example.settings.local.php web/sites/default/settings.local.php

and add your database connection settings. You can also configure your development environment via this file.

You will need a production database from platform.sh to develop locally, see below.

Make sure you serve the site from the `web/` directory.

Likewise, you can create a `drushrc.local.php` based on `example.drushrc.local.php` and set the virtual host address, to let drush know the ip or virtual host address it should use.

## Platform.sh

We use platform.sh for production, and the `master` environment holds a production database.

First, [install `platform` CLI](https://docs.platform.sh/overview/cli.html#how-do-i-get-it), and login

	platform login

(ask Peter for the login details).

You can now get a production database like

	composer import:db

To push code to platform.sh (you should only do this for `master`), you need to set up a new remote and push

	git remote add platform gc4j7g2zzqrry@git.eu.platform.sh:gc4j7g2zzqrry.git
	git push platform master

## Managing a Drupal site built with Composer

Once the site is installed, there is no difference between a site hosted on Platform.sh
and a site hosted anywhere else.  It's just Composer.  See the [Drupal documentation](https://www.drupal.org/node/2404989)
for tips on how best to leverage Composer with Drupal 8.

## Exporting configuration

All configuration is stored in `config/sync`. Use drush to export and import config, like

	drush cex

exports all active storage to file. Note that when you push to platform.sh, all config gets **imported** automatically (see `.platform.app.yml` for the actual drush commands).

To get config locally set up from a remote branch (i.e. rather than getting an up-to-date database dump), you can run

	drush cim
