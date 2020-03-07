<?php

class shopOrder extends shopBaseRestController
{
    public $allowedMethods = array('post');

    public function post()
    {
        $cart = $this->ms2()->cart->get();

        if (!count($cart)) {
            $result = [
                'message' => 'Корзина пуста',
                'success' => false
            ];
            return $this->failure('', $result);
        }

        //Получатель
        $email = $this->getProperty('email');
        $phone = $this->getProperty('phone');
        $receiver = $this->getProperty('name');

        //Дата и время доставки
        $soon = (bool)$this->getProperty('soon');
        $delivery_date = $this->getProperty('delivery_date');
        $delivery_time = $this->getProperty('delivery_time');

        //Адрес
        $street = $this->getProperty('street');
        $building = $this->getProperty('building');
        $room = $this->getProperty('apartment');
        $entrance = $this->getProperty('entrance');
        $floor = $this->getProperty('floor');

        $comment = $this->getProperty('comment');

        //Доставка и оплата
        $payment = (int)$this->getProperty('payment');
        $delivery = (int)$this->getProperty('delivery');

        $this->modx->log(1, print_r(
            array(), 1
        ));

        $this->ms2()->order->add('payment', $payment);
        $this->ms2()->order->add('delivery', $delivery);

        $this->ms2()->order->add('receiver', $receiver);
        $this->ms2()->order->add('email', $email);
        $this->ms2()->order->add('phone', $phone);

        $this->ms2()->order->add('soon', $soon);
        $this->ms2()->order->add('delivery_date', $delivery_date);
        $this->ms2()->order->add('delivery_time', $delivery_time);

        $this->ms2()->order->add('street', $street);
        $this->ms2()->order->add('building', $building);
        $this->ms2()->order->add('room', $room);
        $this->ms2()->order->add('entrance', $entrance);
        $this->ms2()->order->add('floor', $floor);
        $this->ms2()->order->add('comment', $comment);


        $response = $this->ms2()->order->submit();
        if (isset($response['success']) && $response['success'] == false) {
            $result['post'] = $this->properties;
            $result['response'] = $response;
            return $this->failure('', $result);
        }
        if (isset($response['success']) && $response['success'] == true) {
            $result['post'] = $this->properties;
            $result['response'] = $response;
            $this->ms2()->cart->clean();
            return $this->success('', $result);
        }


    }


}