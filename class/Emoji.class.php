<?php
/**
* �����ƥ�̾    �����ӳ�ʸ����ư�Ѵ�
* �ץ����̾  ��MobileClass
*
* :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :
* [�ץ���೵��]
* DoCoMo���������Ϥ�����ʸ���򡢥����������Ƥ�������ꥢ�˹�碌��
* ��ưŪ�˸ߴ����볨ʸ��(������)���ִ����ޤ���
* DoCoMo��ʸ�������Ϥϡ��ؿ��ΰ����˳�ʸ�����ϥ��եȤ�Ȥä�ľ�����Ϥ��뤫��
* 16��ˡ�������Ϳ������ˤ��¸����ޤ�(�侩��16��ˡ�Ǥ�)
*
* [�ƽи�]
* Nothing
*
* [�ƽ���]
* Nothing
*
* [�ѥ�᡼��]
* Nothing
* :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :
*
* @since            2006/11/20
* @auther           T.Kotaka
*
* @version          1.2.0
*
* [��������]
* 000001    2007/01/22    16��ˡ�ˤ�����Ϥ򥵥ݡ��Ȥ��ޤ�����
* 000002    2007/01/22    �桼��������������Ȥ���SoftBank�פκݤˡ���ʸ���Ѵ�����ʤ��Զ��������ޤ�����
* 000003    2007/01/23    EzWeb�ˤ����ơ���ʸ��������ʸ�������Ͻ���ʤ��Զ��������ޤ�����
*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// �嵭���ܤ�xmbile�����˲�¤
class XmobileEmoji
{
	var $EMOJI         = array();     // ��ʸ���ơ��֥�
	var $InputMode     = 0;           // 0 Or 1 ��0���Х��ʥ����ϡ�1����ʸ��ľ�����ϡ�

	var $docomo_char = array();
	var $au_char = array();
	var $softbank_char = array();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ���󥹥ȥ饯��
	function XmobileEmoji()
	{
		$this->__construct();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ���󥹥ȥ饯��(PHP5�б�)
	function __construct()
	{
		// ��ʸ���ơ��֥륻�å�
		$this->_EmojiTable();
		// �桼��������������ȥ��å�
//		$this->getUserAgent();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// �����ǻ��ꤵ�줿ʸ����򡢥���������Ȥˤ��碌�Ƴ�ʸ���Ѵ�
	function convertStr($str_input='', $carrier=0)
	{
		$before_str = array();
		$after_str  = array();

		foreach ($this->EMOJI as $code=>$conv_array)
		{
			$before_str[] = '[%'.$code.'%]';
			$after_str[] = $this->convert($code, $carrier);
		}

		return str_replace($before_str, $after_str, $str_input);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// �����ǻ��ꤵ�줿ʸ����򡢥���������Ȥˤ��碌�Ƴ�ʸ���Ѵ�
	function convert($InputEmoji='', $carrier=0)
	{
		switch ($this->InputMode)
		{
			case 0:
				$InputEmoji = strtoupper($InputEmoji);
				break;
			case 1:
				$InputEmoji = strtoupper(bin2hex($InputEmoji));
				break;
			default:
				break;
		}

		switch ($carrier)
		{
			case 1:
				// DoCoMo
				$InputEmoji = pack("H*",$InputEmoji);
				break;
			case 2:
				// EzWeb
				$InputEmoji = is_numeric($this->EMOJI[$InputEmoji]['EzWeb'])?"<img localsrc=" . $this->EMOJI[$InputEmoji]['EzWeb'] . ">":$this->EMOJI[$InputEmoji]['EzWeb'];
				break;
			case 3:
				// SoftBank
				$InputEmoji = $this->EMOJI[$InputEmoji]['SB'];
				break;
			default:
				// PC
				$InputEmoji = "<img src='./images/emoji/" . $InputEmoji . ".gif'>";
				break;
		}

		return $InputEmoji;
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function _EmojiTable()
	{
		$this->EMOJI['F89F'] = array('TIT' => '����', 'EzWeb' => '44', 'SB' => '$Gj');
		$this->EMOJI['F8A0'] = array('TIT' => '�ޤ�', 'EzWeb' => '107', 'SB' => '$Gi');
		$this->EMOJI['F8A1'] = array('TIT' => '��', 'EzWeb' => '95', 'SB' => '$Gk');
		$this->EMOJI['F8A2'] = array('TIT' => '��', 'EzWeb' => '191', 'SB' => '$Gh');
		$this->EMOJI['F8A3'] = array('TIT' => '��', 'EzWeb' => '16', 'SB' => '$E]');
		$this->EMOJI['F8A4'] = array('TIT' => '����', 'EzWeb' => '190', 'SB' => '$Pc');
		$this->EMOJI['F8A5'] = array('TIT' => '̸', 'EzWeb' => '305', 'SB' => '[̸]');
		$this->EMOJI['F8A6'] = array('TIT' => '����', 'EzWeb' => '481', 'SB' => '$P\');
		$this->EMOJI['F8A7'] = array('TIT' => '���Ӻ�', 'EzWeb' => '192', 'SB' => '$F_');
		$this->EMOJI['F8A8'] = array('TIT' => '�����', 'EzWeb' => '193', 'SB' => '$F`');
		$this->EMOJI['F8A9'] = array('TIT' => '�лҺ�', 'EzWeb' => '194', 'SB' => '$Fa');
		$this->EMOJI['F8AA'] = array('TIT' => '����', 'EzWeb' => '195', 'SB' => '$Fb');
		$this->EMOJI['F8AB'] = array('TIT' => '��Һ�', 'EzWeb' => '196', 'SB' => '$Fc');
		$this->EMOJI['F8AC'] = array('TIT' => '������', 'EzWeb' => '197', 'SB' => '$Fd');
		$this->EMOJI['F8AD'] = array('TIT' => 'ŷ���', 'EzWeb' => '198', 'SB' => '$Fe');
		$this->EMOJI['F8AE'] = array('TIT' => '긺�', 'EzWeb' => '199', 'SB' => '$Ff');
		$this->EMOJI['F8AF'] = array('TIT' => '�ͼ��', 'EzWeb' => '200', 'SB' => '$Fg');
		$this->EMOJI['F8B0'] = array('TIT' => '���Ӻ�', 'EzWeb' => '201', 'SB' => '$Fh');
		$this->EMOJI['F8B1'] = array('TIT' => '���Ӻ�', 'EzWeb' => '202', 'SB' => '$Fi');
		$this->EMOJI['F8B2'] = array('TIT' => '����', 'EzWeb' => '203', 'SB' => '$Fj');
		$this->EMOJI['F8B3'] = array('TIT' => '���ݡ���', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F8B4'] = array('TIT' => '���', 'EzWeb' => '45', 'SB' => '$G6');
		$this->EMOJI['F8B5'] = array('TIT' => '�����', 'EzWeb' => '306', 'SB' => '$G4');
		$this->EMOJI['F8B6'] = array('TIT' => '�ƥ˥�', 'EzWeb' => '220', 'SB' => '$G5');
		$this->EMOJI['F8B7'] = array('TIT' => '���å���', 'EzWeb' => '219', 'SB' => '$G8');
		$this->EMOJI['F8B8'] = array('TIT' => '������', 'EzWeb' => '421', 'SB' => '$G3');
		$this->EMOJI['F8B9'] = array('TIT' => '�Х����åȥܡ���', 'EzWeb' => '307', 'SB' => '$PJ');
		$this->EMOJI['F8BA'] = array('TIT' => '�⡼�������ݡ���', 'EzWeb' => '222', 'SB' => '$ER');
		$this->EMOJI['F8BB'] = array('TIT' => '�ݥ��åȥ٥�', 'EzWeb' => '308', 'SB' => '[PB]');
		$this->EMOJI['F8BC'] = array('TIT' => '�ż�', 'EzWeb' => '172', 'SB' => '$G>');
		$this->EMOJI['F8BD'] = array('TIT' => '�ϲ�Ŵ', 'EzWeb' => '341', 'SB' => '$PT');
		$this->EMOJI['F8BE'] = array('TIT' => '������', 'EzWeb' => '217', 'SB' => '$PU');
		$this->EMOJI['F8BF'] = array('TIT' => '�֡ʥ������', 'EzWeb' => '125', 'SB' => '$G;');
		$this->EMOJI['F8C0'] = array('TIT' => '�֡ʣң֡�', 'EzWeb' => '125', 'SB' => '$PN');
		$this->EMOJI['F8C1'] = array('TIT' => '�Х�', 'EzWeb' => '216', 'SB' => '$Ey');
		$this->EMOJI['F8C2'] = array('TIT' => '��', 'EzWeb' => '379', 'SB' => '$F"');
		$this->EMOJI['F8C3'] = array('TIT' => '���Ե�', 'EzWeb' => '168', 'SB' => '$G=');
		$this->EMOJI['F8C4'] = array('TIT' => '��', 'EzWeb' => '112', 'SB' => '$GV');
		$this->EMOJI['F8C5'] = array('TIT' => '�ӥ�', 'EzWeb' => '156', 'SB' => '$GX');
		$this->EMOJI['F8C6'] = array('TIT' => '͹�ض�', 'EzWeb' => '375', 'SB' => '$Es');
		$this->EMOJI['F8C7'] = array('TIT' => '�±�', 'EzWeb' => '376', 'SB' => '$Eu');
		$this->EMOJI['F8C8'] = array('TIT' => '���', 'EzWeb' => '212', 'SB' => '$Em');
		$this->EMOJI['F8C9'] = array('TIT' => '���ԣ�', 'EzWeb' => '205', 'SB' => '$Et');
		$this->EMOJI['F8CA'] = array('TIT' => '�ۥƥ�', 'EzWeb' => '378', 'SB' => '$Ex');
		$this->EMOJI['F8CB'] = array('TIT' => '����ӥ�', 'EzWeb' => '206', 'SB' => '$Ev');
		$this->EMOJI['F8CC'] = array('TIT' => '������󥹥����', 'EzWeb' => '213', 'SB' => '$GZ');
		$this->EMOJI['F8CD'] = array('TIT' => '��־�', 'EzWeb' => '208', 'SB' => '$Eo');
		$this->EMOJI['F8CE'] = array('TIT' => '����', 'EzWeb' => '99', 'SB' => '$En');
		$this->EMOJI['F8CF'] = array('TIT' => '�ȥ���', 'EzWeb' => '207', 'SB' => '$Eq');
		$this->EMOJI['F8D0'] = array('TIT' => '�쥹�ȥ��', 'EzWeb' => '146', 'SB' => '$Gc');
		$this->EMOJI['F8D1'] = array('TIT' => '����Ź', 'EzWeb' => '93', 'SB' => '$Ge');
		$this->EMOJI['F8D2'] = array('TIT' => '�С�', 'EzWeb' => '52', 'SB' => '$Gd');
		$this->EMOJI['F8D3'] = array('TIT' => '�ӡ���', 'EzWeb' => '65', 'SB' => '$Gg');
		$this->EMOJI['F8D4'] = array('TIT' => '�ե������ȥա���', 'EzWeb' => '245', 'SB' => '$E@');
		$this->EMOJI['F8D5'] = array('TIT' => '�֥ƥ��å�', 'EzWeb' => '124', 'SB' => '$E^');
		$this->EMOJI['F8D6'] = array('TIT' => '���Ʊ�', 'EzWeb' => '104', 'SB' => '$O3');
		$this->EMOJI['F8D7'] = array('TIT' => '���饪��', 'EzWeb' => '289', 'SB' => '$G\');
		$this->EMOJI['F8D8'] = array('TIT' => '�ǲ�', 'EzWeb' => '110', 'SB' => '$G]');
		$this->EMOJI['F8D9'] = array('TIT' => '���Ф��', 'EzWeb' => '70', 'SB' => '$FV');
		$this->EMOJI['F8DA'] = array('TIT' => 'ͷ����', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F8DB'] = array('TIT' => '����', 'EzWeb' => '294', 'SB' => '$O*');
		$this->EMOJI['F8DC'] = array('TIT' => '������', 'EzWeb' => '309', 'SB' => '$Q"');
		$this->EMOJI['F8DD'] = array('TIT' => '���', 'EzWeb' => '494', 'SB' => '$Q#');
		$this->EMOJI['F8DE'] = array('TIT' => '���٥��', 'EzWeb' => '311', 'SB' => '-');
		$this->EMOJI['F8DF'] = array('TIT' => '�����å�', 'EzWeb' => '106', 'SB' => '$EE');
		$this->EMOJI['F8E0'] = array('TIT' => '�ʱ�', 'EzWeb' => '176', 'SB' => '$O.');
		$this->EMOJI['F8E1'] = array('TIT' => '�ر�', 'EzWeb' => '177', 'SB' => '$F(');
		$this->EMOJI['F8E2'] = array('TIT' => '�����', 'EzWeb' => '94', 'SB' => '$G(');
		$this->EMOJI['F8E3'] = array('TIT' => '���Х�', 'EzWeb' => '83', 'SB' => '$OC');
		$this->EMOJI['F8E4'] = array('TIT' => '��', 'EzWeb' => '122', 'SB' => '$Eh');
		$this->EMOJI['F8E5'] = array('TIT' => '��ܥ�', 'EzWeb' => '312', 'SB' => '$O4');
		$this->EMOJI['F8E6'] = array('TIT' => '�ץ쥼���', 'EzWeb' => '144', 'SB' => '$E2');
		$this->EMOJI['F8E7'] = array('TIT' => '�С����ǡ�', 'EzWeb' => '313', 'SB' => '$Ok');
		$this->EMOJI['F8E8'] = array('TIT' => '����', 'EzWeb' => '85', 'SB' => '$G)');
		$this->EMOJI['F8E9'] = array('TIT' => '��������', 'EzWeb' => '161', 'SB' => '$G*');
		$this->EMOJI['F8EA'] = array('TIT' => '���', 'EzWeb' => '395', 'SB' => '$O!');
		$this->EMOJI['F8EB'] = array('TIT' => '�ԣ�', 'EzWeb' => '288', 'SB' => '$EJ');
		$this->EMOJI['F8EC'] = array('TIT' => '������', 'EzWeb' => '232', 'SB' => '[������]');
		$this->EMOJI['F8ED'] = array('TIT' => '�ã�', 'EzWeb' => '300', 'SB' => '$EF');
		$this->EMOJI['F8EE'] = array('TIT' => '�ϡ���', 'EzWeb' => '414', 'SB' => '$F,');
		$this->EMOJI['F8EF'] = array('TIT' => '���ڡ���', 'EzWeb' => '314', 'SB' => '$F.');
		$this->EMOJI['F8F0'] = array('TIT' => '������', 'EzWeb' => '315', 'SB' => '$F-');
		$this->EMOJI['F8F1'] = array('TIT' => '�����', 'EzWeb' => '316', 'SB' => '$F/');
		$this->EMOJI['F8F2'] = array('TIT' => '��', 'EzWeb' => '317', 'SB' => '$P9');
		$this->EMOJI['F8F3'] = array('TIT' => '��', 'EzWeb' => '318', 'SB' => '$P;');
		$this->EMOJI['F8F4'] = array('TIT' => '��ʥ�����', 'EzWeb' => '817', 'SB' => '$G0');
		$this->EMOJI['F8F5'] = array('TIT' => '��ʥ��祭��', 'EzWeb' => '319', 'SB' => '$G1');
		$this->EMOJI['F8F6'] = array('TIT' => '��ʥѡ���', 'EzWeb' => '320', 'SB' => '$G2');
		$this->EMOJI['F8F7'] = array('TIT' => '���Ф᲼', 'EzWeb' => '43', 'SB' => '$FX');
		$this->EMOJI['F8F8'] = array('TIT' => '���Ф��', 'EzWeb' => '42', 'SB' => '$FW');
		$this->EMOJI['F8F9'] = array('TIT' => '­', 'EzWeb' => '728', 'SB' => '$QV');
		$this->EMOJI['F8FA'] = array('TIT' => '����', 'EzWeb' => '729', 'SB' => '$G\'');
		$this->EMOJI['F8FB'] = array('TIT' => '���', 'EzWeb' => '116', 'SB' => '[�ᥬ��]');
		$this->EMOJI['F8FC'] = array('TIT' => '�ְػ�', 'EzWeb' => '178', 'SB' => '$F*');
		$this->EMOJI['F940'] = array('TIT' => '����', 'EzWeb' => '321', 'SB' => '��');
		$this->EMOJI['F941'] = array('TIT' => '���礱��', 'EzWeb' => '322', 'SB' => '$Gl');
		$this->EMOJI['F942'] = array('TIT' => 'Ⱦ��', 'EzWeb' => '323', 'SB' => '$Gl');
		$this->EMOJI['F943'] = array('TIT' => '������', 'EzWeb' => '15', 'SB' => '$Gl');
		$this->EMOJI['F944'] = array('TIT' => '����', 'EzWeb' => '��', 'SB' => '��');
		$this->EMOJI['F945'] = array('TIT' => '��', 'EzWeb' => '134', 'SB' => '$Gr');
		$this->EMOJI['F946'] = array('TIT' => 'ǭ', 'EzWeb' => '251', 'SB' => '$Go');
		$this->EMOJI['F947'] = array('TIT' => '�꥾����', 'EzWeb' => '169', 'SB' => '$G<');
		$this->EMOJI['F948'] = array('TIT' => '���ꥹ�ޥ�', 'EzWeb' => '234', 'SB' => '$GS');
		$this->EMOJI['F949'] = array('TIT' => '���Ф᲼', 'EzWeb' => '71', 'SB' => '$FY');
		$this->EMOJI['F950'] = array('TIT' => '������', 'EzWeb' => '226', 'SB' => '$OD');
		$this->EMOJI['F951'] = array('TIT' => '�դ���', 'EzWeb' => '[�դ���]', 'SB' => '[�դ���]');
		$this->EMOJI['F952'] = array('TIT' => '�ڥ�', 'EzWeb' => '508', 'SB' => '�Υڥ��');
		$this->EMOJI['F955'] = array('TIT' => '�ͱ�', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F956'] = array('TIT' => '����', 'EzWeb' => '[����]', 'SB' => '$E?');
		$this->EMOJI['F957'] = array('TIT' => '��', 'EzWeb' => '490', 'SB' => '$Pk');
		$this->EMOJI['F95E'] = array('TIT' => '����', 'EzWeb' => '46', 'SB' => '$GM');
		$this->EMOJI['F972'] = array('TIT' => 'phone to', 'EzWeb' => '513', 'SB' => '$E$');
		$this->EMOJI['F973'] = array('TIT' => 'mail to', 'EzWeb' => '784', 'SB' => '$E#');
		$this->EMOJI['F974'] = array('TIT' => 'fax to', 'EzWeb' => '166', 'SB' => '$G+');
		$this->EMOJI['F975'] = array('TIT' => 'i�⡼��', 'EzWeb' => '[i�⡼��]', 'SB' => '[i�⡼��]');
		$this->EMOJI['F976'] = array('TIT' => 'i�⡼�ɡ����դ���', 'EzWeb' => '[i�⡼��]', 'SB' => '[i�⡼��]');
		$this->EMOJI['F977'] = array('TIT' => '�᡼��', 'EzWeb' => '108', 'SB' => '$E#');
		$this->EMOJI['F978'] = array('TIT' => '�ɥ�����', 'EzWeb' => '[�ɥ���]', 'SB' => '[�ɥ���]');
		$this->EMOJI['F979'] = array('TIT' => '�ɥ���ݥ����', 'EzWeb' => '[�ɥ���ݥ����]', 'SB' => '[�ɥ���ݥ����]');
		$this->EMOJI['F97A'] = array('TIT' => 'ͭ��', 'EzWeb' => '109', 'SB' => '��');
		$this->EMOJI['F97B'] = array('TIT' => '̵��', 'EzWeb' => '299', 'SB' => '�Σƣңţš�');
		$this->EMOJI['F97D'] = array('TIT' => '�ѥ����', 'EzWeb' => '120', 'SB' => '$G_');
		$this->EMOJI['F97E'] = array('TIT' => '����ͭ', 'EzWeb' => '118', 'SB' => '-');
		$this->EMOJI['F980'] = array('TIT' => '���ꥢ', 'EzWeb' => '324', 'SB' => '[CL]');
		$this->EMOJI['F981'] = array('TIT' => '��������Ĵ�٤��', 'EzWeb' => '119', 'SB' => '$E4');
		$this->EMOJI['F982'] = array('TIT' => '�Σţ�', 'EzWeb' => '334', 'SB' => '$F2');
		$this->EMOJI['F983'] = array('TIT' => '���־���', 'EzWeb' => '730', 'SB' => '-');
		$this->EMOJI['F984'] = array('TIT' => '�ե꡼�������', 'EzWeb' => '�֥ե꡼�������]', 'SB' => '$F1');
		$this->EMOJI['F985'] = array('TIT' => '���㡼�ץ������', 'EzWeb' => '818', 'SB' => '$F0');
		$this->EMOJI['F986'] = array('TIT' => '��У�', 'EzWeb' => '4', 'SB' => '[Q]');
		$this->EMOJI['F987'] = array('TIT' => '1', 'EzWeb' => '180', 'SB' => '$F<');
		$this->EMOJI['F988'] = array('TIT' => '2', 'EzWeb' => '181', 'SB' => '$F=');
		$this->EMOJI['F989'] = array('TIT' => '3', 'EzWeb' => '182', 'SB' => '$F>');
		$this->EMOJI['F98A'] = array('TIT' => '4', 'EzWeb' => '183', 'SB' => '$F?');
		$this->EMOJI['F98B'] = array('TIT' => '5', 'EzWeb' => '184', 'SB' => '$F@');
		$this->EMOJI['F98C'] = array('TIT' => '6', 'EzWeb' => '185', 'SB' => '$FA');
		$this->EMOJI['F98D'] = array('TIT' => '7', 'EzWeb' => '186', 'SB' => '$FB');
		$this->EMOJI['F98E'] = array('TIT' => '8', 'EzWeb' => '187', 'SB' => '$FC');
		$this->EMOJI['F98F'] = array('TIT' => '9', 'EzWeb' => '188', 'SB' => '$FD');
		$this->EMOJI['F990'] = array('TIT' => '0', 'EzWeb' => '325', 'SB' => '$FE');
		$this->EMOJI['F9B0'] = array('TIT' => '����', 'EzWeb' => '326', 'SB' => '$Fm');
		$this->EMOJI['F991'] = array('TIT' => '���ϡ���', 'EzWeb' => '51', 'SB' => '$GB');
		$this->EMOJI['F993'] = array('TIT' => '����', 'EzWeb' => '265', 'SB' => '$GC');
		$this->EMOJI['F994'] = array('TIT' => '�ϡ��Ȥ�����ʣ���ϡ��ȡ�', 'EzWeb' => '266', 'SB' => '$OG');
		$this->EMOJI['F995'] = array('TIT' => '����ʴ򤷤����', 'EzWeb' => '257', 'SB' => '$Gw');
		$this->EMOJI['F996'] = array('TIT' => '���á��ܤä����', 'EzWeb' => '258', 'SB' => '$Gy');
		$this->EMOJI['F997'] = array('TIT' => '�������������������', 'EzWeb' => '441', 'SB' => '$Gx');
		$this->EMOJI['F998'] = array('TIT' => '�⤦��������ᤷ�����', 'EzWeb' => '444', 'SB' => '$P\'');
		$this->EMOJI['F999'] = array('TIT' => '�դ�դ�', 'EzWeb' => '327', 'SB' => '$P&');
		$this->EMOJI['F99A'] = array('TIT' => '���åɡʾ���������', 'EzWeb' => '731', 'SB' => '$FV');
		$this->EMOJI['F99B'] = array('TIT' => '�����', 'EzWeb' => '343', 'SB' => '$G^');
		$this->EMOJI['F99C'] = array('TIT' => '������ʬ�ʲ�����', 'EzWeb' => '224', 'SB' => '$EC');
		$this->EMOJI['F99D'] = array('TIT' => '���襤��', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F99E'] = array('TIT' => '�����ޡ���', 'EzWeb' => '273', 'SB' => '$G#');
		$this->EMOJI['F99F'] = array('TIT' => '�Ԥ��Ԥ��ʿ�������', 'EzWeb' => '420', 'SB' => '$ON');
		$this->EMOJI['F9A0'] = array('TIT' => '�Ҥ�᤭', 'EzWeb' => '77', 'SB' => '$E/');
		$this->EMOJI['F9A1'] = array('TIT' => '�फ�á��ܤ��', 'EzWeb' => '262', 'SB' => '$OT');
		$this->EMOJI['F9A2'] = array('TIT' => '�ѥ��', 'EzWeb' => '281', 'SB' => '$G-');
		$this->EMOJI['F9A3'] = array('TIT' => '����', 'EzWeb' => '268', 'SB' => '$O1');
		$this->EMOJI['F9A4'] = array('TIT' => '�ࡼ��', 'EzWeb' => '291', 'SB' => '$OF');
		$this->EMOJI['F9A5'] = array('TIT' => '�Хåɡʲ����������', 'EzWeb' => '732', 'SB' => '$FX');
		$this->EMOJI['F9A6'] = array('TIT' => '̲��(��̲)', 'EzWeb' => '261', 'SB' => '$E\');
		$this->EMOJI['F9A7'] = array('TIT' => 'exclamation', 'EzWeb' => '2', 'SB' => '$GA');
		$this->EMOJI['F9A8'] = array('TIT' => 'exclamation&question', 'EzWeb' => '733', 'SB' => '����');
		$this->EMOJI['F9A9'] = array('TIT' => 'exclamation��2', 'EzWeb' => '734', 'SB' => '����');
		$this->EMOJI['F9AA'] = array('TIT' => '�ɤ�áʾ׷��', 'EzWeb' => '329', 'SB' => '-');
		$this->EMOJI['F9AB'] = array('TIT' => '�������������ӻ������', 'EzWeb' => '330', 'SB' => '$OQ');
		$this->EMOJI['F9AC'] = array('TIT' => '���顼�áʴ���', 'EzWeb' => '263', 'SB' => '$OQ');
		$this->EMOJI['F9AD'] = array('TIT' => '���å��������Ф����ޡ�', 'EzWeb' => '282', 'SB' => '$OP');
		$this->EMOJI['F9AE'] = array('TIT' => '����Ĺ�����棱��', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F9AF'] = array('TIT' => '����Ĺ�����棲��', 'EzWeb' => '735', 'SB' => '-');
		$this->EMOJI['F9B1'] = array('TIT' => 'i���ץ�', 'EzWeb' => '[�饢�ץ�]', 'SB' => '[�饢�ץ�]');
		$this->EMOJI['F9B2'] = array('TIT' => 'i���ץ�����դ���', 'EzWeb' => '[�饢�ץ�]', 'SB' => '[�饢�ץ�]');
		$this->EMOJI['F9B3'] = array('TIT' => 'T����ġʥܡ�������', 'EzWeb' => '335', 'SB' => '$G&');
		$this->EMOJI['F9B4'] = array('TIT' => '���޸�����', 'EzWeb' => '290', 'SB' => '[����]');
		$this->EMOJI['F9B5'] = array('TIT' => '����', 'EzWeb' => '295', 'SB' => '$O<');
		$this->EMOJI['F9B6'] = array('TIT' => '������', 'EzWeb' => '805', 'SB' => '[������]');
		$this->EMOJI['F9B7'] = array('TIT' => '���Υ�', 'EzWeb' => '221', 'SB' => '[���Υ�]');
		$this->EMOJI['F9B8'] = array('TIT' => '����ڥ�', 'EzWeb' => '48', 'SB' => '$OE');
		$this->EMOJI['F9B9'] = array('TIT' => '�ɥ�', 'EzWeb' => '[�ɥ�]', 'SB' => '[�ɥ�]');
		$this->EMOJI['F9BA'] = array('TIT' => '�ɥ���', 'EzWeb' => '233', 'SB' => '$EO');
		$this->EMOJI['F9BB'] = array('TIT' => '�ѥ�����', 'EzWeb' => '337', 'SB' => '$G,');
		$this->EMOJI['F9BC'] = array('TIT' => '��֥쥿��', 'EzWeb' => '806', 'SB' => '$E#');
		$this->EMOJI['F9BD'] = array('TIT' => '����', 'EzWeb' => '152', 'SB' => '[����]');
		$this->EMOJI['F9BE'] = array('TIT' => '��ɮ', 'EzWeb' => '149', 'SB' => '$O!');
		$this->EMOJI['F9BF'] = array('TIT' => '����', 'EzWeb' => '354', 'SB' => '$E.');
		$this->EMOJI['F9C0'] = array('TIT' => '����', 'EzWeb' => '72', 'SB' => '$GT');
		$this->EMOJI['F9C1'] = array('TIT' => '������', 'EzWeb' => '58', 'SB' => '[������]');
		$this->EMOJI['F9C2'] = array('TIT' => '��ž��', 'EzWeb' => '215', 'SB' => '$EV');
		$this->EMOJI['F9C3'] = array('TIT' => '��Τ�', 'EzWeb' => '423', 'SB' => '$OX');
		$this->EMOJI['F9C4'] = array('TIT' => '�ӻ���', 'EzWeb' => '25', 'SB' => '[�ӻ���]');
		$this->EMOJI['F9C5'] = array('TIT' => '�ͤ��Ƥ��', 'EzWeb' => '441', 'SB' => '$P#');
		$this->EMOJI['F9C6'] = array('TIT' => '�ۤäȤ�����', 'EzWeb' => '446', 'SB' => '$P*');
		$this->EMOJI['F9C7'] = array('TIT' => '����', 'EzWeb' => '257', 'SB' => '$P5');
		$this->EMOJI['F9C8'] = array('TIT' => '����2', 'EzWeb' => '351', 'SB' => '$E(');
		$this->EMOJI['F9C9'] = array('TIT' => '�פä��ä��ʴ�', 'EzWeb' => '779', 'SB' => '$P6');
		$this->EMOJI['F9CA'] = array('TIT' => '�ܥ����äȤ�����', 'EzWeb' => '450', 'SB' => '$P.');
		$this->EMOJI['F9CB'] = array('TIT' => '�ܤ��ϡ���', 'EzWeb' => '349', 'SB' => '$E&');
		$this->EMOJI['F9CC'] = array('TIT' => '�ؤ�OK', 'EzWeb' => '287', 'SB' => '$G.');
		$this->EMOJI['F9CD'] = array('TIT' => '���ä���١�', 'EzWeb' => '264', 'SB' => '$E%');
		$this->EMOJI['F9CE'] = array('TIT' => '������', 'EzWeb' => '348', 'SB' => '$P%');
		$this->EMOJI['F9CF'] = array('TIT' => '���줷����', 'EzWeb' => '446', 'SB' => '$P*');
		$this->EMOJI['F9D0'] = array('TIT' => '���ޤ��', 'EzWeb' => '443', 'SB' => '$P&');
		$this->EMOJI['F9D1'] = array('TIT' => 'ǭ2', 'EzWeb' => '440', 'SB' => '$P"');
		$this->EMOJI['F9D2'] = array('TIT' => '�㤭��', 'EzWeb' => '259', 'SB' => '$P1');
		$this->EMOJI['F9D3'] = array('TIT' => '��', 'EzWeb' => '791', 'SB' => '$P3');
		$this->EMOJI['F9D4'] = array('TIT' => 'NG', 'EzWeb' => '[�Σ�]', 'SB' => '[�Σ�]');
		$this->EMOJI['F9D5'] = array('TIT' => '����å�', 'EzWeb' => '143', 'SB' => '[����å�]');
		$this->EMOJI['F9D6'] = array('TIT' => '���ԡ��饤��', 'EzWeb' => '81', 'SB' => '$Fn');
		$this->EMOJI['F9D7'] = array('TIT' => '�ȥ졼�ɥޡ���', 'EzWeb' => '54', 'SB' => '$QW');
		$this->EMOJI['F9D8'] = array('TIT' => '�����', 'EzWeb' => '218', 'SB' => '$E5');
		$this->EMOJI['F9D9'] = array('TIT' => '�ޥ���', 'EzWeb' => '279', 'SB' => '$O5');
		$this->EMOJI['F9DA'] = array('TIT' => '�ꥵ������', 'EzWeb' => '807', 'SB' => '-');
		$this->EMOJI['F9DB'] = array('TIT' => '�쥸�������ɥȥ졼�ɥޡ���', 'EzWeb' => '82', 'SB' => '$Fo');
		$this->EMOJI['F9DC'] = array('TIT' => '�����ٹ�', 'EzWeb' => '1', 'SB' => '$Fr');
		$this->EMOJI['F9DD'] = array('TIT' => '�ػ�', 'EzWeb' => '[��]', 'SB' => '[��]');
		$this->EMOJI['F9DE'] = array('TIT' => '���������ʡ�����', 'EzWeb' => '387', 'SB' => '$FK');
		$this->EMOJI['F9DF'] = array('TIT' => '��ʥޡ���', 'EzWeb' => '[��]', 'SB' => '[��]');
		$this->EMOJI['F9E0'] = array('TIT' => '���������ʡ�����', 'EzWeb' => '386', 'SB' => '$FJ');
		$this->EMOJI['F9E1'] = array('TIT' => '�������', 'EzWeb' => '808', 'SB' => '��');
		$this->EMOJI['F9E2'] = array('TIT' => '����岼', 'EzWeb' => '809', 'SB' => '-');
		$this->EMOJI['F9E3'] = array('TIT' => '�ع�', 'EzWeb' => '377', 'SB' => '$Ew');
		$this->EMOJI['F9E4'] = array('TIT' => '��', 'EzWeb' => '810', 'SB' => '$P^');
		$this->EMOJI['F9E5'] = array('TIT' => '�ٻλ�', 'EzWeb' => '342', 'SB' => '$G[');
		$this->EMOJI['F9E6'] = array('TIT' => '�����С�', 'EzWeb' => '53', 'SB' => '$E0');
		$this->EMOJI['F9E7'] = array('TIT' => '��������', 'EzWeb' => '241', 'SB' => '[�����꡼]');
		$this->EMOJI['F9E8'] = array('TIT' => '���塼��å�', 'EzWeb' => '113', 'SB' => '$O$');
		$this->EMOJI['F9E9'] = array('TIT' => '�Хʥ�', 'EzWeb' => '739', 'SB' => '[�Хʥ�]');
		$this->EMOJI['F9EA'] = array('TIT' => '���', 'EzWeb' => '434', 'SB' => '$Oe');
		$this->EMOJI['F9EB'] = array('TIT' => '��', 'EzWeb' => '811', 'SB' => '$E0');
		$this->EMOJI['F9EC'] = array('TIT' => '��ߤ�', 'EzWeb' => '133', 'SB' => '$E8');
		$this->EMOJI['F9ED'] = array('TIT' => '��', 'EzWeb' => '235', 'SB' => '$GP');
		$this->EMOJI['F9EE'] = array('TIT' => '���ˤ���', 'EzWeb' => '244', 'SB' => '$Ob');
		$this->EMOJI['F9EF'] = array('TIT' => '���硼�ȥ�����', 'EzWeb' => '239', 'SB' => '$Gf');
		$this->EMOJI['F9F0'] = array('TIT' => '�Ȥä���ʤ����礳�դ���', 'EzWeb' => '400', 'SB' => '$O+');
		$this->EMOJI['F9F1'] = array('TIT' => '�ɤ�֤�', 'EzWeb' => '333', 'SB' => '$O`');
		$this->EMOJI['F9F2'] = array('TIT' => '�ѥ�', 'EzWeb' => '424', 'SB' => '$OY');
		$this->EMOJI['F9F3'] = array('TIT' => '�����Ĥ��', 'EzWeb' => '812', 'SB' => '[�����ĥ��]');
		$this->EMOJI['F9F4'] = array('TIT' => '�Ҥ褳', 'EzWeb' => '78', 'SB' => '$QC');
		$this->EMOJI['F9F5'] = array('TIT' => '�ڥ󥮥�', 'EzWeb' => '252', 'SB' => '$Gu');
		$this->EMOJI['F9F6'] = array('TIT' => '��', 'EzWeb' => '203', 'SB' => '$G9');
		$this->EMOJI['F9F7'] = array('TIT' => '���ޤ���', 'EzWeb' => '454', 'SB' => '$Gv');
		$this->EMOJI['F9F8'] = array('TIT' => '���å��å�', 'EzWeb' => '814', 'SB' => '$P$');
		$this->EMOJI['F9F9'] = array('TIT' => '����', 'EzWeb' => '248', 'SB' => '$G:');
		$this->EMOJI['F9FA'] = array('TIT' => '�֥�', 'EzWeb' => '254', 'SB' => '$E+');
		$this->EMOJI['F9FB'] = array('TIT' => '�磻�󥰥饹', 'EzWeb' => '12', 'SB' => '$Gd');
		$this->EMOJI['F9FC'] = array('TIT' => '���ä���', 'EzWeb' => '350', 'SB' => '$E\'');
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}//end of class
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
