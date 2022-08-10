<?php
 

$page->businessobject->events->add->customAction("onCustomActionGPSRPTAS");

//$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSRPTAS");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSRPTAS");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSRPTAS");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSRPTAS");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSRPTAS");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSRPTAS");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSRPTAS");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSRPTAS");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSRPTAS");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSRPTAS");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSRPTAS");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSRPTAS");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSRPTAS");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSRPTAS");

function onGridColumnHeaderDraw($table, $column , &$ownerdraw) {
	global $page;
	global $objGrids;
	if ($table=="T1" && $column=="u_withpenalty") {
		$checked["name"] = $column;
		$checked["attributes"] = "";
		$checked["input"]["value"] = 1;
		echo "Penalty<input type=\"checkbox\"" . $objGrids[0]->genInputCheckBoxHtml($checked,$table) . "/>";
		$ownerdraw = true;
	}
}
function onCustomActionGPSRPTAS($action) {
        global $objConnection;
	global $page;
	global $objGrids;
        $actionReturn = true;
                  
            if ($action=="newfaas") {
                $objRpfaas1 = new documentschema_br(null,$objConnection,"u_rpfaas1");
                $objRpfaas2 = new documentschema_br(null,$objConnection,"u_rpfaas2");
                $objRpfaas3 = new documentschema_br(null,$objConnection,"u_rpfaas3");
                
                $objRpfaas1a = new documentschema_br(null,$objConnection,"u_rpfaas1a");
                $objRpfaas2a = new documentschema_br(null,$objConnection,"u_rpfaas2a");
                $objRpfaas3a = new documentschema_br(null,$objConnection,"u_rpfaas3a");
                
                $objRpfaas1p = new documentlinesschema_br(null,$objConnection,"u_rpfaas1p");
                $objRpfaas2p = new documentlinesschema_br(null,$objConnection,"u_rpfaas2p");
                $objRpfaas3p = new documentlinesschema_br(null,$objConnection,"u_rpfaas3p");
                
                $objRpfaas2c = new documentlinesschema_br(null,$objConnection,"u_rpfaas2c");
                $objRpfaas3b = new documentlinesschema_br(null,$objConnection,"u_rpfaas3b");
                
                $objConnection->beginwork();
                $objRs = new recordset(null,$objConnection);
                $objRs->queryopen("select b.u_withpenalty,b.u_tdno,B.u_ownername,b.u_tin,b.u_assvalue,b.u_kind,b.u_barangay,b.u_yearfr,b.u_yearto,b.u_years,b.u_curtdno from u_rpprevposting a inner join u_rpprevpostingitems b on a.docid = b.docid where a.docno = '".$page->getitemstring("docno")."' ");
             
                    while ($objRs->queryfetchrow("NAME")) {
                        $memomsg = "Note: This faas was made by posting the old faas with a total assessed value of ".$objRs->fields["u_assvalue"]." and created by ".$_SESSION["userid"] ;
                       if($objRs->fields["u_kind"]=="L"){
                            $objRpfaas1->prepareadd();
                            $objRpfaas1->docno = getNextNoByBranch("u_rpfaas1","",$objConnection);
                            $objRpfaas1->docid = getNextIdByBranch("u_rpfaas1",$objConnection);
                            $objRpfaas1->docseries = -1;
                            $objRpfaas1->docstatus = "Approved";
                            if($objRs->fields["u_withpenalty"]==0){
                                $objRpfaas1->setudfvalue("u_trxcode","DC");
                            }else{
                                $objRpfaas1->setudfvalue("u_trxcode","GR");
                            }
                          
                             $objRs1 = new recordset(null,$objConnection);
                             $objRs1->queryopen("select u_pin from u_rpfaas1 where u_tdno = '".$objRs->fields["u_curtdno"]."' ");
                                  while ($objRs1->queryfetchrow("NAME")) {
                                      $objRpfaas1->setudfvalue("u_pin",$objRs1->fields["u_pin"]);
                                  }
                            $objRpfaas1->setudfvalue("u_tdno",$objRs->fields["u_tdno"]);
                            $objRpfaas1->setudfvalue("u_varpno",$objRs->fields["u_tdno"]);
                            $objRpfaas1->setudfvalue("u_ownertype","C");
                            $objRpfaas1->setudfvalue("u_ownercompanyname",$objRs->fields["u_ownername"]);
                            $objRpfaas1->setudfvalue("u_ownername",$objRs->fields["u_ownername"]);
                            $objRpfaas1->setudfvalue("u_ownertin",$objRs->fields["u_tin"]);
                            $objRpfaas1->setudfvalue("u_barangay",$objRs->fields["u_barangay"]);
                            $objRpfaas1->setudfvalue("u_memoranda",$memomsg);
                            $objRpfaas1->setudfvalue("u_taxable",1);
                            $objRpfaas1->setudfvalue("u_cancelled",1);
                            $objRpfaas1->setudfvalue("u_effqtr",1);
                            $objRpfaas1->setudfvalue("u_effyear",$objRs->fields["u_yearfr"]);
                            $objRpfaas1->setudfvalue("u_effdate",$objRs->fields["u_yearfr"]."-01-01");
                            $objRpfaas1->setudfvalue("u_assvalue",$objRs->fields["u_assvalue"]);
                            $objRpfaas1->setudfvalue("u_expqtr",4);
                            $objRpfaas1->setudfvalue("u_expyear",$objRs->fields["u_yearto"]);
                            
                            $objRsRp1a = new recordset(null,$objConnection);
                            $objRsRp1a->queryopen("select b.u_class,b.u_assvalue,b.u_actualuse from u_rpprevposting a inner join u_rpprevpostingassessment b on a.docid = b.docid and a.company = b.company and a.branch = b.branch where a.docno = '".$page->getitemstring("docno")."' and b.u_tdnumber = '".$objRs->fields["u_tdno"]."' ");
                                while ($objRsRp1a->queryfetchrow("NAME")) {
                                      $objRpfaas1a->prepareadd();
                                      $objRpfaas1a->docno = getNextNoByBranch("u_rpfaas1a","",$objConnection);
                                      $objRpfaas1a->docid = getNextIdByBranch("u_rpfaas1a",$objConnection);
                                      $objRpfaas1a->setudfvalue("u_arpno",$objRpfaas1->docno);
                                      $objRpfaas1a->setudfvalue("u_class",$objRsRp1a->fields["u_class"]);
                                      $objRpfaas1a->setudfvalue("u_actualuse",$objRsRp1a->fields["u_actualuse"]);
                                      $objRpfaas1a->setudfvalue("u_assvalue",$objRsRp1a->fields["u_assvalue"]);
                                      $actionReturn = $objRpfaas1a->add();
                                      
                                      if (!$actionReturn) break;  
                                }
                            $objRsLguSetup = new recordset(null,$objConnection);
                            $objRsLguSetup->queryopen("select u_province,u_municipality from u_lgusetup");
                                while ($objRsLguSetup->queryfetchrow("NAME")) {
                                    $objRpfaas1->setudfvalue("u_municipality",$objRsLguSetup->fields["u_municipality"]);
                                    $objRpfaas1->setudfvalue("u_province",$objRsLguSetup->fields["u_province"]);
                                }

                            if($objRs->fields["u_curtdno"]!=""){
                                $objRs1a = new recordset(null,$objConnection);
                                $objRs1a->queryopen("select docid from u_rpfaas1 where u_tdno = '".$objRs->fields["u_curtdno"]."'"); 
                                    while ($objRs1a->queryfetchrow("NAME")) {
                                        $objRpfaas1p->prepareadd();
                                        $objRpfaas1p->docid = $objRs1a->fields["docid"];
                                        $objRpfaas1p->lineid = getNextIdByBranch("u_rpfaas1p",$objConnection);
                                        $objRpfaas1p->setudfvalue("u_prevarpno",$objRpfaas1->docno) ;
                                        $objRpfaas1p->setudfvalue("u_prevarpno2",$objRs->fields["u_tdno"]) ;
                                        $objRpfaas1p->setudfvalue("u_prevtdno",$objRs->fields["u_tdno"]) ;
                                        $objRpfaas1p->setudfvalue("u_prevowner",$objRs->fields["u_ownername"]) ;
                                        $objRpfaas1p->setudfvalue("u_prevvalue",$objRs->fields["u_assvalue"]) ;
                                        $objRpfaas1p->setudfvalue("u_preveffdate",$objRs->fields["u_yearfr"]."-01-01") ;
                                        $objRpfaas1p->setudfvalue("u_prevrecordeddate",currentdateDB()) ;
                                        $objRpfaas1p->setudfvalue("u_prevrecordedby",$_SESSION["userid"]);
                                        $actionReturn = $objRpfaas1p->add();
    //                                     var_dump($actionReturn);
                                      if (!$actionReturn) break;   
                                    }
                            }//end of faas1p
                               
                             $actionReturn = $objRpfaas1->add();  
                    }//end of Land
                       if($objRs->fields["u_kind"]=="B"){
                            $objRpfaas2->prepareadd();
                            $objRpfaas2->docno = getNextNoByBranch("u_rpfaas2","",$objConnection);
                            $objRpfaas2->docid = getNextIdByBranch("u_rpfaas2",$objConnection);
                            $objRpfaas2->docseries = -1;
                            $objRpfaas2->docstatus = "Approved";
                            if($objRs->fields["u_withpenalty"]==0){
                                $objRpfaas2->setudfvalue("u_trxcode","DC");
                            }else{
                                $objRpfaas2->setudfvalue("u_trxcode","GR");
                            }
                          
                             $objRs1 = new recordset(null,$objConnection);
                             $objRs1->queryopen("select u_pin from u_rpfaas2 where u_tdno = '".$objRs->fields["u_curtdno"]."' ");
                                while ($objRs1->queryfetchrow("NAME")) {
                                    $objRpfaas2->setudfvalue("u_pin",$objRs1->fields["u_pin"]);
                                }   
                            $objRpfaas2->setudfvalue("u_tdno",$objRs->fields["u_tdno"]);
                            $objRpfaas2->setudfvalue("u_varpno",$objRs->fields["u_tdno"]);
                            $objRpfaas2->setudfvalue("u_ownertype","C");
                            $objRpfaas2->setudfvalue("u_ownercompanyname",$objRs->fields["u_ownername"]);
                            $objRpfaas2->setudfvalue("u_ownername",$objRs->fields["u_ownername"]);
                            $objRpfaas2->setudfvalue("u_ownertin",$objRs->fields["u_tin"]);
                            $objRpfaas2->setudfvalue("u_barangay",$objRs->fields["u_barangay"]);
                            $objRpfaas2->setudfvalue("u_memoranda",$memomsg);
                            $objRpfaas2->setudfvalue("u_taxable",1);
                            $objRpfaas2->setudfvalue("u_cancelled",1);
                            $objRpfaas2->setudfvalue("u_effqtr",1);
                            $objRpfaas2->setudfvalue("u_effyear",$objRs->fields["u_yearfr"]);
                            $objRpfaas2->setudfvalue("u_effdate",$objRs->fields["u_yearfr"]."-01-01");
                            $objRpfaas2->setudfvalue("u_assvalue",$objRs->fields["u_assvalue"]);
                            $objRpfaas2->setudfvalue("u_expqtr",4);
                            $objRpfaas2->setudfvalue("u_expyear",$objRs->fields["u_yearto"]);
                             
                            $objRsRp2a = new recordset(null,$objConnection);
                            $objRsRp2a->queryopen("select b.u_class,b.u_assvalue,b.u_actualuse from u_rpprevposting a inner join u_rpprevpostingassessment b on a.docid = b.docid and a.company = b.company and a.branch = b.branch where a.docno = '".$page->getitemstring("docno")."' and b.u_tdnumber = '".$objRs->fields["u_tdno"]."' ");
                                while ($objRsRp2a->queryfetchrow("NAME")) {
                                      $objRpfaas2a->prepareadd();
                                      $objRpfaas2a->docno = getNextNoByBranch("u_rpfaas2a","",$objConnection);
                                      $objRpfaas2a->docid = getNextIdByBranch("u_rpfaas2a",$objConnection);
                                      $objRpfaas2a->setudfvalue("u_arpno",$objRpfaas2->docno);
                                      $objRpfaas2a->setudfvalue("u_class",$objRsRp2a->fields["u_class"]);
                                      $objRpfaas2a->setudfvalue("u_actualuse",$objRsRp2a->fields["u_actualuse"]);
                                      $objRpfaas2a->setudfvalue("u_assvalue",$objRsRp2a->fields["u_assvalue"]);
                                      $actionReturn = $objRpfaas2a->add();
                                      
                                      if (!$actionReturn) break;  
                                }
                            $objRsRp2c = new recordset(null,$objConnection);
                            $objRsRp2c->queryopen("select b.u_class,sum(b.u_assvalue) as u_assvalue,b.u_actualuse from u_rpprevposting a inner join u_rpprevpostingassessment b on a.docid = b.docid and a.company = b.company and a.branch = b.branch where a.docno = '".$page->getitemstring("docno")."' and b.u_tdnumber = '".$objRs->fields["u_tdno"]."' group by b.u_actualuse ");
                                while ($objRsRp2c->queryfetchrow("NAME")) {
                                      $objRpfaas2c->prepareadd();
                                      $objRpfaas2c->lineid = getNextIdByBranch("u_rpfaas2c",$objConnection);
                                      $objRpfaas2c->docid = $objRpfaas2->docid;
                                      $objRpfaas2c->setudfvalue("u_actualuse",$objRsRp2c->fields["u_actualuse"]);
                                      $objRpfaas2c->setudfvalue("u_assvalue",$objRsRp2c->fields["u_assvalue"]);
                                      $actionReturn = $objRpfaas2c->add();
                                      
                                      if (!$actionReturn) break;  
                                }

                            $objRsLguSetup = new recordset(null,$objConnection);
                            $objRsLguSetup->queryopen("select u_province,u_municipality from u_lgusetup");
                                while ($objRsLguSetup->queryfetchrow("NAME")) {
                                    $objRpfaas2->setudfvalue("u_municipality",$objRsLguSetup->fields["u_municipality"]);
                                    $objRpfaas2->setudfvalue("u_province",$objRsLguSetup->fields["u_province"]);
                                }

                            if($objRs->fields["u_curtdno"]!=""){
                                $objRs1a = new recordset(null,$objConnection);
                                $objRs1a->queryopen("select docid from u_rpfaas2 where u_tdno = '".$objRs->fields["u_curtdno"]."'"); 
                                    while ($objRs1a->queryfetchrow("NAME")) {
                                        $objRpfaas2p->prepareadd();
                                        $objRpfaas2p->docid = $objRs1a->fields["docid"];
                                        $objRpfaas2p->lineid = getNextIdByBranch("u_rpfaas1p",$objConnection);
                                        $objRpfaas2p->setudfvalue("u_prevarpno",$objRpfaas2->docno) ;
                                        $objRpfaas2p->setudfvalue("u_prevarpno2",$objRs->fields["u_tdno"]) ;
                                        $objRpfaas2p->setudfvalue("u_prevtdno",$objRs->fields["u_tdno"]) ;
                                        $objRpfaas2p->setudfvalue("u_prevowner",$objRs->fields["u_ownername"]) ;
                                        $objRpfaas2p->setudfvalue("u_prevvalue",$objRs->fields["u_assvalue"]) ;
                                        $objRpfaas2p->setudfvalue("u_preveffdate",$objRs->fields["u_yearfr"]."-01-01") ;
                                        $objRpfaas2p->setudfvalue("u_prevrecordeddate",currentdateDB()) ;
                                        $objRpfaas2p->setudfvalue("u_prevrecordedby",$_SESSION["userid"]);
                                        $actionReturn = $objRpfaas2p->add();
                                      if (!$actionReturn) break;   
                                    }
                            }//end of faas2p
                            
                             $actionReturn = $objRpfaas2->add();  
                    }//end of BUILDING
                       if($objRs->fields["u_kind"]=="M"){
                            $objRpfaas3->prepareadd();
                            $objRpfaas3->docno = getNextNoByBranch("u_rpfaas3","",$objConnection);
                            $objRpfaas3->docid = getNextIdByBranch("u_rpfaas3",$objConnection);
                            $objRpfaas3->docseries = -1;
                            $objRpfaas3->docstatus = "Approved";
                            if($objRs->fields["u_withpenalty"]==0){
                                $objRpfaas3->setudfvalue("u_trxcode","DC");
                            }else{
                                $objRpfaas2->setudfvalue("u_trxcode","GR");
                            }
                          
                             $objRs1 = new recordset(null,$objConnection);
                             $objRs1->queryopen("select u_pin from u_rpfaas3 where u_tdno = '".$objRs->fields["u_curtdno"]."' ");
                                  while ($objRs1->queryfetchrow("NAME")) {
                                      $objRpfaas3->setudfvalue("u_pin",$objRs1->fields["u_pin"]);
                                  }     
                            $objRpfaas3->setudfvalue("u_tdno",$objRs->fields["u_tdno"]);
                            $objRpfaas3->setudfvalue("u_varpno",$objRs->fields["u_tdno"]);
                            $objRpfaas3->setudfvalue("u_ownertype","C");
                            $objRpfaas3->setudfvalue("u_ownercompanyname",$objRs->fields["u_ownername"]);
                            $objRpfaas3->setudfvalue("u_ownername",$objRs->fields["u_ownername"]);
                            $objRpfaas3->setudfvalue("u_ownertin",$objRs->fields["u_tin"]);
                            $objRpfaas3->setudfvalue("u_barangay",$objRs->fields["u_barangay"]);
                            $objRpfaas3->setudfvalue("u_memoranda",$memomsg);
                            $objRpfaas3->setudfvalue("u_taxable",1);
                            $objRpfaas3->setudfvalue("u_cancelled",1);
                            $objRpfaas3->setudfvalue("u_effqtr",1);
                            $objRpfaas3->setudfvalue("u_effyear",$objRs->fields["u_yearfr"]);
                            $objRpfaas3->setudfvalue("u_effdate",$objRs->fields["u_yearfr"]."-01-01");
                            $objRpfaas3->setudfvalue("u_assvalue",$objRs->fields["u_assvalue"]);
                            $objRpfaas3->setudfvalue("u_expqtr",4);
                            $objRpfaas3->setudfvalue("u_expyear",$objRs->fields["u_yearto"]);

                            $objRsLguSetup = new recordset(null,$objConnection);
                            $objRsLguSetup->queryopen("select u_province,u_municipality from u_lgusetup");
                                while ($objRsLguSetup->queryfetchrow("NAME")) {
                                    $objRpfaas3->setudfvalue("u_municipality",$objRsLguSetup->fields["u_municipality"]);
                                    $objRpfaas3->setudfvalue("u_province",$objRsLguSetup->fields["u_province"]);
                                }
                                
                            $objRsRp3b = new recordset(null,$objConnection);
                            $objRsRp3b->queryopen("select b.u_class,sum(b.u_assvalue) as u_assvalue,b.u_actualuse from u_rpprevposting a inner join u_rpprevpostingassessment b on a.docid = b.docid and a.company = b.company and a.branch = b.branch where a.docno = '".$page->getitemstring("docno")."' and b.u_tdnumber = '".$objRs->fields["u_tdno"]."' group by b.u_actualuse ");
                                while ($objRsRp3b->queryfetchrow("NAME")) {
                                      $objRpfaas3b->prepareadd();
                                      $objRpfaas3b->lineid = getNextIdByBranch("u_rpfaas3b",$objConnection);
                                      $objRpfaas3b->docid = $objRpfaas3->docid;
                                      $objRpfaas3b->setudfvalue("u_actualuse",$objRsRp3b->fields["u_actualuse"]);
                                      $objRpfaas3b->setudfvalue("u_assvalue",$objRsRp3b->fields["u_assvalue"]);
                                      $actionReturn = $objRpfaas3b->add();
                                      
                                      if (!$actionReturn) break;  
                                }

                            if($objRs->fields["u_curtdno"]!=""){
                                $objRs1a = new recordset(null,$objConnection);
                                $objRs1a->queryopen("select docid from u_rpfaas3 where u_tdno = '".$objRs->fields["u_curtdno"]."'"); 
                                    while ($objRs1a->queryfetchrow("NAME")) {
                                        $objRpfaas3p->prepareadd();
                                        $objRpfaas3p->docid = $objRs1a->fields["docid"];
                                        $objRpfaas3p->lineid = getNextIdByBranch("u_rpfaas1p",$objConnection);
                                        $objRpfaas3p->setudfvalue("u_prevarpno",$objRpfaas3->docno) ;
                                        $objRpfaas3p->setudfvalue("u_prevarpno2",$objRs->fields["u_tdno"]) ;
                                        $objRpfaas3p->setudfvalue("u_prevtdno",$objRs->fields["u_tdno"]) ;
                                        $objRpfaas3p->setudfvalue("u_prevowner",$objRs->fields["u_ownername"]) ;
                                        $objRpfaas3p->setudfvalue("u_prevvalue",$objRs->fields["u_assvalue"]) ;
                                        $objRpfaas3p->setudfvalue("u_preveffdate",$objRs->fields["u_yearfr"]."-01-01") ;
                                        $objRpfaas3p->setudfvalue("u_prevrecordeddate",currentdateDB()) ;
                                        $objRpfaas3p->setudfvalue("u_prevrecordedby",$_SESSION["userid"]);
                                        $actionReturn = $objRpfaas3p->add();
    //                                     var_dump($actionReturn);
                                      if (!$actionReturn) break;   
                                    }
                            }//end of faas1p
                            
                             $actionReturn = $objRpfaas3->add();  
                    }//end of MACHINE
                      
                               
                       
                    if (!$actionReturn) break;
                    }
                    
            if($actionReturn) $objRs->executesql("UPDATE u_rpprevposting set docstatus = 'C' where docno = '".$page->getitemstring("docno")."' ",true); 
            }
             
        if ($actionReturn) {
              $objConnection->commit();
        }else{
              $objConnection->rollback();
        }
        
	return true;
}

