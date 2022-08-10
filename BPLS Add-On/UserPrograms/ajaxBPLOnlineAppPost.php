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
//        file_put_contents("c:\users\data.txt",serialize($obj));
        
        $obju_BPLAppsOnline = new documentschema_br(null,$objConnection,"u_bplappsonline");
			
        foreach($obj['data'][0] as $data) {
            
//                $actionReturn = $obju_BPLAppsOnline->getbykey($data["appno"]);
                
                if ($obju_BPLAppsOnline->getbykey($data["appno"])) {
                } else {
                        $obju_BPLAppsOnline->prepareadd();
                        $obju_BPLAppsOnline->docno = $data["appno"];
                        $obju_BPLAppsOnline->docid = getNextIdByBranch("u_bplappsonline",$objConnection);
                        $obju_BPLAppsOnline->docseries = -1;
                        $obju_BPLAppsOnline->docstatus = "O";
                }
                
//                if ($data["regdate"]!="")  $obju_BPLAppsOnline->setudfvalue("u_regdate",formatDateToDB($data["regdate"]));
//                else  $obju_BPLAppsOnline->setudfvalue("u_regdate","");
                
                $obju_BPLAppsOnline->setudfvalue("u_appdate",$data["appdate"]);
                $obju_BPLAppsOnline->setudfvalue("u_regdate",$data["regdate"]);
                $obju_BPLAppsOnline->setudfvalue("u_orgtype",$data["orgtype"]);
                $obju_BPLAppsOnline->setudfvalue("u_paymode",$data["paymode"]);
                $obju_BPLAppsOnline->setudfvalue("u_lastname",$data["lastname"]);
                $obju_BPLAppsOnline->setudfvalue("u_firstname",$data["firstname"]);
                $obju_BPLAppsOnline->setudfvalue("u_middlename",$data["middlename"]);
                $obju_BPLAppsOnline->setudfvalue("u_owneraddress",$data["owneraddress"]);
                $obju_BPLAppsOnline->setudfvalue("u_telno",$data["telno"]);
                $obju_BPLAppsOnline->setudfvalue("u_email",$data["email"]);
                $obju_BPLAppsOnline->setudfvalue("u_regno",$data["regno"]);
                $obju_BPLAppsOnline->setudfvalue("u_ctcno",$data["ctcno"]);
                $obju_BPLAppsOnline->setudfvalue("u_tin",$data["tin"]);
                $obju_BPLAppsOnline->setudfvalue("u_incentive",$data["incentive"]);
                $obju_BPLAppsOnline->setudfvalue("u_incentiveentity",$data["incentiveentity"]);
                $obju_BPLAppsOnline->setudfvalue("u_tlastname",$data["tlastname"]);
                $obju_BPLAppsOnline->setudfvalue("u_tfirstname",$data["tfirstname"]);
                $obju_BPLAppsOnline->setudfvalue("u_tmiddlename",$data["tmiddlename"]);
                $obju_BPLAppsOnline->setudfvalue("u_corpname",$data["corpname"]);
                $obju_BPLAppsOnline->setudfvalue("u_tradename",$data["tradename"]);
                
                $obju_BPLAppsOnline->setudfvalue("u_baddress",$data["baddress"]);
                $obju_BPLAppsOnline->setudfvalue("u_baddressno",$data["baddressno"]);
                $obju_BPLAppsOnline->setudfvalue("u_bblock",$data["bblock"]);
                $obju_BPLAppsOnline->setudfvalue("u_blotno",$data["blotno"]);
                $obju_BPLAppsOnline->setudfvalue("u_bstreet",$data["bstreet"]);
                $obju_BPLAppsOnline->setudfvalue("u_bvillage",$data["bvillage"]);
                $obju_BPLAppsOnline->setudfvalue("u_bbldgno",$data["bbldgno"]);
                $obju_BPLAppsOnline->setudfvalue("u_bbldgname",$data["bbldgname"]);
                $obju_BPLAppsOnline->setudfvalue("u_bunitno",$data["bunitno"]);
                $obju_BPLAppsOnline->setudfvalue("u_bfloorno",$data["bfloorno"]);
                $obju_BPLAppsOnline->setudfvalue("u_bbrgy",$data["bbrgy"]);
                $obju_BPLAppsOnline->setudfvalue("u_bcity",$data["bcity"]);
                $obju_BPLAppsOnline->setudfvalue("u_bprovince",$data["bprovince"]);
                $obju_BPLAppsOnline->setudfvalue("u_btelno",$data["btelno"]);
                $obju_BPLAppsOnline->setudfvalue("u_bemail",$data["bemail"]);
                $obju_BPLAppsOnline->setudfvalue("u_pin",$data["pin"]);
                $obju_BPLAppsOnline->setudfvalue("u_businessarea",$data["businessarea"]);
                $obju_BPLAppsOnline->setudfvalue("u_empcount",$data["empcount"]);
                $obju_BPLAppsOnline->setudfvalue("u_emplgucount",$data["emplgucount"]);
                
                $obju_BPLAppsOnline->setudfvalue("u_llastname",$data["llastname"]);
                $obju_BPLAppsOnline->setudfvalue("u_lfirstname",$data["lfirstname"]);
                $obju_BPLAppsOnline->setudfvalue("u_lmiddlename",$data["lmiddlename"]);
                $obju_BPLAppsOnline->setudfvalue("u_lessoraddress",$data["lessoraddress"]);
                $obju_BPLAppsOnline->setudfvalue("u_monthlyrental",$data["monthlyrental"]);
                $obju_BPLAppsOnline->setudfvalue("u_ltelno",$data["ltelno"]);
                $obju_BPLAppsOnline->setudfvalue("u_lemail",$data["lemail"]);
                $obju_BPLAppsOnline->setudfvalue("u_apptype",$data["apptype"]);
                $obju_BPLAppsOnline->setudfvalue("u_year",$data["year"]);
                $obju_BPLAppsOnline->setudfvalue("u_remarks",$data["remarks"]);
                
                if ($obju_BPLAppsOnline->rowstat=="N") $actionReturn = $obju_BPLAppsOnline->add();
                else $actionReturn = $obju_BPLAppsOnline->update($obju_BPLAppsOnline->docno,$obju_BPLAppsOnline->rcdversion);
                if (!$actionReturn) break;
        }

//        if ($actionReturn) $actionReturn = raiseError($obj['data'][0]);
        if ($actionReturn) $result["status"] = $actionReturn;
        else $result["status"] = $_SESSION["errormessage"];
        
        array_push($objs["result"],$result);
        echo json_encode($objs); 
        
        if ($actionReturn) $objConnection->commit();
	else $objConnection->rollback();
?>
