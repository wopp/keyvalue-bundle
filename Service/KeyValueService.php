<?php

/**
 * Define the operations can be performed with key value objects.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Service;

use Wopp\KeyValueBundle\Model\KeyValue;

interface KeyValueService {

    /**
     * Create a new keyValue object and persist it, If the object already exists
     * will update the old one
     * @param string $key
     * @param string $value
     * @param string $namespace = ''
     * @param int $expirationDateTime = 0
     * @return KeyValue the just created key value object
     */
    public function set($key, $value, $namespace = KeyValue::DEFAULT_NAMESPACE,
                        $expirationDateTime = KeyValue::NO_EXPIRE);

    /**
     * @param string $key
     * @param string $namespace = 'default'
     * @return KeyValue
     * @throws KeyNotFoundException if the key does not exits
     * @throws ExpiredKeyException if the key exits but is expired
     */
    public function get($key, $namespace = KeyValue::DEFAULT_NAMESPACE);

    /**
     * @param string $key
     * @param string $namespace = 'default'
     * @return string.
     * @throws KeyNotFoundException if the key does not exits
     * @throws ExpiredKeyException if the key exits but is expired
     */
    public function getValue($key, $namespace = KeyValue::DEFAULT_NAMESPACE);

    /**
     * @param string $key
     * @param string $namespace = 'default'
     */
    public function delete($key, $namespace = KeyValue::DEFAULT_NAMESPACE);
}
