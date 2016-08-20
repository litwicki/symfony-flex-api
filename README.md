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

    $ curl -X POST http://api.tavro.dev/api/v1/people -d '{"first_name": "John", "last_name": "Doe", "email": "johndoe@example.com"}' --header "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJ1c2VybmFtZSI6InRhdnJvYm90IiwiaWF0IjoiMTQ3MTYyNjQwNyJ9.GXpnUrnCu_cFZpoKgNRkgQJjY-lyZGL9i3Ck6R68ne6QHmYyvAtOETNf2n_NzsbQPzHiRJwEp2FyIDDo2Wt-ZrwFlKYDwS6G94y9Lwd0-PGrROYwvNem8ix0dKxPltk0ZQuj5WYGrRHMZSQpoyrkkQTH3dpQOPuF4ogICfC6aCS_f2DKkszsKq4xtHZcFWwxLdg0kxHVDj1UzRayrQl-M7IMZidZU-4A9vKWv80qEVJ0vKw6IC0tH2_dHFAdWlAohzBXvXDc38hpaRhVOkFDyV_x8d5-8ll3tdknuERnrA_NFxUkPkGlqelbJWwBhfFWH78ErkwWk8KjzvH0XF_Xf4vgp-JpyVp7SFy7_MY6nE8tDBXpnToibNn5bv4C4pYCZR4BavX4YmBdFCPdX323v3SUs866IPNOpYpd3fTZgEVywXXPixC_AavWMdJ_KhLj4MvaVuqkiCMZkgx8IQMCZinCAL5AEtTLvPWz5wB7MIyFsT38acRpaj6_HRyWO2A0QMM-SRRbpBBglBhtUbUctAMyl4bJYMr_9Wt-AAVotHu1LylLdhB0vwm86LPTXLo6rpDgM0zpg7PCIgT717ApMo4E3cu890g9gZI2YNxgmEKgqNXvC--vP7py97g-9flHziNzHiEPXALtnXrr2ZQ4UEKHb7batLKsGmOTgGbFEK8"

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
