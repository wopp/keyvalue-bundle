<?php
/**
 * Unit test suite for KeyValueService redis implementation
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Tests\Unit\Service;

use Wopp\KeyValueBundle\Service\KeyValueServiceCacheImpl;
use Wopp\KeyValueBundle\Model\KeyValue;

class KeyValueServiceCacheImplTest extends \PHPUnit_Framework_TestCase {

    const KEY_VALUE_SERVICE_CLASS = 'Wopp\KeyValueBundle\Service\KeyValueService';
    const IRRELEVANT_KEY = 'irrelevantKey';
    const IRRELEVANT_VALUE = 'irrelevantValue';

    /**
     * @test
     */
    public function setShouldCacheTheKeyValueAndCallOnlyOnceToService() {
        $keyValueService = new KeyValueServiceCacheImpl(
            $this->getRepositoryMockReturningValidKeyValue(0, 1, 0));
        //first call is not cached
        $keyValue = $keyValueService->set(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE);
        //get the cached element with no calling wrapped service
        $cachedKeyValue = $keyValueService->get(self::IRRELEVANT_KEY);
        $this->assertThat($keyValue, $this->equalTo($cachedKeyValue));
    }

    /**
     * @test
     */
    public function getShouldCacheTheKeyValueAndCallOnlyOnceToService() {
        $keyValueService = new KeyValueServiceCacheImpl(
            $this->getRepositoryMockReturningValidKeyValue(1, 0, 0));
        //first call is not cached
        $keyValue = $keyValueService->get(self::IRRELEVANT_KEY);
        //get the cached element with no calling wrapped service
        $cachedKeyValue = $keyValueService->get(self::IRRELEVANT_KEY);
        $this->assertThat($keyValue, $this->equalTo($cachedKeyValue));
    }

    /**
     * @test
     */
    public function deleteShouldRemoveCache() {
        $keyValueService = new KeyValueServiceCacheImpl(
            $this->getRepositoryMockReturningValidKeyValue(1, 0, 1));
        //first call is not cached
        $keyValueService->delete(self::IRRELEVANT_KEY);
        //get the cached element with no calling wrapped service
        $keyValueService->get(self::IRRELEVANT_KEY);
    }


    private function getRepositoryMockReturningValidKeyValue(
        $getTimes, $setTimes, $deleteTimes) {
        $mock = $this->getMockBuilder(self::KEY_VALUE_SERVICE_CLASS)
            ->disableOriginalConstructor()->getMock();
        $mock->expects($this->exactly($setTimes))
            ->method('set')
            ->will($this->returnValue(
            new KeyValue(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE)));

        $mock->expects($this->exactly($getTimes))
            ->method('get')
            ->will($this->returnValue(
            new KeyValue(self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE)));

        $mock->expects($this->exactly($deleteTimes))
            ->method('delete');
        return $mock;
    }

}
