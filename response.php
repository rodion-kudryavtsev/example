<?php
namespace ______;

class Response{

	protected $mResponse;
	protected $bSuccess;
	protected $strMessageId;

	public function __construct($mResponse){
		$this->mResponse = $mResponse;
		$this->bSuccess = false;
		$this->strMessageId = '';
	}

	public function setSuccess(bool $bSuccess){
		$this->bSuccess = $bSuccess;
	}

	public function setMessageId(string $strMessageId){
		$this->strMessageId = $strMessageId;
	}

	public function getResponse(){
		return $this->mResponse;
	}

	public function getSuccess(){
		return $this->bSuccess;
	}
}
