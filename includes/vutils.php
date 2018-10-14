<?PHP

function redirectPage($page) {
	header('Location: ' . BASE_URL . $page);
	exit;
}

function escapeSql($input) {
	return mysql_real_escape_string($input);
}


function destroySession() {
	session_start();

	$_SESSION = array();
	
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	
	session_destroy();
}
function generateRandomString($length = 6) {
    return substr(str_shuffle("23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ"), 0, $length);
}
function encrypt2Way($input) {
		$password = 'vamsi6985';
		$iv_len = 8;
		$input .= "\x13";
		$n = strlen($input);
		if ($n % 8) $input .= str_repeat("\0", 8 - ($n % 8));
		$i = 0;
		//$enc_text = getRandomIV($iv_len);
		$enc_text = "krishnas";
		$iv = substr($password ^ $enc_text, 0, 512);
		while ($i < $n) {
		   $block = substr($input, $i, 8) ^ pack('H*', md5($iv));
		   $enc_text .= $block;
		   $iv = substr($block . $iv, 0, 512) ^ $password;
		   $i += 8;
		}
		return base64_encode($enc_text);
	}


	function decrypt($input) {
		$password = 'vamsi6985';
		$iv_len = 8;
		$input = base64_decode($input);
		$n = strlen($input);
		$i = $iv_len;
		$plain_text = '';
		$iv = substr($password ^ substr($input, 0, $iv_len), 0, 512);
		while ($i < $n) {
		   $block = substr($input, $i, 8);
		   $plain_text .= $block ^ pack('H*', md5($iv));
		   $iv = substr($block . $iv, 0, 512) ^ $password;
		   $i += 8;
		}
		return preg_replace('/\\x13\\x00*$/', '', $plain_text);
	}

// Date should be in DD/MM/YYYY format
function XLSconvertDateToDBFormat($inputDate, $withTime = false) {
	if($withTime == true) {
		$parts = explode(' ', $inputDate);
		$time = count($parts) == 2 ? (' ' . $parts[1]) : '';
		$parts = explode('/', $parts[0]);
		
		return $parts[2] . '-' . $parts[1] . '-' . $parts[0] . ' ' . $time;
		//return $parts[2] . '-' . $parts[0] . '-' . $parts[1] . ' ' . $time;
	} else {
		$parts = explode('/', $inputDate);
		return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
		//return $parts[2] . '-' . $parts[0] . '-' . $parts[1];		
	}
}

// Date should be in DD/MM/YYYY format
function convertDateToDBFormat($inputDate, $withTime = false) {
	if($withTime == true) {
		$parts = explode(' ', $inputDate);
		$time = count($parts) == 2 ? (' ' . $parts[1]) : '';
		$parts = explode('/', $parts[0]);
		
		return $parts[2] . '-' . $parts[0] . '-' . $parts[1] . ' ' . $time;
	} else {
		$parts = explode('/', $inputDate);
		return $parts[2] . '-' . $parts[0] . '-' . $parts[1];	
	}
}

// Date should be in DD/MM/YYYY format
	function toDBFormat($inputDate, $withTime = false) {
		if($withTime == true) {
			$parts = explode(' ', $inputDate);
			$time = count($parts) == 2 ? (' ' . $parts[1]) : '';
			$parts = explode('/', $parts[0]);
			
			return $parts[2] . '-' . $parts[0] . '-' . $parts[1] . ' ' . $time;
		} else {
			$parts = explode('/', $inputDate);
			return $parts[2] . '-' . $parts[0] . '-' . $parts[1];	
		}
	}

	// Date should be in MM-DD-YYYY HH:mm:ss format
	function toUSFormat($inputDate, $withTime = true) {
		$parts = explode(' ', $inputDate);
		$time = count($parts) == 2 ? (' ' . $parts[1]) : '';
		$parts = explode('-', $parts[0]);
		
		if($withTime) {
			//$datetime = $parts[1] . '/' . $parts[2] . '/' . $parts[0] . $time;
			//return date("m/d/Y h:i:s a", strtotime($datetime));
			return $parts[1] . '/' . $parts[2] . '/' . $parts[0] . $time;
		} else {
			return $parts[1] . '/' . $parts[2] . '/' . $parts[0];
		}
	}


