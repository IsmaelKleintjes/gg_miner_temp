<?php defined('_JEXEC') or die;

$app = JFactory::getApplication();
$itemId = $app->input->get('Itemid', 0,'int');

foreach ($list as $i => &$item)
{
	$class = 'dropdown-item item-' . $item->id;
	if($itemId == $item->id)
    {
        $class .= ' active';
    }

	if ($item->type === 'separator')
	{
		echo '<div class="dropdown-divider"></div>';
		continue;
	}

	$link = '';
	$link .= $item->link;
	$link .= '&Itemid=' . $item->id;

	echo '<a class="' . $class . '" href="' . $link . '">';

        if(!empty($menuIconCss = $item->params->get('menu_image_css'))) {
            echo '<i class="'.$menuIconCss.'"></i> &nbsp ';
        }

	    echo $item->title;
    echo '</a>';

	$linktype = $item->title;
}