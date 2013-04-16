<?php
/**
 * Represents a key value entity.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="keyvalues",
 *  repositoryClass="Wopp\KeyValueBundle\Repository\KeyValueRepository",
 *  indexes={
 *      @MongoDB\Index(keys={"key"="asc"}, options={"unique"=true}),
 *      @MongoDB\Index(keys={"key"="asc", "namespace"="asc"}, options={"unique"=false}),
 *   }
 * )
 *
 */
class KeyValue {

    const NO_EXPIRE = -1;
    const DEFAULT_NAMESPACE = '';

    /**
     * @var string
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string
     * @MongoDB\String
     */
    private $key;

    /**
     * @var string
     * @MongoDB\String
     */
    private $namespace;

    /**
     * @var string
     * @MongoDB\String
     */
    private $value;

    /**
     * @var int the timestamp
     * @MongoDB\Int
     */
    private $expirationTimestamp;

    /**
     * @param string $key
     * @param string $value
     * @param string $namespace
     * @param int $expirationDateTime
     */
    public function __construct($key, $value,
                                $namespace = self::DEFAULT_NAMESPACE,
                                $expirationDateTime = self::NO_EXPIRE) {
        $this->setValues($key, $value, $namespace, $expirationDateTime);
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $namespace
     * @param int $expirationDateTime
     */
    public function setValues($key, $value,
                              $namespace = self::DEFAULT_NAMESPACE,
                              $expirationDateTime = self::NO_EXPIRE) {
        $this->key = $key;
        $this->value = serialize($value);
        $this->namespace = $namespace;
        $this->expirationTimestamp = $expirationDateTime;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getValue() {
        return unserialize($this->value);
    }

    /**
     * @param int $expirationDateTime
     */
    public function setExpirationTimestamp($expirationDateTime) {
        $this->expirationTimestamp = $expirationDateTime;
    }

    /**
     * @return int
     */
    public function getExpirationTimestamp() {
        return $this->expirationTimestamp;
    }

    public function setExpirationInSeconds($seconds) {
        $this->expirationTimestamp = time() + $seconds;
    }

    /**
     * @return boolean
     */
    public function isExpired() {
        return ($this->expirationTimestamp !== self::NO_EXPIRE
            && $this->expirationTimestamp < time());
    }

    public function getSecsToExpire() {
        $secs = self::NO_EXPIRE;
        if ($this->expirationTimestamp !== self::NO_EXPIRE) {
            $secs = $this->expirationTimestamp - time();
        }
        return $secs;
    }

    public function equalsTo($keyValue) {
        $this->key === $keyValue->getKey();
    }
}
