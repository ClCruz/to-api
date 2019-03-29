<?php

require_once($_SERVER['DOCUMENT_ROOT']."/lib/phpmailer/PHPMailer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/phpmailer/SMTP.php");
require_once($_SERVER['DOCUMENT_ROOT']."/lib/phpmailer/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendToSMTP($from, $fromName, $to, $toName, $subject, $msg) {

	try {
		$mail = new PHPMailer();
		//$mail->SMTPDebug = 1;
		$mail->SMTPDebug = 3; //Alternative to above constant

		$mail->SetLanguage('br');
		$mail->SMTPSecure = false;
		$mail->SMTPAutoTLS = false;
		$mail->IsSMTP();
		$mail->Host = 'smtp1.iagentesmtp.com.br';
		$mail->Port = 587;
		$mail->SMTPAuth = true;
		$mail->Timeout = 10;
		$mail->Username = 'leonel.costa@tixs.me';
		$mail->Password = '54534c';

		$mail->Debugoutput = function($str, $level) {echo "<br />debug level $level; message: $str";};

		$mail->From = $from;
		$mail->FromName = $fromName;

		$mail->AddAddress($to, $toName);
		$mail->IsHTML(true);
		$mail->CharSet = 'utf8';

		$mail->Subject  = $subject;
		$mail->Body = $msg;
		//$mail->AltBody = 'plain text';

		$enviado = $mail->Send();
		echo $enviado;
		echo $mail->ErrorInfo;
		//die("oi");

		$mail->ClearAllRecipients();
		$mail->ClearAttachments();

		return "";
	} catch (Exception $e) {
		die(json_encode($e));
		//return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		//return "";
	}
}
function sendToAPI($from, $fromName, $to, $toName, $subject, $msg) {	
	//die( "oi");
	//return sendToSMTP($from, $fromName, $to, $toName, $subject, $msg);
	//return;
	try {
		$tomultiple = explode(',', $to);
		$fields_string = "";

		$apiuser = "leonel.costa@tixs.me";
		$apikey = "b175cc5be004456855e061f1fb8f113b";
		$url = 'http://app1.iagentesmtp.com.br/api/v1/send.json';

		if (sizeof($tomultiple)>1) {
			$fields = array(
				'api_user' => urlencode($apiuser),
				'api_key' => urlencode($apikey),
				'from' => urlencode($from),
				'fromname' => urlencode($fromName),
				'subject' => urlencode($subject),
				'html' => urlencode($msg),
		//		'trackingdomain' => "bringressos.com.br",
			);
		
			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');	

			for ($i=0; $i < sizeof($tomultiple); $i++) { 
				$fields_string .= "&to[]=".$tomultiple[$i];
				$fields_string .= "&toname[]=".$tomultiple[$i];
			}
		}
		else {
			$fields = array(
				'api_user' => urlencode($apiuser),
				'api_key' => urlencode($apikey),
				'from' => urlencode($from),
				'fromname' => urlencode($fromName),
				'to' => urlencode($to),
				'toname' => urlencode($toName),
				'subject' => urlencode($subject),
				'html' => urlencode($msg),
		//		'trackingdomain' => "bringressos.com.br",
			);
		
			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');	
		}

		// die($fields_string);
	
		//open connection
		$ch = curl_init();
	
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	
		//execute post
		$result = curl_exec($ch);
		//close connection
		curl_close($ch);
		//die($result);
		return $result;

	} catch (Exception $e) {
		return "";

	}

}
?>