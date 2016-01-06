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

Available scripts to automate or speed up several tasks.

Make sure you're *in* the Virtual Machine first, and in the repo root:

    $ cd /path/to/tavro
    
### Reset Database

    $ sudo bash ./scripts/symfony-db.sh
    
### Rebuild an empty Database

    $ sudo bash ./scripts/symfony-db-empty.sh
    
### Rebuild an environment with Demo Data

**NOTE** This process takes a substantial amount of time, and will upload 'dummy' images to AWS!

    $ sudo bash ./scripts/build-demo.sh