Pedlar-Cart Laravel Package
===============

[![Build Status](https://travis-ci.org/BobKingstone/Pedlar-Cart.svg?branch=master)](https://travis-ci.org/BobKingstone/Pedlar-Cart)[![Latest Stable Version](https://poser.pugx.org/bobkingstone/pedlar-cart/v/stable.svg)](https://packagist.org/packages/bobkingstone/pedlar-cart) [![Total Downloads](https://poser.pugx.org/bobkingstone/pedlar-cart/downloads.svg)](https://packagist.org/packages/bobkingstone/pedlar-cart) [![Latest Unstable Version](https://poser.pugx.org/bobkingstone/pedlar-cart/v/unstable.svg)](https://packagist.org/packages/bobkingstone/pedlar-cart) [![License](https://poser.pugx.org/bobkingstone/pedlar-cart/license.svg)](https://packagist.org/packages/bobkingstone/pedlar-cart)

Laravel e-commerce shopping cart package.

Installation
-----------

Add bobkingstone/pedlar-cart to your composer.json

    "require": {
        "bobkingstone/pedlar-cart": "dev-master"
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
        'id => '1', //your cart id
        'qty' => 2,
        'price' => 200.00,
    );

    $CartItemIdentifier = Cart::add($item);

To get the total number of all items in cart:

    Cart::countItems();

To get an array of CartItems from cart:

    Cart::all();

To access cart items values:

    foreach (Cart::all() as $item)
    {
        echo $item->id;
        echo $item->price;
        echo $item->qty;
    };

To get the total value of all items in cart:

    Cart::totalValue();

To get a count of unique items in cart:

    Cart::totalUniqueItems();

To empty the cart:

    Cart::clear();

To set tax rate, you can either add it to each item:

    $item = array(
        'id => '1',
        'qty' => 2,
        'tax' => 20,
        'price' => 200.00,
    );

    Cart::totalWithTax();

Or pass in the percentage with the cart total calculation (this will override each items predefined tax rate):

    Cart::totalWithTax(20);


Exceptions
---

The package will throw InvalidNumberOfValuesException if one of the following required params is missing:

    $requiredParams = array (
        'id',
        'qty',
        'price'
    );

It will also throw InvalidItemKeyException if an invalid update is passed to a cart item.