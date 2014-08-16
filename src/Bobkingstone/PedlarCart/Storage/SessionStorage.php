<?php namespace Bobkingstone\PedlarCart\Storage;

use Session;

/**
 * Class SessionStorage
 * @package Bobkingstone\PedlarCart\Storage
 */
class SessionStorage implements StorageInterface{

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

    private function getCart()
    {
        if (Session::has('cart'))
        {
            $cart = Session::get('cart');

            return $cart;
        }

        return $cart = array();

    }

} 