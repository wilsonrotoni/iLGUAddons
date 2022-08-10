<?php
 
include_once("./sls/enumyear.php");

//$page->businessobject->events->add->customAction("onCustomActionGPSLGUBuilding");

$page->businessobject->events->add->beforeDefault("onBeforeDefaultGPSLGUBuilding");
$page->businessobject->events->add->afterDefault("onAfterDefaultGPSLGUBuilding");

//$page->businessobject->events->add->prepareAdd("onPrepareAddGPSLGUBuilding");
//$page->businessobject->events->add->beforeAdd("onBeforeAddGPSLGUBuilding");
//$page->businessobject->events->add->afterAdd("onAfterAddGPSLGUBuilding");

//$page->businessobject->events->add->prepareEdit("onPrepareEditGPSLGUBuilding");
//$page->businessobject->events->add->beforeEdit("onBeforeEditGPSLGUBuilding");
//$page->businessobject->events->add->afterEdit("onAfterEditGPSLGUBuilding");

//$page->businessobject->events->add->prepareUpdate("onPrepareUpdateGPSLGUBuilding");
//$page->businessobject->events->add->beforeUpdate("onBeforeUpdateGPSLGUBuilding");
//$page->businessobject->events->add->afterUpdate("onAfterUpdateGPSLGUBuilding");

//$page->businessobject->events->add->prepareDelete("onPrepareDeleteGPSLGUBuilding");
//$page->businessobject->events->add->beforeDelete("onBeforeDeleteGPSLGUBuilding");
//$page->businessobject->events->add->afterDelete("onAfterDeleteGPSLGUBuilding");
$appdata= array();
function onCustomActionGPSLGUBuilding($action) {
	return true;
}

function onBeforeDefaultGPSLGUBuilding() { 
        global $page;
	global $appdata;
	$appdata["u_bpno"] = $page->getitemstring("u_bpno");
	$appdata["u_apptype"] = $page->getitemstring("u_apptype");
	$appdata["u_businessname"] = $page->getitemstring("u_businessname");
	return true;
}

