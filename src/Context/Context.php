<?php
/**
 * @description context
 *
 * @package Kovey\Library\Context
 *
 * @author kovey
 *
 * @time 2022-02-16 14:57:03
 *
 */
namespace Kovey\Library\Context;

class Context
{
    /**
     * @description trace id
     *
     * @var string
     */
    private string $traceId;

    /**
     * @description span id
     *
     * @var string
     */
    private string $spanId;

    /**
     * @description ext
     *
     * @var array
     */
    private Array $ext;

    /**
     * @description construct
     *
     * @param string $traceId
     *
     * @param string $spanId
     *
     * @param Array $ext
     *
     * @return Context
     */
    public function __construct(string $traceId, string $spanId, Array $ext = array())
    {
        $this->traceId = $traceId;
        $this->spanId = $spanId;
        $this->ext = $ext;
    }

    /**
     * @description get trace id
     *
     * @return string
     */
    public function getTraceId() : string
    {
        return $this->traceId;
    }

    /**
     * @description get span id
     *
     * @return string
     */
    public function getSpanId() : string
    {
        return $this->spanId;
    }

    /**
     * @description get ext
     *
     * @return array
     */
    public function getExt() : Array
    {
        return $this->ext;
    }
}
