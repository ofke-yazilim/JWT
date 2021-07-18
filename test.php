<?php

include_once 'JWTToken.php';

############################################################
# Create RS256 jwt TOKEN.
############################################################

//if you RS256 Token then you must use token header.
$token_header = [
    "alg" => "RS256",
    "typ" => "JWT"
];

//if you RS256 Token then you must use token payload.
$token_payload = [
    "aud" => "https://okesmez.com",
    "iss" => "https://okesmez.com/b9cefc6e9de1",
    "sub" => "https://okesmez.com/b9cefc6e9de1",
];
$jwt = new JWTToken();

$jwt->token_payload = $token_payload;
$jwt->token_header  = $token_header;
## Create RS256 token
$jwt->createRS256Token();
echo "############################################################<br>
      # You can see the RS256 Token at bellow<br>
      ############################################################<br>";
echo $jwt->getJWTToken();

echo "<br><br><br>";

## You can could use the following code to verify the token on the server side.
$jwt->verifyRS256Token($jwt->getJWTToken());

############################################################
# Create  HS256 jwt TOKEN.
############################################################

$token_header = array(
    "alg" => "HS256",
    "typ" => "JWT"
);

//if you RS256 Token then you must use token payload.
$token_payload = [
    "name" => "omer faruk",
    "surname" => "kesmez",
    "city" => "istanbul",
    "province" => "marmara"
];
$jwt = new JWTToken();

$jwt->token_payload = $token_payload;
$jwt->token_header = $token_header;
$jwt->secret_key = "1213149842934783947393483973232";
$jwt->createHS256Token();
echo "############################################################<br>
      # You can see the HS256 Token at bellow<br>
      ############################################################<br>";
echo $jwt->getJWTToken();