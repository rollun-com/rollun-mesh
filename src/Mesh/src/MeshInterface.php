<?php
/**
 * Created by PhpStorm.
 * User: victorsecuring
 * Date: 27.03.18
 * Time: 3:58 PM
 */

namespace rollun\mesh;


interface MeshInterface
{
    /**
     * @param string $serviceName
     * @throws ServiceHostNotFound
     * @return string
     */
    public function resolveServiceHost(string $serviceName);
}