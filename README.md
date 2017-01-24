# mysql-monitor
MySQL server monitoring script for real-time SMS status updates (Ubuntu). The script will try to connect to your database at regular intervals and send you a text message if the connection fails. Especially useful if you manage hosting for clients.

  <h3>How it works</h3>
  <ul>
    <li>The <strong>checkConnection.php</strong> script is executed by cron every minute<img src="http://i.imgur.com/bCFPOSQ.png" width=204px" height="360px" align="right"></img></li>
    <li>The script initiates a connection to the database</li>
    <li>If the connection fails, suggesting that MySQL is inactive, the script will check the status value in <strong>downtimeStatus.txt</strong></li>
    <li>If the status value is not equal to 1 - suggesting that a message has not yet been sent for this particular crash - the script will execute a new Twilio text message</li>
    <li>Once the message is sent, the status is set to 1, in order to avoid repeating messages whilst the issue is being fixed</li>
    <li>If the connection to the database is successful, suggesting that MySQL is active, the status will be set to zero</li>
  </ul>

<h3>Pre-requisites</h3>
The following packages must be installed on your server:
<ul>
  <li>PHP >= 5.3</li>
  <li>cURL</li>
  <li><a href="https://github.com/twilio/twilio-php">Twilio's PHP library</a></li>
</ul>

<h3>Setup instructions</h3>
  <li>Sign up for a Twilio account <a href="https://www.twilio.com/try-twilio">here</a></li>
  <li>Get your Twilio phone number by following the instructions. This will serve as the number used to send out SMS</li>
  <li>Verify a recipient phone number. If you're using a Twilio Trial account, you'll only be able to send SMS messages to phone numbers that you've verified with Twilio. Phone numbers can be verified via your Twilio Console's Verified Caller IDs</li>
  <li>Download the <strong>mysql-monitor</strong> directory to your server</li>
  <li>In the <strong>checkConnection.php</strong> file, insert your database connection credentials, your Twilio credentials and your message details</li>
<h3>Set the script to run using crontab</h3>
Create a cron job to execute your <strong>checkConnection.php</strong> script at regular intervals, ensuring up-to-date alerts if and when your MySQL server becomes inactive
<ul>
<li>From the terminal, execute the 'crontab -e' command to open your user account's crontab file</li>
<li>Insert your cronjob, like so

    */1 * * * * php /absolute/path/to/mysql-monitor/checkConnection.php >> /var/log/statusChecker.log 2>&1

</li>
<li> This section of the cronjob provides a location to which logs can be sent:

     >> /var/log/statusChecker.log 2>&1

Be sure to create the <strong>statusChecker.log</strong> file so that you can keep track of your MySQL status history. Records of the events that occur each time the <script>checkConnection.php</script> script runs will be kept inside this file 
</li>
<li>To learn more about setting up a crontab file, visit <a href="https://help.ubuntu.com/community/CronHowto">Ubuntu's Community Help Wiki</a>.</li>
