<?php defined('_JEXEC') or die;

class JHtmlDetail extends JViewLegacy
{
	
	public function __construct( $view, $attr=array() )
	{
		JHtml::_('behavior.tooltip');
		JHtml::_('behavior.formvalidation');
		JHtml::_('formbehavior.chosen', 'select');

		$this->view			= $view;
		$this->form			= $view->get("Form");
		$this->item			= $view->get("Item");
		$this->fieldsets	= $this->form->getFieldsets();

		$hiddenFound = false;

		if(count($this->fieldsets)) foreach($this->fieldsets as $fieldSetName => $fieldset) {

			$fields  = $this->form->getFieldset( $fieldSetName );
			if(count($fields)) {
			    foreach($fields as $field) {
                    if(strtolower($field->type)=='editor') {
                        $editorNames[] = $field->fieldname;
                    }
                }
            }

            if($hiddenFound){
			    $this->customFieldsets[] = $fieldset;
            }

            if($fieldset->name == 'hidden'){
			    $hiddenFound = true;
            }
		}

        JModelLegacy::addIncludePath("components/com_fields/models/");
        $groupModel = JModelLegacy::getInstance('Group', 'FieldsModel');

        if(count($this->customFieldsets)){
            foreach($this->customFieldsets as $key => $customFieldset){
                $splittedName = explode('-', $customFieldset->name);

                $this->customFieldsetItems[$key] = $groupModel->getItem($splittedName[1]);
            }
        }

        $this->authorizedViewLevels = array();

        if(Input4U::get('view', 'REQUEST') == 'user'){

            $this->authorizedViewLevels = JAccess::getAuthorisedViewLevels($this->item->id);
        }
	}

	public function fieldset( $title, $hidden=false, $language=false, $table=false )
	{
		$html = "";
		$fields = $this->form->getFieldset( $title );

		if(count($fields))
		{

            foreach($fields as $field)
            {
                if(!$hidden)
                {
                    $html .= "<div class='form-group'>";
                        $html .= $field->label;

	                    if($language)
	                    {
	                        $html .= language4UHelper::getInput($table, $field->getAttribute('name'), $language->lang_id, $this->item->id, $field->getAttribute('type'), false, $field->getAttribute('class'), array('counter' => $field->getAttribute('counter'), 'max_count' => $field->getAttribute('max_count')), $field->__get('options'));
	                    }
	                    else
	                    {
	                        $html .= $field->input;
	                        if($field->getAttribute('counter') == 1){
	                            $html .= '<div id="counter_' . $field->id . '"></div>';
	                            $html .= "<script>
	                            var $ = jQuery.noConflict();
	
	                            $('#" .  $field->id . "').simplyCountable({
	                                maxCount: " . $field->getAttribute('max_count') . ",
	                                strictMax: true,
	                                countDirection: 'down',
	                                counter: '#counter_" . $field->id . "'
	                            });
	                            </script>";
	                        }
	                    }

                    $html .= "</div>";
                }
                else
                {
                    $html .= $field->input;
                }
            }
		}
		
		return $html;
	}
	
	public function title( $newLabel, $editLabel=false )
	{
		if($editLabel===false || $this->item->id==0)
		{
			$html .= JText::_( $newLabel );
		}
		else
		{
			$html .= JText::_( $editLabel );
		}

		return $html;
	}

    public function buttons()
    {
        $html = '';

        $html .= '<div class="pull-right">';
	        $html .= ' <button type="button" class="btn btn-primary" id="btn-submit-form" onclick="Joomla.submitbutton(\'' . Input4U::get('view', 'REQUEST') . '.apply\')">' . JText::_('COM_ENGINE_SAVE') . '</button> ';
	        $html .= ' <button type="button" class="btn btn-default" onclick="jQuery(\'#adminForm\').validate().cancelSubmit = true; Joomla.submitbutton(\'' . Input4U::get('view', 'REQUEST') . '.cancel\')">' . JText::_('COM_ENGINE_CANCEL') . '</button> ';
        $html .= '</div>';

        return $html;
    }
	
