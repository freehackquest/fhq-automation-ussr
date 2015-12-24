FROM debian:jessie
MAINTAINER Evgenii Sopov <mrseakg@gmail.com>

RUN apt-get update
RUN apt-get -y install \
	apache2 \
	php5 php5-gd \
	ssh \
	mc \
	vim \
	nano \
	python-httplib2

# service sources and configurations
RUN rm /var/www/html/index.html
# RUN chown -R www-data:www-data /var/lib/php5
# ADD ./www /var/www/html
# RUN touch /var/www/html/logger.log
# RUN chmod 777 /var/www/html/logger.log
RUN sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN echo root:docker | chpasswd

EXPOSE 22 80 4445
CMD /etc/init.d/apache2 restart && /etc/init.d/ssh start && /bin/bash
