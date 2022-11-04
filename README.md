After deploying to AWS:

1. Connect to the EC2 instance
2. Change directory to `/var/www/html`
3. Run the command `sudo -u apache nohup php craft magi/magi & disown $!` to run the bot client as a background process.

You should be able to disconnect from the instance without the session shutting down the bot.

To shut down the bot use `ps auxf` to get a detailed list of all running processes on the EC2 instance. Use `sudo kill [pid]` to terminate the bot as necessary.

Using a system service on AWS:

1. Create the new file at `/lib/systemd/system` (in this case, `magi.service`)
2. Add the following to the file:
    [Unit]
    Description=Magi Discord Bot
    After=multi-user.target
    After=network-online.target
    Wants=network-online.target
    
    [Service]
    ExecStart=/bin/php /var/www/html/craft magi/magi
    User=apache
    Group=apache
    Type=idle
    Restart=always
    RestartSec=15
    RestartPreventExitStatus=0
    TimeoutStopSec=10
    
    [Install]
    WantedBy=multi-user.target
3. Set the proper permissions: `sudo chmod 644 magi.service`
4. Verify the service works: `sudo systemd-analyze verify magi.service`
5. Reload the daemons: `sudo systemctl daemon-reload`
6. Start the service: `sudo systemctl start magi`
7. Check on the service: `sudo systemctl service magi`
8. View all services running: `sudo systemctl`

To view the current output of the service use `sudo journalctl -u magi -f`
