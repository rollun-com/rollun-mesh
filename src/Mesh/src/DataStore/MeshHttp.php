<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 21.05.18
 * Time: 16:10
 */

namespace rollun\mesh\DataStore;


use rollun\datastore\DataStore\HttpClient;
use rollun\mesh\DataStore\Interfaces\MeshInterface as MeshDataStoreInterface;

/**
 * Class MeshHttp
 * @package rollun\mesh\DataStore
 */
class MeshHttp extends HttpClient implements MeshDataStoreInterface
{

}