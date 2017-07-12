<?php

/**
 * Latzko Websoftware GmbH
 *
 * Copyright (c) 2017 ML
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'LwsNews2Facebook\LwsNews2Facebook'                					=> 'system/modules/z_lwsnews2facebook/LwsNews2Facebook.php',
	'Facebook\Facebook'                									=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Facebook.php',
	'Facebook\FacebookApp'                								=> 'system/modules/z_lwsnews2facebook/lib/Facebook/FacebookApp.php',
	'Facebook\FacebookClient'                							=> 'system/modules/z_lwsnews2facebook/lib/Facebook/FacebookClient.php',
	'Facebook\FacebookResponse'                							=> 'system/modules/z_lwsnews2facebook/lib/Facebook/FacebookResponse.php',
	'Facebook\HttpClients\HttpClientsFactory'                			=> 'system/modules/z_lwsnews2facebook/lib/Facebook/HttpClients/HttpClientsFactory.php',
	'Facebook\HttpClients\FacebookHttpClientInterface'                	=> 'system/modules/z_lwsnews2facebook/lib/Facebook/HttpClients/FacebookHttpClientInterface.php',
	'Facebook\HttpClients\FacebookCurl'                					=> 'system/modules/z_lwsnews2facebook/lib/Facebook/HttpClients/FacebookCurl.php',
	'Facebook\HttpClients\FacebookCurlHttpClient'                		=> 'system/modules/z_lwsnews2facebook/lib/Facebook/HttpClients/FacebookCurlHttpClient.php',
	'Facebook\PseudoRandomString\PseudoRandomStringGeneratorFactory'    => 'system/modules/z_lwsnews2facebook/lib/Facebook/PseudoRandomString/PseudoRandomStringGeneratorFactory.php',
	'Facebook\PseudoRandomString\PseudoRandomStringGeneratorInterface'  => 'system/modules/z_lwsnews2facebook/lib/Facebook/PseudoRandomString/PseudoRandomStringGeneratorInterface.php',
	'Facebook\PseudoRandomString\PseudoRandomStringGeneratorTrait'      => 'system/modules/z_lwsnews2facebook/lib/Facebook/PseudoRandomString/PseudoRandomStringGeneratorTrait.php',
	'Facebook\PseudoRandomString\McryptPseudoRandomStringGenerator'     => 'system/modules/z_lwsnews2facebook/lib/Facebook/PseudoRandomString/McryptPseudoRandomStringGenerator.php',
	'Facebook\Url\UrlDetectionInterface'                				=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Url/UrlDetectionInterface.php',
	'Facebook\PersistentData\PersistentDataFactory'                		=> 'system/modules/z_lwsnews2facebook/lib/Facebook/PersistentData/PersistentDataFactory.php',
	'Facebook\PersistentData\PersistentDataInterface'                	=> 'system/modules/z_lwsnews2facebook/lib/Facebook/PersistentData/PersistentDataInterface.php',
	'Facebook\PersistentData\FacebookSessionPersistentDataHandler'      => 'system/modules/z_lwsnews2facebook/lib/Facebook/PersistentData/FacebookSessionPersistentDataHandler.php',
	'Facebook\Url\FacebookUrlDetectionHandler'                			=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Url/FacebookUrlDetectionHandler.php',
	'Facebook\Helpers\FacebookRedirectLoginHelper'                		=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Helpers/FacebookRedirectLoginHelper.php',
	'Facebook\Authentication\OAuth2Client'                				=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Authentication/OAuth2Client.php',
	'Facebook\Exceptions\FacebookSDKException'                			=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Exceptions/FacebookSDKException.php',
	'Facebook\Url\FacebookUrlManipulator'                				=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Url/FacebookUrlManipulator.php',
	'Facebook\Authentication\AccessToken'                				=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Authentication/AccessToken.php',
	'Facebook\FacebookRequest'                							=> 'system/modules/z_lwsnews2facebook/lib/Facebook/FacebookRequest.php',
	'Facebook\Http\RequestBodyInterface'                				=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Http/RequestBodyInterface.php',
	'Facebook\Http\RequestBodyUrlEncoded'                				=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Http/RequestBodyUrlEncoded.php',
	'Facebook\Http\GraphRawResponse'                					=> 'system/modules/z_lwsnews2facebook/lib/Facebook/Http/GraphRawResponse.php',
	'Facebook\PseudoRandomString\RandomBytesPseudoRandomStringGenerator'                					=> 'system/modules/z_lwsnews2facebook/lib/Facebook/PseudoRandomString/RandomBytesPseudoRandomStringGenerator.php',

	
));
