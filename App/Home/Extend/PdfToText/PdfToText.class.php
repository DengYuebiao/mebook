<?php
namespace Home\Extend\PdfToText;

/*
用于将pdf转换为text
*/
class PdfToText{
	
	/*
	获取pdf的文本
	@param string pdf文本
	@return string
	*/
	static function getText($pdf_file)
	{
		$curr_dir=dirname(__FILE__);
		$sep=DIRECTORY_SEPARATOR;
		$file_name=basename($pdf_file);
		$pos=strpos($file_name,".");
		$file_left_name=$pos!==false?substr($file_name,0,$pos):$file_name;
		$dest_file=dirname($pdf_file).$sep.$file_left_name.".ptxt";
		$os_ver=trim(exec("file /bin/ls | cut -c14-15"));
		$exe_str=(DIRECTORY_SEPARATOR=='\\')?"\"{$curr_dir}{$sep}bin{$sep}pdftotext.exe\"":"\"{$curr_dir}{$sep}bin{$sep}pdftotext{$os_ver}\"";
		$cmd_str="{$exe_str} -cfg \"{$curr_dir}{$sep}bin{$sep}cn{$sep}xpdfrc\" -raw -q {$pdf_file} {$dest_file}";
		exec($cmd_str);
		$pdf_txt=file_get_contents($dest_file);
		@unlink($dest_file);
		return $pdf_txt;
	}
	

	/*
	更新map配置的dir目录
	*/
	static function upMapDir()
	{
		
		$curr_dir=dirname(__FILE__);
		$sep=DIRECTORY_SEPARATOR;
		$cnf_str="#----- begin Chinese Simplified support package (2011-sep-02)
cidToUnicode	Adobe-GB1	{$curr_dir}{$sep}bin{$sep}cn{$sep}Adobe-GB1.cidToUnicode
unicodeMap	ISO-2022-CN	{$curr_dir}{$sep}bin{$sep}cn{$sep}ISO-2022-CN.unicodeMap
unicodeMap	EUC-CN		{$curr_dir}{$sep}bin{$sep}cn{$sep}EUC-CN.unicodeMap
unicodeMap	GBK		{$curr_dir}{$sep}bin{$sep}cn{$sep}GBK.unicodeMap
cMapDir		Adobe-GB1	{$curr_dir}{$sep}bin{$sep}cn{$sep}CMap
toUnicodeDir			{$curr_dir}{$sep}bin{$sep}cn{$sep}CMap
textPageBreaks      no  
textEncoding        UTF-8 ";
		file_put_contents("{$curr_dir}{$sep}bin{$sep}cn{$sep}xpdfrc",$cnf_str);
	}
	
}