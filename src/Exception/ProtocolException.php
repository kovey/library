<?php
/**
 *
 * @description Protocol Exception
 *
 * @package     Library\Exception
 *
 * @time        2019-11-16 18:13:16
 *
 * @author      kovey
 */
namespace Kovey\Library\Exception;

class ProtocolException extends KoveyException
{
    /**
     * @description error type
     *
     * @var string
     */
    private string $errorType;

    /**
     * @description trace info
     *
     * @var string
     */
    private string $traceString;

    /**
     * @description construct
     *
     * @param string $msg
     *
     * @param int $code
     *
     * @param string $type
     *
     * @return Exception
     */
    public function __construct(string $msg, int $code, string $type, string $traceString = '')
    {
        $this->errorType = $type;
        $this->traceString = $traceString;

        parent::__construct($msg, $code);
    }

    /**
     * @description get error type
     *
     * @return string
     */
    public function getErrorType() : string
    {
        return $this->errorType;
    }

    /**
     * @description get trace string
     *
     * @return string
     */
    public function getTraceString() : string
    {
        return $this->traceString;
    }
}
