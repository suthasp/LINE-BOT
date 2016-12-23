<?php
$access_token = 'mEOx3Km33RvGppLnFUmE1yxXHOM0G4JCQbrj7Lq1JaGoRl5DYyb/dg1SA2Rp5ZF8xbpb1XUy26/YiUusigxI7L06hIYO8ecI6+Gu0PYPsFdxuusguUFjo6n006u2gN12/AkZiol0Sk8+opyVrZ3MsQdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;