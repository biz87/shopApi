<?php

class shopResourcesRestController extends shopBaseRestController
{

    public $whereCondition = array('published' => true, 'deleted' => false);

    public function initialize()
    {
        parent::initialize();

        if ($this->getProperty('alias')) {
            $this->whereCondition['alias'] = $this->getProperty('alias');

            $resource = $this->pdo->getArray(
                'modResource',
                $this->whereCondition,
                array(
                    'select' => array(
                        'modResource' => 'id,  alias',
                    ),
                )
            );

            if ($resource) {
                $this->setProperty($this->primaryKeyField, $resource['id']);
            }

        }

        if ($this->getProperty('parent')) {
            $this->whereCondition['parent'] = $this->getProperty('parent');
        }
    }

}