function onBeforeDefaultGPSRPTAS() { 
	return true;
}

function onAfterDefaultGPSRPTAS() { 
        global $objConnection;
	global $page;
        $page->setitem("u_years",date('Y'));
        $page->setitem("u_date",currentdate());
	return true;
}

function onPrepareAddGPSRPTAS(&$override) { 
	return true;
}

function onBeforeAddGPSRPTAS() { 
	return true;
}

function onAfterAddGPSRPTAS() { 
	return true;
}

function onPrepareEditGPSRPTAS(&$override) { 
	return true;
}

function onBeforeEditGPSRPTAS() { 
	return true;
}

function onAfterEditGPSRPTAS() { 
	return true;
}

function onPrepareUpdateGPSRPTAS(&$override) { 
	return true;
}

function onBeforeUpdateGPSRPTAS() { 
	return true;
}

function onAfterUpdateGPSRPTAS() { 
	return true;
}

function onPrepareDeleteGPSRPTAS(&$override) { 
	return true;
}

function onBeforeDeleteGPSRPTAS() { 
	return true;
}

function onAfterDeleteGPSRPTAS() { 
	return true;
}

$page->businessobject->items->seteditable("u_date",false);
$page->businessobject->items->seteditable("u_ownertin",false);
$page->businessobject->items->seteditable("u_ownername",false);
$page->businessobject->items->seteditable("docno",false);
$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("docseries",false);