	public function header( $newLabel, $editLabel=false, $buttons=true )
	{
		$html = '';

        $html .= '<nav class="layout-navbar navbar navbar-expand-lg align-items-lg-center container-p-x bg-navbar-theme d-block d-lg-none">';
        $html .= '<div class="container flex-lg-wrap">';

        $html .= '<button type="button" class="btn btn-link nav-item nav-link px-0 mr-lg-4" onclick="jQuery(\'#adminForm\').validate().cancelSubmit = true; Joomla.submitbutton(\'' . Input4U::get('view', 'REQUEST') . '.cancel\')">';
            $html .= '<i class="ion ion-md-arrow-back text-large align-middle"></i>';
        $html .= '</button>';

        $html .= '<div class="navbar-text navbar-page-title pt-lg-2 pb-lg-1 pl-lg-2">';
        $html .= ''. self::title( $newLabel, $editLabel ) .'';
        $html .= '</div>';

        //$html .= '<button type="button" class="btn btn-link nav-item nav-link px-0 mr-lg-4" id="btn-submit-form" onclick="Joomla.submitbutton(\'' . Input4U::get('view', 'REQUEST') . '.apply\')">';
            //$html .= '<i class="ion ion-md-checkbox text-large align-middle"></i>';
        //$html .= '</button> ';

        $html .= '</div>';
        $html .= '</nav>';


		$html .= '<h4 class="page-title page-detail-title d-flex justify-content-between align-items-center w-100 font-weight-bold py-0 mb-4">';
			$html .= '<div>'.self::title( $newLabel, $editLabel ).'</div>';
			$html .= '<input type="hidden" id="redirect_field" name="jform[redirect]">';
			if($buttons) {
				$html .= self::buttons();
			}
		$html .= '</h4>';
		
		return $html;
	}


	public function layout($attr)
	{
		$html = '';
		$html .= "<form action='". JUri::base() . "index.php?option=com_" . COMPONENT . "&id=" . $this->item->id . "' method='post' enctype='multipart/form-data' name='adminForm' id='adminForm' class='adminForm form-validate'>";
			$html .= "<div class='row-fluid'>"; 
				$html .= "<div class='form-horizontal'>";

                $html .= self::header( $attr['pageTitleNew'], $attr['pageTitleEdit'], $attr['buttons'] );

				switch($attr['type'])
				{
					case "accordion":
						$html .= self::accordion($attr);
					break;	
					default:
					case "tabs":
						$html .= self::tabs($attr);
					break;	
				}
				
				$html .= "</div>";
			$html .= "</div>";

            $html .= '<div class="row mt-3 footer-actions">';
                $html .= '<div class="col-12">';
                    $html .= '<button type="button" class="btn btn-success btn-block" id="btn-submit-form" onclick="Joomla.submitbutton(\''.Input4U::get('view', 'REQUEST').'.apply\')">Opslaan</button>';
                $html .= '</div>';
            $html .= '</div>';

			$html .= "<div>";
				$html .= $this->fieldset("hidden", true);
				$html .= "<input type='hidden' name='task' id='task' value='" . $this->view->get('name') . ".edit' />";
				$html .= JHtml::_('form.token');
			$html .= "</div>";
		$html .= "</form>";
		
		return $html;
	}
	
