<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.4.4
 * @author	hikashop.com
 * @copyright	(C) 2010-2021 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
$mainDivName = $this->params->get('main_div_name', '');
$height = (int)$this->image->main_thumbnail_y;
$width = (int)$this->image->main_thumbnail_x;


$hidden_html = '';
$part_html = '';
$img_nb = count($this->row->images);
if ($img_nb > 1) {
	$row = @$this->row->images;

	$img_array = array();
	foreach($row as $k => $img) {
		if($k > 0) {
			$hidden_img = $this->image->getThumbnail(
				$img->file_path,
				array('width' => $width, 'height' => $height),
				array('default' => true,'forcesize'=>$this->config->get('image_force_size',true),'scale'=>$this->config->get('image_scale_mode','inside'))
			);

			if($hidden_img->success) {
				$part_html = ''.
				'<img class="hikashop_product_listing_image" '.
				'title="'.$this->escape($img->file_description).'" '.
				'alt="'.$this->escape($img->file_name).'" src="'.$hidden_img->url.'"/>';

				if($this->config->get('add_webp_images', 1) && function_exists('imagewebp') && !empty($hidden_img->webpurl)) {
					$part_html = ''.
					'<picture>'.
						'<source srcset="'.$hidden_img->webpurl.'" type="image/webp">'.
						'<source srcset="'.$hidden_img->url.'" type="image/'.$hidden_img->ext.'">'.
					$part_html.
					'</picture>';
				}
				$hidden_html .= '<div class="hikashop_img_hidden_'.$k.'">'.
					$part_html.
				'</div>';
			}
		}
	}
}

$link = hikashop_contentLink('product&task=show&cid=' . (int)$this->row->product_id . '&name=' . $this->row->alias . $this->itemid . $this->category_pathway, $this->row);
$haveLink = (int)$this->params->get('link_to_product_page', 1);

if(!empty($this->row->extraData->top)) { echo implode("\r\n",$this->row->extraData->top); }

?>
<div class="hikashop_listing_img_title" id="div_<?php echo $mainDivName.'_'.$this->row->product_id; ?>">
<?php
if($this->config->get('thumbnail', 1)) {
	$extra_class = '';
	if (count($this->row->images) > 1) {
		$extra_class = 'hikashop_hover_img';
	}
?>
	<!-- PRODUCT IMG -->
	<div class="hikashop_product_image">
		<div class="hikashop_product_image_subdiv <?php echo $extra_class; ?>">
<?php if($haveLink) { ?>
			<a href="<?php echo $link;?>" title="<?php echo $this->escape($this->row->product_name); ?>" style="display:inline-block; height:<?php echo $height; ?>px;">
<?php } ?>
<?php
	$img = $this->image->getThumbnail(
		@$this->row->file_path,
		array('width' => $width, 'height' => $height),
		array('default' => true,'forcesize'=>$this->config->get('image_force_size',true),'scale'=>$this->config->get('image_scale_mode','inside'))
	);
	if($img->success) {
		$html = '<img class="hikashop_product_listing_image" title="'.$this->escape(@$this->row->file_description).'" '.
		'alt="'.$this->escape(@$this->row->file_name).'" src="'.$img->url.'"/>';
		if($this->config->get('add_webp_images', 1) && function_exists('imagewebp') && !empty($img->webpurl)) {
			$html = ''.
			'<picture>
				<source srcset="'.$img->webpurl.'" type="image/webp">
				<source srcset="'.$img->url.'" type="image/'.$img->ext.'">
				'.$html;
			'</picture>'.
			'';
		}

		if(!isset($k)) $k = 1;

		if ($hidden_html != '') {
			echo '<div class="hikashop_img_curr_final">'.
				$html.
			'</div>'.
			$hidden_html;
		}
		echo '<div class="hikashop_img_curr" style="top: -'. ($k + 1) * $height .'px;">'.
			$html.
		'</div>';


?>		<meta itemprop="image" content="<?php echo $img->url; ?>"/>
<?php
	}
	if($this->params->get('display_badges', 1)) {
		$this->classbadge->placeBadges($this->image, $this->row->badges, array('vertical' => -10, 'horizontal' => 0, 'thumbnail' => $img));
	}
?>
<?php if($haveLink) { ?>
			</a>
<?php } ?>
		</div>
	</div>
	<!-- EO PRODUCT IMG -->
<?php } ?>

	<!-- PRODUCT PRICE -->
