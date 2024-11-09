# The source code of randallwilk.dev

[![Tests](https://github.com/rawilk/randallwilk.dev/actions/workflows/pest.yml/badge.svg?branch=develop)](https://github.com/rawilk/randallwilk.dev/actions/workflows/pest.yml)
[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F2154a8d9-deed-48ab-ad0d-c8ff49b46bf4%3Fdate%3D1%26label%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/855537/sites/2525798)

This repo contains the source code of [my personal website](https://randallwilk.dev).

# Local Development

All development should be done on or PR'd to the `develop` branch. Treat the `main` and `stage` branches as readonly.

To work on this project on your local machine, you may follow the instructions below. These instructions
assume you are serving the site using Laravel Valet or Herd out of your `~/Sites` directory:

## Requirements

Be sure the following are installed on your machine:

- [Composer](https://getcomposer.org/download/)
- PHP >= 8.3
- PostgreSQL >= 16
- Node >= 18
- Redis for queues/cache
- (Optional) A GitHub OAuth app for your local installation for social login

## Installation

```bash
cd ~/Sites

# Clone your forked version - replace {username} with your username
git clone git@github.com:{username}/randallwilk.dev randallwilk.dev

cd randallwilk.dev

# Run the bash setup script
./bin/setup.sh
```

> **Note:** Be sure to fill out GitHub credentials in the `.env` to import docs (optional).

> **Note:** The `repositories` database table is only populated by running the `php artisan import:github-repositories` command, and the repositories pulled in are based on your GitHub credentials in the `.env` file.

## Docs

Docs can be imported from the artisan command, as long as you have Laravel Horizon running and the credentials for the GitHub api filled in. It also requires you to have read access to each of the repositories that have docs (I don't think that should be an issue since they're all public).

```bash
php artisan import:docs
```

# Syncing Upstream Changes Into Your Fork

This [GitHub article](https://help.github.com/en/articles/syncing-a-fork) provides instructions on how to pull the
latest changes from this repository into your fork.

# Updating After Remote Code Changes

If you pull down the upstream changes from this repository into your local repository, you'll want to update your
Composer and NPM dependencies. For convenience, you may run the `bin/update-deps.sh` script to update these things:

```bash
./bin/update-deps.sh
```

# Deployment

Deployments for the `main` and `stage` branch are handled through GitHub actions that will handle generating a fresh copy of the `.env` file with secrets from my 1Password vault. The action will send the fresh copy to the server and trigger a deployment through Forge.

To make updating these two branches easy, the `bin/publish.sh` script should be used, so there is no need to PR to these branches.

```bash
./bin/publish.sh
```

# Commands

| Command | Description                                                                                    |
| --- |------------------------------------------------------------------------------------------------|
| `npm run dev` | Watch for changes in CSS and JS files                                                          |
| `php artisan test` | Run tests                                                                                      |
| `php artisan import:docs` | Import doc files                                                                               |
| `php artisan import:github-repositories` | Sync public repo info with GitHub                                                              |
| `php artisan import:packagist-downloads` | Sync download stats for composer packages                                                      |
| `php artisan sitemap:generate` | Regenerate sitemaps                                                                            |
| `php artisan app:refresh-staging-data` | Sync staging database with production; only available to run in production environment         |
| `php artisan app:redact-sensitive-data` | Redact sensitive information from certain tables; not allowed to run in production environment |

# Credits

This website was principally designed and developed by [Randall Wilk](https://github.com/rawilk).

# License

- The web application falls under the [MIT License](https://choosealicense.com/licenses/mit/)
- The content and design are under [exclusive copyright](https://choosealicense.com/no-license/)

If you'd like to reuse or repost something, feel free to reach out at randall@randallwilk.dev. Please remember that the design is not meant to be forked!
