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

## Development

Workflow and useful 'stuff' for developing in Tavro.

### Install Gulp + Dependencies etc.

    $ npm install --global gulp-cli
    $ npm install --save-dev gulp gulp-sass gulp-concat gulp-minify-css fs gulp-s3 gulp-image gulp-util

    
### Testing with PHPUnit

    $ cd /var/www/tavro/api && phpunit
    
### Testing JWT Token

    $ curl -X POST http://api.tavro.dev/api/login_check -d _username=tavrobot -d _password=Password1!

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
