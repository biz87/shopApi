## Категории товаров

Список категорий

- GET https://shop.krendel.kz/rest/categories

Одна категория

- GET https://shop.krendel.kz/rest/categories/{id}
- GET https://shop.krendel.kz/rest/categories/{alias}

Параметры не принимает. 


## Товары

Общий перечень

- GET https://shop.krendel.kz/rest/products

Фильтр по родителю

- GET https://shop.krendel.kz/rest/products?parent={parent_id}

Фильтр по тэгу

- GET https://shop.krendel.kz/rest/products?tag={tag}

Один товар

- GET https://shop.krendel.kz/rest/products/{id}
- GET https://shop.krendel.kz/rest/products/{alias}


## Корзина

Просмотр корзины
- GET https://shop.krendel.kz/rest/cart

Добавить в корзину

- POST https://shop.krendel.kz/rest/cart
- Обязательный параметр id товара  product={product_id}
- Обязательный параметр size (фасовка)  size={size}
-  Не обязательный параметр count (количество). По умолчанию 1   count=1 


Очистить  корзину

- DELETE  https://shop.krendel.kz/rest/cart
- обязательный параметр key (хэш товара в корзине)
- Для удаления одной позиции нужно использовать метод пересчета корзины, передав нулевое количество нужного товара


Пересчет корзины
- PUT https://shop.krendel.kz/rest/cart
- Обязательный параметр key (хэш) товара в корзине
- count - количество товара для пересчета
- size - размер фасовки продукции



## Способы оплаты
- GET https://shop.krendel.kz/rest/payments

## Способы доставки
- GET https://shop.krendel.kz/rest/deliveries


## Заказ
Оформлениее заказа протекает в два этапа. 
1. Заполнение полей. По одному за раз. 
    


2. Отправка заказа (submit) 
- POST https://shop.krendel.kz/rest/order




