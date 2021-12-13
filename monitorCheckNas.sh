#!/bin/sh
mount -t nfs 192.168.0.250:/volume1/storage/image_storage /mnt/storage/image_storage
echo "process nas check!!" $(date +"%Y-%m-%d %H:%M:%S")
