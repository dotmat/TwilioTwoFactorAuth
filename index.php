<?php

session_start();

?>
<html>
    <head>
        <title>Two Factor Authentication Demo</title>
        <style>
            .center {
                margin-left: auto;
                margin-right: auto;
                margin-top: 25px;
            }

            #submit { float: center; }

            form { border-style: solid; padding: 10px; width: 500px; }


            div { text-align: center; width: 500px; }
        </style>
    </head>
    <body>
        <div class="center">
            <p>This is just a demo that demonstrates how voice/SMS could be
                integrated to build a simple two-factor authentication system
                for better security and fraud prevention.</p>

            <p>No matter what username you put into the initial box, the system
                will generate a one-time use password similar to an RSA token.
                Once this password is used, the user's session is set and the
                password is destroyed. In this particular case, we're not
                storing anything long term.</p>

            <span id="message">
            <?php 
            if(isset($_SESSION['password'])){
            echo "<p>Hi " . $_SESSION['username'] . "</p>";
            echo "<p>The Two Factor Password that was sent to your phone was: " . $_SESSION['password'] . "</p>";
            echo "<p>Please compare this to the password that was sent to your mobile device</p>";
			}
			?>
            </span>
        </div>
        <form id="generateTwoFA" action="twoFactorAuthProcessor.php" method="POST" class="center">
        <div id="userName" class="ui-resizable-disabled ui-state-disabled">
               <label for="userName">Your Name</label>
               <input type="text" name="userName" id="userName" required="required">
          </div>
          <div id="tel_number" class="ui-resizable-disabled ui-state-disabled">
               <label for="tel_number">Your phone number</label>
               <input type="tel" name="tel_number" id="tel_number" required="required">
		</div>
		<div id="method" class="ui-resizable-disabled ui-state-disabled">
		<label for="method"> Deliver code via: <br></label>
			<input type="radio" name="method" value="sms">SMS Message
			<input type="radio" name="method" value="voice">Voice Call
         <div id="form-submit" class="field f_100 clearfix submit">
               <input type="submit" value="Submit">
          </div>
        </form>
    </body>
</html>