$page->businessobject->items->setcfl("u_date","Calendar");
$page->businessobject->items->setcfl("u_ownertin","OpenCFLfs()");

$objGrids[0]->automanagecolumnwidth = false;
$objGrids[0]->columnwidth("u_withpenalty",8);
$objGrids[0]->columnwidth("u_tdno",20);
$objGrids[0]->columnwidth("u_ownername",30);
$objGrids[0]->columnwidth("u_curtdno",20);
$objGrids[0]->columnwidth("u_barangay",20);
$objGrids[0]->columnwidth("u_tin",20);
$objGrids[0]->columnwidth("u_Assvalue",10);
$objGrids[0]->columnwidth("u_kind",10);
$objGrids[0]->columnwidth("u_yearfr",8);
$objGrids[0]->columnwidth("u_yearto",8);
$objGrids[0]->columnwidth("u_years",8);

$objGrids[0]->columnattributes("u_years","disabled");
$objGrids[0]->columnattributes("u_tin","disabled");

$objGrids[0]->columntitle("u_years","Years");
$objGrids[0]->columntitle("u_yearfr","From");
$objGrids[0]->columntitle("u_yearto","To");
$objGrids[0]->columntitle("u_curtdno","Current Td #");

$objGrids[0]->columncfl("u_curtdno","OpenCFLfs()");
$objGrids[1]->columncfl("u_tdnumber","OpenCFLfs()");

$objGrids[0]->height = 300;
$objGrids[0]->width = 1000;

$objMaster->reportaction = "QS";
?> 