function onAfterDefaultGPSLGUBuilding() { 
        global $objConnection;
	global $page;
	global $objGrids;
	global $appdata;
		$objRs_uLGUSetup = new recordset(null,$objConnection);
		$objRs_uLGUSetup->queryopen("select A.u_MUNICIPALITY,A.U_CITY, A.u_province from U_LGUSETUP A");
		if ($objRs_uLGUSetup->queryfetchrow("NAME")) {
                if($objRs_uLGUSetup->fields["u_MUNICIPALITY"] !=""){
                    $page->setitem("u_bcity",$objRs_uLGUSetup->fields["u_MUNICIPALITY"]);
                }else{
                    $page->setitem("u_bcity",$objRs_uLGUSetup->fields["U_CITY"]);
                }
				$page->setitem("u_bprovince",$objRs_uLGUSetup->fields["u_province"]);
		}
        $page->setitem("u_year",date('Y'));
        $page->setitem("u_docdate",currentdate());
        $page->setitem("docstatus","D");
        $page->setitem("docseries","-1");
        $page->setitem("docno","");
        
        $objGrids[0]->clear();
	$objGrids[1]->clear();
	$objGrids[2]->clear();
	$objGrids[3]->clear();
	$objGrids[4]->clear();
	$objGrids[5]->clear();
        
        $engitotal=0;
        $objRs = new recordset(null,$objConnection);
         if ($appdata["u_bpno"]!="") {
                    if ($appdata["u_apptype"]=="RENEW") {
						$page->setitem("u_appnature","Renewal");
                        $page->businessobject->items->seteditable("u_businessname",false);
                    }
                    $obju_BpldgApps = new documentschema_br(null,$objConnection,"u_bldgapps");
                    if ($obju_BpldgApps->getbykey($appdata["u_bpno"])) {
                            $page->setitem("u_natureofbusiness",$obju_BpldgApps->getudfvalue("u_natureofbusiness"));
                            $page->setitem("u_address",$obju_BpldgApps->getudfvalue("u_address"));
                            $page->setitem("u_lastname",$obju_BpldgApps->getudfvalue("u_lastname"));
                            $page->setitem("u_firstname",$obju_BpldgApps->getudfvalue("u_firstname"));
                            $page->setitem("u_middlename",$obju_BpldgApps->getudfvalue("u_middlename"));
                            $page->setitem("u_owneraddress",$obju_BpldgApps->getudfvalue("u_owneraddress"));
                            $page->setitem("u_authrep",$obju_BpldgApps->getudfvalue("u_authrep"));
                            $page->setitem("u_gender",$obju_BpldgApps->getudfvalue("u_gender"));
                            $page->setitem("u_contactno",$obju_BpldgApps->getudfvalue("u_contactno"));
                            $page->setitem("u_istup",$obju_BpldgApps->getudfvalue("u_istup"));
                            $page->setitem("u_orsfamt",formatNumericAmount($obju_BpldgApps->getudfvalue("u_orsfamt")));
                            $page->setitem("u_businessname",$obju_BpldgApps->getudfvalue("u_businessname"));
                            $page->setitem("u_bbrgy",$obju_BpldgApps->getudfvalue("u_bbrgy"));
                            $page->setitem("u_bbldgno",$obju_BpldgApps->getudfvalue("u_bbldgno"));
                            $page->setitem("u_bblock",$obju_BpldgApps->getudfvalue("u_bblock"));
                            $page->setitem("u_blotno",$obju_BpldgApps->getudfvalue("u_blotno"));
                            $page->setitem("u_bvillage",$obju_BpldgApps->getudfvalue("u_bvillage"));
                            $page->setitem("u_bstreet",$obju_BpldgApps->getudfvalue("u_bstreet"));
                            $page->setitem("u_bphaseno",$obju_BpldgApps->getudfvalue("u_bphaseno"));
                            $page->setitem("u_baddressno",$obju_BpldgApps->getudfvalue("u_baddressno"));
                            $page->setitem("u_bbldgname",$obju_BpldgApps->getudfvalue("u_bbldgname"));
                            $page->setitem("u_bunitno",$obju_BpldgApps->getudfvalue("u_bunitno"));
                            $page->setitem("u_bfloorno",$obju_BpldgApps->getudfvalue("u_bfloorno"));
                            $page->setitem("u_bcity",$obju_BpldgApps->getudfvalue("u_bcity"));
                            $page->setitem("u_bprovince",$obju_BpldgApps->getudfvalue("u_bprovince"));
                            $page->setitem("u_btelno",$obju_BpldgApps->getudfvalue("u_btelno"));
                            $page->setitem("u_acctno",$obju_BpldgApps->getudfvalue("u_acctno"));

                            $objRs->queryopen("select u_feecode, u_feedesc, u_amount, u_seqno from
                                                (select u_feecode, u_feedesc, u_amount, u_seqno from u_bldgappfees where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                union all
                                                select code as u_feecode,name as u_feedesc, 0 as u_amount,u_seqno from u_bldgfees ) as x
                                                group by x.u_feecode,x.u_feedesc
                                                ORDER BY u_seqno");
                            while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[0]->addrow();
                                    $objGrids[0]->setitem(null,"u_year",date('Y'));
                                    $objGrids[0]->setitem(null,"u_feecode",$objRs->fields["u_feecode"]);
                                    $objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["u_feedesc"]);
                                    $objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                                    $objGrids[0]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                            }
                            $objRs->queryopen("SELECT x.code as u_code,x.u_desc,x.u_unitprice,sum(x.u_quantity) as u_quantity,x.u_unitprice * sum(x.u_quantity) as u_linetotal,sum(seq1) as seq1,sum(seq2) as seq2 FROM (
                                                        SELECT u_code as code,u_desc,u_unitprice,u_quantity,u_linetotal,0 as seq1,0 as seq2 FROM u_bldgcompute
                                                        where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                        UNION ALL
                                                        SELECT A.code,B.u_desc,B.u_unitprice,0 u_quantity,b.u_unitprice as u_linetotal,A.u_seqno as seq1,b.u_seqno as seq2 FROM U_bldgpermitcompute A
                                                        INNER JOIN U_bldgpermitcomputeitems B ON a.CODE = B.CODE
                                                        ) as x
                                                        group by x.code,x.u_desc
                                                        ORDER BY sum(seq1),sum(seq2) ");
                            while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[1]->addrow();
                                    $objGrids[1]->setitem(null,"u_code",$objRs->fields["u_code"]);
                                    $objGrids[1]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                                    $objGrids[1]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                                    $objGrids[1]->setitem(null,"u_quantity",$objRs->fields["u_quantity"]);
                                    $objGrids[1]->setitem(null,"u_linetotal",formatNumericAmount($objRs->fields["u_linetotal"]));
                    //		$sanitarytotal+=$objRs->fields["u_amount"];
                            }
                            $objRs->queryopen("SELECT x.code as u_code,x.u_desc,x.u_unitprice,sum(x.u_quantity) as u_quantity,x.u_unitprice * sum(x.u_quantity) as u_linetotal,sum(seq1) as seq1,sum(seq2) as seq2 FROM (
                                                        SELECT u_code as code,u_desc,u_unitprice,u_quantity,u_linetotal,0 as seq1,0 as seq2 FROM u_mechcompute
                                                        where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                        UNION ALL
                                                        SELECT A.code,B.u_desc,B.u_unitprice,0 u_quantity,b.u_unitprice as u_linetotal,A.u_seqno as seq1,b.u_seqno as seq2 FROM u_mechanicalcompute A
                                                        INNER JOIN u_mechanicalcomputeitems B ON a.CODE = B.CODE
                                                        ) as x
                                                        group by x.code,x.u_desc
                                                        ORDER BY sum(seq1),sum(seq2) ");
												
							while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[2]->addrow();
                                    $objGrids[2]->setitem(null,"u_code",$objRs->fields["u_code"]);
                                    $objGrids[2]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                                    $objGrids[2]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                                    $objGrids[2]->setitem(null,"u_quantity",$objRs->fields["u_quantity"]);
                                    $objGrids[2]->setitem(null,"u_linetotal",formatNumericAmount($objRs->fields["u_linetotal"]));
                    //		$sanitarytotal+=$objRs->fields["u_amount"];
                            }
                            $objRs->queryopen("SELECT x.code as u_code,x.u_desc,x.u_unitprice,sum(x.u_quantity) as u_quantity,x.u_unitprice * sum(x.u_quantity) as u_linetotal,sum(seq1) as seq1,sum(seq2) as seq2 FROM (
                                                        SELECT u_code as code,u_desc,u_unitprice,u_quantity,u_linetotal,0 as seq1,0 as seq2 FROM u_bldgplumbingcompute
                                                        where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                        UNION ALL
                                                        SELECT A.code,B.u_desc,B.u_unitprice,0 u_quantity,b.u_unitprice as u_linetotal,A.u_seqno as seq1,b.u_seqno as seq2 FROM u_plumbingcompute A
                                                        INNER JOIN u_plumbingcomputeitems B ON a.CODE = B.CODE
                                                        ) as x
                                                        group by x.code,x.u_desc
                                                        ORDER BY sum(seq1),sum(seq2) ");
												
                            while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[3]->addrow();
                                    $objGrids[3]->setitem(null,"u_code",$objRs->fields["u_code"]);
                                    $objGrids[3]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                                    $objGrids[3]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                                    $objGrids[3]->setitem(null,"u_quantity",$objRs->fields["u_quantity"]);
                                    $objGrids[3]->setitem(null,"u_linetotal",formatNumericAmount($objRs->fields["u_linetotal"]));
                    //		$sanitarytotal+=$objRs->fields["u_amount"];
                            }
							
                            $objRs->queryopen("SELECT x.code as u_code,x.u_desc,x.u_unitprice,sum(x.u_quantity) as u_quantity,x.u_unitprice * sum(x.u_quantity) as u_linetotal,sum(seq1) as seq1,sum(seq2) as seq2 FROM (
                                                        SELECT u_code as code,u_desc,u_unitprice,u_quantity,u_linetotal,0 as seq1,0 as seq2 FROM u_bldgelectricalcompute
                                                        where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                        UNION ALL
                                                        SELECT A.code,B.u_desc,B.u_unitprice,0 u_quantity,b.u_unitprice as u_linetotal,A.u_seqno as seq1,b.u_seqno as seq2 FROM u_electricalcompute A
                                                        INNER JOIN u_electricalcomputeitems B ON a.CODE = B.CODE
                                                        ) as x
                                                        group by x.code,x.u_desc
                                                        ORDER BY sum(seq1),sum(seq2) ");
                            
							while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[4]->addrow();
                                    $objGrids[4]->setitem(null,"u_code",$objRs->fields["u_code"]);
                                    $objGrids[4]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                                    $objGrids[4]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                                    $objGrids[4]->setitem(null,"u_quantity",$objRs->fields["u_quantity"]);
                                    $objGrids[4]->setitem(null,"u_linetotal",formatNumericAmount($objRs->fields["u_linetotal"]));
                    //		$sanitarytotal+=$objRs->fields["u_amount"];
                            }
							
                            $objRs->queryopen("SELECT x.code as u_code,x.u_desc,x.u_unitprice,sum(x.u_quantity) as u_quantity,x.u_unitprice * sum(x.u_quantity) as u_linetotal,sum(seq1) as seq1,sum(seq2) as seq2 FROM (
                                                        SELECT u_code as code,u_desc,u_unitprice,u_quantity,u_linetotal,0 as seq1,0 as seq2 FROM u_bldgsignagecompute
                                                        where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                        UNION ALL
                                                        SELECT A.code,B.u_desc,B.u_unitprice,0 u_quantity,b.u_unitprice as u_linetotal,A.u_seqno as seq1,b.u_seqno as seq2 FROM u_signagecompute A
                                                        INNER JOIN u_signagecomputeitems B ON a.CODE = B.CODE
                                                        ) as x
                                                        group by x.code,x.u_desc
                                                        ORDER BY sum(seq1),sum(seq2) ");
                            while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[5]->addrow();
                                    $objGrids[5]->setitem(null,"u_code",$objRs->fields["u_code"]);
                                    $objGrids[5]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                                    $objGrids[5]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                                    $objGrids[5]->setitem(null,"u_quantity",$objRs->fields["u_quantity"]);
                                    $objGrids[5]->setitem(null,"u_linetotal",formatNumericAmount($objRs->fields["u_linetotal"]));
                    //		$sanitarytotal+=$objRs->fields["u_amount"];
                            }
                            
                            $objRs->queryopen("SELECT x.code,sum(x.qty) as qty FROM (
                                                SELECT code,0 as qty FROM U_mechanicalcompute 
                                                UNION ALL 
                                                SELECT u_code as code, u_quantity as qty from u_mechgroupscompute 
                                                where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                ) as x
                                               group by x.code");
                            while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[7]->addrow();
                                    $objGrids[7]->setitem(null,"u_code",$objRs->fields["code"]);
                                    $objGrids[7]->setitem(null,"u_quantity",$objRs->fields["qty"]);
                    //		$sanitarytotal+=$objRs->fields["u_amount"];
                            }
                            
                            $objRs->queryopen("SELECT x.code,sum(x.qty) as qty FROM (
                                                SELECT code,0 as qty FROM U_electricalcompute 
                                                UNION ALL 
                                                SELECT u_code as code, u_quantity as qty from u_electricalgroupscompute 
                                                where company='$obju_BpldgApps->company' and branch='$obju_BpldgApps->branch' and docid='$obju_BpldgApps->docid'
                                                ) as x
                                               group by x.code");
                            while ($objRs->queryfetchrow("NAME")) {
                                    $objGrids[9]->addrow();
                                    $objGrids[9]->setitem(null,"u_code",$objRs->fields["code"]);
                                    $objGrids[9 ]->setitem(null,"u_quantity",$objRs->fields["qty"]);
                    //		$sanitarytotal+=$objRs->fields["u_amount"];
                            }
                    }
         } else {
                $objRs->queryopen("select code, name, u_amount,u_seqno from u_bldgfees order by u_seqno asc, name asc");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[0]->addrow();
                        $objGrids[0]->setitem(null,"u_year",date('Y'));
                        $objGrids[0]->setitem(null,"u_feecode",$objRs->fields["code"]);
                        $objGrids[0]->setitem(null,"u_feedesc",$objRs->fields["name"]);
                        $objGrids[0]->setitem(null,"u_seqno",$objRs->fields["u_seqno"]);
                        $objGrids[0]->setitem(null,"u_amount",formatNumericAmount($objRs->fields["u_amount"]));
                        $engitotal+=$objRs->fields["u_amount"];
                }

                $page->setitem("u_orsfamt",formatNumericAmount($engitotal));

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM U_bldgpermitcompute A
                                    INNER JOIN U_bldgpermitcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[1]->addrow();
                        $objGrids[1]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[1]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[1]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[1]->setitem(null,"u_quantity",0);
                        $objGrids[1]->setitem(null,"u_linetotal",formatNumericAmount(0));
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM U_mechanicalcompute A
                                    INNER JOIN u_mechanicalcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[2]->addrow();
                        $objGrids[2]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[2]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[2]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[2]->setitem(null,"u_quantity",0);
                        $objGrids[2]->setitem(null,"u_linetotal",formatNumericAmount(0));
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_plumbingcompute A
                                    INNER JOIN u_plumbingcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[3]->addrow();
                        $objGrids[3]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[3]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[3]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[3]->setitem(null,"u_quantity",0);
                        $objGrids[3]->setitem(null,"u_linetotal",formatNumericAmount(0));
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_electricalcompute A
                                    INNER JOIN u_electricalcomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[4]->addrow();
                        $objGrids[4]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[4]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[4]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[4]->setitem(null,"u_quantity",0);
                        $objGrids[4]->setitem(null,"u_linetotal",formatNumericAmount(0));
                }

                $objRs->queryopen("SELECT A.code,B.u_desc,B.u_unitprice FROM u_signagecompute A
                                    INNER JOIN u_signagecomputeitems B ON A.CODE = B.CODE
                                    ORDER BY A.u_seqno,b.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[5]->addrow();
                        $objGrids[5]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[5]->setitem(null,"u_desc",$objRs->fields["u_desc"]);
                        $objGrids[5]->setitem(null,"u_unitprice",formatNumericAmount($objRs->fields["u_unitprice"]));
                        $objGrids[5]->setitem(null,"u_quantity",0);
                        $objGrids[5]->setitem(null,"u_linetotal",formatNumericAmount(0));
                }
                
                $objRs->queryopen("SELECT A.code FROM U_bldgpermitcompute A
                                    ORDER BY A.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[6]->addrow();
                        $objGrids[6]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[6]->setitem(null,"u_quantity",0);
                }

                $objRs->queryopen("SELECT A.code FROM U_mechanicalcompute A
                                    ORDER BY A.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[7]->addrow();
                        $objGrids[7]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[7]->setitem(null,"u_quantity",0);
                }

                $objRs->queryopen("SELECT A.code FROM u_plumbingcompute A
                                    ORDER BY A.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[8]->addrow();
                        $objGrids[8]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[8]->setitem(null,"u_quantity",0);
                }

                $objRs->queryopen("SELECT A.code FROM u_electricalcompute A
                                    ORDER BY A.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[9]->addrow();
                        $objGrids[9]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[9]->setitem(null,"u_quantity",0);
                }

                $objRs->queryopen("SELECT A.code FROM u_signagecompute A
                                    ORDER BY A.u_seqno");
                while ($objRs->queryfetchrow("NAME")) {
                        $objGrids[10]->addrow();
                        $objGrids[10]->setitem(null,"u_code",$objRs->fields["code"]);
                        $objGrids[10]->setitem(null,"u_quantity",0);
                }
         }
	
        
	return true;
}

function onPrepareAddGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeAddGPSLGUBuilding() { 
	return true;
}

function onAfterAddGPSLGUBuilding() { 
	return true;
}

function onPrepareEditGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeEditGPSLGUBuilding() { 
	return true;
}

function onAfterEditGPSLGUBuilding() { 
	return true;
}

function onPrepareUpdateGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeUpdateGPSLGUBuilding() { 
	return true;
}

function onAfterUpdateGPSLGUBuilding() { 
	return true;
}

function onPrepareDeleteGPSLGUBuilding(&$override) { 
	return true;
}

function onBeforeDeleteGPSLGUBuilding() { 
	return true;
}

function onAfterDeleteGPSLGUBuilding() { 
	return true;
}
$addoptions = false;
$deleteoption = false;
$page->businessobject->items->setcfl("u_docdate","Calendar");
$page->businessobject->items->seteditable("u_year",false);
$page->businessobject->items->seteditable("docstatus",false);
$page->businessobject->items->seteditable("u_orsfamt",false);
$page->businessobject->items->seteditable("u_bplappno",false);
$page->businessobject->items->seteditable("u_acctno",false);
$page->businessobject->items->setcfl("u_zoningappno","OpenCFLfs()");
$page->businessobject->items->setcfl("u_natureofbusiness","OpenCFLfs()");

