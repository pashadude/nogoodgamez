<?php
namespace Controllers;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\MongoDB\Query;


class QueryController{
    private $dm;

    public function __construct(DocumentManager $dm) {
        $this->dm = $dm;
    }

    public function findOneItem ($collection_elem, $field, $value) {
        $result = $this->dm->createQueryBuilder(get_class($collection_elem))
            ->field($field)->equals($value)
            ->hydrate(false)
            ->getQuery()
            ->getSingleResult();
        return $result;
    }
}


?>