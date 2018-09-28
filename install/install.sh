#!/bin/bash

yum groupinstall 'Development Tools' -y
yum install -y pcre-devel openssl-devel wget

wget -O nginx-1.15.4.tar.gz http://nginx.org/download/nginx-1.15.4.tar.gz

tar zxvf nginx-1.15.4.tar.gz

cd nginx-1.15.4

./configure --prefix=/opt/nginx --with-mail --with-mail_ssl_module --with-stream
make
make install

cd ..

cp nginx.conf /opt/nginx/conf/nginx.conf

adduser nginx

mkdir /var/log/nginx/
touch /var/log/nginx/error.log
touch /var/log/nginx/access.log

systemctl stop postfix
yum remove postfix -y

cp nginx.service /lib/systemd/system/nginx.service
systemctl enable nginx
systemctl start nginx
