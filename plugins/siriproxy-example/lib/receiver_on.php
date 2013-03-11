<?php
function send_to_host($host,$method,$path='/',$data=''){
    $method = strtoupper($method);
    $fp = fsockopen($host, 80) or die("Unable to open socket");

/* Header is 	POST /YamahaRemoteControl/ctrl HTTP/1.1
		Content-Type: text/plain
		Content-length: 152 (for this command)
		HOST: 192.168.1.25 (where your amp is)
*/
    fputs($fp, "$method $path HTTP/1.1\r\n");
    fputs($fp, "Host: $host\r\n");
    fputs($fp, "Content-type: text/xml\r\n");
    if ($method == 'POST') fputs($fp, "Content-length: " . strlen($data) . "\r\n");
    fputs($fp, "Connection: close\r\n\r\n");

/* Body(XML) is	<?xml version="1.0" encoding="UTF-8"?>
		<YAMAHA_AV cmd="PUT">
		<Main_Zone>
		<Vol>
		<Mute>On</Mute>
		</Vol>
		</Main_Zone>
		</YAMAHA_AV>
*/
    if ($method == 'POST') fputs($fp, $data);

    while (!feof($fp))
		$buf .= fgets($fp,128);
	
    fclose($fp);
    return $buf;
}
$command = '<YAMAHA_AV cmd="PUT"><Main_Zone><Power_Control><Power>On</Power><Sleep>Off</Sleep></Power_Control></Main_Zone></YAMAHA_AV>';
$yamip = '192.168.1.5'; //your amp
$test = send_to_host($yamip.':80/YamahaRemoteControl/ctrl','POST','/YamahaRemoteControl/ctrl',$command);
echo $test;
?>
