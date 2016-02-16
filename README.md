# TAVRO.io

This is the core application for tavro.io

## Setup

* [Vagrant](http://vagrantup.com/downloads) 1.7.4+
* [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
* [Composer](https://getcomposer.org/doc/00-intro.md)
* [Ansible](http://docs.ansible.com/ansible/intro_installation.html)

### Install Homebrew

    $ /usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"

### Install NodeJS + NPM

    $ brew update
    $ brew doctor
    $ export PATH="/usr/local/bin:$PATH"
    $ brew install node
    
#### Install NVM for managing nodejs modules and version dependencies

    $ curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.0/install.sh | bash
    
#### Other useful Homebrew packages, if you're so inclined..

    $ brew install python python3 git vim rbenv ruby-build 

### Install Composer

    $ curl -sS https://getcomposer.org/installer | php
    $ sudo mv composer.phar /usr/local/bin/

### Install Gulp

    $ npm install -g gulp

### Install Compass (used by Gulp)

    $ sudo gem install compass

### Ansible Extras

    $ vagrant plugin install ansible
    $ vagrant plugin install landrush

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

### Scripts

These scripts all require you first login to the VM via SSH:

    $ cd /path/to/tavro
    $ vagrant ssh
    
#### Restore to a Clean Database

    $ sudo bash /var/www/tavro/scripts/reset-clean.sh
   
#### Restore to Development "dummy" data

    $ sudo bash /var/www/tavro/scripts/reset-dev.sh
    