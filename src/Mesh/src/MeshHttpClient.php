<?php


namespace rollun\mesh;

use rollun\dic\InsideConstruct;
use Zend\Http\Exception\InvalidArgumentException;
use Zend\Http\Client;
use Zend\Http\Request;
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
    protected $mesh;

    /**
     * MeshHttpClient constructor.
     * @param MeshInterface $mesh
     * @param null $uri
     * @param array $options
     */
    public function __construct(MeshInterface $mesh = null, $uri = null, array $options = [])
    {
        InsideConstruct::setConstructParams(["mesh" => MeshInterface::class]);
        if(!isset($this->mesh)) {
            throw new \InvalidArgumentException("You must pass a valid rollun\mesh\MeshInterface");
        }
        parent::__construct($uri, $options);
    }

    /**
     * Get service host by service name or use existing if not found.
     * @param string|HttpUri $uri
     * @return Client
     */
    public function setUri($uri)
    {
        if(!empty($uri)) {
            if (is_string($uri)) {
                try {
                    $uri = new HttpUri($uri);
                } catch (UriException\InvalidUriPartException $e) {
                    throw new InvalidArgumentException(
                        sprintf('Invalid URI passed as string (%s)', (string)$uri),
                        $e->getCode(),
                        $e
                    );
                }
            } elseif (!($uri instanceof HttpUri)) {
                throw new InvalidArgumentException(
                    'URI must be an instance of Zend\Uri\Http or a string'
                );
            }
            $serviceName = $uri->getHost();
            try {
                $serviceHost = $this->mesh->resolveServiceHost($serviceName);
            } catch (ServiceHostNotFound $exception) {
                //if service host not resolved use service name.
                $serviceHost = $serviceName;
            }
            $uri->setHost($serviceHost);
        }
        return parent::setUri($uri);
    }

    /**
     * @param Request $request
     * @return $this|Client
     */
    public function setRequest(Request $request)
    {
        parent::setRequest($request);
        $this->setUri($request->getUri());
        return $this;
    }


}