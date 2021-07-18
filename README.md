# JWT
Create HS256 and RS256 Token with PHP

#### Create RS256 Token at client side and verify at server side
##### Summary
To use the HS256 Token firstly we must create private key and certificate. You can follow the following steps 
to create them. `I put private key and certificate under the "certificates" folder. 
Because this JWT class get private key and certificate content under the "certificates" folder.`
- Create private key the following command.
```
openssl genrsa -out private.key 2048
```
- Create certificate the following command.
```
openssl req -x509 -sha256 -nodes -days 365 -newkey rsa:4096 -keyout private.key -out certificate.crt
```
>How to add JWT class at own project
```
include_once 'JWTToken.php';
```
>How to create RS256 Token with class
```
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
echo $jwt->getJWTToken();
```

>How to verify JWT Token with class at server side
```
$jwt->verifyRS256Token($token);
## If you want to verify the token which created at previous step
$jwt->verifyRS256Token($jwt->getJWTToken());
```

#### Create HS256 Token at client side
>How to add JWT class at own project
```
include_once 'JWTToken.php';
```
>How to create RS256 Token with class
```
//if you HS256 Token then you must use token header.
$token_header = array(
    "alg" => "HS256",
    "typ" => "JWT"
);

//if you HS256 Token then you must use token payload.
$token_payload = [
    "name" => "omer faruk",
    "surname" => "__",
    "city" => "istanbul",
    "province" => "marmara"
];
$jwt = new JWTToken();

$jwt->token_payload = $token_payload;
$jwt->token_header = $token_header;
$jwt->secret_key = "1213149842934783947393483973232";
$jwt->token_function = "createHS256Token";
$jwt->createHS256Token();
echo $jwt->getJWTToken();
```

#### LAST
 - [If you want to create Token online then click](https://jwt.io/)
 - [If you want to look at working code](http://okesmez.com/JWT/test.php)




