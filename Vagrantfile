# -*- mode: ruby -*-
# vi: set ft=ruby :

$phpVersion = "7.1";
$binDir = "/home/vagrant/bin";
$composerVersion = "1.6.2";
$composerFile = $binDir + "/composer";

Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/xenial64"

    config.vm.network "forwarded_port", guest: 8000, host: 8001
    config.vm.network "private_network", ip: "192.168.56.26"

    config.vm.synced_folder ".", "/vagrant",
        mount_options: ["actimeo=1"],
        nfs: true,
        linux__nfs_options: ["rw", "no_subtree_check", "all_squash", "async"]

    config.vm.provider "virtualbox" do |vb|
        vb.memory = "1536"
        vb.cpus = 4
        vb.name = "supercache-bundle"
    end

    config.vm.provision "shell" do |s|
        s.path = "vagrant/apt.sh"
        s.args = [$phpVersion]
    end

    config.vm.provision "shell" do |s|
        s.path = "vagrant/php.sh"
        s.args = [$phpVersion]
    end

    config.vm.provision "shell" do |s|
        s.path = "vagrant/composer.sh"
        s.args = [$composerVersion, $binDir, $composerFile]
        s.privileged = false
    end
end
