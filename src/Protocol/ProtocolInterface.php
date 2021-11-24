<?php
/**
 *
 * @description rpc protocol interface
 *
 * @package     Protocol
 *
 * @time        2019-11-16 21:18:40
 *
 * @author      kovey
 */
namespace Kovey\Library\Protocol;

interface ProtocolInterface
{
    /**
     * @description gzip compress
     *
     * @var int
     */
    const COMPRESS_GZIP = 1;

    /**
     * @description no compress
     *
     * @var int
     */
    const COMPRESS_NO = 0;

    /**
     * @description uncompress max length
     *
     * @var string
     */
    const COMPRESS_LENGTH = 4194304;

    /**
     * @description pack type
     *
     * @var string
     */
    const PACK_TYPE = 'N';

    /**
     * @description header length
     *
     * @var int
     */
    const HEADER_LENGTH = 4;

    /**
     * @description header length
     *
     * @var int
     */
    const HEADER_LENGTH_NEW = 8;

    /**
     * @description max length
     *
     * @var int
     */
    const MAX_LENGTH = 2097152;

    /**
     * @description length offset
     *
     * @var int
     */
    const LENGTH_OFFSET = 0;

    /**
     * @description body offset
     *
     * @var int
     */
    const BODY_OFFSET = 4;

    /**
     * @description constructor
     *
     * @param string $body
     *
     * @param string $key
     *
     * @param string $type
     *
     * @param bool $isPub
     *
     * @return ProtocolInterface
     */
    public function __construct(string $body, string $key, string $type = 'aes', bool $isPub = false);

    /**
     * @description parse
     *
     * @return bool
     */
    public function parse() : bool;

    /**
     * @description get path
     *
     * @return string
     */
    public function getPath() : string;

    /**
     * @description get method
     *
     * @return string
     */
    public function getMethod() : string;

    /**
     * @description get args
     *
     * @return Array
     */
    public function getArgs() : Array;

    /**
     * @description get clear
     *
     * @return string
     */
    public function getClear() : string;

    /**
     * @description get traceId
     *
     * @return string
     */
    public function getTraceId() : string;

    /**
     * @description get from
     *
     * @return string
     */
    public function getFrom() : string;

    /**
     * @description get client language
     *
     * @return string
     */
    public function getClientLang() : string;

    /**
     * @description get client version
     *
     * @return string
     */
    public function getVersion() : string;

    /**
     * @description get span id
     *
     * @return string
     */
    public function getSpanId() : string;

    /**
     * @description get compress
     *
     * @return string
     */
    public function getCompress() : string;

    /**
     * @description packet
     *
     * @param Array $packet
     *
     * @param string $secretKey
     *
     * @param string $type
     *
     * @param bool $isPub
     *
     * @return string
     */
    public static function pack(Array $packet, string $secretKey, string $type = 'aes', bool $isPub = false, string $compress = self::COMPRESS_NO) : string;

    /**
     * @description unpack
     *
     * @param string $data
     *
     * @param string $secretKey
     *
     * @param string $type
     *
     * @param bool $isPub
     *
     * @return Array
     */
    public static function unpack(string $data, string $secretKey, string $type = 'aes', bool $isPub = false) : Array;
}
