<?php

include_once("./classes/masterdataschema_br.php");
include_once("./classes/documentlinesschema_br.php");
include_once("./utils/journalentries.php");
include_once("./classes/usermsgs.php");
require_once("./classes/smsoutlog.php");

function onBeforeAddEventdocumentschema_brGPSLGUPurchasingBayambang($objTable) {
    global $objConnection;
    $actionReturn = true;
    switch ($objTable->dbtable) {
        case "u_lgupurchaserequests":
            if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                        $msgid = date('Y-m-d H:i:s') . ".PR Approved." . $objRs->fields["USERID"] . " " .$_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $_SERVER["REMOTE_ADDR"];
                        $alertschema = array("Document No." => array("type" => "string"),"Remarks" => array("type" => "string"),"Approved By" => array("type" => "string"),"Approved Date" => array("type" => "date"));
                        $alertdata = array( 0 => array("Document No" => $objTable->docno,"Remarks" => $objTable->getudfvalue("u_remarks"),"Approved By" => $_SESSION["userid"],"Approved Date" => date('Y-m-d')));
                        
                        $objUserMsgs =  new usermsgs(null,$objConnection);
                        $objUserMsgs->prepareadd();
                        $objUserMsgs->msgid = $msgid;
                        $objUserMsgs->userid = $objTable->createdby;
                        $objUserMsgs->msgfrom = "system";
                        $objUserMsgs->msgtype = "IBX";
                        $objUserMsgs->msgsubtype = "AL";  
                        $objUserMsgs->priority=1;
                        $objUserMsgs->isalert=1;
                        $objUserMsgs->msgsubject = "Approved Purchase Request No. ".$objTable->docno."." ;
                        $objUserMsgs->msgbody = "Your Purchase Request has been successfully added/approved in the system. \r\n " . $objTable->getudfvalue("u_remarks") ;
                        $objUserMsgs->status = "U";
                        $objUserMsgs->msgdate = date('Y-m-d');
                        $objUserMsgs->msgtime = date('H:i:s');
                        $objUserMsgs->alertschema = serialize($alertschema) ;
                        $objUserMsgs->alertdata = serialize($alertdata);
                        $actionReturn = $objUserMsgs->add();
                } 
            }
            break;
        case "u_lguphilgeps":
            if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                }
            }
            break;
        case "u_lgucanvassing":
            if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                }
            }
            break;
        case "u_lguaward":
            if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                }
            }
            break;

        case "u_lgupurchaseorder":
            if ($objTable->docstatus == "O") {
             
            } 
            break;
        case "u_lgusplitpo":
            if ($objTable->docstatus == "O") {
               
            } 
            break;
        case "u_lgupurchasedelivery":
            if ($objTable->docstatus=="O") {
                   
            } 
            break;
            
        case "u_lgupurchasereturn":
             if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                }
            }
            break;
        case "u_lgugoodsissue":
             if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                } 
            }
            break;
        case "u_lgustocktransfer":
            if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                }
            }
            break;
    }
    return $actionReturn;
}

function onAddEventdocumentschema_brGPSLGUPurchasingBayambang($objTable) {
    global $objConnection;
    $actionReturn = true;
    switch ($objTable->dbtable) {

        case "u_lgupurchaserequests":
            if ($objTable->docstatus == "O") {
               
            }
            break;
        case "u_lguphilgeps":
            if ($objTable->docstatus == "O") {
                
            }
            break;
        case "u_lgucanvassing":
            if ($objTable->docstatus == "O") {
                
            }
            break;
        case "u_lguaward":
            if ($objTable->docstatus == "O") {
               
            }
            break;
        case "u_lgupurchaseorder":
            if ($objTable->docstatus == "O") {
                
            }
            break;
        case "u_lgusplitpo":
            if ($objTable->docstatus == "O") {
               
            }
	break;
        case "u_lgupurchasedelivery":
            if ($objTable->docstatus == "O") {
               
            }
            break;

    }
    return $actionReturn;
}

function onBeforeUpdateEventdocumentschema_brGPSLGUPurchasingBayambang($objTable) {
    global $objConnection;
    global $page;
    $actionReturn = true;
    switch ($objTable->dbtable) {
        case "u_lgupurchaseorder":
            if ($objTable->fields["DOCSTATUS"] == "D" && $objTable->docstatus == "O") {
                
            }

            break;
        case "u_lgupurchaserequests":
            if ($actionReturn) {
                if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                        $msgid = date('Y-m-d H:i:s') . ".PR Approved." . $objRs->fields["USERID"] . " " .$_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $_SERVER["REMOTE_ADDR"];
                        $alertschema = array("Document No." => array("type" => "string"),"Remarks" => array("type" => "string"),"Approved By" => array("type" => "string"),"Approved Date" => array("type" => "date"));
                        $alertdata = array( 0 => array("Document No." => $objTable->docno,"Remarks" => $objTable->getudfvalue("u_remarks"),"Approved By" => $_SESSION["userid"],"Approved Date" => date('Y-m-d')));
                        
                        $objUserMsgs =  new usermsgs(null,$objConnection);
                        $objUserMsgs->prepareadd();
                        $objUserMsgs->msgid = $msgid;
                        $objUserMsgs->userid = $objTable->createdby;
                        $objUserMsgs->msgfrom = "system";
                        $objUserMsgs->msgtype = "IBX";
                        $objUserMsgs->msgsubtype = "AL";  
                        $objUserMsgs->priority=1;
                        $objUserMsgs->isalert=1;
                        $objUserMsgs->msgsubject = "Approved Purchase Request No. ".$objTable->docno."." ;
                        $objUserMsgs->msgbody = "Your Purchase Request has been successfully added/approved in the system. \r\n " . $objTable->getudfvalue("u_remarks") ;
                        $objUserMsgs->status = "U";
                        $objUserMsgs->msgdate = date('Y-m-d');
                        $objUserMsgs->msgtime = date('H:i:s');
                        $objUserMsgs->alertschema = serialize($alertschema) ;
                        $objUserMsgs->alertdata = serialize($alertdata);
                        $actionReturn = $objUserMsgs->add();
                }
            }
            break;
        case "u_lgupurchasedelivery":
            if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                    
            }
            break;
            
        case "u_lgupurchasereturn":
             if ($actionReturn) {
                if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "CN") {
                } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "D") {
                }
            }
            break;
        case "u_lgugoodsissue":
             if ($actionReturn) {
                if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                    
                }
            }
            break;
        case "u_lgusplitpo":
            if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                
            }
            break;
    }
    //if ($actionReturn) $actionReturn = raiseError("onBeforeUpdateEventdocumentschema_brLGUPurchasing()");
    return $actionReturn;
}

/*
  function onUpdateEventdocumentschema_brGPSLGUPurchasing($objTable) {
  global $objConnection;
  $actionReturn = true;
  switch ($objTable->dbtable) {
  case "u_lgupurchaserequests":
  break;
  }
  return $actionReturn;
  }
 */

/*
  function onBeforeDeleteEventdocumentschema_brLGUPurchasing($objTable) {
  global $objConnection;
  $actionReturn = true;
  switch ($objTable->dbtable) {
  case "u_lgupurchaserequests":
  break;
  }
  return $actionReturn;
  }
 */

/*
  function onDeleteEventdocumentschema_brLGUPurchasing($objTable) {
  global $objConnection;
  $actionReturn = true;
  switch ($objTable->dbtable) {
  case "u_lgupurchaserequests":
  break;
  }
  return $actionReturn;
  }
 */


?>