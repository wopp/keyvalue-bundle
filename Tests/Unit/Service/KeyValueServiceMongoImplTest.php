<?php

/**
 * Unit test suite for KeyValueService mongo implementation
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Tests\Unit\Service;

use Wopp\KeyValueBundle\Service\KeyValueServiceMongoImpl;
use Wopp\KeyValueBundle\Exception\KeyNotFoundException;
use Wopp\KeyValueBundle\Model\KeyValue;

class KeyValueServiceMongoImplTest extends \PHPUnit_Framework_TestCase {

    const KEY_VALUE_REPOSITORY_CLASS = 'Wopp\KeyValueBundle\Repository\KeyValueRepository';
    const IRRELEVANT_KEY = 'irrelevantKey';
    const IRRELEVANT_VALUE = 'irrelevantValue';

    /**
     * @test
     */
    public function getKeyValueShouldReturnTheValue() {
        $keyValueService = new KeyValueServiceMongoImpl(
            $this->getRepositoryMockReturningValidKeyValue());
        $keyValue = $keyValueService->get(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE);
        $this->assertFalse($keyValue->isExpired());
    }

    /**
     * @test
     * @expectedException Wopp\KeyValueBundle\Exception\ExpiredKeyException
     */
    public function getKeyValueShouldThrowExpiredException() {
        $keyValueService = new KeyValueServiceMongoImpl(
            $this->getRepositoryMockReturningExpiredKeyValue());
        $keyValueService->get(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE);
    }

    /**
     * @test
     * @expectedException Wopp\KeyValueBundle\Exception\KeyNotFoundException
     */
    public function getKeyValueShouldThrowKeyNotFoundException() {
        $keyValueService = new KeyValueServiceMongoImpl(
            $this->getRepositoryMockTrhowingNotFoundException());
        $keyValueService->get(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE);
    }

    private function getRepositoryMockReturningValidKeyValue() {
        $mock = $this->getMockBuilder(self::KEY_VALUE_REPOSITORY_CLASS)
            ->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())
            ->method('findElement')
            ->will($this->returnValue(
            new KeyValue(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE)));
        return $mock;
    }

    private function getRepositoryMockReturningExpiredKeyValue() {
        $keyValue = new KeyValue(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE);
        $past =time()-1;
        $keyValue->setExpirationTimestamp($past);

        $mock = $this->getMockBuilder(self::KEY_VALUE_REPOSITORY_CLASS)
            ->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())
            ->method('findElement')
            ->will($this->returnValue($keyValue));
        return $mock;
    }

    private function getRepositoryMockTrhowingNotFoundException() {
        $keyValue = new KeyValue(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE);
        $past = time() - 1;
        $keyValue->setExpirationTimestamp($past);

        $mock = $this->getMockBuilder(self::KEY_VALUE_REPOSITORY_CLASS)
            ->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())
            ->method('findElement')
            ->will($this->throwException(
            new KeyNotFoundException(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE)));
        return $mock;
    }
}
