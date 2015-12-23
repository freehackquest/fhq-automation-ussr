#!/bin/bash
echo "INSTALL SCRIPT"

sudo cp -f etc/init.d/ussr-storage /etc/init.d/ussr-storage
sudo chmod 777 /etc/init.d/ussr-storage
sudo chmod +x /etc/init.d/ussr-storage
sudo mkdir -p /usr/share/ctfight/services/ussr-storage/service/flags
sudo chmod 777 -R /usr/share/ctfight/services/ussr-storage
cp -f usr/share/ctfight/services/ussr-storage/service/ussr-storage.py /usr/share/ctfight/services/ussr-storage/service/ussr-storage.py
cp -f usr/share/ctfight/services/ussr-storage/checker /usr/share/ctfight/services/ussr-storage/checker
chmod +x /usr/share/ctfight/services/ussr-storage/service/ussr-storage.py
chmod +x /usr/share/ctfight/services/ussr-storage/checker

# add job to cron - not work on ubuntu
#crontab -l | grep -v ussr-storage > /tmp/crontab_jobs
#echo "*/5 * * * * /etc/init.d/ussr-storage restart" >> /tmp/crontab_jobs
#crontab /tmp/crontab_jobs
#rm /tmp/crontab_jobs

# run service
sudo /etc/init.d/ussr-storage restart
echo "DONE"
