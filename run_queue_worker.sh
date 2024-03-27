#!/bin/bash
now=$(date +%H:%M)
if [[ "$now" > "19:00" && "$now" < "23:00" ]]; then
    php /path/to/artisan queue:work --queue=myqueue
fi
