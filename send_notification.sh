#!/bin/bash

DATE=$(date "+7 days")

# Format date
DAY=`date --date="$DATE" +'%d'`
MONTH=`date --date="$DATE" +'%m'`

php bin/console birthday:notify --day=${DAY} --month=${MONTH}
