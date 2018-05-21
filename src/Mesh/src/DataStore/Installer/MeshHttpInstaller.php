<?php

namespace rollun\mesh\DataStore\Installer;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use rollun\datastore\DataStore\Factory\DbTableAbstractFactory;
use rollun\datastore\DataStore\Factory\HttpClientAbstractFactory;
use rollun\datastore\DataStore\Installers\DbTableInstaller;
use rollun\datastore\DataStore\Installers\HttpClientInstaller;
use rollun\datastore\TableGateway\Factory\TableGatewayAbstractFactory;
use rollun\datastore\TableGateway\TableManagerMysql;
use rollun\installer\Install\InstallerAbstract;
use rollun\mesh\DataStore\Interfaces\MeshInterface;
use rollun\mesh\DataStore\MeshHttp;
use rollun\mesh\DataStore\MeshTable;
use Zend\Uri\Uri;

/**
 * Class MeshTableInstaller
 * @package rollun\mesh\DataStore\Installer
 */
class MeshHttpInstaller extends InstallerAbstract
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
		$isInvalid = true;
		$uri = new Uri();
    	while ($isInvalid) {
			$uriStr = $this->consoleIO->ask("Write url to mesh http data store: ");
			$uri = new Uri($uriStr);
			$isInvalid = !$uri->isValid();
			if($isInvalid) {
				$this->consoleIO->writeError("Uri $uriStr is invalid.");
			}
		}
    	$config = [
            'dependencies' => [
                'aliases' => [
                    "MeshHttp" => MeshHttp::class,
                ],
                'invokables' => [],
                'factories' => [],
            ],
            'dataStore' => [
				MeshHttp::class => [
                    "class" => MeshHttp::class,
                    HttpClientAbstractFactory::KEY_OPTIONS => [

					],
					HttpClientAbstractFactory::KEY_URL => $uri->toString()
                ],

            ]
        ];
        if($this->consoleIO->askConfirmation("Add aliases to `MeshInterface::class => MeshHttp::class` ? ")) {
            $config["dependencies"]["aliases"][MeshInterface::class] = MeshHttp::class;
        }
        return $config;
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
        } catch (NotFoundExceptionInterface $e) {
            return false;
        } catch (ContainerExceptionInterface $e) {
            return false;
        }
        return (
            isset($config["dataStore"][MeshHttp::class])
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
            HttpClientInstaller::class
        ];
    }
}