function sendEmail($from, $to, $subject, $body) {
	if (LOCAL) {
		return true;	
	}
	$optional = "-f".$from;
	$mailheaders = "MIME-Version: 1.0\n";
	$mailheaders .= "Content-type: text/html; charset=iso-8859-1\n";
	$mailheaders .= "X-Priority: 3\n";
	$mailheaders .= "X-MSMail-Priority: Normal\n";
	$mailheaders .= "X-Mailer: php\n";
	$mailheaders .= "From: NCPL Consulting <$from>\n";
	$mailheaders .= "Reply-to: NCPL Consulting <$from>\n";
	$mailheaders .= "Return-path: NCPL Consulting <$from>\n";
	
	return mail($to, $subject, $body, $mailheaders);
}

function contactEmail($from, $to, $subject, $body) {
	if (LOCAL) {
		return true;	
	}
	$optional = "-f".$from;
	$mailheaders = "MIME-Version: 1.0\n";
	$mailheaders .= "Content-type: text/html; charset=iso-8859-1\n";
	$mailheaders .= "X-Priority: 3\n";
	$mailheaders .= "X-MSMail-Priority: Normal\n";
	$mailheaders .= "X-Mailer: php\n";
	$mailheaders .= "From: <$from>\n";
	$mailheaders .= "Reply-to: <donotreply@http://icheckin.ncplinc.net>\n";
	$mailheaders .= "Return-path: <donotreply@http://icheckin.ncplinc.net>\n";
	
	return mail($to, $subject, $body, $mailheaders);
}

