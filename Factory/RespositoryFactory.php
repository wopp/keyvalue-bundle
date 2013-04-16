<?php

/**
 *
 * @copyright (C) Wopp 21 (2013)
 * @author Sergio Arroyo Cuevas <serch@wopp.me>
 */
namespace Wopp\KeyValueBundle\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;

class RespositoryFactory {

    private $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getRepository($class, $manager) {
        $repositoryBuilder = $this->container->get(
            $this->processDocumentManager($manager));
        return $repositoryBuilder->getRepository($class);
    }

    private function processDocumentManager($documentManager) {
        $mongoDbDocumentManagerTemplate = 'doctrine_mongodb.odm.%s_document_manager';
        $documentManagerTag = sprintf($mongoDbDocumentManagerTemplate, $documentManager);
        return $documentManagerTag;
    }
}
