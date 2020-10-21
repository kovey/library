<?php
/**
 *
 * @description Busi Exception
 *
 * @package     Library\Exception
 *
 * @time        2020-02-12 14:30:53
 *
 * @author      kovey
 */
namespace Kovey\Library\Exception;

class BusiException extends KoveyException
{
    /**
     * @description ext
     *
     * @var Array
     */
    protected Array $ext;

    /**
     * @description construct
     *
     * @param string $msg
     *
     * @param int $code
     *
     * @param Array $ext
     */
    public function __construct(string $msg = '', int $code = 0, Array $ext = array())
    {
        $this->ext = $ext;
        parent::__construct($msg, $code);
    }

    /**
     * @description get ext
     *
     * @return Array
     */
    public function getExt() : Array
    {
        return $this->ext;
    }
}
