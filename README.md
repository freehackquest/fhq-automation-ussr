# automation-ussr

## Use Docker

Build image: `# docker build --rm=true -t ctfight:automation-ussr ./`  
Run container: `# docker run -t --name=keva-automation-ussr ctfight:automation-ussr`  
Stop container: `docker stop $(docker ps -a | grep "ctfight:automation-ussr" | sed -s 's/.*\([0-9a-z]\{12,12\}\) .*/\1/')`  
Remove container: `# docker rm $(docker ps -a | grep "ctfight:automation-ussr" | sed -s 's/.*\([0-9a-z]\{12,12\}\) .*/\1/')`  
Remove image: `# docker rmi -f $(docker images | grep automation-ussr | sed -s 's/.* \([0-9a-z]\{12,12\}\) .*/\1/')`  

```
# docker images | grep automation-ussr
``` 

## Manual Installation

Web: `sudo apt-get install apache2 mysql-server mysql-client php5`

### Download sources

	sudo apt-get install git-core
	git clone https://github.com/ctfight/automation-ussr.git automation-ussr.git

### Install service
	
	cd ~/automation-ussr.git/service/
	./install.sh

Check service status `/etc/init.d/ussr-storage status`

Check service stop `sudo /etc/init.d/ussr-storage stop`

Check service start `sudo /etc/init.d/ussr-storage start`

Check service restart `sudo /etc/init.d/ussr-storage restart`

Or in 'hand mode':

	$ cd /usr/share/ctfight/services/ussr-storage/service/
	$ sudo ./ussr-storage.py

### Install database:

	cd ~/automation-ussr.git/
	sudo mysql -u root -p

	mysql> CREATE DATABASE automation-ussr;
	mysql> CREATE USER 'automation-ussr'@'localhost' IDENTIFIED BY 'automation-ussr';
	mysql> USE automation-ussr;
	mysql> GRANT ALL ON `automation-ussr`.* TO 'automation-ussr'@'localhost';
	mysql> FLUSH PRIVILEGES;
	
	... here create all tables from file automation-ussr.sql ...
	
	mysql> quit;
	

### Install web:

Copy files to web directory:

	cd ~/automation-ussr.git/
	sudo cp -R www/* /var/www/html

Configure config in nano or you favorite editor
	
	`sudo nano /etc/www/html/config.php`

### Install cron jury (putting flags to service)

* Cron Script will be putted 20 flags to service every minute.
* 28800 flags in 24 hours
* Flag will be live ~15 minut
* Also cron_jury.py will be remove old flags from flag directory (> 30 minutes)

Install python-mysql module:

	`sudo apt-get install python-mysqldb`

Configure db connection:

	`nano /home/user/automate-ussr.git/cron_jury.py`

Configure cron on every minute:

	sudo crontab -e
	
And add next line:

	* * * * * /home/user/automate-ussr.git/cron_jury.py

### automate_example.py

	`apt-get install python-httplib2`

### vulns (logic)
	* remove from protocol DEL
	* access deny for rewrite PUT

