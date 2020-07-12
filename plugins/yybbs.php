<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Xmobile�� YYBBS���ץ饰���� V0.2 bata
// -----------------------------------------------------------
// �ܥץ饰����ϡ�YYBBS��0.5994��Xmobile0.33��١����˳�ǧ���Ƥ��ޤ���
//
// ������ǽ�����¤Ȥ��ޤ���
// �������λ��ȡ�������ơ��ֿ��Ǥ��ʽ����������̤������
// ������ź�յ�ǽ�Ϥ���ޤ���
// �����٥�����ε�ǽ�Ϥ���ޤ����Xmobile��̤�����Τ����
// ���Ŀͥ����ȤǱ��Ѥ��Ƥ���ΤǺ��ϴŴŤǤ�
// ��Xmobile�ְ�������פΡ֥��ƥ����ɽ����ˡ�פǡ��֥��쥯�ȥܥå����פ�̤�б��Ǥ�
//
// �ʲ���Xmobile���Τ��ѹ������⥸�塼��Υ��åץǡ��Ȥ�¹Ԥ��Ƥ���������
// -----------------------------------------------------------

// xmobile\templates\xmobile_yybbs.html
// xmobile\plugins\yybbs.php
//  ���嵭2�ե�������ɲ�
//
// xmobile\xoops_version.php
//  ��91�����ն�:�ƥ�ץ졼�Ȥ��ɲ�
//      $modversion['templates'][32]['file'] = 'xmobile_xoopspoll.html';
//      $modversion['templates'][32]['description'] = '';
//      $modversion['templates'][33]['file'] = 'xmobile_yybbs.html';     �ʢ��ɲá�
//      $modversion['templates'][33]['description'] = '';                �ʢ��ɲá�
//     
//
// xmobile\class\Plugin.class.php
//  ��253�����ն�:�����������ƥ��������Ǽ
//      ------------------
//      ��
//      $number = $i + 1;
//      $cat_list[$i] = $category;                      �ʢ��ɲá�
//      $cat_list[$i]['key'] = $number;
//      $cat_list[$i]['title'] = $this->adjustTitle($title);
//      ��
//      ------------------
//
//  ��357�����ն�:�������������ƥ������Ǽ
//      ------------------
//      ��
//      $number = $i + 1;
//      $item_list[$i]['_itemObject'] = $itemObject;    �ʢ��ɲá�
//      $item_list[$i]['key'] = $number;
//      $item_list[$i]['title'] = $this->adjustTitle($title);
//      ��
//      ------------------
//
// xmobile\language\japanese\main.php
//  ��YYBBS�Ѥ����ʸ�ɲ�
//      define('_MD_XMOBILE_POST_SUCCESS_MSG','��Ƥ���λ���ޤ�����');
//      define('_MD_XMOBILE_GO_NEXT','���������');
//      define('_MD_XMOBILE_RES_MSG','���ֿ����ޤ���');
//
// -----------------------------------------------------------
// - �������� -
// [2007.11.08] V0.1 bata: ��������
// [2008.01.04] V0.2 bata: ��ơ��ֿ����Υѡ��ߥå��������å��ɲ�
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(!defined('XOOPS_ROOT_PATH')) exit();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobileYybbsPlugin extends XmobilePlugin
{
	function XmobileYybbsPlugin()
	{
		// call parent constructor
		XmobilePlugin::XmobilePlugin();
		// define object elements
		$this->initVar('id',			XOBJ_DTYPE_INT, '0', true);
		$this->initVar('serial',		XOBJ_DTYPE_INT, '0', true);
		$this->initVar('bbs_id',		XOBJ_DTYPE_INT, '1', true);
		$this->initVar('uid',			XOBJ_DTYPE_INT, '0', true);
		$this->initVar('name',			XOBJ_DTYPE_TXTBOX, '', true, 64);
		$this->initVar('email',			XOBJ_DTYPE_TXTBOX, '', true, 64);
		$this->initVar('url',			XOBJ_DTYPE_TXTBOX, '', true, 64);
		$this->initVar('title',			XOBJ_DTYPE_TXTBOX, '', true, 64);
		$this->initVar('message',		XOBJ_DTYPE_TXTAREA, '', true);
		$this->initVar('icon',			XOBJ_DTYPE_TXTBOX, '', true, 24);
		$this->initVar('col',			XOBJ_DTYPE_TXTBOX, '0', true, 8);
		$this->initVar('passwd',		XOBJ_DTYPE_TXTBOX, '', true, 34);
		$this->initVar('parent',		XOBJ_DTYPE_INT, '0', true);
		$this->initVar('inputdate',		XOBJ_DTYPE_INT, '0', true);
		$this->initVar('update_date',	XOBJ_DTYPE_INT, '0', true);
		$this->initVar('ip',			XOBJ_DTYPE_TXTBOX, '', true, 22);
		$this->initVar('thumb_w',		XOBJ_DTYPE_INT, '0', true);
		$this->initVar('thumb_h',		XOBJ_DTYPE_INT, '0', true);
		$this->initVar('ext',			XOBJ_DTYPE_TXTBOX, '', true, 5);

		// define primary key
		$this->setKeyFields(array('id'));
		$this->setAutoIncrementField('id');
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XmobileYybbsPluginHandler extends XmobilePluginHandler
{
//	var $moduleDir = 'yybbs';
//	var $categoryTableName = 'yybbs_bbs';
//	var $itemTableName = 'yybbs';
	var $template = 'xmobile_yybbs.html';

// category parameters
	var $category_id_fld = 'bbs_id';
	var $category_title_fld = 'title';
	var $category_order_fld = 'priority';

// item parameters
	var $item_id_fld = 'id';
	var $item_cid_fld = 'bbs_id';
	var $item_uid_fld = 'uid';
	var $item_title_fld = 'title';
	var $item_description_fld = 'message';
	var $item_order_fld = 'update_date';
	var $item_date_fld = 'update_date';
	var $item_order_sort = 'ASC';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function XmobileYybbsPluginHandler($db)
	{
		global $xoopsConfig;
		XmobilePluginHandler::XmobilePluginHandler($db);

		$pluginName = strtolower(basename(__FILE__,'.php'));
		if(!preg_match("/^\w+$/", $pluginName))
		{
			trigger_error('Invalid pluginName');
			exit();
		}
		$this->moduleDir = $pluginName;
		$this->categoryTableName = $pluginName.'_bbs';
		$this->itemTableName = $pluginName;
		
		$langFileDir = XOOPS_ROOT_PATH.'/modules/'.$this->moduleDir.'/language/'.$xoopsConfig['language'];
		$langFileName1 = $langFileDir.'/main.php';
		$langFileName2 = $langFileDir.'/modinfo.php';
		if(file_exists($langFileName1))
		{
			include_once $langFileName1;
		}
		if(file_exists($langFileName2))
		{
			include_once $langFileName2;
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �������
// ���ƥ���������ǿ��ǡ����������Խ��ѥ�󥯤�ɽ��
	function getDefaultView()
	{
		parent::getDefaultView();

		if($this->getCatList() == false){
			$this->controller->render->template->assign('lang_no_item_list',_MD_XMOBILE_NO_DATA);
		}
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ��������
// ���ƥ���������ǡ����������Խ��ѥ�󥯤�ɽ��
	function getListView()
	{
		parent::getListView();

		//�ѡ��ߥå��������å�
		if(!$this->checkPermission("post_new_thread",$this->category_id)){
			return false;
		}
		
		$editURL = $this->utils->getLinkUrl($this->controller->getActionState(),'edit',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
		$editURL .= '&'.$this->category_id_fld.'='.$this->category_id. '&proc='._MD_XMOBILE_POSTNEW.'&back_view=list';
		$editURL = preg_replace('/&amp;/i','&',$editURL);
		
		$editLink = '<form action="'.$editURL.'" method="post">';
		$editLink .= '<input type="submit" value="'._MD_XMOBILE_POSTNEW.'"></form>';
		
		$this->controller->render->template->assign('cat_link',$editLink);
		$this->controller->render->template->assign('item_link',$editLink);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ܺٲ���
// �ǡ����ܺ١������ȡ��Խ��ѥ�󥯤�ɽ��
// �ǡ����ܺ٤ϴݤ���HTML��item_detail�Ȥ��ƽ���
	function getDetailView()
	{
		parent::getDetailView();
		$this->controller->render->template->assign('item_detail_page_navi','');	//�ڡ������ܤ�id�ѥ�᥿���ʤ��ʤäƤ��ޤ����ᡢ�������ܸ�˵���ID�������Ǥ��ʤ�
		
		//�ѡ��ߥå��������å�
		if(!$this->checkPermission("post_response",$this->category_id)){
			return false;
		}

		$id = htmlspecialchars($this->utils->getGetPost($this->item_id_fld, ''), ENT_QUOTES);
		$editURL = $this->utils->getLinkUrl($this->controller->getActionState(),'edit',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
		$editURL .= '&'.$this->category_id_fld.'='.$this->category_id. '&proc='._MD_XMOBILE_REPLY.'&back_view=detail&id='.$id;
		$editURL = preg_replace('/&amp;/i','&',$editURL);
		
		$editLink = '<form action="'.$editURL.'" method="post">';
		$editLink .= '<input type="submit" value="'._MD_XMOBILE_REPLY.'"></form>';
		
		$this->controller->render->template->assign('cat_link',$editLink);
		$this->controller->render->template->assign('item_link',$editLink);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �Խ�����
	function getEditView()
	{
		$this->controller->render->template->assign('cat_list',$this->getCatList());
		$this->controller->render->template->assign('cat_list_page_navi',$this->categoryPageNavi->renderNavi());
		$this->controller->render->template->assign('item_detail',$this->renderEntryForm());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ��Ʋ���
	function getConfirmView()
	{
		$this->controller->render->template->assign('item_detail',$this->saveEntry());
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���ƥ�������μ���
// ������������ͤϥ��֥������ȤǤϤʤ�����
	function getCatList()
	{
		$categoryArray = parent::getCatList();

		//�ѡ��ߥå����ǵ��Ĥ���Ƥ���ID����Ӥ��ơ�����ʤ�Τϥϥ���
		$cat_list = array();
		$i = 0;
		foreach($categoryArray as $category)
		{
			//�Ǽ���ID�μ���
			$bbs_id = $category[$this->category_id_fld];
			//�ѡ��ߥå��������å�
			if($this->checkPermission("view_bbs",$bbs_id)){
				$cat_list[$i] = $category;
				$i++;
			}
		}
		if(!count($cat_list)) {		//�ѡ��ߥå����ˤ�ꡢɽ���������̵��
			return false;
		}
		return $cat_list;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// ���ƥ��ꥻ�쥯�ȥܥå����μ���
// ����ͤ�HTML
	function getCatSelect()
	{
		$categoryArray = $this->getCatList();

		$cat_select = '';
		$cat_select .= '<select name="'.$this->category_id.'">';
		$cat_select .= '<option value="0">----</option>';

		$i = 0;
		foreach($categoryArray as $category)
		{
			//�Ǽ���ID�μ���
			$bbs_id = $category[$this->category_id_fld];
			$title = $category['title'];

			$sel = '';
			if($bbs_id == $this->category_id)
			{
				$sel = ' selected="selected"';
			}
			$cat_select .= '<option value="'.$bbs_id.'"'.$sel.'>'.$title.'</option>';
			$i++;
		}
		$cat_select .= '</select>';
		
		if($cat_select != '')
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
		$itemObjectArray = parent::getItemList();
		//�ѡ��ߥå��������å�
		if(!$this->checkPermission("view_bbs",$this->category_id)){
			$this->controller->render->template->assign('lang_no_item_list',_MD_XMOBILE_NO_PERM_MESSAGE);
			return false;
		}

		if($itemObjectArray == false || count($itemObjectArray) == 0) // ɽ������ǡ���̵��
		{
			$this->controller->render->template->assign('lang_no_item_list',_MD_XMOBILE_NO_DATA);
			return false;
		}

		$item_list = array();
		$i = 0;
		foreach($itemObjectArray as $itemObject)
		{
			$item_list[$i] = $itemObject;

			$workObject = $itemObject['_itemObject'];

			$uid = $workObject->getVar($this->item_uid_fld);
			$uname = $this->getUserLink($uid);
			$title = $itemObject['title'];
			$item_list[$i]['title'] = $title. " (".$uname.")";
			$i++;
		}
		return $item_list;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ǿ����������μ���
// ������������ͤϥ��֥������ȤǤϤʤ�����
	function getRecentList()
	{
		return parent::getRecentList();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �����ܺ١������ȡ��Խ��ѥ�󥯤μ���
// ������������ͤϥ��֥������ȤǤϤʤ�HTML
	function getItemDetail()
	{
		//�ѡ��ߥå��������å�
		if(!$this->checkPermission("view_bbs",$this->category_id)){
			$this->controller->render->template->assign('lang_no_item_list',_MD_XMOBILE_NO_PERM_MESSAGE);
			return false;
		}

		$detail4html = parent::getItemDetail();

		$workCriteria=new CriteriaCompo();
		$workCriteria->add(new Criteria('parent', $this->item_id, '='));
		$workCriteria->setSort($this->item_date_fld);
		$workCriteria->setOrder('DESC');
		$itemObjectArray = $this->getObjects($workCriteria);

		foreach($itemObjectArray as $itemObject)
		{
			if(!is_object($itemObject))
			{
				continue;
			}

			$itemObject->assignSanitizerElement();

			$detail4html .= "<hr>";
			$detail4html .= _MD_XMOBILE_ITEM_DETAIL.'<br />';
			// �����ȥ�
			if(!is_null($this->item_title_fld))
			{
				$detail4html .= _MD_XMOBILE_TITLE;
				$detail4html .= $itemObject->getVar($this->item_title_fld).'<br />';
			}
			// �桼��̾
			if(!is_null($this->item_uid_fld))
			{
				$uid = $itemObject->getVar($this->item_uid_fld);
				$uname = $this->getUserLink($uid);
				$detail4html .= _MD_XMOBILE_CONTRIBUTOR.$uname.'<br />';
			}
			// ���ա�����
			if(!is_null($this->item_date_fld))
			{
				$date = $itemObject->getVar($this->item_date_fld);
				$detail4html .= _MD_XMOBILE_DATE.$this->utils->getDateLong($date).'<br />';
				// �ѹ���
				$detail4html .= _MD_XMOBILE_TIME.$this->utils->getTimeLong($date).'<br />';
			}
			// �ҥåȿ�
			if(!is_null($this->item_hits_fld))
			{
				$detail4html .= _MD_XMOBILE_HITS.$itemObject->getVar($this->item_hits_fld).'<br />';
				// �ҥåȥ�����Ȥ�����
				$this->increaseHitCount($this->item_id);
			}
			// ������
			if(!is_null($this->item_comments_fld))
			{
	//			$detail4html .= _MD_XMOBILE_COMMENT.$itemObject->getVar($this->item_comments_fld).'<br />';
			}
			// �ܺ�
			$description = '';
			if(!is_null($this->item_description_fld))
			{
				$description = $itemObject->getVar($this->item_description_fld);
				$detail4html .= _MD_XMOBILE_CONTENTS.'<br />';
				$detail4html .= $description.'<br />';
			}
			// ����¾��ɽ���ե������
			if(count($this->item_extra_fld) > 0)
			{
				foreach($this->item_extra_fld as $key=>$caption)
				{
					if($itemObject->getVar($key))
					{
						$detail4html .= $caption;
						$detail4html .= $itemObject->getVar($key).'<br />';
					}
				}
			}
		}
		return $detail4html;

	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function setCategoryId()
	{
		$this->category_id = $this->utils->getGetPost($this->category_id_fld, null);
		if(is_null($this->category_id) && !is_null($this->item_cid_fld))
		{
			$this->category_id = $this->utils->getGetPost($this->item_cid_fld, null);
		}

		if(!is_null($this->category_id))
		{
			$this->category_id = intval($this->category_id);
		}

		// debug
		$this->utils->setDebugMessage(__CLASS__, 'category_id', $this->category_id);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//���ƥ������ɽ����setItemParameter()�ˤǥ��åȤ���롢�����ǡ����������ˡ
	//����åɤοƤΤߤΰ�����ɽ�����뤿�ᡢ'parent'�����"0"�Τ�Τ���ɽ�����롣
	function setItemCriteria()
	{
		$this->item_criteria =& new CriteriaCompo();
		$this->item_criteria->add(new Criteria('parent', 0, '='));
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function renderEntryForm()
	{
		global $xoopsModuleConfig;
		$myts =& MyTextSanitizer::getInstance();
		$this->setItemCriteria();

		$this->ticket = new XoopsGTicket;

		$paramList = $this->getParams();
		$comment = '';
		switch($paramList["proc"])
		{
			case _MD_XMOBILE_POSTNEW:
				break;

			case _MD_XMOBILE_REPLY:
				$itemObjectArray = parent::getItemList();
				
				//�������ξ������
				$itemObject = array();
				foreach($itemObjectArray as $_itemObject)
				{
					$workObject = $_itemObject['_itemObject'];
					$uid = $workObject->getVar($this->item_id_fld);
					if($uid == $paramList["id"])
					{
						$itemObject = $_itemObject;
						break;
					}
				}
				$paramList["title"] = "Re: ".$itemObject['title'];
				$comment = _MD_XMOBILE_TITLEJ.' ['.$itemObject['title'].'] '._MD_XMOBILE_RES_MSG;
				break;
		}

		$member_handler =& xoops_gethandler('member');
		$uid = $this->sessionHandler->getUid();
		$user =& $member_handler->getUser($uid);

		if(is_object($user))
		{
			$paramList["name"] = $user->getVar('uname');
		}
//		$paramList["passwd"] = $uid;

		$submitURL = $this->utils->getLinkUrl($this->controller->getActionState(),'confirm',$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
		$submitURL = preg_replace('/&amp;/i','&',$submitURL);
		$cancelURL = $this->utils->getLinkUrl($this->controller->getActionState(),$paramList["back_view"],$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
		$cancelURL = preg_replace('/&amp;/i','&',$cancelURL);

		$innerHTML = '';
		$innerHTML .= '<strong>'.$paramList["proc"].'</strong><br />'.$comment.'<br />&nbsp;';
		$innerHTML .= '<form action="'.$submitURL.'" method="post"><div class ="form">';
		$innerHTML .= $this->ticket->getTicketHtml();
		$innerHTML .= '<input type="hidden" name="'.session_name().'" value="'.session_id().'" />';
		$innerHTML .= '<input type="hidden" name="HTTP_REFERER" value="'.$submitURL.'" />';
		$innerHTML .= $this->getHiddenParams($paramList);
		$innerHTML .= _MD_XMOBILE_NAME.'<br /><input type="text" name="name" value="'.$paramList["name"].'" /><br />';
		$innerHTML .= _MD_XMOBILE_TITLE.'<br /><input type="text" name="title" value="'.$paramList["title"].'" /><br />';
		$innerHTML .= _MD_XMOBILE_PASSWORD.'<br /><input type="text" name="passwd" value="" /><br />';
		$innerHTML .= _MD_XMOBILE_MESSAGE.'<br /><textarea rows="'.$xoopsModuleConfig['tarea_rows'].'" cols="'.$xoopsModuleConfig['tarea_cols'].'" name="message">'.$paramList["message"].'</textarea><br />';
		$innerHTML .= '<input type="submit" name="submit" value="'._SUBMIT.'" />';
		$innerHTML .= '</div></form>';
		$innerHTML .= '<form action="'.$cancelURL.'" method="post"><div class ="form">';
		$innerHTML .= $this->getHiddenParams($paramList);
		$innerHTML .= '<input type="submit" name="cancel" value="'._CANCEL.'" />';
		$innerHTML .= '</form>';

		return $innerHTML;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function saveEntry()
	{
		global $xoopsModuleConfig;
		$myts =& MyTextSanitizer::getInstance();
		$this->setItemCriteria();

		$paramList = $this->getParams();

		$this->ticket = new XoopsGTicket;
		if(!$ticket_check = $this->ticket->check(true,'',false))
		{
			return _MD_XMOBILE_TICKET_ERROR;
		}

		$uid = $this->sessionHandler->getUid();
		$now_time = time();
		
		//������ID�κ����ͼ���
		$sql = '';
		$sql .= 'select MAX('. $this->item_id_fld .') as '. $this->item_id_fld. ' from '. $this->itemTableName;
//echo "<br>SQL:".$sql. "<br>";
		if(!$ret = $this->db->query($sql))
		{
			$this->utils->setDebugMessage(__CLASS__, 'SQL error:', $this->db->error());
			return _MD_XMOBILE_INSERT_FAILED ."<br />".$sql;
		}
		$max_id = 1;
		while($row = $this->db->fetchArray($ret))
		{
			$max_id = intval($row[$this->item_id_fld]) + 1;
		}

		//�����Ǽ�����κ��絭��ID�����
		$sql = '';
		$sql .= 'select MAX(serial) as serial from '. $this->itemTableName. ' where bbs_id = '. $paramList["bbs_id"];
//echo "<br>SQL:".$sql. "<br>";
		if(!$ret = $this->db->query($sql))
		{
			$this->utils->setDebugMessage(__CLASS__, 'SQL error:', $this->db->error());
			return _MD_XMOBILE_INSERT_FAILED ."<br />".$sql;
		}
		$max_serial = 1;
		while($row = $this->db->fetchArray($ret))
		{
			$max_serial = intval($row['serial']) + 1;
		}

		//������ƤǤ⡢�ֿ��Ǥ�Ȥꤢ������Ͽ
		$_sql = "";
		$_sql .= "insert into %s ";
		$_sql .= "(id, serial, bbs_id, uid, name, url, title, message, col, passwd, parent, inputdate, update_date, ip, thumb_w, thumb_h) values ";
		$_sql .= "(%u, %u, %u, %u, '%s', 'http://', '%s', '%s', '#800000', '%s', '0', '%u', '%u', '%s', '0', '0') ";

		$sql = sprintf( $_sql, 
						$this->itemTableName,
						$max_id,
						$max_serial,
						$paramList["bbs_id"],
						$uid,
						$myts->addSlashes($paramList["name"]),
						$myts->addSlashes($paramList["title"]),
						$myts->addSlashes($paramList["message"]),
						$myts->addSlashes(md5($paramList["passwd"])),
						$now_time,
						$now_time,
						$myts->addSlashes($_SERVER['REMOTE_ADDR'])
						);
//echo "<br>SQL:".$sql. "<br>";
		$this->utils->setDebugMessage(__CLASS__, 'saveEntry SQL:', $sql);
		if(!$ret = $this->db->query($sql))
		{
			$this->utils->setDebugMessage(__CLASS__, 'SQL error:', $this->db->error());
			return _MD_XMOBILE_INSERT_FAILED ."<br />".$sql;
		}
		
		//�Ǽ��ĥơ��֥�Υ��ꥢ���ֹ湹��
		$_sql = "";
		$_sql .= "update %s set serial = %u where bbs_id = %u";
		$sql = sprintf( $_sql, $this->categoryTableName, $max_serial, $paramList["bbs_id"] );
		$this->utils->setDebugMessage(__CLASS__, 'saveEntry SQL:', $sql);
//echo "<br>SQL:".$sql. "<br>";
		if(!$ret = $this->db->query($sql))
		{
			$this->utils->setDebugMessage(__CLASS__, 'SQL error:', $this->db->error());
			return _MD_XMOBILE_INSERT_FAILED ."<br />".$sql;
		}

		switch($paramList["proc"])
		{
			case _MD_XMOBILE_POSTNEW:
			
				break;

			case _MD_XMOBILE_REPLY:
				//�ֿ����ä���硢��ʬ��parent�˿Ƥ�id������Ǽ����
				$_sql = "";
				$_sql .= "update %s a, %s b set a.parent = case when b.parent != 0 then b.parent else b.id end where a.id=%u and b.id = %u";
				$sql = sprintf( $_sql, $this->itemTableName, $this->itemTableName, $max_id, $paramList["id"] );
				$this->utils->setDebugMessage(__CLASS__, 'saveEntry SQL:', $sql);
//echo "<br>SQL:".$sql. "<br>";
				if(!$ret = $this->db->query($sql))
				{
					$this->utils->setDebugMessage(__CLASS__, 'SQL error:', $this->db->error());
					return _MD_XMOBILE_INSERT_FAILED ."<br />".$sql;
				}

				//�ֿ����ä���硢��ʬ�οƤ�update_date�򹹿�����
				$_sql = "";
				$_sql .= "update %s a, %s b set a.update_date = b.update_date where a.id = b.parent and b.id = %u";
				$sql = sprintf( $_sql, $this->itemTableName, $this->itemTableName, $max_id );
				$this->utils->setDebugMessage(__CLASS__, 'saveEntry SQL:', $sql);
//echo "<br>SQL:".$sql. "<br>";
				if(!$ret = $this->db->query($sql))
				{
					$this->utils->setDebugMessage(__CLASS__, 'SQL error:', $this->db->error());
					return _MD_XMOBILE_INSERT_FAILED ."<br />".$sql;
				}
				
				break;
		}

/*
		// ���Υ᡼������
		$notify =& xoops_gethandler('notification');
		$pageuri = sprintf('%s/modules/yybbs/viewbbs.php?bbs_id=%d',XOOPS_URL,$obj->getVar('bbs_id'));
		$tags = array('BBS_TITLE'=>$bbs->getVar('title'),
		      'NAME'=>$obj->getVar('name'),
		      'TITLE'=>$obj->getVar('title'),
		      'PAGE_URI'=>$pageuri);
		$notify->triggerEvent('yybbs_bbs', $obj->getVar('bbs_id'), 'entry', $tags);
		$notify->triggerEvent('yybbs', 0, 'entry', $tags);
*/

		$nextURL = $this->utils->getLinkUrl($this->controller->getActionState(),$paramList["back_view"],$this->controller->getPluginState(),$this->sessionHandler->getSessionID());
		$nextURL .= $this->getHiddenURL($paramList);
		$nextURL = preg_replace('/&amp;/i','&',$nextURL);

		$innerHTML = "";
		$innerHTML .= _MD_XMOBILE_POST_SUCCESS_MSG ."<br />&nbsp;<br />";
		$innerHTML .= '<a href="'.$nextURL . '">'._MD_XMOBILE_GO_NEXT.'</a>';
		return $innerHTML;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function getParams()
	{
		$pramaNames = array("name", "title", "message", "id", "bbs_id", "parent", "passwd", "proc", "submit", "cancel", "back_view");

		$paramList = array();
		foreach($pramaNames as $name)
		{
			$value = htmlspecialchars($this->utils->getGetPost($name, ''), ENT_QUOTES);
			$paramList[$name] = $value;

			// debug
			$this->utils->setDebugMessage(__CLASS__, 'getParams ', $name. "=". $value);
		}
		return $paramList;
	}
	
	function getHiddenParams($paramList)
	{
		$hiddenHTML = "";
		foreach ($paramList as $name => $value)
		{
			if($value != ""){
				$hiddenHTML .= '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
			}
		}
		return $hiddenHTML;
	}

	function getHiddenURL($paramList)
	{
		$hiddenURL = "";
		foreach ($paramList as $name => $value)
		{
			if($value != ""){
				$hiddenURL  .= '&'.$name.'='.$value;
			}
		}
		return $hiddenURL;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function checkPermission($perm_name, $category_id)
	{
		require_once XOOPS_ROOT_PATH."/modules/".$this->moduleDir."/class/global.php";
		require_once XOOPS_ROOT_PATH."/modules/exFrame/frameloader.php";
		require_once XOOPS_ROOT_PATH."/modules/exFrame/xoops/perm.php";

		$xoopsUser =& $this->sessionHandler->getUser();
		$criteria=new CriteriaCompo();
		$module_handler =& xoops_gethandler('module');
		$config_handler =& xoops_gethandler('config');
		$xoopsYybbsModule =& $module_handler->getByDirname($this->moduleDir);
		$xoopsYybbsModuleConfig =& $config_handler->getConfigsByCat(0,$xoopsYybbsModule->getVar('mid'));

		// �ѡ��ߥå�������¤�����е��Ĥ���Ƥ��� BBS ��Ĵ�٤�
		if($xoopsYybbsModuleConfig['permission']) {
			if(!exPerm::Guard($perm_name)) {
				$handler=&exXoopsGroupPermHandler::getInstance();

				$pCriteria=new CriteriaCompo();
				$pCriteria->add(new Criteria('gperm_modid',$xoopsYybbsModule->mid()));
				$pCriteria->add(new Criteria('gperm_name',$perm_name));
				$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
				$pCriteria->add(new criteria('gperm_groupid', '('.implode(",", $groups).')', 'IN'));
			
				$objs=&$handler->getObjects($pCriteria);
				if(!count($objs)) {		//�ѡ��ߥå����ˤ�ꡢɽ���������̵��
					return false;
				} else {
		    		foreach($objs as $obj) {
		    			$criteria->add(new Criteria('bbs_id',$obj->getVar('gperm_itemid')), 'OR');
		    		}
				}
			}
		}

		//�ѡ��ߥå������Ĥ���Ƥ���Ǽ���ID����������
		$handler=&YYBBS::getHandler('bbs');
		$objs=&$handler->getObjects($criteria,null,'priority');

		foreach($objs as $obj)
		{
			$ok_bbs_id = $obj->getVar($this->category_id_fld);
			if($ok_bbs_id == $category_id){
				return true;
			}
		}
		return false;
	}

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �ڡ����ʥӥ���������ѥѥ�᡼���μ���
/*	function getItemExtraArg()
	{
		// $extra���ͤ�getLinkUrl()��htmlspecialchars()��ݤ�����Τ�&amp;�ǤϤʤ�&�ȵ��Ҥ��Ƥ���
		$extra = parent::getItemExtraArg();
		//�����Ȥε���ID�����ꤹ��
		$id = htmlspecialchars($this->utils->getGetPost($this->item_id_fld, ''), ENT_QUOTES);
		if($id != ""){
			$extra .= '&amp;'.$this->item_id_fld.'='.$id;
		}
		echo "=======>".$extra;
		return $extra;
	}
*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
