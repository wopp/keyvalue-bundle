<?php
/**
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Factory;

use Predis\Client;

class PredisClientFactory {

    private $redisConnection;

    public function __construct($redisConnection) {
        $this->redisConnection = $redisConnection;
    }

    public function getInstance() {
        $predisClient = new Client($this->redisConnection);
        return $predisClient;
    }
}
