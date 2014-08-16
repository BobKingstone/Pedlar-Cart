<?php namespace Bobkingstone\PedlarCart;


use Bobkingstone\PedlarCart\Exceptions\InvalidNumberOfValuesException;
use Bobkingstone\PedlarCart\Storage\StorageInterface;

/**
 * Class Cart
 * @package Bobkingstone\PedlarCart\Cart
 */
class Cart
{


    /**
     * @var Storage\StorageInterface
     */
    private $storage;

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
     * @param StorageInterface $storage
     */
    function __construct(StorageInterface $storage)
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
            $this->updateQty($data, $identifier);

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
     * calculates total value of all products in cart
     *
     * @return int
     */
    public function totalValue()
    {
        $total = 0;

        foreach ( $this->all() as $item )
        {
            $total += $item->total();
        }

        return $total;
    }

    /**
     * @param null $tax
     * @return float|int|mixed
     */
    public function totalWithTax($tax = null)
    {
        $total = 0;

        if ( ! $tax )
        {
            $total = $this->itemsWithTax($total);
        }
        else
        {
            $total = $this->totalWithDefinedTax($tax);
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
    public function totalUnqiueItems()
    {
        return count($this->all());
    }

    /**
     * @return int
     */
    public function itemCount()
    {
        $count = 0;

        foreach ( $this->all() as $item )
        {
            $count += $item->qty;
        }

        return $count;
    }

    public function remove ($ident)
    {
        $this->storage->remove($ident);
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

    /**
     * @param $data
     * @param $identifier
     */
    private function updateQty($data, $identifier)
    {
        $existing = $this->find($identifier);
        $existing->qty += $data['qty'];
    }

    /**
     * @param $total
     * @return mixed
     */
    private function itemsWithTax($total)
    {
        foreach ($this->all() as $item) {
            $total += $item->totalWithTax();
        }
        return $total;
    }

    /**
     * @param $tax
     * @return float|int
     */
    private function totalWithDefinedTax($tax)
    {
        $total = $this->totalValue();
        $total += (($total / 100) * $tax);
        return $total;
    }
}