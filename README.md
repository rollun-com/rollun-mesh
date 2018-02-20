# rollun-mesh
Реализация service-mesh на php.
> [Более подробно о service mesh тут](https://medium.com/microservices-in-practice/service-mesh-for-microservices-2953109a3c9a)

Клиент [`rollun/mesh/MeshHttpClient`](src/Mesh/src/MeshHttpClient.php) основаный на `Zend\Http\Client` позволяет отпралять запросы используя 
вместо host-name (ip, domain, ip:port, ...) использовать имя сервиса к которому вы хотите обратится.
Для этого оно должно быть доступно из DataStore которые иньецируется в клиент. 
Это может быть ваш кастомный dataStore, либо можно использовать реализацию в виде [`MeshTable::class`](src/Mesh/src/DataStore/MeshTable.php). 
DataStore который представляет таблицу в базе данных. 
> Можно установить используя [`MeshTableInstaller::class`](src/Mesh/src/DataStore/Installer/MeshTableInstaller.php). 

В случае если хост не был найдет по имени в dataStore, то имя будет использовать в качетсве хоста по умолчанию.
> Это означает что если вы не заполните dataStore данными, то данный клиент будет работать так же как и `Zend\Http\Client`

Для того что бы воспользоваться данным клиентом, используйте предоставленый по умолчанию [`MeshTable::class`](src/Mesh/src/DataStore/MeshTable.php). 
> Можно установить используя [`MeshTableInstaller::class`](src/Mesh/src/DataStore/Installer/MeshTableInstaller.php). 

Либо создайте и настройте свой *MeshDataStore*. 
> В таком случае Вам необходимо реализовать [`MeshInterface::class`](src/Mesh/src/DataStore/Interfaces/MeshInterface.php),
 и сделать ваш сервсис(dataStore) доступным по имени [`MeshInterface::class`](src/Mesh/src/DataStore/Interfaces/MeshInterface.php).
 
Теперь подключите [`rollun/mesh/ConfigProvider`](src/Mesh/src/ConfigProvider.php) как это сделано в файле [`config/config.php`](config/config.php#L22)