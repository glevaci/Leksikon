<?php
session_start();

header('Content-type: text/html; charset=UTF-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'autoload.php';
require_once 'php_functions.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;


// init app with app id and secret
FacebookSession::setDefaultApplication( '1576331335963540','92ffa24dc7b30a869f8ae42d4df07dc5' );
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper('http://web.studenti.math.pmf.unizg.hr/~glevaci/rp2/Projekt/fbconfig.php' );
try {
  $session = $helper->getSessionFromRedirect();
} 
catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
  echo $ex->getMessage();
} 
catch( Exception $ex ) {
  // When validation fails or other local issues
  echo $ex->getMessage();
}

// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
      /* ---- Session Variables -----*/
	    $_SESSION['FBID'] = $fbid;           
      $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;

      checkFacebook($_SESSION['FULLNAME'], $_SESSION['EMAIL']);

      /* ---- header location after session ----*/
      //header("Location: index.php");
} else {
  $loginUrl = $helper->getLoginUrl(array('req_perms' => 'email'));
 header("Location: ".$loginUrl);
}
?>