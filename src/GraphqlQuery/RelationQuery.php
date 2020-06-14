<?php
namespace GraphqlClient\GraphqlQuery;

use GraphqlClient\GraphqlRequest\GraphqlRequest;

class RelationQuery
{
    private $type;
    private $relationName;
    private $relationClass;
    private $relation;
    private $pagination;
    private $filters;

    public function __construct(
        string $type,
        string $relationName,
        string $relationClass,
        $relation = null,
        $pagination = null,
        $filters = null
    ) {
        $this->type = $type;
        $this->relationName = $relationName;
        $this->relationClass = $relationClass;
        $this->relation = $relation;
        $this->pagination = $pagination;
        $this->filters = $filters;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getRelationName()
    {
        return $this->relationName;
    }

    public function getRelationClass()
    {
        return $this->relationClass;
    }

    public function getRelation()
    {
        return $this->relation;
    }

    public function setRelation(GraphqlRequest $relation)
    {
        $this->relation = $relation;
    }

    public function getPagination()
    {
        return $this->pagination;
    }

    public function setPagination(PaginationQuery $pagination)
    {
        $this->pagination = $pagination;
    }

    public function getFilters()
    {
        return $this->filters;
    }
}
