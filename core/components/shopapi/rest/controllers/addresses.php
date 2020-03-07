<?php

class shopAddresses extends shopBaseRestController
{
    public $allowedMethods = array('get');
    public $classKey = 'address_item';
    private $query = '';


    public function initialize()
    {
        parent::initialize();
        $this->modx->addPackage('addresses', MODX_CORE_PATH . 'components/addresses/model/');

        $this->query = $this->getProperty('query');

        if (empty($this->query) || mb_strlen($this->query < 3)) {
            return $result = $this->failure('Введите хотя бы три буквы адреса', $this->query);
        }
    }


    public function get()
    {
        $q = $this->modx->newQuery('address_item');
        $q->select(array(
                'address_item.*',
                'number' => 'CAST(building as UNSIGNED)'
            )
        );
        $q->where(array(
            'address_item.street:LIKE' => "%{$this->query}%",
            'address_item.active' => 1
        ));
        $q->sortby('street', 'ASC');
        $q->sortby('number', 'ASC');

        $q->limit(100);
        /** @var xPDOObject $address */
        $addresses = $this->modx->getIterator('address_item', $q);
        $arr = [];
        if ($addresses) {
            foreach ($addresses as $address) {
                $arr[] = array_merge(
                    $address->toArray(),
                    array(
                        'name' => $address->street . ' ' . $address->building
                    )
                );
            }
        }
        $result = $this->success('', $arr);

        return $result;


    }

}