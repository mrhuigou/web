<?php
namespace common\component\Iospush;
class  Iospush{

		static function push($deviceToken,$data){

			$passphrase = '365jiarun';//密码

			$ctx = stream_context_create();
			$pem = dirname(__FILE__) . '/' . 'ck.pem';
			$cer = dirname(__FILE__) . '/' . 'entrust_2048_ca.cer';
			stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			stream_context_set_option($ctx, 'ssl', 'cafile', $cer);
			// Open a connection to the APNS server
			$fp = stream_socket_client(
				'tls://gateway.sandbox.push.apple.com:2195', $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if (!$fp){
				exit("Failed to connect: $err $errstr" . PHP_EOL);
			}
			//echo 'Connected to APNS' . PHP_EOL;
			// Create the payload body
			 $body['aps'] = array(
			 	'alert' => $data['title'],
			 	'sound' => 'default',
			 	'data' => array(
					'title' => $data['title'],
					'content'=> $data['content'],
					'type' => $data['type'],
					'value'=> $data['value'],
					'time'=> $data['time'],
					),
				"type"=>"SHOP",
				"value"=>"DP0057"
			 	);

			// Encode the payload as JSON
			$payload = json_encode($body);

			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));

			if (!$result) {
				echo 'Message not delivered' . PHP_EOL;
			}else{
				echo 'Message successfully delivered' . PHP_EOL;
			}


			// Close the connection to the server
			fclose($fp);
		}

		////////////////////////////////////////////////////////////////////////////////


}