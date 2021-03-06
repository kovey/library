<?php
/**
 *
 * @description 协议接口
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
     * @description 打包类型
     *
     * @var string
     */
    const PACK_TYPE = 'N';

    /**
     * @description 包头长度
     *
     * @var int
     */
    const HEADER_LENGTH = 4;

    /**
     * @description 包最大长度
     *
     * @var int
     */
    const MAX_LENGTH = 2097152;

    /**
     * @description 包长度所在位置
     *
     * @var int
     */
    const LENGTH_OFFSET = 0;

    /**
     * @description 包体开始位置
     *
     * @var int
     */
    const BODY_OFFSET = 4;

    /**
     * @description 构造函数
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
     * @description 解析包
     *
     * @return bool
     */
    public function parse() : bool;

    /**
     * @description 获取路径
     *
     * @return string
     */
    public function getPath() : string;

    /**
     * @description 获取方法
     *
     * @return string
     */
    public function getMethod() : string;

    /**
     * @description 获取参数
     *
     * @return Array
     */
    public function getArgs() : Array;

    /**
     * @description 获取明文
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
     * @description 打包
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
    public static function pack(Array $packet, string $secretKey, string $type = 'aes', bool $isPub = false) : string;

    /**
     * @description 解包
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
