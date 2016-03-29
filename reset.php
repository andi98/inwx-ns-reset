<?php
/*
 * XML-RPC support in PHP is not enabled by default. 
 * You will need to use the --with-xmlrpc[=DIR] configuration option when compiling PHP to enable XML-RPC support. 
 * This extension is bundled into PHP as of 4.1.0.
 * 
 * cURL needs to be installed and activated
 * 
 */

header('Content-type: text/plain; charset=utf-8');
error_reporting(E_ALL);
require "INWX/Domrobot.php";

$addr = "https://api.domrobot.com/xmlrpc/";
//$addr = "https://api.ote.domrobot.com/xmlrpc/";

$usr = "USERNAME";
$pwd = "PASSWORT";

$domrobot = new INWX\Domrobot($addr);
$domrobot->setDebug(false);
$domrobot->setLanguage('en');
$res = $domrobot->login($usr,$pwd);

//optional bei aktiviert 2fa
//$res = $domrobot->call('account', 'unlock', array('tan' => 123456));

$ns = array(
'ns.inwx.de',
'ns2.inwx.de',
'ns3.inwx.eu',
'ns4.inwx.com',
'ns5.inwx.net'
);

if ($res['code']==1000) {
	$obj = "domain";
	$meth = "list";
	$params = array();
	$res = $domrobot->call($obj,$meth,$params);
    foreach($res['resData']['domain'] as $domain) {
        $domrobot->call('nameserver', 'update', array('domain' => $domain, 'ns' => $ns));
    }
} else {
	print_r($res);
}

$res = $domrobot->logout();

?>
