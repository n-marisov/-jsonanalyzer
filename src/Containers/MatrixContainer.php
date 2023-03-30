<?php

namespace Maris\JsonAnalyzer\Containers;

use Maris\JsonAnalyzer\Analyzer;
use Maris\JsonAnalyzer\Exceptions\ContainerException;
use Maris\JsonAnalyzer\Exceptions\NotFoundException;
use Maris\JsonAnalyzer\Interfaces\AdapterContainerInterface;
use Maris\JsonAnalyzer\Interfaces\JsonAdapterInterface;
use Maris\JsonAnalyzer\Matrix\Matrix;
use Psr\Container\ContainerInterface;

class MatrixContainer implements ContainerInterface
{
    /**
     * @var array< class-string|string , Matrix>
     */
    private array $matrices = [];

    private Analyzer $analyzer;


    public function __construct( Analyzer $analyzer )
    {
        $this->analyzer = $analyzer;
    }


    /**
     * @param string $id
     * @return Matrix
     */
    public function get( string $id ):?Matrix
    {
        if( !$this->has($id) && class_exists($id)){
            $this->matrices[$id] = new Matrix( $id, $this->analyzer );
        }
        if(!$this->has($id)) return null;
        return $this->matrices[$id];
    }

    /**
     * @inheritDoc
     */
    public function has( string $id ): bool
    {
        return isset( $this->matrices[$id] );
    }
}