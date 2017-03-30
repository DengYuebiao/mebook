<?php
/*
二维码显示类
*/	
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'tcpdf_barcodes_2d.php');

class MyQrcode extends TCPDF2DBarcode{
	
	
	/**
	 * 生成二维码png图片
	 *	@param string png文件名称 最好为唯一
	 *	@param int 宽度
	 *	@param int 高度
	 *	@param array 二维码背景颜色
 	 * @return image or false in case of error.
 	 * @public
	 */
	public function genQrcodePNG($file_name,$w=4, $h=4, $color=array(0,51,102)) {
		
		$file_path="/Public/Uploads/qrcode/".$file_name;
		
		$file_path_local=$_SERVER['DOCUMENT_ROOT'].$file_path;
		$file_dir=dirname($file_path_local);
		if(!file_exists($file_dir))
		{
			mkdir($file_dir,0770);
		}
		if(file_exists($file_path_local))
		{
			unlink($file_path_local);
		}
		// calculate image size
		$width = ($this->barcode_array['num_cols'] * $w);
		$height = ($this->barcode_array['num_rows'] * $h);
		if (function_exists('imagecreate')) {
			// GD library
			$imagick = false;
			$png = imagecreate($width, $height);
			$bgcol = imagecolorallocate($png, 255, 255, 255);
			imagecolortransparent($png, $bgcol);
			$fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
		} elseif (extension_loaded('imagick')) {
			$imagick = true;
			$bgcol = new imagickpixel('rgb(255,255,255');
			$fgcol = new imagickpixel('rgb('.$color[0].','.$color[1].','.$color[2].')');
			$png = new Imagick();
			$png->newImage($width, $height, 'none', 'png');
			$bar = new imagickdraw();
			$bar->setfillcolor($fgcol);
		} else {
			return false;
		}
		// print barcode elements
		$y = 0;
		// for each row
		for ($r = 0; $r < $this->barcode_array['num_rows']; ++$r) {
			$x = 0;
			// for each column
			for ($c = 0; $c < $this->barcode_array['num_cols']; ++$c) {
				if ($this->barcode_array['bcode'][$r][$c] == 1) {
					// draw a single barcode cell
					if ($imagick) {
						$bar->rectangle($x, $y, ($x + $w - 1), ($y + $h - 1));
					} else {
						imagefilledrectangle($png, $x, $y, ($x + $w - 1), ($y + $h - 1), $fgcol);
					}
				}
				$x += $w;
			}
			$y += $h;
		}
		
		imagepng($png,$file_path_local);
		imagedestroy($png);
		return $file_path;
	}
	
	/**
	 * 获取二维码png图片二进制
	 *	@param int 宽度
	 *	@param int 高度
	 *	@param array 二维码背景颜色
 	 * @return image or false in case of error.
 	 * @public
	 */
	public function getQrcodePNG($w=4, $h=4, $color=array(0,0,0)) {
		
		// calculate image size
		$width = ($this->barcode_array['num_cols'] * $w);
		$height = ($this->barcode_array['num_rows'] * $h);
		if (function_exists('imagecreate')) {
			// GD library
			$imagick = false;
			$png = imagecreate($width, $height);
			$bgcol = imagecolorallocate($png, 255, 255, 255);
			//将背景色设为透明色
			//imagecolortransparent($png, $bgcol);
			$fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
		} elseif (extension_loaded('imagick')) {
			$imagick = true;
			$bgcol = new imagickpixel('rgb(255,255,255');
			$fgcol = new imagickpixel('rgb('.$color[0].','.$color[1].','.$color[2].')');
			$png = new Imagick();
			$png->newImage($width, $height, 'none', 'png');
			$bar = new imagickdraw();
			$bar->setfillcolor($fgcol);
		} else {
			return false;
		}
		// print barcode elements
		$y = 0;
		// for each row
		for ($r = 0; $r < $this->barcode_array['num_rows']; ++$r) {
			$x = 0;
			// for each column
			for ($c = 0; $c < $this->barcode_array['num_cols']; ++$c) {
				if ($this->barcode_array['bcode'][$r][$c] == 1) {
					// draw a single barcode cell
					if ($imagick) {
						$bar->rectangle($x, $y, ($x + $w - 1), ($y + $h - 1));
					} else {
						imagefilledrectangle($png, $x, $y, ($x + $w - 1), ($y + $h - 1), $fgcol);
					}
				}
				$x += $w;
			}
			$y += $h;
		}
		
		return $png;
	}
}

