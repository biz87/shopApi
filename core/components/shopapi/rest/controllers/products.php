<?php

require_once dirname(dirname(__FILE__)) . '/resourcescontroller.php';

class shopProducts extends shopResourcesRestController
{
    public $classKey = 'msProduct';

    public function initialize()
    {
        parent::initialize();
        $this->whereCondition['class_key'] = 'msProduct';

        //Исключаю ненужный раздел
        $this->whereCondition['parent:!='] = '270';

        if ($this->getProperty('tag')) {
            $this->whereCondition['Tags.value:LIKE'] = $this->getProperty('tag');
        }
    }

    public function getList()
    {
        $leftJoin = [];
        $leftJoin["Data"] = [
            "class" => "msProductData",
            "alias" => "Data",
        ];

        if ($this->getProperty('tag')) {
            $leftJoin["Tags"] = [
                'class' => 'msProductOption',
                'alias' => 'Tags',
                'on' => 'msProduct.id = Tags.product_id'
            ];
        }


        $select = [];
        $select['msProduct'] = 'id, pagetitle, alias ,menuindex, description';
        $select['Data'] = 'price, old_price, size, thumb, new, popular, favorite, tags, measure, avability, split_items';

        if ($this->getProperty('tag')) {
            $select['Tags'] = 'Tags.value as tag';

        }

        $collection = $this->pdo->getCollection(
            $this->classKey,
            $this->whereCondition,
            array(
                'leftJoin' => $leftJoin,
                'select' => $select,
                'sortby' => 'Data.new',
                'sortdir' => 'desc',

            )
        );

        $total = count($collection);

        foreach ($collection as $key => $cat) {
            $collection[$key]['thumb'] = 'https://shop.krendel.kz' . $cat['thumb'];
            $collection[$key]['measure'] = $this->getMeasureTitle($cat['measure']);
        }


        return $this->collection($collection, $total);
    }

    public function read($id)
    {
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('rest.err_field_ns', array(
                'field' => $this->primaryKeyField,
            )));
        }

        $this->whereCondition['id'] = $id;

        $product = $this->pdo->getArray(
            $this->classKey,
            $this->whereCondition,
            array(
                'leftJoin' => [
                    "Data" => [
                        "class" => "msProductData",
                        "alias" => "Data",
                    ]
                ],
                'select' => array(
                    'msProduct' => 'id, pagetitle, alias ,menuindex, description',
                    'Data' => 'price, old_price, size, thumb, new, popular, favorite, tags, measure, avability, split_items',

                ),
            )
        );
        if (empty($product)) {
            return $this->failure($this->modx->lexicon('rest.err_obj_nf', array(
                'class_key' => $this->classKey,
            )));
        }

        $product['thumb'] = 'https://shop.krendel.kz' . $product['thumb'];
        $product['measure'] = $this->getMeasureTitle($product['measure']);

        return $this->success('', $product);

    }

    private function getMeasureTitle($measure)
    {
        switch ($measure) {
            case'1':
                return 'кг.';
                break;
            case'2':
                return 'шт.';
                break;
            case'3':
                return 'уп.';
                break;
            case'4':
                return '8 шт.';
                break;
        }
    }
}