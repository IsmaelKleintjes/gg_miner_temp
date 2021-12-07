<?php defined('_JEXEC') or die;

/**
 * Class JHtmlOverview
 *
 * @version     1.0
 * @since       21-11-2016
 */
class JHtmlOverview extends JViewLegacy
{
    protected $config;
    protected $component;
    protected $crud;
    protected $cruds;
    protected $state;
    protected $user;
    protected $userId;
    protected $listOrder;
    protected $listDirn;
    protected $archived;
    protected $trashed;
    protected $saveOrder;
    protected $sortFields;
    protected $mobileHead;

    /**
     * JHtmlOverview constructor.
     *
     * @param array $view
     * @param array $attr
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function __construct( $view, $attr=array() )
    {
        ini_set('pcre.backtrack_limit', 9999999);

        $this->model		= $view->getModel();
        $this->config		= $this->model->config;

        $this->component	= (!empty($this->config['component']) ? $this->config['component'] : 'com_engine');
        $this->cruds		= $this->config['cruds'];
        $this->crud			= $this->config['crud'];
        $this->fields		= $this->config['fields'];
        $this->tabs 		= $this->config['tabs'];
        $this->sortFields	= EngineHelper::getSortFields( $this->config );

        $this->sidebar 		= $view->sidebar;
        $this->items 		= $view->get('Items');
        $this->total        = $view->get('Total');

        $this->tabTotals    = false;
        if($this->config['tabTotals']) {
            $this->tabTotals = $view->get('TabTotals');
        }

        $this->pagination 	= $view->get('Pagination');
        $this->state 		= $view->get('State');

        $this->user			= JFactory::getUser();
        $this->userId		= $this->user->get('id');

        $this->listOrder	= $this->escape($this->state->get('list.ordering'));
        $this->listDirn		= $this->escape($this->state->get('list.direction'));

        $this->archived		= $this->state->get('filter.state') == 2 ? true : false;
        $this->trashed		= $this->state->get('filter.state') == -2 ? true : false;
        $this->filterTab    = $this->escape($this->state->get('filter.tab'));

        $this->filters      = $view->filters;

        $this->buttons      = $view->buttons;
        $this->noResultError = $view->noResultError;

        $this->dropdowns    = $view->dropdowns;
        $this->customFilters= $view->customFilters;

        $this->addDateRangeFilter    = $view->addDateRangeFilter;
        $this->addDatePicker = $view->addDatePicker;

        $this->mobileHead   = $this->config['mobile_head'];
        $this->pageTitle    = $view->pageTitle;
        $this->pageSubtitle = $view->pageSubtitle;
    }

    public function show()
    {
        JFactory::getDocument()->addStyleSheet(JUri::base() . 'media/jui/css/icomoon.css');

        $html = '';

        /*
         * HEAD
         */
        $html .= $this->showHeader();
        $html .= "<form action='" . JUri::base() . 'index.php?option=' . $this->component . '&task=system.filter&cruds=' . $this->cruds . $layout . "' data-type=\"$this->crud\" method='post' name='adminForm' id='adminForm'>";

        /*
         * FILTERS
         */
        $html .= $this->getFilterBar();

        /*
         * THIS IS WHERE THE MAGIC HAPPENS
         */
        if(count($this->fields))
        {
            $html .= '<div class="card">';
            $html .= $this->showFields();
            $html .= '</div>';

        }
        else if (count($this->tabs))
        {
            $html .= $this->tabScript();
            $html .= $this->showTabs();
        }

        /*
         * BOTTOM Joomla magic
         */
        $html .= '<div class="pagination-bottom mt-3">';
            $html .= '<div class="row">';
            $html .= '<div class="col-xs-12 col-sm-6">';
            $html .= '<div class="counter pull-left" style="display: flex">';
            $html .= '<label class="counter-item" style="padding-right: 5px; padding-top: 4px;">Toon</label>';
            $html .= '<div class="counter-item" style="font-size: 12px;">';

            $html .= str_replace("inputbox input-mini", "custom-select custom-select-sm form-control form-control-sm", $this->pagination->getLimitBox());

            $html .= '</div>';
            $html .= '<p class="counter-item" style="font-size: 12px; padding-left: 5px; padding-top: 4px;">van ' . $this->total . '</p>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div class="col-xs-12 col-sm-6">';
                $html .= $this->pagination->getPagesLinks();
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';

