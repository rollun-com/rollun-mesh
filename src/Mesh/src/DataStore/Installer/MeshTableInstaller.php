<?php

namespace rollun\mesh\DataStore\Installer;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use rollun\datastore\DataStore\Factory\DbTableAbstractFactory;
use rollun\datastore\DataStore\Installers\DbTableInstaller;
use rollun\datastore\TableGateway\Factory\TableGatewayAbstractFactory;
use rollun\datastore\TableGateway\TableManagerMysql;
use rollun\installer\Install\InstallerAbstract;
use rollun\mesh\DataStore\Interfaces\MeshInterface;
use rollun\mesh\DataStore\MeshTable;

/**
 * Class MeshTableInstaller
 * @package rollun\mesh\DataStore\Installer
 */
class MeshTableInstaller extends InstallerAbstract
{
    /**
     * install
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \rollun\datastore\DataStore\DataStoreException
     * @throws \rollun\datastore\RestException
     */
    public function install()
    {
        $tableManager = $this->getTableManager();
        if (!$tableManager->hasTable(MeshTable::TABLE_NAME)) {
            $tableManager->createTable(MeshTable::TABLE_NAME);
        }
        $config = [
            'dependencies' => [
                'aliases' => [
                    "MeshTable" => MeshTable::class,
                ],
                'invokables' => [],
                'factories' => [],
            ],
            TableGatewayAbstractFactory::KEY_TABLE_GATEWAY => [
                MeshTable::TABLE_NAME => [],
            ],
            'dataStore' => [
                MeshTable::class => [
                    "class" => MeshTable::class,
                    DbTableAbstractFactory::KEY_TABLE_GATEWAY => MeshTable::TABLE_NAME,
                ],

            ]
        ];
        if($this->consoleIO->askConfirmation("Add aliases to `MeshInterface::class => MeshTable::class` ? ")) {
            $config["dependencies"]["aliases"][MeshInterface::class] = MeshTable::class;
        }
        return $config;
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getTableManager()
    {
        $dbAdapter = $this->container->get('db');
        $tableConfig = [
            TableManagerMysql::KEY_TABLES_CONFIGS => array_merge(
                MeshTable::getTableConfig()
            )
        ];
        return new TableManagerMysql($dbAdapter, $tableConfig);
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {
        // TODO: Implement uninstall() method.
    }

    /**
     * Return true if install, or false else
     * @return bool
     */
    public function isInstall()
    {
        try {
            $config = $this->container->get("config");
            $tableManager = $this->getTableManager();
        } catch (NotFoundExceptionInterface $e) {
            return false;
        } catch (ContainerExceptionInterface $e) {
            return false;
        }
        return (
            $tableManager->hasTable(MeshTable::TABLE_NAME) &&
            isset($config[TableGatewayAbstractFactory::KEY_TABLE_GATEWAY][MeshTable::TABLE_NAME]) &&
            isset($config["dataStore"][MeshTable::class])
        );
    }

    /**
     * Return string with description of installable functional.
     * @param string $lang ; set select language for description getted.
     * @return string
     */
    public function getDescription($lang = "en")
    {
        switch ($lang) {
            case "ru":
                $description = "Создает структуру таблиц в базе.";
                break;
            default:
                $description = "Does not exist.";
        }
        return $description;
    }

    public function getDependencyInstallers()
    {
        return [
            DbTableInstaller::class,
        ];
    }
}