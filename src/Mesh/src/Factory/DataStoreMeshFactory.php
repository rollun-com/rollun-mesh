<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.03.18
 * Time: 4:07 PM
 */

namespace rollun\mesh\Factory;


use Interop\Container\ContainerInterface;
use rollun\mesh\DataStore\Interfaces\MeshInterface as MeshDataStoreInterface;
use rollun\mesh\DataStoreMesh;
use Zend\ServiceManager\Factory\FactoryInterface;

class DataStoreMeshFactory implements FactoryInterface
{
    const KEY = DataStoreMeshFactory::class;

    const KEY_DATASTORE = "dataStore";

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return DataStoreMesh
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container);
        $dataStore = isset($config[static::KEY_DATASTORE]) ?
            $container->get($config[static::KEY_DATASTORE]) :
            $container->get(MeshDataStoreInterface::class);
        return new DataStoreMesh($dataStore);
    }

    /**
     * @param ContainerInterface $container
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function getConfig(ContainerInterface $container)
    {
        $config = $container->get("config");
        return isset($config[static::KEY]) ? $config[static::KEY] : [];
    }
}