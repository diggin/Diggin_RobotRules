<?php
/** Diggin_RobotRules_Protocol_Txt_Line **/
require_once '../library/Diggin/RobotRules/Protocol/Txt/Line.php';
/** Diggin_RobotRules_Protocol_Txt_Record **/
require_once '../library/Diggin/RobotRules/Protocol/Txt/Record.php';

$line1 = new Diggin_RobotRules_Protocol_Txt_Line;
$line1->setField('Disallow');
$line1->setValue('/');
$line2 = new Diggin_RobotRules_Protocol_Txt_Line;
$line2->setField('User-Agent');
$line2->setValue('Mozilla');
$line3 = new Diggin_RobotRules_Protocol_Txt_Line;
$line3->setField('Allow');
$line3->setValue('/test');
$line4 = new Diggin_RobotRules_Protocol_Txt_Line;
$line4->setField('Crawl-delay');
$line4->setValue('120');

$record = new Diggin_RobotRules_Protocol_Txt_Record;
$record->append($line1);
$record->append($line2);
$record->append($line3);
$record->append($line4);

var_dump($record);
echo $record;

