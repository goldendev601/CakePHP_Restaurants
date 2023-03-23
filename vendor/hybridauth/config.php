<?php

/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------
    //echo dirname(dirname(__FILE__));die();
return
		array(
			"base_url" => BASE_URL."users/social_redirect",
			"providers" => array(
				// openid providers
				"OpenID" => array(
					"enabled" => true
				),
				"Yahoo" => array(
					"enabled" => true,
					"keys" => array("key" => "", "secret" => ""),
				),
				"AOL" => array(
					"enabled" => true
				),
				"Google" => array(
					"enabled" => true,
					"keys" => array("id" => GOOGLE_CLIENT, "secret" => GOOGLE_SECRET),
				),
				/*"Facebook" => array(
					"enabled" => true,
					"keys" => array("id" => "1781046502143427", "secret" => "4a68507118836a4f57515a4909c68bbc"),
					"trustForwarded" => true
				),*/
                "Facebook" => array(
					"enabled" => true,
					"keys" => array("id" => "1770654449929870", "secret" => "61ea028a669ef81197be3b048f36207e"),
					"trustForwarded" => true
				),
				"Twitter" => array(
					"enabled" => true,
					"keys" => array("key" => "", "secret" => ""),
					"includeEmail" => false
				),
				// windows live
				"Live" => array(
					"enabled" => true,
					"keys" => array("id" => "", "secret" => "")
				),
				"LinkedIn" => array(
					"enabled" => true,
					"keys" => array("key" => "", "secret" => "")
				),
				"Foursquare" => array(
					"enabled" => true,
					"keys" => array("id" => "", "secret" => "")
				),
			),
			// If you want to enable logging, set 'debug_mode' to true.
			// You can also set it to
			// - "error" To log only error messages. Useful in production
			// - "info" To log info and error messages (ignore debug messages)
			"debug_mode" => false,
			// Path to file writable by the web server. Required if 'debug_mode' is not false
			//"debug_file" => "https://www.klikly.in/users/social.log",
			"debug_file" => dirname(dirname(__FILE__))."/social.log",
);
