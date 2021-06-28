<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-06-28 18:07:53
 *
 */
namespace Kovey\Library\Util;

class Version
{
    const GT = 1;

    const EQ = 0;

    const LT = -1;

    public function compare(string $left, string $right) : int
    {
        $linfo = explode('.', $left);
        $lcount = count($linfo);
        if ($lcount < 2) {
            return self::EQ;
        }

        $rinfo = explode('.', $right);
        $rcount = count($rinfo);
        if ($rcount < 2) {
            return self::GT;
        }

        for ($i = 0; $i < $lcount; $i ++) {
            if (!isset($rinfo[$i])) {
                return self::GT;
            }

            if ($linfo[$i] > $rinfo[$i]) {
                return self::GT;
            }

            if ($linfo[$i] < $rinfo[$i]) {
                return self::LT;
            }
        }

        if ($rcount > $lcount) {
            return self::LT;
        }

        return self::EQ;
    }
}
