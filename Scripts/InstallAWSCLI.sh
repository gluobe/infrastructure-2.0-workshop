#!/bin/bash

# Make sure it's run as root.
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root! use 'sudo su -'." 
   exit 1
fi
  
# Install Python
apt install python wget -y
# Download Pip
wget https://bootstrap.pypa.io/get-pip.py
# Install Pip
python get-pip.py
# Remove Pip file
rm get-pip.py
# Install AWSCLI with pip
pip install awscli

# Completed.
echo -e "AWSCLI sucessfully installed."
