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

    $ curl -X POST http://api.tavro.dev/api/v1/login_check -d _username=tavrobot -d _password=Password1!

### Testing CURL Api Request

    $ curl -X POST http://api.tavro.dev/api/v1/revenues/1/comments -d '{"body":"test", "user":1}' --header "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0NzA5NDA1MTAsInVzZXJuYW1lIjoidGF2cm9ib3QiLCJpYXQiOiIxNDcwODU0MTEwIn0.LR1gLFrOLGFojWC0EVpGkzQqJ95ukf29FyVSyDJJFAtvEJ039P5MxDs0th0KYrxhhZc379V177ch86mOLPcNuCH0oJ6VGQ2sWTiEQ7Buvkefp3klFhR7XSON0b-1i8lJzpkxvSdYHU5OEivtg7VowwHORhRE95hzindmst9XLRZtkF3kBhksGLhMVKVC1ps3e_Tf1gs1T0vrBjqSGXhaVEq8muekVCywqdyuF5o9Ogm5dF3IMw8vsk3dYJPzndqG0n_u-EJoTYtvqAubODvESGTSn_U_STKCX-LtqRXD4YEkA4teFp1WREDCBUcKxb-3wlfRcDhSijuSffxHAtZFh_AMQRQjBBoE7RhjgTtnBf6voSW6W4eO3cCV3K21XVZ4F9uiD8t0Q5ZBQOG9xOKoTWRv49WuaT3yYhGZ9CaENMtB93LE00iYjxeLqFw-s569osHhwKMv5dC11ZYjKpWoBOOR6ySl-F6-Mnhvmsx3c9iNQ36Yi6yuv78kjy5TT1UgYzz-CjNx-3vHHgADhBleyi9ogIxuxVxGzpwH6xm2ag6Y6WAWGpKtEmLeZi1qfo9cmkETEjhGaWEaAueZDv8yCmFBhPWZdIjYCYv7lpwUCDPU8oqEVRVGiSCpO4--PX2JvXih7cUBUfSFRVxhFyQsbkzEA8IsIQ_VV_ofc47lL9Y"

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
