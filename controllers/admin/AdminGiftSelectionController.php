<?php


class AdminGiftSelectionController extends ModuleAdminController
{
    public function __construct()
    {
        $this->table = 'gift_products';
        $this->className = 'GiftProduct';
        $this->lang = false;
        $this->bootstrap = true;

        parent::__construct();

        $this->fields_list = array(
            'id_gift' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 25
            ),
            'gift_name' => array(
                'title' => $this->l('Gift Name'),
                'width' => 'auto'
            ),
            'id_product' => array(
                'title' => $this->l('Product ID'),
                'width' => 'auto'
            ),
            'promotion_rule' => array(
                'title' => $this->l('Promotion Rule'),
                'width' => 'auto'
            )
        );

        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Gift Product'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Gift Name'),
                    'name' => 'gift_name',
                    'required' => true
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Product'),
                    'name' => 'id_product',
                    'options' => array(
                        'query' => $this->getProducts(),
                        'id' => 'id_product',
                        'name' => 'name'
                    ),
                    'required' => true,
                    'desc' => $this->l('Select the product that will be used as a gift.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Promotion Rule'),
                    'name' => 'promotion_rule',
                    'required' => true,
                    'desc' => $this->l('Define the promotion rule, e.g., total>=100 or specific_product=200')
                )
            ),
            'submit' => array(
                'title' => $this->l('Save')
            )
        );

        return parent::renderForm();
    }

    private function getProducts()
    {
        $products = Product::getProducts($this->context->language->id, 0, 0, 'name', 'ASC');
        return $products;
    }
}
