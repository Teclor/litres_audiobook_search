<?php

namespace Rest;


use \ORM\ITable;

class EntityJsonConverter
{
    private ITable $entity;
    private $primaryKeyValue;
    private array $additionalFields;
    
    public function __construct(ITable $entity, $primaryKeyValue, array $additionalFields = [])
    {
        $this->entity = $entity;
        $this->primaryKeyValue = $primaryKeyValue;
        $this->additionalFields = $additionalFields;
    }
    
    public function convert(): string
    {
        $entityData = [];
        
        $entityElement = current($this->entity::select(['=' . $this->entity::getPrimaryKey() => $this->primaryKeyValue]));
        if (empty($entityElement)) {
            throw new \Exception("Entity not found by primary key $this->primaryKeyValue");
        }
        
        $allFields = array_merge($this->additionalFields, $entityElement);
        foreach ($allFields as $key => $value) {
            $newKey = '';
            $key = strtolower($key);
            $keyParts = explode('_', $key);
            foreach ($keyParts as $keyPart) {
                if ($newKey !== '') {
                    $keyPart = ucfirst($keyPart);
                }
                $newKey .= $keyPart;
            }
            $entityData[$newKey] = $value;
        }
        
        return json_encode($entityData);
    }
}