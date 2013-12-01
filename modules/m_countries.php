<?php
/*
 * @name: Písesa
 * @desc: Muestra información de diversos paises
 * @ver: 1.0
 * @author: MRX
 * @id: country
 * @key: subliminalmessagesonthecode
 *
 */

class subliminalmessagesonthecode{
	private $paises;
	private $idiomas;
	public function __construct(&$core){
		$core->registerCommand("pais", "country", "Muestra información de un país. Sintaxis: pais <Código de pais>");
		$core->registerCommandAlias("país", "pais");
		$this->ipaises();
		$this->iidiomas();
	}
	
	public function pais(&$irc, $data, &$core){
		$ts = $core->jparam($data->messageex,1);
		if(isset($this->paises[strtolower($ts)])){
			$data->messageex[1]=$this->paises[strtolower($ts)];
		}
		$p = file_get_contents("http://restcountries.eu/rest/alpha/{$data->messageex[1]}");
		$j = json_decode($p);
		//print_r($j);
		if($j->area<1){$area=($j->area*100 )."\2 has";}else{$area=$j->area."\2 km²";}
		$moneda = "";
		if($core->isLoaded("divisa")){
			$monedas = explode(",", $j->currency);
			foreach($monedas as $mo){
				$moneda .= $core->getModule("divisa")->divinam[$mo]. " ({$mo}), ";
			}
			$moneda = trim($moneda, ", ");
		}else{ $moneda = $j->currency;}
		$r = "\2{$j->translations->es}\2: Capital: \2{$j->capital}\2, moneda: \2{$moneda}\2, población: \2".number_format($j->population,0,",",".")."\2 ".
		"TLD: \2{$j->topLevelDomain}\2. Superficie: \2$area. Idiomas: ";
			foreach($j->languages as $l){
				$r.="\2".$this->idiomas[$l]."\2, ";
			}
			$r=trim($r,", "). " Zonas horarias: ";
		/* <parseando las zonas horarias..> */
			foreach($j->timezones as $tz){
				if($tz=="UTC"){
					$ts=time();
				}else{
					echo $tz;
					preg_match("#UTC(\+|\-|−)(.+)\:(.+)#i", $tz, $m);
					print_r($m);
					$diff = ($m[2] * 3600) + ($m[3]*60);
					if($m[1]=="+"){
						$ts = time() + $diff;
					}else{
						$ts = time() - $diff;
					}
				}
				$r.= "\2".$tz."\2 (".date("H:i:s", $ts)."), ";
			}
			$r=trim($r, ", ");
		
		if((strtolower($data->messageex[1]) == "mrx") || (strtolower($data->messageex[1]) == "polsaker")){
			$r = "\2La tierra de Polsaker\2 Capital: \2Yuyo\2, moneda: \2Mosquito sellado de oro (XTS)\2, población:\2 5\2, TLD: \2.xxx\2. Superficie:\2 2\2 has. Zona horaria: HNE.";
		}
		/* </parseo> */
		//$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $r);
		$core->message($data->channel, $r);
	}
	
