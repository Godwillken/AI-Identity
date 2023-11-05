
<?php
class TypingDNAVerifyClient {
    private $clientId;
    private $applicationId;
    private $secret;

    const VERSION = 1.1;

    function __construct(string $clientId, string $applicationId, string $secret) {
        if (!$clientId) {
            throw new Exception('Missing client id');
        }

        if (!$applicationId) {
            throw new Exception('Missing application id');
        }

        if (!$secret) {
            throw new Exception('Missing secret');
        }

        $this->clientId = $clientId;
        $this->applicationId = $applicationId;
        $this->secret = $secret;
    }

    private static $host = 'https://verify.typingdna.com';

    private function encrypt(string $string, string $secret, string $salt) {
        $encryptionKey = hash_pbkdf2('sha512', $secret, $salt, 10000, 32, true);
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($string, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv);

        return bin2hex($encrypted) . bin2hex($iv);
    }

    private function encryptPayload(array $payload) {
        $payloadArray = array();
        $validPayloadKeys = array('email', 'phoneNumber', 'countryCode', 'language', 'mode');

        foreach ($validPayloadKeys as &$key) {
            if (isset($payload[$key])) {
                $payloadArray[$key] = $payload[$key];
            }
        }

        $payloadArray['ts'] = microtime();

        return TypingDNAVerifyClient::encrypt(json_encode($payloadArray), $this->secret, $this->applicationId);
    }

    public function getDataAttributes(array $payload) {
        return array(
            'clientId' => $this->clientId,
            'applicationId' => $this->applicationId,
            'payload' => $this->encryptPayload($payload),
        );
    }

    public function sendOTP(array $payload) {
        return $this->request('/otp/send', $payload);
    }

    public function validateOTP(array $payload, string $code) {
        return $this->request('/otp/validate', $payload, array('code' => $code));
    }

    private function request(string $path, array $payload, array $data = array()) {
        $body = array_merge(array(
            'clientId' => $this->clientId,
            'applicationId' => $this->applicationId,
            'payload' => $this->encryptPayload($payload),
            'version' => self::VERSION
        ), $data);

        $curl = curl_init(TypingDNAVerifyClient::$host . $path);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
?>

<title>Verifying: <?php echo $_SESSION['username']; ?> </title>

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
<link href="https://forlonga.com/index.html" rel="canonical">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="icon.png" rel="icon" sizes="512x512" type="image/png">
<link href="css/AI_&_Identity.css?v=271" rel="stylesheet">
<link href="css/TypingDNAVerifyClient.css?v=271" rel="stylesheet">
<meta name="theme-color" content="#4B0181"/>

</head>
<body>
</body>
</html>