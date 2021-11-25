<?php
/**
 * @description trace interface
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-11-25 15:15:51
 *
 */
namespace Kovey\Library\Trace;

interface TraceInterface
{
    /**
     * @description set trace id
     *
     * @param string $traceId
     *
     * @return void
     */
    public function setTraceId(string $traceId) : void;

    /**
     * @description set span id
     *
     * @param string $spanId
     *
     * @return void
     */
    public function setSpanId(string $spanId)  : void;

    /**
     * @description get trace id
     *
     * @return string
     */
    public function getTraceId() : string;

    /**
     * @description get span id
     *
     * @return string
     */
    public function getSpanId() : string;
}
