<?php

// Autoload files using Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

use DatabaseExporterImporter\Model\DataExporter\JSON\JsonDataExporter;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlDataProvider;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlTablesProvider;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableColumnsExporter;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableDataRowsExporter;
use DatabaseExporterImporter\Model\DataProvider\TableForeignKeysValuesProvider;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlTableColumnsProvider;

$connection = new \PDO('mysql:dbname=inventory;host=localhost', 'root', '');

$columnsProvider = new MySqlTableColumnsProvider($connection);
$tablesProvider = new MySqlTablesProvider($columnsProvider);
$tablesProvider->setConnection($connection);

$dataProvider = new MySqlDataProvider($tablesProvider);
$dataProvider
    ->setConnection($connection)
    ->setForeignValueProvider(new TableForeignKeysValuesProvider())
    ->setPrimaryTableName('daily_sale')
    ->setPrimaryKeyColumn('id')
    ->setPrimaryKey(1);

$exporter = new JsonDataExporter();
$data = $exporter
    ->setColumnsExporter(new JsonTableColumnsExporter())
    ->setDataRowsExporter(new JsonTableDataRowsExporter())
    ->setDataProvider($dataProvider)
    ->getData();

print_r($data);
