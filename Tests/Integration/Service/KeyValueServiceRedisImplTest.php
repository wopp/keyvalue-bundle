<?php
/**
 * Integration test suite for KeyValueService redis implementation using real
 * redis connection.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Tests\Integration\Service;

use Wopp\KeyValueBundle\Service\KeyValueServiceRedisImpl;
use Predis\Client;

class KeyValueServiceRedisImplTest extends \PHPUnit_Framework_TestCase {

    private $keyValueService;

    const IRRELEVANT_KEY = 'irrelevantKey';
    const IRRELEVANT_VALUE = 'irrelevantKey';
    const IRRELEVANT_NAMESPACE = 'irrelevantNamespace';

    public function setUp() {
        $this->keyValueService = new KeyValueServiceRedisImpl(
            new Client('tcp://localhost:6379')
        );
    }

    /**
     * @test
     */
    public function setShouldStoreTheKey() {
        $storedKeyValue = $this->keyValueService->set(
            self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE,
            self::IRRELEVANT_NAMESPACE);
        $retrievedKeyValue = $this->keyValueService->get(
            self::IRRELEVANT_KEY, self::IRRELEVANT_NAMESPACE);
        $this->assertThat($storedKeyValue->getValue(),
            $this->equalTo($retrievedKeyValue->getValue()));
    }

    /**
     * @test
     * @expectedException Wopp\KeyValueBundle\Exception\KeyNotFoundException
     */
    public function setWithExpirationShouldExpireTheKey() {
        $this->keyValueService->set(
            self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE,
            self::IRRELEVANT_NAMESPACE, time());
        $this->keyValueService->get(
            self::IRRELEVANT_KEY, self::IRRELEVANT_NAMESPACE);
    }


    /**
     * @test
     * @expectedException Wopp\KeyValueBundle\Exception\KeyNotFoundException
     */
    public function deleteShouldMakethekeyNotAccesible() {
        $this->keyValueService->set(
            self::IRRELEVANT_KEY, self::IRRELEVANT_VALUE,
            self::IRRELEVANT_NAMESPACE, time());
        $this->keyValueService->delete(
            self::IRRELEVANT_KEY, self::IRRELEVANT_NAMESPACE);
        $this->keyValueService->get(
            self::IRRELEVANT_KEY, self::IRRELEVANT_NAMESPACE);
    }

    /**
     * @test
     * @expectedException Wopp\KeyValueBundle\Exception\KeyNotFoundException
     */
    public function getNoExistentKeyShouldThrowError() {
        $this->keyValueService->get(
            self::IRRELEVANT_KEY, self::IRRELEVANT_NAMESPACE);
    }

    public function tearDown() {
       $this->keyValueService->delete(self::IRRELEVANT_KEY, self::IRRELEVANT_NAMESPACE);
    }

}