	private function iidiomas(){
		$this->idiomas = array('aa' => 'afar',
			'ab' => 'abjaso',
			'ae' => 'avéstico',
			'af' => 'afrikaans',
			'ak' => 'akano',
			'am' => 'amárico',
			'an' => 'aragonés',
			'ar' => 'árabe',
			'as' => 'asamés',
			'av' => 'avar',
			'ay' => 'aimara',
			'az' => 'azerí',
			'ba' => 'baskir',
			'be' => 'bielorruso',
			'bg' => 'búlgaro',
			'bh' => 'bhojpurí',
			'bi' => 'bislama',
			'bm' => 'bambara',
			'bn' => 'bengalí',
			'bo' => 'tibetano',
			'br' => 'bretón',
			'bs' => 'bosnio',
			'ca' => 'catalán',
			'ce' => 'checheno',
			'ch' => 'chamorro',
			'co' => 'corso',
			'cr' => 'cree',
			'cs' => 'checo',
			'cu' => 'eslavo eclesiástico antiguo',
			'cv' => 'chuvasio',
			'cy' => 'galés',
			'da' => 'danés',
			'de' => 'alemán',
			'dv' => 'maldivo',
			'dz' => 'dzongkha',
			'ee' => 'ewe',
			'el' => 'griego',
			'en' => 'inglés',
			'eo' => 'esperanto',
			'es' => 'español',
			'et' => 'estonio',
			'eu' => 'euskera',
			'fa' => 'persa',
			'ff' => 'fula',
			'fi' => 'finés',
			'fj' => 'fiyiano',
			'fo' => 'feroés',
			'fr' => 'francés',
			'fy' => 'frisón',
			'ga' => 'irlandés',
			'gd' => 'gaélico escocés',
			'gl' => 'gallego',
			'gn' => 'guaraní',
			'gu' => 'guyaratí',
			'gv' => 'manés',
			'ha' => 'hausa',
			'he' => 'hebreo',
			'hi' => 'hindi',
			'ho' => 'hiri motu',
			'hr' => 'croata',
			'ht' => 'haitiano',
			'hu' => 'húngaro',
			'hy' => 'armenio',
			'hz' => 'herero',
			'ia' => 'interlingua',
			'id' => 'indonesio',
			'ie' => 'occidental',
			'ig' => 'igbo',
			'ii' => 'yi de Sichuán',
			'ik' => 'inupiaq',
			'io' => 'ido',
			'is' => 'islandés',
			'it' => 'italiano',
			'iu' => 'inuktitut',
			'ja' => 'japonés',
			'jv' => 'javanés',
			'ka' => 'georgiano',
			'kg' => 'kongo',
			'ki' => 'kikuyu',
			'kj' => 'kuanyama',
			'kk' => 'kazajo',
			'kl' => 'groenlandés',
			'km' => 'camboyano',
			'kn' => 'canarés',
			'ko' => 'coreano',
			'kr' => 'kanuri',
			'ks' => 'cachemiro',
			'ku' => 'kurdo',
			'kv' => 'komi',
			'kw' => 'córnico',
			'ky' => 'kirguís',
			'la' => 'latín',
			'lb' => 'luxemburgués',
			'lg' => 'luganda',
			'li' => 'limburgués',
			'ln' => 'lingala',
			'lo' => 'lao',
			'lt' => 'lituano',
			'lu' => 'luba-katanga',
			'lv' => 'letón',
			'mg' => 'malgache',
			'mh' => 'marshalés',
			'mi' => 'maorí',
			'mk' => 'macedonio',
			'ml' => 'malayalam',
			'mn' => 'mongol',
			'mr' => 'maratí',
			'ms' => 'malayo',
			'mt' => 'maltés',
			'my' => 'birmano',
			'na' => 'nauruano',
			'nb' => 'noruego bokmål',
			'nd' => 'ndebele del norte',
			'ne' => 'nepalí',
			'ng' => 'ndonga',
			'nl' => 'neerlandés',
			'nn' => 'nynorsk',
			'no' => 'noruego',
			'nr' => 'ndebele del sur',
			'nv' => 'navajo',
			'ny' => 'chichewa',
			'oc' => 'occitano',
			'oj' => 'ojibwa',
			'om' => 'oromo',
			'or' => 'oriya',
			'os' => 'osético',
			'pa' => 'panyabí',
			'pi' => 'pali',
			'pl' => 'polaco',
			'ps' => 'pastú',
			'pt' => 'portugués',
			'qu' => 'quechua',
			'rm' => 'romanche',
			'rn' => 'kirundi',
			'ro' => 'rumano',
			'ru' => 'ruso',
			'rw' => 'ruandés',
			'sa' => 'sánscrito',
			'sc' => 'sardo',
			'sd' => 'sindhi',
			'se' => 'sami septentrional',
			'sg' => 'sango',
			'si' => 'cingalés',
			'sk' => 'eslovaco',
			'sl' => 'esloveno',
			'sm' => 'samoano',
			'sn' => 'shona',
			'so' => 'somalí',
			'sq' => 'albanés',
			'sr' => 'serbio',
			'ss' => 'suazi',
			'st' => 'sesotho',
			'su' => 'sundanés',
			'sv' => 'sueco',
			'sw' => 'suajili',
			'ta' => 'tamil',
			'te' => 'telugú',
			'tg' => 'tayiko',
			'th' => 'tailandés',
			'ti' => 'tigriña',
			'tk' => 'turcomano',
			'tl' => 'tagalo',
			'tn' => 'setsuana',
			'to' => 'tongano',
			'tr' => 'turco',
			'ts' => 'tsonga',
			'tt' => 'tártaro',
			'tw' => 'twi',
			'ty' => 'tahitiano',
			'ug' => 'uigur',
			'uk' => 'ucraniano',
			'ur' => 'urdu',
			'uz' => 'uzbeko',
			've' => 'venda',
			'vi' => 'vietnamita',
			'vo' => 'volapük',
			'wa' => 'valón',
			'wo' => 'wolof',
			'xh' => 'xhosa',
			'yi' => 'yídish',
			'yo' => 'yoruba',
			'za' => 'chuan',
			'zh' => 'chino',
			'zu' => 'zulú'
			);
		}
		
