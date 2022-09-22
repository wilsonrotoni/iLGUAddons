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
        $_SESSION["company"]="LGU";
        $_SESSION["branch"]="MAIN";
        
        array_push($post_data["data"],$_POST["data"]);
        
        $company = $_SESSION["company"];
        $branch = $_SESSION["branch"];
        $arr = get_defined_vars();
        
        $json_encode = json_encode($post_data);
        $obj = json_decode($json_encode,true);
        file_put_contents("c:\users\dashboarddata.txt",serialize($obj));
        $errmessage = "";
        $obju_Header = new documentschema_br(null,$objConnection,"u_dashboardexpense");
			
        foreach($obj['data'][0] as $data) {
            
//                $actionReturn = $obju_BPLAppsOnline->getbykey($data["appno"]);
                
                if ($obju_Header->getbykey($data["docno"])) {
                        if ($data["pageaction"] =="a") {
                            $actionReturn = false;
                            $errmessage = "Duplicate entry ". $company."-".$branch."-".$data["docno"]." for table u_dashboardexpense";
                        } 
                } else {
                        $obju_Header->prepareadd();
                        $obju_Header->docno = $data["docno"];
                        $obju_Header->docid = getNextIdByBranch("u_dashboardexpense",$objConnection);
                        $obju_Header->docseries = -1;
                }
                $obju_Header->docstatus = $data["docstatus"];
                $obju_Header->setudfvalue("u_date",formatDateToDB($data["date"]));
                $obju_Header->setudfvalue("u_datefrm",formatDateToDB($data["datefrm"]));
                $obju_Header->setudfvalue("u_dateto",formatDateToDB($data["dateto"]));
                $obju_Header->setudfvalue("u_totalamount",$data["totalamount"]);
                $obju_Header->setudfvalue("u_remarks",$data["remarks"]);
                
                if ($actionReturn) {
                        if ($obju_Header->rowstat=="N") $actionReturn = $obju_Header->add();
                        else $actionReturn = $obju_Header->update($obju_Header->docno,$obju_Header->rcdversion);
                        if (!$actionReturn) break;
                }
        }

//        if ($actionReturn) $actionReturn = raiseError($obj['data'][0]);
        if ($actionReturn){
                $result["result"]["status"] = "Sucess";
                $result["result"]["message"] = "Operation ended successfuly";
        } else {
                $result["result"]["status"] = "Failed";
                if ($errmessage != "") $result["result"]["message"] = $errmessage;
                else $result["result"]["message"] = $_SESSION["errormessage"];
        }
        
        
        // array_push($objs["result"],$result);
        echo json_encode($result); 
        
        if ($actionReturn) $objConnection->commit();
	else $objConnection->rollback();
?>
