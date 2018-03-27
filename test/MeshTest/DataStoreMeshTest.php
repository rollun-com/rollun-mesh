<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.03.18
 * Time: 4:22 PM
 */

namespace rollun\test\MeshTest;

use PHPUnit\Framework\TestCase;
use rollun\mesh\DataStore\Interfaces\MeshInterface as MeshDataStoreInterface;
use rollun\datastore\DataStore\Memory;
use rollun\mesh\DataStoreMesh;
use rollun\mesh\MeshInterface;

class DataStoreMeshTest extends TestCase
{
    /**
     * @var MeshInterface
     */
    private $object;

    const SERVICE_NAME = "test_mesh";
    const SERVICE_HOST = "192.168.0.1";

    public function setUp()
    {
        $meshDataStore = new class extends Memory implements MeshDataStoreInterface
        {

        };
        $meshDataStore->create([
            $meshDataStore->getIdentifier() => uniqid(),
            MeshDataStoreInterface::FIELD_SERVICE_NAME => static::SERVICE_NAME,
            MeshDataStoreInterface::FIELD_SERVICE_HOST => static::SERVICE_HOST,
        ]);
        $this->object = new DataStoreMesh($meshDataStore);
    }

    /**
     * Check valid resolve service host by name
     */
    public function testResolveServiceHostSuccess()
    {
        $serviceHost = $this->object->resolveServiceHost(static::SERVICE_NAME);
        $this->assertEquals(static::SERVICE_HOST, $serviceHost);
    }

    /**
     * @expectedException \rollun\mesh\ServiceHostNotFound
     */
    public function testResolveServiceHostException()
    {
        $this->object->resolveServiceHost("not_exist_service_name");
    }
}
