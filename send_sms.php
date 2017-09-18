<?php

require_once(dirname(__FILE__) . './textbox_api/No2SMS_Client.class.php');

parse_str($argv[1], $_POST);

$fullname = htmlspecialchars($_POST["fullname"]);
$phone = htmlspecialchars($_POST["phone"]);
$link = htmlspecialchars($_POST["link"]);

$user        = htmlspecialchars($_POST["user"]);
$password    = htmlspecialchars($_POST["password"]);
$destination = $phone;
$message     = $fullname . '\n' . $link;

echo $fullname;

var_dump(No2SMS_Client::message_infos($message, TRUE));
var_dump(No2SMS_Client::test_message_conversion($message));

$client = new No2SMS_Client($user, $password);

try {
    if (!$client->auth())
        die('mauvais utilisateur ou mot de passe');

    print "===> ENVOI\n";
    $res = $client->send_message($destination, $message);
    var_dump($res);
    $id = $res[0][2];
    printf("SMS-ID: %s\n", $id);

    print "===> STATUT\n";
    $res = $client->get_status($id);
    var_dump($res);

    $credits = $client->get_credits();
    printf("===> Il vous reste %d crédits\n", $credits);

} catch (No2SMS_Exception $e) {
    printf("!!! Problème de connexion: %s", $e->getMessage());
    exit(1);
}

