wget https://releases.hashicorp.com/vagrant/2.2.5/vagrant_2.2.5_x86_64.deb
apt install ./vagrant_2.2.5_x86_64.deb

# cat Vagrantfile 
Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/eoan64"
end

vagrant up
vagrant ssh -c 'ls / '
