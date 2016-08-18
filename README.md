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

    $ curl -X GET http://api.tavro.dev/api/v1/users/1 --header "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJ1c2VybmFtZSI6InRhdnJvYm90IiwiaWF0IjoiMTQ3MTQ5NjE0NyJ9.o5LQfiWvEim8HhCwCIH7uTOoJEuRWspfX2bNaEu0EfHRf9Uj-UzM7z7dgKd2z7r9m9uVkM4kU5D7z4fYSTC5NFEXZXdWq9IVvqWnqbGYZVwWC1pfOrc5-TtpxexILd0HsP3DiuvgnQuoKnADIfKYG1RrCWRdwhvSvOAsjHGkFwHF1bpoY054QksvualyJqVZ1on8985rgfJ3BedTDjdv4JC1g0pJ99vVK-2UadricrwNLHwiNaoktah-smIMdfQPZUDpii65da0E906ciHIX0wtqntYZkt3fpzLCCMs3WezkqurUJJyed0ylg-e4F1lxrdW-4QOjunQ-FBMPZe2d2U3zPEHz45LB4LaGZO-M8kJ0pxm-kD5PPHiPf_AgOg-JmGfFYqQLXT9nYtKsG_35W4IIww-rrwcG_S0Dej18oH0TJI_IzkHegb2ckG3wjcI3JkvrOCs4dduHy4SYFFMoZOtweYZ7LeRy3VVZDIdZDwggoWB0okWBh6P9BMBuLb41YPQHAJ7QCRfA_a2B-8oElXXJTTaa-rskRTDMhuE-oKGBWyhU45E_fVk-dKmjNKxS5ui6YaNQp9Wsy8VO1PU_a6RXh5_fqSZ3YgjTM6pvIFsY9WHf3SIZdBeKJ4wjsGiviZhqh3q2W84bCwyj2MCkJ7b3Hfal8JRas9aNBC8g6Uk"

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
