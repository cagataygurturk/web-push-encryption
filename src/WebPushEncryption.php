<?php

namespace RStiekema\WebPushEncryption;

require __DIR__.'/../vendor/autoload.php';


use \Mdanter\Ecc\Curves\CurveFactory;
use \Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use \Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use \Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;
use \Mdanter\Ecc\Serializer\PublicKey\PemPublicKeySerializer;


class WebPushEncryption {


    public function encryptMessage($clientEndpoint, $clientP256dh, $clientAuth, $messageText) {
        echo "encrypting message for $clientEndpoint\n";
        echo "$messageText\n";


        $serverP256 = self::getServerP256();
        $p256Secret = self::calculateSecret($clientP256dh, $serverP256['private']);

        print_r($serverP256);
    }


    private static function calculateSecret($clientP256dh, $serverPrivateKey) {
        $publicKeySerializer = new DerPublicKeySerializer();
        $publicKey = $publicKeySerializer->parse($clientP256dh);

        var_dump($publicKey);
    }


    private static function getServerP256() {
        $generator = CurveFactory::getGeneratorByName('nist-p256');

        $privateKeySerializer = new PemPrivateKeySerializer(new DerPrivateKeySerializer());
        $privateKey           = $generator->createPrivateKey();
        $privateKeyStr        = $privateKeySerializer->serialize($privateKey);

        $publicKeySerializer = new PemPublicKeySerializer(new DerPublicKeySerializer());
        $publicKey           = $privateKey->getPublicKey();
        $publicKeyStr        = $publicKeySerializer->serialize($publicKey);

        return array(
            'private' => $privateKeyStr,
            'public'  => $publicKeyStr
        );
    }


    function send_message($endpoint, $message) {
    	$subscription = get_subscription($endpoint);

    	echo $subscription['p256dh']."\n";

    	if (!$subscription) {
    		return false;
    	}

    	// 3a. Store the to-be-encrypted payload in |plaintext|.
    	$plaintext     = $message;

    	// 3b. Store the decoded client’s public key (p256dh) in |client_public|.
    	$client_public = $subscription['p256dh'];

    	// 3c. Store the client’s authentication secret in |auth|.
    	$auth          = $subscription['auth'];

    	// 3d. Generate 16 cryptographically secure random bytes, store them in |salt|.
    	$salt          = openssl_random_pseudo_bytes(16); // to be replaced by random_bytes() (php 7)

    	// 3e i. Create a P-256 public/private key-pair for the server.
    	$server_p256   = create_server_p256(); // 3e i

    	// 3e ii. Calculate the P-256 secret with the server’s private key, and the
    	//        client’s decoded public key (|client_public|).
    	$p256_secret   = '';

    	// 3e iii. Store the ephemeral public key to |server_public|, the shared secret
    	//         secret to |shared_secret|.
    	$shared_secret = '';

    }



}
