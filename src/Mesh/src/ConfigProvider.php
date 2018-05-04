<?php

namespace rollun\mesh;

use rollun\mesh\MeshInterface;
use rollun\mesh\Factory\DataStoreMeshFactory;
use rollun\mesh\Factory\MeshHttpClientFactory;

/**
 * Class ConfigProvider
 * You need provided MeshDataStoreInterface::class service.
 * @package rollun\mesh
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependenciesConfig(),
            MeshHttpClientFactory::KEY => $this->getMeshHttpClientFactoryConfig(),
        ];
    }

    /**
     * @return array
     */
    public function getDependenciesConfig()
    {
        return [
            // Use 'aliases' to alias a service name to another service. The
            // key is the alias name, the value is the service to which it points.
            'aliases' => [
                MeshInterface::class => DataStoreMesh::class,
            ],
            // Use 'invokables' for constructor-less services, or services that do
            // not require arguments to the constructor. Map a service name to the
            // class name.
            'invokables' => [],
            // Use 'factories' for services provided by callbacks/factory classes.
            'factories' => [
                MeshHttpClient::class => MeshHttpClientFactory::class,
                DataStoreMesh::class => DataStoreMeshFactory::class
            ],
            "abstract_factories" => []
        ];
    }

    /**
     * Init MeshHttpClient instance of MeshInterface datastore service.
     * @return array
     */
    public function getMeshHttpClientFactoryConfig()
    {
        return [
            MeshHttpClientFactory::KEY_MESH_SERVICE => MeshInterface::class,
        ];
    }
}