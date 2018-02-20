<?php

namespace rollun\mesh\DataStore\Interfaces;

use rollun\datastore\DataStore\Interfaces\DataStoresInterface;

/**
 * Interface MeshInterface
 * @package rollun\mesh\DataStore\Interfaces
 */
interface MeshInterface extends DataStoresInterface
{
    /**
     * Service name field constant
     */
    const FIELD_SERVICE_NAME = "name";

    /**
     * service host field constant
     */
    const FIELD_SERVICE_HOST = "host";
}