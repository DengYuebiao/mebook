<?php
namespace Home\Controller;
class ApiController extends BaseController {
    public function book(){
		$isbn=isset($_GET['isbn'])?trim($_GET['isbn']):"";
		$callback=isset($_GET['callback'])?trim($_GET['callback']):"";
		$isbn=strip_tags(stripslashes($isbn));
		$callback=strip_tags(stripslashes($callback));
		$ret_data=array();
		
		if($isbn)
		{
			$mod_book=D("Book");
			$isbn_format=preg_replace('/^\d/','',$isbn);
			$isbn_other=strlen($isbn_format)>=13?substr($isbn,4):"978-".$isbn;
			
			$isbncode=\Home\Extend\Isbn\IsbnBase::convIsbnCode($isbn);
			
			$book_info=$mod_book->where("isbn='{$isbn}' OR isbn='{$isbncode}' OR isbn='{$isbn_other}'")->find();
			if($book_info)
			{
				$ret_data['url']="http://".$_SERVER['HTTP_HOST']."/Index/bookview/book_id/".$book_info['book_id'];
			}
		}
		
		echo $callback."(".json_encode($ret_data).")";
    }
	
}