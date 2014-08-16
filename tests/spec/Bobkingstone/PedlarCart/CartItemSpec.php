<?php

namespace spec\BobKingstone\PedlarCart;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CartItemSpec extends ObjectBehavior
{


    /**
     * Used for testing items with tax defined
     *
     * @var array
     */
    private $itemWithTax = [
        'identifier' => '12',
        'id' => '1',
        'name' => 'foo',
        'tax' => 20,
        'qty' => 1,
        'price' => 3.00
    ];

    /**
     * used instead of let as tests for
     * error on creation are included
     *
     */
    function thisConstructorWithDefaultItem()
    {
        //default item
        $item = [
            'identifier' => '12',
            'id' => '1',
            'name' => 'foo',
            'qty' => 1,
            'price' => 3.00
        ];

        $this->beConstructedWith($item);
    }

    function it_is_initializable()
    {
        $this->thisConstructorWithDefaultItem();

        $this->shouldHaveType('Bobkingstone\PedlarCart\CartItem');
    }

    function it_throws_exception_if_qty_is_missing_from_constructor()
    {
        $item = [
            'id' => '1',
            'name' => 'foo',
            'price' => 3.00
        ];

        $this->shouldThrow('Bobkingstone\PedlarCart\Exceptions\InvalidItemKeyException')->during__construct($item);
    }

    function it_should_allow_individual_values_to_be_retrieved()
    {
        $this->thisConstructorWithDefaultItem();

        $this->identifier->shouldReturn('12');

        $this->qty->shouldReturn(1);
    }

    function it_should_update_item_qty()
    {
        $this->thisConstructorWithDefaultItem();

        $this->update('qty',2);

        $this->qty->shouldReturn(2);
    }

    function it_should_return_total_value_of_items()
    {
        $this->thisConstructorWithDefaultItem();

        $this->update('qty',3);

        $this->total()->shouldReturn(9.00);
    }

    function it_should_return_item_value_with_defined_tax_if_requested()
    {
        $this->thisConstructorWithDefaultItem();

        $this->total(10)->shouldReturn(3.30);
    }

    function it_throws_an_exception_if_update_key_is_not_found()
    {
        $this->thisConstructorWithDefaultItem();

        $this->shouldThrow('Bobkingstone\PedlarCart\Exceptions\InvalidItemKeyException')
            ->duringUpdate('foo', 'bar');
    }

    function it_should_calculate_item_total_with_preset_tax()
    {
        $this->beConstructedWith($this->itemWithTax);

        $this->totalWithTax()->shouldReturn(3.60);
    }

    function it_should_calculate_item_tax_with_defined_tax_rate()
    {
        $this->thisConstructorWithDefaultItem();

        $this->total(20)->shouldReturn(3.60);
    }
}