	private function ipaises(){
		$this->paises =array(
			'afganistán' => 'AF',
			'afganistan' => 'AF',
			'albania' => 'AL',
			'alemania' => 'DE',
			'algeria' => 'DZ',
			'andorra' => 'AD',
			'angola' => 'AO',
			'anguila' => 'AI',
			'antártida' => 'AQ',
			'antartida' => 'AQ',
			'antigua y barbuda' => 'AG',
			'antillas neerlandesas' => 'AN',
			'arabia saudita' => 'SA',
			'argentina' => 'AR',
			'armenia' => 'AM',
			'aruba' => 'AW',
			'australia' => 'AU',
			'austria' => 'AT',
			'azerbayán' => 'AZ',
			'azerbayan' => 'AZ',
			'bélgica' => 'BE',
			'belgica' => 'BE',
			'bahamas' => 'BS',
			'bahrein' => 'BH',
			'bangladesh' => 'BD',
			'barbados' => 'BB',
			'belice' => 'BZ',
			'benín' => 'BJ',
			'benin' => 'BJ',
			'bhután' => 'BT',
			'bhutan' => 'BT',
			'bielorrusia' => 'BY',
			'birmania' => 'MM',
			'bolivia' => 'BO',
			'bosnia y herzegovina' => 'BA',
			'botsuana' => 'BW',
			'brasil' => 'BR',
			'brazil' => 'BR',
			'brunéi' => 'BN',
			'brunei' => 'BN',
			'bulgaria' => 'BG',
			'burkina faso' => 'BF',
			'burundi' => 'BI',
			'cabo verde' => 'CV',
			'camboya' => 'KH',
			'camerún' => 'CM',
			'camerun' => 'CM',
			'canadá' => 'CA',
			'canada' => 'CA',
			'chad' => 'TD',
			'chile' => 'CL',
			'china' => 'CN',
			'chipre' => 'CY',
			'ciudad del vaticano' => 'VA',
			'colombia' => 'CO',
			'comoras' => 'KM',
			'congo' => 'CG',
			'congo' => 'CD',
			'corea del norte' => 'KP',
			'corea del sur' => 'KR',
			'costa de marfil' => 'CI',
			'costa rica' => 'CR',
			'croacia' => 'HR',
			'cuba' => 'CU',
			'dinamarca' => 'DK',
			'dominica' => 'DM',
			'ecuador' => 'EC',
			'egipto' => 'EG',
			'el salvador' => 'SV',
			'emiratos árabes unidos' => 'AE',
			'emiratos arabes unidos' => 'AE',
			'eritrea' => 'ER',
			'eslovaquia' => 'SK',
			'eslovenia' => 'SI',
			'españa' => 'ES',
			'estados unidos de américa' => 'US',
			'estados unidos de america' => 'US',
			'estados unidos' => 'US',
			'estonia' => 'EE',
			'etiopía' => 'ET',
			'etiopia' => 'ET',
			'filipinas' => 'PH',
			'finlandia' => 'FI',
			'fiyi' => 'FJ',
			'francia' => 'FR',
			'gabón' => 'GA',
			'gabon' => 'GA',
			'gambia' => 'GM',
			'georgia' => 'GE',
			'ghana' => 'GH',
			'gibraltar' => 'GI',
			'granada' => 'GD',
			'grecia' => 'GR',
			'groenlandia' => 'GL',
			'guadalupe' => 'GP',
			'guam' => 'GU',
			'guatemala' => 'GT',
			'guayana francesa' => 'GF',
			'guernsey' => 'GG',
			'guinea' => 'GN',
			'guinea ecuatorial' => 'GQ',
			'guinea-bissau' => 'GW',
			'guyana' => 'GY',
			'haití' => 'HT',
			'haiti' => 'HT',
			'honduras' => 'HN',
			'hong kong' => 'HK',
			'hungría' => 'HU',
			'india' => 'IN',
			'indonesia' => 'ID',
			'irán' => 'IR',
			'iran' => 'IR',
			'irak' => 'IQ',
			'irlanda' => 'IE',
			'isla bouvet' => 'BV',
			'isla de man' => 'IM',
			'isla de navidad' => 'CX',
			'isla norfolk' => 'NF',
			'islandia' => 'IS',
			'islas bermudas' => 'BM',
			'islas caimán' => 'KY',
			'islas caiman' => 'KY',
			'islas cocos (keeling)' => 'CC',
			'islas cocos' => 'CC',
			'islas keeling' => 'CC',
			'islas cook' => 'CK',
			'islas de åland' => 'AX',
			'islas de aland' => 'AX',
			'islas feroe' => 'FO',
			'islas georgias del sur y sandwich del sur' => 'GS',
			'islas heard y mcdonald' => 'HM',
			'islas maldivas' => 'MV',
			'islas malvinas' => 'FK',
			'islas marianas del norte' => 'MP',
			'islas marshall' => 'MH',
			'islas pitcairn' => 'PN',
			'islas salomón' => 'SB',
			'islas salomon' => 'SB',
			'islas turcas y caicos' => 'TC',
			'islas ultramarinas menores de estados unidos' => 'UM',
			'islas vírgenes británicas' => 'VG',
			'islas virgenes britanicas' => 'VG',
			'islas vírgenes de los estados unidos' => 'VI',
			'islas virgenes de los estados unidos' => 'VI',
			'israel' => 'IL',
			'italia' => 'IT',
			'jamaica' => 'JM',
			'japón' => 'JP',
			'japon' => 'JP',
			'jersey' => 'JE',
			'jordania' => 'JO',
			'kazajistán' => 'KZ',
			'kazajistan' => 'KZ',
			'kenia' => 'KE',
			'kirgizstán' => 'KG',
			'kirgizstan' => 'KG',
			'kiribati' => 'KI',
			'kuwait' => 'KW',
			'líbano' => 'LB',
			'libano' => 'LB',
			'laos' => 'LA',
			'lesoto' => 'LS',
			'letonia' => 'LV',
			'liberia' => 'LR',
			'libia' => 'LY',
			'liechtenstein' => 'LI',
			'lituania' => 'LT',
			'luxemburgo' => 'LU',
			'méxico' => 'MX',
			'mexico' => 'MX',
			'mónaco' => 'MC',
			'monaco' => 'MC',
			'macao' => 'MO',
			'macedônia' => 'MK',
			'macedonia' => 'MK',
			'madagascar' => 'MG',
			'malasia' => 'MY',
			'malawi' => 'MW',
			'mali' => 'ML',
			'malta' => 'MT',
			'marruecos' => 'MA',
			'martinica' => 'MQ',
			'mauricio' => 'MU',
			'mauritania' => 'MR',
			'mayotte' => 'YT',
			'micronesia' => 'FM',
			'moldavia' => 'MD',
			'mongolia' => 'MN',
			'montenegro' => 'ME',
			'montserrat' => 'MS',
			'mozambique' => 'MZ',
			'namibia' => 'NA',
			'nauru' => 'NR',
			'nepal' => 'NP',
			'nicaragua' => 'NI',
			'niger' => 'NE',
			'nigeria' => 'NG',
			'niue' => 'NU',
			'noruega' => 'NO',
			'nueva caledonia' => 'NC',
			'nueva zelanda' => 'NZ',
			'omán' => 'OM',
			'oman' => 'OM',
			'países bajos' => 'NL',
			'paises bajos' => 'NL',
			'pakistán' => 'PK',
			'pakistan' => 'PK',
			'palau' => 'PW',
			'palestina' => 'PS',
			'panamá' => 'PA',
			'papúa nueva guinea' => 'PG',
			'papua nueva guinea' => 'PG',
			'paraguay' => 'PY',
			'perú' => 'PE',
			'peru' => 'PE',
			'polinesia francesa' => 'PF',
			'polonia' => 'PL',
			'portugal' => 'PT',
			'puerto rico' => 'PR',
			'qatar' => 'QA',
			'reino unido' => 'GB',
			'república centroafricana' => 'CF',
			'republica centroafricana' => 'CF',
			'república checa' => 'CZ',
			'republica checa' => 'CZ',
			'república dominicana' => 'DO',
			'republica dominicana' => 'DO',
			'reunión' => 'RE',
			'reunion' => 'RE',
			'ruanda' => 'RW',
			'rumanía' => 'RO',
			'rumania' => 'RO',
			'rusia' => 'RU',
			'sahara occidental' => 'EH',
			'samoa' => 'WS',
			'samoa americana' => 'AS',
			'san bartolomé' => 'BL',
			'san cristóbal y nieves' => 'KN',
			'san cristobal y nieves' => 'KN',
			'san marino' => 'SM',
			'san martín (francia)' => 'MF',
			'san martin (francia)' => 'MF',
			'san pedro y miquelón' => 'PM',
			'san pedro y miquelon' => 'PM',
			'san vicente y las granadinas' => 'VC',
			'santa elena' => 'SH',
			'santa lucía' => 'LC',
			'santa lucia' => 'LC',
			'santo tomé y príncipe' => 'ST',
			'santo tome y príncipe' => 'ST',
			'senegal' => 'SN',
			'serbia' => 'RS',
			'seychelles' => 'SC',
			'sierra leona' => 'SL',
			'singapur' => 'SG',
			'siria' => 'SY',
			'somalia' => 'SO',
			'sri lanka' => 'LK',
			'sudáfrica' => 'ZA',
			'sudafrica' => 'ZA',
			'sudán' => 'SD',
			'sudan' => 'SD',
			'suecia' => 'SE',
			'suiza' => 'CH',
			'surinám' => 'SR',
			'svalbard y jan mayen' => 'SJ',
			'swazilandia' => 'SZ',
			'tadjikistán' => 'TJ',
			'tailandia' => 'TH',
			'taiwán' => 'TW',
			'taiwan' => 'TW',
			'tanzania' => 'TZ',
			'territorio británico del océano Índico' => 'IO',
			'territorio británico del oceano Índico' => 'IO',
			'territorios australes y antárticas franceses' => 'TF',
			'territorios australes y antarticas franceses' => 'TF',
			'timor oriental' => 'TL',
			'togo' => 'TG',
			'tokelau' => 'TK',
			'tonga' => 'TO',
			'trinidad y tobago' => 'TT',
			'tunez' => 'TN',
			'turkmenistán' => 'TM',
			'turkmenistan' => 'TM',
			'turquía' => 'TR',
			'turquia' => 'TR',
			'tuvalu' => 'TV',
			'ucrania' => 'UA',
			'uganda' => 'UG',
			'uruguay' => 'UY',
			'uzbekistán' => 'UZ',
			'uzbekistan' => 'UZ',
			'vanuatu' => 'VU',
			'venezuela' => 'VE',
			'vietnam' => 'VN',
			'wallis y futuna' => 'WF',
			'yemen' => 'YE',
			'yibuti' => 'DJ',
			'zambia' => 'ZM',
			'zimbabue' => 'ZW'
		);

	}
}
