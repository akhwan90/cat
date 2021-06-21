<?php
namespace App\Libraries;

define('CAPTCHA_SESSION_VAR','CAPTCHA');

/**
// Display captcha
echo Captcha::html(); // you could pass text and background color
// => <img src="..." />
// Perform verification
Captcha::check($_GET['captcha']); // return true if the test string is the same as the last Captcha generated
*/

class Captcha
{
	static public function reset($k=NULL)
	{
		$_SESSION[CAPTCHA_SESSION_VAR] = NULL;
	}
	
	static public function html($color='#333',$bg=NULL)
	{
		// on génère un code
		if(!isset($_SESSION[CAPTCHA_SESSION_VAR]) || $_SESSION[CAPTCHA_SESSION_VAR]===NULL)
			$_SESSION[CAPTCHA_SESSION_VAR] = rand(1000000,9999999);

		// ***********************************************
		// ***********************************************
		// ***********************************************
		$_img = imagecreatetruecolor(100,20);
		imagealphablending($_img, false);
		imagesavealpha($_img, true);

		if($bg===NULL) // si aucun fond on exploite la transparence du PNG
		{
			$bg = imagecolorallocatealpha($_img, 0, 0, 0, 127);
			imagecolortransparent($_img,$bg);
		}
		else // sinon on remplira de la couleur demandé
		{
			$bg = self::RGB($bg);
			$bg = imagecolorallocatealpha($_img, $bg[0], $bg[1], $bg[2],0); // couleur d'arrière plan
		}
		imagefill($_img, 0, 0, $bg);

		// on prépare ensuite la couleur du texte et des traits
		$color = self::RGB($color);
		$color = imagecolorallocatealpha($_img, $color[0], $color[1], $color[2],0); // Couleur des chiffres

		imagestring($_img, 5, 18, 3, $_SESSION[CAPTCHA_SESSION_VAR], $color);
		imageline($_img, 10, 8, 87, 8, $color);
		imageline($_img, 13, 12, 90, 12, $color);
		ob_start();
		imagepng($_img);
		$img64 = base64_encode(ob_get_clean());
		return '<img src="data:image/png;base64,'.$img64.'" />';
	}
	
	static public function check($str)
	{
		return !empty($_SESSION[CAPTCHA_SESSION_VAR]) && $str==$_SESSION[CAPTCHA_SESSION_VAR];
	}

	static public function RGB($rgb)
	{
		if(is_string($rgb))
		{
			$rgb = str_replace('#','',$rgb); // #123 => 123
			
			if(strlen($rgb)===3) // 123 => 112233
				$rgb = preg_replace('/^(.)(.)(.)$/','\\1\\1\\2\\2\\3\\3',$rgb);

			$r = hexdec(substr($rgb,0,2));
			$g = hexdec(substr($rgb,2,2));
			$b = hexdec(substr($rgb,4,2));
		}
		else
		{
			list($r, $g, $b) = $rgb;
			if($g==NULL || $b==NULL)
			{
				$r = $g = $b = $rgb;
			}
		}
		return array($r,$g,$b);
	}
}
?>