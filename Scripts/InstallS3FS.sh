#!/bin/bash
# Installs S3FS and mounts a bucket on /s3. Make sure you've got privileges with the user you execute this as.

# Make sure it's run as root.
  if [[ $EUID -ne 0 ]]; then
     echo "This script must be run as root! use 'sudo su -'." 
     exit 1
  fi

# Install packages
  apt-get install -y automake autotools-dev fuse g++ git libcurl4-gnutls-dev libfuse-dev libssl-dev libxml2-dev make pkg-config

# Clone the s3fs git repo
  cd ~
  # More info on the S3FS project here: https://github.com/s3fs-fuse/s3fs-fuse/wiki/Fuse-Over-Amazon
  git clone https://github.com/s3fs-fuse/s3fs-fuse.git

# Install s3fs
  cd s3fs-fuse/
  ./autogen.sh && ./configure && make && make install

# Go back to home dir
  cd ~

# Please go to /s3
  echo -e "S3FS successfully installed."