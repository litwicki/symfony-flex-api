# TAVRO.io

This is the core application for tavro.io

## Required Stuff

Clone the Zoadilack dev repo and run the OSX Developer script to install (or update) everything you need.

    $ git clone git@bitbucket.org:zoadilack/zoadilack-scripts.git /usr/public/zoadilack-scripts
    $ sudo bash /usr/public/zoadilack-scripts/osx-dev.sh

### Your Hosts File

Add the following line to your hosts file:

* MAC/Linux: `/etc/hosts`
* Windows: `c:\Windows\System32\Drivers\etc\hosts`

    $ 192.168.50.13      tavro.dev

You can now access your dev machine at [https://tavro.dev](https://tavro.dev)

### Build the machine using Vagrant

    $ cd /path/to/tavro
    $ git clone git@bitbucket.org:zoadilack/tavro.git /path/to/tavro
    $ vagrant up

### Setup Keys for JWT Authorization

**IMPORTANT** 

Whatever you set the passphrase to you will need to also set in `app/config.parameters.yml` for your `jwt_passphrase` value!

    $ mkdir -p /var/www/tavro/api/app/keys
    $ openssl genrsa -out /var/www/tavro/api/app/keys/private.pem -aes256 4096
    $ openssl rsa -pubout -in /var/www/tavro/api/app/keys/private.pem -out /var/www/tavro/api/app/keys/public.pem

## Development

Workflow and useful 'stuff' for developing in Tavro.

### Install Gulp + Dependencies etc.

    $ npm install --global gulp-cli
    $ npm install --save-dev gulp gulp-sass gulp-concat gulp-minify-css fs gulp-s3 gulp-image gulp-util

### Testing with PHPUnit

**NOTE** Tests will be run before every commit regardless!

    $ cd /var/www/tavro/api && phpunit
    
### Testing JWT Token

    $ curl -X POST http://api.tavro.dev/api/v1/auth -d username=tavrobot -d password=Password1!

### Testing CURL Api Request

    $ curl -X POST http://api.tavro.dev/api/v1/expenses -d '{"body":"test", "user":1, "amount":100, "organization": 1, "expense_date": "2016-08-12 01:01:01"}' --header "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0NzExMDg3MTAsInVzZXJuYW1lIjoidGF2cm9ib3QiLCJpYXQiOiIxNDcxMDIyMzEwIn0.NwjMZkfIZvU5XeAXB3ooW_7gSm1o3p3VjL98Tc6qEAZC0dUout3UYZ8O-T6eR0N6bTpDzxRyPtiYx0hr9aqE0J6tBmw5TvN0o6XtxIgknvZgkKddmcQlwYZ_KEh1Pi5daxxZk1xLTzGXzocLOEfTVVHjNZ_sBt6IbWSKkGpygYoIq5hAiuEuUhKRBd-m7baStIjyErP1dHEDuXLmkjxgk_aS_jPjg-0Tm0Vpy8p-iRAmc90UpxsOAIhX9S_8aNED_eq9xyiQhkggq8zHdr_knCctCsKOacZcSdGJy0xcts2oSOmJdW5z2YexjSeToxhaNfMa6PvqkOs2K3S5t9xJNUpTxu69zr2SmE9bSoYA7c1_V0YmwP5vyF4CDlhJrTD68B2tTyDcOw0XUHsZK4rJne9Ddz9FxcImEQ9Y54-TfIYfEDLhm4W97Y4yKufU7goOgrPhBeSwPKlec8yaLnZ4_u1Nb96wgUz6prX5pzL38YrM8y9tXLNQmZkW-MKdD9r1EqnW2WpELpoiYXeWFQJFS_hjlQuZmC-DRjb31YW-Z8Sr2AaBytrxbeRfuQfVE3cbT_x3DzYJzTSw1GPz6Q1hZJym8qBAHFeltF8CRlPIZw4jTN0G_tLGPkQSPUKN6dgaxNodGAz8JQtVtHkV46M7HYc6DDKmr_hvhCgKL_x9u6E"

### Scripts

These scripts all require you first login to the VM via SSH:

    $ cd /path/to/tavro
    $ vagrant ssh
    
#### Restore to a Clean Database

    $ sudo bash /var/www/tavro/scripts/reset-clean.sh
   
#### Restore to Development "dummy" data

    $ sudo bash /var/www/tavro/scripts/reset-dev.sh
    
### Documentation

Regenerate API Documentation:

    $ sudo bash /var/www/tavro/scripts/regenerate-docs.sh
