<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.03.18
 * Time: 3:59 PM
 */

namespace rollun\mesh;

use rollun\mesh\DataStore\Interfaces\MeshInterface as MeshDataStoreInterface;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;

/**
 * Class MeshService
 * @package rollun\mesh
 */
class DataStoreMesh implements MeshInterface
{
    /**
     * @var MeshDataStoreInterface
     */
    protected $meshDataStore;

    /**
     * DataStoreMesh constructor.
     * @param MeshDataStoreInterface $meshDataStore
     */
    public function __construct(MeshDataStoreInterface $meshDataStore)
    {
        $this->meshDataStore = $meshDataStore;
    }

    /**
     * @param $serviceName
     * @return string
     */
    public function resolveServiceHost(string $serviceName) {
        $query = new Query();
        $query->setQuery(new EqNode(MeshDataStoreInterface::FIELD_SERVICE_NAME, $serviceName));
        $result = $this->meshDataStore->query($query);
        if(empty($result)) {
            throw new ServiceHostNotFound("Host for service $serviceName not found.");
        }
        $serviceHost = current($result)[MeshDataStoreInterface::FIELD_SERVICE_HOST];
        return $serviceHost;
    }
}