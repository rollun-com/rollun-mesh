<?php


namespace rollun\mesh;

use Zend\Http\Exception\InvalidArgumentException;
use rollun\mesh\DataStore\Interfaces\MeshInterface;
use Xiag\Rql\Parser\Node\Query\ScalarOperator\EqNode;
use Xiag\Rql\Parser\Query;
use Zend\Http\Client;
use Zend\Uri\Exception as UriException;
use Zend\Uri\Http as HttpUri;

/**
 * Class MeshHttpClient
 * Get service ip by host in url.
 * if service with host name not found, use it for request.
 * @package rollun\mesh
 */
class MeshHttpClient extends Client
{
    /**
     * @var MeshInterface
     */
    protected $meshDataStore;

    /**
     * MeshHttpClient constructor.
     * @param MeshInterface $meshDataStore
     * @param null $uri
     * @param null $options
     */
    public function __construct(MeshInterface $meshDataStore, $uri = null, $options = null)
    {
        $this->meshDataStore = $meshDataStore;
        parent::__construct($uri, $options);
    }

    /**
     * Get service host by service name or use existing if not found.
     * @param string|HttpUri $uri
     * @return void|Client
     */
    public function setUri($uri)
    {
        if (is_string($uri)) {
            try {
                $uri = new HttpUri($uri);
            } catch (UriException\InvalidUriPartException $e) {
                throw new InvalidArgumentException(
                    sprintf('Invalid URI passed as string (%s)', (string) $uri),
                    $e->getCode(),
                    $e
                );
            }
        } elseif (! ($uri instanceof HttpUri)) {
            throw new InvalidArgumentException(
                'URI must be an instance of Zend\Uri\Http or a string'
            );
        }
        $serviceName = $uri->getHost();
        $serviceHost = $this->resolveServiceHost($serviceName);
        $uri->setHost($serviceHost);
        parent::setUri($uri);
    }

    /**
     * @param $serviceName
     * @return string
     */
    protected function resolveServiceHost(string $serviceName) {
        $query = new Query();
        $query->setQuery(new EqNode(MeshInterface::FIELD_SERVICE_NAME, $serviceName));
        $result = $this->meshDataStore->query($query);
        return empty($result) ? $serviceName : current($result)[MeshInterface::FIELD_SERVICE_HOST];
    }

}