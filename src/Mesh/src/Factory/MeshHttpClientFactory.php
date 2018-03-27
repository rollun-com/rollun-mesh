<?php


namespace rollun\mesh\Factory;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use rollun\mesh\MeshHttpClient;
use Zend\ServiceManager\Factory\FactoryInterface;

class MeshHttpClientFactory implements FactoryInterface
{
    const KEY = MeshHttpClientFactory::class;

    /**
     * Mesh DataStore service name
     */
    const KEY_MESH_SERVICE = "meshDataStore";

    /**
     * Default url name
     */
    const KEY_URL = "url";

    /**
     *  Array params
     */
    const KEY_OPTIONS = "options";

    /**
     * Create an object
     * [
     *      MeshHttpClientFactory::KEY_MESH_DATASTORE => MeshInterface::class,
     *      //MeshHttpClientFactory::KEY_URL => "google.com",
     *      MeshHttpClientFactory::KEY_OPTIONS => [
     *          "timeout" => 30,
     *      ],
     * ]
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return MeshHttpClient
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("config");
        $factoryConfig = $config[static::KEY];
        $meshDataStore = $container->get($factoryConfig[static::KEY_MESH_SERVICE]);
        $uri = isset($config[static::KEY_URL]) ? $config[static::KEY_URL] : null;
        $options = isset($config[static::KEY_OPTIONS]) ? $config[static::KEY_OPTIONS] : null;
        return new MeshHttpClient($meshDataStore, $uri, $options);
    }
}