<?php


if (!defined('_PS_VERSION_')) {
    exit;
}

class GiftSelection extends Module
{
    public function __construct()
    {
        $this->name = 'giftselection';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Nicolas Scandizzo';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Gift Selection Module');
        $this->description = $this->l('Allows management of promotional gifts.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
            $this->installDb() &&
            $this->registerHook('displayShoppingCart') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerTab();
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            $this->uninstallDb() &&
            $this->unregisterTab();
    }

    private function installDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "gift_products` (
            `id_gift` int(11) NOT NULL AUTO_INCREMENT,
            `gift_name` varchar(255) NOT NULL,
            `id_product` int(11) NOT NULL,
            `promotion_rule` varchar(255) NOT NULL,
            PRIMARY KEY (`id_gift`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "gift_products`;";
        return Db::getInstance()->execute($sql);
    }

    private function registerTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminGiftSelection';
        $tab->module = $this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentModulesSf');
        $tab->name = array();

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Gift Selection';
        }

        return $tab->save();
    }

    private function unregisterTab()
    {
        $tabId = (int)Tab::getIdFromClassName('AdminGiftSelection');
        if ($tabId) {
            $tab = new Tab($tabId);
            return $tab->delete();
        }

        return false;
    }

    public function hookDisplayShoppingCart($params)
    {
        $gifts = $this->getAvailableGifts();

        if (!empty($gifts)) {
            $this->context->smarty->assign([
                'gifts' => $gifts,
            ]);

            return $this->display(__FILE__, 'views/templates/hook/displayShoppingCart.tpl');
        }

        return '';
    }

    public function hookActionValidateOrder($params)
    {
        $cart = $params['cart'];
        $order = $params['order'];

        if (Tools::getIsset('selected_gift')) {
            $selectedGiftId = (int)Tools::getValue('selected_gift');

            // Descontar stock del producto seleccionado como regalo
            $giftProduct = new Product($selectedGiftId);
            if ($giftProduct->id && $this->isGiftStockAvailable($giftProduct->id)) {
                StockAvailable::updateQuantity($giftProduct->id, null, -1, $this->context->shop->id);

                // Guardar la elección del regalo
                $this->saveGiftSelection($order->id, $selectedGiftId);
            } else {
                // Gestionar el caso en que no haya suficiente stock
                $this->context->controller->errors[] = $this->l('Selected gift is out of stock.');
            }
        }
    }

    private function isGiftStockAvailable($idProduct)
    {
        $stockAvailable = StockAvailable::getQuantityAvailableByProduct($idProduct);
        return $stockAvailable > 0;
    }

    private function getAvailableGifts()
    {
        $cart = $this->context->cart;
        $total = $cart->getOrderTotal(true, Cart::BOTH);

        $gifts = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'gift_products');

        $availableGifts = [];

        foreach ($gifts as $gift) {
            if ($this->isGiftApplicable($gift, $cart, $total)) {
                $availableGifts[] = $gift;
            }
        }

        return $availableGifts;
    }

    private function isGiftApplicable($gift, $cart, $total)
    {
        $applicable = false;

        if (strpos($gift['promotion_rule'], 'total>=') !== false) {
            $minTotal = (int)str_replace('total>=', '', $gift['promotion_rule']);
            if ($total >= $minTotal) {
                $applicable = true;
            }
        }

        if (strpos($gift['promotion_rule'], 'specific_product=') !== false) {
            $productId = (int)str_replace('specific_product=', '', $gift['promotion_rule']);
            foreach ($cart->getProducts() as $product) {
                if ($product['id_product'] == $productId) {
                    $applicable = true;
                    break;
                }
            }
        }

        return $applicable;
    }

    private function saveGiftSelection($orderId, $selectedGift)
    {
        Db::getInstance()->insert('order_gift_selection', [
            'id_order' => (int)$orderId,
            'selected_gift' => pSQL($selectedGift),
        ]);
    }
}
