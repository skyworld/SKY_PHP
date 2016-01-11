<?php

/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file Exception.class.php
 * @author cble
 * @date 2010-12-2
 * @description This file contains the definition for exception handling.
 */

if (!defined('IN_NCG')) exit ('Access Denied.');

/**
 * The following definitions are for exception types.
 * There are still some PROBLEMS when adding new types, so if you want to add any new types here, please contact me to avoid those problem.
 */
define('E_TYPE_SQL', 0);
define('E_TYPE_MATHEMATICAL', 1);
define('E_TYPE_FILE_OPERATION', 2);
define('E_TYPE_UNKNOWN', 3);

/**
 * The following definitions are for the custom exception class.
 */
class NcgException extends Exception
{
	//Enable the user to store extra information of exception.
	private $extraInfo;

	/**
     * @name __construct
     * @author cble
     * @date 2010-12-3
     * @description
     *   The function is the rewrited version of the function in the parent class.
     * @parameters
     *   string $message: The exception message when exception is thrown.
     *   integer $code: The exception code when exception is thrown.
     *   mixed $extraInfo: The extra information when exception is thrown.
     * @return void
     */
	public function __construct($message, $code=E_TYPE_UNKNOWN, $extraInfo='')
    {
    	//The following judgement prevents undefined exception code.
        if (!($code>=0 && $code<=E_TYPE_UNKNOWN))
        {
        	$code = E_TYPE_UNKNOWN;
        }
		$this->extraInfo = $extraInfo;
        parent::__construct($message,$code);
    }

    /**
     * @name __toString
     * @author cble
     * @date 2010-12-3
     * @description
     *   The function is the rewrited version of the function in the parent class.
     *   The function prints the detail of exception.
     * @parameters void
     * @return string $exceptionInfo: The string of detailed exception information.
     */
    public function __toString()
    {
	    //String array for printing exception type.
    	$exceptionType = array('SqlException', 'MathematicalException', 'FileOperationException', 'UnknownException');

  		$exceptionInfo = 'Caught Exception: ';
  		$exceptionInfo .= $exceptionType[$this->getCode()] . ' at ';
  		$exceptionInfo .= '"' . $this->getFile() . '" ';
  		$exceptionInfo .= 'on Line ' . $this->getLine();
  		ImbaLog::SetLog($exceptionInfo, L_TYPE_EXCEPTION);
  		ImbaLog::WriteLog();
  		return $exceptionInfo;
    }

    /**
     * @name GetExtraInfo
     * @author cble
     * @date 2010-12-3
     * @description
     *   The function enables user to get the extra information back so that he/she can deal with it.
     * @parameters void
     * @return mixed $extraInfo: The extra information given by the user when constructing.
     */
    public function GetExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * @name IncludeExceptionHandler
     * @author cble
     * @date 2010-12-4
     * @description
     *   The function enables user to include different exception-handling page due to different exception.
     *   Once calling this function, the application will be stopped.
     * @parameters void
     * @return void
     */
    public function IncludeExceptionHandlerStop()
    {
  		echo 'The application is stopped due to exception. <br />';
		echo $this . '<br />';
		echo $this->GetExtraInfo() . '<br />';
  		exit;
    }

    /**
     * @name IncludeExceptionHandler
     * @author cble
     * @date 2010-12-6
     * @description
     *   The function enables user to include different exception-handling page due to different exception.
     *   Once calling this function, the application will NOT be stopped.
     * @parameters void
     * @return void
     */
    public function IncludeExceptionHandlerContinue()
    {
  		echo 'The application is stopped due to exception. <br />';
		echo $this . '<br />';
		echo $this->GetExtraInfo() . '<br />';
    }
}

// end off script