<?php

/**
 * Fabrik PivotTable Viz HTML View
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.visualization.pivottable
 * @copyright   Copyright (C) 2019  Projeto PITT. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

/**
 * Fabrik PivotTable Viz HTML View
 *
 * @package     Joomla.Plugin
 * @subpackage  Fabrik.visualization.pivottable
 * @since       3.0
 */
class FabrikViewPivotTable extends JViewLegacy
{
	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 */

	public function display($tpl = 'default')
	{
		$app   = JFactory::getApplication(); //COMUM
		$input = $app->input; //COMUM
		$j3    = FabrikWorker::j3(); //COMUM
		$srcs  = FabrikHelperHTML::framework(); //COMUM

		$usersConfig = JComponentHelper::getParams('com_fabrik'); //COMUM
		$model       = $this->getModel(); //COMUM
		$id          = $input->getInt('id', $usersConfig->get('visualizationid', $input->getInt('visualizationid', 0))); //COMUM
		$model->setId($id); //COMUM
		$row = $model->getVisualization(); //COMUM
		//INICIO COMUM
		if (!$model->canView()) {
			echo FText::_('JERROR_ALERTNOAUTHOR');
			return false;
		}
		//FIM COMUM
		$this->pivotjs 	      = $model->getJSData();
		$this->listid 	      = $model->getListId();
		$this->arrayCSV		  = $model->getCsvFields();
		$this->listname		  = $model->getNameList();
		$this->urlbase		  = $model->getUrlBase();
		$this->containerId    = $this->get('ContainerId');
		$this->row            = $row; //COMUM
		$this->showFilters    = $input->getInt('showfilters', 1) === 1 ? 1 : 0;
		$this->filters        = $model->getFilters(); //COMUM
		$this->filterFormURL  = $model->getFilterFormURL(); //COMUM
		$this->urlcontext 	  = $model->getUrlContext();
		$params               = $model->getParams(); //COMUM
		$this->params         = $params; //COMUM
		$tpl                  = $j3 ? 'bootstrap' : 'default'; //COMUM
		$tmplpath             = '/plugins/fabrik_visualization/pivottable/views/pivottable/tmpl/' . $tpl; //COMUM
		$this->_setPath('template', JPATH_ROOT . $tmplpath);
		ini_set('max_execution_time', 4000);

		$opts = new stdClass;
		$opts->list_id = $this->listid;
		$opts->list_name = $this->listname;
		$opts->arr_elements_csv = $this->arrayCSV;
		$opts->urlbase = $this->urlbase;
		$opts = json_encode($opts);

		JHTML::stylesheet('media/com_fabrik/css/list.css');

		FabrikHelperHTML::stylesheetFromPath($tmplpath . '/template.css');


		$ref = $model->getJSRenderContext();
		$js = "var {$ref} = new fbVisPivotTable('pivottable_" . $id . "', $opts);\n";
		$js .= "Fabrik.addBlock('" . $ref . "', {$ref});\n";
		$js .= $model->getFilterJs();

		$srcs['FbVisPivotTable'] = 'plugins/fabrik_visualization/pivottable/pivottable.js';
		FabrikHelperHTML::iniRequireJs($model->getShim());
		FabrikHelperHTML::script($srcs, $js);

		// PIVOT.CSS
		FabrikHelperHTML::stylesheetFromPath('plugins/fabrik_visualization/pivottable/dist/pivot.css');

		FabrikHelperHTML::iniRequireJs('/plugins/fabrik_visualization/pivottable/pivottable.js'); //COMUM

		// Check and add a general fabrik custom css file overrides template css and generic table css
		FabrikHelperHTML::stylesheetFromPath('media/com_fabrik/css/custom.css');

		// Check and add a specific biz  template css file overrides template css generic table css and generic custom css
		FabrikHelperHTML::stylesheetFromPath('plugins/fabrik_visualization/pivottable/views/pivottable/tmpl/' . $tpl . '/custom.css');

		return parent::display();
	}
}
