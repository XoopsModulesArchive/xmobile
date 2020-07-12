<?php
if (!defined('XOOPS_ROOT_PATH')) exit();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobileRsscPlugin extends XmobilePlugin
{
	function XmobileRsscPlugin()
	{
		// call parent constructor
		XmobilePlugin::XmobilePlugin();
		// define object elements
		$this->initVar('fid', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('lid', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('uid', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('mid', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('p1', XOBJ_DTYPE_INT, '0', false);
		$this->initVar('p2', XOBJ_DTYPE_INT, '0', false);
		$this->initVar('p3', XOBJ_DTYPE_INT, '0', false);
		$this->initVar('site_title', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('site_link', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('title', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('link', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('entry_id', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('guid', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('updated_unix', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('published_unix', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('category', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('author_name', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('author_uri', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('author_email', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('type_cont', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('raws', XOBJ_DTYPE_TXTAREA, '', true);
		$this->initVar('content', XOBJ_DTYPE_TXTAREA, '', true);
		$this->initVar('search', XOBJ_DTYPE_TXTAREA, '', true);
		$this->initVar('enclosure_url', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('enclosure_type', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('enclosure_length', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('aux_int_1', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('aux_int_2', XOBJ_DTYPE_INT, '0', true);
		$this->initVar('aux_text_1', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('aux_text_2', XOBJ_DTYPE_TXTBOX, '', true, 255);

		// define primary key
		$this->setKeyFields(array('fid'));
		$this->setAutoIncrementField('fid');
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function assignSanitizerElement()
	{
		$dohtml = 1;
		$dosmiley = 1;
		$doxcode = 1;

		$this->initVar('dohtml',XOBJ_DTYPE_INT,$dohtml);
		$this->initVar('dosmiley',XOBJ_DTYPE_INT,$dosmiley);
		$this->initVar('doxcode',XOBJ_DTYPE_INT,$doxcode);
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobileRsscPluginHandler extends XmobilePluginHandler
{
	var $moduleDir = 'rssc';
	var $categoryTableName = 'rssc_link';
	var $itemTableName = 'rssc_feed';
// category parameters
	var $category_id_fld = 'lid';
	var $category_title_fld = 'title';
//	var $category_order_fld = 'lid';
// item parameters
	var $item_id_fld = 'fid';
	var $item_cid_fld = 'lid';
	var $item_title_fld = 'title';
	var $item_description_fld = 'content';
	var $item_order_fld = 'published_unix';
	var $item_date_fld = 'published_unix';
	var $item_order_sort = 'DESC';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function XmobileRsscPluginHandler($db)
	{
		XmobilePluginHandler::XmobilePluginHandler($db);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setItemCriteria()
	{
		if ($this->item_criteria == null)
		{
			$this->item_criteria =& new CriteriaCompo();
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ƥ�ǡ���������criteria���ɲ�����
// ���ƥ���ID�������ȥե�����ɡ������Ƚ������
/*
	function addItemCriteria()
	{
		parent::addItemCriteria();
		$lid = intval($this->utils->getGetPost('lid', 0));
		if ($lid != 0)
		{
			$this->item_criteria->add(new Criteria('lid', $lid));
		}
	}
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
	function getDefaultView()
	{
		parent::getListView();
	}
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setBaseUrl()
	{
		$this->baseUrl = $this->utils->getLinkUrl('plugin',$this->nextViewState,'rssc',$this->sessionHandler->getSessionID());
		// debug
		$this->utils->setDebugMessage(__CLASS__, 'setBaseUrl', $this->baseUrl);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���������μ���
// ������������ͤϥ��֥������ȤǤϤʤ�����
	function getItemList()
	{
		$this->setNextViewState('detail');
		$this->setBaseUrl();
		$this->setItemParameter();
		$this->setItemListPageNavi();

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getList criteria', $this->item_criteria->render());

		$itemObjectArray = $this->getObjects($this->item_criteria);
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
// rss�ե�������ǥ�����ͭ���ˤ���
			$itemObject->assignSanitizerElement();

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
				//�ѹ���
				$date = $itemObject->getVar($this->item_date_fld);
				$date = $this->utils->getDateShort($date);
			}

			$number = $i + 1; // �������������Ѥ��ֹ桢1���鳫��
			$item_list[$i]['key'] = $number;
			$item_list[$i]['title'] = $this->adjustTitle($title);
			$item_list[$i]['url'] = $url_parameter;
			$item_list[$i]['date'] = $date;
// ����ɽ��
			$item_list[$i]['content'] = mb_strimwidth($itemObject->getVar('content'), 0, 100, '..', SCRIPT_CODE);
// ���ȸ�URI
			$item_list[$i]['link'] = $itemObject->getVar('link');
			$i++;
		}

		return $item_list;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ܺ١������ȡ��Խ��ѥ�󥯤μ���
// ������������ͤϥ��֥������ȤǤϤʤ�HTML
	function getItemDetail()
	{
		// debug
		$this->utils->setDebugMessage(__CLASS__, 'getItemDetail criteria', $this->item_criteria->render());
		// ��դ�id�ǤϤʤ�criteria�Ǹ�������١����֥������Ȥ������֤����
		if (!$itemObjectArray = $this->getObjects($this->item_criteria))
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
			// �ѹ���
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


		$chstr = "^".XOOPS_URL."/modules/wordpress/index.php\?p";
		$repstr = XMOBILE_URL."/?act=plugin&plg=wordpress&author";
		$blog_link = ereg_replace($chstr, $repstr, $itemObject->getVar('link'));
		$detail4html .= '<hr /><a href="'.$blog_link.'">���ε����ؤΥ��</a><br />';

		// blog�����Ȥ�URL
//		if ($url !== '')
//		{
//			$detail4html .= 'url:&nbsp;'.$url.'<br />';
//		}


		return $detail4html;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
