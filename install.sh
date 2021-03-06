#!/bin/bash

# Install php
read -p "sysdash_reporter_php will be installed to the current directory. Do you want to proceed (y/n)? " yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi


read -p 'The dependencies php, git and cron-apt need to be installed. Do you want to proceed (y/n)? ' yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi

echo 'Installing dependencies...'
sudo apt-get install php7.4-cli git cron-apt
echo 'Done'



read -p 'We are now going to download the reporter files into php-system-info. Do you want to proceed (y/n)? ' yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi


echo 'Cloning repo to php-system-info...'
git clone https://github.com/KuenzelIT/php-system-info
echo 'Done'

cd php-system-info || exit

echo 'Downloading config...'
wget $1 -O config.php
echo  'Done'


read -p 'Do you want to execute the reporting script for the first time (y/n)? ' yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi

echo "Running script..."
php getSystemInfo.php
echo "Done"


read -p 'Do you want to create the schedule for the script to be executed? ' yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi

cwd=$(pwd)

echo "Creating schedule in /etc/cron.d/php-system-info ..."
echo "* * * * * $USER php $cwd/getSystemInfo.php" | sudo tee /etc/cron.d/php-system-info
echo "Done"


echo 'cron-apt executes does two things in its actions: the first is updating the sources and the second thing is autocleaning and automatically upgrading.'
read -p 'Do you want to disable automatic cleaning and upgrading (updating stays, because it is needed for sysdash) (y/n)? ' yesno
if [[ ! $yesno =~ ^[Yy]$ ]]
then
    exit 1
fi

FILE=/etc/cron-apt/action.d/3-download
if test -f "$FILE"; then
    sudo rm $FILE
fi


echo 'Done completely! Thanks for using SysDash!'
