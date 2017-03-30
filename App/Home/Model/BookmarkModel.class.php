<?php
namespace Home\Model;
class BookmarkModel extends BaseModel {
	
	/*
	判断图书是否已经放入书架
	@param int 
	@return bool
	*/
	function inBookmark($user_id,$book_id)
	{
		if(!$user_id || !$book_id)
		{
			return false;
		}
		
		$tmp_info=$this->where("user_id={$user_id} AND book_id={$book_id}")->find();
		$is_in=!empty($tmp_info);
		return $is_in;
	}
}
