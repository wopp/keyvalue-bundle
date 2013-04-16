<?php
/**
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Tests\Unit\Model;

use Wopp\KeyValueBundle\Model\KeyValue;
use DateTime;

class KeyValueTest extends \PHPUnit_Framework_TestCase {


    public static function getSecsToExpireProvider() {
        return array(
            // array($seconds to expire, assuming it will take less than one
            // second we try a range of [-1second, the provided time])

            array(1, 0, 1),
            array(50, 49, 50),
            array(1500, 1499, 1500),
            array(86400, 86399, 86400),
        );
    }

    /**
     * @test
     * @dataProvider getSecsToExpireProvider
     */
    public function getSecsToExpireShouldReturnDoesNotEpired($seconds, $minSecs, $maxSecs) {
        $keyValue = new KeyValue('irrelevantKey', 'irrelevantValue',
            'irrelevantNamespace');
        $keyValue->setExpirationInSeconds($seconds);
        $secsToExpire = $keyValue->getSecsToExpire();
        $this->assertThat($minSecs, $this->lessThanOrEqual($secsToExpire));
        $this->assertThat($maxSecs, $this->greaterThanOrEqual($secsToExpire));
    }

    public static function getNotExpiredKeyValues() {
        $future = time() + 500;
        $farFuture = time() + 50000000000000;

        return array(
            array(new KeyValue('irrelevantKey', 'irrelevantValue')),
            array(new KeyValue('irrelevantKey', 'irrelevantValue',
                'irrelevantNamespace', $future)),
            array(new KeyValue('irrelevantKey', 'irrelevantValue',
                'irrelevantNamespace', $farFuture)),
            array(new KeyValue('irrelevantKey', 'irrelevantValue',
                'irrelevantNamespace', -1))
        );
    }

    /**
     * @test
     * @dataProvider getNotExpiredKeyValues
     */
    public function keyValueShouldNotBeExpired(KeyValue $keyValue) {
        $this->assertFalse($keyValue->isExpired());
    }


    public static function getExpiredKeyValues() {
        $past = time() - 1;
        return array(
            array(new KeyValue('irrelevantKey', 'irrelevantValue',
                'irrelevantNamespace', $past))
        );
    }
    /**
     * @test
     * @dataProvider getExpiredKeyValues
     */
    public function keyValueShouldBeExpired(KeyValue $keyValue) {
        $this->assertTrue($keyValue->isExpired());
    }

}
