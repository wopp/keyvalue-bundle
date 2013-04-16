<?php

/**
 * In charge to perform persistent operations with KeyValue objects.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Wopp\KeyValueBundle\Exception\KeyNotFoundException;
use Wopp\KeyValueBundle\Model\KeyValue;

class KeyValueRepository extends DocumentRepository {

    /**
     * @param string $key
     * @param string $namespace
     * @return KeyValue
     * @throw KeyNotFoundException if the key does not exists.
     */
    public function findElement($key, $namespace = KeyValue::DEFAULT_NAMESPACE) {
        $keyValue = $this->findElementWithNoException($key, $namespace);
        if (!$keyValue) {
            throw new KeyNotFoundException($key, $namespace);
        }
        return $keyValue;
    }

    /**
     * @param KeyValue $keyValue
     */
    public function create(KeyValue $keyValue) {
        $existentkeyValue = $this->findElementWithNoException(
            $keyValue->getKey(), $keyValue->getNamespace());
        if  ($existentkeyValue) {
            $existentkeyValue->setValues($keyValue->getKey(), $keyValue->getValue(),
                $keyValue->getNamespace(), $keyValue->getExpirationTimestamp());
        } else {
            $this->dm->persist($keyValue);
        }
        $this->flush();
    }

    /**
     * @param KeyValue $keyValue
     */
    public function remove(KeyValue $keyValue) {
        $this->dm->remove($keyValue);
    }

    public function flush($keyValue = null) {
        $this->dm->flush($keyValue, array('safe' => true));
    }

    private function findElementWithNoException($key, $namespace) {
        $keyValue = $this->findOneBy(array(
            'key' => $key,
            'namespace' => $namespace
        ));
        return $keyValue;
    }
}