<?php
	if($this->params->get('show_price','-1')=='-1'){
		$config =& hikashop_config();
		$this->params->set('show_price',$config->get('show_price'));
	}
	if($this->params->get('show_price')){
		$this->setLayout('listing_price');
		echo $this->loadTemplate();
	}
?>
	<!-- EO PRODUCT PRICE -->

	<!-- PRODUCT NAME -->
	<span class="hikashop_product_name">
<?php if($haveLink) { ?>
		<a href="<?php echo $link;?>">
<?php } ?>
			<?php echo $this->row->product_name; ?>
<?php if($haveLink) { ?>
		</a>
<?php } ?>
	</span>
	<meta itemprop="name" content="<?php echo $this->escape(strip_tags($this->row->product_name)); ?>">
	<!-- EO PRODUCT NAME -->

	<!-- PRODUCT CODE -->
		<span class='hikashop_product_code_list'>
<?php if ($this->config->get('show_code')) { ?>
<?php if($haveLink) { ?>
			<a href="<?php echo $link;?>">
<?php } ?>
				<?php echo $this->row->product_code; ?>
<?php if($haveLink) { ?>
			</a>
<?php } ?>
<?php } ?>
		</span>
	<!-- EO PRODUCT CODE -->

	<!-- PRODUCT CUSTOM FIELDS -->
<?php
if(!empty($this->productFields)) {
	foreach($this->productFields as $fieldName => $oneExtraField) {
		if(empty($this->row->$fieldName) && (!isset($this->row->$fieldName) || $this->row->$fieldName !== '0'))
			continue;

		if(!empty($oneExtraField->field_products)) {
			$field_products = is_string($oneExtraField->field_products) ? explode(',', trim($oneExtraField->field_products, ',')) : $oneExtraField->field_products;
			if(!in_array($this->row->product_id, $field_products))
				continue;
		}
?>
	<dl class="hikashop_product_custom_<?php echo $oneExtraField->field_namekey;?>_line">
		<dt class="hikashop_product_custom_name">
			<?php echo $this->fieldsClass->getFieldName($oneExtraField);?>
		</dt>
		<dd class="hikashop_product_custom_value">
			<?php echo $this->fieldsClass->show($oneExtraField,$this->row->$fieldName); ?>
		</dd>
	</dl>
<?php
	}
}
?>
	<!-- EO PRODUCT CUSTOM FIELDS -->

<?php if(!empty($this->row->extraData->afterProductName)) { echo implode("\r\n",$this->row->extraData->afterProductName); } ?>

	<!-- PRODUCT VOTE -->
<?php

if($this->params->get('show_vote')) {
	$this->setLayout('listing_vote');
	echo $this->loadTemplate();
}
?>
	<!-- EO PRODUCT VOTE -->

	<!-- ADD TO CART BUTTON AREA -->
<?php
if($this->params->get('add_to_cart') || $this->params->get('add_to_wishlist')) {
	$this->setLayout('add_to_cart_listing');
	echo $this->loadTemplate();
}
?>
	<!-- EO ADD TO CART BUTTON AREA -->

	<!-- COMPARISON AREA -->
