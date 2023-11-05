<?php
session_start();

$client_id = '0oabpa2f20gnNqAAW5d7';
$client_secret = 'AEfYS4CErFsoo796EtaYfWqjUXTWv6jcFVG3DcF01jOCTr8OvaLuK2xZgBd9CtUe';
$redirect_uri = 'https://forlonga.com/ai&identity/';
$metadata_url = 'https://dev-06310232.okta.com/oauth2/default/.well-known/openid-configuration';


if (isset($_GET['logout']) == 'out') {
  unset($_SESSION['username']);
  unset($_SESSION['sub']);
  session_destroy();
  header('Location:index.php');
  die();
}

if(!isset($_SESSION['username'])) {
  header('Location:index.php');
}

$metadata = http($metadata_url);

if(!isset($_GET['code'])) {

  $_SESSION['state'] = bin2hex(random_bytes(5));
  $_SESSION['code_verifier'] = bin2hex(random_bytes(50));
  $code_challenge = base64_urlencode(hash('sha256', $_SESSION['code_verifier'], true));

  $authorize_url = $metadata->authorization_endpoint.'?'.http_build_query([
    'response_type' => 'code',
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'state' => $_SESSION['state'],
    'scope' => 'openid profile',
    'code_challenge' => $code_challenge,
    'code_challenge_method' => 'S256',
  ]); 

} else {

  if($_SESSION['state'] != $_GET['state']) {
    die('Authorization server returned an invalid state parameter');
  }

  if(isset($_GET['error'])) {
    die('Authorization server returned an error: '.htmlspecialchars($_GET['error']));
  }

  $response = http($metadata->token_endpoint, [
    'grant_type' => 'authorization_code',
    'code' => $_GET['code'],
    'redirect_uri' => $redirect_uri,
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code_verifier' => $_SESSION['code_verifier'],
  ]);


  if(!isset($response->access_token)) {
    die('Error fetching access token');
  }

  $userinfo = http($metadata->userinfo_endpoint, [
    'access_token' => $response->access_token,
  ]);

  if($userinfo->sub) {
    $_SESSION['sub'] = $userinfo->sub;
    $_SESSION['username'] = $userinfo->preferred_username;
    $_SESSION['profile'] = $userinfo; 
    die();
  }

}

// Base64-urlencoding is a simple variation on base64-encoding
// Instead of +/ we use -_, and the trailing = are removed.
function base64_urlencode($string) {
  return rtrim(strtr(base64_encode($string), '+/', '-_'), '=');
}


function http($url, $params=false) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if($params)
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
  return json_decode(curl_exec($ch));
}

?>


<?php

   // set Okta credentials 
   $app_client_id = 'e0631a9a202c3c10afcd3f07588f2056';
   $secret = 'cdb384243076062de9109e262b8fcdde';
   $application_id = "7e5e5230311d142b9a8ac87ecf9bfae5"; 


    // include TypingDna Verify library
    include('TypingDNAVerifyClient.php');

    // create and store a TypingDNAVerifyClient instance using your Verify credentials
    $typingDNAVerifyClient = new TypingDNAVerifyClient($app_client_id, $application_id, $secret);

    // if there is opt param in the link it means the Verify flow is completed and we need to validate it
    if( isset($_GET['otp']) ) {

        // validate opt code
        $response = $typingDNAVerifyClient->validateOTP([
            'email' => $_SESSION['username'],
        ], $_GET['otp']);

        // print response
        //print_r($response);
        header('Location:https://dev-06310232.okta.com/app/UserHome');
        die();
    }

    // create the data object required to generate Verify button
    $typingDNADataAttributes = $typingDNAVerifyClient->getDataAttributes([
        'email' =>  $_SESSION['username'],
        'language' => "en",
        'mode' => "standard"
    ]);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title></title>
<meta name="description" content="The best software & application design network
Developer in South Sudan,
IT in South Sudan
Best Website in South Sudan,
Best Website Designer in South Sudan,
Godwill Kenyi,
Kenny,">
<meta name="keywords" content="Forlonga,
Godwill Kenyi,
Eng. Godwill Kenyi,
Eng. Ken,
Best Developer in South Sudan,
Best IT in South Sudan,
Software Developer in South Sudan,
IT in South Sudan,">
<meta name="author" content="Godwill Kenyi">
<meta name="categories" content="Software & Application">
<meta name="robots" content="index, follow">
<meta name="generator" content="Ken">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://forlonga.com/index.html" rel="canonical">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="css/AI_&_Identity.css?v=279" rel="stylesheet">
<link href="css/verify.css?v=279" rel="stylesheet">
<script src="jquery-1.12.4.min.js"></script>
<script src="wb.panel.min.js"></script>
<script src="jquery.ui.effect.min.js"></script>
<script src="wwb17.min.js"></script>

<meta name="theme-color" content="#4B0181"/>
<script src="https://cdn.typingdna.com/verify/typingdna-verify.js"></script>



