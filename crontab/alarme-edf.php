<?php 
include_once("../smsenvoi/smsenvoi.php");
$sms=new smsenvoi();
$sms->sendSMS('+3306060606','N\'oublie pas de payer EDF ! Bisous','PREMIUM','Gladys');	
?>