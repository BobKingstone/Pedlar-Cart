<?php namespace Bobkingstone\PedlarCart;

use Bobkingstone\PedlarCart\Exceptions\InvalidItemKeyException;


/**
 * Class CartItem
 * @package Bobkingstone\PedlarCart\Cart
 */
class CartItem
{

    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param $item
     * @throws Exceptions\InvalidItemKeyException
     */
    function __construct($item)
    {
        if ( ! array_key_exists('qty', $item))
            throw new InvalidItemKeyException("Missing qty param");

        foreach ($item as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }

    /**
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if ( array_key_exists($key, $this->data))
        {
            return $this->data[$key];
        }

        return null;
    }


    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }


    /**
     * updates relevant data[key] value
     *
     * @param $key
     * @param null $value
     * @throws Exceptions\InvalidItemKeyException
     */
    public function update($key, $value = null)
    {
        if (array_key_exists($key,$this->data))
        {
            $this->data[$key] = $value;
        }
        else
        {
            throw new InvalidItemKeyException;
        }

    }

    /**
     * returns item total value
     *
     * @param null $taxRate
     * @return float|null
     */
    public function total($taxRate = null)
    {
        $price = $this->price;

        if ( $taxRate )
            return ( ($price/100) * $taxRate) + $price;

        return $this->qty * $price;
    }

    /**
     * return item value with tax
     *
     * @return float|null
     */
    public function totalWithTax()
    {
        $taxRate = $this->tax;

        return $this->total($taxRate);
    }
}
