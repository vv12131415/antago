# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/trusty32"
  config.vm.network "forwarded_port", guest: 92, host: 9291
  config.vm.network "private_network", ip: "192.168.33.11"
  config.vm.synced_folder "./", "/home/vagrant/projects/antago", create: true, group: "vagrant", owner: "vagrant"
  config.vm.provider "virtualbox" do |vb|
    vb.memory = "1024"
  end
  config.vm.provision "shell", privileged: false do |s|
    s.path="provision/setup.sh"
  end
end

