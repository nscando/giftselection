<?php

class GiftProduct extends ObjectModel
{
    public $id_gift;
    public $gift_name;
    public $id_product;
    public $promotion_rule;

    public static $definition = array(
        'table' => 'gift_products',
        'primary' => 'id_gift',
        'fields' => array(
            'gift_name' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'id_product' => array('type' => self::TYPE_INT, 'required' => true),
            'promotion_rule' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
        ),
    );
}
