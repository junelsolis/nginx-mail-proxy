#!/bin/bash

# update system
yum update -y

# remove possible pre-existing Docker installations
yum remove docker docker-client docker-client-latest docker-commondocker-latest docker-latest-logrotate docker-logrotate docker-selinux docker-engine-selinux docker-engine

# install Docker CE
yum install -y yum-utils device-mapper-persistent-data lvm2
yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
yum install docker-ce

# install Docker compose
curl -L "https://github.com/docker/compose/releases/download/1.22.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# initialize container
docker-compose up -d
