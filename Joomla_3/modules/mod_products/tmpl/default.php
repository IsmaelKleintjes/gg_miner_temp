<?php 
// No direct access
defined('_JEXEC') or die; ?>
<table class="table table-striped table-hover">
                <th width="10%">
                        <?php echo JText::_('image') ;?>
                </th>
                <th width="10%">
                        <?php echo JText::_('name') ;?>
                </th>
                <th width="10%">
                        <?php echo JText::_('price') ;?>
                </th>
        <tbody>
                <?php if (!empty($products)) : ?>

                        <?php foreach ($products as $product_id) : 
                            foreach($product_id as $i => $row)
                            {
                            $link = 'index.php/product?id=' . $row->id;
                            $total_price += $row->price;
                            ?>
                                <tr>
                                        <td>
                                        <?php echo $row->image; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo $link; ?>" title="<?php echo JText::_('Product details'); ?>">
                                                    <?php echo $row->name; ?>
                                            </a>
                                        </td>
                                        <td>
                                        <?php echo $row->price; ?>
                                        </td>
                                </tr><?php
                            }
                            endforeach; ?>
                <?php endif; ?>
                                <tr>
                                    <td>
                                        <a href="http://localhost/edit4u/Joomla_3/gg_miner_temp/Joomla_3/index.php/order">View cart</a>
                                    </td>
                                    <td>
                                        <a href="http://localhost/edit4u/Joomla_3/gg_miner_temp/Joomla_3/index.php/order">Checkout</a>
                                    </td>
                                    <td><?=$total_price?></td>
                                </tr>
        </tbody>
</table>
<?php



