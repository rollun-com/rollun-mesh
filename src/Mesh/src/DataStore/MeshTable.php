<?php


namespace rollun\mesh\DataStore;

use rollun\datastore\DataStore\SerializedDbTable;
use rollun\datastore\TableGateway\TableManagerMysql;
use rollun\mesh\DataStore\Interfaces\MeshInterface;

/**
 * Class MeshTable
 * DbTable for mesh db table.
 * @package rollun\mesh\DataStore
 */
class MeshTable extends SerializedDbTable implements MeshInterface
{
    const TABLE_NAME = "central_service_mesh";

    const FIELD_ID = self::DEF_ID;

    /**
     * Return table config
     * @return array
     */
    static public function getTableConfig()
    {
        return [
            static::TABLE_NAME => [
                static::FIELD_ID => [
                    TableManagerMysql::FIELD_TYPE => "Varchar",
                    TableManagerMysql::PRIMARY_KEY => true,
                    TableManagerMysql::FIELD_PARAMS => [
                        'nullable' => false,
                        'length' => 6,
                    ]
                ],
                static::FIELD_SERVICE_NAME => [
                    TableManagerMysql::FIELD_TYPE => "Varchar",
                    TableManagerMysql::FIELD_PARAMS => [
                        'nullable' => false,
                        'length' => 255,
                    ]
                ],
                static::FIELD_SERVICE_HOST => [
                    TableManagerMysql::FIELD_TYPE => "Varchar",
                    TableManagerMysql::FIELD_PARAMS => [
                        'nullable' => false,
                        'length' => 255,
                    ]
                ],
            ]
        ];
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return static::FIELD_ID;
    }


}