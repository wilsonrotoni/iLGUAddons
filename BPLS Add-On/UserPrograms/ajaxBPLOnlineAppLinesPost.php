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
        file_put_contents("c:\users\data.txt",serialize($obj));
        
        $obju_BPLAppLinesOnline = new documentschema_br(null,$objConnection,"u_bplapplinesonline");
        foreach($obj["data"][0][0] as $key => $data2) {
            if ($key=="appno") {
                 $appno = $data2;
                  while(true){
                        if ($obju_BPLAppLinesOnline->getbysql("u_appno = '".$data2."' and docstatus = 'O'")) {
                            $obju_BPLAppLinesOnline->docstatus = "CN";
                            $actionReturn = $obju_BPLAppLinesOnline->update($obju_BPLAppLinesOnline->docno,$obju_BPLAppLinesOnline->rcdversion);
                        } else {
                            break;
                        }
                        if(!$actionReturn)  break;
                  }
                
            }
             
            if ($key=="businesslines") {
                  foreach($data2 as $businesslineitems) {
                        $obju_BPLAppLinesOnline->prepareadd();
                        $obju_BPLAppLinesOnline->docno = getNextNoByBranch("u_bplapplinesonline","",$objConnection);
                        $obju_BPLAppLinesOnline->docid = getNextIdByBranch("u_bplapplinesonline",$objConnection);
                        $obju_BPLAppLinesOnline->docseries = -1;
                        $obju_BPLAppLinesOnline->docstatus = "O";
                        $obju_BPLAppLinesOnline->setudfvalue("u_appno",$appno);
                        $obju_BPLAppLinesOnline->setudfvalue("u_businessline",$businesslineitems["name"]);
                        $obju_BPLAppLinesOnline->setudfvalue("u_unitcount",$businesslineitems["unitcount"]);
                        $obju_BPLAppLinesOnline->setudfvalue("u_grossamount",$businesslineitems["grossamount"]);
                        $obju_BPLAppLinesOnline->setudfvalue("u_capital",$businesslineitems["capital"]);
                        $actionReturn = $obju_BPLAppLinesOnline->add();

                        if (!$actionReturn) break;
                       
                  }
            }
            
             
        }	

//        if ($actionReturn) $actionReturn = raiseError($appno);
        if ($actionReturn) $result["status"] = $actionReturn;
        else $result["status"] = $_SESSION["errormessage"];
        
        array_push($objs["result"],$result);
        echo json_encode($objs); 
        
        if ($actionReturn) $objConnection->commit();
	else $objConnection->rollback();
?>
