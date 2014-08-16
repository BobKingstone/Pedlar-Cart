<?php namespace Bobkingstone\PedlarCart\Storage;

use Session;

/**
 * Class SessionStorage
 * @package Bobkingstone\PedlarCart\Storage
 */
class SessionStorage implements StorageInterface{

    protected $cart;

    /**
     * @param $item
     */
    function insert($item)
    {
        Session::push('cart',$item);
    }

    /**
     * @return array
     */
    function getAll()
    {
        $cart = $this->getCart();

        return $cart;
    }

    /**
     * @param $ident
     * @return bool
     */
    function has($ident)
    {
        $cart = $this->getCart();

        foreach ($cart as $item)
        {
            if ( $item->identifier == $ident)
                return true;
        }

        return false;
    }

    /**
     * @param $ident
     * @return null
     */
    function find($ident)
    {
        $cart = $this->getCart();

        foreach ($cart as $item)
        {
            if ( $item->identifier == $ident)
                return $item;
        }

        return null;
    }

    /**
     *
     */
    function flush()
    {
        //Session flush is not used as this will flush all session data
        Session::forget('cart');
    }

    /**
     * @return array
     */
    private function getCart()
    {
        if (Session::has('cart'))
        {
            $cart = Session::get('cart');

            return $cart;
        }

        return $cart = array();

    }

    /**
     * remove cart item
     *
     * @param $ident
     * @return mixed
     */
    function remove($ident)
    {
       $cart = $this->getCart();

        $this->flush();

        foreach ($cart as $position => $item) {
            if ($ident === $item->identifier) {

                unset($cart[$position]);
            }
        }


        Session::put('cart',$cart);
    }
}