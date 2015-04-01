<?php

namespace Hydrators;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Hydrator\HydratorInterface;
use Doctrine\ODM\MongoDB\UnitOfWork;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ODM. DO NOT EDIT THIS FILE.
 */
class DocumentsAssessmentHydrator implements HydratorInterface
{
    private $dm;
    private $unitOfWork;
    private $class;

    public function __construct(DocumentManager $dm, UnitOfWork $uow, ClassMetadata $class)
    {
        $this->dm = $dm;
        $this->unitOfWork = $uow;
        $this->class = $class;
    }

    public function hydrate($document, $data, array $hints = array())
    {
        $hydratedData = array();

        /** @Field(type="id") */
        if (isset($data['_id'])) {
            $value = $data['_id'];
            $return = $value instanceof \MongoId ? (string) $value : $value;
            $this->class->reflFields['id']->setValue($document, $return);
            $hydratedData['id'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['game'])) {
            $reference = $data['game'];
            if (isset($this->class->fieldMappings['game']['simple']) && $this->class->fieldMappings['game']['simple']) {
                $className = $this->class->fieldMappings['game']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->unitOfWork->getClassNameForAssociation($this->class->fieldMappings['game'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['game']->setValue($document, $return);
            $hydratedData['game'] = $return;
        }

        /** @ReferenceOne */
        if (isset($data['user'])) {
            $reference = $data['user'];
            if (isset($this->class->fieldMappings['user']['simple']) && $this->class->fieldMappings['user']['simple']) {
                $className = $this->class->fieldMappings['user']['targetDocument'];
                $mongoId = $reference;
            } else {
                $className = $this->unitOfWork->getClassNameForAssociation($this->class->fieldMappings['user'], $reference);
                $mongoId = $reference['$id'];
            }
            $targetMetadata = $this->dm->getClassMetadata($className);
            $id = $targetMetadata->getPHPIdentifierValue($mongoId);
            $return = $this->dm->getReference($className, $id);
            $this->class->reflFields['user']->setValue($document, $return);
            $hydratedData['user'] = $return;
        }

        /** @Field(type="boolean") */
        if (isset($data['like'])) {
            $value = $data['like'];
            $return = (bool) $value;
            $this->class->reflFields['like']->setValue($document, $return);
            $hydratedData['like'] = $return;
        }

        /** @Field(type="date") */
        if (isset($data['happened'])) {
            $value = $data['happened'];
            if ($value instanceof \MongoDate) { $return = new \DateTime(); $return->setTimestamp($value->sec); } elseif (is_numeric($value)) { $return = new \DateTime(); $return->setTimestamp($value); } elseif ($value instanceof \DateTime) { $return = $value; } else { $return = new \DateTime($value); }
            $this->class->reflFields['happened']->setValue($document, clone $return);
            $hydratedData['happened'] = $return;
        }
        return $hydratedData;
    }
}