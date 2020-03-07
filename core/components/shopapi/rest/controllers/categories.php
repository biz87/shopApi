<?php

require_once dirname(dirname(__FILE__)) . '/resourcescontroller.php';

class shopCategories extends shopResourcesRestController
{

    public $classKey = 'msCategory';

    public function initialize()
    {
        parent::initialize();
        $this->whereCondition['class_key'] = 'msCategory';
        //Исключаю эксклюзивный заказ
        $this->whereCondition['id:!='] = 270;
    }


    public function getList()
    {

        $collection = $this->pdo->getCollection(
            $this->classKey,
            $this->whereCondition,
            array(
                'loadModels' => 'ms2gallery',
                'leftJoin' => [
                    "image" => [
                        "class" => "msResourceFile",
                        "alias" => "image",
                        "on" => "image.resource_id = msCategory.id  AND image.rank = 0 AND image.path LIKE '%/small/%'"
                    ]
                ],
                'select' => array(
                    'msCategory' => 'id, pagetitle, alias ,menuindex',
                    "image" => "image.url as image"
                ),
                'sortby' => 'menuindex',
                'sortdir' => 'asc',

            )
        );

        $total = count($collection);

        foreach ($collection as $key => $cat) {
            $collection[$key]['image'] = 'https://shop.krendel.kz' . $cat['image'];
            $children = $this->modx->getChildIds($cat['id'], 1, array('context' => 'web'));
            $collection[$key]['products_count'] = count($children);
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

        $category = $this->pdo->getArray(
            $this->classKey,
            $this->whereCondition,
            array(
                'select' => array(
                    'msCategory' => 'id, pagetitle, alias'
                ),
            )
        );
        if (empty($category)) {
            return $this->failure($this->modx->lexicon('rest.err_obj_nf', array(
                'class_key' => $this->classKey,
            )));
        }
        $children = $this->modx->getChildIds($category['id'], 1, array('context' => 'web'));
        $category['products_count'] = count($children);

        $afterRead = $this->afterRead($category);

        if ($afterRead !== true && $afterRead !== null) {
            return $this->failure($afterRead === false ? $this->errorMessage : $afterRead);
        }

        return $this->success('', $category);

    }
}