<?php
/**
 * MongoDB implementation for AddKeyValueCommand.
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Command;


class AddMongoKeyValueCommand extends BaseAddKeyValueCommand {

    protected function getServiceId() {
        return 'wopp_key_value.key.value.service.mongo';
    }

    protected function getCommandName() {
        return 'Wopp:keyvalue:mongo:addkey';
    }
}
