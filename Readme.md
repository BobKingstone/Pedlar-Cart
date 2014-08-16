Pedlar-Cart Laravel Package
===============

[![Build Status](https://travis-ci.org/BobKingstone/Pedlar-Cart.svg?branch=master)](https://travis-ci.org/BobKingstone/Pedlar-Cart)

Laravel e-commerce shopping cart package.

Installation
-----------

Add bobkingstone/pedlarcart to your composer.json

    "require": {
        "bobkingstone/pedlarcart": "dev-master"
    }

Run composer update

    composer update

Once this has completed add the service provider to the array of providers in `app/config/app.php`

    'Bobkingstone\PedlarCart\PedlarCartServiceProvider'


Usage
---

The package generates a 'Cart' facade for the package automatically so there is no need to add it to the alias array.

To add an item to the cart:

    $item = array(
        'id => '1',
        'qty' => 2,
        'price' => 200.00,
    );

    Cart::add($item);

To get the total number of all items in cart:

    Cart::countItems();

To get the total value of all items in cart:

    Cart::totalValue();

To get a count of unique items in cart:

    Cart::totalUniqueItems();

To empty the cart:

    Cart::clear();


Exceptions
---
