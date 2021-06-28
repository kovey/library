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

    public static function compare(string $left, string $right) : int
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
                break;
            }

            if ($linfo[$i] > $rinfo[$i]) {
                return self::GT;
            }

            if ($linfo[$i] < $rinfo[$i]) {
                return self::LT;
            }
        }

        if ($rcount == $lcount) {
            return self::EQ;
        }

        if ($rcount > $lcount) {
            return self::EQ - self::check($rinfo, $lcount, $rcount);
        }

        return self::check($linfo, $rcount, $lcount);
    }

    private static function check(Array $info, int $start, int $count) : int
    {
        for ($i = $start; $i < $count; $i ++) {
            if ($info[$i] > 0) {
                return self::GT;
            }
        }

        return self::EQ;
    }
}