            $html .= '<div>';
            //$html .= '<input type="hidden" name="option" value="' . $this->component. '" />';
            //$html .= '<input type="hidden" name="view" value="' . $this->cruds. '" />';
            $html .= '<input type="hidden" name="Itemid" value="' . Input4U::getInt('Itemid', 'REQUEST') . '" />';
            $html .= '<input type="hidden" name="task" value="system.filter" id="task" />';
            $html .= '<input type="hidden" name="cruds" value="' . $this->cruds . '" id="task" />';
            #$html .= '<input type="hidden" name="cid" value=""/>';
            $html .= '<input type="hidden" name="boxchecked" value="0" />';
            $html .= '<input type="hidden" name="filter_order" value="' . $this->listOrder . '" />';
            $html .= '<input type="hidden" name="filter_order_Dir" value="' . $this->listDirn . '" />';
            $html .= '<input type="hidden" name="filter_tab" value="' . $this->filterTab . '" />';
            if(!empty($hiddenFields)) $html .= $this->addHiddenFields($hiddenFields);
        $html .= '</div>';

        $html .= '<input type="submit" style="display:none;" />';

        $html .= '</form>';
        $html .= '<script type="text/javascript" src="' . JUri::base() . 'media/system/js/core.js"></script>';

        return $html;
    }


    public function showHeader($hiddenFields = null)
    {
        $html = '';

        $html .= '<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center container-p-x bg-navbar-theme d-block d-lg-none">';
        $html .= '<div class="container flex-lg-wrap">';

            $html .= '<div class="layout-sidenav-toggle">';
                $html .= '<a class="nav-item nav-link px-0 mr-lg-4" href="javascript:void(0)">';
                    $html .= '<i class="ion ion-md-menu text-large align-middle"></i>';
                $html .= '</a>';
            $html .= '</div>';

            $html .= '<div class="navbar-text navbar-page-title pt-lg-2 pb-lg-1 pl-lg-2">';
                $html .= ''. $this->pageTitle .'';
            $html .= '</div>';

            if($this->buttons['add']) {
                $addUrl = 'index.php?option=com_engine&task=' . $this->crud . '.add';
                $html .= '<a class="nav-item nav-link px-2 mr-lg-4" href="' . $addUrl . '">';
                    $html .= '<i class="ion ion-md-add text-large align-middle"></i>';
                $html .= '</a>';
            }

        $html .= '</div>';
        $html .= '</nav>';

        $html .= '<h4 class="page-title page-overview-title d-flex justify-content-between align-items-center w-100 font-weight-bold py-0">';

        if(!empty($this->pageTitle)) {
            $html .= '<div>';
            $html .= $this->pageTitle;
            $html .= '</div>';
        }

        if(!empty($this->pageSubtitle)) {
            /*
             * TODO moet nog een oplossing voor komen. Nu wordt alles op 1 regel uitgelijnd, terwijl de subtitel eronder moet komen.
            $html .= '<small class="font-weight-normal">' . $this->pageSubtitle . '</small>';
            */
        }

        if(count($this->buttons)){

            $html .= '<div class="btn-toolbar">';

            if($this->buttons['overview'])
            {
                $overviewUrl = Route4U::getUrl('index.php?option=com_engine&view=' . $this->cruds);
                $html .= '<button type="button" class="btn btn-primary" onclick="window.location.href=\'' . $overviewUrl . '\'">';
                $html .= '<span class="ion ion-md-list"></span>&nbsp;' . JText::_('COM_ENGINE_OVERVIEW');
                $html .= '</button>';
            }

            if($this->buttons['archive'])
            {
                $archiveUrl = 'index.php?option=com_engine&view=' . $this->cruds . '&layout=archive';
                $html .= '<button type="button" class="btn btn-warning" onclick="window.location.href=\'' . $archiveUrl . '\'">';
                $html .= '<span class="ion ion-md-archive"></span>&nbsp;' . JText::_('COM_ENGINE_ARCHIVE');
                $html .= '</button>';
            }

            if($this->buttons['export'])
            {
                $exportUrl = 'index.php?option=com_engine&task=' . $this->cruds . '.export';
                $html .= '<button type="button" class="btn btn-default" onclick="window.location.href=\'' . $exportUrl . '\'">';
                $html .= '<span class="ion ion-md-download"></span>&nbsp;' . JText::_('COM_ENGINE_EXPORT');
                $html .= '</button>';
            }

            if($this->buttons['add'])
            {
                $addUrl = 'index.php?option=com_engine&task=' . $this->crud . '.add';
                $html .= '<button type="button" class="btn btn-primary" onclick="window.location.href=\'' . $addUrl . '\'">';
                $html .= '<span class="ion ion-md-add"></span>&nbsp;' . JText::_('COM_ENGINE_ADD');
                $html .= '</button>';
            }

            if($this->buttons['mail'])
            {
                $html .= '<button type="button" class="btn btn-primary" onclick="if (document.adminForm.boxchecked.value == 0) { alert(\'Maak eerst een selectie uit de lijst.\'); } else {Joomla.submitbutton(\'workorders.showWorkOrders\')}">';
                $html .= '<span class="ion ion-md-mail"></span>&nbsp;' . JText::_('COM_ENGINE_SEND_MAIL');
                $html .= '</button>';
            }

            if(isset($this->buttons['custom']) && is_array($this->buttons['custom']) && count($this->buttons['custom'])){
                foreach($this->buttons['custom'] as $customButton){
                    $html .= '<a href="' . $customButton['url'] . '" class="' . $customButton['class'] . ' ml-1">' . $customButton['label'] . '</a>';
                }
            }

            $html .= '</div>';
        }

        $html .= '</h4>';

        return $html;
    }

    public function showTabs()
    {
        $html = "";

        if(count($this->tabs))
        {
            $activeTabId = $this->state->get('filter.tab');
            if(isset($this->tabs[$activeTabId])) {
                $fields = $this->tabs[$activeTabId]['fields'];
            }

            /*
                TABS
            */
            $html .= "<div class='nav-tabs-top'>";
                $html .= '<ul class="nav nav-tabs">';
                foreach($this->tabs as $tabId => $tab)
                {
                    //$tabUrl = Route4U::getUrl('index.php?option=' . $this->component . '&view=' . $this->cruds . '&tab=' . $tabId);
                    $tabOnclick = "openTab('" . $tabId . "');";
                    $html .= '<li class="nav-item">';
                        //$html .= '<a class="nav-link '.($tabId == $activeTabId ? 'active' : '').'" href="'.$tabUrl.'">';
                        $html .= '<a class="nav-link '.($tabId == $activeTabId ? 'active' : '').'" href="#" onclick="'.$tabOnclick.'">';
                            $html .= $tab['title'];
                            if(is_array($this->tabTotals)) {
                                $tabTotal = ($this->tabTotals[$tabId]->total > 0) ? $this->tabTotals[$tabId]->total : 0;
                                $html .= ' <span class="badge badge-'.$tab['color'].'">' . $tabTotal . '</span>';
                            }
                        $html .= '</a>';
                    $html .= '</li>';
                }
                $html .= '</ul>';

                $this->fields = $fields;
                $html .= '<div class="tab-content">';
                    $html .= '<div class="tab-pane show active" id="'.$activeTabId.'">';
                        $html .= $this->showFields();
                    $html .= '</div>';
                $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    public function showFields()
    {
        $html = "";

        $layout = '';
        if(Input4U::get('layout', 'GET')) {
            $layout = '&layout=' . Input4U::get('layout', 'GET');
        }

        $html .= '<div class="table-responsive">';

        $html .= '<table class="table card-table table-hover table-striped">';
        $html .= '<thead>';
        $html .= '<tr>';
        foreach($this->fields as $field)
        {
            $field['sort'] = $field['sort'] === false ? false : true;

            $html .= '<th class="' . $field['head_class'] . '" width="' . $field['head_width'] . '">';
            $html .= $this->showTableHead( $field );
            $html .= '</th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody class="padding-tables" id="myTable">';

        if(!empty($this->items)) {
            foreach ($this->items as $i => $item)
            {
                $item->max_ordering = 0;
                $html .= '<tr>';
                $html .= $this->showTableBody( $item, $i );
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="' . count($this->fields) . '" class="pull-left">' . (!empty($this->noResultError) ? $this->noResultError : 'Geen resultaten gevonden.') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';

        $html .= '</div>';

        return $html;
    }

    /**
     * Description comes later
     *
     * @param $field
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function showTableHead( $field )
    {
        $html = "";
        $columnKey = end(explode(".",$field['column']));
        switch($columnKey)
        {
            case 'ordering':
                $this->saveOrder = $this->listOrder==$field['column'];
                if ($this->saveOrder)
                {
                    $this->saveOrderingUrl = 'index.php?option=' . $this->component . '&task=' . $this->cruds . '.saveOrderAjax&tmpl=component';
                    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($this->listDirn), $this->saveOrderingUrl);
                }

                //$html .= '<th>';
                if($field['sort']) {
                    $html .= JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', $field['column'], $this->listDirn, $this->listOrder, null, 'asc', $field['label']);
                } else {
                    $html .= '<i class="icon-menu-2"></i>';
                }
                //$html .= '</th>';
                break;
            case 'check':
                $html .= '<div class="custom-control custom-checkbox">';
                $html .= '<input type="checkbox" class="custom-control-input" id="checkall" name="checkall-toggle" value="" title="' . $field['label'] . '" onclick="Joomla.checkAll(this)" />';
                $html .= '<label class="custom-control-label" for="checkall"></label>';
                $html .= '</div>';
                break;
            case 'pubfeat':
            case 'featured':
            case 'published':
                //$html .= '<th width="1%" style="min-width:55px" class="nowrap center">';
                if($field['sort']) {
                    $html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
                } else {
                    $html .= $field['label'];
                }
                //$html .= '</th>';
                break;
            case 'created':
                //$html .= '<th width="10%" class="nowrap hidden-phone">';
                if($field['sort']) {
                    $html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
                } else {
                    $html .= $field['label'];
                }
                //$html .= '</th>';
                break;
            case 'id':
                //$html .= '<th>';
                if($field['sort']) {
                    $html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
                } else {
                    $html .= $field['label'];
                }
                //$html .= '</th>';
                break;
            default:
                //$html .= '<th>';
                if($field['sort']) {
                    $html .= JHtml::_('grid.sort', $field['label'], $field['column'], $this->listDirn, $this->listOrder);
                } else {
                    $html .= $field['label'];
                }
                //$html .= '</th>';
                break;
        }

        return $html;
    }

    /**
     * Description comes later
     *
     * @param $item
     * @param $i
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function showTableBody($item, $i )
    {

        //$ordering   = ($listOrder == 'a.ordering');
        //$canCreate  = $this->user->authorise('core.create', $this->component . '.' . $this->crud . '.'.$item->catid);
        //$canEdit    = $this->user->authorise('core.edit', $this->component . '.' . $this->crud . '.'.$item->id);
        //$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
        //$canEditOwn = $this->user->authorise('core.edit.own', $this->component . '.' . $this->crud . '.'.$item->id) && $item->created_by == $userId;
        //$canChange  = $this->user->authorise('core.edit.state', $this->component . '.' . $this->crud . '.'.$item->id) && $canCheckin;

        $html = '';

        foreach($this->fields as $field)
        {
            $columnKey = end(explode(".",$field['column']));
            $class = $field['class'];

            $field['onclick'] = $field['onclick'] === false ? false : true;
            $onclick = $field['onclick'] ? (!empty($item->onclick_url) ? 'onclick="location.href=\'' . $item->onclick_url . '\'"' : 'onclick="location.href=\'' . Route4U::getUrl('index.php?option=' . $this->component . '&view=' . $this->crud . '&id=' . $item->id) . '\'"') : '';

            if($field['onclick']){
                $class .= " col-clickable";
            }
            $class .= ' ';

            switch($columnKey)
            {

                case 'ordering':
                    $html .= '<td class="' . $class. '">';
                    $disableClassName = '';
                    $disabledLabel	  = '';
                    if (!$this->saveOrder) {
                        $disabledLabel    = JText::_('JORDERINGDISABLED');
                        $disableClassName = 'inactive tip-top';
                    }
                    $html .= '<span class="sortable-handler hasTooltip ' . $disableClassName . '" title="' . $disabledLabel . '">';
                    $html .= '<i class="icon-menu"></i>';
                    $html .= '</span>';
                    $html .= '<input type="text" style="display:none" name="order[]" size="5" value="' . $item->ordering . '" class="width-20 text-area-order " />';
                    $html .= '</td>';
                    break;
                case 'check':
                    $html .= '<td class="' . $class. '">';
                    $html .= '<div class="custom-control custom-checkbox">';
                    $html .= '<input type="checkbox" class="custom-control-input" id="cb' . $i . '" name="cid[]" value="' . $item->id . '" onclick="Joomla.isChecked(this.checked);" />';
                    $html .= '<label class="custom-control-label" for="cb' . $i . '"></label>';
                    $html .= '</div>';
                    $html .= '</td>';
                    break;
                case 'pubfeat':
                    $html .= '<td class="' . $class. '">';
                    $html .= '<div class="btn-group">';
                    $html .= JHtml::_('jgrid.published', $item->published, $i, $this->cruds . '.', $canChange, 'cb');
                    $html .= $this->featured( $item->featured, $i, $canChange);
                    $html .= '</div>';
                    $html .= '</td>';
                    break;
                case 'featured':
                    $html .= '<td class="' . $class. '">';
                    $html .= '<div class="btn-group">';
                    $html .= $this->featured( $item->featured, $i, $canChange);
                    $html .= '</div>';
                    $html .= '</td>';
                    break;
                case 'published':
                    $html .= '<td class="' . $class. '">';
                    $html .= '<div class="btn-group">';
                    $html .= JHtml::_('jgrid.published', $item->published, $i, $this->cruds . '.', $canChange, 'cb');
                    $html .= '</div>';
                    $html .= '</td>';
                    break;
                case 'registerDate':
                case 'carried_out':
                case 'date':
                case 'created':
                    $html .= '<td class="' . $class. '" ' . $onclick . '' . $onclick . '>';
                    if(!empty($item->{$columnKey}) && $item->{$columnKey} != '0000-00-00 00:00:00' && $item->{$columnKey} != '0000-00-00') {
                        $jFieldsFormat = $field["format"]=='' ? 'date' : $field["format"];
                        $html .= $this->formatValue( $item->{$columnKey}, $jFieldsFormat);
                    }
                    $html .= '</td>';
                    break;
                case 'id':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= (int) $item->id;
                    $html .= '</td>';
                    break;
                case 'form':
                    $buttons = $field['form'];

                    $html .= '<td class="' . $class. '">';

                    $html .= '<div class="btn-group pull-right" style="display: flex;">';

                    if(isset($buttons['care']) && $buttons['care'] == true){
                        $html .= '<a href="' . Route4U::getUrl('index.php?option=' . Input4U::get('option', 'REQUEST') . '&view=care&layout=edit&id=' . $item->id) . '" class="btn btn-' . ($item->care_id > 0 ? 'success' : 'default') . ' btn-url pull-left">F1</a>';
                    }

                    if(isset($buttons['day']) && $buttons['day'] == true){
                        $html .= '<a href="' . Route4U::getUrl('index.php?option=' . Input4U::get('option', 'REQUEST') . '&view=daycare&layout=edit&id=' . $item->id) . '" class="btn btn-' . ($item->daycare_id > 0 ? 'success' : 'default') . ' btn-url pull-left">F2</a>';
                    }

                    if(isset($buttons['insurance']) && $buttons['insurance'] == true){
                        $html .= '<a href="' . Route4U::getUrl('index.php?option=' . Input4U::get('option', 'REQUEST') . '&view=insurance&layout=edit&id=' . $item->id) . '" class="btn btn-' . ($item->insurance_id > 0 ? 'success' : 'default') . ' btn-url pull-left" style="margin-bottom: 1.85714286em;">F3</a>';
                    }

                    $html .= '</div>';

                    $html .= '</td>';

                    break;
                case 'buttons':
                    $buttons = $field['buttons'];

                    $html .= '<td class="action-group ' . $class. '">';

                    $html .= '<div class="btn-group float-right">';

                    if(isset($buttons['view']) && $buttons['view'] == true){
                        $html .= '<a href="' . Route4U::getUrl('index.php?option=com_engine&view=' . $this->crud . '&id=' . $item->id) . '" class="btn btn-default btn-sm btn-overview"><span class="ion-md-eye"></span></a>';
                    }

                    if(isset($buttons['workorderDetail']) && $buttons['workorderDetail'] == true){
                        $btnDetailUrl = Route4U::getUrl('index.php?option=com_engine&view=' . $this->crud . '&id=' . $item->id);
                        $html .= '<a href="' . $btnDetailUrl . '" class="btn btn-default btn-overview"><span class="ion-md-information-circle-outline" style=""></span></a>';
                    }

                    if(isset($buttons['pdf']) && $buttons['pdf'] == true){
                        $html .= '<a target="_blank" href="' . JUri::base() . 'index.php?option=com_engine&task=' . $this->crud . '.createPDF&id=' . $item->id . Input4U::Itemid() . '" class="btn btn-primary btn-sm"><span class="ion-md-document"></span></a>';
                    }

                    if(isset($buttons['edit']) && $buttons['edit'] == true){

                        if(!empty($field['editTask'])) {
                            $editTask = $field['editTask'];
                        } else {
                            $editTask = 'edit';
                        }

                        $editLayout = '';
                        if(!empty($field['editLayout'])) {
                            $editLayout = '&layout=' . $field['editLayout'];
                        }

                        $editUrl = JUri::base() . 'index.php?option=' . Input4U::get('option', 'REQUEST') . '&task=' . $this->crud . '.' . $editTask . '&id=' . $item->id . Input4U::Itemid() . $editLayout;
                        $html .= '<a href="' . $editUrl . '" class="btn btn-default btn-sm"><span class="ion-md-create"></span></a>';
                    }

                    if(isset($buttons['delete']) && $buttons['delete'] == true){

                        if(!empty($item->delete_now_allowed_message)) {
                            $html .= '<button type="button" onclick="alert(\'' . $item->delete_now_allowed_message . '\')"  class="btn btn-danger btn-sm"><span class="ion-md-trash"></span></button>';
                        } else {
                            $html .= '<button type="button" onclick="confirm(\'Weet u het zeker?\') ? location.href=\'' . JUri::base() . 'index.php?option=' . Input4U::get('option', 'REQUEST') . '&task=' . $this->cruds . '.delete&cid[]=' . $item->id . '&' . JFactory::getSession()->getFormToken() . '=1' . Input4U::Itemid() . '\' : \'\'"  class="btn btn-danger btn-sm"><span class="ion-md-trash"></span></button>';
                        }
                    }

                    if(isset($buttons['archive']) && $buttons['archive'] == true){
                        $html .= '<button type="button" onclick="confirm(\'Weet u het zeker?\') ? location.href=\'' . JUri::base() . 'index.php?option=' . Input4U::get('option', 'REQUEST') . '&task=' . $this->cruds . '.archiveUser&cid[]=' . $item->id . '&' . JFactory::getSession()->getFormToken() . '=1' . Input4U::Itemid() . '\' : \'\'"  class="btn btn-warning btn-sm"><span class="ion-md-archive"></span></button>';
                    }

                    if(isset($buttons['publish']) && $buttons['publish'] == true){
                        $html .= '<button type="button" onclick="confirm(\'Weet u het zeker?\') ? location.href=\'' . JUri::base() . 'index.php?option=' . Input4U::get('option', 'REQUEST') . '&task=' . $this->cruds . '.publishUser&cid[]=' . $item->id . '&' . JFactory::getSession()->getFormToken() . '=1' . Input4U::Itemid() . '\' : \'\'"  class="btn btn-success btn-sm"><span class="ion-md-person-add"></span></button>';
                    }

                    $html .= '</div>';

                    $html .= '</td>';

                    break;
                case 'link':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= '<a class="table-link" href="' . str_replace('{ID}', $item->id, $field['table_link']) . '">' . $field['table_link_title'] . '</a>';
                    $html .= '</td>';
                    break;
                case 'url':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= '<a class="table-link" href="' . str_replace('{URL}', $item->url, $field['table_link']) . '">' . str_replace('{URL}', $item->url, $field['table_link_title']) . '</a>';
                    $html .= '</td>';
                    break;
                case 'fromto':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= '<p class="type--fine-print no-bottom">' . JHtml::date($item->start, 'j-n-Y')  .  ' t/m ' . JHtml::date($item->end, 'j-n-Y') . '</p>';
                    $html .= '</td>';
                    break;
                case 'contact_name':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= '<p>' . $item->contact->firstname . '  ' . $item->contact->lastname .   '</p>';
                    $html .= '</td>';
                    break;
                case 'details_description':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    foreach($item->details as $detail) {
                        $html .= '<p>' . $detail->description .   '</p>';
                    }
                    $html .= '</td>';
                    break;
                case 'invoice_date':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= '<p>' . JHtml::date($item->invoice_date, 'j-n-Y') . '</p>';
                    break;
                case 'invoice_amount':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= '<p>' . Price4U::showPrice($item->total_price_incl_tax) . '</p>';
                    break;
                case 'photo':
                    $html .= '<td class="' . $class. '"' . $onclick . '>';
                    $html .= $item->{$columnKey};
                    break;
                default:

                    $html .= '<td class="' . $class. '" ' . $onclick . '>';

                    if($field['image']){
                        $html .= "<span>".$this->showImage($item->{$columnKey}, $item, $field)."<span>";
                    } elseif($field['checkbox']) {

                        $checked = false;

                        if($compares && property_exists($compares, $item->id)){
                            $checked = true;
                        }

                        $html .= '<a role="button" tabindex="0" class="btn btn-onclick benchmark-popover btn-benchmark-compare ' . ($checked ? ' btn-success' : 'btn-default') . '" data-id="' . $item->id . '" data-type="' . $this->crud . '">
                                <span class="zmdi zmdi-check" style="' . (!$checked ? 'display:none' : '') . '"></span> ' . JText::_('COM_ENGINE_COMPARE') . '
                                </a>';

                    } else {
                        $html .= $this->showValue( $item->{$columnKey}, $item->id, $field['link'], $item->href, $field['html'], $field['image'], $field['format'] );
                    }

                    $html .= '</td>';


                    break;
            }

        }

        return $html;
    }

    /**
     * Adds hidden fields
     *
     * @param null $fields
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function addHiddenFields($fields = null)
    {
        $html = "";
        foreach($fields as $field)
        {
            $html .= "<input type='hidden' name='". $field['name'] . "' value='". $field['value'] ."'/>";
        }

        return $html;
    }

    public function showImage($value, $item, $field)
    {
        $html = '';

        $pathinfo = pathinfo($value);

        if(strlen(trim($value))){
            if($field['cache'] == true){
                $value = JHtmlImage::cache($value, $pathinfo['dirname'] . '/', null, $field['cache_width'], $field['cache_height']);
            } else {
                $value = JUri::base() . $value;
            }

            $html .= '<img src="' . $value . '" class="img-responsive"/>';
        }

        return $html;
    }
    /**
     * Shows some value
     *
     * @param $value
     * @param int $id
     * @param bool $link
     * @param $href
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function showValue($value, $id=0, $link=false, $href, $htmlAllowed = false, $isImage = false, $format=false )
    {
        $html = "";
        if($href)
        {
            $html .= '<a href="' . JRoute::_($href) . '">';
        }
        elseif($link)
        {
            $html .= '<a href="' . Route4U::getUrl('index.php?option=' . $this->component . '&view=' . $this->crud . '&id=' . $id) . '">';
        }

        if($format) {
            $value = $this->formatValue( $value, $format );
        }

        if($isImage && strlen(trim($value))){
            $html .= '<img src="' . JUri::base() . $value . '" class="img-responsive"/>';
        } elseif($htmlAllowed){
            $html .= $value;
        } else {
            $html .= $this->escape( $value );
        }

        if($link) {
            $html .= '</a>';
        }
        elseif($href)
        {
            $html .= '</a>';
        }
        return $html;
    }

    public function formatValue( $value, $format )
    {
        if(empty($value) || $value == '' || $value == '0000-00-00 00:00:00' || $value == '0000-00-00') {
            return "";
        }

        switch ($format)
        {
            case 'date':
                $value = JHtml::date($value, 'd-m-Y');
                break;
            case 'datetime':
                $value = JHtml::date($value, 'd-m-Y H:i');
                break;
            case 'datetimesec':
                $value = JHtml::date($value, 'd-m-Y H:i:s');
                break;
        }

        return $value;
    }

    /**
     * Gets filterbar
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function getFilterBar()
    {
        $ddCount = count($this->dropdowns);
        $lgCol = ($ddCount>2) ? 3 : 4;

        $html = '';

        $html .= '<div class="">';
        $html .= '<div class="row">';

        $html .= '<div class="col-6 col-md-4 col-lg-'.$lgCol.' mb-4 pr-0">';
            $html .= '<input type="text" class="form-control" name="filter_search" placeholder="Trefwoord.." id="filter_search" value="' . $this->escape($this->state->get('filter.search')) . '" />';
        $html .= '</div>';

        if($ddCount){
            foreach($this->dropdowns as $dropdown){
                $html .= '<div class="col-6 col-md-4 col-lg-'.$lgCol.' mb-4">';
                    $html .= $dropdown;
                $html .= '</div>';
            }
        }

        if(count($this->customFilters)){
            foreach($this->customFilters as $customFilter){
                $html .= $customFilter;
            }
        }

        if($this->addDateRangeFilter) {

            $html .= '<link href="' . JUri::base() . 'components/com_engine/assets/css/daterangepicker.css" rel="stylesheet">';
            $html .= '<script type="text/javascript" src="' . JUri::base() . 'components/com_engine/assets/js/moment-with-locales.min.js"></script>';
            $html .= '<script type="text/javascript" src="' . JUri::base() . 'components/com_engine/assets/js/daterangepicker.js"></script>';

            $html .= '<div class="col-12 col-md-6 mb-4">';
            $html .= '<div class="input-group">';
            $html .= '<input type="text" class="daterange form-control" name="filter_dates" value="' . $this->escape($this->state->get('filter.dates')) . '" placeholder="Datum" />';
            $html .= '<span class="input-group-append">';
            $html .= '<button class="btn btn-secondary daterange-open px-3 px-md-2 px-lg-3" type="button"><span class="ion-md-calendar"></span></button>';
            $html .= '</span>';
            $html .= '<span class="input-group-append">';
            $html .= '<button class="btn btn-danger daterange-remove px-3 px-md-2 px-lg-3" type="button"><span class="ion ion-md-close"></span></button>';
            $html .= '</span>';
            $html .= '</div>';
            $html .= '</div>';
        }

        if($this->addDatePicker) {

            $html .= '<div class="col-6 col-md-4 mb-2">';
            $html .= '<div class="input-group">';
            $html .= '<input type="text" class="datepicker form-control" name="filter_date" value="' . $this->escape($this->state->get('filter.date')) . '" onchange="this.form.submit();" placeholder="Datum" />';
            $html .= '</div>';
            $html .= '</div>';
        }

        $html .= '</div>';

        $html .= '</div>';

        return $html;
    }


    /**
     * Description comes later
     *
     * @param int $value
     * @param $i
     * @param bool $canChange
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function featured($value = 0, $i, $canChange = true)
    {
        JHtml::_('bootstrap.tooltip');

        // Array of image, task, title, action
        $states	= array(
            0	=> array('star-empty', $this->cruds . '.featured', 'OVERVIEW_UNFEATURED', 'OVERVIEW_TOGGLE_TO_FEATURE'),
            1	=> array('star', $this->cruds . '.unfeatured', 'OVERVIEW_FEATURED', 'OVERVIEW_TOGGLE_TO_UNFEATURE'),
        );
        $state	= Joomla\Utilities\ArrayHelper::getValue($states, (int) $value, $states[1]);
        $icon	= $state[0];
        if ($canChange) {
            $html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="'.JText::_($state[3]).'"><i class="icon-'
                . $icon.'"></i></a>';
        }

        return $html;
    }

    public function getActiveTab()
    {
        if(count($this->tabs)) {
            $tabByReq = Input4U::get('tab', 'GET');
            if($tabByReq) {
                $this->activeTab = $tabByReq;
                //$this->model->setState('tab.id', $this->activeTab);
            } else {
                $this->activeTab = array_key_first($this->tabs);
            }
        }
        return $this->activeTab;
    }

    /**
     * Description comes later
     *
     * @return string
     *
     * @version     1.0
     * @since       21-11-2016
     */
    public function script()
    {
        $script = "<script type=\"text/javascript\">";
        $script .= "\n Joomla.orderTable = function() { ";
        $script .= "\n 	table = document.getElementById(\"sortTable\"); ";
        $script .= "\n	direction = document.getElementById(\"directionTable\"); ";
        $script .= "\n 	order = table.options[table.selectedIndex].value; ";
        $script .= "\n 	if (order != '" . $this->listOrder . "') { ";
        $script .= "\n 		dirn = 'asc'; ";
        $script .= "\n 	} else { ";
        $script .= "\n 		dirn = direction.options[direction.selectedIndex].value; ";
        $script .= "\n 	} ";
        $script .= "\n	Joomla.tableOrdering(order, dirn, ''); ";
        $script .= "\n } ";
        $script .= "\n </script>";

        return $script;
    }

    /**
     * Description comes later
     *
     * @return string
     *
     * @version     1.0
     * @since       17-09-2020
     */
    public function tabScript()
    {
        $script = "<script>";
        $script .= " function openTab( tab ) { ";
        $script .= " jQuery(\"input[name='filter_tab']\").val( tab ); ";
        $script .= " jQuery('#adminForm').submit(); ";
        $script .= " } ";
        $script .= "</script>";

        return $script;
    }
}
