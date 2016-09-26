# TAVRO.io

This is the core application for tavro.io

## Required Stuff

Clone the Zoadilack dev repo and run the OSX Developer script to install (or update) everything you need.

    $ git clone git@bitbucket.org:zoadilack/zoadilack-scripts.git /usr/public/zoadilack-scripts
    $ sudo bash /usr/public/zoadilack-scripts/osx-dev.sh
    
    $ sudo vagrant plugin install vagrant-vbguest

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

### Install Frontend Dev Tools

    $ npm install npm -g
    $ brew install watchman

### Testing with PHPUnit

Unit tests are executed using PHPUnit, and coverage analysis report is available at [phpunit.tavro.dev](http://phpunit.tavro.dev)

    $ php symfony tavro:testing
    
### Setting up Keys for JWT

    $ mkdir -p app/keys
    $ openssl genrsa -out app/keys/private.pem -aes256 4096
    $ openssl rsa -pubout -in app/keys/private.pem -out app/keys/public.pem
    
### Testing JWT Token

    $ curl -X POST http://api.tavro.dev/api/v1/auth -d username=tavrobot -d password=Password1!

### Sample CURL Api Request

Replace `{JWT_TOKEN_PAYLOAD}` with the token response from the JWT Token Request (above)

    $ curl -X POST http://api.tavro.dev/api/v1/users -d '{"first_name": "John", "last_name": "Doe", "email": "johndoe@example.com"}' --header "Authorization: Bearer {JWT_TOKEN_PAYLOAD}"

### Testing with Postman

#### Sample "Login" (Get a JWT Token)
![alt text](http://i.imgur.com/LJ5PfHz.png "Postman Auth Request")

#### Sample User `GET` Request
![alt text](http://i.imgur.com/UGzBx0V.png "Postman User Request")

### Provisioning

These scripts are to be executed on specific servers. You can provision a new server, setup a demo app, or simply reinstall a clean local instance.

`{ENVIRONMENT_NAME}` should be replaced with the environment you wish to execute upon.

#### Install Clean Demo Environment

    $ ansible-playbook -i /path/to/tavro/provisioning/inventories/{ENVIRONMENT_NAME} /path/to/tavro/provisioning/install-demo.yml

#### Install Fresh Application (Core Only)

    $ ansible-playbook -i /path/to/tavro/provisioning/inventories/{ENVIRONMENT_NAME} /path/to/tavro/provisioning/install.yml

#### ** USE WITH CAUTION FOR DEVELOPMENT PURPOSES ONLY!! ***

This will rebase the migrations and schema of Tavro from the specified environment database.

    $ ansible-playbook -i /path/to/tavro/provisioning/inventories/{ENVIRONMENT_NAME} /path/to/tavro/provisioning/rebase.yml
    
### Documentation

Regenerate API Documentation:

    $ sudo bash /var/www/tavro/scripts/regenerate-docs.sh
