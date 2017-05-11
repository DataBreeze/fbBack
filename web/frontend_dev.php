<?php
$allowed = array('127.0.0.1', '::1','74.95.197.233','74.95.197.234','74.95.197.235','74.95.197.236','74.95.197.237','173.11.103.18','173.11.103.19','173.11.103.20');
// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], $allowed))
{
  die('IP logged and refused.[' . @$_SERVER['REMOTE_ADDR'] . '] Check '.basename(__FILE__).' for more information.');
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
