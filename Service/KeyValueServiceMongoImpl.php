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

class KeyValueServiceMongoImpl implements KeyValueService {

    /**
     * @var \Wopp\KeyValueBundle\Repository\KeyValueRepository
     */
    private $keyValueRepository;

    public function __construct(KeyValueRepository $keyValueRepository) {
        $this->keyValueRepository = $keyValueRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value, $namespace = KeyValue::DEFAULT_NAMESPACE,
                        $expirationDateTime = KeyValue::NO_EXPIRE) {
        $keyValue = new KeyValue($key, $value, $namespace, $expirationDateTime);
        $this->keyValueRepository->create($keyValue);
        return $keyValue;
    }

    /**
     * {@inheritDoc}
     */
    public function get($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $keyValue = $this->keyValueRepository->findElement($key, $namespace);
        if ($keyValue->isExpired()) {
            throw new ExpiredKeyException($key, $namespace);
        }
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
        $keyValue = $this->keyValueRepository->findElement($key, $namespace);
        $this->keyValueRepository->remove($keyValue);
        $this->keyValueRepository->flush();
    }
}
