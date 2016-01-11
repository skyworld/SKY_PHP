<?php
/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file page.class.php
 * @author SkyWorld
 * @date 2011-1-19
 * @description This file contains all funtions about time calculating
 **/

if(!defined('IN_NCG')) exit ('Access Denied.');

    /**
	 * @name Timer
	 * @author SkyWorld
	 * @date 2011-1-18
	 * @description this function to call will set up a node to calculate time
     * @parameters  void
     * @return void
     **/

 class page{

 	/**
	 * those are private variables of page class
	 * $total: store the number of total records
	 * $page : store current page number
	 * $num  : set the number of record in ervey page
	 * $pageNum : store the number pages
	 * $offset : the start mark while read form database
     **/
 	private $total;
 	private $page;
 	private $num ;
 	private $pageNum;
 	private $offset;


 	/**
	 * @name  __construct
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description  Constructor of page class
     * @parameters  [int]
     * 				$total: the number of total record
     * 				$page : current page number
     * 				$num  : the number of record in ervey page
     * @return void
     **/
 	function __construct($total,$page=1,$num=5){
 		$this->total = $total;
 		$this->page	 = $page;
 		$this->num	 = $num;
 		$this->pageNum = $this->getPageNum();
 		$this->offset = $this->getOffset();
 	}

 	/**
	 * @name  getPageNum
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description  get the number of total pages
     * @parameters  void
     * @return [int] the number of total pages
     **/
 	private function getPageNum(){
 		return ceil($this->total/$this->num);
 	}

 	/**
	 * @name  getNextPage()
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description  get the index of next page
     * @parameters  void
     * @return [int] the page number of next page
     **/
 	private function getNextPage(){
 		if($this->page==$this->pageNum){
 			return false;
 		}else{
			return $this->page+1;
		}
 	}

 	/**
	 * @name  getPrevPage
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description  get the index of previous page
     * @parameters  void
     * @return [int] the page number of previous page
     **/
 	private function getPrevPage(){
 		if($this->page==1){
 			return false;
 		}else{
			return $this->page-1;
 		}
 	}

 	/**
	 * @name  getOffset
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description  get the offset of database
     * @parameters  void
     * @return [int] the offset of database
     **/
 	private function getOffset(){
 		return ($this->page-1)*$this->num;
 	}


 	/**
	 * @name  getStartNum
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description  get the start record number of current page
     * @parameters  void
     * @return [int] the start record number of current page
     **/
 	private function getStartNum(){
 		if($this->total==0){
 			return 0;
 		}else{
 			return $this->offset+1;
 		}
 	}

 	/**
	 * @name  getEndNum
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description  get the end record number of current page
     * @parameters  void
     * @return [int] the end record number of current page
     **/
 	private function getEndNum(){
 		return min($this->offset+$this->num , $this->total);
 	}


	/**
	 * @name  getPageInfo
	 * @author SkyWorld
	 * @date 2011-1-19
	 * @description this public function to call will return the detail infomation of current page
     * @parameters  void
     * @return An array that contains all the infomation of current page
     **/
 	public function getPageInfo(){
 		$pageInfo = array(
 			'row_total' => $this->total,
 			'row_num'	=> $this->num,
 			'page_num'  => $this->getPageNum(),
 			'current_page' => $this->page,
 			'row_offset' => $this->getOffset(),
 			'next_page' => $this->getNextPage(),
 			'prev_page' => $this->getPrevPage(),
 			'page_start' => $this->getStartNum(),
 			'page_end' => $this->getEndNum()
 		);
 		return $pageInfo;
	}
 }

/**
 * En example about how to use this class
 * @author SkyWorld
 * @date  2011-1-19
 *
 * $currnet_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
 * $fpage = new page(3,$currnet_page,2);
 * $pageInfo = $fpage->getPageInfo();
 *
 */

