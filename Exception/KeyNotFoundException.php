<?php
/**
 * Exception thrown when a key value element does not exists.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Exception;

class KeyNotFoundException extends \DomainException {

    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $namespace;

    function __construct($key, $namespace) {
        parent::__construct(sprintf(
            'Element with key [%s] not found in namespace [%s]', $key, $namespace));

        $this->key = $key;
        $this->namespace = $namespace;
    }

    public function getKey() {
        return $this->key;
    }

    public function getNamespace() {
        return $this->namespace;
    }


}
