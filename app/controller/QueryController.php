<?php
namespace Controllers;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\MongoDB\Query;


class QueryController{
    private $dm;

    public function __construct(DocumentManager $dm) {
        $this->dm = $dm;
    }

    public function findOneItem($collection_elem, $field, $value) {
        $result = $this->dm->createQueryBuilder(get_class($collection_elem))
            ->field($field)->equals($value)
            ->hydrate(false)
            ->getQuery()
            ->getSingleResult();
        return $result;
    }

    public function findById($id, $collection_elem) {
        return $this->dm->find(get_class($collection_elem), $id);
    }

    public function giveDistinctValues ($collection_elem, $field) {
        $results = $this->dm->createQueryBuilder(get_class($collection_elem))
            ->distinct($field)
            ->getQuery()
            ->execute()
            ->toArray();
            //->count();

        return $results;
    }

    public function findByRef($field_name, $target_elem, $elem){
        $qb = $this->dm->createQueryBuilder(get_class($target_elem))
            ->field($field_name)->references($elem);
        return $qb;
    }
}


?>