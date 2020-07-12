<?php
// �ƥץ饰���󥯥饹�ηѾ���
// �᥽�åɤϻ��Ѥ���⥸�塼��˹�碌�ƥ����С��饤��?
//
if (!defined('XOOPS_ROOT_PATH')) exit();
require_once XOOPS_ROOT_PATH.'/modules/'.basename(dirname(dirname(__FILE__))).'/class/PageNavigator.class.php';
require_once XOOPS_ROOT_PATH.'/modules/'.basename(dirname(dirname(__FILE__))).'/class/TableObject.class.php';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobilePlugin extends XmobileTableObject
{
	function XmobilePlugin()
	{
		XmobileTableObject::XmobileTableObject();
	}
//////////////////////////////////////////////////////////////////////////
	function assignSanitizerElement()
	{
	}
//////////////////////////////////////////////////////////////////////////
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobilePluginHandler extends XmobileTableObjectHandler
{
	var $controller;
//	var $db;
	var $utils;
	var $sessionHandler;
	var $session_id;
	var $xmobilePageNavi;
	var $baseUrl = '';
	var $nextViewState = 'default';
	var $template = 'xmobile_plugin.html';

// module parameters
	var $categoryTableName = null;
	var $itemTableName = null;
	var $moduleDir = '';
	var $mid = 0;
	var $moduleName = '';
	var $moduleConfig = array();
	var $moduleAdmin = 0;
	var $modulePerm = 0;

// category parameters
	var $category_id_fld = null;
	var $category_pid_fld = null;
	var $category_title_fld = null;
	var $category_order_fld = null;
	var $category_criteria = null;

	var $category_id = null;
	var $category_pid = null;
	var $category_extra_arg = '';
	var $categoryPageNavi;
	var $categoryTree;

// item parameters
	var $item_id_fld = null;
	var $item_cid_fld = null;
	var $item_title_fld = null;
	var $item_description_fld = null;
	var $item_order_fld = null;
	var $item_date_fld = null;
	var $item_uid_fld = null;
	var $item_hits_fld = null;
	var $item_comments_fld = null;
	var $item_extra_fld = array();
//	var $item_order_sort = 'DESC';
	var $item_order_sort = null;
	var $item_criteria = null;

	var $item_id = null;
	var $item_extra_arg = '';
	var $itemListPageNavi = null;
	var $itemDetailPageNavi = null;

	var $ticket = null;
	var $allowAdd = false;
	var $allowEdit = false;
	var $allowDelete = false;

//	var $xoopsUser = null;
	var $user = null;
	var $uid = null;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function XmobilePluginHandler($db)
	{
		XmobileTableObjectHandler::XmobileTableObjectHandler($db);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function prepare(&$controller)
	{
		$this->controller = $controller;
		$this->utils =& $this->controller->utils;
		$this->sessionHandler =& $this->controller->getSessionHandler();
		$this->setUser();
		$this->setSessionId();
		$this->setTableName();
		$this->setModule();
		global $xoopsConfig;
		// �⥸�塼��θ���ե�����򥤥󥯥롼��
		$fileName = XOOPS_ROOT_PATH.'/modules/'.$this->moduleDir.'/language/'.$xoopsConfig['language'].'/main.php';
		if (file_exists($fileName))
		{
			include_once $fileName;
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function execute()
	{
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �������
// ���ƥ���������ǿ��ǡ����������Խ��ѥ�󥯤�ɽ��
	function getDefaultView()
	{
		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['cat_type'] == 0)// ����ɽ��
		{
			$this->controller->render->template->assign('cat_list',$this->getCatList());
			$this->controller->render->template->assign('cat_list_page_navi',$this->categoryPageNavi->renderNavi());
		}
		elseif ($xoopsModuleConfig['cat_type'] == 1)// �ɥ�åץ�����ꥹ��ɽ��
		{
			$this->controller->render->template->assign('cat_select',$this->getCatSelect());
		}
		$this->controller->render->template->assign('recent_item_list',$this->getRecentList());
		$this->checkPerm();
		$this->controller->render->template->assign('edit_link',$this->getEditLink());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ��������
// ���ƥ���������ǡ����������Խ��ѥ�󥯤�ɽ��
	function getListView()
	{
		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['cat_type'] == 0)// ����ɽ��
		{
			$this->controller->render->template->assign('cat_list',$this->getCatList());
			$this->controller->render->template->assign('cat_list_page_navi',$this->categoryPageNavi->renderNavi());
		}
		elseif ($xoopsModuleConfig['cat_type'] == 1)// �ɥ�åץ�����ꥹ��ɽ��
		{
			$this->controller->render->template->assign('cat_select',$this->getCatSelect());
		}
		$this->controller->render->template->assign('item_list',$this->getItemList());
		$this->controller->render->template->assign('item_list_page_navi',$this->itemListPageNavi->renderNavi());
		$this->checkPerm();
		$this->controller->render->template->assign('edit_link',$this->getEditLink());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ܺٲ���
// �ǡ����ܺ١������ȡ��Խ��ѥ�󥯤�ɽ��
// �ǡ����ܺ٤ϴݤ���HTML��item_detail�Ȥ��ƽ���
	function getDetailView()
	{
		$this->setBaseUrl();
		$this->setCategoryParameter();
		$this->setItemParameter();
		$this->setItemDetailPageNavi();

		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['cat_type'] == 0)// ����ɽ��
		{
			$this->controller->render->template->assign('cat_path',$this->getCatPathFromId($this->category_id));
		}
		elseif ($xoopsModuleConfig['cat_type'] == 1)// �ɥ�åץ�����ꥹ��ɽ��
		{
			$this->controller->render->template->assign('cat_select',$this->getCatSelect());
		}

		$this->controller->render->template->assign('item_detail',$this->getItemDetail());
		$this->controller->render->template->assign('item_detail_page_navi',$this->itemDetailPageNavi->renderNavi());
//die(var_dump($this->itemDetailPageNavi->renderNavi()));
		$this->checkPerm();
		$this->controller->render->template->assign('edit_link',$this->getEditLink($this->item_id));
		// ������
		// com_op�ϥ����Ȱ�������Ʋ��̤ǵ�����ʸ��ɽ�������椹��٤�ɬ��
		$this->controller->render->template->assign('comment_link',$this->getCommentLink($this->item_id));
		$com_op = htmlspecialchars($this->controller->utils->getGetPost('com_op', ''), ENT_QUOTES);
		$this->controller->render->template->assign('com_op',$com_op);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �Խ�����
	function getEditView()
	{
		$this->ticket = new XoopsGTicket;
		$this->setNextViewState('confirm');
		$this->setBaseUrl();
		$this->setCategoryParameter();
		$this->setItemParameter();
		$this->checkPerm();
		$this->controller->render->template->assign('item_detail',$this->getForm());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ��Ʋ���
	function getConfirmView()
	{
		$this->ticket = new XoopsGTicket;
		$this->setNextViewState('detail');
		$this->setBaseUrl();
		$this->setCategoryParameter();
//		$this->setItemParameter();
		$this->item_id = intval($this->utils->getGetPost(XMTO_PREFIX.$this->item_id_fld, 0));
		$this->checkPerm();
		$this->controller->render->template->assign('item_detail',$this->saveRecord());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���ƥ�������μ���
// ������������ͤϥ��֥������ȤǤϤʤ�����
	function getCatList()
	{
		$this->setNextViewState('list');
		$this->setBaseUrl();
		$this->setCategoryParameter();

		if (!is_null($this->category_pid_fld) || is_null($this->category_id))
		{
			$categoryArray = $this->categoryTree->getFirstChild($this->category_id);
		}
		else
		{
			$categoryArray = false;
		}

		// ���ƥ���Υѥ󤯤���ɽ��
		$this->controller->render->template->assign('cat_path',$this->getCatPathFromId($this->category_id));

		if (!is_array($categoryArray))
		{
			return false;
		}

		$subcategory_count = count($categoryArray);
		if ($subcategory_count == 0) // ɽ������ǡ���̵��
		{
			return false;
		}

		if (!is_null($this->category_id))
		{
			$item_count = $this->getItemCountById();
		}
		else
		{
			$item_count = 0;
		}

		if ($item_count > 0)
		{
			$use_accesskey = false;
		}
		else
		{
			$use_accesskey = true;
		}

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getCatList subcategory_count', $subcategory_count);
		$this->utils->setDebugMessage(__CLASS__, 'getCatList item_count', $item_count);

		$cat_list = array();
		$i = 0;
		foreach($categoryArray as $category)
		{
			$id = $category[$this->category_id_fld];
			$title = $category[$this->category_title_fld];
			$url_parameter = $this->getBaseUrl();

			if (!is_null($this->category_pid_fld))
			{
				$pid = $category[$this->category_pid_fld];
				$url_parameter .= '&amp;'.$this->category_pid_fld.'='.$pid;
			}
			if (!is_null($this->category_id_fld))
			{
				$url_parameter .= '&amp;'.$this->category_id_fld.'='.$id;
			}
//			$htmlBody .= $this->getListTitleLink($number,$id,$title,$url_parameter,$use_accesskey).'<br />';
			$number = $i + 1; // �������������Ѥ��ֹ桢1���鳫��
			$cat_list[$i]['key'] = $number;
			$cat_list[$i]['title'] = $this->adjustTitle($title);
			$cat_list[$i]['url'] = $url_parameter;
			$cat_list[$i]['item_count'] = sprintf(_MD_XMOBILE_NUMBER, $this->getChildItemCountById($id));
			$i++;
		}
		return $cat_list;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���ƥ��ꥻ�쥯�ȥܥå����μ���
// ����ͤ�HTML
	function getCatSelect()
	{
		$this->setNextViewState('list');
		$this->setBaseUrl();
		$this->setCategoryParameter();

		if (is_null($this->category_id_fld))
		{
			return false;
		}

		$cat_select = $this->categoryTree->makeMySelBox($this->category_id,true,null,$this->category_criteria);

		if ($cat_select != '')
		{
			$base_url = preg_replace("/&amp;/i",'&',$this->getBaseUrl());
			$catselect4html = '';
			$catselect4html .= '<form action="'.$base_url.'" method="post">';
			$catselect4html .= '<div class ="form">';
			$catselect4html .= _MD_XMOBILE_CATEGORY.'<br />';
			$catselect4html .= $cat_select.'<br />';
			$catselect4html .= '<input type="submit" name="submit" value="'._MD_XMOBILE_SHOW.'" />';
			$catselect4html .= '</div>';
			$catselect4html .= '</form>';
		}
		else // ɽ������ǡ���̵��
		{
			$catselect4html = false;
		}

		return $catselect4html;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���������μ���
// ������������ͤϥ��֥������ȤǤϤʤ�����
	function getItemList()
	{
		global $xoopsConfig;

		$this->setNextViewState('detail');
		$this->setBaseUrl();
		$this->setItemParameter();
		$this->setItemListPageNavi();

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getList criteria', $this->item_criteria->render());

		$itemObjectArray =& $this->getObjects($this->item_criteria);
		if (!$itemObjectArray)
		{
			// debug
			$this->utils->setDebugMessage(__CLASS__, 'getList Error', $this->getErrors());
		}

		if (count($itemObjectArray) == 0) // ɽ������ǡ���̵��
		{
//			return _MD_XMOBILE_NO_DATA;
			$this->controller->render->template->assign('lang_no_item_list',_MD_XMOBILE_NO_DATA);
			return false;
		}

		$item_list = array();
		$i = 0;
		foreach($itemObjectArray as $itemObject)
		{
			$id = $itemObject->getVar($this->item_id_fld);
			$title = $itemObject->getVar($this->item_title_fld);
			// �ܺ٥���ѥѥ�᡼������
			$url_parameter = $this->getBaseUrl();
			if (!is_null($this->category_pid_fld) && !is_null($this->category_pid))
			{
				$url_parameter .= '&amp;'.$this->category_pid_fld.'='.$this->category_pid;
			}
			if (!is_null($this->category_id_fld) && ($this->item_cid_fld != $this->category_id_fld))
			{
				$url_parameter .= '&amp;'.$this->category_id_fld.'='.$this->category_id;
			}
			if (!is_null($this->item_cid_fld))
			{
				$cid = $itemObject->getVar($this->item_cid_fld);
				$url_parameter .= '&amp;'.$this->item_cid_fld.'='.$cid;
			}
			if (!is_null($this->item_id_fld))
			{
				$url_parameter .= '&amp;'.$this->item_id_fld.'='.$id;
			}

			$date = '';
			if (!is_null($this->item_date_fld))
			{
				$date = $itemObject->getVar($this->item_date_fld);
				$date = $this->utils->getDateShort($date);
			}

			$number = $i + 1; // �������������Ѥ��ֹ桢1���鳫��
			$item_list[$i]['key'] = $number;
			$item_list[$i]['title'] = $this->adjustTitle($title);
			$item_list[$i]['url'] = $url_parameter;
			$item_list[$i]['date'] = $date;
			$i++;
		}
		return $item_list;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ǿ����������μ���
// ������������ͤϥ��֥������ȤǤϤʤ�����
	function getRecentList()
	{
		global $xoopsModuleConfig;
		global $xoopsConfig;

		if ($xoopsModuleConfig['show_recent_title'] == 0)
		{
			return false;
		}

		$this->setNextViewState('detail');
		$this->setBaseUrl();
		$this->setItemParameter();
		if (!is_null($this->item_date_fld))
		{
			$this->item_criteria->setSort($this->item_date_fld);
			$this->item_criteria->setOrder('DESC');
			$this->item_criteria->setLimit($xoopsModuleConfig['recent_title_row']);
		}

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getRecentList criteria', $this->item_criteria->render());

		if (!$itemObjectArray =& $this->getObjects($this->item_criteria))
		{
			$this->utils->setDebugMessage(__CLASS__, 'getRecentlist Error', $this->getErrors());
		}

		if (count($itemObjectArray) == 0) // ɽ������ǡ���̵��
		{
			$this->controller->render->template->assign('lang_no_item_list',_MD_XMOBILE_NO_DATA);
			return false;
		}

		$recent_list = array();
		$i = 0;
		foreach($itemObjectArray as $itemObject)
		{
			$id = $itemObject->getVar($this->item_id_fld);
			$title = $itemObject->getVar($this->item_title_fld);
			$url_parameter = $this->getBaseUrl();

			if (!is_null($this->category_pid_fld) && !is_null($this->category_pid))
			{
				$url_parameter .= '&amp;'.$this->category_pid_fld.'='.$this->category_pid;
			}
			if (!is_null($this->category_id_fld) && ($this->item_cid_fld != $this->category_id_fld) && !is_null($this->category_id))
			{
				$url_parameter .= '&amp;'.$this->category_id_fld.'='.$this->category_id;
			}
			if (!is_null($this->item_cid_fld))
			{
				$cid = $itemObject->getVar($this->item_cid_fld);
				$url_parameter .= '&amp;'.$this->item_cid_fld.'='.$cid;
			}
			if (!is_null($this->item_id_fld))
			{
				$url_parameter .= '&amp;'.$this->item_id_fld.'='.$id;
			}
			$date = '';
			if (!is_null($this->item_date_fld))
			{
				$date = $itemObject->getVar($this->item_date_fld);
				$date = $this->utils->getDateShort($date).' '.$this->utils->getTimeShort($date);
			}

			$recent_list[$i]['title'] = $this->adjustTitle($title);
			$recent_list[$i]['url'] = $url_parameter;
			$recent_list[$i]['date'] = $date;
			$i++;
		}
		return $recent_list;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ܺ١������ȡ��Խ��ѥ�󥯤μ���
// ������������ͤϥ��֥������ȤǤϤʤ�HTML
	function getItemDetail()
	{
		global $xoopsConfig;

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getItemDetail criteria', $this->item_criteria->render());
		// ��դ�id�ǤϤʤ�criteria�Ǹ�������١����֥������Ȥ������֤����
		if (!$itemObjectArray =& $this->getObjects($this->item_criteria))
		{
			// debug
			$this->utils->setDebugMessage(__CLASS__, 'getItemDetail Error', $this->getErrors());
		}

		if (count($itemObjectArray) == 0)
		{
			return false;
		}

		$itemObject = $itemObjectArray[0];

		if (!is_object($itemObject))
		{
			return false;
		}

		// assign item object
		$this->controller->render->template->assign('itemObject',$itemObject);
		$this->controller->render->template->assign('item_id_fld',$this->item_id_fld);
		$this->controller->render->template->assign('item_cid_fld',$this->item_cid_fld);
		$this->controller->render->template->assign('item_title_fld',$this->item_title_fld);
		$this->controller->render->template->assign('item_description_fld',$this->item_description_fld);
		$this->controller->render->template->assign('item_date_fld',$this->item_date_fld);
		$this->controller->render->template->assign('item_uid_fld',$this->item_uid_fld);
		$this->controller->render->template->assign('item_hits_fld',$this->item_hits_fld);


		$this->item_id = $itemObject->getVar($this->item_id_fld);
		$url_parameter = $this->getBaseUrl();
		$itemObject->assignSanitizerElement();


		$detail4html = '';
		$detail4html .= _MD_XMOBILE_ITEM_DETAIL.'<br />';
		// �����ȥ�
		if (!is_null($this->item_title_fld))
		{
			$detail4html .= _MD_XMOBILE_TITLE;
			$detail4html .= $itemObject->getVar($this->item_title_fld).'<br />';
		}
		// �桼��̾
		if (!is_null($this->item_uid_fld))
		{
			$uid = $itemObject->getVar($this->item_uid_fld);
			$uname = $this->getUserLink($uid);
			$detail4html .= _MD_XMOBILE_CONTRIBUTOR.$uname.'<br />';
		}
		// ���ա�����
		if (!is_null($this->item_date_fld))
		{
			$date = $itemObject->getVar($this->item_date_fld);
			$detail4html .= _MD_XMOBILE_DATE.$this->utils->getDateLong($date).'<br />';
			$detail4html .= _MD_XMOBILE_TIME.$this->utils->getTimeLong($date).'<br />';
		}

		// �ҥåȿ�
		if (!is_null($this->item_hits_fld))
		{
			$detail4html .= _MD_XMOBILE_HITS.$itemObject->getVar($this->item_hits_fld).'<br />';
			// �ҥåȥ�����Ȥ�����
			$this->increaseHitCount($this->item_id);
		}

		// ������
		if (!is_null($this->item_comments_fld))
		{
//			$detail4html .= _MD_XMOBILE_COMMENT.$itemObject->getVar($this->item_comments_fld).'<br />';
		}
		// �ܺ�
		$description = '';
		if (!is_null($this->item_description_fld))
		{
			$description = $itemObject->getVar($this->item_description_fld);
			$detail4html .= _MD_XMOBILE_CONTENTS.'<br />';
			$detail4html .= $description.'<br />';
		}

		// ����¾��ɽ���ե������
		if (count($this->item_extra_fld) > 0)
		{
			foreach($this->item_extra_fld as $key=>$caption)
			{
				if ($itemObject->getVar($key))
				{
					$detail4html .= $caption;
					$detail4html .= $itemObject->getVar($key).'<br />';
				}
			}
		}
		return $detail4html;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function checkPerm()
	{
		$type = htmlspecialchars($this->utils->getGetPost('type', ''), ENT_QUOTES);
		// �Խ�����Ĥ�����ϡ��ƥץ�ѥƥ���true������
		$this->allowAdd = false;
		$this->allowEdit = false;
		$this->allowDelete = false;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getForm()
	{
		$type = htmlspecialchars($this->utils->getGetPost('type', ''), ENT_QUOTES);

		switch ($type)
		{
			case 'new':

				$record =& $this->create();
				if (!is_object($record))
				{
					return false;
				}

				break;

			case 'edit':

			case 'delete':

				$record =& $this->get($this->item_id);

				if (!is_object($record))
				{
					return false;
				}
				break;
		}


		if (is_object($this->categoryTree) && !is_null($this->category_id_fld))
		{
			$cat_tree_arr = $this->categoryTree->getAllTreeArray();
			$record->assignFormElement($this->category_id_fld, array('type'=>'select', 'caption'=>_MD_XMOBILE_CATEGORY, 'params'=>$cat_tree_arr));
		}

		if (!is_null($this->item_date_fld))
		{
			$date = $this->utils->getDateLong($record->getVar($this->item_date_fld));
			$record->assignFormElement($this->item_date_fld, array('type'=>'label', 'caption'=>_MD_XMOBILE_DATE, 'value'=>$date));
		}
		if (!is_null($this->item_uid_fld))
		{
			$record->assignFormElement($this->item_date_fld, array('type'=>'hidden', 'value'=>$this->uid));
		}

		$baseUrl = preg_replace('/&amp;/i','&',$this->baseUrl);
		$record->assignFormElement('HTTP_REFERER', array('type'=>'hidden', 'value'=>$this->baseUrl));
		$record->assignFormElement(session_name(), array('type'=>'hidden', 'value'=>session_id()));
		$record->assignFormElement('op', array('type'=>'hidden', 'value'=>'save'));
		$record->assignFormElement('type', array('type'=>'hidden', 'value'=>$type));
		$record->initFormElements($type);

		//return $this->renderForm(&$record, $this->baseUrl, $type);
		return $this->renderForm($record, $this->baseUrl, $type);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setNewVars(&$record)
	{
	
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function saveRecord()
	{
		$type = htmlspecialchars($this->utils->getGetPost('type', ''), ENT_QUOTES);
		$myts =& MyTextSanitizer::getInstance();
		$body = '';

		if (isset($_POST['cancel']))
		{
			$baseUrl = preg_replace('/&amp;/i','&',$this->baseUrl);
			header('Location: '.$baseUrl);
			exit();
		}

		//�����åȤγ�ǧ
		if (!$ticket_check = $this->ticket->check(true,'',false))
		{
			return _MD_XMOBILE_TICKET_ERROR;
		}

		switch ($type)
		{
			case 'new':

				if ($this->allowAdd == false)
				{
					$body = _MD_XMOBILE_NO_PERM_MESSAGE;
					return $body;
				}

				$record =& $this->create();

				if (!is_object($record))
				{
					$this->utils->setDebugMessage(__CLASS__, 'Record does not exist', $record->getErrors(true));
					return false;
				}

				$record->setFormVars($_POST, XMTO_PREFIX);

				if (!is_null($this->item_uid_fld))
				{
					$record->setVar($this->item_uid_fld, $this->uid);
				}
				if (!is_null($this->item_date_fld))
				{
					$record->setVar($this->item_date_fld, time());
				}

//				$this->setNewVars(&$record);
				$this->setNewVars($record);

				$updateOnlyChanged = false;

				if ($this->insert($record, false, $updateOnlyChanged))
				{
					$record->unsetNew();
					$body = _MD_XMOBILE_INSERT_SUCCESS;
				}
				else
				{
					// debug
					$this->utils->setDebugMessage(__CLASS__, 'Insert Record Error', $this->getErrors());
					$body = _MD_XMOBILE_INSERT_FAILED;
				}
				break;

			case 'edit':

				if ($this->allowEdit == false)
				{
					$body = _MD_XMOBILE_NO_PERM_MESSAGE;
					return $body;
				}

				$record =& $this->get($this->item_id);

				if (!is_object($record))
				{
					$this->utils->setDebugMessage(__CLASS__, 'Record does not exist', $this->getErrors());
					return false;
				}

				$record->setFormVars($_POST, XMTO_PREFIX);

				if (!is_null($this->item_uid_fld))
				{
					$record->setVar($this->item_uid_fld, $this->uid);
				}
				if (!is_null($this->item_date_fld))
				{
					$record->setVar($this->item_date_fld, time());
				}

				$updateOnlyChanged = true;
				if ($this->insert($record, false, $updateOnlyChanged))
				{
					$body = _MD_XMOBILE_UPDATE_SUCCESS;
				}
				else
				{
					// debug
					$this->utils->setDebugMessage(__CLASS__, 'Update Record Error', $this->getErrors());
					$body = _MD_XMOBILE_UPDATE_FAILED;
				}

				break;

			case 'delete':

				if ($this->allowDelete == false)
				{
					$body = _MD_XMOBILE_NO_PERM_MESSAGE;
					return $body;
				}

				$record =& $this->get($this->item_id);

				if (!is_object($record))
				{
					$this->utils->setDebugMessage(__CLASS__, 'Record does not exist', $this->getErrors());
					return false;
				}

				if ($this->delete($record, false))
				{
					$body = _MD_XMOBILE_DELETE_SUCCESS;
				}
				else
				{
					// debug
					$this->utils->setDebugMessage(__CLASS__, 'Delete Record Error', $this->getErrors());
					$body = _MD_XMOBILE_DELETE_FAILED;
				}

				break;
		}

		return $body;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// xoopsUser������
	function setUser()
	{
//		$this->xoopsUser =& $this->sessionHandler->getUser();
		$this->user =& $this->sessionHandler->getUser();
		$this->uid = $this->sessionHandler->getUid();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// session_id������
	function setSessionId()
	{
		$this->session_id = $this->sessionHandler->getSessionID();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ơ��֥�̾������
	function setTableName()
	{
		if (!is_null($this->categoryTableName)) $this->categoryTableName = $this->db->prefix($this->categoryTableName);
		if (!is_null($this->itemTableName)) $this->itemTableName = $this->db->prefix($this->itemTableName);

		$this->tableName = $this->itemTableName;

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'categoryTableName', $this->categoryTableName);
		$this->utils->setDebugMessage(__CLASS__, 'itemTableName', $this->itemTableName);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �⥸�塼�������
	function setModule()
	{
		$module_handler =& xoops_gethandler('module');
		$this->module =& $module_handler->getByDirName($this->moduleDir);
		if (!is_object($this->module))
		{
			return false;
		}
		$this->mid = $this->module->getVar('mid');
		$this->moduleName = $this->module->getVar('name');

		$this->setModuleConfig();
		$this->setModulePerm();
		// debug
		$this->utils->setDebugMessage(__CLASS__, 'mid', $this->mid);
		$this->utils->setDebugMessage(__CLASS__, 'moduleDir', $this->moduleDir);
//		$this->utils->setDebugMessage(__CLASS__, 'moduleName', $this->moduleName);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �⥸�塼��Υ���ե�������
	function setModuleConfig()
	{
		$config_handler =& xoops_gethandler('config');
//		$this->moduleConfig =& $config_handler->getConfigsByDirname($this->moduleDir);
		$this->moduleConfig =& $config_handler->getConfigsByCat(0, $this->mid);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// �⥸�塼��δ����Ը��¥����å�
	function getModuleAdmin()
	{
		$user =& $this->sessionHandler->getUser();
		if (is_object($user))
		{
			$this->moduleAdmin = $user->isAdmin($this->mid);
		}
		else
		{
			$this->moduleAdmin = false;
		}

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'moduleAdmin', $this->moduleAdmin);

		return $this->moduleAdmin;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// �⥸�塼��Υ��롼�ץ����������¥����å�
	function setModulePerm($gperm_name='module_read')
	{
		$pluginState = $this->controller->getPluginState();
		if ($pluginState == 'default')
		{
			$this->modulePerm = true;
		}
		else
		{
			$user =& $this->sessionHandler->getUser();
			$this->modulePerm = $this->utils->getModulePerm($user, $this->mid, $gperm_name='module_read');
		}
		// debug
//		$this->utils->setDebugMessage(__CLASS__, 'modulePerm', $this->modulePerm);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function &getModuleObject()
	{
		return $this->module;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getModulePerm()
	{
		return $this->modulePerm;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getModuleDir()
	{
		return $this->moduleDir;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getModuleName()
	{
		if (is_object($this->module))
		{
			$this->moduleName = $this->module->getVar('name');
		}
		return $this->moduleName;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getMid()
	{
		$this->mid = $this->module->getVar('mid');
		return $this->mid;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function &getModuleConfig()
	{
		return $this->moduleConfig;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setNextViewState($nextViewState)
	{
		$this->nextViewState = $nextViewState;
		// debug
		$this->utils->setDebugMessage(__CLASS__, 'nextViewState', $this->nextViewState);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setBaseUrl()
	{
		$this->baseUrl = $this->utils->getLinkUrl($this->controller->getActionState(),$this->nextViewState,$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
		// debug
		$this->utils->setDebugMessage(__CLASS__, 'setBaseUrl', $this->baseUrl);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getBaseUrl()
	{
		return $this->baseUrl;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getTitleLink()
	{
		$baseUrl = $this->utils->getLinkUrl('plugin','default',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
		$titleLink = '<a href="'.$baseUrl.'">'.$this->getModuleName().'</a>';
		return $titleLink;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setCategoryParameter()
	{
		$this->setCategoryId();
		$this->setCategoryParentId();
		$this->setCategoryCriteria();
		$this->setCategoryTree();
		$this->setCategoryPageNavi();

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'category_criteria', $this->category_criteria->render());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setCategoryId()
	{
		if (is_null($this->category_id_fld)) return;

		$cid = intval($this->utils->getGetPost($this->category_id_fld, 0));
		$item_cid = 0;
		if (!is_null($this->item_cid_fld))
		{
			$item_cid = intval($this->utils->getGetPost($this->item_cid_fld, 0));
		}

		if ($cid == 0 && $item_cid != 0)
		{
			$this->category_id = $item_cid;
		}
		else
		{
			$this->category_id = $cid;
		}

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'category_id', $this->category_id);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCategoryId()
	{
		return $this->category_id;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCategoryIdField()
	{
		return $this->category_id_fld;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setCategoryParentId()
	{
		if (is_null($this->category_pid_fld)) return;

		if (isset($_GET[$this->category_pid_fld]))
		{
			$this->category_pid = intval($_GET[$this->category_pid_fld]);
		}
		elseif (isset($_POST[$this->category_pid_fld]))
		{
			$this->category_pid = intval($_POST[$this->category_pid_fld]);
		}

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'category_pid', $this->category_pid);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCategoryParentId()
	{
		return $this->category_pid;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setCategoryCriteria()
	{
		$this->category_criteria =& new CriteriaCompo();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCategoryExtraArg()
	{
		// $extra���ͤ�getLinkUrl()��htmlspecialchars()��ݤ�����Τ�&amp;�ǤϤʤ�&�ȵ��Ҥ��Ƥ���
		$extra = '';
		if (!is_null($this->category_pid_fld) && !is_null($this->category_pid))
		{
			$extra .= '&'.$this->category_pid_fld.'='.$this->category_pid;
		}
		if (!is_null($this->category_id_fld) && !is_null($this->category_id))
		{
			$extra .= '&'.$this->category_id_fld.'='.$this->category_id;
		}
		$extra = preg_replace('/^\&/','',$extra);
		$category_extra_arg = $this->utils->getLinkUrl($this->controller->getActionState(),$this->controller->getViewState(),$this->controller->getPluginState(),$this->sessionHandler->getSessionID(),$extra);

		// debug
//		$this->utils->setDebugMessage(__CLASS__, 'category_extra_arg', $category_extra_arg);
		return $category_extra_arg;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCategoryCriteria()
	{
		return $this->category_criteria;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���ƥ���ĥ꡼�ν����
	function setCategoryTree()
	{
		$this->categoryTree =& new XmobileCategoryTree($this->categoryTableName, $this->category_id_fld, $this->category_pid_fld, $this->category_title_fld, $this->category_order_fld);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setCategoryPageNavi()
	{
		global $xoopsModuleConfig;
		$total = $this->categoryTree->getFirstChildCount($this->category_id);

		$this->categoryPageNavi =& new XmobilePageNavigator($total, $xoopsModuleConfig['max_title_row'], 'cat_start', $this->getCategoryExtraArg());
		$this->category_criteria->setLimit($this->categoryPageNavi->getPerpage());
		$this->category_criteria->setStart($this->categoryPageNavi->getStart());
		$this->categoryTree->setCriteria($this->category_criteria);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCategoryPageNavi()
	{
		return $this->categoryPageNavi;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setItemParameter()
	{
		$this->setItemId();
		$this->setItemCriteria();
		$this->addItemCriteria();
//		$this->setItemListPageNavi();

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'item_criteria', $this->item_criteria->render());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setItemId()
	{
		if (is_null($this->item_id_fld)) return;

		if (isset($_GET[$this->item_id_fld]))
		{
			$this->item_id = intval($_GET[$this->item_id_fld]);
		}
		elseif (isset($_POST[$this->item_id_fld]))
		{
			$this->item_id = intval($_POST[$this->item_id_fld]);
		}

//		$this->item_id = intval($this->utils->getGetPost($this->item_id_fld, null));
		// debug
		$this->utils->setDebugMessage(__CLASS__, 'item_id', $this->item_id);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getItemId()
	{
		return $this->item_id;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ǡ���������criteria������
// ɬ�פ˱����Ƴƥץ饰����ǥ����С��饤��
	function setItemCriteria()
	{
		$this->item_criteria =& new CriteriaCompo();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ƥ�ǡ���������criteria���ɲ�����
// ���ƥ���ID�������ȥե�����ɡ������Ƚ������
	function addItemCriteria()
	{
		global $xoopsModuleConfig;
		if (!is_object($this->item_criteria))
		{
			return;
		}
//		if (!is_null($this->item_cid_fld) && !is_null($this->category_id))
		if (!is_null($this->item_cid_fld) && !is_null($this->category_id) && $this->category_id != 0)
		{
			$this->item_criteria->add(new Criteria($this->item_cid_fld, $this->category_id));
		}
		if (!is_null($this->item_order_fld))
		{
			$this->item_criteria->setSort($this->item_order_fld);
		}
		if (is_null($this->item_order_sort))
		{
			$this->item_order_sort = $xoopsModuleConfig['title_order_sort'];
		}
		$this->item_criteria->setOrder($this->item_order_sort);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getItemCriteria()
	{
		return $this->item_criteria;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ڡ����ʥӥ���������ѥѥ�᡼���μ���
	function getItemExtraArg()
	{
		// $extra���ͤ�getLinkUrl()��htmlspecialchars()��ݤ�����Τ�&amp;�ǤϤʤ�&�ȵ��Ҥ��Ƥ���
		$extra = '';
		if (!is_null($this->category_id_fld) && !is_null($this->category_id))
		{
			$extra .= '&'.$this->category_id_fld.'='.$this->category_id;
		}
		else
		{
/*
			if (!is_null($this->item_id_fld) && !is_null($this->item_id))
			{
				$extra .= '&'.$this->item_id_fld.'='.$this->item_id;
			}
*/
		}
		$extra = preg_replace('/^\&/','',$extra);
		$item_extra_arg = $this->utils->getLinkUrl($this->controller->getActionState(),$this->controller->getViewState(),$this->controller->getPluginState(),$this->sessionHandler->getSessionID(),$extra);
		// debug
//		$this->utils->setDebugMessage(__CLASS__, 'item_extra_arg', $item_extra_arg);
		return $item_extra_arg;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ǡ��������Υڡ����ʥӥ��������ν������
// �����ƥ�ǡ���������criteria�˥�ߥåȡ��������Ȥ�����
	function setItemListPageNavi()
	{
		global $xoopsModuleConfig;
		$total = $this->getCount($this->item_criteria);
		$this->itemListPageNavi =& new XmobilePageNavigator($total, $xoopsModuleConfig['max_title_row'], 'start', $this->getItemExtraArg());
		$this->item_criteria->setLimit($this->itemListPageNavi->getPerpage());
		$this->item_criteria->setStart($this->itemListPageNavi->getStart());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getItemListPageNavi()
	{
		return $this->itemListPageNavi;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ǡ����ܺ٤Υڡ����ʥӥ��������ν������
// �����ƥ�ǡ���������criteria�˥�ߥåȡ��������Ȥ�����
	function setItemDetailPageNavi()
	{
		$total = $this->getCount($this->item_criteria);

		if (!is_null($this->item_id))
		{
			$page = $this->getItemPageFromID($this->item_id);
			$_GET['start'] = $page;

			$this->itemDetailPageNavi =& new XmobilePageNavigator($total, 1, 'start', $this->getItemExtraArg());
			$this->item_criteria->setLimit($this->itemDetailPageNavi->getPerpage());
			$this->item_criteria->setStart($this->itemDetailPageNavi->getStart());
		}
		else
		{
			if (isset($_GET['start']))
			{
				$page = intval($_GET['start']);
				$this->itemDetailPageNavi =& new XmobilePageNavigator($total, 1, 'start', $this->getItemExtraArg());
				$this->item_criteria->setLimit($this->itemDetailPageNavi->getPerpage());
				$this->item_criteria->setStart($this->itemDetailPageNavi->getStart());
				$itemObjectArray =& $this->getObjects($this->item_criteria);
				if (count($itemObjectArray) == 1)
				{
					$itemObject = $itemObjectArray[0];
					if (is_object($itemObject))
					{
						$this->item_id = $itemObject->getVar($this->item_id_fld);
					}
				}
			}
		}

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'setItemDetailPageNavi Limit', $this->itemDetailPageNavi->getPerpage());
		$this->utils->setDebugMessage(__CLASS__, 'setItemDetailPageNavi Start', $this->itemDetailPageNavi->getStart());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getItemDetailPageNavi()
	{
		return $this->itemDetailPageNavi;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getItemPageFromID($id)
	{
		$sql = 'SELECT '.$this->item_id_fld.' FROM '.$this->itemTableName.' WHERE '.$this->item_criteria->render();
		if (!is_null($this->item_order_fld))
		{
			$sql .= ' ORDER BY '.$this->item_order_fld;
		}
		if (is_null($this->item_order_sort))
		{
			$this->item_order_sort = $xoopsModuleConfig['title_order_sort'];
		}
		$sql .= ' '.$this->item_order_sort;
		$result = $this->db->query($sql);

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getItemPageFromID SQL', $sql);

		if (!$result)
		{
			// debug
			$this->utils->setDebugMessage(__CLASS__, 'getItemPageFromID SQL Error', $this->db->error());
//			die('DB Error : '.$this->db->error());
		}

		if ($this->db->getRowsNum($result) == 0)
		{
			return;
		}

		$page = 0;
		while ($row = $this->db->fetchArray($result))
		{
			if ($id == $row[$this->item_id_fld])
			{
				// debug
				$this->utils->setDebugMessage(__CLASS__, 'getItemPageFromID page', $page);
				return $page;
			}
			$page++;
		}

/*
		$itemObjects =& $this->getObjects($this->item_criteria);
		if (!$itemObjects)
		{
			// debug
			$this->utils->setDebugMessage(__CLASS__, 'getItemPageFromID Error', $this->getErrors());
		}
		if (count($itemObjects) > 0)
		{
			$page = 0;
			foreach($itemObjects as $itemObject)
			{
				if ($id == $itemObject->getVar($this->item_id_fld))
				{
					// debug
					$this->utils->setDebugMessage(__CLASS__, 'getItemPageFromID page', $page);
					return $page;
				}
				$page++;
			}
		}
*/
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//	function getItemCountById($id)
	function getItemCountById()
	{
//		$id = intval($id);
//		$this->setItemParameter();
		$itemCount = $this->getCount($this->item_criteria);
		return $itemCount;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���ƥ��������ɽ�����륢���ƥ���μ���
	function getChildItemCountById($id)
	{
		$ids = intval($id);
		if (!is_null($this->category_pid_fld))
		{
			$idArray = $this->categoryTree->getAllChildId($ids);
			if (count($idArray) > 0)
			{
				$ids .= ',';
				$ids .= join(',',$idArray);
			}
		}
		$ids = '('.$ids.')';
		$this->setItemCriteria();
		$criteria =& $this->item_criteria;
		$criteria->add(new Criteria($this->item_cid_fld,$ids,'IN'));
		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getChildItemCountById criteria', $criteria->render());
		if (is_object($criteria))
		{
			$itemCount = $this->getCount($criteria);
			// debug
			$this->utils->setDebugMessage(__CLASS__, 'getChildItemCountById itemCount', $itemCount);
			return $itemCount;
		}
		else
		{
			return false;
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ѥ����ȥ��ʸ������Ĵ��
	function adjustTitle($title)
	{
		global $xoopsModuleConfig;
		$myts =& MyTextSanitizer::getInstance();
		$title = $myts->makeTboxData4Show($title);
		$title = mb_strimwidth($title,0,$xoopsModuleConfig['max_title_length'],'..',SCRIPT_CODE);
		return $title;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ѥǡ���������
	function getListTitleLink($number,$id,$title,$baseUrl,$use_accesskey=true,$show_count=true)
	{
		global $xoopsModuleConfig;
		$myts =& MyTextSanitizer::getInstance();

		$title_link = '';
		$number = intval($number);
		$id = intval($id);
		$title = $myts->makeTboxData4Show($title);
		$title = mb_strimwidth($title,0,$xoopsModuleConfig['max_title_length'],'..',SCRIPT_CODE);
		$baseUrl = $myts->makeTboxData4Show($baseUrl);
//		$baseUrl = htmlspecialchars($baseUrl, ENT_QUOTES);

		if ($show_count && $xoopsModuleConfig['show_item_count'])
		{
			$item_count = $this->getChildItemCountById($id);
			$title .= sprintf(_MD_XMOBILE_NUMBER, $item_count);
		}

		if ($use_accesskey && $xoopsModuleConfig['use_accesskey'])
		{
			$title_link .= '['.$number.']';
			$title_link .= '<a href="'.$baseUrl.'" accesskey="'.$number.'">'.$title.'</a>';
		}
		else
		{
			$title_link .= '<a href="'.$baseUrl.'">'.$title.'</a>';
		}

		// debug
//		$this->utils->setDebugMessage(__CLASS__, 'getListTitleLink baseUrl', $baseUrl);

		return $title_link;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getCatPathFromId($cat_id=0)
	{
		if ($cat_id == 0) return false;

		if (!is_null($this->category_pid_fld))
		{
			$baseUrl = $this->utils->getLinkUrl('plugin','list',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
//			$catPath = $this->categoryTree->getNicePathFromId($cat_id, $baseUrl).'<hr />';
			$catPath = $this->categoryTree->getNicePathFromId($cat_id, $baseUrl);
			return $catPath;
		}
		elseif (!is_null($this->category_id_fld))
		{
			$baseUrl = $this->utils->getLinkUrl('plugin','list',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
//			$catTitle = $this->categoryTree->getTitileLinkById($cat_id, $baseUrl).'<hr />';
			$catTitle = $this->categoryTree->getTitileLinkById($cat_id, $baseUrl);
			return $catTitle;
		}
		else
		{
			return false;
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getUserLink($uid)
	{
		global $xoopsConfig;

		$uid = intval($uid);
		$member_handler =& xoops_gethandler('member');
		$user =& $member_handler->getUser($uid);
		if (is_object($user))
		{
			if (is_object($this->sessionHandler->getUser()))
			{
				// �����Ȱʳ��ˤϥ桼������ؤΥ�󥯤�ɽ��
				$extra = 'uid='.$uid;
				$baseUrl = $this->utils->getLinkUrl('userinfo','default',null,$this->sessionHandler->getSessionID(),$extra);
				$uname = '<a href="'.$baseUrl.'">'.$user->getVar('uname').'</a>';
			}
			else
			{
				// �����Ȥˤϥ桼��̾�Τ�ɽ��
				$uname = $user->getVar('uname');
			}
		}
		else
		{
			$uname = $xoopsConfig['anonymous'];
		}
		return $uname;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getEditLink()
	{
		$edit_link = '';
		if ($this->allowEdit == true)
		{
			$edit_url = $this->utils->getLinkUrl($this->controller->getActionState(),'edit',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
			$edit_link .= '<a href="'.$edit_url.'&amp;type=edit&amp;'.$this->item_id_fld.'='.$this->item_id.'">'._EDIT.'</a>&nbsp;';
		}
		if ($this->allowDelete == true)
		{
			$delete_url = $this->utils->getLinkUrl($this->controller->getActionState(),'edit',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
			$edit_link .= '<a href="'.$delete_url.'&amp;type=delete&amp;'.$this->item_id_fld.'='.$this->item_id.'">'._DELETE.'</a>';
		}
		if ($this->allowAdd == true)
		{
			if ($this->allowEdit == true || $this->allowDelete == true)
			{
				$edit_link .= '<hr />';
			}
			$add_url = $this->utils->getLinkUrl($this->controller->getActionState(),'edit',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
			$catlink = '';
			if (!is_null($this->category_id_fld) && !is_null($this->category_id))
			{
				$catlink = '&amp;'.$this->category_id_fld.'='.$this->category_id;
			}
			$edit_link .= '<a href="'.$add_url.'&amp;type=new'.$catlink.'">'._MD_XMOBILE_POSTNEW.'</a>&nbsp;';
		}
		return $edit_link;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �������ѥ�󥯤μ���
	function getCommentLink($id)
	{
		if (!is_null($this->item_comments_fld))
		{
			include_once XOOPS_ROOT_PATH.'/modules/'.basename(dirname(dirname(__FILE__))).'/class/Comments.class.php';
			$xmobile_comment =& new XmobileComments($this->controller,$this,$id,$this->category_id,$this->itemDetailPageNavi->getStart());
			$comment_link = $xmobile_comment->makeCommentLink();
			if ($comment_link)
			{
				$com_count = $xmobile_comment->com_count;
				$this->updateCommentCount($id, $com_count);
				return $comment_link;
			}
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ҥåȥ�����Ȥ��ɲ�
	function increaseHitCount($id=0)
	{
		$id =intval($id);

		if (is_null($this->item_hits_fld))
		{
			return false;
		}
		if ($id==0)
		{
			return false;
		}

		$this->mClass =& $this->get($id);
		if (!is_object($this->mClass))
		{
			return false;
		}

		$count = $this->mClass->getVar($this->item_hits_fld) + 1;
		$this->mClass->setVar($this->item_hits_fld,$count);

		if ($ret = $this->insert($this->mClass,true))
		{
			return true;
		}
		else
		{
			// debug
			$this->utils->setDebugMessage(__CLASS__, 'increaseHitCount Error', $this->getErrors());
			return false;
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ȿ����ɲ�
	function updateCommentCount($id=0,$com_count=0)
	{
		$id =intval($id);
		$com_count = intval($com_count);

		if (!$this->item_comments_fld)
		{
			return false;
		}
		if ($id==0)
		{
			return false;
		}
		if ($com_count==0)
		{
			return false;
		}

		$this->mClass =& $this->get($id);
		if (!is_object($this->mClass))
		{
			return false;
		}

		$count = $this->mClass->getVar($this->item_comments_fld) + $com_count;
		$this->mClass->setVar($this->item_comments_fld,$count);

		if ($ret = $this->insert($this->mClass,true))
		{
			return true;
		}
		else
		{
			// debug
			$this->utils->setDebugMessage(__CLASS__, 'updateCommentCount Error', $this->getErrors());
			return false;
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���ƥ���ĥ꡼���饹
require_once XOOPS_ROOT_PATH.'/modules/'.basename(dirname(dirname(__FILE__))).'/class/Tree.class.php';
class XmobileCategoryTree extends XmobileTree
{
	function XmobileCategoryTree($table_name, $id_name, $pid_name, $title_name=null, $order=null)
	{
		XmobileTree::XmobileTree($table_name, $id_name, $pid_name, $title_name, $order);
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
