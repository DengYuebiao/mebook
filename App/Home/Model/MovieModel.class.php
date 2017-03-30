<?php
namespace Home\Model;
class MovieModel extends BaseModel {
	function getAddRule()
	{
		return array(
			"rules"=>array(
				"title"=>array(
					"required"=>true,
					"rangelength"=>array(1,200)
				)
			)
		);
	}
	
	
	function getEditRule()
	{
		return array(
			"rules"=>array(
				"title"=>array(
					"required"=>true,
					"rangelength"=>array(1,200)
				)
			)
		);
	}

	function getFiles($movie_id)
	{
		$mod_movie_file=D('Movie_file');
		$file_list=$mod_movie_file->where("movie_id='{$movie_id}'")->order('movie_file_id')->select();
		return $file_list;
	}
	
	function addFile($movie_id)
	{
		$mod_movie_file=D('Movie_file');
		$is_success=$mod_movie_file->addData($movie_id);
		if($is_success===false)
		{
			$this->error=$mod_movie_file->getError();
		}
		return $is_success;
	}
	
	function saveFile($movie_id)
	{
		$mod_movie_file=D('Movie_file');
		$is_success=$mod_movie_file->saveData($movie_id);
		if($is_success===false)
		{
			$this->error=$mod_movie_file->getError();
		}
		return $is_success;
	}
	
	function dropFile($movie_id)
	{
		$mod_movie_file=D('Movie_file');
		$is_success=$mod_movie_file->dropData($movie_id);
		if($is_success===false)
		{
			$this->error=$mod_movie_file->getError();
		}
		return $is_success;
	}
}
