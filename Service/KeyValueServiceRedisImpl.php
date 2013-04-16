<?php
/**
 * Redis based implementation of KeyValueService
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Service;

use Wopp\KeyValueBundle\Model\KeyValue;
use Wopp\KeyValueBundle\Exception\KeyNotFoundException;
use Predis\Client;

class KeyValueServiceRedisImpl implements KeyValueService {

    /**
     * @var \Predis\Client
     */
    private $redisClient;

    public function __construct(Client $redisClient) {
        $this->redisClient = $redisClient;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $namespace = KeyValue::DEFAULT_NAMESPACE,
                        $expirationDateTime = KeyValue::NO_EXPIRE) {
        $effectiveKey = $this->buildKey($key, $namespace);
        $this->redisClient->set($effectiveKey, serialize($value));
        $keyValue = new KeyValue($key, $value, $namespace, $expirationDateTime);
        if ($expirationDateTime !== KeyValue::NO_EXPIRE) {
            $this->redisClient->expire($effectiveKey, $keyValue->getSecsToExpire());
        }
        return $keyValue;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $value = $this->getValue($key, $namespace);
        $effectiveKey = $this->buildKey($key, $namespace);
        $expiration = $this->redisClient->ttl($effectiveKey);
        $keyValue = new KeyValue($key, $value, $namespace);
        if ($expiration !== -1) {
            $keyValue->setExpirationInSeconds($expiration);
        }
        return $keyValue;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $effectiveKey = $this->buildKey($key, $namespace);
        $value = $this->redisClient->get($effectiveKey);
        if (!$value) {
            throw new KeyNotFoundException($key, $namespace);
        }
        return unserialize($value);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $effectiveKey = $this->buildKey($key, $namespace);
        $this->redisClient->del($effectiveKey);
    }

    private function buildKey($key, $namexpace) {
        return $key . '::' . $namexpace;
    }

}
