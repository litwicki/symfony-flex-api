# TAVRO.io

This is the core application for tavro.io

## Setup

* [Vagrant](http://vagrantup.com/downloads) 1.7.4+
* [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
* [Composer](https://getcomposer.org/doc/00-intro.md)
* [Ansible](http://docs.ansible.com/ansible/intro_installation.html)

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

## Scripts

First, we need to setup our ansible environment variables.


    
### Reset Database

    $ ansible-playbook /var/www/tavro/ansible/roles/tavro/tasks/database.yml --extra-vars="dbname={dbname} dbuser={dbuser} dbpass={dbpass} dbhost={dbhost}"
    
### Rebuild an environment with Demo Data

**NOTE** This process takes a substantial amount of time, and will upload 'dummy' images to AWS!

    $ ansible-playbook /var/www/tavro/ansible/roles/tavro/tasks/demo.yml --extra-vars=""