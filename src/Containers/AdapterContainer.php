<?php

namespace Maris\JsonAnalyzer\Containers;

use Maris\JsonAnalyzer\Adapters\BoolAdapter;
use Maris\JsonAnalyzer\Adapters\FloatAdapter;
use Maris\JsonAnalyzer\Adapters\IntAdapter;
use Maris\JsonAnalyzer\Adapters\StringAdapter;
use Maris\JsonAnalyzer\Analyzer;
use Maris\JsonAnalyzer\Exceptions\ContainerException;
use Maris\JsonAnalyzer\Exceptions\NotFoundException;
use Maris\JsonAnalyzer\Interfaces\AdapterContainerInterface;
use Maris\JsonAnalyzer\Interfaces\JsonAdapterInterface;

class AdapterContainer implements AdapterContainerInterface
{

    protected Analyzer $analyzer;
    /**
     * @var array<class-string|string , JsonAdapterInterface>
     */
    private array $adapters = [];

    /**
     * @param Analyzer $analyzer
     */
    public function __construct( Analyzer $analyzer )
    {
        $this->analyzer = $analyzer;
    }


    /**
     * @inheritDoc
     */
    public function set(string $type, JsonAdapterInterface $adapter ): static
    {
        if(  !class_exists($type) && !in_array($type,["string","int","float","bool"]) ){
            throw new ContainerException("{type} не является допустимым типом данных",[
                "type"=> $type
            ]);
        }
        $this->adapters[$type] = $adapter;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     */
    public function get(string $id):JsonAdapterInterface
    {
        if(!$this->has($id))
            throw new NotFoundException("Адаптер не существует");
            
        return $this->adapters[$id];
    }

    /**
     * @inheritDoc
     */
    public function has( string $id ): bool
    {
        if(!isset( $this->adapters[$id] ) && in_array($id,["float","int","string","bool"])){
            $this->adapters[$id] = match ($id){
                "float" => new FloatAdapter( $this->analyzer->getLogger() ),
                "int" => new IntAdapter( $this->analyzer->getLogger() ),
                "string" => new StringAdapter( $this->analyzer->getLogger() ),
                "bool" => new BoolAdapter( $this->analyzer->getLogger() ),
            };
        }
        return isset( $this->adapters[$id] );
    }
}