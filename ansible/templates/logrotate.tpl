/var/log/kom-inn/*.log {
    daily
    rotate 14
    compress
    delaycompress
    missingok
    notifempty
    create 0666 deploy devs
}
