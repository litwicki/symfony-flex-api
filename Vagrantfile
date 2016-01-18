# -*- mode: ruby -*-
# vi: set ft=ruby :

# ========================================================================
# IF YOU CHANGE VALUES HERE PLEASE ALSO CHANGE IN: /provisioning/config
# I promise that's the only replication!
# ========================================================================

ip_address = "192.168.50.33"
appname = "tavro"
hostname = appname + ".dev"


# ========================================================================

Vagrant.configure(2) do |config|
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  Vagrant.require_version ">= 1.6.0"

  config.vm.box = "ubuntu/trusty64"
  config.vm.network "private_network", ip: ip_address

  # Landrush: https://github.com/phinze/landrush
  config.landrush.enabled = true
  config.landrush.tld = 'dev'
  config.landrush.host hostname, ip_address

  # from VVV
  # SSH Agent Forwarding
  #
  # Enable agent forwarding on vagrant ssh commands. This allows you to use ssh keys
  # on your host machine inside the guest. See the manual for `ssh-add`.
  config.ssh.forward_agent = true
  # Configuration options for the VirtualBox provider.
  config.vm.provider :virtualbox do |v|
    v.customize ["modifyvm", :id, "--memory", 1024]
    v.customize ["modifyvm", :id, "--cpus", 2]
    v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    v.customize ["modifyvm", :id, "--natdnsproxy1", "on"]
  end

  # NFS is waaaaay faster than the standard rsync, and this way we determine the
  # root directory structure to replicate production as well.
  config.vm.synced_folder '.', "/var/www/" + appname, :nfs => true

  # from VVV
  config.vm.provision "fix-no-tty", type: "shell" do |s|
      s.privileged = false
      s.inline = "sudo sed -i '/tty/!s/mesg n/tty -s \\&\\& mesg n/' /root/.profile"
  end

  config.vm.provision "ansible" do |ansible|
      # Provision Wordpress submodule as www.{{hostname}}
      ansible.playbook = "ansible/playbook.yml"
      ansible.extra_vars = {
          hostname: hostname,
          appname: appname
      }
  end

  # Auto build the docs
  config.vm.provision :shell, inline: "php /var/www/tavro/sami/sami update /var/www/tavro/sami/config.php -v", run: "always"

end