function fillDropdown($query, $defaultValue = NULL) {

	$options = '';
	
	$db = new VDatabase(true);
	
	$rows = $db->getRows($query);
	
	$db->closeConnection();
	
	foreach($rows as $row) {
		
		$options .= '<option value="' . $row[0] . '"' . ($defaultValue == $row[0] ? ' selected="selected"' : '') .  '>' . $row[1] . '</option>';	
		
	}
	
	return $options;

}

	
	function limitstring($str, $limit=100, $strip = false) {
	    $str = ($strip == true)?strip_tags($str):$str;
	    if (strlen ($str) > $limit) {
	        $str = substr ($str, 0, $limit - 3);
	        return (substr ($str, 0, strrpos ($str, ' ')).'...');
	    }
	    return trim($str);
	}
	
	
	function states($defaultValue = NULL)
	{
		$states = '
		
			<option value="Alabama" '.($defaultValue == "Alabama" ? 'selected="selected"' : '').'>Alabama</option> 
			<option value="Alaska" '.($defaultValue == "Alaska" ? 'selected="selected"' : '').'>Alaska</option> 
			<option value="Arizona" '.($defaultValue == "Arizona" ? 'selected="selected"' : '').'>Arizona</option> 
			<option value="Arkansas" '.($defaultValue == "Arkansas" ? 'selected="selected"' : '').'>Arkansas</option> 
			<option value="California" '.($defaultValue == "California" ? 'selected="selected"' : '').'>California</option> 
			<option value="Colorado" '.($defaultValue == "Colorado" ? 'selected="selected"' : '').'>Colorado</option> 
			<option value="Connecticut" '.($defaultValue == "Connecticut" ? 'selected="selected"' : '').'>Connecticut</option> 
			<option value="Delaware" '.($defaultValue == "Delaware" ? 'selected="selected"' : '').'>Delaware</option> 
			<option value="Florida" '.($defaultValue == "Florida" ? 'selected="selected"' : '').'>Florida</option> 
			<option value="Georgia" '.($defaultValue == "Georgia" ? 'selected="selected"' : '').'>Georgia</option> 
			<option value="Hawaii" '.($defaultValue == "Hawaii" ? 'selected="selected"' : '').'>Hawaii</option> 
			<option value="Idaho" '.($defaultValue == "Idaho" ? 'selected="selected"' : '').'>Idaho</option> 
			<option value="Illinois" '.($defaultValue == "Illinois" ? 'selected="selected"' : '').'>Illinois</option> 
			<option value="Indiana" '.($defaultValue == "Indiana" ? 'selected="selected"' : '').'>Indiana</option> 
			<option value="Iowa" '.($defaultValue == "Iowa" ? 'selected="selected"' : '').'>Iowa</option> 
			<option value="Kansas" '.($defaultValue == "Kansas" ? 'selected="selected"' : '').'>Kansas</option> 
			<option value="Kentucky" '.($defaultValue == "Kentucky" ? 'selected="selected"' : '').'>Kentucky</option> 
			<option value="Louisiana" '.($defaultValue == "Louisiana" ? 'selected="selected"' : '').'>Louisiana</option> 
			<option value="Maine" '.($defaultValue == "Maine" ? 'selected="selected"' : '').'>Maine</option> 
			<option value="Maryland" '.($defaultValue == "Maryland" ? 'selected="selected"' : '').'>Maryland</option> 
			<option value="Massachusetts" '.($defaultValue == "Massachusetts" ? 'selected="selected"' : '').'>Massachusetts</option> 
			<option value="Michigan" '.($defaultValue == "Michigan" ? 'selected="selected"' : '').'>Michigan</option> 
			<option value="Minnesota" '.($defaultValue == "Minnesota" ? 'selected="selected"' : '').'>Minnesota</option> 
			<option value="Mississippi" '.($defaultValue == "Mississippi" ? 'selected="selected"' : '').'>Mississippi</option> 
			<option value="Missouri" '.($defaultValue == "Missouri" ? 'selected="selected"' : '').'>Missouri</option> 
			<option value="Montana" '.($defaultValue == "Montana" ? 'selected="selected"' : '').'>Montana</option> 
			<option value="Nebraska" '.($defaultValue == "Nebraska" ? 'selected="selected"' : '').'>Nebraska</option> 
			<option value="Nevada" '.($defaultValue == "Nevada" ? 'selected="selected"' : '').'>Nevada</option> 
			<option value="New Hampshire" '.($defaultValue == "New Hampshire" ? 'selected="selected"' : '').'>New Hampshire</option> 
			<option value="New Jersey" '.($defaultValue == "New Jersey" ? 'selected="selected"' : '').'>New Jersey</option> 
			<option value="New Mexico" '.($defaultValue == "New Mexico" ? 'selected="selected"' : '').'>New Mexico</option> 
			<option value="New York" '.($defaultValue == "New York" ? 'selected="selected"' : '').'>New York</option> 
			<option value="North Carolina" '.($defaultValue == "North Carolina" ? 'selected="selected"' : '').'>North Carolina</option> 
			<option value="North Dakota" '.($defaultValue == "North Dakota" ? 'selected="selected"' : '').'>North Dakota</option> 
			<option value="Ohio" '.($defaultValue == "Ohio" ? 'selected="selected"' : '').'>Ohio</option> 
			<option value="Oklahoma" '.($defaultValue == "Oklahoma" ? 'selected="selected"' : '').'>Oklahoma</option> 
			<option value="Oregon" '.($defaultValue == "Oregon" ? 'selected="selected"' : '').'>Oregon</option> 
			<option value="Pennsylvania" '.($defaultValue == "Pennsylvania" ? 'selected="selected"' : '').'>Pennsylvania</option> 
			<option value="Rhode Island" '.($defaultValue == "Rhode Island" ? 'selected="selected"' : '').'>Rhode Island</option> 
			<option value="South Carolina" '.($defaultValue == "South Carolina" ? 'selected="selected"' : '').'>South Carolina</option> 
			<option value="South Dakota" '.($defaultValue == "South Dakota" ? 'selected="selected"' : '').'>South Dakota</option> 
			<option value="Tennessee" '.($defaultValue == "Tennessee" ? 'selected="selected"' : '').'>Tennessee</option> 
			<option value="Texas" '.($defaultValue == "Texas" ? 'selected="selected"' : '').'>Texas</option> 
			<option value="Utah" '.($defaultValue == "Utah" ? 'selected="selected"' : '').'>Utah</option> 
			<option value="Vermont" '.($defaultValue == "Vermont" ? 'selected="selected"' : '').'>Vermont</option> 
			<option value="Virginia" '.($defaultValue == "Virginia" ? 'selected="selected"' : '').'>Virginia</option> 
			<option value="Washington" '.($defaultValue == "Washington" ? 'selected="selected"' : '').'>Washington</option> 
			<option value="West Virginia" '.($defaultValue == "West Virginia" ? 'selected="selected"' : '').'>West Virginia</option> 
			<option value="Wisconsin" '.($defaultValue == "Wisconsin" ? 'selected="selected"' : '').'>Wisconsin</option> 
			<option value="Wyoming" '.($defaultValue == "Wyoming" ? 'selected="selected"' : '').'>Wyoming</option> 
		';
		return $states;
	}
	
	function swagHeaders()
	{
		header("Access-Control-Allow-Origin: *");
		header('Content-Type, api_key, Authorization, x-requested-with, Total-Count, Total-Pages, Error-Message');
		header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT');
		header('Access-Control-Max-Age:1800');
		header('Content-Type: application/json;charset=UTF-8');
	}
?>
