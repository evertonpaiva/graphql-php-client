<?php

namespace GraphqlClient\GraphqlQuery;

/**
 * Class PaginationQuery
 * Define as informações de paginação
 *
 * @package GraphqlClient\GraphqlRequest
 */
abstract class PaginationQuery
{
    /**
     * Número de registros retornados
     * @var int
     */
    protected $size;

    /**
     * Ponteiro para um determinado registro
     * @var String
     */
    protected $cursor;

    public function __construct(int $size, String $cursor = null)
    {
        $this->size = $size;

        if (!is_null($cursor)) {
            $this->cursor = $cursor;
        }
    }

    /**
     * Recupera o tamanho da paginação
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Recupera o cursor
     * @return String
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * Nome do campo que informa o tamanho
     * @return mixed
     */
    abstract public function getSizeName();

    /**
     * Nome do campo que informa o tipo de cursor
     * @return mixed
     */
    abstract public function getCursorName();
}
