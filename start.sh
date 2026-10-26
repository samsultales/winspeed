#!/bin/sh

# Execute the setfacl.sh script
/var/www/voice-recorder-web/setfacl.sh

# Run the background process for scheduled tasks
# nohup sh -c 'while true; do cd /var/www/voice-recorder-web && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1; sleep 1; done' &
nohup sh -c 'cd /var/www/voice-recorder-web && /usr/local/bin/php artisan schedule:work >> /var/log/cron.log 2>&1 &' &

# Run the queue worker for transkrip jobs (Whisper)
nohup sh -c 'cd /var/www/voice-recorder-web && /usr/local/bin/php artisan queue:work --queue=transkrip,default --timeout=900 --tries=3 >> /var/log/queue.log 2>&1 &' &

# Run the background process for Laravel Reverb WebSocket server
# nohup sh -c 'cd /var/www/voice-recorder-web && /usr/local/bin/php artisan reverb:start >> /var/log/reverb.log 2>&1 &' &

# Run the foreground process (supervisord)
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