$objGrids[0]->columnvisibility("u_seqno",false);
$objGrids[0]->columncfl("u_feecode","OpenCFLfs()");
$objGrids[0]->columncfl("u_feedesc","OpenCFLfs()");
$objGrids[0]->columndataentry("u_year","type","select");
$objGrids[0]->columndataentry("u_year","options","loadenumyear");
$objGrids[0]->columnwidth("u_year",8);
//$objGrids[1]->columnwidth("u_unitprice",8);
//$objGrids[1]->columnwidth("u_linetotal",8);

$objGrids[1]->automanagecolumnwidth = false;
$objGrids[1]->columnwidth("u_code",20);
//$objGrids[1]->columnwidth("u_desc",30);
$objGrids[1]->columninput("u_quantity","type","text");
$objGrids[1]->columndataentry("u_quantity","type","label");
$objGrids[1]->columndataentry("u_unitprice","type","label");
$objGrids[1]->columndataentry("u_linetotal","type","label");
$objGrids[1]->columndataentry("u_desc","type","label");
$objGrids[1]->columndataentry("u_code","type","label");
$objGrids[1]->height = 500;
$objGrids[1]->width = 900;
$objGrids[1]->showactionbar = false;


$objGrids[2]->automanagecolumnwidth = false;
$objGrids[2]->columnwidth("u_quantity",8);
$objGrids[2]->columnwidth("u_unitprice",8);
$objGrids[2]->columnwidth("u_linetotal",8);
$objGrids[2]->columnwidth("u_code",37);
$objGrids[2]->columnwidth("u_desc",35);
//$objGrids[2]->columnwidth("u_desc",30);
$objGrids[2]->columninput("u_quantity","type","text");
$objGrids[2]->columndataentry("u_quantity","type","label");
$objGrids[2]->columndataentry("u_unitprice","type","label");
$objGrids[2]->columndataentry("u_linetotal","type","label");
$objGrids[2]->columndataentry("u_desc","type","label");
$objGrids[2]->columndataentry("u_code","type","label");
$objGrids[2]->showactionbar = false;

