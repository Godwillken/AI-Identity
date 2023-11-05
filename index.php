
<?php
    session_start(); 

    // if there is a username in session, redirect the user to verify page
    if(isset($_SESSION['username'])) {
        header('Location:verify.php');
        die();
    }

     // helper function to make http requests
     function http($url, $params=false) {

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if($params) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        return json_decode(curl_exec($ch));
    }


    // set Okta credentials 
    $client_id = '0oabpa2f20gnNqAAW5d7';
    $client_secret = 'AEfYS4CErFsoo796EtaYfWqjUXTWv6jcFVG3DcF01jOCTr8OvaLuK2xZgBd9CtUe';
    $redirect_uri = 'https://forlonga.com/ai&identity/';
    $metadata_url = 'https://dev-06310232.okta.com/oauth2/default/.well-known/openid-configuration';


    if(isset($_GET['error'])) {
        die('Authorization server returned an error: '.htmlspecialchars($_GET['error']));
    }

    // get OAuth2 authorization, exchange and introspection endpoints
    $metadata = http($metadata_url);

    // if there is code param in the link, it means it's a redirect from Okta and we need to begin the OAuth2 exchange flow
    if(!isset($_SESSION['username']) && isset($_GET['code'])) {

        // exchange OAuth2 code for an access token
        $response = http($metadata->token_endpoint, [
            'grant_type' => 'authorization_code',
            'code' => $_GET['code'],
            'redirect_uri' => $redirect_uri,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        if(!isset($response->access_token)) {
            die('Error fetching access token');
        }

        // make the introspection request and get the username for the logged user
        $token = http($metadata->introspection_endpoint, [
            'token' => $response->access_token,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);

        // save the username in php session and redirect the user to verify page
        if($token->active == 1) {
            $_SESSION['username'] = $token->username; 
            $verifier = $token->client_secret;
            header('Location:verify.php');
            die();
        }
    }

    // generate Okta authorization link
    $authorize_url = $metadata->authorization_endpoint.'?'.http_build_query([
        'response_type' => 'code',
        'client_id' => $client_id,
        'redirect_uri' => $redirect_uri,
        'state' => time(),
        'scope' => 'openid',
    ]);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>AI & IDENTITY</title>
<meta name="description" content="Forlonga App - AI & IDENTITY using OKTA">
<meta name="keywords" content="Forlonga App">
<meta name="author" content="Godwill Kenyi">
<meta name="categories" content="Software & Application">
<meta name="robots" content="index, follow">
<meta name="generator" content="Ken">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://forlonga.com/index.html" rel="canonical">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="css/AI_&_Identity.css?v=272" rel="stylesheet">
<link href="css/index.css?v=272" rel="stylesheet">
<script src="jquery-1.12.4.min.js"></script>
<script src="jquery.ui.effect.min.js"></script>
<script src="wb.panel.min.js"></script>

<meta name="theme-color" content="#4B0181"/>
<link rel="stylesheet" media="all and (orientation:landscape)" href="cs/index.css">

<script src="index.js?v=272"></script>
</head>
<body>

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
   <li role="menuitem"><a href="./index.php" itemprop="url" class="nav-link PanelMenu1-effect"><i class="account-balance-wallet"></i><span itemprop="name">&nbsp; Start Organization</span></a></li>
   <li role="menuitem"><a href="./index.php" itemprop="url" class="nav-link PanelMenu1-effect"><i class="perm-phone-msg"></i><span itemprop="name">Contact</span></a></li>
</ul>
<div id="PanelMenu1-footer">Menu,  v 12.1</div>
</div>

</div>
</div>
</div>
</div>
</div>
<div id="wb_indexLayoutGrid2">
<div id="indexLayoutGrid2-overlay"></div>
<div id="indexLayoutGrid2">
<div class="row">
<div class="col-1">
<div id="wb_indexLayoutGrid4">
<div id="indexLayoutGrid4-overlay"></div>
<div id="indexLayoutGrid4">
<div class="row">
<div class="col-1">
</div>
</div>
</div>
</div>
</div>
<div class="col-2">
<div id="wb_Card25" style="display:flex;width:100%;text-align:center;z-index:4;" class="card">
   <div id="Card25-card-body">
      <img id="Card25-card-item0" src="images/4longa.png" width="127" height="145" alt="" title="">
      <div id="Card25-card-item1">PROJECT NAME: FORLONGA</div>
      <div id="Card25-card-item2">FOR: AI & IDENTITY USING OKTA</div>
   </div>
</div>
<input type="button" id="indexButton1" onclick="window.location.href='<?php echo $authorize_url; ?>';return false;" name="" value="Start Organization" style="display:inline-block;width:170px;height:41px;z-index:5;">
<div id="wb_indexText1">
<span style="color:#FFFFFF;font-family:'Century Gothic';font-size:20px;"><strong>Supported by: forlonga.com</strong></span>
</div>
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