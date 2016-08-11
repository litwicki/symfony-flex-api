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

    $ curl -X POST http://api.tavro.dev/api/v1/expenses -d '{"body":"test", "user":1, "amount":100, "organization": 1}' --header "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJleHAiOjE0NzEwMzM3NDIsInVzZXJuYW1lIjoidGF2cm9ib3QiLCJpYXQiOiIxNDcwOTQ3MzQyIn0.S0o9r1BGxu_WvrJBivGXpvQUt6-uxbVtIjgJNZiOnUWW1ntY5Ua3YOFnjH5E61FOrylmqubkFHA3yphnTWCxloI_y8vqz-QMgf1FdIhfRckUWR8lGm0AM-yEDV43CkqZYbXnV1DlO4RJvmQkfZfeYBolJ5ZMmnhxq81jQOfio8bkMFRecyiacLu9ziYjLFon6LMVT90tuIm1b4BorVfMUUQ9iCLhgWGY-k7rHzevOXjoQBojebvhgN3rNw4uZFWCHjIbgqQh55opZpxNifxVf9hsUAieiHlO9KA0-5FDeQG6MdsLj0Yexi8FjNiphCzuM6-OqRnJeUeiHx-94y4RcB7_RsGQWnoveg0tfyRP_ueMwqDft_uHErGWWTVFDZnlVoqHT5QrRmhBpbzoDPpQ8xICkapPMSCP-YC_fWiYIbTWUSlcpg7x4-X05pY10q3R9_9EsN8MfgV_0tS9R_MQ564YXKXCNKoHMkXSWWVqhWx4XH9WCSL_cBcQyEmptvBFIpaSikXxC51OBFsEnr6KBG9SfLE42Gfv1tsCtJNlcbPlHJi57IvRBjB5sAU-cyfD6P2K2eT3jWW76yCO5XjmjwoaGQosdyxLx2q54HHMn_2I7Ca_CSKDoDXHyO9oHVjkddO6AtwxHGHRN11cedZZramixDus5dhbQQ71B0ELXUY"

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
