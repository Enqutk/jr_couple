#!/bin/bash
# Optional cron service start command on Railway.
set -euo pipefail
while true; do
  echo "Running the scheduler..."
  php artisan schedule:run --verbose --no-interaction &
  sleep 60
done
