#!/bin/bash

# Install php
read -p "php-system-info will be installed to the current directory. Do you want to proceed (y/n)? " yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi


read -p 'The dependencies php, git and cron-apt need to be installed. Do you want to proceed (y/n)?' yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi

echo 'Installing dependencies...'
sudo apt-get install php7.3-cli git cron-apt
echo 'Done'

echo 'Cloning repo to php-system-info...'
git clone https://github.com/KuenzelIT/php-system-info
echo 'Done'

cd php-system-info || exit

echo 'Downloading config...'
wget $1 -O config.php
echo  'Done'

# Ask for target directory, default is /var/www?
# cd to that directory
#git clone https://github.com/KuenzelIT/php-system-info
#cd php-system-info

# Use params to get the config
# wget {{ getConfigUrl(system) }} -O config.php

# Ask if the system info script should be executed for the first time

# Ask if the schedule should be installed
