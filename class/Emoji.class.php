<?php
/**
* システム名    ：携帯絵文字自動変換
* プログラム名  ：MobileClass
*
* :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :
* [プログラム概要]
* DoCoMo向けに入力した絵文字を、アクセスしてきたキャリアに合わせて
* 自動的に互換する絵文字(コード)に置換します。
* DoCoMo絵文字の入力は、関数の引数に絵文字入力ソフトを使って直接入力するか、
* 16進法を引数に与える事により実現します(推奨は16進法です)
*
* [呼出元]
* Nothing
*
* [呼出先]
* Nothing
*
* [パラメータ]
* Nothing
* :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :    :
*
* @since            2006/11/20
* @auther           T.Kotaka
*
* @version          1.2.0
*
* [改版履歴]
* 000001    2007/01/22    16進法による入力をサポートしました。
* 000002    2007/01/22    ユーザーエージェントが「SoftBank」の際に、絵文字変換されない不具合を修正しました。
* 000003    2007/01/23    EzWebにおいて、絵文字の代替文字が出力出来ない不具合を修正しました。
*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 上記を見本にxmbile向けに改造
class XmobileEmoji
{
	var $EMOJI         = array();     // 絵文字テーブル
	var $InputMode     = 0;           // 0 Or 1 （0：バイナリ入力、1：絵文字直接入力）

	var $docomo_char = array();
	var $au_char = array();
	var $softbank_char = array();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// コンストラクタ
	function XmobileEmoji()
	{
		$this->__construct();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// コンストラクタ(PHP5対応)
	function __construct()
	{
		// 絵文字テーブルセット
		$this->_EmojiTable();
		// ユーザーエージェントセット
//		$this->getUserAgent();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 引数で指定された文字列を、エージェントにあわせて絵文字変換
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
	// 引数で指定された文字列を、エージェントにあわせて絵文字変換
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
		$this->EMOJI['F89F'] = array('TIT' => '晴れ', 'EzWeb' => '44', 'SB' => '$Gj');
		$this->EMOJI['F8A0'] = array('TIT' => '曇り', 'EzWeb' => '107', 'SB' => '$Gi');
		$this->EMOJI['F8A1'] = array('TIT' => '雨', 'EzWeb' => '95', 'SB' => '$Gk');
		$this->EMOJI['F8A2'] = array('TIT' => '雪', 'EzWeb' => '191', 'SB' => '$Gh');
		$this->EMOJI['F8A3'] = array('TIT' => '雷', 'EzWeb' => '16', 'SB' => '$E]');
		$this->EMOJI['F8A4'] = array('TIT' => '台風', 'EzWeb' => '190', 'SB' => '$Pc');
		$this->EMOJI['F8A5'] = array('TIT' => '霧', 'EzWeb' => '305', 'SB' => '[霧]');
		$this->EMOJI['F8A6'] = array('TIT' => '小雨', 'EzWeb' => '481', 'SB' => '$P\');
		$this->EMOJI['F8A7'] = array('TIT' => '牡羊座', 'EzWeb' => '192', 'SB' => '$F_');
		$this->EMOJI['F8A8'] = array('TIT' => '牡牛座', 'EzWeb' => '193', 'SB' => '$F`');
		$this->EMOJI['F8A9'] = array('TIT' => '双子座', 'EzWeb' => '194', 'SB' => '$Fa');
		$this->EMOJI['F8AA'] = array('TIT' => '蟹座', 'EzWeb' => '195', 'SB' => '$Fb');
		$this->EMOJI['F8AB'] = array('TIT' => '獅子座', 'EzWeb' => '196', 'SB' => '$Fc');
		$this->EMOJI['F8AC'] = array('TIT' => '乙女座', 'EzWeb' => '197', 'SB' => '$Fd');
		$this->EMOJI['F8AD'] = array('TIT' => '天秤座', 'EzWeb' => '198', 'SB' => '$Fe');
		$this->EMOJI['F8AE'] = array('TIT' => '蠍座', 'EzWeb' => '199', 'SB' => '$Ff');
		$this->EMOJI['F8AF'] = array('TIT' => '射手座', 'EzWeb' => '200', 'SB' => '$Fg');
		$this->EMOJI['F8B0'] = array('TIT' => '山羊座', 'EzWeb' => '201', 'SB' => '$Fh');
		$this->EMOJI['F8B1'] = array('TIT' => '水瓶座', 'EzWeb' => '202', 'SB' => '$Fi');
		$this->EMOJI['F8B2'] = array('TIT' => '魚座', 'EzWeb' => '203', 'SB' => '$Fj');
		$this->EMOJI['F8B3'] = array('TIT' => 'スポーツ', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F8B4'] = array('TIT' => '野球', 'EzWeb' => '45', 'SB' => '$G6');
		$this->EMOJI['F8B5'] = array('TIT' => 'ゴルフ', 'EzWeb' => '306', 'SB' => '$G4');
		$this->EMOJI['F8B6'] = array('TIT' => 'テニス', 'EzWeb' => '220', 'SB' => '$G5');
		$this->EMOJI['F8B7'] = array('TIT' => 'サッカー', 'EzWeb' => '219', 'SB' => '$G8');
		$this->EMOJI['F8B8'] = array('TIT' => 'スキー', 'EzWeb' => '421', 'SB' => '$G3');
		$this->EMOJI['F8B9'] = array('TIT' => 'バスケットボール', 'EzWeb' => '307', 'SB' => '$PJ');
		$this->EMOJI['F8BA'] = array('TIT' => 'モータースポーツ', 'EzWeb' => '222', 'SB' => '$ER');
		$this->EMOJI['F8BB'] = array('TIT' => 'ポケットベル', 'EzWeb' => '308', 'SB' => '[PB]');
		$this->EMOJI['F8BC'] = array('TIT' => '電車', 'EzWeb' => '172', 'SB' => '$G>');
		$this->EMOJI['F8BD'] = array('TIT' => '地下鉄', 'EzWeb' => '341', 'SB' => '$PT');
		$this->EMOJI['F8BE'] = array('TIT' => '新幹線', 'EzWeb' => '217', 'SB' => '$PU');
		$this->EMOJI['F8BF'] = array('TIT' => '車（セダン）', 'EzWeb' => '125', 'SB' => '$G;');
		$this->EMOJI['F8C0'] = array('TIT' => '車（ＲＶ）', 'EzWeb' => '125', 'SB' => '$PN');
		$this->EMOJI['F8C1'] = array('TIT' => 'バス', 'EzWeb' => '216', 'SB' => '$Ey');
		$this->EMOJI['F8C2'] = array('TIT' => '船', 'EzWeb' => '379', 'SB' => '$F"');
		$this->EMOJI['F8C3'] = array('TIT' => '飛行機', 'EzWeb' => '168', 'SB' => '$G=');
		$this->EMOJI['F8C4'] = array('TIT' => '家', 'EzWeb' => '112', 'SB' => '$GV');
		$this->EMOJI['F8C5'] = array('TIT' => 'ビル', 'EzWeb' => '156', 'SB' => '$GX');
		$this->EMOJI['F8C6'] = array('TIT' => '郵便局', 'EzWeb' => '375', 'SB' => '$Es');
		$this->EMOJI['F8C7'] = array('TIT' => '病院', 'EzWeb' => '376', 'SB' => '$Eu');
		$this->EMOJI['F8C8'] = array('TIT' => '銀行', 'EzWeb' => '212', 'SB' => '$Em');
		$this->EMOJI['F8C9'] = array('TIT' => 'ＡＴＭ', 'EzWeb' => '205', 'SB' => '$Et');
		$this->EMOJI['F8CA'] = array('TIT' => 'ホテル', 'EzWeb' => '378', 'SB' => '$Ex');
		$this->EMOJI['F8CB'] = array('TIT' => 'コンビニ', 'EzWeb' => '206', 'SB' => '$Ev');
		$this->EMOJI['F8CC'] = array('TIT' => 'ガソリンスタンド', 'EzWeb' => '213', 'SB' => '$GZ');
		$this->EMOJI['F8CD'] = array('TIT' => '駐車場', 'EzWeb' => '208', 'SB' => '$Eo');
		$this->EMOJI['F8CE'] = array('TIT' => '信号', 'EzWeb' => '99', 'SB' => '$En');
		$this->EMOJI['F8CF'] = array('TIT' => 'トイレ', 'EzWeb' => '207', 'SB' => '$Eq');
		$this->EMOJI['F8D0'] = array('TIT' => 'レストラン', 'EzWeb' => '146', 'SB' => '$Gc');
		$this->EMOJI['F8D1'] = array('TIT' => '喫茶店', 'EzWeb' => '93', 'SB' => '$Ge');
		$this->EMOJI['F8D2'] = array('TIT' => 'バー', 'EzWeb' => '52', 'SB' => '$Gd');
		$this->EMOJI['F8D3'] = array('TIT' => 'ビール', 'EzWeb' => '65', 'SB' => '$Gg');
		$this->EMOJI['F8D4'] = array('TIT' => 'ファーストフード', 'EzWeb' => '245', 'SB' => '$E@');
		$this->EMOJI['F8D5'] = array('TIT' => 'ブティック', 'EzWeb' => '124', 'SB' => '$E^');
		$this->EMOJI['F8D6'] = array('TIT' => '美容院', 'EzWeb' => '104', 'SB' => '$O3');
		$this->EMOJI['F8D7'] = array('TIT' => 'カラオケ', 'EzWeb' => '289', 'SB' => '$G\');
		$this->EMOJI['F8D8'] = array('TIT' => '映画', 'EzWeb' => '110', 'SB' => '$G]');
		$this->EMOJI['F8D9'] = array('TIT' => '右斜め上', 'EzWeb' => '70', 'SB' => '$FV');
		$this->EMOJI['F8DA'] = array('TIT' => '遊園地', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F8DB'] = array('TIT' => '音楽', 'EzWeb' => '294', 'SB' => '$O*');
		$this->EMOJI['F8DC'] = array('TIT' => 'アート', 'EzWeb' => '309', 'SB' => '$Q"');
		$this->EMOJI['F8DD'] = array('TIT' => '演劇', 'EzWeb' => '494', 'SB' => '$Q#');
		$this->EMOJI['F8DE'] = array('TIT' => 'イベント', 'EzWeb' => '311', 'SB' => '-');
		$this->EMOJI['F8DF'] = array('TIT' => 'チケット', 'EzWeb' => '106', 'SB' => '$EE');
		$this->EMOJI['F8E0'] = array('TIT' => '喫煙', 'EzWeb' => '176', 'SB' => '$O.');
		$this->EMOJI['F8E1'] = array('TIT' => '禁煙', 'EzWeb' => '177', 'SB' => '$F(');
		$this->EMOJI['F8E2'] = array('TIT' => 'カメラ', 'EzWeb' => '94', 'SB' => '$G(');
		$this->EMOJI['F8E3'] = array('TIT' => 'カバン', 'EzWeb' => '83', 'SB' => '$OC');
		$this->EMOJI['F8E4'] = array('TIT' => '本', 'EzWeb' => '122', 'SB' => '$Eh');
		$this->EMOJI['F8E5'] = array('TIT' => 'リボン', 'EzWeb' => '312', 'SB' => '$O4');
		$this->EMOJI['F8E6'] = array('TIT' => 'プレゼント', 'EzWeb' => '144', 'SB' => '$E2');
		$this->EMOJI['F8E7'] = array('TIT' => 'バースデー', 'EzWeb' => '313', 'SB' => '$Ok');
		$this->EMOJI['F8E8'] = array('TIT' => '電話', 'EzWeb' => '85', 'SB' => '$G)');
		$this->EMOJI['F8E9'] = array('TIT' => '携帯電話', 'EzWeb' => '161', 'SB' => '$G*');
		$this->EMOJI['F8EA'] = array('TIT' => 'メモ', 'EzWeb' => '395', 'SB' => '$O!');
		$this->EMOJI['F8EB'] = array('TIT' => 'ＴＶ', 'EzWeb' => '288', 'SB' => '$EJ');
		$this->EMOJI['F8EC'] = array('TIT' => 'ゲーム', 'EzWeb' => '232', 'SB' => '[ゲーム]');
		$this->EMOJI['F8ED'] = array('TIT' => 'ＣＤ', 'EzWeb' => '300', 'SB' => '$EF');
		$this->EMOJI['F8EE'] = array('TIT' => 'ハート', 'EzWeb' => '414', 'SB' => '$F,');
		$this->EMOJI['F8EF'] = array('TIT' => 'スペード', 'EzWeb' => '314', 'SB' => '$F.');
		$this->EMOJI['F8F0'] = array('TIT' => 'ダイヤ', 'EzWeb' => '315', 'SB' => '$F-');
		$this->EMOJI['F8F1'] = array('TIT' => 'クラブ', 'EzWeb' => '316', 'SB' => '$F/');
		$this->EMOJI['F8F2'] = array('TIT' => '目', 'EzWeb' => '317', 'SB' => '$P9');
		$this->EMOJI['F8F3'] = array('TIT' => '耳', 'EzWeb' => '318', 'SB' => '$P;');
		$this->EMOJI['F8F4'] = array('TIT' => '手（グー）', 'EzWeb' => '817', 'SB' => '$G0');
		$this->EMOJI['F8F5'] = array('TIT' => '手（チョキ）', 'EzWeb' => '319', 'SB' => '$G1');
		$this->EMOJI['F8F6'] = array('TIT' => '手（パー）', 'EzWeb' => '320', 'SB' => '$G2');
		$this->EMOJI['F8F7'] = array('TIT' => '右斜め下', 'EzWeb' => '43', 'SB' => '$FX');
		$this->EMOJI['F8F8'] = array('TIT' => '左斜め上', 'EzWeb' => '42', 'SB' => '$FW');
		$this->EMOJI['F8F9'] = array('TIT' => '足', 'EzWeb' => '728', 'SB' => '$QV');
		$this->EMOJI['F8FA'] = array('TIT' => 'くつ', 'EzWeb' => '729', 'SB' => '$G\'');
		$this->EMOJI['F8FB'] = array('TIT' => '眼鏡', 'EzWeb' => '116', 'SB' => '[メガネ]');
		$this->EMOJI['F8FC'] = array('TIT' => '車椅子', 'EzWeb' => '178', 'SB' => '$F*');
		$this->EMOJI['F940'] = array('TIT' => '新月', 'EzWeb' => '321', 'SB' => '●');
		$this->EMOJI['F941'] = array('TIT' => 'やや欠け月', 'EzWeb' => '322', 'SB' => '$Gl');
		$this->EMOJI['F942'] = array('TIT' => '半月', 'EzWeb' => '323', 'SB' => '$Gl');
		$this->EMOJI['F943'] = array('TIT' => '三日月', 'EzWeb' => '15', 'SB' => '$Gl');
		$this->EMOJI['F944'] = array('TIT' => '満月', 'EzWeb' => '○', 'SB' => '○');
		$this->EMOJI['F945'] = array('TIT' => '犬', 'EzWeb' => '134', 'SB' => '$Gr');
		$this->EMOJI['F946'] = array('TIT' => '猫', 'EzWeb' => '251', 'SB' => '$Go');
		$this->EMOJI['F947'] = array('TIT' => 'リゾート', 'EzWeb' => '169', 'SB' => '$G<');
		$this->EMOJI['F948'] = array('TIT' => 'クリスマス', 'EzWeb' => '234', 'SB' => '$GS');
		$this->EMOJI['F949'] = array('TIT' => '左斜め下', 'EzWeb' => '71', 'SB' => '$FY');
		$this->EMOJI['F950'] = array('TIT' => 'カチンコ', 'EzWeb' => '226', 'SB' => '$OD');
		$this->EMOJI['F951'] = array('TIT' => 'ふくろ', 'EzWeb' => '[ふくろ]', 'SB' => '[ふくろ]');
		$this->EMOJI['F952'] = array('TIT' => 'ペン', 'EzWeb' => '508', 'SB' => '［ペン］');
		$this->EMOJI['F955'] = array('TIT' => '人影', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F956'] = array('TIT' => 'いす', 'EzWeb' => '[いす]', 'SB' => '$E?');
		$this->EMOJI['F957'] = array('TIT' => '夜', 'EzWeb' => '490', 'SB' => '$Pk');
		$this->EMOJI['F95E'] = array('TIT' => '時計', 'EzWeb' => '46', 'SB' => '$GM');
		$this->EMOJI['F972'] = array('TIT' => 'phone to', 'EzWeb' => '513', 'SB' => '$E$');
		$this->EMOJI['F973'] = array('TIT' => 'mail to', 'EzWeb' => '784', 'SB' => '$E#');
		$this->EMOJI['F974'] = array('TIT' => 'fax to', 'EzWeb' => '166', 'SB' => '$G+');
		$this->EMOJI['F975'] = array('TIT' => 'iモード', 'EzWeb' => '[iモード]', 'SB' => '[iモード]');
		$this->EMOJI['F976'] = array('TIT' => 'iモード（枠付き）', 'EzWeb' => '[iモード]', 'SB' => '[iモード]');
		$this->EMOJI['F977'] = array('TIT' => 'メール', 'EzWeb' => '108', 'SB' => '$E#');
		$this->EMOJI['F978'] = array('TIT' => 'ドコモ提供', 'EzWeb' => '[ドコモ]', 'SB' => '[ドコモ]');
		$this->EMOJI['F979'] = array('TIT' => 'ドコモポイント', 'EzWeb' => '[ドコモポイント]', 'SB' => '[ドコモポイント]');
		$this->EMOJI['F97A'] = array('TIT' => '有料', 'EzWeb' => '109', 'SB' => '￥');
		$this->EMOJI['F97B'] = array('TIT' => '無料', 'EzWeb' => '299', 'SB' => '［ＦＲＥＥ］');
		$this->EMOJI['F97D'] = array('TIT' => 'パスワード', 'EzWeb' => '120', 'SB' => '$G_');
		$this->EMOJI['F97E'] = array('TIT' => '次項有', 'EzWeb' => '118', 'SB' => '-');
		$this->EMOJI['F980'] = array('TIT' => 'クリア', 'EzWeb' => '324', 'SB' => '[CL]');
		$this->EMOJI['F981'] = array('TIT' => 'サーチ（調べる）', 'EzWeb' => '119', 'SB' => '$E4');
		$this->EMOJI['F982'] = array('TIT' => 'ＮＥＷ', 'EzWeb' => '334', 'SB' => '$F2');
		$this->EMOJI['F983'] = array('TIT' => '位置情報', 'EzWeb' => '730', 'SB' => '-');
		$this->EMOJI['F984'] = array('TIT' => 'フリーダイヤル', 'EzWeb' => '「フリーダイヤル]', 'SB' => '$F1');
		$this->EMOJI['F985'] = array('TIT' => 'シャープダイヤル', 'EzWeb' => '818', 'SB' => '$F0');
		$this->EMOJI['F986'] = array('TIT' => 'モバＱ', 'EzWeb' => '4', 'SB' => '[Q]');
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
		$this->EMOJI['F9B0'] = array('TIT' => '決定', 'EzWeb' => '326', 'SB' => '$Fm');
		$this->EMOJI['F991'] = array('TIT' => '黒ハート', 'EzWeb' => '51', 'SB' => '$GB');
		$this->EMOJI['F993'] = array('TIT' => '失恋', 'EzWeb' => '265', 'SB' => '$GC');
		$this->EMOJI['F994'] = array('TIT' => 'ハートたち（複数ハート）', 'EzWeb' => '266', 'SB' => '$OG');
		$this->EMOJI['F995'] = array('TIT' => 'わーい（嬉しい顔）', 'EzWeb' => '257', 'SB' => '$Gw');
		$this->EMOJI['F996'] = array('TIT' => 'ちっ（怒った顔）', 'EzWeb' => '258', 'SB' => '$Gy');
		$this->EMOJI['F997'] = array('TIT' => 'がく〜（落胆した顔）', 'EzWeb' => '441', 'SB' => '$Gx');
		$this->EMOJI['F998'] = array('TIT' => 'もうやだ〜（悲しい顔）', 'EzWeb' => '444', 'SB' => '$P\'');
		$this->EMOJI['F999'] = array('TIT' => 'ふらふら', 'EzWeb' => '327', 'SB' => '$P&');
		$this->EMOJI['F99A'] = array('TIT' => 'グッド（上向き矢印）', 'EzWeb' => '731', 'SB' => '$FV');
		$this->EMOJI['F99B'] = array('TIT' => 'るんるん', 'EzWeb' => '343', 'SB' => '$G^');
		$this->EMOJI['F99C'] = array('TIT' => 'いい気分（温泉）', 'EzWeb' => '224', 'SB' => '$EC');
		$this->EMOJI['F99D'] = array('TIT' => 'かわいい', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F99E'] = array('TIT' => 'キスマーク', 'EzWeb' => '273', 'SB' => '$G#');
		$this->EMOJI['F99F'] = array('TIT' => 'ぴかぴか（新しい）', 'EzWeb' => '420', 'SB' => '$ON');
		$this->EMOJI['F9A0'] = array('TIT' => 'ひらめき', 'EzWeb' => '77', 'SB' => '$E/');
		$this->EMOJI['F9A1'] = array('TIT' => 'むかっ（怒り）', 'EzWeb' => '262', 'SB' => '$OT');
		$this->EMOJI['F9A2'] = array('TIT' => 'パンチ', 'EzWeb' => '281', 'SB' => '$G-');
		$this->EMOJI['F9A3'] = array('TIT' => '爆弾', 'EzWeb' => '268', 'SB' => '$O1');
		$this->EMOJI['F9A4'] = array('TIT' => 'ムード', 'EzWeb' => '291', 'SB' => '$OF');
		$this->EMOJI['F9A5'] = array('TIT' => 'バッド（下向き矢印）', 'EzWeb' => '732', 'SB' => '$FX');
		$this->EMOJI['F9A6'] = array('TIT' => '眠い(睡眠)', 'EzWeb' => '261', 'SB' => '$E\');
		$this->EMOJI['F9A7'] = array('TIT' => 'exclamation', 'EzWeb' => '2', 'SB' => '$GA');
		$this->EMOJI['F9A8'] = array('TIT' => 'exclamation&question', 'EzWeb' => '733', 'SB' => '！？');
		$this->EMOJI['F9A9'] = array('TIT' => 'exclamation×2', 'EzWeb' => '734', 'SB' => '！！');
		$this->EMOJI['F9AA'] = array('TIT' => 'どんっ（衝撃）', 'EzWeb' => '329', 'SB' => '-');
		$this->EMOJI['F9AB'] = array('TIT' => 'あせあせ（飛び散る汗）', 'EzWeb' => '330', 'SB' => '$OQ');
		$this->EMOJI['F9AC'] = array('TIT' => 'たらーっ（汗）', 'EzWeb' => '263', 'SB' => '$OQ');
		$this->EMOJI['F9AD'] = array('TIT' => 'ダッシュ（走り出すさま）', 'EzWeb' => '282', 'SB' => '$OP');
		$this->EMOJI['F9AE'] = array('TIT' => 'ー（長音記号１）', 'EzWeb' => '-', 'SB' => '-');
		$this->EMOJI['F9AF'] = array('TIT' => 'ー（長音記号２）', 'EzWeb' => '735', 'SB' => '-');
		$this->EMOJI['F9B1'] = array('TIT' => 'iアプリ', 'EzWeb' => '[ｉアプリ]', 'SB' => '[ｉアプリ]');
		$this->EMOJI['F9B2'] = array('TIT' => 'iアプリ（枠付き）', 'EzWeb' => '[ｉアプリ]', 'SB' => '[ｉアプリ]');
		$this->EMOJI['F9B3'] = array('TIT' => 'Tシャツ（ボーダー）', 'EzWeb' => '335', 'SB' => '$G&');
		$this->EMOJI['F9B4'] = array('TIT' => 'がま口財布', 'EzWeb' => '290', 'SB' => '[財布]');
		$this->EMOJI['F9B5'] = array('TIT' => '化粧', 'EzWeb' => '295', 'SB' => '$O<');
		$this->EMOJI['F9B6'] = array('TIT' => 'ジーンズ', 'EzWeb' => '805', 'SB' => '[ジーンズ]');
		$this->EMOJI['F9B7'] = array('TIT' => 'スノボ', 'EzWeb' => '221', 'SB' => '[スノボ]');
		$this->EMOJI['F9B8'] = array('TIT' => 'チャペル', 'EzWeb' => '48', 'SB' => '$OE');
		$this->EMOJI['F9B9'] = array('TIT' => 'ドア', 'EzWeb' => '[ドア]', 'SB' => '[ドア]');
		$this->EMOJI['F9BA'] = array('TIT' => 'ドル袋', 'EzWeb' => '233', 'SB' => '$EO');
		$this->EMOJI['F9BB'] = array('TIT' => 'パソコン', 'EzWeb' => '337', 'SB' => '$G,');
		$this->EMOJI['F9BC'] = array('TIT' => 'ラブレター', 'EzWeb' => '806', 'SB' => '$E#');
		$this->EMOJI['F9BD'] = array('TIT' => 'レンチ', 'EzWeb' => '152', 'SB' => '[レンチ]');
		$this->EMOJI['F9BE'] = array('TIT' => '鉛筆', 'EzWeb' => '149', 'SB' => '$O!');
		$this->EMOJI['F9BF'] = array('TIT' => '王冠', 'EzWeb' => '354', 'SB' => '$E.');
		$this->EMOJI['F9C0'] = array('TIT' => '指輪', 'EzWeb' => '72', 'SB' => '$GT');
		$this->EMOJI['F9C1'] = array('TIT' => '砂時計', 'EzWeb' => '58', 'SB' => '[砂時計]');
		$this->EMOJI['F9C2'] = array('TIT' => '自転車', 'EzWeb' => '215', 'SB' => '$EV');
		$this->EMOJI['F9C3'] = array('TIT' => '湯のみ', 'EzWeb' => '423', 'SB' => '$OX');
		$this->EMOJI['F9C4'] = array('TIT' => '腕時計', 'EzWeb' => '25', 'SB' => '[腕時計]');
		$this->EMOJI['F9C5'] = array('TIT' => '考えてる顔', 'EzWeb' => '441', 'SB' => '$P#');
		$this->EMOJI['F9C6'] = array('TIT' => 'ほっとした顔', 'EzWeb' => '446', 'SB' => '$P*');
		$this->EMOJI['F9C7'] = array('TIT' => '冷や汗', 'EzWeb' => '257', 'SB' => '$P5');
		$this->EMOJI['F9C8'] = array('TIT' => '冷や汗2', 'EzWeb' => '351', 'SB' => '$E(');
		$this->EMOJI['F9C9'] = array('TIT' => 'ぷっくっくな顔', 'EzWeb' => '779', 'SB' => '$P6');
		$this->EMOJI['F9CA'] = array('TIT' => 'ボケーっとした顔', 'EzWeb' => '450', 'SB' => '$P.');
		$this->EMOJI['F9CB'] = array('TIT' => '目がハート', 'EzWeb' => '349', 'SB' => '$E&');
		$this->EMOJI['F9CC'] = array('TIT' => '指でOK', 'EzWeb' => '287', 'SB' => '$G.');
		$this->EMOJI['F9CD'] = array('TIT' => 'あっかんべー', 'EzWeb' => '264', 'SB' => '$E%');
		$this->EMOJI['F9CE'] = array('TIT' => 'ウィンク', 'EzWeb' => '348', 'SB' => '$P%');
		$this->EMOJI['F9CF'] = array('TIT' => 'うれしい顔', 'EzWeb' => '446', 'SB' => '$P*');
		$this->EMOJI['F9D0'] = array('TIT' => 'がまん顔', 'EzWeb' => '443', 'SB' => '$P&');
		$this->EMOJI['F9D1'] = array('TIT' => '猫2', 'EzWeb' => '440', 'SB' => '$P"');
		$this->EMOJI['F9D2'] = array('TIT' => '泣き顔', 'EzWeb' => '259', 'SB' => '$P1');
		$this->EMOJI['F9D3'] = array('TIT' => '涙', 'EzWeb' => '791', 'SB' => '$P3');
		$this->EMOJI['F9D4'] = array('TIT' => 'NG', 'EzWeb' => '[ＮＧ]', 'SB' => '[ＮＧ]');
		$this->EMOJI['F9D5'] = array('TIT' => 'クリップ', 'EzWeb' => '143', 'SB' => '[クリップ]');
		$this->EMOJI['F9D6'] = array('TIT' => 'コピーライト', 'EzWeb' => '81', 'SB' => '$Fn');
		$this->EMOJI['F9D7'] = array('TIT' => 'トレードマーク', 'EzWeb' => '54', 'SB' => '$QW');
		$this->EMOJI['F9D8'] = array('TIT' => '走る人', 'EzWeb' => '218', 'SB' => '$E5');
		$this->EMOJI['F9D9'] = array('TIT' => 'マル秘', 'EzWeb' => '279', 'SB' => '$O5');
		$this->EMOJI['F9DA'] = array('TIT' => 'リサイクル', 'EzWeb' => '807', 'SB' => '-');
		$this->EMOJI['F9DB'] = array('TIT' => 'レジスタードトレードマーク', 'EzWeb' => '82', 'SB' => '$Fo');
		$this->EMOJI['F9DC'] = array('TIT' => '危険・警告', 'EzWeb' => '1', 'SB' => '$Fr');
		$this->EMOJI['F9DD'] = array('TIT' => '禁止', 'EzWeb' => '[禁]', 'SB' => '[禁]');
		$this->EMOJI['F9DE'] = array('TIT' => '空室・空席・空車', 'EzWeb' => '387', 'SB' => '$FK');
		$this->EMOJI['F9DF'] = array('TIT' => '合格マーク', 'EzWeb' => '[合]', 'SB' => '[合]');
		$this->EMOJI['F9E0'] = array('TIT' => '満室・満席・満車', 'EzWeb' => '386', 'SB' => '$FJ');
		$this->EMOJI['F9E1'] = array('TIT' => '矢印左右', 'EzWeb' => '808', 'SB' => '⇔');
		$this->EMOJI['F9E2'] = array('TIT' => '矢印上下', 'EzWeb' => '809', 'SB' => '-');
		$this->EMOJI['F9E3'] = array('TIT' => '学校', 'EzWeb' => '377', 'SB' => '$Ew');
		$this->EMOJI['F9E4'] = array('TIT' => '波', 'EzWeb' => '810', 'SB' => '$P^');
		$this->EMOJI['F9E5'] = array('TIT' => '富士山', 'EzWeb' => '342', 'SB' => '$G[');
		$this->EMOJI['F9E6'] = array('TIT' => 'クローバー', 'EzWeb' => '53', 'SB' => '$E0');
		$this->EMOJI['F9E7'] = array('TIT' => 'さくらんぼ', 'EzWeb' => '241', 'SB' => '[チェリー]');
		$this->EMOJI['F9E8'] = array('TIT' => 'チューリップ', 'EzWeb' => '113', 'SB' => '$O$');
		$this->EMOJI['F9E9'] = array('TIT' => 'バナナ', 'EzWeb' => '739', 'SB' => '[バナナ]');
		$this->EMOJI['F9EA'] = array('TIT' => 'りんご', 'EzWeb' => '434', 'SB' => '$Oe');
		$this->EMOJI['F9EB'] = array('TIT' => '芽', 'EzWeb' => '811', 'SB' => '$E0');
		$this->EMOJI['F9EC'] = array('TIT' => 'もみじ', 'EzWeb' => '133', 'SB' => '$E8');
		$this->EMOJI['F9ED'] = array('TIT' => '桜', 'EzWeb' => '235', 'SB' => '$GP');
		$this->EMOJI['F9EE'] = array('TIT' => 'おにぎり', 'EzWeb' => '244', 'SB' => '$Ob');
		$this->EMOJI['F9EF'] = array('TIT' => 'ショートケーキ', 'EzWeb' => '239', 'SB' => '$Gf');
		$this->EMOJI['F9F0'] = array('TIT' => 'とっくり（おちょこ付き）', 'EzWeb' => '400', 'SB' => '$O+');
		$this->EMOJI['F9F1'] = array('TIT' => 'どんぶり', 'EzWeb' => '333', 'SB' => '$O`');
		$this->EMOJI['F9F2'] = array('TIT' => 'パン', 'EzWeb' => '424', 'SB' => '$OY');
		$this->EMOJI['F9F3'] = array('TIT' => 'かたつむり', 'EzWeb' => '812', 'SB' => '[カタツムリ]');
		$this->EMOJI['F9F4'] = array('TIT' => 'ひよこ', 'EzWeb' => '78', 'SB' => '$QC');
		$this->EMOJI['F9F5'] = array('TIT' => 'ペンギン', 'EzWeb' => '252', 'SB' => '$Gu');
		$this->EMOJI['F9F6'] = array('TIT' => '魚', 'EzWeb' => '203', 'SB' => '$G9');
		$this->EMOJI['F9F7'] = array('TIT' => 'うまい！', 'EzWeb' => '454', 'SB' => '$Gv');
		$this->EMOJI['F9F8'] = array('TIT' => 'ウッシッシ', 'EzWeb' => '814', 'SB' => '$P$');
		$this->EMOJI['F9F9'] = array('TIT' => 'ウマ', 'EzWeb' => '248', 'SB' => '$G:');
		$this->EMOJI['F9FA'] = array('TIT' => 'ブタ', 'EzWeb' => '254', 'SB' => '$E+');
		$this->EMOJI['F9FB'] = array('TIT' => 'ワイングラス', 'EzWeb' => '12', 'SB' => '$Gd');
		$this->EMOJI['F9FC'] = array('TIT' => 'げっそり', 'EzWeb' => '350', 'SB' => '$E\'');
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}//end of class
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
