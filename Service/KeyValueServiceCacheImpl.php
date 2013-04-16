<?php

/**
 * MongoDB based implementation of KeyValueService.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Service;

use Wopp\KeyValueBundle\Service\KeyValueService;
use Wopp\KeyValueBundle\Exception\ExpiredKeyException;
use Wopp\KeyValueBundle\Model\KeyValue;
use Wopp\KeyValueBundle\Repository\KeyValueRepository;

class KeyValueServiceCacheImpl implements KeyValueService {

    /**
     * @var KeyValueService
     */
    private $keyValueService;

    private $cache = array();

    public function __construct(KeyValueService $keyValueService) {
        $this->keyValueService = $keyValueService;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $namespace = KeyValue::DEFAULT_NAMESPACE,
                        $expirationDateTime = KeyValue::NO_EXPIRE) {
        $keyValue = $this->keyValueService->set(
            $key, $value, $namespace, $expirationDateTime);
        $this->cache[$this->buildCompleteKey($key, $namespace)] = $keyValue;
        return $keyValue;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $completeKey = $this->buildCompleteKey($key, $namespace);

        if (isset($this->cache[$completeKey])) {
            $keyValue = $this->cache[$completeKey];
        } else {
            $keyValue = $this->keyValueService->get($key, $namespace);
            $this->cache[$completeKey] = $keyValue;
        }

        $this->validateExpiration($keyValue);
        return $keyValue;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $keyValue = $this->get($key, $namespace);
        return $keyValue->getValue();
    }

    /**
     * {@inheritDoc}
     */
    public function delete($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $this->keyValueService->delete($key, $namespace);
        unset($this->cache[$this->buildCompleteKey($key, $namespace)]);
    }

    private function validateExpiration(KeyValue $keyValue) {
        if ($keyValue->isExpired()) {
            throw new ExpiredKeyException($keyValue->getKey(), $keyValue->getNamespace());
        }
    }

    private function buildCompleteKey($key, $namespace) {
        return $key . '::' . $namespace;
    }
}