$objGrids[3]->automanagecolumnwidth = false;
//$objGrids[3]->columnwidth("u_quantity",8);
//$objGrids[3]->columnwidth("u_unitprice",8);
//$objGrids[3]->columnwidth("u_linetotal",8);
$objGrids[3]->columnwidth("u_code",20);
//$objGrids[3]->columnwidth("u_desc",30);
$objGrids[3]->columninput("u_quantity","type","text");
$objGrids[3]->columndataentry("u_quantity","type","label");
$objGrids[3]->columndataentry("u_unitprice","type","label");
$objGrids[3]->columndataentry("u_linetotal","type","label");
$objGrids[3]->columndataentry("u_desc","type","label");
$objGrids[3]->columndataentry("u_code","type","label");
$objGrids[3]->showactionbar = false;

$objGrids[4]->automanagecolumnwidth = false;
//$objGrids[4]->columnwidth("u_quantity",8);
//$objGrids[4]->columnwidth("u_unitprice",8);
//$objGrids[4]->columnwidth("u_linetotal",8);
$objGrids[4]->columnwidth("u_code",40);
$objGrids[4]->columnwidth("u_desc",25);
$objGrids[4]->columninput("u_quantity","type","text");
$objGrids[4]->columndataentry("u_quantity","type","label");
$objGrids[4]->columndataentry("u_unitprice","type","label");
$objGrids[4]->columndataentry("u_linetotal","type","label");
$objGrids[4]->columndataentry("u_desc","type","label");
$objGrids[4]->columndataentry("u_code","type","label");
$objGrids[4]->showactionbar = false;

