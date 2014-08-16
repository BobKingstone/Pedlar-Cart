<?php

namespace spec\Bobkingstone\PedlarCart;

use Bobkingstone\PedlarCart\CartItem;
use Bobkingstone\PedlarCart\Storage\SessionStorage;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CartSpec extends ObjectBehavior
{
    private $cartItemComplete = [
        'id' => '1',
        'name' => 'foo',
        'qty' => 1,
        'price' => 200
    ];

    private $cartItem2 = [
        'id' => '2',
        'name' => 'bar',
        'qty' => 2,
        'price' => 300.05,
    ];

    private $cartItemLessQty = [
        'id' => '1',
        'name' => 'foo',
        'price' => 200
    ];

    private $cartItemWithIdent = [
        'id' => '1',
        'name' => 'foo',
        'qty' => 1,
        'price' => 200,
        'identifier' => '91936a0df88d531b5a770b614cd3c1ea'
    ];

    function let(SessionStorage $storage)
    {
        $this->beConstructedWith($storage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Bobkingstone\PedlarCart\Cart');
    }

    function it_should_return_a_hash_of_item_as_identifier(SessionStorage $store)
    {
        $store->insert()->willReturn();

        $expected = md5(serialize($this->cartItemLessQty));

        $this->insert($this->cartItemComplete)->shouldReturn($expected);
    }

    function it_should_find_an_item_using_hash(SessionStorage $storage, CartItem $item)
    {
        $storage->find('hash')->willReturn($item);

        $this->find('hash')->shouldReturnAnInstanceOf('Bobkingstone\PedlarCart\CartItem');
    }

    function it_should_return_true_if_item_is_in_cart(SessionStorage $storage)
    {
        $storage->has('hash')->willReturn(true);

        $this->has('hash')->shouldReturn(true);
    }

    function it_should_return_false_if_item_is_not_in_cart(SessionStorage $storage)
    {
        $storage->has('hash')->willReturn(false);

        $this->has('hash')->shouldReturn(false);
    }

    function it_should_return_a_total_price_for_all_items_in_cart(SessionStorage $storage, CartItem $item1, CartItem $item2)
    {
        $item1->total()->willReturn('400');

        $item2->total()->willReturn('400.10');

        $storage->getAll()->willReturn([$item1,$item2]);

        $this->totalValue()->shouldReturn(800.10);
    }

    function it_should_clear_cart_of_all_items(SessionStorage $storage, CartItem $item1, CartItem $item2)
    {
        $storage->getAll()->willReturn([$item1,$item2]);

        $this->all()->shouldHaveCount(2);

        $storage->flush()->shouldBeCalled();

        $this->clear();

        $storage->getAll()->willReturn([]);

        $this->all()->shouldHaveCount(0);
    }

    function it_should_return_a_total_number_of_unique_items_in_cart(SessionStorage $storage, CartItem $item1, CartItem $item2)
    {
        $storage->getAll()->willReturn([$item1,$item2]);

        $this->TotalUnqiueItems()->shouldReturn(2);
    }

    function it_should_throw_exception_if_a_required_param_is_missing()
    {
        $this->shouldThrow('Bobkingstone\PedlarCart\Exceptions\InvalidNumberOfValuesException')
            ->duringInsert($this->cartItemLessQty);
    }

    function it_should_calculate_total_cart_value_with_item_tax_rate(SessionStorage $storage, CartItem $item1, CartItem $item2)
    {
        $storage->getAll()->willReturn([$item1,$item2]);

        $item1->totalWithTax()->willReturn(2.20);

        $item2->totalWithTax()->willReturn(10.50);

        $this->totalWithTax()->shouldReturn(12.70);

    }

    function it_should_calculate_total_cart_value_with_defined_tax_rate(SessionStorage $storage, CartItem $item1, CartItem $item2)
    {
        $storage->getAll()->willReturn([$item1,$item2]);

        $item1->total(20)->willReturn(2.50);

        $item2->total(20)->willReturn(10.50);

        $this->totalWithTax(20)->shouldReturn(13.00);

    }
}
