<?php
/**
 * Redis implementation for AddKeyValueCommand.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Command;


class AddRedisKeyValueCommand extends BaseAddKeyValueCommand {

    protected function getServiceId() {
        return 'wopp_key_value.key.value.service.redis';
    }

    protected function getCommandName() {
        return 'Wopp:keyvalue:redis:addkey';
    }
}
