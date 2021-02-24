#!/bin/bash
####
##
####

find /home/adwsd/public_html/assets/downloads -name "*.csv" -type f -mtime +10 -exec rm -f {} \;