<?php

	class EMailer
	{
		public static function SendConfirmationEmail($userId, $emailAddress, $confirmationCode)
		{
			//define the receiver of the email
			$to = $emailAddress;
			
			//define the subject of the email
			$subject = 'Please Complete Your Lunch on Me Registration';
			//define the message to be sent. Each line should be separated with \n
			//$message = "<h3>Thanks for registering " . $user->GetFirstName() . ".</h3> <br /><br />Please click the following link to complete your Wingmann Registration: " .
			//	"<a href=\"https://wingmann.com/index.php?page=users.NewMemberLogin \">Fear the Fanny Pack!</a>";
				
			$message = "<html><h3>Thanks for registering for Lunch on Me.</h3> <br /><br />Please click the following link to complete your Registration: " .
				"<a href=\"http://rehlander.com/tou/WebClient3/index.php?userId=" . $userId . "&confirmationCode=" . $confirmationCode . " \">Get me started!</a></html>";
				
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = 'From: registration@rehlander.com' . "\r\n" .
				'Reply-To: noreply@rehlander.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			//send the email
			EMailer::Protect($to, $to);		
			
			//$mail_sent = mail( $to, $subject, $message, $headers);
			$mail_sent = mail( $emailAddress, 'Registration Confirmation', $message, $headers);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
		}
		
		public static function SendGroupInviteEmail($from, $emailAddress, $groupId, $groupName, $message)
		{
			//define the receiver of the email
			$to = $emailAddress;
			
			//define the subject of the email
			$subject = 'Invitation to Lunch on Me Group';
			
			$message = "<html><h3>" . stripcslashes($from) . " has invited you to join his Lunch on Me group \"" . stripcslashes($groupName) . ".\"</h3> <br /><br />Please click the following link to accept: " .
				"<a href=\"http://rehlander.com/tou/WebClient3/index.php?groupId=" . $groupId . " \">I want in!</a></html>";
				
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = 'From: invitations@rehlander.com' . "\r\n" .
				'Reply-To: noreply@rehlander.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			//send the email
			EMailer::Protect($to, $to);		
			
			//$mail_sent = mail( $to, $subject, $message, $headers);
			$mail_sent = mail( $emailAddress, 'Invitation to Lunch on Me Group', $message, $headers);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
		}
		
		public static function SendLunchInvitation($from, $friends, $location, $date, $time)
		{
			//define the receiver of the email
			foreach($friends as $toUserRow)
			{
				$to .= $toUserRow['UserEmail'] . ' , ';
			}
			
			// Get rid of last comma
			$to = substr($to, 0, strlen($to) - 3);
			
			//define the subject of the email
			$subject = 'Join Me For Lunch!';
			
			$message = "<html><h3>" . stripcslashes($from['UserFirstName']) . " " . stripcslashes($from['UserLastName']) . " has invited you to lunch at " . stripcslashes($location) . ".  It will take place on " . $date . " at " . $time .".  ";
			$message .= "If you would like to respond, please email " . stripcslashes($from['UserFirstName']) . " directly at " . stripcslashes($from['UserEmail']) . ".</h3>";
				
			//define the headers we want passed. Note that they are separated with \r\n
			$headers = 'From: invitations@rehlander.com' . "\r\n" .
				'Reply-To: noreply@rehlander.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			//send the email
			EMailer::Protect($to, $to);		
			
			//$mail_sent = mail( $to, $subject, $message, $headers);
			$mail_sent = mail( $to, 'Join Me For Lunch!', $message, $headers);
			
			//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
			return (($mail_sent ? "Mail sent" : "Mail failed"));
		}
		
		public static function Protect($name, $email)
		{
			if ( preg_match( "/[\r\n]/", $name ) || preg_match( "/[\r\n]/", $email ) ) 
			{
				throw new Exception("Newlines are not allowed in the Name or Email fields.  Injection attack detected.");
			}
		}
		
		public static function CreateConfirmationCode($dateCreated)
		{
			$replaceChars = array("-", " ", ":");
			$replaceWith = array("", "", "");
			$confCode = str_replace($replaceChars, $replaceWith, $dateCreated);
			
			$confCode = $confCode - 150;
			
			return $confCode;		
		}
	}

?>