$objGrids[5]->automanagecolumnwidth = false;
//$objGrids[5]->columnwidth("u_quantity",8);
//$objGrids[5]->columnwidth("u_unitprice",8);
//$objGrids[5]->columnwidth("u_linetotal",8);
$objGrids[5]->columnwidth("u_code",20);
//$objGrids[5]->columnwidth("u_desc",30);
$objGrids[5]->columninput("u_quantity","type","text");
$objGrids[5]->columndataentry("u_quantity","type","label");
$objGrids[5]->columndataentry("u_unitprice","type","label");
$objGrids[5]->columndataentry("u_linetotal","type","label");
$objGrids[5]->columndataentry("u_desc","type","label");
$objGrids[5]->columndataentry("u_code","type","label");
$objGrids[5]->showactionbar = false;

$objGrids[6]->columndataentry("u_code","type","label");
$objGrids[6]->columninput("u_quantity","type","text");
$objGrids[6]->columndataentry("u_quantity","type","label");
$objGrids[6]->showactionbar = false;

$objGrids[7]->columndataentry("u_code","type","label");
$objGrids[7]->columninput("u_quantity","type","text");
$objGrids[7]->columndataentry("u_quantity","type","label");
$objGrids[7]->showactionbar = false;

$objGrids[8]->columndataentry("u_code","type","label");
$objGrids[8]->columninput("u_quantity","type","text");
$objGrids[8]->columndataentry("u_quantity","type","label");
$objGrids[8]->showactionbar = false;

$objGrids[9]->columndataentry("u_code","type","label");
$objGrids[9]->columninput("u_quantity","type","text");
$objGrids[9]->columndataentry("u_quantity","type","label");
$objGrids[9]->showactionbar = false;

$objGrids[10]->columndataentry("u_code","type","label");
$objGrids[10]->columninput("u_quantity","type","text");
$objGrids[10]->columndataentry("u_quantity","type","label");
$objGrids[10]->showactionbar = false;

$objGrids[6]->columntitle("u_quantity","Total Quantity");
$objGrids[7]->columntitle("u_quantity","Total Quantity");
$objGrids[8]->columntitle("u_quantity","Total Quantity");
$objGrids[9]->columntitle("u_quantity","Total Quantity");
$objGrids[10]->columntitle("u_quantity","Total Quantity");
?> 

