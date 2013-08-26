<?php

function MimeType($filename){
	$ext = pathinfo($filename);
	$ext = $ext['extension'];
	
	switch($ext){
		case "bmp": return "image/bmp"; break;
		case "gif": return "image/gif"; break;
		case "jpe": return "image/jpeg"; break;
		case "jpeg": return "image/jpeg"; break;
		case "jpg": return "image/jpeg"; break;
		case "png": return "image/png"; break;
		case "swf": return "application/x-shockwave-flash"; break;
		case "tif": return "image/tiff"; break;
		case "tiff": return "image/tiff"; break;
		default: return ""; break;
	}
}

function imageshackUpload($filename){
	$sock = @fsockopen("www.imageshack.us", 80, $errno, $errstr, 30);
	
	$handle = fopen($filename, "r");
	$binarydata = fread($handle, filesize($filename));
	fclose($handle);
	
	$mimetype = MimeType($filename);
	
	$AaB03x  = "--AaB03x\r\n";
	$AaB03x .="content-disposition: form-data; name=\"uploadtype\"\r\n\r\n";
	
	$AaB03x .= "on\r\n";
	$AaB03x .= "--AaB03x\r\n";
	$AaB03x .= "content-disposition: form-data; name=\"fileupload\"; filename=\"".basename($filename)."\"\r\n";
	$AaB03x .= "Content-Type: $mimetype\r\n";
	$AaB03x .= "Content-Transfer-Encoding: binary\r\n\r\n";
	
	$AaB03x .= "$binarydata\r\n";
	$AaB03x .= "--AaB03x--\r\n";
	
	$header  = "POST / HTTP/1.1\r\n";
	$header .= "Host: www.imageshack.us\r\n";
	$header .= "Content-type: multipart/form-data, boundary=AaB03x\r\n";
	$header .= "Content-Length: ".strlen($AaB03x)."\r\n\r\n";
	
	$header .= $AaB03x;
	
	fwrite($sock, $header);
				
	while (!feof($sock)){
		$response .= fgets($sock, 128);
	}
	
	fclose($sock);
	
	preg_match_all("#\<input type\=\"text\" onclick\=\"highlight\(this\)\" style\=\"width\: 500px\" size\=\"70\" value\=\"\[URL\=http\:\/\/imageshack\.us\]\[IMG\](.*)\[\/IMG\]\[\/URL\]\"\/\>#", $response, $matches);
	
	return $matches[1][0];
}

?>

<html>
<?php if(!isset($_POST['sub'])){ ?>
	<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>"> 
	Enter path to file: <input type="text" name="test" /><br />
	<input type="submit" name="sub" />
	</form> 	
<?php } else { 
	echo imageshackUpload($_POST['test']);
}?>
</html>
