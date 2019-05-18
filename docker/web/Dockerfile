FROM centos:7

MAINTAINER yamaaaaa

# update yum
RUN yum update -y && \
    yum clean all

# epel,remi
RUN yum install -y epel-release && \
	yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum clean all && \
	sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/epel.repo && \
	sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/remi.repo

# httpd, sshd, scp, openssl, sudo, which
RUN yum install -y httpd httpd-tools openssh-server openssh-clients openssl sudo which && \
    yum clean all

# libmcrypt, supervisor
RUN yum install --enablerepo=epel -y libmcrypt supervisor && \
    yum clean all

# gd-last (for php-gd)
RUN yum install --enablerepo=remi -y gd-last && \
    yum clean all

# php
RUN yum --enablerepo=epel,remi,remi-php72 install -y php php-devel php-gd php-mbstring php-mcrypt php-mysqlnd php-pear php-xml php-opcache && yum clean all

#imagemagick
RUN yum -y install ImageMagick
RUN yum -y install ImageMagick-devel
RUN pecl install imagick

# timezone
RUN cp -p /usr/share/zoneinfo/Japan /etc/localtime

# httpd 
RUN systemctl enable httpd.service

EXPOSE 80
CMD ["/usr/sbin/init"]