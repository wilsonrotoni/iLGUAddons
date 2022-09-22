<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/documentlinesschema_br.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentitems.php");
	include_once("./classes/productionorders.php");
	include_once("./classes/productionorderitems.php");
	include_once("./classes/branches.php");
	include_once("./classes/brancheslist.php");
	include_once("./classes/users.php");
	
	$actionReturn = true;
	
	$mode="eBT";
	
	$objConnection->beginwork();
	$corid = $_GET["corid"];
	$_SESSION["userid"] = $_GET["userid"];
        
        $objs["result"] = array();
        $post_data["data"] = array();
        $datas = array();
        $result = array();
        $appno = "";
        $_SESSION["company"]="LGU";
        $_SESSION["branch"]="MAIN";
        
        array_push($post_data["data"],$_POST["data"]);
        
        $company = $_SESSION["company"];
        $branch = $_SESSION["branch"];
        $arr = get_defined_vars();
        
        $json_encode = json_encode($post_data);
        $obj = json_decode($json_encode,true);
        file_put_contents("c:\users\dashboardlinesdata.txt",serialize($obj));

        $obju_DashboardInvoice = new documentschema_br(null,$objConnection,"u_dashboardinvoice");
        $obju_DashboardInvoiceItems = new documentlinesschema_br(null,$objConnection,"u_dashboardinvoiceitems");
        $docid = "";
        $test = "";
        foreach($obj["data"][0] as $key => $value) {
            
            if ($key=="docno") {
                if ($obju_DashboardInvoice->getbykey($value)) {
                    $docid = $obju_DashboardInvoice->docid;
                }
            }

            if ($key=="grids" && $docid != "") {

                // foreach ($value[0] as $keys => $values) {
                    if(array_key_exists('lineid',$value[0])){

                        for ($i=0;$i<count($value[0]["lineid"]);$i++){
                             // $test = $value[0]["u_module"][$i];
                            if ($obju_DashboardInvoiceItems->getbysql("docid = '".$docid."' and lineid = '".$value[0]["lineid"][$i]."'")){
                            } else {
                                if ($actionReturn){
                                    $obju_DashboardInvoiceItems->prepareadd();
                                    $obju_DashboardInvoiceItems->docid = $docid;
                                    $obju_DashboardInvoiceItems->lineid = getNextIdByBranch("u_dashboardinvoiceitems",$objConnection);
                                  
                                }
                            }

                                $obju_DashboardInvoiceItems->setudfvalue("u_seqno",$value[0]["u_seqno"][$i]);
                                $obju_DashboardInvoiceItems->setudfvalue("u_module",$value[0]["u_module"][$i]);
                                $obju_DashboardInvoiceItems->setudfvalue("u_quantity",$value[0]["u_quantity"][$i]);
                                $obju_DashboardInvoiceItems->setudfvalue("u_linetotal",intval(str_replace(',', '', $value[0]["u_linetotal"][$i])));

                            if ($obju_DashboardInvoiceItems->rowstat=="N") $actionReturn = $obju_DashboardInvoiceItems->add();
                            else $actionReturn = $obju_DashboardInvoiceItems->update($obju_DashboardInvoiceItems->docid,$obju_DashboardInvoiceItems->lineid,$obju_DashboardInvoiceItems->rcdversion);
                            if (!$actionReturn) break;
                        }
                        
                    } else {
                        // $test = 'false';
                        $actionReturn = $obju_DashboardInvoiceItems->executesql("DELETE FROM u_dashboardinvoiceitems WHERE COMPANY='$company' AND BRANCH='$branch' AND DOCID='$docid'",true);
                        for ($i=0;$i<count($value[0]["u_seqno"]);$i++){
                            $obju_DashboardInvoiceItems->prepareadd();
                            $obju_DashboardInvoiceItems->docid = $docid;
                            $obju_DashboardInvoiceItems->lineid = getNextIdByBranch("u_dashboardinvoiceitems",$objConnection);
                            $obju_DashboardInvoiceItems->setudfvalue("u_seqno",$value[0]["u_seqno"][$i]);
                            $obju_DashboardInvoiceItems->setudfvalue("u_module",$value[0]["u_module"][$i]);
                            $obju_DashboardInvoiceItems->setudfvalue("u_quantity",$value[0]["u_quantity"][$i]);
                            $obju_DashboardInvoiceItems->setudfvalue("u_linetotal",intval(str_replace(',', '', $value[0]["u_linetotal"][$i])));
                            $actionReturn = $obju_DashboardInvoiceItems->add();
                            if (!$actionReturn) break;
                        }
                        
                    }
                    
                // }
            }

        }	

//        if ($actionReturn) $actionReturn = raiseError($appno);
        if ($actionReturn) $result["status"] = $actionReturn;
        // if ($actionReturn) $result["status"] = $test;
        else $result["status"] = $_SESSION["errormessage"];
        array_push($objs["result"],$result);
        echo json_encode($objs); 
        
        if ($actionReturn) $objConnection->commit();
	else $objConnection->rollback();
?>
