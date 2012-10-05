<?php

header('Content-Type: application/xrds+xml');

$server = $_SERVER['SERVER_NAME'];

echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";

?>
<XRDS xmlns="xri://$xrds">
    <XRD xmlns:simple="http://xrds-simple.net/core/1.0" xmlns="xri://$XRD*($v*2.0)" xmlns:openid="http://openid.net/xmlns/1.0" version="2.0" xml:id="main">
	<Type>xri://$xrds*simple</Type>
	<Service>
	    <Type>http://oauth.net/discovery/1.0</Type>
	    <URI>#main</URI>
	</Service>
	<Service>
	    <Type>http://oauth.net/core/1.0/endpoint/request</Type>
	    <Type>http://oauth.net/core/1.0/parameters/auth-header</Type>
	    <Type>http://oauth.net/core/1.0/parameters/uri-query</Type>
	    <Type>http://oauth.net/core/1.0/signature/HMAC-SHA1</Type>
	    <Type>http://oauth.net/core/1.0/signature/PLAINTEXT</Type>
	    <URI>http://<?php echo $server; ?>/oauth/request_token</URI>
	</Service>
	<Service>
	    <Type>http://oauth.net/core/1.0/endpoint/authorize</Type>
	    <Type>http://oauth.net/core/1.0/parameters/uri-query</Type>
	    <URI>http://<?php echo $server; ?>/oauth/authorize</URI>
	</Service>
	<Service>
	    <Type>http://oauth.net/core/1.0/endpoint/access</Type>
	    <Type>http://oauth.net/core/1.0/parameters/auth-header</Type>
	    <Type>http://oauth.net/core/1.0/parameters/uri-query</Type>
	    <Type>http://oauth.net/core/1.0/signature/HMAC-SHA1</Type>
	    <Type>http://oauth.net/core/1.0/signature/PLAINTEXT</Type>
	    <URI>http://<?php echo $server; ?>/oauth/access_token</URI>
	</Service>
    </XRD>
</XRDS>
