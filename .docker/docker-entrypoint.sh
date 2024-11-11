#!/bin/sh
set -e

# Enable Laravel schedule
if [[ "${ENABLE_CRONTAB:-0}" = "1" ]]; then
  mv -f /etc/supervisor.d/cron.default.conf /etc/supervisor.d/cron.conf
  echo "* * * * * php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >> /etc/crontabs/www-data
fi

# Enable Laravel queue workers
if [[ "${ENABLE_WORKER:-0}" = "1" ]]; then
  mv -f /etc/supervisor.d/worker.default.conf /etc/supervisor.d/worker.conf
else
  rm -f /etc/supervisor.d/worker.default.conf
fi

# Enable Laravel horizon
if [[ "${ENABLE_HORIZON:-0}" = "1" ]]; then
  mv -f /etc/supervisor.d/horizon.default.conf /etc/supervisor.d/horizon.conf
else
  rm -f /etc/supervisor.d/horizon.default.conf
fi

exec "$@"
