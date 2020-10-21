<?php
/**
 * @description
 *
 * @package
 *
 * @author zhayai
 *
 * @time 2020-10-21 09:35:31
 *
 */
namespace Kovey\Library\Util;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testFormatValue()
    {
        $valid = new Validator(array(), array());
        $this->assertEquals('aaa', $valid->formatValue('aaa'));
        $this->assertEquals('{"a":"b"}', $valid->formatValue(array('a' => 'b')));
    }
    
    public function testRun()
    {
        $valid = new Validator(array(
            'test' => '1111',
            'test1' => 'test',
            'test2' => '13912345678',
            'test3' => array('1', '2', 'a'),
            'date' => '1990-01-01',
            'dateTime' => '1990-01-01 12:19:12',
            'notRequired' => null,
            'notRequired1' => '111',
            'email' => 'koveysha@vip.qq.com'
        ), array(
            'test' => array('required', 'number', 'inArray' => array('1111', '2222')),
            'test1' => array('required', 'account', 'minlength' => 1, 'maxlength' => 10),
            'test2' => array('required', 'number'),
            'test3' => array('required', 'isArray', 'notEmpty'),
            'date' => array('required', 'date'),
            'dateTime' => array('required', 'dateTime'),
            'notRequired' => array('number'),
            'notRequired1' => array('number', 'ge' => 1),
            'email' => array('required', 'email')
        ));
        $result = $valid->run();
        $this->assertTrue($result);
    }

    public function testNumber()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->number('111'));
        $this->assertFalse($valid->number('1aaa'));
    }

    public function tetMinlength()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->minlength('aaa', 2));
        $this->assertTrue($valid->minlength('aaa', 3));
        $this->assertFalse($valid->minlength('aaa', 4));
    }

    public function testMaxlength()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->maxlength('aaa', 4));
        $this->assertTrue($valid->maxlength('aaa', 3));
        $this->assertFalse($valid->maxlength('aaa', 2));
    }

    public function testGt()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->gt('5', 4));
        $this->assertFalse($valid->gt('5', 5));
        $this->assertFalse($valid->gt('5', 6));
    }

    public function testGe()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->ge('5', 4));
        $this->assertTrue($valid->ge('5', 5));
        $this->assertFalse($valid->ge('5', 6));
    }

    public function testLt()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->lt('3', 4));
        $this->assertFalse($valid->lt('5', 5));
        $this->assertFalse($valid->lt('5', 2));
    }

    public function testLe()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->le('3', 4));
        $this->assertTrue($valid->le('5', 5));
        $this->assertFalse($valid->le('5', 2));
    }

    public function testInArray()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->inArray('aaa', array('aaa', 'bbb')));
        $this->assertFalse($valid->inArray('aaa', array('ccc', 'bbb')));
        $this->assertFalse($valid->inArray('11', array(11, 22)));
    }

    public function testGetError()
    {
        $valid = new Validator(array(
        ), array(
            'test' => array('required')
        ));
        $this->assertFalse($valid->run());
        $this->assertEquals('test is not exists.', $valid->getError());
    }

    public function testIsArray()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->isArray( array('aaa', 'bbb')));
        $this->assertFalse($valid->isArray(new \ArrayObject()));
        $this->assertFalse($valid->isArray('aa'));
    }

    public function testNotEmpty()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->notEmpty('aa'));
        $this->assertTrue($valid->notEmpty(array('aaa')));
        $this->assertTrue($valid->notEmpty(false));
        $this->assertTrue($valid->notEmpty(0));
        $this->assertTrue($valid->notEmpty('0'));
        $this->assertFalse($valid->notEmpty(''));
        $this->assertFalse($valid->notEmpty(null));
    }

    public function testUrl()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->url('https://kovey.cn'));
        $this->assertFalse($valid->url('aaa'));
    }

    public function testAccount()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->account('a123_a'));
        $this->assertFalse($valid->account('aaa8@3'));
    }

    public function testEqualLength()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->equalLength('aaaa', 4));
        $this->assertFalse($valid->equalLength('aaaa', 3));
    }

    public function testId()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->id('aabbcc123214'));
        $this->assertFalse($valid->id('a123214g'));
    }

    public function testDate()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->date('1990-01-01'));
        $this->assertFalse($valid->date('1990-01-01 12:12:00'));
    }

    public function testDateTime()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->dateTime('1990-01-01 12:12:00'));
        $this->assertFalse($valid->dateTime('1990-01-01'));
        $this->assertFalse($valid->dateTime('12:12:00'));
        $this->assertFalse($valid->dateTime('aaa'));
        $this->assertFalse($valid->dateTime('1990-01-01T12:12:00'));
    }

    public function testIsLeapYear()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->isLeapYear('2008'));
        $this->assertFalse($valid->isLeapYear('2010'));
    }

    public function testTime()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->time('12:12:12'));
        $this->assertFalse($valid->time('1990-01-01 12:12:12'));
        $this->assertFalse($valid->time('1990-01-01'));
    }

    public function testMobileOrEmail()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->mobileOrEmail('13912345678'));
        $this->assertTrue($valid->mobileOrEmail('koveysha@vip.qq.com'));
        $this->assertFalse($valid->mobileOrEmail('aaaa'));
    }

    public function testEmail()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->email('koveysha@vip.qq.com'));
        $this->assertFalse($valid->email('aaaa'));
        $this->assertFalse($valid->email('13912345678'));
    }

    public function testMoney()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->money('12.00'));
        $this->assertFalse($valid->money('12'));
        $this->assertFalse($valid->money('12.000'));
    }

    public function testNumeric()
    {
        $valid = new Validator(array(), array());
        $this->assertTrue($valid->numeric('12'));
        $this->assertTrue($valid->numeric(12));
        $this->assertTrue($valid->numeric('12.1111'));
        $this->assertTrue($valid->numeric(12.1111));
        $this->assertFalse($valid->numeric('12a'));
        $this->assertFalse($valid->numeric('a12.00'));
        $this->assertFalse($valid->numeric('12..00'));
    }
}
