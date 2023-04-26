<?php
class Core_Mailer_Transport_Print extends Zend_Mail_Transport_Abstract {
	public function _sendMail() {
		$_arrHash=array( 
			'to'=>$this->recipients, 
			'from'=>$this->_mail->getFrom(), 
			'subject'=>$this->_mail->getSubject(), 
			'raw'=>($this->header.$this->body), 
		);
		while (@ob_end_clean());
		Core_Parsers::viewAsHtml( $_arrHash, Zend_Registry::get( 'config' )->path->relative->letters.'print.tpl' );
		exit;
	}
}
?>