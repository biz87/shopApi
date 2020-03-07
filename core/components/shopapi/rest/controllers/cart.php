<?php

class shopCart extends shopBaseRestController
{
    public $allowedMethods = array('get', 'post', 'delete', 'put');


    public function getList()
    {
        return $this->success('', array_merge($this->ms2()->cart->status(), array('cart' => $this->ms2()->cart->get())));
    }

    public function post()
    {
        $product_id = (int)$this->getProperty('product');
        $count = (int)$this->getProperty('count');
        $size = $this->getProperty('size');

        if (!$product_id) {
            return $this->failure('Выберите продукт');
        }

        if ($count === 0) {
            $count = 1;
        }

        $resultSize = $this->checkSize($product_id, $size);

        if (!$resultSize) {
            return $this->failure('Укажите фасовку');
        }

        $options = array();
        $options['size'] = $size;

        $result = $this->ms2()->cart->add($product_id, $count, $options);
        if ($result['success']) {
            return $this->success($result['message'], $result['data']);
        } else {
            return $this->failure($result['message'], $result['data']);
        }
    }

    public function delete()
    {
        return $this->success('Cart cleaned', $this->ms2()->cart->clean());
    }

    public function put()
    {
        $key = $this->getProperty('key');
        $count = (int)$this->getProperty('count');
        $size = $this->getProperty('size');

        $cart = $this->ms2()->cart->get();

        if (!$key || !array_key_exists($key, $cart)) {
            return $this->failure('Неверные данные', array_merge(
                $cart
            ));
        }

        if ($count === 0) {
            $count = $cart[$key]['count'];
        }

        if ($count <= 0) {
            $this->ms2()->cart->remove($key);
        }


        if ($size != $cart[$key]['options']['size']) {
            $checkedSize = $this->checkSize($cart[$key]['id'], $size);
            if ($checkedSize) {
                $cart[$key]['options']['size'] = $size;
                $this->ms2()->cart->set($cart);

            }
        }


        $this->ms2()->cart->change($key, $count);


        return $this->success('Корзина обновлена', array_merge($this->ms2()->cart->status(), array('cart' => $this->ms2()->cart->get())));
    }


    /**
     * Если у товара есть фасовка, проверяю указана ли она!
     * @param integer $product_id
     * @param mixed $size
     * @return bool
     */
    private function checkSize($product_id, $size)
    {
        $product = $this->getProduct($product_id);

        if (empty($product['size'][0])) {
            return false;
        }


        if (in_array($size, $product['size'])) {
            return true;
        }

        return false;

    }


    /**
     * @param integer $product_id
     * @return array
     */
    private function getProduct($product_id)
    {
        $product = $this->pdo->getArray(
            'msProduct',
            array('id' => $product_id),
            array(
                'leftJoin' => [
                    "Data" => [
                        "class" => "msProductData",
                        "alias" => "Data",
                    ]
                ],
                'select' => array(
                    'msProduct' => 'id, pagetitle',
                    'Data' => 'size',

                ),

            )
        );

        return $product;
    }


}