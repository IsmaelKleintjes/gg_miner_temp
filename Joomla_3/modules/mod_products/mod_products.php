<?php
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$shopping_cart = modProductsHelper::getShoppingCart();
$products = modProductsHelper::getProductsById($shopping_cart);
require JModuleHelper::getLayoutPath('mod_products');