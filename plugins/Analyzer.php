<?php
if (!defined('XOOPS_ROOT_PATH')) exit();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobileAnalyzerPlugin extends XmobilePlugin
{
	function XmobileAnalyzerPlugin()
	{
		// call parent constructor
		XmobilePlugin::XmobilePlugin();
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobileAnalyzerPluginHandler extends XmobilePluginHandler
{
	var $moduleDir = 'Analyzer';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function XmobileAnalyzerPluginHandler($db)
	{
		XmobilePluginHandler::XmobilePluginHandler($db);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function prepare(&$controller)
	{
//		XmobilePluginHandler::prepare(&$controller);
		XmobilePluginHandler::prepare($controller);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setAccessLog()
	{
		$fileName_old = XOOPS_ROOT_PATH.'/modules/Analyzer/class/cls_analyzer.php';
		$fileName_xc = XOOPS_ROOT_PATH.'/modules/Analyzer/blocks/analyzer_block.php';
		if (file_exists($fileName_old))
		{
			require $fileName_old;
			$this->analyzer_show();
		}
		elseif (file_exists($fileName_xc))
		{
			require $fileName_xc;
			$this->analyzer_xc();
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//---Access log record block---//
	function analyzer_show()
	{
		$ana = new analyzer();
		$ana->delete_data();
		if ( $ana->chk_admin() ) {
			return array();
		}

		if ( $ana->chk_ip() ) {
			$ana->chk_ana();
		}
		return array();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//---Access log record block for Xoops Cube---//
	function analyzer_xc()
	{
		$root =& XCube_Root::getSingleton();
		$root->mContext->mXoopsUser =& $this->user;
//		trigger_error(var_dump($root->mContext->mXoopsUser), E_USER_ERROR);

		$block = array();
		$ana = new Analyzer_RecordBlock($block);
		$ana->execute();

		$root->mContext->mXoopsUser = null;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>