<?php

namespace rollun\test\MeshTest;

use rollun\datastore\DataStore\Memory;
use rollun\dic\InsideConstruct;
use rollun\mesh\DataStore\Interfaces\MeshInterface as MeshDataStoreInterface;
use rollun\mesh\DataStoreMesh;
use rollun\mesh\MeshInterface;
use rollun\mesh\MeshHttpClient;
use PHPUnit\Framework\TestCase;

class MeshHttpClientTest extends TestCase
{
    /** @var MeshHttpClient */
    protected $object;

    /** @var MeshDataStoreInterface */
    protected $meshDataStore;

    /** @var MeshInterface */
    private $meshService;

    /**
     * Init mesh dataStore and meshHttpClient
     */
    public function setUp()
    {
        $container = require 'config/container.php';
        InsideConstruct::setContainer($container);
        $this->meshDataStore = new class extends Memory implements MeshDataStoreInterface
        {

        };
        $this->meshService = new DataStoreMesh($this->meshDataStore);
        $this->object = new MeshHttpClient($this->meshService);
    }

    /**
     *
     */
    public function getExpectedHosts()
    {
        return [
            ["google.com"],
            ["192.168.123.123"],
            ["127.0.0.1"],
            ["localhost"],
        ];
    }

    /**
     * @dataProvider getExpectedHosts
     * @param $expectedHost
     */
    public function testUseDefaultHost($expectedHost)
    {
        $this->object->setUri("http://$expectedHost");
        $actualHost = $this->object->getUri()->getHost();
        $this->assertEquals($expectedHost, $actualHost);
    }

    /**
     * @return array
     */
    protected function getDefaultHosts()
    {
        return [
            "service" => "host",
            "192.168.123.13" => "186.75.32.123",
            "data" => "192.168.123.13",
            "google" => "google.com",
            "localhost" => "127.0.0.1:8080"
        ];
    }

    /**
     * @param array $hosts
     */
    protected function initMeshDataStore($hosts = [])
    {
        if (empty($hosts)) {
            $hosts = $this->getDefaultHosts();
        }
        foreach ($hosts as $name => $host) {
            $this->meshDataStore->create([
                $this->meshDataStore->getIdentifier() => uniqid(),
                MeshDataStoreInterface::FIELD_SERVICE_NAME => $name,
                MeshDataStoreInterface::FIELD_SERVICE_HOST => $host,
            ]);
        }
    }

    /**
     * @return array
     */
    public function getChangeHostData()
    {
        return [
            [
                "host",
                "service",
                "",
            ],
            [
                "186.75.32.123",
                "192.168.123.13",
                "",
            ],
            [
                "192.168.123.13",
                "data",
                "",
            ],
            [
                "google.com",
                "google",
                "",
            ],
            [
                "127.0.0.1:8080",
                "localhost",
                "",
            ],
            [
                "host",
                "service",
                "api",
            ],
            [
                "186.75.32.123",
                "192.168.123.13",
                "api/datastore/test",
            ],
            [
                "192.168.123.13",
                "data",
                "webhook/calc_foo",
            ],
            [
                "google.com",
                "google",
                "search",
            ],
            [
                "127.0.0.1:8080",
                "localhost",
                "some_page",
            ],
        ];
    }

    /**
     * @dataProvider getChangeHostData
     * @param $expectedHost
     * @param $serviceName
     * @param string $path
     */
    public function testChangeHost($expectedHost, $serviceName, $path = "")
    {
        $this->initMeshDataStore();
        $uri = "http://$serviceName/$path";
        $this->object->setUri($uri);
        $actualHost = $this->object->getUri()->getHost();
        $this->assertEquals($expectedHost, $actualHost);
    }
}
