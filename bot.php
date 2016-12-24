<?php
$access_token = 'mEOx3Km33RvGppLnFUmE1yxXHOM0G4JCQbrj7Lq1JaGoRl5DYyb/dg1SA2Rp5ZF8xbpb1XUy26/YiUusigxI7L06hIYO8ecI6+Gu0PYPsFdxuusguUFjo6n006u2gN12/AkZiol0Sk8+opyVrZ3MsQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

$jsonObj = json_decode($json_string); //รับ JSON มา decode เป็น StdObj
$to = $jsonObj->{"result"}[0]->{"content"}->{"from"}; //หาผู้ส่ง 
$text = $jsonObj->{"result"}[0]->{"content"}->{"text"}; //หาข้อความที่โพสมา
$text_ex = explode(':', $text); //เอาข้อความมาแยก : ได้เป็น Array

// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => 'Hello, May I help you?'
			];

			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
        
        
	}
    
    if($text_ex[0] == "อยากรู้"){ //ถ้าข้อความคือ "อยากรู้" ให้ทำการดึงข้อมูลจาก Wikipedia หาจากไทยก่อน
    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers); 
    curl_setopt($ch1, CURLOPT_URL, 'https://th.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_ex[1]);
    $result1 = curl_exec($ch1); 
    curl_close($ch1);
    $obj = json_decode($result1, true);
    foreach($obj['query']['pages'] as $key => $val){
        $result_text = $val['extract'];
    }
if(empty($result_text)){//ถ้าไม่พบให้หาจาก en
    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch1, CURLOPT_URL, 'https://en.wikipedia.org/w/api.php?format=json&action=query&prop=extracts&exintro=&explaintext=&titles='.$text_ex[1]);
    $result1 = curl_exec($ch1);
    curl_close($ch1);
    $obj = json_decode($result1, true);
    foreach($obj['query']['pages'] as $key => $val){
        $result_text = $val['extract'];
    }
}
        if(empty($result_text)){//หาจาก en ไม่พบก็บอกว่า ไม่พบข้อมูล ตอบกลับไป
        $result_text = 'ไม่พบข้อมูล';
    }
    $response_format_text = ['contentType'=>1,"toType"=>1,"text"=>$result_text];
}
}

echo "OK";