<?php

include_once("./classes/masterdataschema_br.php");
include_once("./classes/documentlinesschema_br.php");
include_once("./utils/journalentries.php");

function onBeforeAddEventdocumentschema_brGPSLGUPurchasing($objTable) {
    global $objConnection;
    $actionReturn = true;
    switch ($objTable->dbtable) {
        case "u_lgupurchaserequests":
            if ($actionReturn) {
                if ($objTable->docstatus=="O") {
                    $docseries = getSeries("u_lgupurchaserequests", "PO");
                    if ($objTable->getudfvalue("u_refno") == "?") {
                        $refno = getNextSeriesNoByBranch("u_lgupurchaserequests", $docseries, $objTable->getudfvalue("u_date"));
                        $objTable->docseries = $docseries;
                        $objTable->setudfvalue("u_refno", $refno);
                    } else {
                        $objTable->docseries = -1;
                    }
                    $objTable->docno = $objTable->getudfvalue("u_refno");
                    $actionReturn = onCustomeventUpdateProgramsProjectdocumentschema_brGPSLGUPurchasing($objTable);
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
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPRCanvassingAndAwardNoticedocumentschema_brGPSLGUPurchasing($objTable);
            } 
            break;
        case "u_lgusplitpo":
            if ($objTable->docstatus == "O") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOdocumentschema_brGPSLGUPurchasing($objTable);
            } 
            break;
        case "u_lgupurchasedelivery":
            if ($objTable->docstatus=="O") {
                    if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOAndSplitPOdocumentschema_brGPSLGUPurchasing($objTable);
                    $actionReturn = onCustomeventUpdateStocksdocumentschema_brGPSLGUPurchasing($objTable);
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
                    if ($actionReturn) $actionReturn = onCustomeventUpdateStocksGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable);
                    if ($objTable->getudfvalue("u_jevno") != "") if ($actionReturn) $actionReturn = onCustomeventPostGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable);
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

function onAddEventdocumentschema_brGPSLGUPurchasing($objTable) {
    global $objConnection;
    $actionReturn = true;
    switch ($objTable->dbtable) {

        case "u_lgupurchaserequests":
            if ($objTable->docstatus == "O") {
                $obju_LGUPurchaseRequestItems = new documentlinesschema_br(null, $objConnection, "u_lgupurchaserequestitems");
                $obju_LGUPurchaseRequestService = new documentlinesschema_br(null, $objConnection, "u_lgupurchaserequestservice");

                if ($obju_LGUPurchaseRequestItems->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPurchaseRequestItems->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPurchaseRequestItems->update($obju_LGUPurchaseRequestItems->docid, $obju_LGUPurchaseRequestItems->lineid, $obju_LGUPurchaseRequestItems->rcdversion);
                    if (!$actionReturn) break;
                }
                if ($obju_LGUPurchaseRequestService->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPurchaseRequestService->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPurchaseRequestService->update($obju_LGUPurchaseRequestService->docid, $obju_LGUPurchaseRequestService->lineid, $obju_LGUPurchaseRequestService->rcdversion);
                    if (!$actionReturn) break;
                }
            }
            break;
        case "u_lguphilgeps":
            if ($objTable->docstatus == "O") {
                $obju_LGUPhilGepsItems = new documentlinesschema_br(null, $objConnection, "u_lguphilgepsitems");
                $obju_LGUPhilGepsService= new documentlinesschema_br(null, $objConnection, "u_lguphilgepsservice");

                if ($obju_LGUPhilGepsItems->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPhilGepsItems->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPhilGepsItems->update($obju_LGUPhilGepsItems->docid, $obju_LGUPhilGepsItems->lineid, $obju_LGUPhilGepsItems->rcdversion);
                    if (!$actionReturn) break;
                }
                if ($obju_LGUPhilGepsService->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPhilGepsService->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPhilGepsService->update($obju_LGUPhilGepsService->docid, $obju_LGUPhilGepsService->lineid, $obju_LGUPhilGepsService->rcdversion);
                    if (!$actionReturn) break;
                }
            }
            break;
        case "u_lgucanvassing":
            if ($objTable->docstatus == "O") {
                $obju_LGUCanvassingItems = new documentlinesschema_br(null, $objConnection, "u_lgucanvassingitems");
                $obju_LGUCanvassingService= new documentlinesschema_br(null, $objConnection, "u_lgucanvassingservice");

                if ($obju_LGUCanvassingItems->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUCanvassingItems->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUCanvassingItems->update($obju_LGUCanvassingItems->docid, $obju_LGUCanvassingItems->lineid, $obju_LGUCanvassingItems->rcdversion);
                    if (!$actionReturn) break;
                }
                if ($obju_LGUCanvassingService->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUCanvassingService->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUCanvassingService->update($obju_LGUCanvassingService->docid, $obju_LGUCanvassingService->lineid, $obju_LGUCanvassingService->rcdversion);
                    if (!$actionReturn) break;
                }
            }
            break;
        case "u_lguaward":
            if ($objTable->docstatus == "O") {
                $obju_LGUAwardItems = new documentlinesschema_br(null, $objConnection, "u_lguawarditems");
                $obju_LGUAwardService= new documentlinesschema_br(null, $objConnection, "u_lguawardservice");

                if ($obju_LGUAwardItems->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUAwardItems->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUAwardItems->update($obju_LGUAwardItems->docid, $obju_LGUAwardItems->lineid, $obju_LGUAwardItems->rcdversion);
                    if (!$actionReturn) break;
                }
                if ($obju_LGUAwardService->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUAwardService->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUAwardService->update($obju_LGUAwardService->docid, $obju_LGUAwardService->lineid, $obju_LGUAwardService->rcdversion);
                    if (!$actionReturn) break;
                }
            }
            break;
        case "u_lgupurchaseorder":
            if ($objTable->docstatus == "O") {
                $obju_LGUPurchaseOrderItems = new documentlinesschema_br(null, $objConnection, "u_lgupurchaseorderitems");
                $obju_LGUPurchaseOrderService= new documentlinesschema_br(null, $objConnection, "u_lgupurchaseorderservice");

                if ($obju_LGUPurchaseOrderItems->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPurchaseOrderItems->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPurchaseOrderItems->update($obju_LGUPurchaseOrderItems->docid, $obju_LGUPurchaseOrderItems->lineid, $obju_LGUPurchaseOrderItems->rcdversion);
                    if (!$actionReturn) break;
                }
                if ($obju_LGUPurchaseOrderService->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPurchaseOrderService->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPurchaseOrderService->update($obju_LGUPurchaseOrderService->docid, $obju_LGUPurchaseOrderService->lineid, $obju_LGUPurchaseOrderService->rcdversion);
                    if (!$actionReturn) break;
                }
            }
            break;
        case "u_lgusplitpo":
            if ($objTable->docstatus == "O") {
                $obju_LGUSplitPOItems = new documentlinesschema_br(null, $objConnection, "u_lgusplitpoitems");
                $obju_LGUSplitPOService= new documentlinesschema_br(null, $objConnection, "u_lgusplitposervice");

                if ($obju_LGUSplitPOItems->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUSplitPOItems->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUSplitPOItems->update($obju_LGUSplitPOItems->docid, $obju_LGUSplitPOItems->lineid, $obju_LGUSplitPOItems->rcdversion);
                    if (!$actionReturn) break;
                }
                if ($obju_LGUSplitPOService->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUSplitPOService->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUSplitPOService->update($obju_LGUSplitPOService->docid, $obju_LGUSplitPOService->lineid, $obju_LGUSplitPOService->rcdversion);
                    if (!$actionReturn) break;
                }
            }
	break;
        case "u_lgupurchasedelivery":
            if ($objTable->docstatus == "O") {
                $obju_LGUPurchaseDeliveryItems = new documentlinesschema_br(null, $objConnection, "u_lgupurchasedeliveryitems");
                $obju_LGUPurchaseDeliveryService= new documentlinesschema_br(null, $objConnection, "u_lgupurchasedeliveryservice");

                if ($obju_LGUPurchaseDeliveryItems->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPurchaseDeliveryItems->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPurchaseDeliveryItems->update($obju_LGUPurchaseDeliveryItems->docid, $obju_LGUPurchaseDeliveryItems->lineid, $obju_LGUPurchaseDeliveryItems->rcdversion);
                    if (!$actionReturn) break;
                }
                if ($obju_LGUPurchaseDeliveryService->getbysql("DOCID='" . $objTable->docid . "'")) {
                    $obju_LGUPurchaseDeliveryService->setudfvalue("u_linestatus", "O");
                    $actionReturn = $obju_LGUPurchaseDeliveryService->update($obju_LGUPurchaseDeliveryService->docid, $obju_LGUPurchaseDeliveryService->lineid, $obju_LGUPurchaseDeliveryService->rcdversion);
                    if (!$actionReturn) break;
                }
            }
            break;

    }
    return $actionReturn;
}

function onBeforeUpdateEventdocumentschema_brGPSLGUPurchasing($objTable) {
    global $objConnection;
    global $page;
    $actionReturn = true;
    switch ($objTable->dbtable) {
        case "u_lgupurchaseorder":
            if ($objTable->fields["DOCSTATUS"] == "D" && $objTable->docstatus == "O") {
                $docseries = getSeries("u_lgupurchaseorder", "PO");
                if ($objTable->getudfvalue("u_refno") == "?") {
                    $refno = getNextSeriesNoByBranch("u_lgupurchaseorder", $docseries, $objTable->getudfvalue("u_date"));
                    $objTable->docseries = $docseries;
                    $objTable->setudfvalue("u_refno", $refno);
                } else {
                    $objTable->docseries = -1;
                }
                if ($objTable->getudfvalue("u_refno") != "") {
                    $objTable->docno = $objTable->getudfvalue("u_refno");
                } 
                $actionReturn = onCustomeventUpdateCopyFromPRCanvassingAndAwardNoticedocumentschema_brGPSLGUPurchasing($objTable);
            } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "CN") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPRCanvassingAndAwardNoticedocumentschema_brGPSLGUPurchasing($objTable, true);
            } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "D") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPRCanvassingAndAwardNoticedocumentschema_brGPSLGUPurchasing($objTable, true);
            }

            break;
        case "u_lgupurchaserequests":
            if ($actionReturn) {
                if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                    $docseries = getSeries("u_lgupurchaserequests", "PO");
                    if ($objTable->getudfvalue("u_refno") == "?") {
                        $refno = getNextSeriesNoByBranch("u_lgupurchaserequests", $docseries, $objTable->getudfvalue("u_date"));
                        $objTable->docseries = $docseries;
                        $objTable->setudfvalue("u_refno", $refno);
                    } else {
                        $objTable->docseries = -1;
                    }
                    $objTable->docno = $objTable->getudfvalue("u_refno");
                    $actionReturn = onCustomeventUpdateProgramsProjectdocumentschema_brGPSLGUPurchasing($objTable);
                } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "CN") {
                    if ($actionReturn) $actionReturn = onCustomeventUpdateProgramsProjectdocumentschema_brGPSLGUPurchasing($objTable, true);
                } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "D") {
                    if ($actionReturn) $actionReturn = onCustomeventUpdateProgramsProjectdocumentschema_brGPSLGUPurchasing($objTable, true);
                }
            }
            break;
        case "u_lgupurchasedelivery":
            if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                    if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOAndSplitPOdocumentschema_brGPSLGUPurchasing($objTable);
                    $actionReturn = onCustomeventUpdateStocksdocumentschema_brGPSLGUPurchasing($objTable);
            } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "CN") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOAndSplitPOdocumentschema_brGPSLGUPurchasing($objTable, true);
                    $actionReturn = onCustomeventUpdateStocksdocumentschema_brGPSLGUPurchasing($objTable, true);
            } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "D") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOAndSplitPOdocumentschema_brGPSLGUPurchasing($objTable, true);
                    $actionReturn = onCustomeventUpdateStocksdocumentschema_brGPSLGUPurchasing($objTable, true);
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
                    if ($actionReturn) $actionReturn = onCustomeventUpdateStocksGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable);
                    if ($objTable->getudfvalue("u_jevno") != "") if ($actionReturn) $actionReturn = onCustomeventPostGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable);
                    
                } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "CN") {
                    if ($actionReturn) $actionReturn = onCustomeventUpdateStocksGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable, true);
                    if ($objTable->getudfvalue("u_jevno") != "") if ($actionReturn) $actionReturn = onCustomeventPostGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable, true);
                 
                } elseif (($objTable->fields["DOCSTATUS"] != "AP" && $objTable->fields["DOCSTATUS"] != "DA" ) && ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "D")) {
                    $objJvHdr = new journalvouchers(null,$objConnection);
                    $objJvDtl = new journalvoucheritems(null,$objConnection);
                    if ($objTable->getudfvalue("u_jevno") != "") {
                        if ($objJvHdr->getbykey($objTable->getudfvalue("u_jevno"))) {
                            $objJvDtl->queryopen($objJvDtl->selectstring()." AND DOCID='$objJvHdr->docid'");
                            while ($objJvDtl->queryfetchrow()) {
                                    if ($objJvDtl->getudfvalue("itemtype")!="A") {
                                            if (($objJvDtl->getudfvalue("debit")-$objJvDtl->getudfvalue("credit"))!=$objJvDtl->getudfvalue("balanceamount")) {
                                                    return raiseError("JV Item for [".$objJvDtl->getudfvalue("itemname")."] already have settlement.");
                                            }
                                    }
                                    $objJvDtl->privatedata["header"] = $objJvHdr;
                                    $actionReturn = $objJvDtl->delete();
                                    if (!$actionReturn) return false;
                            }					
                            if ($actionReturn) $actionReturn = deleteJournalEntries($objJvHdr->docno,"JV");
                            if ($actionReturn) $actionReturn = $objJvHdr->delete();
                        } else {
                                return raiseError("Unable to find Station JV No[".$objTable->userfields["u_jvdocno"]["value"]."].");
                        }
                    }
                    if ($actionReturn) $actionReturn = onCustomeventUpdateStocksGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable, true);
                }
            }
            break;
        case "u_lgusplitpo":
            if ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "O") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOdocumentschema_brGPSLGUPurchasing($objTable);
            } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "CN") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOdocumentschema_brGPSLGUPurchasing($objTable, true);
            } elseif ($objTable->fields["DOCSTATUS"] != $objTable->docstatus && $objTable->docstatus == "D") {
                if ($actionReturn)
                    $actionReturn = onCustomeventUpdateCopyFromPOdocumentschema_brGPSLGUPurchasing($objTable, true);
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

function onCustomeventUpdateCopyFromPRdocumentschema_brGPSLGUPurchasing($objTable, $delete = false) {
    global $objConnection;
    global $page;
    $actionReturn = true;

    $obju_LGUPurchaseRequest = new documentschema_br(null, $objConnection, "u_lgupurchaserequests");
    $obju_LGUPurchaseRequestItems = new documentlinesschema_br(null, $objConnection, "u_lgupurchaserequestitems");
    $obju_LGUPurchaseRequestService = new documentlinesschema_br(null, $objConnection, "u_lgupurchaserequestservice");

    $obju_LGUItems = new masterdataschema(null, $objConnection, "u_lguitems");

    $objRs = new recordset(null, $objConnection);
    if ($objTable->getudfvalue("u_doctype") == "I") {
        $objRs->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lguphilgepsitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    } else {
        $objRs->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lguphilgepsservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    }
    while ($objRs->queryfetchrow("NAME")) {
        if ($actionReturn) {
            if ($objRs->fields["U_BASETYPENM"] == "LGU_Purchase Request") {
                if ($obju_LGUPurchaseRequestItems->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestItems->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestItems->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseRequestItems->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseRequestItems->update($obju_LGUPurchaseRequestItems->docid, $obju_LGUPurchaseRequestItems->lineid, $obju_LGUPurchaseRequestItems->rcdversion);
                    if (!$actionReturn)
                        break;
                }
                if ($obju_LGUPurchaseRequestService->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseRequestService->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestService->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseRequestService->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestService->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseRequestService->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseRequestService->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseRequestService->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseRequestService->update($obju_LGUPurchaseRequestService->docid, $obju_LGUPurchaseRequestService->lineid, $obju_LGUPurchaseRequestService->rcdversion);
                    if (!$actionReturn)
                        break;
                }
            }
        }
        if (!$actionReturn)
            break;
    }
    if ($actionReturn) {
        $objRs1 = new recordset(null, $objConnection);
        if ($objTable->getudfvalue("u_doctype") == "I") {
            $objRs1->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lguphilgepsitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        } else {
            $objRs1->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lguphilgepsservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        }
        while ($objRs1->queryfetchrow("NAME")) {
            if ($objRs1->fields["U_BASETYPENM"] != "") {
                if ($objRs->fields["U_BASETYPENM"] == "LGU_Purchase Request") {
                    if ($obju_LGUPurchaseRequest->getbykey($objRs1->fields["U_BASEDOCNO"])) {
                        $objRs2 = new recordset(null, $objConnection);
                        if ($objTable->getudfvalue("u_doctype") == "I") {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaseorderitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseRequest->docid' ");
                        } else {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaseorderservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseRequest->docid' ");
                        }
                        while ($objRs2->queryfetchrow("NAME")) {
                            if ($objRs2->fields["U_OPENQUANTITY"] <= 0) {
                                $obju_LGUPurchaseRequest->docstatus = "C";
                                $actionReturn = $obju_LGUPurchaseRequest->update($obju_LGUPurchaseRequest->docno, $obju_LGUPurchaseRequest->rcdversion);
                            }
                        }
                    }
                    if (!$actionReturn)
                        break;
                }
            }
            if (!$actionReturn)
                break;
        }
    }

    return $actionReturn;
}
function onCustomeventUpdateCopyFromPOdocumentschema_brGPSLGUPurchasing($objTable, $delete = false) {
    global $objConnection;
    global $page;
    $actionReturn = true;

    $obju_LGUPurchaseOrder = new documentschema_br(null, $objConnection, "u_lgupurchaseorder");
    $obju_LGUPurchaseOrderItems = new documentlinesschema_br(null, $objConnection, "u_lgupurchaseorderitems");
    $obju_LGUPurchaseOrderService = new documentlinesschema_br(null, $objConnection, "u_lgupurchaseorderservice");

    $obju_LGUItems = new masterdataschema(null, $objConnection, "u_lguitems");

    $objRs = new recordset(null, $objConnection);
    if ($objTable->getudfvalue("u_doctype") == "I") {
        $objRs->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgusplitpoitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    } else {
        $objRs->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgusplitposervice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    }
    while ($objRs->queryfetchrow("NAME")) {
        if ($actionReturn) {
            if ($objRs->fields["U_BASETYPENM"] == "LGU_Purchase Order") {
                if ($obju_LGUPurchaseOrderItems->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderItems->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderItems->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseOrderItems->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseOrderItems->update($obju_LGUPurchaseOrderItems->docid, $obju_LGUPurchaseOrderItems->lineid, $obju_LGUPurchaseOrderItems->rcdversion);
                    if (!$actionReturn)
                        break;
                }
                if ($obju_LGUPurchaseOrderService->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseOrderService->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderService->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseOrderService->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderService->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseOrderService->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseOrderService->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseOrderService->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseOrderService->update($obju_LGUPurchaseOrderService->docid, $obju_LGUPurchaseOrderService->lineid, $obju_LGUPurchaseOrderService->rcdversion);
                    if (!$actionReturn)
                        break;
                }
            }
        }
        if (!$actionReturn)
            break;
    }
    if ($actionReturn) {
        $objRs1 = new recordset(null, $objConnection);
        if ($objTable->getudfvalue("u_doctype") == "I") {
            $objRs1->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgusplitpoitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        } else {
            $objRs1->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgusplitposervice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        }
        while ($objRs1->queryfetchrow("NAME")) {
            if ($objRs1->fields["U_BASETYPENM"] != "") {
                if ($objRs->fields["U_BASETYPENM"] == "LGU_Purchase Order") {
                    if ($obju_LGUPurchaseOrder->getbykey($objRs1->fields["U_BASEDOCNO"])) {
                        $objRs2 = new recordset(null, $objConnection);
                        if ($objTable->getudfvalue("u_doctype") == "I") {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaseorderitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseOrder->docid' ");
                        } else {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaseorderservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseOrder->docid' ");
                        }
                        while ($objRs2->queryfetchrow("NAME")) {
                            if ($objRs2->fields["U_OPENQUANTITY"] <= 0) {
                                $obju_LGUPurchaseOrder->docstatus = "C";
                                $actionReturn = $obju_LGUPurchaseOrder->update($obju_LGUPurchaseOrder->docno, $obju_LGUPurchaseOrder->rcdversion);
                            }
                        }
                    }
                    if (!$actionReturn)
                        break;
                }
            }
            if (!$actionReturn)
                break;
        }
    }

    return $actionReturn;
}

function onCustomeventUpdateCopyFromPOAndSplitPOdocumentschema_brGPSLGUPurchasing($objTable, $delete = false) {
    global $objConnection;
    global $page;
    $actionReturn = true;

    $obju_LGUPurchaseOrder = new documentschema_br(null, $objConnection, "u_lgupurchaseorder");
    $obju_LGUPurchaseOrderItems = new documentlinesschema_br(null, $objConnection, "u_lgupurchaseorderitems");
    $obju_LGUPurchaseOrderService = new documentlinesschema_br(null, $objConnection, "u_lgupurchaseorderservice");

    $obju_LGUSplitPO = new documentschema_br(null, $objConnection, "u_lgusplitpo");
    $obju_LGUSplitPOItems = new documentlinesschema_br(null, $objConnection, "u_lgusplitpoitems");
    $obju_LGUSplitPOService = new documentlinesschema_br(null, $objConnection, "u_lgusplitposervice");
    $obju_LGUItems = new masterdataschema(null, $objConnection, "u_lguitems");

    $objRs = new recordset(null, $objConnection);
    if ($objTable->getudfvalue("u_doctype") == "I") {
        $objRs->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchasedeliveryitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    } else {
        $objRs->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchasedeliveryservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    }
    while ($objRs->queryfetchrow("NAME")) {
        //update instock quantity
        if ($obju_LGUItems->getbysql("CODE='" . $objRs->fields["U_ITEMCODE"] . "'")) {
            if (!$delete) {
                $obju_LGUItems->setudfvalue("u_instockqty", $obju_LGUItems->getudfvalue("u_instockqty") + $objRs->fields["U_QUANTITY"]);
                if ($objRs->fields["U_BASETYPENM"] != "" || $objRs->fields["U_BASETYPENM"] == "LGU_Purchase Order") {
                    $obju_LGUItems->setudfvalue("u_orderedqty", $obju_LGUItems->getudfvalue("u_orderedqty") - $objRs->fields["U_QUANTITY"]);
                }
            } else {
                $obju_LGUItems->setudfvalue("u_instockqty", $obju_LGUItems->getudfvalue("u_instockqty") - $objRs->fields["U_QUANTITY"]);
                if ($objRs->fields["U_BASETYPENM"] != "" || $objRs->fields["U_BASETYPENM"] == "LGU_Purchase Order") {
                    $obju_LGUItems->setudfvalue("u_orderedqty", $obju_LGUItems->getudfvalue("u_orderedqty") + $objRs->fields["U_QUANTITY"]);
                }
            }
            $actionReturn = $obju_LGUItems->update($obju_LGUItems->code, $obju_LGUItems->rcdversion);
        }

        if ($actionReturn) {
            if ($objRs->fields["U_BASETYPENM"] == "LGU_Purchase Order") {
                if ($obju_LGUPurchaseOrderItems->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderItems->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderItems->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseOrderItems->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseOrderItems->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseOrderItems->update($obju_LGUPurchaseOrderItems->docid, $obju_LGUPurchaseOrderItems->lineid, $obju_LGUPurchaseOrderItems->rcdversion);
                    if (!$actionReturn)
                        break;
                }
                if ($obju_LGUPurchaseOrderService->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseOrderService->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderService->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseOrderService->setudfvalue("u_openquantity", $obju_LGUPurchaseOrderService->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseOrderService->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseOrderService->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseOrderService->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseOrderService->update($obju_LGUPurchaseOrderService->docid, $obju_LGUPurchaseOrderService->lineid, $obju_LGUPurchaseOrderService->rcdversion);
                    if (!$actionReturn)
                        break;
                }
            }else if ($objRs->fields["U_BASETYPENM"] == "LGU_Progress Billing") {
                if ($obju_LGUSplitPOItems->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUSplitPOItems->setudfvalue("u_openquantity", $obju_LGUSplitPOItems->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUSplitPOItems->setudfvalue("u_openquantity", $obju_LGUSplitPOItems->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUSplitPOItems->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUSplitPOItems->setudfvalue("u_openquantity", 0);
                        $obju_LGUSplitPOItems->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUSplitPOItems->update($obju_LGUSplitPOItems->docid, $obju_LGUSplitPOItems->lineid, $obju_LGUSplitPOItems->rcdversion);
                    if (!$actionReturn)
                        break;
                }
                if ($obju_LGUSplitPOService->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUSplitPOService->setudfvalue("u_openquantity", $obju_LGUSplitPOService->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUSplitPOService->setudfvalue("u_openquantity", $obju_LGUSplitPOService->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUSplitPOService->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUSplitPOService->setudfvalue("u_openquantity", 0);
                        $obju_LGUSplitPOService->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUSplitPOService->update($obju_LGUSplitPOService->docid, $obju_LGUSplitPOService->lineid, $obju_LGUSplitPOService->rcdversion);
                    if (!$actionReturn)
                        break;
                }
            }
        }
        if (!$actionReturn)
            break;
    }
    if ($actionReturn) {
        $objRs1 = new recordset(null, $objConnection);
        if ($objTable->getudfvalue("u_doctype") == "I") {
            $objRs1->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchasedeliveryitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        } else {
            $objRs1->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchasedeliveryservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        }
        while ($objRs1->queryfetchrow("NAME")) {
            if ($objRs1->fields["U_BASETYPENM"] != "") {
                if ($objRs1->fields["U_BASETYPENM"] == "LGU_Purchase Order") {
                    if ($obju_LGUPurchaseOrder->getbykey($objRs1->fields["U_BASEDOCNO"])) {
                        $objRs2 = new recordset(null, $objConnection);
                        if ($objTable->getudfvalue("u_doctype") == "I") {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaseorderitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseOrder->docid' ");
                        } else {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaseorderservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseOrder->docid' ");
                        }
                        while ($objRs2->queryfetchrow("NAME")) {
                            if ($objRs2->fields["U_OPENQUANTITY"] <= 0) {
                                $obju_LGUPurchaseOrder->docstatus = "C";
                                $actionReturn = $obju_LGUPurchaseOrder->update($obju_LGUPurchaseOrder->docno, $obju_LGUPurchaseOrder->rcdversion);
                            }
                        }
                    }
                    if (!$actionReturn)
                        break;
                } else if ($objRs1->fields["U_BASETYPENM"] == "LGU_Progress Billing") {
                    if ($obju_LGUSplitPO->getbykey($objRs1->fields["U_BASEDOCNO"])) {
                        $objRs2 = new recordset(null, $objConnection);
                        if ($objTable->getudfvalue("u_doctype") == "I") {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgusplitpoitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUSplitPO->docid' ");
                        } else {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgusplitposervice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUSplitPO->docid' ");
                        }
                        while ($objRs2->queryfetchrow("NAME")) {
                            if ($objRs2->fields["U_OPENQUANTITY"] <= 0) {
                                $obju_LGUSplitPO->docstatus = "C";
                                $actionReturn = $obju_LGUSplitPO->update($obju_LGUSplitPO->docno, $obju_LGUSplitPO->rcdversion);
                            }
                        }
                    }
                    if (!$actionReturn)
                        break;
                }
            }
            if (!$actionReturn)
                break;
        }
    }

    return $actionReturn;
}
function onCustomeventUpdateStocksdocumentschema_brGPSLGUPurchasing($objTable, $delete = false) {
    global $objConnection;
    global $page;
    $actionReturn = true;

    $obju_LGUStockCardCosting = new documentschema_br(null, $objConnection, "u_lgustockcardcosting");
    $obju_LGUItems = new masterdataschema(null, $objConnection, "u_lguitems");
    $obju_LGUStockCardSummary = new masterdataschema(null, $objConnection, "u_lgustockcardsummary");

    $objRs = new recordset(null, $objConnection);
    if ($objTable->getudfvalue("u_doctype") == "I") {
        $objRs->queryopen("select B.U_COSTMETHOD,a.LINEID,a.U_WHSCODE,a.U_ITEMCODE,a.U_BASELINEID,a.U_BASETYPENM, a.U_BASEDOCID, a.U_BASEDOCNO,a.U_QUANTITY,a.U_COST from u_lgupurchasedeliveryitems a LEFT JOIN U_LGUITEMS b on a.u_itemcode = b.code where a.company='" . $_SESSION["company"] . "' and a.branch='" . $_SESSION["branch"] . "' and a.docid='$objTable->docid' ");
    } 
    while ($objRs->queryfetchrow("NAME")) {
        //insert/update stockcardcosting
        $insert = true;
        if ($obju_LGUStockCardCosting->getbysql("company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and u_itemcode ='" . $objRs->fields["U_ITEMCODE"] . "' and u_whscode='" . $objRs->fields["U_WHSCODE"] . "' ")) {
                 if ($objRs->fields["U_COSTMETHOD"] == 0) {
                    if (!$delete) {
                        $obju_LGUStockCardCosting->setudfvalue("u_qty", $obju_LGUStockCardCosting->getudfvalue("u_qty") + $objRs->fields["U_QUANTITY"]);
                        $obju_LGUStockCardCosting->setudfvalue("u_stockvalue", $obju_LGUStockCardCosting->getudfvalue("u_stockvalue") + $objRs->fields["U_COST"]);
                    } else {
                        $obju_LGUStockCardCosting->setudfvalue("u_qty", $obju_LGUStockCardCosting->getudfvalue("u_qty") - $objRs->fields["U_QUANTITY"]);
                        $obju_LGUStockCardCosting->setudfvalue("u_stockvalue", $obju_LGUStockCardCosting->getudfvalue("u_stockvalue") - $objRs->fields["U_COST"]);
                    }
                    $actionReturn = $obju_LGUStockCardCosting->update($obju_LGUStockCardCosting->docno, $obju_LGUStockCardCosting->rcdversion);
                    $insert = false;
                 } 
        }
        if ($insert) {
                $obju_LGUStockCardCosting->prepareadd();
                $obju_LGUStockCardCosting->docno = getNextNoByBranch("u_lgustockcardcosting","",$objConnection);
                $obju_LGUStockCardCosting->docid = getNextIdByBranch("u_lgustockcardcosting",$objConnection);
                $obju_LGUStockCardCosting->setudfvalue("u_refno",$objTable->docno);
                $obju_LGUStockCardCosting->setudfvalue("u_reflineid",$objRs->fields["LINEID"]);
                $obju_LGUStockCardCosting->setudfvalue("u_reftype","GR");
                if ($objRs->fields["U_COSTMETHOD"] == 0) {
                    $obju_LGUStockCardCosting->setudfvalue("u_refdate","0000-00-00");
                    $obju_LGUStockCardCosting->setudfvalue("u_agedate","0000-00-00");
                } else {
                    $obju_LGUStockCardCosting->setudfvalue("u_refdate",$objTable->getudfvalue("u_date"));
                    $obju_LGUStockCardCosting->setudfvalue("u_agedate",$objTable->getudfvalue("u_date"));
                }
                $obju_LGUStockCardCosting->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                $obju_LGUStockCardCosting->setudfvalue("u_whscode",$objRs->fields["U_WHSCODE"]);
                $obju_LGUStockCardCosting->setudfvalue("u_costmethod",$objRs->fields["U_COSTMETHOD"]);
                $obju_LGUStockCardCosting->setudfvalue("u_qty",$objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardCosting->setudfvalue("u_stockvalue",$objRs->fields["U_COST"]);
                $obju_LGUStockCardCosting->setudfvalue("u_remarks",$objRs->fields["U_BASETYPENM"] . " - " . $objRs->fields["U_BASETYPENM"]);
                $actionReturn = $obju_LGUStockCardCosting->add();
        }
        
        //update stockcardsummary
        $code = $_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $objRs->fields["U_WHSCODE"] . "-" . $objRs->fields["U_ITEMCODE"];
        if ($obju_LGUStockCardSummary->getbysql("CODE='" . $code . "'")) {
            if (!$delete) {
                $obju_LGUStockCardSummary->setudfvalue("u_availableqty", $obju_LGUStockCardSummary->getudfvalue("u_availableqty") + $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_instockqty", $obju_LGUStockCardSummary->getudfvalue("u_instockqty") + $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_stockvalue", $obju_LGUStockCardSummary->getudfvalue("u_stockvalue") + $objRs->fields["U_COST"]);
               
            } else {
                $obju_LGUStockCardSummary->setudfvalue("u_availableqty", $obju_LGUStockCardSummary->getudfvalue("u_availableqty") - $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_instockqty", $obju_LGUStockCardSummary->getudfvalue("u_instockqty") - $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_stockvalue", $obju_LGUStockCardSummary->getudfvalue("u_stockvalue") - $objRs->fields["U_COST"]);
                
            }
            $obju_LGUStockCardSummary->setudfvalue("u_avgprice", $obju_LGUStockCardSummary->getudfvalue("u_stockvalue") / $obju_LGUStockCardSummary->getudfvalue("u_availableqty"));
            $actionReturn = $obju_LGUStockCardSummary->update($obju_LGUStockCardSummary->code, $obju_LGUStockCardSummary->rcdversion);
        } else {
                $obju_LGUStockCardSummary->prepareadd();
		$obju_LGUStockCardSummary->code = $code;
                $obju_LGUStockCardSummary->setudfvalue("u_itemcode",$objRs->fields["U_ITEMCODE"]);
                $obju_LGUStockCardSummary->setudfvalue("u_whscode",$objRs->fields["U_WHSCODE"]);
                $obju_LGUStockCardSummary->setudfvalue("u_instockqty",$objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_availableqty",$objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_stockvalue",$objRs->fields["U_COST"]);
                $obju_LGUStockCardSummary->setudfvalue("u_avgprice",$objRs->fields["U_COST"] / $objRs->fields["U_QUANTITY"]);
                $actionReturn = $obju_LGUStockCardSummary->add();
        }

        if (!$actionReturn)
            break;
    }
    

    return $actionReturn;
}

function onCustomeventUpdateStocksGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable, $delete = false) {
    global $objConnection;
    global $page;
    $actionReturn = true;
    
    $obju_LGUStockCardCosting = new documentschema_br(null, $objConnection, "u_lgustockcardcosting");
    $obju_LGUItems = new masterdataschema(null, $objConnection, "u_lguitems");
    $obju_LGUStockCardSummary = new masterdataschema(null, $objConnection, "u_lgustockcardsummary");
    
    $obju_LGUGoodsIssueItems = new documentlinesschema_br(null, $objConnection, "u_lgugoodsissueitems");
    
    $objRs = new recordset(null, $objConnection);
    $objRs_StockcardCosting = new recordset(null, $objConnection);
    $objRs->queryopen("select b.U_COSTMETHOD,a.LINEID,a.U_WHSCODE,a.U_ITEMCODE,a.U_QUANTITY,a.U_LINETOTAL,a.U_UNITPRICE from u_lgugoodsissueitems a LEFT JOIN U_LGUITEMS b on a.u_itemcode = b.code where a.company='" . $_SESSION["company"] . "' and a.branch='" . $_SESSION["branch"] . "' and a.docid='$objTable->docid' ");
    
    while ($objRs->queryfetchrow("NAME")) {
        $qty = $objRs->fields["U_QUANTITY"] ;
        $lineqty = $objRs->fields["U_QUANTITY"] ;
        $stockvalue = 0;
        $stockvaluecost = 0;
        $avgprice = 0;
        
        switch($objRs->fields["U_COSTMETHOD"]) {
            case "0":
                $code = $_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $objRs->fields["U_WHSCODE"] . "-" . $objRs->fields["U_ITEMCODE"];
                if ($obju_LGUStockCardSummary->getbysql("CODE='" . $code . "'")) {
                        $avgprice = $obju_LGUStockCardSummary->getudfvalue("u_avgprice");
                        $stockvalue = $qty * $avgprice;
                }
                if ($obju_LGUGoodsIssueItems->getbysql("company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and lineid ='" . $objRs->fields["LINEID"] . "' ")) {
                        $obju_LGUGoodsIssueItems->setudfvalue("u_itemcost",$avgprice);
                        $actionReturn = $obju_LGUGoodsIssueItems->update($obju_LGUGoodsIssueItems->docid, $obju_LGUGoodsIssueItems->lineid, $obju_LGUGoodsIssueItems->rcdversion);
                }
          
                //update stockcardcosting
                if ($obju_LGUStockCardCosting->getbysql("company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and u_itemcode ='" . $objRs->fields["U_ITEMCODE"] . "' and u_whscode='" . $objRs->fields["U_WHSCODE"] . "' ")) {
                    if (!$delete) {
                        $obju_LGUStockCardCosting->setudfvalue("u_qty", $obju_LGUStockCardCosting->getudfvalue("u_qty") - $qty);
                        $obju_LGUStockCardCosting->setudfvalue("u_stockvalue", $obju_LGUStockCardCosting->getudfvalue("u_stockvalue") - $stockvalue);
                    } else {
                        $obju_LGUStockCardCosting->setudfvalue("u_qty", $obju_LGUStockCardCosting->getudfvalue("u_qty") + $qty);
                        $obju_LGUStockCardCosting->setudfvalue("u_stockvalue", $obju_LGUStockCardCosting->getudfvalue("u_stockvalue") + $stockvalue);
                    }
                    if ($obju_LGUStockCardCosting->getudfvalue("u_qty") == 0 ) {
                        if ($actionReturn) $actionReturn = $obju_LGUStockCardCosting->delete();
                    } else if ($obju_LGUStockCardCosting->getudfvalue("u_qty") < 0 ) {
                        return raiseError("Item [".$objRs->fields["U_ITEMCODE"]."--".$objRs->fields["U_WHSCODE"]."] stock quantity is negative [".$obju_LGUStockCardCosting->getudfvalue("u_qty")."]");
                    } else {
                        if ($actionReturn) $actionReturn = $obju_LGUStockCardCosting->update($obju_LGUStockCardCosting->docno, $obju_LGUStockCardCosting->rcdversion);
                    }
                }
                
                break;
            case "2":
                $objRs_StockcardCosting->queryopen("select DOCNO,U_QTY,U_STOCKVALUE from u_lgustockcardcosting where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and u_itemcode='".$objRs->fields["U_ITEMCODE"]."' and  u_whscode = '".$objRs->fields["U_WHSCODE"]."' ORDER BY U_AGEDATE");
                while ($objRs_StockcardCosting->queryfetchrow("NAME")) {
                  
                    if ($lineqty > 0) {
                       
                        if ($lineqty > $objRs_StockcardCosting->fields["U_QTY"]) $qty = $objRs_StockcardCosting->fields["U_QTY"];
                        else $qty = $lineqty;
                        $stockvalue += $qty * ($objRs_StockcardCosting->fields["U_STOCKVALUE"] / $objRs_StockcardCosting->fields["U_QTY"]);
                        $stockvaluecost = $qty * ($objRs_StockcardCosting->fields["U_STOCKVALUE"] / $objRs_StockcardCosting->fields["U_QTY"]);
                        
                        //update stockcardcosting
                        if ($obju_LGUStockCardCosting->getbykey($objRs_StockcardCosting->fields["DOCNO"])) {
                            if (!$delete) {
                                $obju_LGUStockCardCosting->setudfvalue("u_qty", $obju_LGUStockCardCosting->getudfvalue("u_qty") - $qty);
                                $obju_LGUStockCardCosting->setudfvalue("u_stockvalue", $obju_LGUStockCardCosting->getudfvalue("u_stockvalue") - $stockvaluecost);
                            } else {
                                $obju_LGUStockCardCosting->setudfvalue("u_qty", $obju_LGUStockCardCosting->getudfvalue("u_qty") + $qty);
                                $obju_LGUStockCardCosting->setudfvalue("u_stockvalue", $obju_LGUStockCardCosting->getudfvalue("u_stockvalue") + $stockvaluecost);
                            }
                            if ($obju_LGUStockCardCosting->getudfvalue("u_qty") == 0 ) {
                                if ($actionReturn) $actionReturn = $obju_LGUStockCardCosting->delete();
                            } else if ($obju_LGUStockCardCosting->getudfvalue("u_qty") < 0 ) {
                                return raiseError("Item [".$objRs->fields["U_ITEMCODE"]."--".$objRs->fields["U_WHSCODE"]."] stock quantity is negative [".$obju_LGUStockCardCosting->getudfvalue("u_qty")."]");
                            } else {
                                 $actionReturn = $obju_LGUStockCardCosting->update($obju_LGUStockCardCosting->docno, $obju_LGUStockCardCosting->rcdversion);
                            }
                        }
                    }
                      $lineqty = $lineqty - $qty;
                    
                }
                if ($obju_LGUGoodsIssueItems->getbysql("company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and lineid ='" . $objRs->fields["LINEID"] . "' ")) {
                        $obju_LGUGoodsIssueItems->setudfvalue("u_itemcost",$stockvalue / $objRs->fields["U_QUANTITY"]);
                        $actionReturn = $obju_LGUGoodsIssueItems->update($obju_LGUGoodsIssueItems->docid, $obju_LGUGoodsIssueItems->lineid, $obju_LGUGoodsIssueItems->rcdversion);
                }
                
                break;
        }
      
        //update stockcardsummary
        $code = $_SESSION["company"] . "-" . $_SESSION["branch"] . "-" . $objRs->fields["U_WHSCODE"] . "-" . $objRs->fields["U_ITEMCODE"];
        if ($obju_LGUStockCardSummary->getbysql("CODE='" . $code . "'")) {
            if (!$delete) {
                $obju_LGUStockCardSummary->setudfvalue("u_availableqty", $obju_LGUStockCardSummary->getudfvalue("u_availableqty") - $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_instockqty", $obju_LGUStockCardSummary->getudfvalue("u_instockqty") - $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_stockvalue", $obju_LGUStockCardSummary->getudfvalue("u_stockvalue") - $stockvalue);
            } else {
                $obju_LGUStockCardSummary->setudfvalue("u_availableqty", $obju_LGUStockCardSummary->getudfvalue("u_availableqty") + $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_instockqty", $obju_LGUStockCardSummary->getudfvalue("u_instockqty") + $objRs->fields["U_QUANTITY"]);
                $obju_LGUStockCardSummary->setudfvalue("u_stockvalue", $obju_LGUStockCardSummary->getudfvalue("u_stockvalue") + $stockvalue);
            }
            
            if ($obju_LGUStockCardSummary->getudfvalue("u_instockqty") < 0 ) {
                return raiseError("Item [".$objRs->fields["U_ITEMCODE"]."--".$objRs->fields["U_WHSCODE"]."] stock quantity is negative [".$obju_LGUStockCardSummary->getudfvalue("u_instockqty")."]");
            }
            if ($obju_LGUStockCardSummary->getudfvalue("u_stockvalue") < 0) $obju_LGUStockCardSummary->setudfvalue("u_stockvalue", 0);
            
            $obju_LGUStockCardSummary->setudfvalue("u_avgprice", $obju_LGUStockCardSummary->getudfvalue("u_stockvalue") / $obju_LGUStockCardSummary->getudfvalue("u_availableqty"));
            $actionReturn = $obju_LGUStockCardSummary->update($obju_LGUStockCardSummary->code, $obju_LGUStockCardSummary->rcdversion);
        } else {
                return raiseError("Unable to find item [".$objRs->fields["U_ITEMCODE"]."--".$objRs->fields["U_WHSCODE"]."] in stockcard summary.");
        }
        if (!$actionReturn)
            break;
    }
    return $actionReturn;
}

function onCustomeventPostGoodsIssuedocumentschema_brGPSLGUPurchasing($objTable,$delete=false) {
	global $objConnection;
	global $page;
	$actionReturn = true;
	
	$jvsettings = getBusinessObjectSettings("JOURNALVOUCHER");
	
	$objRs = new recordset(null,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
			

	$objJvHdr->prepareadd();
	$objJvHdr->objectcode = "JOURNALVOUCHER";
	$objJvHdr->sbo_post_flag = $jvsettings["autopost"];
	$objJvHdr->jeposting = $jvsettings["jeposting"];
	$objJvHdr->docid = getNextIdByBranch("JOURNALVOUCHERS",$objConnection);
	$objJvHdr->docdate = $objTable->getudfvalue("u_date");
	$objJvHdr->docduedate = $objJvHdr->docdate;
	$objJvHdr->taxdate = $objJvHdr->docdate;
	$objJvHdr->docseries = "-1";
	$objJvHdr->docno = $objTable->getudfvalue("u_jevno");
	$objJvHdr->currency = $_SESSION["currency"];
	$objJvHdr->docstatus = "C";
	$objJvHdr->reference1 = $objTable->docno;
	$objJvHdr->reference2 = "Goods Issue";
	$objJvHdr->profitcenter = $objTable->getudfvalue("u_profitcenter");
	$objJvHdr->profitcentername = $objTable->getudfvalue("u_profitcentername");
	
	$objJvHdr->remarks = $objTable->getudfvalue("u_empname");
	
	$objJvHdr->postdate = $objJvHdr->docdate ." ". date('H:i:s');
	
	
	if ($actionReturn) {
		$objRs->queryopen("select U_ITEMCOST, U_QUANTITY, (U_QUANTITY * U_ITEMCOST) AS U_AMOUNT, c.U_GLACCTNO, c.U_GLACCTNAME,c.U_EXPENSEGLACCTNO, c.U_EXPENSEGLACCTNAME  from u_lgugoodsissueitems a inner join u_lguitems b on a.u_itemcode = b.code inner join u_lguitemgroups c on b.u_itemgroup = c.code where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.docid='".$objTable->docid."'");
		if ($objRs->queryfetchrow("NAME")) {
                        
                        $objJvDtl->prepareadd();
                        $objJvDtl->docid = $objJvHdr->docid;
                        $objJvDtl->objectcode = $objJvHdr->objectcode;
                        $objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
                        $objJvDtl->refbranch = $_SESSION["branch"];
                        $objJvDtl->projcode = "";
                        $objJvDtl->profitcenter4 = "GI DEBIT";
                        $objJvDtl->itemtype = "A";
                        $objJvDtl->itemno = $objRs->fields["U_EXPENSEGLACCTNO"];
                        $objJvDtl->itemname = $objRs->fields["U_EXPENSEGLACCTNAME"];
                        if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
                                        $objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			}
                       
                        $objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
                        $objJvDtl->privatedata["header"] = $objJvHdr;
                        $actionReturn = $objJvDtl->add();

			$objJvDtl->prepareadd();
			$objJvDtl->docid = $objJvHdr->docid;
			$objJvDtl->objectcode = $objJvHdr->objectcode;
			$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
			$objJvDtl->refbranch = $_SESSION["branch"];
			$objJvDtl->projcode = "";
			$objJvDtl->profitcenter4 = "GI CREDIT";
			$objJvDtl->itemtype = "A";
			$objJvDtl->itemno = $objRs->fields["U_GLACCTNO"];
			$objJvDtl->itemname = $objRs->fields["U_GLACCTNAME"];
                        if (!$delete) {
				if ($objRs->fields["U_AMOUNT"]>0) {
                                        $objJvDtl->credit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->credit;
					
				} else {
					$objJvDtl->debit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->debit;
				}
			} else {
				if ($objRs->fields["U_AMOUNT"]>0) {
					$objJvDtl->debit = $objRs->fields["U_AMOUNT"];
					$objJvDtl->grossamount = $objJvDtl->debit;
				} else {
					$objJvDtl->credit = bcmul($objRs->fields["U_AMOUNT"],-1,14);
					$objJvDtl->grossamount = $objJvDtl->credit;
				}
			}
			$objJvHdr->totaldebit += $objJvDtl->debit ;
			$objJvHdr->totalcredit += $objJvDtl->credit ;
			$objJvDtl->privatedata["header"] = $objJvHdr;
			$actionReturn = $objJvDtl->add();
			
		}
	}	
	if ($actionReturn) {
		$actionReturn = $objJvHdr->add();
	}	

	//if ($actionReturn) $actionReturn = raiseError("onCustomeventPostCashReceiptdocumentschema_brGPSLGUAcctg()");
	return $actionReturn;
}

function onCustomeventUpdateCopyFromPRCanvassingAndAwardNoticedocumentschema_brGPSLGUPurchasing($objTable, $delete = false) {
    global $objConnection;
    global $page;
    $actionReturn = true;

    $obju_LGUPurchaseRequests = new documentschema_br(null, $objConnection, "u_lgupurchaserequests");
    $obju_LGUPurchaseRequestItems = new documentlinesschema_br(null, $objConnection, "u_lgupurchaserequestitems");
    $obju_LGUPurchaseRequestService = new documentlinesschema_br(null, $objConnection, "u_lgupurchaserequestservice");

    $obju_LGUCanvassing = new documentschema_br(null, $objConnection, "u_lgucanvassing");
    $obju_LGUCanvassingItems = new documentlinesschema_br(null, $objConnection, "u_lgucanvassingitems");
    $obju_LGUCanvassingService = new documentlinesschema_br(null, $objConnection, "u_lgucanvassingservice");

    $obju_LGUAward = new documentschema_br(null, $objConnection, "u_lguaward");
    $obju_LGUAwardItems = new documentlinesschema_br(null, $objConnection, "u_lguawarditems");
    $obju_LGUAwardService = new documentlinesschema_br(null, $objConnection, "u_lguawardservice");

    $obju_LGUItems = new masterdataschema(null, $objConnection, "u_lguitems");

    $objRs = new recordset(null, $objConnection);
    if ($objTable->getudfvalue("u_doctype") == "I") {
        $objRs->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchaseorderitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    } else {
        $objRs->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchaseorderservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' ");
    }
    while ($objRs->queryfetchrow("NAME")) {
        //update instock quantity
        if ($obju_LGUItems->getbysql("CODE='" . $objRs->fields["U_ITEMCODE"] . "'")) {
            if (!$delete) {
                $obju_LGUItems->setudfvalue("u_orderedqty", $obju_LGUItems->getudfvalue("u_orderedqty") + $objRs->fields["U_QUANTITY"]);
            } else {
                $obju_LGUItems->setudfvalue("u_orderedqty", $obju_LGUItems->getudfvalue("u_orderedqty") - $objRs->fields["U_QUANTITY"]);
            }
            $actionReturn = $obju_LGUItems->update($obju_LGUItems->code, $obju_LGUItems->rcdversion);
        }

        if ($actionReturn) {
            if ($objRs->fields["U_BASETYPENM"] == "LGU_Purchase Request") {
                if ($obju_LGUPurchaseRequestItems->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestItems->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestItems->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseRequestItems->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseRequestItems->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseRequestItems->update($obju_LGUPurchaseRequestItems->docid, $obju_LGUPurchaseRequestItems->lineid, $obju_LGUPurchaseRequestItems->rcdversion);
                    if (!$actionReturn)
                        break;
                }
                if ($obju_LGUPurchaseRequestService->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUPurchaseRequestService->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestService->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUPurchaseRequestService->setudfvalue("u_openquantity", $obju_LGUPurchaseRequestService->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUPurchaseRequestService->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUPurchaseRequestService->setudfvalue("u_openquantity", 0);
                        $obju_LGUPurchaseRequestService->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUPurchaseRequestService->update($obju_LGUPurchaseRequestService->docid, $obju_LGUPurchaseRequestService->lineid, $obju_LGUPurchaseRequestService->rcdversion);
                    if (!$actionReturn)
                        break;
                }
            } else if ($objRs->fields["U_BASETYPENM"] == "LGU_Canvassing") {
                if ($obju_LGUCanvassingItems->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUCanvassingItems->setudfvalue("u_openquantity", $obju_LGUCanvassingItems->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUCanvassingItems->setudfvalue("u_openquantity", $obju_LGUCanvassingItems->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUCanvassingItems->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUCanvassingItems->setudfvalue("u_openquantity", 0);
                        $obju_LGUCanvassingItems->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUCanvassingItems->update($obju_LGUCanvassingItems->docid, $obju_LGUCanvassingItems->lineid, $obju_LGUCanvassingItems->rcdversion);
                    if (!$actionReturn)
                        break;
                }
                if ($obju_LGUCanvassingService->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUCanvassingService->setudfvalue("u_openquantity", $obju_LGUCanvassingService->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUCanvassingService->setudfvalue("u_openquantity", $obju_LGUCanvassingService->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUCanvassingService->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUCanvassingService->setudfvalue("u_openquantity", 0);
                        $obju_LGUCanvassingService->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUCanvassingService->update($obju_LGUCanvassingService->docid, $obju_LGUCanvassingService->lineid, $obju_LGUCanvassingService->rcdversion);
                    if (!$actionReturn)
                        break;
                }
            } else if ($objRs->fields["U_BASETYPENM"] == "LGU_Award") {
                if ($obju_LGUAwardItems->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUAwardItems->setudfvalue("u_openquantity", $obju_LGUAwardItems->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUAwardItems->setudfvalue("u_openquantity", $obju_LGUAwardItems->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUAwardItems->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUAwardItems->setudfvalue("u_openquantity", 0);
                        $obju_LGUAwardItems->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUAwardItems->update($obju_LGUAwardItems->docid, $obju_LGUAwardItems->lineid, $obju_LGUAwardItems->rcdversion);
                    if (!$actionReturn)
                        break;
                }
                if ($obju_LGUAwardService->getbysql("DOCID='" . $objRs->fields["U_BASEDOCID"] . "' AND LINEID='" . $objRs->fields["U_BASELINEID"] . "'")) {
                    if (!$delete) {
                        $obju_LGUAwardService->setudfvalue("u_openquantity", $obju_LGUAwardService->getudfvalue("u_openquantity") - $objRs->fields["U_QUANTITY"]);
                    } else {
                        $obju_LGUAwardService->setudfvalue("u_openquantity", $obju_LGUAwardService->getudfvalue("u_openquantity") + $objRs->fields["U_QUANTITY"]);
                    }
                    if ($obju_LGUAwardService->getudfvalue("u_openquantity") <= 0) {
                        $obju_LGUAwardService->setudfvalue("u_openquantity", 0);
                        $obju_LGUAwardService->setudfvalue("u_linestatus", "C");
                    }
                    $actionReturn = $obju_LGUAwardService->update($obju_LGUAwardService->docid, $obju_LGUAwardService->lineid, $obju_LGUAwardService->rcdversion);
                    if (!$actionReturn)
                        break;
                }
            }
        }
        if (!$actionReturn)
            break;
    }
    if ($actionReturn) {
        $objRs1 = new recordset(null, $objConnection);
        if ($objTable->getudfvalue("u_doctype") == "I") {
            $objRs1->queryopen("select U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchaseorderitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        } else {
            $objRs1->queryopen("select '' as U_ITEMCODE,U_BASELINEID,U_BASETYPENM, U_BASEDOCID, U_BASEDOCNO,U_QUANTITY from u_lgupurchaseorderservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$objTable->docid' GROUP BY U_BASEDOCNO ");
        }
        while ($objRs1->queryfetchrow("NAME")) {
            if ($objRs1->fields["U_BASETYPENM"] != "") {
                if ($objRs->fields["U_BASETYPENM"] == "LGU_Purchase Request") {
                    if ($obju_LGUPurchaseRequests->getbykey($objRs1->fields["U_BASEDOCNO"])) {
                        $objRs2 = new recordset(null, $objConnection);
                        if ($objTable->getudfvalue("u_doctype") == "I") {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaserequestitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseRequests->docid' ");
                        } else {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgupurchaserequestservice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUPurchaseRequests->docid' ");
                        }
                        while ($objRs2->queryfetchrow("NAME")) {
                            if ($objRs2->fields["U_OPENQUANTITY"] <= 0) {
                                $obju_LGUPurchaseRequests->docstatus = "C";
                                $actionReturn = $obju_LGUPurchaseRequests->update($obju_LGUPurchaseRequests->docno, $obju_LGUPurchaseRequests->rcdversion);
                            }
                        }
                    }
                    if (!$actionReturn)
                        break;
                }else if ($objRs->fields["U_BASETYPENM"] == "LGU_Canvassing") {
                    if ($obju_LGUCanvassing->getbykey($objRs1->fields["U_BASEDOCNO"])) {
                        $objRs2 = new recordset(null, $objConnection);
                        if ($objTable->getudfvalue("u_doctype") == "I") {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgusplitpoitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUCanvassing->docid' ");
                        } else {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgusplitposervice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUCanvassing->docid' ");
                        }
                        while ($objRs2->queryfetchrow("NAME")) {
                            if ($objRs2->fields["U_OPENQUANTITY"] <= 0) {
                                $obju_LGUCanvassing->docstatus = "C";
                                $actionReturn = $obju_LGUCanvassing->update($obju_LGUCanvassing->docno, $obju_LGUCanvassing->rcdversion);
                            }
                        }
                    }
                    if (!$actionReturn)
                        break;
                }else if ($objRs->fields["U_BASETYPENM"] == "LGU_Award") {
                    if ($obju_LGUAward->getbykey($objRs1->fields["U_BASEDOCNO"])) {
                        $objRs2 = new recordset(null, $objConnection);
                        if ($objTable->getudfvalue("u_doctype") == "I") {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgusplitpoitems where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUAward->docid' ");
                        } else {
                            $objRs2->queryopen("select SUM(U_OPENQUANTITY) AS U_OPENQUANTITY from u_lgusplitposervice where company='" . $_SESSION["company"] . "' and branch='" . $_SESSION["branch"] . "' and docid='$obju_LGUAward->docid' ");
                        }
                        while ($objRs2->queryfetchrow("NAME")) {
                            if ($objRs2->fields["U_OPENQUANTITY"] <= 0) {
                                $obju_LGUAward->docstatus = "C";
                                $actionReturn = $obju_LGUAward->update($obju_LGUAward->docno, $obju_LGUAward->rcdversion);
                            }
                        }
                    }
                    if (!$actionReturn)
                        break;
                }
            }
            if (!$actionReturn)
                break;
        }
    }

    return $actionReturn;
}

function onCustomeventUpdateProgramsProjectdocumentschema_brGPSLGUPurchasing($objTable, $delete = false) {
    global $objConnection;
    global $page;
    $actionReturn = true;

    $obju_LGUProjs = new masterdataschema_br(null, $objConnection, "u_lguprojs");
 
    if ($objTable->getudfvalue("u_projcode") != "") {
        if ($obju_LGUProjs->getbysql("CODE='" . $objTable->getudfvalue("u_projcode") . "'")) {
            if (!$delete) {
                $obju_LGUProjs->setudfvalue("u_totalpramount", $obju_LGUProjs->getudfvalue("u_totalpramount") + $objTable->getudfvalue("u_totalamount"));
            } else {
                if ($obju_LGUProjs->getudfvalue("u_totalpramount") > 0)
                    $obju_LGUProjs->setudfvalue("u_totalpramount", $obju_LGUProjs->getudfvalue("u_totalpramount") - $objTable->getudfvalue("u_totalamount"));
            }
            if ($obju_LGUProjs->getudfvalue("u_totalpramount") > $obju_LGUProjs->getudfvalue("u_estbudget")) {
                return raiseError("Total PR Amount must be less than or equal to Budget Amount.");
            }
            $obju_LGUProjs->update($obju_LGUProjs->code, $obju_LGUProjs->rcdversion);
        } else
            return raiseError("Unable to find Program/Project [" . $objTable->getudfvalue("u_projcode") . "].");
    }

    return $actionReturn;
}

?>