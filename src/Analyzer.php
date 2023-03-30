<?php

namespace Maris\JsonAnalyzer;

use Exception;
use Maris\JsonAnalyzer\Containers\AdapterContainer;
use Maris\JsonAnalyzer\Containers\MatrixContainer;
use Maris\JsonAnalyzer\Exceptions\ContainerException;
use Maris\JsonAnalyzer\Interfaces\JsonAdapterInterface;
use Maris\JsonAnalyzer\Tools\EventDispatchers\Dispatcher;
use Maris\JsonAnalyzer\Tools\EventDispatchers\Event;
use Maris\JsonAnalyzer\Tools\Filters\NamespaceFilter;
use Psr\Log\LoggerInterface;


/**
 * Обработчик объекта
 */
class Analyzer
{
    /**
     * The logger instance.
     *
     * @var LoggerInterface|null
     */
    protected ?LoggerInterface $logger = null;

    public readonly string $namespace;
    public readonly MatrixContainer $matrices;
    public readonly AdapterContainer $adapters;
    public readonly NamespaceFilter $namespaceFilter;

    public readonly Dispatcher $eventDispatcher;

    public function __construct( string $namespace = Json::DEFAULT_NAMESPACE )
    {
        $this->namespace = $namespace;
        $this->adapters = new AdapterContainer( $this );
        $this->namespaceFilter = new NamespaceFilter( $namespace );
        $this->matrices = new MatrixContainer( $this );

        $this->eventDispatcher = new Dispatcher();
    }

    /**
     * @param class-string<? Event> $event
     * @param callable $handler
     * @return $this
     * @throws Exception
     */
    public function addEventListener( string $event , callable $handler):self
    {
        $this->eventDispatcher->provider->addListener( $event, $handler );
        return $this;
    }


    public function fromArray(object|array $data , string $target , ?object $parent = null ):array|object|null
    {
        $start = $this->matrices->get( $target )?->getStartJsonKey();

        if( is_array($data) && !array_is_list($data) )
            $data = json_decode(json_encode($data));

        if(isset($start) && is_object($data) && property_exists($data,$start))
            return $this->fromProcess($data->{$start},$target,$parent);

        return $this->fromProcess($data,$target,$parent);
    }

    protected function fromProcess( object|array $data , string $target , ?object $parent = null):array|object|null
    {
        if( is_array($data) && array_is_list($data) ){
            foreach ($data as $key => $value)
                $data[$key] = $this->fromArray( $value, $target, $parent );
            return $data;
        }
        return $this->matrices->get($target)?->fromJson( (object) $data, $parent );
    }

    public function toArray( object|array $data ):array
    {
        if(is_object($data)){
            return $this->matrices->get( $data::class )?->toJson( $data );
        }else{
            foreach ($data as $key => $value){
                if(is_array($value) || is_object($value))
                    $data[$key] = $this->toArray( $value );
            }
        }
        return $data;
    }

    public function registeredAdapter( string $type, JsonAdapterInterface $adapter ):self
    {
        try {
            $this->adapters->set($type, $adapter);
        } catch (ContainerException $e) {
            $this->logger->error($e->getMessage());
        }
        return $this;
    }

    /**
     * Получаем логгер
     * @return LoggerInterface|null
     */
    public function getLogger():?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Устанавливаем логер
     * @param LoggerInterface|null $logger
     * @return Analyzer
     */
    public function setLogger(?LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return $this;
    }

}