<?php
require 'vendor/autoload.php';

use Zendesk\API\HttpClient as ZendeskAPI;

$subdomain = ""; // replace this with your domain
$username  = ""; // replace this with your registered email
$token     = ""; // replace this with your token

$client = new ZendeskAPI($subdomain);
$client->setAuth('basic', ['username' => $username, 'token' => $token]);

$tickets = $client->tickets()->findAll();
echo "<pre>";
print_r(json_encode($tickets));
echo "</pre>";

file_put_contents('JSON_SOURCE.json', print_r(json_encode($tickets), true));
?>