	public function tabs($attr)
	{
		$html = '';

		/* TAB indien meer dan 1 */
		if(count($attr['items'])>1)
		{
			$html .= '<ul class="nav nav-tabs" id="tabs">';

	        if($attr['languages'])
	        {
	            foreach(language4UHelper::getLanguages() as  $i => $language)
	            {
	                $html .= '	<li class="'.($i==0?"active":"").'"><a href="#language-'.$language->lang_code.'"  data-toggle="tab">'.language4UHelper::getImage($language).'</a></li>';
	            }
	        }

			foreach($attr['items'] as $item)
			{
	            if($item['id'])
	            {
	                $html .= '	<li class="'.($item['open']?"active":"").'"><a href="#'.$item['layout'].'" id="' . $item['id']  .'" data-toggle="tab">'.JText::_($item['label']).'</a></li>';
	            }
	            else
	            {
	                $html .= '	<li class="'.($item['open']?"active":"").'"><a href="#'.$item['layout'].'"  data-toggle="tab">'.JText::_($item['label']).'</a></li>';

	            }
			}

			if(!empty($this->customFieldsets)) {
	            foreach($this->customFieldsets as $key => $fieldset){
	                $item = $this->customFieldsetItems[$key];

	                if(!in_array($item->access, $this->authorizedViewLevels) && Input4U::get('view', 'REQUEST') == 'user'){
	                    continue;
	                }

	                $html .= '	<li class=""><a href="#'.$fieldset->name.'"  data-toggle="tab">'.$fieldset->label.'</a></li>';

	            }
	        }

			$html .= '</ul>';
			$html .= '<div class="tab-content">';
		}
		
		foreach($attr['items'] as $item)
		{
			if(count($attr['items'])>1) $html .= '<div class="tab-pane '.($item['open']?"active":"").'" id="'.$item['layout'].'">';

				$html .= $this->view->loadTemplate($item['layout']);

			if(count($attr['items'])>1) $html .= '</div>';
		}


		if(!empty($this->customFieldsets)) {
            foreach($this->customFieldsets as $key => $fieldset)
            {
                $item = $this->customFieldsetItems[$key];

                if(!in_array($item->access, $this->authorizedViewLevels) && Input4U::get('view', 'REQUEST') == 'user'){
                    continue;
                }

	            if(count($attr['items'])>1) $html .= '<div class="tab-pane" id="'.$fieldset->name.'">';

                $html .= 	$this->fieldset($fieldset->name);

	            if(count($attr['items'])>1) $html .= '</div>';
            }
        }

        /*
        if($attr['languages'])
        {
            foreach(language4UHelper::getLanguages() as $i => $language)
            {
                $html .= '<div class="tab-pane '.($i==0?"active":"").'" id="language-'.$language->lang_code.'">';

                if($i==0)
                {
                    foreach($this->fieldsets as $fieldset)
                    {
                        if($fieldset->translate)
                        {
                            $html .= $this->fieldset($fieldset->name);
                        }
                    }
                } else{
                    foreach($this->fieldsets as $fieldset)
                    {
                        if($fieldset->translate)
                        {
                            $html .= $this->fieldset($fieldset->name, false, $language, $fieldset->table);
                        }
                    }
                }
                $html .= '</div>';
            }
        }
        */

        if(count($attr['items'])>1) {

            $html .= '</div>';

        }

		return $html;
	}
	
	public function accordion($attr)
	{	
		$html = '';
		
		if($attr['']) {
			$html .= '<div class="accordion" id="accordion">';
		}
		
		foreach($attr['items'] as $item)
		{
			$html .= '<div class="accordion-">';
			$html .= '	<div class="accordion-heading">';
			$html .= '		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#'.$item['layout'].'">';
			$html .= 			JText::_($item['label']);
			$html .= '		</a>';
			$html .= '	</div>';
			$html .= '	<div id="'.$item['layout'].'" class="accordion-body collapse '.($item['open']?"in":"").'">';
			$html .= '		<div class="accordion-inner">';
			$html .= 			$this->view->loadTemplate($item['layout']);
			$html .= '		</div>';
			$html .= '	</div>';
			$html .= '</div>';
		
		}
		
		if($attr['']) {
			$html .= '</div>';
		}
		
		return $html;	
	}

    public function inlineHtmlFormGroup( $label, $value, $cols = array(4,8) )
    {
        $html = '<div class="form-group row">';
            $html .= '<label class="col-sm-' . $cols[0] . ' col-form-label">' . $label . '</label>';
            $html .= '<div class="col-sm-' . $cols[1] . '">';
                $html .= '<span class="form-control-plaintext">' . $value . '</span>';
            $html .= '</div>';
        $html .= '</div>';

        return $html;
	}


	
}
