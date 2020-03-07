<?php

class shopBaseRestController extends modRestController
{
    public $allowedMethods = array('get');
    public $classKey = 'modResource';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $whereCondition = array();
    public $pdo;


    public function initialize()
    {
        $this->pdo = $this->pdoFetch();
    }

    /** {@inheritdoc} */
    public function methodAllowed($method)
    {
        return in_array($method, $this->allowedMethods);
    }

    /** {@inheritdoc} */
    public function get()
    {
        if ($this->methodAllowed('get')) {
            return parent::get();
        } else {
            return $this->failure('Method not allowed');
        }
    }

    /** {@inheritdoc} */
    public function post()
    {
        if ($this->methodAllowed('post')) {
            return parent::post();
        } else {
            return $this->failure('Method not allowed');
        }
    }

    /** {@inheritdoc} */
    public function put()
    {
        if ($this->methodAllowed('put')) {
            return parent::put();
        } else {
            return $this->failure('Method not allowed');
        }
    }

    /** {@inheritdoc} */
    public function delete()
    {
        if ($this->methodAllowed('delete')) {
            return parent::delete();
        } else {
            return $this->failure('Method not allowed');
        }
    }

    /**
     * Example connect miniShop2 service
     *
     * @return null|miniShop2
     */
    public function ms2()
    {
        /** @var null|miniShop2 $ms2 */
        $ms2 = $this->modx->getService('minishop2');
        $ms2->initialize($this->modx->context->key, array('json_response' => false));
        return $ms2;
    }

    /**
     * Example connect pdoFetch service
     *
     * @return null|pdoFetch
     */
    public function pdoFetch()
    {
        /** @var null|pdoFetch $pdoFetch */
        $pdoFetch = $this->modx->getService('pdoFetch');
        return $pdoFetch;
    }

}