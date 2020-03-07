<?php

class shopDeliveries extends shopBaseRestController
{
    public $allowedMethods = array('get');
    public $classKey = 'msDelivery';
    public $defaultSortField = 'rank';
    public $defaultSortDirection = 'ASC';


    public function getList()
    {
        $deliveries = $this->pdo->getCollection(
            $this->classKey,
            array('active' => 1),
            array(
                'select' => 'id, name, description, requires',
                'sortby' => $this->defaultSortField,
                'sortdir' => $this->defaultSortDirection
            )
        );

        $result = $this->success('', $deliveries);

        return $result;
    }

    public function read($id)
    {
        $delivery = $this->pdo->getArray(
            $this->classKey,
            array('active' => 1, 'id' => $id),
            array(
                'select' => 'id, name, description, requires',
                'sortby' => $this->defaultSortField,
                'sortdir' => $this->defaultSortDirection
            )
        );

        if($delivery){
            return $this->success('', $delivery);
        } else {
            return $this->failure('not found', $id);
        }

    }

}