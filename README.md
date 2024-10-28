# The source code of randallwilk.dev

[![Tests](https://github.com/rawilk/randallwilk.dev/actions/workflows/pest.yml/badge.svg)](https://github.com/rawilk/randallwilk.dev/actions/workflows/pest.yml)
[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F89c6d447-9e87-4a50-9508-e7a4baaf7461%3Fdate%3D1%26label%3D1%26commit%3D1&style=flat-square)](https://forge.laravel.com/servers/855537/sites/2513450)

This repo contains the source code of [my personal website](https://randallwilk.dev).

# Local Development

To work on this project on your local machine, you may follow the instructions below. These instructions
assume you are serving the site using Laravel Valet out of your `~/Sites` directory:

1. Fork this repository
2. Open your terminal and `cd` to your `~/Sites` folder
3. Clone your fork into the `~/Sites/randallwik.dev` folder, by running the following command *with your username placed
   into the {username} slot*:
    ```bash
    git clone git@github.com:{username}/randallwilk.dev randallwilk.dev
    ```
4. CD into the new directory you just created:
    ```bash
    cd randallwilk.dev
    ```
5. Run the `setup.sh` bin script, which will take all the steps necessary to prepare your local install:
    ```bash
    ./bin/setup.sh
    ```
6. You should also open up a `php artisan tinker` session and create an admin user so the admin panel can be accessed.

# Syncing Upstream Changes Into Your Fork

This [GitHub article](https://help.github.com/en/articles/syncing-a-fork) provides instructions on how to pull the
latest changes from this repository into your fork.

# Updating After Remote Code Changes

If you pull down the upstream changes from this repository into your local repository, you'll want to update your
Composer and NPM dependencies. For convenience, you may run the `bin/update.sh` script to update these things:

```bash
./bin/update.sh
```

# TODO:

- Cleanup language lines
