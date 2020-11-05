<?php
/**
 * @description Json Web Token
 *
 * @package Library\Jwt
 *
 * @author kovey
 *
 * @time 2020-02-16 10:56:25
 *
 */
namespace Kovey\Library\Jwt;

use Kovey\Library\Util\Json;
use Kovey\Library\Exception\TokenExpiredException;

class Jwt 
{
    const JWT_ALG = 'HS256';

    const JWT_TYPE = 'JWT';

    const JWT_ADMIN = 'JWT_API_ADMIN_KOVEY';

    /**
     * @description expired time
     *
     * @var int
     */
    private int $expired;

    /**
     * @description header
     *
     * @var Array
     */
    private Array $header;

    /**
     * @description encrypt key
     *
     * @var string
     */
    private string $key;

    /**
     * @description config
     *
     * @var Array
     */
    private $algConfig = array(
        'HS256'=>'sha256'
    );

    /**
     * @description construct
     *
     * @param string $key
     *
     * @param int $expired
     *
     * @return Jwt
     *
     */
    public function __construct(string $key, int $expired = 86400)
    {
        $this->expired = $expired;
        $this->key = $key;
        $this->header = array(
            'alg' => self::JWT_ALG,
            'typ' => self::JWT_TYPE
        );
    }

    /**
     * @description encode
     *
     * @param Array $ext
     *
     * @return string
     */
    public function encode(Array $ext) : string
    {
        $base64header = $this->base64UrlEncode(Json::encode($this->header));
        $base64payload = $this->base64UrlEncode(Json::encode(array(
            'iss' => self::JWT_ADMIN,
            'iat' => time(),
            'exp' => time() + $this->expired,
            'jti' => uniqid('JWT_API_UNIQID', true) . strval(microtime(true)) . random_int(1000000, 9999999),
            'ext' => $ext
        )));

        return $base64header . '.' . $base64payload . '.' . $this->signature($base64header . '.' . $base64payload, $this->key, $this->header['alg']);
    }

    /**
     * @description decode
     *
     * @param string $token
     *
     * @return Array
     */
    public function decode(string $token) : Array
    {
        $tokens = explode('.', $token);
        if (count($tokens) != 3) {
            throw new TokenExpiredException('token format error');
        }

        list($base64header, $base64payload, $sign) = $tokens;

        $base64decodeheader = Json::decode($this->base64UrlDecode($base64header));

        if (empty($base64decodeheader['alg'])) {
            throw new TokenExpiredException('alg is empty');
        }

        if ($this->signature($base64header . '.' . $base64payload, $this->key, $base64decodeheader['alg']) !== $sign) {
            throw new TokenExpiredException('sign error');
        }

        $payload = Json::decode($this->base64UrlDecode($base64payload));

        if (empty($payload['iat'])
            || $payload['iat'] > time()
        ) {
            throw new TokenExpiredException('iat is error');
        }

        if (empty($payload['exp'])
            || $payload['exp'] < time()
        ) {
            throw new TokenExpiredException('token expired');
        }

        return $payload['ext'] ?? array();
    }

    /**
     * @description process base64
     *
     * @param string $input
     *
     * @return string
     */
    private function base64UrlEncode(string $input) : string
    {
        return str_replace('=', '', strtr(Aes::encrypt($input, $this->key), '+/', '-_'));
    }

    /**
     * @description process base64 decode
     *
     * @param string $input
     *
     * @return string
     */
    private function base64UrlDecode(string $input) : string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }
        return Aes::decrypt(strtr($input, '-_', '+/'), $this->key);
    }

    /**
     * @description signature
     *
     * @param string $input
     *
     * @param string $key
     *
     * @param string $alg
     *
     * @return string
     *
     */
    private function signature(string $input, string $key, string $alg = 'HS256') : string
    {
        return $this->base64UrlEncode(hash_hmac($this->algConfig[$alg], $input, $key,true));
    }
}
