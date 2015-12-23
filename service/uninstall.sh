#!/bin/bash
echo "UNINSTALL SCRIPT"

# stop service
sudo /etc/init.d/ussr-storage stop

# remove job from cron for current user
# crontab -l | grep -v ussr-storage > /tmp/crontab_jobs
# crontab /tmp/crontab_jobs
# rm /tmp/crontab_jobs

# remove folders
sudo rm /etc/init.d/ussr-storage
sudo rm -rf /usr/share/ctfight/services/ussr-storage
echo "DONE"
