<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class ModProductsHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getProductsInStock($params)
    {
        $model = JModelLegacy::getInstance('Products', 'EngineModel');
        $products = $model->getProductsInStock();   
        
        return $products;
    }
    
    public static function getProductsById($shopping_cart)
    {   
        $products = array();
        foreach ($shopping_cart as $product_id => $quantity)
        {
        $model = JModelLegacy::getInstance('Products', 'EngineModel');
        $products[$product_id] = $model->getProductById($product_id);   
        }
        
        return $products;
    }
    
     public static function getShoppingCart()
    {   
        $session = JFactory::getSession();
        $shopping_cart = $session->get('shopping_cart');
        
        return $shopping_cart;
    }
    
    
}