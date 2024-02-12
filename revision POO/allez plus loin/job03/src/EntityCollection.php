<?php

namespace App;




class EntityCollection {
    private $entities = [];

    public function add(EntityInterface $entity): self {
        $this->entities[] = $entity;
        return $this;
    }

    public function remove(EntityInterface $entity): self {
        $key = array_search($entity, $this->entities, true);
        if ($key !== false) {
            unset($this->entities[$key]);
        }
        return $this;
    }

    public function retrieve(EntityInterface $entity): array {
        $relatedEntities = [];
        foreach ($this->entities as $storedEntity) {
            if ($storedEntity === $entity) {
                $relatedEntities[] = $storedEntity;
            }
        }
        return $relatedEntities;
    }
}
