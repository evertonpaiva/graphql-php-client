<?php
namespace GraphqlClient\GraphqlQuery;

use GraphqlClient\GraphqlRequest\GraphqlRequest;

class RelationQuery
{
    private $relationName;
    private $relationClass;
    private $relation;

    public function __construct($relationName, $relationClass, $relation)
    {
        $this->relationName = $relationName;
        $this->relationClass = $relationClass;
        $this->relation = $relation;
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
    
    public function setRelation($relation)
    {
        $this->relation = $relation;
    }
}
