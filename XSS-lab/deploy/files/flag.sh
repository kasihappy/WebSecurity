#!/bin/bash
set -e

echo $FLAG > /flag

export FLAG=not_flag
FLAG=not_flag

chmod 777 -R /var/www/html


mysql -e "source /var/www/html/kasilab.sql;"