<script src="verify.js?v=279"></script>
</head>
<body onload="ShowObject('indexButton2', 0);return false;">

<div id="wb_Menu">
<div id="Menu">
<div class="row">
<div class="col-1">
<div id="wb_Image1" style="display:inline-block;width:37px;height:39px;z-index:0;">
<img src="images/4longa.png" id="Image1" alt="" style="float:left;" width="33" height="38">
</div>
</div>
<div class="col-2">
<div id="wb_Text8" class="caption" style="left: 40px; position: fixed;">
<span style="color:#FFFFFF;"><sub>AI &amp; IDENTITY<br></sub></span><span style="color:#87CEEB;"><strong>www.forlonga.com/ai&amp;identity</strong></span>
</div>
<div id="wb_PanelMenu1" style="display:inline-block;width:98px;height:59px;text-align:right;z-index:2;">
<a href="#PanelMenu1_markup" id="PanelMenu1">Menu&nbsp; </a>
<div id="PanelMenu1_markup">
<ul role="menu" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
   <li role="menuitem"><a href="./verify.php" itemprop="url" class="nav-link PanelMenu1-effect"><i class="account-balance-wallet"></i><span itemprop="name">&nbsp; Start Organization</span></a></li>
   <li role="menuitem"><a href="./index.php" itemprop="url" class="nav-link PanelMenu1-effect"><i class="perm-phone-msg"></i><span itemprop="name">Contact</span></a></li>
</ul>
<div id="PanelMenu1-footer">Menu,  v 12.1</div>
</div>

</div>
</div>
</div>
</div>
</div>
<div id="wb_config">
<div id="config-overlay"></div>
<div id="config">
<div class="row">
<div class="col-1">
<div id="wb_indexLayoutGrid3">
<div id="indexLayoutGrid3-overlay"></div>
<div id="indexLayoutGrid3">
<div class="row">
<div class="col-1">
</div>
</div>
</div>
</div>
</div>
<div class="col-2">
<div id="wb_indexCard1" style="display:flex;width:100%;text-align:center;z-index:4;" class="card">
   <div id="indexCard1-card-body">
      <img id="indexCard1-card-item0" src="images/4longa.png" width="127" height="145" alt="" title="">
      <div id="indexCard1-card-item1">FORLONGA</div>
   </div>
</div>
<div id="wb_indexText2">
<span style="color:#FFFFFF;font-family:'Century Gothic';font-size:27px;"><strong>Successfully Logged-In</strong></span>
</div>
<label for="" id="indexLabel1" style="display:block;width:100%;line-height:17px;z-index:6;">Email Address:</label>
<input type="text" id="cid" style="display:block;width: 100%;height:27px;z-index:7;" name="email" value="<?php echo $_SESSION['username']; ?>" disabled spellcheck="false" placeholder="Enter here . . . ">
<label for="" id="indexLabel4" style="display:block;width:100%;line-height:17px;z-index:8;">Client ID:</label>
<input type="text" id="indexEditbox3" style="display:block;width: 100%;height:27px;z-index:9;" name="id" value="<?php echo $client_id; ?>" disabled spellcheck="false" placeholder="Enter here . . . ">
<label for="" id="indexLabel3" style="display:block;width:100%;line-height:17px;z-index:10;">Client Secret:</label>
<input type="text" id="indexEditbox1" style="display:block;width: 100%;height:27px;z-index:11;" name="secret" value="<?php echo $client_secret; ?>" disabled spellcheck="false" placeholder="Enter here . . . ">
<label for="" id="indexLabel2" style="display:block;width:100%;line-height:17px;z-index:12;">Verifier Code:</label>
<input type="text" id="indexEditbox2" style="display:block;width: 100%;height:27px;z-index:13;" name="verifier" value="<?php echo $_SESSION['code_verifier']; ?>" disabled spellcheck="false" placeholder="Enter here . . . ">
<input type="button" id="verifyButton1" name="" value="Verify with TYPINGDNA" style="display:inline-block;width:198px;height:32px;z-index:14;" class = "typingdna-verify"
           data-typingdna-client-id=<?php echo $typingDNADataAttributes["clientId"]?>
           data-typingdna-application-id=<?php echo $typingDNADataAttributes["applicationId"] ?> 
           data-typingdna-payload=<?php echo $typingDNADataAttributes["payload"]?> 
           data-typingdna-callback-fn= "callbackFn">
<input type="button" id="submit_bttn" onclick="window.location.href='https://dev-06310232.okta.com/app/UserHome';return false;" name="" value="Go To Dashboard" style="display:inline-block;width:161px;height:32px;z-index:15;">
</div>
<div class="col-3">
</div>
</div>
</div>
</div>
<div id="wb_FText">
<div id="FText">
<div class="row">
<div class="col-1">
<div id="wb_Text3" >



&nbsp;
</div>
</div>
</div>
</div>
</div>
</body>
</html>