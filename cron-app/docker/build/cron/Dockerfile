FROM centos:7
ENV container=docker

# Install systemd
RUN (cd /lib/systemd/system/sysinit.target.wants/; for i in *; do [ $i == \
systemd-tmpfiles-setup.service ] || rm -f $i; done); \
rm -f /lib/systemd/system/multi-user.target.wants/*;\
rm -f /etc/systemd/system/*.wants/*;\
rm -f /lib/systemd/system/local-fs.target.wants/*; \
rm -f /lib/systemd/system/sockets.target.wants/*udev*; \
rm -f /lib/systemd/system/sockets.target.wants/*initctl*; \
rm -f /lib/systemd/system/basic.target.wants/*;\
rm -f /lib/systemd/system/anaconda.target.wants/*;

# Update server
RUN yum -y update

# Install yum utils
RUN yum install -y yum-utils

# Install vim
RUN yum install -y vim

# Install epel
RUN yum install -y epel-release

# Install PHP
RUN rpm -Uvh http://rpms.remirepo.net/enterprise/remi-release-7.rpm
RUN yum install -y --enablerepo=remi-php71 php php-bcmath php-dom php-mbstring php-imap php-intl php-odbc php-posix php-shmop php-soap php-sysvmsg php-sysvsem php-sysvshm php-xml php-xmlwriter php-xsl php-wddx php-xmlreader php-amqp php-apcu php-igbinary php-mailparse php-memcache php-msgpack php-propro php-raphf php-memcached php-mongodb php-http php-redis php-sapnwrfc php-xdebug php-opcache php-pdo_odbc php-gearman php-devel php-simplexml php-gd php-ldap php-mysqli php-mysqlnd php-pdo_mysql php-sapnwrfc php-zip php-mcrypt php-fpm

# Install supervisor
#RUN yum install -y supervisor
#RUN systemctl enable supervisord

# Install crontab
RUN yum install -y cronie
RUN systemctl enable crond

# Copy Sf project
COPY . /var/www/html

# Setup crons
RUN mkdir /var/www/html/cron
RUN mkdir /var/www/html/cron/logs
RUN mkdir /var/www/html/cron/crons
RUN touch /var/www/html/cron/logs/cron.log
RUN echo "* * * * * php /var/www/html/bin/console test:test >> /var/www/html/cron/logs/cron.log 2>&1" >> /var/www/html/cron/crons/cron1
RUN crontab /var/www/html/cron/crons/cron1
RUN ls -l /usr/sbin | grep cron

# START
CMD ["/usr/sbin/crond", "-n"]