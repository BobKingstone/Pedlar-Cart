<?php namespace Bobkingstone\PedlarCart\Storage;


interface StorageInterface {

    /**
     * @param $item
     */
    function insert($item);

    /**
     * @return array
     */
    function getAll();

    /**
     * @param $ident
     * @return bool
     */
    function has($ident);

    /**
     * @param $ident
     * @return mixed
     */
    function find($ident);

    /**
     *Clear cart
     */
    function flush();

    /**
     * remove cart item
     *
     * @param $ident
     * @return mixed
     */
    function remove ($ident);
} 