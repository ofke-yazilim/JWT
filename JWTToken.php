<?php


class JWTToken
{
    public $token_header    = array();
    public $token_payload   = array();
    public $token_function  = "";
    public $header          = array();
    public $payload         = array();
    public $JWTToken        = "";
    public $error           = array();
    public $secret_key      = "";
    public $binary_signature_base64 = "";

    /**
     * ClientService constructor.
     * @param array $data : Service Request Payload Data
     * @param string $token_type : RS256 or HS256 etc.
     * @param array $token_payload : if you need different payload to create a token
     */
    public function __construct($data=array(),$token_type="",$token_payload=array())
    {
        $this->payload        = $data;
        $this->token_payload  = $token_payload;
        $this->token_function = "create".$token_type."Token";
    }

    /**
     * Client Server Create RS256 jwt token.
     * If you want to create RS256 jwt Token then you must create firstly private.key and secondly public.key certificate.
     * a) Open the Computer terminal screen and then write that command : openssl genrsa -out private.key 2048
     * b) You created a private.key with above command, now you would write that command to create certificate :
     *     openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:4096 -keyout private.key -out certificate2.crt
     * c) You could go to https://jwt.io/ and try to create JWT Token with private.key and certificate.
     */
    public function createRS256Token(){

        // Token kullanım süresi 120 dakika sonrasına ayarlanıyor.
        $this->token_payload['exp'] = strtotime("+120 minutes", time());

        $header_base64   = str_replace('=', '', strtr(base64_encode(json_encode($this->token_header)), '+/', '-_'));
        $payload_base64  = str_replace('=', '', strtr(base64_encode(json_encode($this->token_payload)), '+/', '-_'));

        $data           = $header_base64.".".$payload_base64;

        $fp             = fopen("certificates/certificate.crt", "r+");
        $certificate    = "";
        while (($line   = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
            $certificate .= $line."\n";
        }

        $fp             = fopen("certificates/private.key", "r+");
        $private_key    = "";
        while (($line   = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
            $private_key .= $line."\n";
        }

        openssl_sign($data, $binary_signature, $private_key, "SHA256");

        // Check signature
        $ok = openssl_verify($data, $binary_signature, $certificate, "SHA256");
        $this->binary_signature_base64 = $binary_signature;
        if ($ok == 1) {
            $binary_signature_base64  = str_replace('=', '', \strtr(\base64_encode($binary_signature), '+/', '-_'));
            $this->JWTToken           = $data.".".$binary_signature_base64;
        } elseif ($ok == 0) {
            $this->error = [
                'function' => 'createRS256Token',
                'message'  => 'JWT Token was not created.'
            ];
        } else {
            $this->error = [
                'function' => 'createRS256Token',
                'message'  => 'JWT Token was not created. Check signature.'
            ];
        }

    }

    /**
     * Client Server Create HS256 jwt token.
     * If you
     */
    public function createHS256Token(){
        // Create token header as a JSON string
        $header  = json_encode($this->token_header);

        // Create token payload as a JSON string
        $payload = json_encode($this->token_payload);

        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret_key, true);

        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Create JWT
        $this->JWTToken = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * return the jwt token
     */
    public function getJWTToken(){
        return $this->JWTToken;
    }

    /**
     * @param $token
     * If you want to verify the RS256 Token then you could use the following function
     * RS256 Token could be verified with certificate content and token value.
     */
    public function verifyRS256Token($token){
        $fp             = fopen("certificates/certificate.crt", "r+");
        $certificate    = "";
        while (($line   = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
            $certificate .= $line."\n";
        }

        $token_parse          = explode(".",$token);
        $header               = base64_decode($token_parse[0]);
        $payload              = base64_decode($token_parse[1]);
        $binary_signature     = base64_decode(str_replace(array('-', '_',''), array('+', '/','='), $token_parse[2]));

        // Check signature
        $ok = openssl_verify($token_parse[0].".".$token_parse[1], $binary_signature, $certificate, "SHA256");

        if ($ok == 1) {
            echo "Token is true.<br><br>";
        } elseif ($ok == 0) {
            echo "Token is wrong.<br><br>";
        } else {
            echo "Token is wrong. Check binary signature.<br><br>";
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}