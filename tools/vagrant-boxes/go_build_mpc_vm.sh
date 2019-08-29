#!/bin/bash
#
# Build the standalone Mon premier CTF VM
#

echo "
####
#
# Building Mon Premier CTF VM
#
"

ff() {
    echo
}

#
# Vagrant 2.xx installed ?
printf "=> Verification : Vagrant installé : "
if [[ $(which vagrant) ]]; then
    echo "Ok"
    echo "$(vagrant --version)"

  else
    printf "${RED}Ko${NC}\n"
    echo "Pour installer vagrant rapidement:"
    echo "wget https://releases.hashicorp.com/vagrant/2.2.5/vagrant_2.2.5_x86_64.deb"
    echo "apt install ./vagrant_2.2.5_x86_64.deb"
    exit;
fi


#
# Start VM
vagrant up


#
# Install .deb
#
init_script='
sudo apt-get update;
sudo apt-get install -y python-minimal;
sudo apt-get install -y wget net-tools;
sudo apt-get install -y docker-compose;
sudo apt install -y zip;
sudo gpasswd -a $USER docker
sudo adduser ctf --gecos "" --disabled-password 
echo "ctf:ctf" | sudo chpasswd
sudo gpasswd -a ctf docker
sudo gpasswd -a ctf sudo
'
vagrant ssh -c "$init_script"


#
# Reboot VM
#
vagrant halt
vagrant up



#
# Install mon_premier_ctf and run it
# then Setup auto run at VM startup
#
init_script='
if [ ! -d "mon_premier_ctf" ]; then git clone https://github.com/monpremierctf/mon_premier_ctf.git; fi;
cd mon_premier_ctf;
git pull;
./go_first_install_webserver_run -y

sudo cp tools/vagrant-boxes/etc_init_d_mon_premier_ctf.sh /etc/init.d/mon_premier_ctf
sudo chmod 755 /etc/init.d/mon_premier_ctf
sudo update-rc.d mon_premier_ctf defaults
sudo systemctl daemon-reload'

vagrant ssh -c "$init_script"





#
# Update startup banner
#
init_script='
sudo cp mon_premier_ctf/tools/vagrant-boxes/etc_issue /etc/issue
sudo chmod 755 /etc/issue
sudo rm -f /etc/update-motd.d/*;
sudo cp mon_premier_ctf/tools/vagrant-boxes/etc_update-motd.d_00-header            /etc/update-motd.d/;
sudo cp mon_premier_ctf/tools/vagrant-boxes/etc_update-motd.d_50-landscape-sysinfo /etc/update-motd.d/;
sudo chmod 755 /etc/update-motd.d/*;'

vagrant ssh -c "$init_script"



#
# Upload VM
#
# ls -al ~/VirtualBox\ VMs/vagrant-boxes_default_1566830539113_74963/