After deploying to AWS:

1. Connect to the EC2 instance
2. Change directory to `/var/www/html`
3. Run the command `sudo -u apache nohup php craft discordbot/bot & disown $!` to run the bot client as a background process.

You should be able to disconnect from the instance without the session shutting down the bot.

To shut down the bot use `ps auxf` to get a detailed list of all running processes on the EC2 instance. Use `sudo kill [pid]` to terminate the bot as necessary.