<?php
	
if ($page->getvarstring("opt") == 'viewonly') {
	$page->setvar("formAccess","R");
	$page->toolbar->setaction("find",false);
}
?>