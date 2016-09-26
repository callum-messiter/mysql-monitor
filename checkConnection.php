<?php

    // Database connection credentials
    $hostname = '';
    $user     = '';
    $password = '';
    $dbName   = '';

    // Twilio credentials
    $sid      = ''; // Your Account SID from www.twilio.com/console
    $token    = ''; // Your Auth Token from www.twilio.com/console
    $senderNo = ''; // An active phone number from www.twilio.com/console/phone-numbers/

    // Message details
    $timezone    = ''; // Your timezone
    ini_set('date.timezone', $timezone); // Set timezone
    $textMessage = 'MySQL went down at: '. date('Y-m-d H:i'); // Specify the contents of the message you want to send
    $recipientNo = ''; // The phone number to which you want to send the message

    // Connect to the database
    $db = new mysqli($hostname, $user, $password, $dbName);

    // If there is an error connecting to the database:
    if($db->connect_errno > 0){
        // Echo MySQL-down error; this should be displayed in the log file for MySQL-status history purposes
        echo "MySQL DOWN ON PREVIOUS ATTEMPT AT " . date('Y-m-d H:i') . "\n";
        // Check the downtime status
        $downtimeStatus = file_get_contents(__DIR__ . "/downtimeStatus.txt");
        // If the downtime status is not 1 (the message hasn't yet been sent):
        if ($downtimeStatus != "1"){
            // 1) Send the message
            include(__DIR__ . "/twilio-php/Twilio/autoload.php"); // Include the Twilio library
            $client  = new Twilio\Rest\Client($sid, $token);
            $message = $client->messages->create(
              $recipientNo,
              array(
                'from' => $senderNo,
                'body' => $textMessage
              )
            );
            // 2) Set the downtime status to 1 once the message is sent, to avoid repeating messages whilst working to fix the problem with the MySQL service
            $filename = __DIR__ . "/downtimeStatus.txt";
            $myfile = fopen($filename,"w");
            $downtimeStatus = "1";
            fwrite($myfile, $downtimeStatus);
        }

    }else{
    // Set downtime status to 0
    $filename = __DIR__ . "/downtimeStatus.txt";
    $myfile = fopen($filename,"w");
    $downtimeStatus = "0";
    fwrite($myfile, $downtimeStatus);
    // Echo MySQL-running message; this should be displayed in the log file for MySQL-status history purposes
    echo "MySQL RUNNING ON PREVIOUS ATTEMPT " . date('Y-m-d H:i') . "\n";
    }

?>
