#!/bin/bash
# Optional worker service start command on Railway.
set -euo pipefail
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
