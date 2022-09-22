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
        file_put_contents("c:\users\dashboarddataexpense.txt",serialize($obj));
        $errmessage = "";
        $obju_Header = new masterdataschema_br(null,$objConnection,"u_expenses");
			
        foreach($obj['data'][0] as $key => $value) {
            
//                $actionReturn = $obju_BPLAppsOnline->getbykey($data["appno"]);
                if ($key=="grids") {
                        if(array_key_exists('code',$value[0])){
                                for ($i=0;$i<count($value[0]["code"]);$i++){
                                        switch ($value[0]["action"][$i]) {
                                                case 'add':
                                                        if($obju_Header->getbykey($value[0]["code"][$i])){
                                                                $actionReturn = false;
                                                                $errmessage = "Duplicate entry ". $company."-".$branch."-".$value[0]["code"][$i]." for table u_dashboardexpense";
                                                        }
                                                        $obju_Header->prepareadd();
                                                        $obju_Header->code = $value[0]["code"][$i];
                                                        $obju_Header->name = $value[0]["name"][$i];
                                                        $actionReturn = $obju_Header->add();
                                                        break; 
                                                case 'update':
                                                       $obju_Header->getbykey($value[0]["code"][$i]);
                                                       $obju_Header->name = $value[0]["name"][$i];
                                                       $obju_Header->update($obju_Header->code,$obju_Header->rcdversion);
                                                        break; 
                                                case 'delete':
                                                        $code = $value[0]["code"][$i];
                                                        if ($obju_Header->getbykey($value[0]["code"][$i])) {
                                                                 $actionReturn = $obju_Header->executesql("DELETE FROM u_expenses WHERE COMPANY='$company' AND BRANCH='$branch' AND CODE='$code'",true);
                                                        }
                                                        // code...
                                                        break;
                                                default:
                                                        // code...
                                                        break;
                                        }
                                }
                        }
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
