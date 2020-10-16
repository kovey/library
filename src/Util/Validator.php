<?php
/**
 * @description data validate
 *
 * @package Library\Util
 *
 * @author zhayai
 *
 * @time 2019-11-20 11:24:49
 *
 */
namespace Kovey\Library\Util;

class Validator
{
    /**
     * @description error message
     *
     * @var string
     */
    private string $error = '';

    /**
     * @description data
     *
     * @var array
     */
    private Array $data;

    /**
     * @description valid rules
     * 
     * @var array
     */
    private Array $rules;

    /**
     * @description construct
     *
     * @param Array $data valid data
     *
     * @param Array $rules valid rules
     *
     * @return Validator
     */
    public function __construct(Array $data, Array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    /**
     * @description valid
     *
     * @return bool
     */
    public function run() : bool
    {
        foreach ($this->rules as $field => $rule) {
            if (in_array('required', $rule, true)) {
                if (!isset($this->data[$field])) {
                    $this->error = "$field is not exists.";
                    return false;
                }
            } else {
                if (!isset($this->data[$field])) {
                    continue;
                }
            }

            foreach ($rule as $r => $f) {
                if ($f === 'required') {
                    continue;
                }

                if (is_numeric($r)) {
                    if (!$this->$f($this->data[$field])) {
                        $this->error = "$field validate failure with $f, value: " . $this->formatValue($this->data[$field]);
                        return false;
                    }

                    continue;
                }

                if ($r === 'inArray') {
                    if (!is_array($f)) {
                        $this->error = "$field validate failure with $r, value: " . $this->formatValue($this->data[$field]);
                        return false;
                    }

                    if (!$this->$r($this->data[$field], $f)) {
                        $this->error = "$field validate failure with $r, condition: " . $this->formatValue($f) . ', value: ' . $this->formatValue($this->data[$field]);
                        return false;
                    }

                    continue;
                }

                if (!is_array($f)) {
                    $f = array($f);
                }

                if (!$this->$r($this->data[$field], ...$f)) {
                    $this->error = "$field validate failure with $r, condition: " . $this->formatValue($f) . ', value: ' . $this->formatValue($this->data[$field]);
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @description format data
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function formatValue($value)
    {
        return is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : $value;
    }

    /**
     * @description is number?
     *
     * @param mixed $num
     *
     * @return bool
     */
    public function number($num) : bool
    {
        return ctype_digit(strval($num));
    }

    /**
     * @description min length
     *
     * @param mixed $val
     *
     * @param int $len
     *
     * @return bool
     */
    public function minlength($val, int $len) : bool
    {
        return strlen($val) >= $len;
    }

    /**
     * @description max length
     *
     * @param mixed $val
     *
     * @param int $len
     *
     * @return bool
     */
    public function maxlength($val, int $len) : bool
    {
        return strlen($val) <= $len;
    }

    /**
     * @description greater than
     *
     * @param mixed $val
     *
     * @param int $mixed
     *
     * @return bool
     */
    public function gt($val, int $mixed) : bool
    {
        return $val > $mixed;
    }

    /**
     * @description greater equal
     *
     * @param mixed $val
     *
     * @param int $mixed
     *
     * @return bool
     */
    public function ge($val, int $mixed) : bool
    {
        return $val >= $mixed;
    }

    /**
     * @description less than
     *
     * @param mixed $val
     *
     * @param int $mixed
     *
     * @return bool
     */
    public function lt($val,int $mixed) : bool
    {
        return $val < $mixed;
    }

    /**
     * @description less equal
     *
     * @param mixed $val
     *
     * @param int mixed
     *
     * @return bool
     */
    public function le($val, int $mixed) : bool
    {
        return $val <= $mixed;
    }

    /**
     * @description in array
     *
     * @param mixed $val
     *
     * @param Array $con
     *
     * @return bool
     */
    public function inArray($val, Array $con) : bool
    {
        return in_array($val, $con, true);
    }

    /**
     * @description get errors
     *
     * @return string
     */
    public function getError() : string
    {
        return $this->error;
    }

    /**
     * @description is array
     *
     * @param mixed $val
     *
     * @return bool
     */
    public function isArray($val) : bool
    {
        return is_array($val);
    }

    /**
     * @description is not empty
     *
     * @param mixed $val
     *
     * @return bool
     */
    public function notEmpty($val) : bool
    {
        return !empty($val);
    }

    public function url($val) : bool
    {
        return (bool)filter_var($val, FILTER_SANITIZE_URL);
    }

    public function account($val) : bool
    {
        return (bool)preg_match('/^[a-zA-Z0-9_]+/', $val);
    }

    public function equalLength($val, int $len) : bool
    {
        return strlen($val) == $len;
    }

    public function id($val) : bool
    {
        return (bool)preg_match('/^[a-f0-9]+/', $val);
    }

	public function date($date) : bool
	{
        $date = explode('-', $date);
        if (count($date) != 3) {
            return false;
        }

		if (strlen($date[0]) != 4
			|| strlen($date[1]) != 2	
			|| strlen($date[2]) != 2	
		) {
			return false;
		}

		if (!ctype_digit($date[0]) 
			|| !ctype_digit($date[1])
			|| !ctype_digit($date[2])
		) {
            return false;
        }

        if (intval($date[1]) < 1 || intval($date[1]) > 12) {
            return false;
        }

        if (in_array($date[1], array('01', '03', '05', '07', '08', '10', '12'), true)) {
            if (intval($date[2]) < 1 || intval($date[2]) > 31) {
                return false;
            }
        } else if ($date[1] === '02') {
            if ($this->isLeapYear($date[0])) {
                if (intval($date[2]) > 29 || intval($date[2]) < 1) {
                    return false;
                }
            } else {
				if (intval($date[2]) > 28 || intval($date[2]) < 1) {
					return false;
				}
			}
		} else {
            if (intval($date[2]) > 30 || intval($date[2]) < 1) {
                return false;
            }
		}

        return true;
	}

    public function dateTime($dateTime) : bool
    {
		$info = explode(' ', $dateTime);
		if (count($info) != 2) {
			return false;
		}

		$date = $info[0];
		if (!$this->date($date)) {
			return false;
		}

		$time = $info[1];
		return $this->time($time);
    }

    public function isLeapYear($year) : bool
    {
        if (strlen($year) != 4) {
            return false;
        }

        if ($year % 4 == 0 && $year % 100 != 0 || $year % 400 == 0) {
            return true;
        }

        return false;
    }

    public function time($time) : bool
    {
		$time = explode(':', $time);
        if (count($time) != 3) {
            return false;
        }

		if (strlen($time[0]) != 2
			|| strlen($time[1]) != 2	
			|| strlen($time[2]) != 2	
		) {
			return false;
		}

        if (!ctype_digit($time[0]) || !ctype_digit($time[1]) || !ctype_digit($time[2])) {
            return false;
        }

        if (intval($time[0]) > 23 || intval($time[0]) < 0) {
            return false;
        }

        if (intval($time[1]) > 59 || intval($time[1]) < 0) {
            return false;
        }

        if (intval($time[2]) > 59 || intval($time[2]) < 0) {
            return false;
        }

        return true;
    }

    public function mobileOrEmail($data) : bool
    {
        return $this->number($data) || $this->email($data);
    }

    public function email($data) : bool
    {
        return (bool)preg_match("/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/", $data);
    }

    public function money($data)
    {
        return (bool)preg_match('/^[0-9]+\.[0-9]{2}/', $data);
    }

    public function numeric($data) : bool
    {
        return is_numeric($data);
    }
}
