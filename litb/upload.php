<?php
	require('litbheader.php');	
	
	header("Content-Type: text/html; charset=utf-8");
	//echo "<pre>";
	//print_r($_FILES);
	//echo "</pre>";
	
	$utmcampaign = $_POST['utmcampaign'];
	
	$username = $_POST['username'];
	$filename = $_POST['filename'];
	//echo 'file name : '.$filename.'<br />';
	
	//echo $utmcampaign.'<br />';
	//echo $username.'<br />';
	//echo $passwd.'<br />';
	$flag = true;
	for ($i=0; $i<strlen($username); $i++)
	{
		$c = substr($username, $i, 1); //将单个字符存到数组当中
		if (! ( $c >= 'a' && $c <= 'z' || $c >= 'A' && $c <= 'Z' || $c >= '0' && $c <= '9' ))
		{
			$flag = false;
			break;
		}
	}
	if ($flag == false)
	{
		echo 'username should only contains letters and numbers<br />';
		exit;
	}
	
	if ($username == '')
	{
		echo 'please input the username<br />';
		echo 'because username is directly related with the file name:)<br />';
		exit;
	}
	
	$username_arr = array('jiyuliang', 'liuxiaoyan', 'liutianfeng', 'guoguo');
	$isin = in_array($username, $username_arr);
	if (! $isin)
	{
		echo 'the username "'.$username.'" not in username list<br />';
		exit;
	}
	
	if ($filename == '')
	{
		echo 'filename should not be empty<br />';
		exit;
	}
	
	/* xml file */
	//得到文件类型
	$tmpfile = $_FILES['xmlfile']['tmp_name'];
	$filetype = explode('/',$_FILES['xmlfile']['type']);
	$filetype = $filetype[0];
	
	//得到文件后缀
	$fname = $_FILES['xmlfile']['name'];
	$fileext = explode('.',$fname);
	$cnt = count($fileext);
	$fileext = (string)$fileext[$cnt-1];
	
	$xmlfile = 'uploads/'.$username.'/'.$filename.'.'.$fileext;
	if (file_exists($xmlfile))
	{
		if (!unlink($xmlfile))
		{
			echo $xmlfile.' 旧文件已存在，但是删除的时候出了一些问题<br />';
		}
	}
	
	if(move_uploaded_file($tmpfile,$xmlfile)) {
		;
	}
	else {
		echo "xml文件上传失败<br />";
		exit;
	}

	//得到文件类型
	$tmpfile = $_FILES['excelfile']['tmp_name'];
	$filetype = explode('/',$_FILES['excelfile']['type']);
	$filetype = $filetype[0];
	
	//得到文件后缀
	$fname = $_FILES['excelfile']['name'];
	$fileext = explode('.',$fname);
	$cnt = count($fileext);
	$fileext = (string)$fileext[$cnt-1];
	
	$excelfile = 'uploads/'.$username.'/'.$filename.'.'.$fileext;
	if (file_exists($excelfile))
	{
		if (!unlink($excelfile))
		{
			echo $excelfile.' 旧文件已存在，但是删除的时候出了一些问题<br />';
		}
	}
	
	if(move_uploaded_file($tmpfile,$excelfile)) {
		;
	}
	else {
		echo "excel文件上传失败<br />";
		exit;
	}
	
	//$content = file_get_contents('uploads/'.$username.'.'.$fileext);
	//echo 'content:<br /><pre>';
	//echo $content;
	//echo '</pre>';
		
	//header("content-type:text/html;charset=utf-8"); 
	//文件路径 
	//$fp=fopen($file_path,"a+");
	//$file_path=$xmlfile;
	//$conn=file_get_contents($file_path); 
	//$conn=str_replace("rn","<br/>",file_get_contents($file_path)); 
	//echo $conn; 
	//fclose($fp);
	
	// 读取xml文件内容
	$fp = fopen($xmlfile,"a+");
	$content = file_get_contents($xmlfile);
	fclose($fp);
	
	/* 数据处理1开始 */
	
	// 处理utm campaign
	//$conntent = preg_replace("<utm-campaign>", "hello", $content);
    //$uuu = '<utm-campaign>';
    //$pos = strpos($content, $uuu);
	//echo 'pos : '.$pos.'<br />';
	
//	$content = preg_replace("/<utm-campaign>MITB20151208<\/utm-campaign>/", "<utm-campaign>".$utmcampaign."</utm-campaign>", $content);
//	$content = preg_replace("/&utm_campaign=MITB20151208&/", "&utm_campaign=".$utmcampaign."&", $content);
	
	
	/* 数据处理1结束 */
	
	/* 读取excel数据开始 */
	
	require_once '../phpexcel/Classes/PHPExcel/IOFactory.php';
	
	$reader = PHPExcel_IOFactory::createReader('Excel5'); //设置以Excel5格式(Excel97-2003工作簿)
	$PHPExcel = $reader->load($excelfile); // 载入excel文件
	$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
	$highestRow = $sheet->getHighestRow(); // 取得总行数
	$highestColumm = $sheet->getHighestColumn(); // 取得总列数
	//echo '总行数： '.$highestRow.'<br />';
	//echo '总列数： '.$highestColumm.'<br />';
	
	for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
		$value = $sheet->getCellByColumnAndRow(0, $row)->getValue();
		$content = preg_replace("/<id>.*<\/id>/", "<id2>".$value."</id>", $content, 1);
	}
	$content = preg_replace("/<id2>/", "<id>", $content);
	
	/* 读取excel数据结束 */
	
	$resultfile = 'result/'.$username.'/'.$filename.'.xml';
//	echo 'result file path : '.$resultfile.'<br />';
//	$resultfile = iconv("UTF-8","GBK", $resultfile);
//	echo 'result file path : '.$resultfile.'<br />';
	$fp = fopen($resultfile,"w");
//	$resultfile = iconv("GBK", "UTF-8", $resultfile);
//	echo 'result file path : '.$resultfile.'<br />';
	if (!fwrite($fp, $content)) {//执行写入文件
		fclose($fp);
		echo 'failed!<br />';
		exit;
	}
	else 
	{
		echo 'succeed!<br />';
		fclose($fp);
		
		echo '<a href="'.$resultfile.'">show the result online</a><br />';
		echo '<a href="'.$resultfile.'" download="'.$filename.'.xml">download the file</a><br />';
	}
	

	require('litbfooter.php');
?>
