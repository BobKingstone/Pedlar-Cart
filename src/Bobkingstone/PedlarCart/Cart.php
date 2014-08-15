<?php

namespace Bobkingstone\PedlarCart;
use Bobkingstone\PedlarCart\Exceptions\InvalidNumberOfValuesException;
use Bobkingstone\PedlarCart\Storage\SessionStorage;

/**
 * Class Cart
 * @package Bobkingstone\PedlarCart\Cart
 */
class Cart
{

    /**
     * @var
     */
    private $id;

    /**
     * @var Storage\SessionStorage
     */
    private $storage;

    /**
     * Cart Items
     *
     * @var array
     */
    private $cart = array();

    /**
     * Array of params to remove from item identifier generator
     *
     * @var array
     */
    private  $removeParams = array(
        'qty'
    );

    /**
     * @var array
     */
    private $requiredParams = array (
        'id',
        'qty',
        'price'
    );

    /**
     * @param SessionStorage $storage
     */
    function __construct(SessionStorage $storage)
    {
        $this->storage = $storage;
    }


    /**
     * @param $data
     * @throws InvalidNumberOfValuesException
     * @return string
     */
    public function insert($data)
    {
        $this->checkParams($data);

        $identifier = $this->createItemIdentifier($data);

        if ( $this->has($identifier) )
        {
            $existing = $this->find($identifier);
            $existing->qty += $data['qty'];

            return $identifier;
        }

        $item = $this->createItem($data, $identifier);

        $this->storage->insert($item);

        return $identifier;
    }


    /**
     * @return array
     */
    public function all()
    {
        return $this->storage->getAll();
    }

    /**
     * @param $data
     * @return string
     */
    private function createItemIdentifier($data)
    {
        //unset values that may change during use
        foreach ( $this->removeParams as $key )
        {
            unset($data[$key]);
        }

        $identifier = md5(serialize($data));

        return $identifier;
    }


    /**
     * @param $identifier
     * @return mixed
     */
    public function find($identifier)
    {
        //cartitem or null
        return $this->storage->find($identifier);
    }

    /**
     * @param $identifier
     * @return bool
     */
    public function has($identifier)
    {
        return $this->storage->has($identifier);
    }

    /**
     * @return int
     */
    public function total()
    {
        $total = 0;

        foreach ( $this->all() as $item )
        {
            $total += $item->total();
        }

        return $total;
    }

    /**
     * clear shopping cart
     */
    public function clear()
    {
        $this->storage->flush();
    }

    /**
     * @param $data
     * @param $identifier
     * @return CartItem
     */
    private function createItem($data, $identifier)
    {
        $data[ 'identifier' ] = $identifier;

        $item = new CartItem($data);

        return $item;
    }

    /**
     * @return int
     */
    public function TotalUnqiueItems()
    {
        return count($this->cart);
    }

    /**
     * @return int
     */
    public function totalItems()
    {
        $total = 0;

        foreach ( $this->all() as $item )
        {
            $total += $item->qty;
        }

        return $total;
    }

    /**
     * @param $data
     * @throws Exceptions\InvalidNumberOfValuesException
     */
    private function checkParams($data)
    {
        foreach ( $this->requiredParams as $param ) {
            if ( ! array_key_exists($param, $data) )
                throw new InvalidNumberOfValuesException("{ $param } param is missing");
        }
    }
}

