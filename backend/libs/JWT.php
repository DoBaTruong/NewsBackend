<?php 
namespace libs;

use app\exceptions\InternalServerException;

class JWT
{
    public static function decode($jwt, $key = null, $verify = true)
	{
		$tks = explode('.', $jwt);
		if (count($tks) != 3) {
			$error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
            ]);
            exit();
		}
		list($headb64, $bodyb64, $cryptob64) = $tks;
		if (null === ($header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64)))) {
			$error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
            ]);
            exit();
		}
		if (null === $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64))) {
			$error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage(),
            ]);
            exit();
		}
		$sig = JWT::urlsafeB64Decode($cryptob64);
		if ($verify) {
			if (empty($header->alg)) {
				$error = new InternalServerException();
                Response::json(200, [
                    'code' => $error->getCode(),
                    'message' => $error->getMessage(),
                ]);
                exit();
			}
			if ($sig != JWT::sign("$headb64.$bodyb64", $key, $header->alg)) {
				$error = new InternalServerException();
                Response::json(200, [
                    'code' => $error->getCode(),
                    'message' => $error->getMessage(),
                    'status' => 'status 5'
                ]);
                exit();
			}
		}
		return $payload;
	}

    public static function encode($payload, $key, $algo = 'HS256')
    {
        $header = [
            'typ' => 'JWT',
            'alg' => $algo
        ];
        $segments = array();
        $segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($header));
		$segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($payload));
        $signing_input = implode('.', $segments);
		$signature = JWT::sign($signing_input, $key, $algo);
        $segments[] = JWT::urlsafeB64Encode($signature);
		return implode('.', $segments);
    }

    public static function sign($msg, $key, $method = 'HS256')
	{
		$methods = [
			'HS256' => 'sha256',
			'HS384' => 'sha384',
			'HS512' => 'sha512',
        ];
		if (empty($methods[$method])) {
			$error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage()
            ]);
            exit();
		}
		return hash_hmac($methods[$method], $msg, $key, true);
	}

    public static function urlsafeB64Decode($input)
	{
		$remainder = strlen($input) % 4;
		if ($remainder) {
			$padlen = 4 - $remainder;
			$input .= str_repeat('=', $padlen);
		}
		return base64_decode(strtr($input, '-_', '+/'));
	}

    public static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    public static function jsonDecode($input)
	{
		$obj = json_decode($input);
		if (function_exists('json_last_error') && $errno = json_last_error()) {
			$error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage()
            ]);
		} else if ($obj === null && $input !== 'null') {
			$error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage()
            ]);
		}
		return $obj;
	}

    public static function jsonEncode($input)
    {
        $json = json_encode($input);
        if(function_exists('json_last_error') && $errno = json_last_error()) {
            $error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage()
            ]);
            exit();
        } else if ($json === 'null' && $input !== null) {
			$error = new InternalServerException();
            Response::json(200, [
                'code' => $error->getCode(),
                'message' => $error->getMessage()
            ]);
            exit();
		}
		return $json;
    }
}
