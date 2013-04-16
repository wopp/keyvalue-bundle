<?php
/**
 * Base command to add key value objects into mongo
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */

namespace Wopp\KeyValueBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Wopp\KeyValueBundle\Model\KeyValue;

abstract class BaseAddKeyValueCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName($this->getCommandName())
            ->setDescription(
                'It will store a keyValue object into the key value storage')
            ->addArgument('key', InputArgument::REQUIRED,
                'The key of the stored object')
            ->addArgument('value', InputArgument::REQUIRED,
                'The value of the stored object')
            ->addOption('namespace', null, InputOption::VALUE_OPTIONAL,
                'The key namespace', KeyValue::DEFAULT_NAMESPACE)
            ->addOption('expiration', null, InputOption::VALUE_OPTIONAL,
                'The time in seconds to expire the key (default no expiration)', KeyValue::NO_EXPIRE)
            ->addOption('format', null, InputOption::VALUE_OPTIONAL,
                'Defines whether the key value is a plain "string" or a "json" encoded value', 'string');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $key = $input->getArgument('key');
        $value = $this->prepareValue(
            $input->getArgument('value'), $input->getOption('format'));
        $namespace = $input->getOption('namespace');
        $expiration = $this->prepareExpiration($input->getOption('expiration'));
        $container = $this->getContainer();
        $keyValueService = $container->get($this->getServiceId());
        $keyValueService->set($key, $value, $namespace, $expiration);
    }

    private function prepareExpiration($expiration) {
        if ($expiration != KeyValue::NO_EXPIRE) {
            $keyValue = new KeyValue('', '');
            $keyValue->setExpirationInSeconds($expiration);
            $expiration = $keyValue->getExpirationTimestamp();
        }
        return $expiration;
    }

    private function prepareValue($value, $format) {
        if ($format == 'json') {
            $value = json_decode($value);
        }
        return $value;
    }

    protected abstract function getServiceId();

    protected abstract function getCommandName();
}