<?php
if(hikaInput::get()->getVar('hikashop_front_end_main', 0) && hikaInput::get()->getVar('task') == 'listing' && $this->params->get('show_compare')) {
	$css_button = $this->config->get('css_button', 'hikabtn');
	$css_button_compare = $this->config->get('css_button_compare', 'hikabtn-compare');
?>
	<br/>
<?php
	if((int)$this->params->get('show_compare') == 1) {
?>
	<a class="<?php echo $css_button . ' ' . $css_button_compare; ?>" href="<?php echo $link; ?>" onclick="if(window.hikashop.addToCompare) { return window.hikashop.addToCompare(this); }" data-addToCompare="<?php echo $this->row->product_id; ?>" data-product-name="<?php echo $this->escape($this->row->product_name); ?>" data-addTo-class="hika-compare"><span><?php
		echo JText::_('ADD_TO_COMPARE_LIST');
	?></span></a>
<?php
	} else {
?>
	<label><input type="checkbox" class="hikashop_compare_checkbox" onchange="if(window.hikashop.addToCompare) { return window.hikashop.addToCompare(this); }" data-addToCompare="<?php echo $this->row->product_id; ?>" data-product-name="<?php echo $this->escape($this->row->product_name); ?>" data-addTo-class="hika-compare"><?php echo JText::_('ADD_TO_COMPARE_LIST'); ?></label>
<?php
	}
}
?>
	<!-- EO COMPARISON AREA -->

	<!-- CONTACT US AREA -->
<?php
	$contact = (int)$this->config->get('product_contact', 0);
	if(hikashop_level(1) && $this->params->get('product_contact_button', 0) && ($contact == 2 || ($contact == 1 && !empty($this->row->product_contact)))) {
		$css_button = $this->config->get('css_button', 'hikabtn');
?>
	<a href="<?php echo hikashop_completeLink('product&task=contact&cid=' . (int)$this->row->product_id . $this->itemid); ?>" class="<?php echo $css_button; ?>"><?php
		echo JText::_('CONTACT_US_FOR_INFO');
	?></a>
<?php
	}
?>

	<!-- EO CONTACT US AREA -->

	<!-- PRODUCT DETAILS BUTTON AREA -->
<?php
	$details_button = (int)$this->params->get('details_button', 0);
	if($details_button) {
		$css_button = $this->config->get('css_button', 'hikabtn');
?>
	<a href="<?php echo $link; ?>" class="<?php echo $css_button; ?>"><?php
		echo JText::_('PRODUCT_DETAILS');
	?></a>
<?php
	}
?>

	<!-- EO PRODUCT DETAILS BUTTON AREA -->

	<meta itemprop="url" content="<?php echo $link; ?>">
</div>
<?php

if(!empty($this->row->extraData->bottom)) { echo implode("\r\n",$this->row->extraData->bottom); }

if(isset($this->rows[0]) && $this->rows[0]->product_id == $this->row->product_id) {
	$height_list_cont = $height + 40;

	$css = '';
	if($height > 0){
		$css .= '
#'.$mainDivName.' .hikashop_product_image { height: '.$height.'px; }'. 
'#'.$mainDivName.' .hikashop_product_image_subdiv.hikashop_hover_img { height: '.$height.'px;  }'.
'#'.$mainDivName.' .display_list .hikashop_container .hikashop_subcontainer {  height: '. $height_list_cont .'px; }'.
'#'.$mainDivName.' .hikashop_product_image_subdiv.hikashop_hover_img div.hikashop_img_hidden_1 { top: -'. $height.'px;}'. 
'#'.$mainDivName.' .hikashop_product_image_subdiv.hikashop_hover_img div.hikashop_img_hidden_2 { top: -'. 2 * $height.'px; }'. 
'#'.$mainDivName.' .hikashop_product_image_subdiv.hikashop_hover_img div.hikashop_img_hidden_3 { top: -'. 3 * $height.'px; }'. 
'#'.$mainDivName.' .hikashop_product_image_subdiv.hikashop_hover_img div.hikashop_img_hidden_4 { top: -'. 4 * $height.'px; }'. 
'#'.$mainDivName.' .hikashop_product_image_subdiv.hikashop_hover_img div.hikashop_img_hidden_5 { top: -'. 5 * $height.'px; }';
	}
	if($width > 0){
		$css .= ''.
'#'.$mainDivName.' .hikashop_product_image_subdiv .hikashop_img_curr { width:'.$width.'px; }';
	}
	$doc = JFactory::getDocument();
	$doc->addStyleDeclaration($css);
}
?>
