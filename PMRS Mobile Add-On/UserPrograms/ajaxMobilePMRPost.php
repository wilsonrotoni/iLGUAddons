<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./utils/customers.php");
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
	
	/*$objs["pickitems"] = array();
	$result["returnstatus"] = $_GET["type"];
	array_push($objs["pickitems"],$result);
	echo json_encode($objs);		
		*/	
	file_put_contents("c:\users\ajaxMobilePMRPost-".date('Ymd')."-".$_GET["terminalid"]."-".$_SESSION["userid"].".txt",serialize($_GET));
	
	
	switch ($_GET["type"]) {
		case "Upload":
			
			$objs["pickitems"] = array();
			$datas = array();
			$result = array();
			$_SESSION["company"]="LGU";
			$_SESSION["branch"]="MAIN";
			$company = $_SESSION["company"];
			$branch = $_SESSION["branch"];
			
			//$userid = "manager";
			$obj = json_decode($_GET["ambulantcollections"],true);
			 //file_put_contents("c:\users\sales.txt",serialize($obj));
			$actionReturn = true;  
			  
			$objRsItems = new recordset(null,$objConnectiom);  
			$obju_PMRCashTickets = new masterdataschema_br(null,$objConnection,"u_pmrcashtickets");
			$obju_PMRAmbulantVendors = new masterdataschema_br(null,$objConnection,"u_pmrambulantvendors");
			$obju_MobileSales = new documentschema_br(null,$objConnection,"u_pmrambulantcollections");
			$obju_MobileSaleItems = new documentlinesschema_br(null,$objConnection,"u_pmrambulantcollectiontickets");
			
			$obju_MobileSales->prepareadd();
			$obju_MobileSales->docid = getNextIdByBranch("u_pmrambulantcollections",$objConnection);
			$obju_MobileSales->setudfvalue("u_userid",$_SESSION["userid"]);
			$obju_MobileSales->setudfvalue("u_terminalid",$_GET["terminalid"]);
			$totalamount=0;
			foreach($obj['ambulantcollections'] as $data) {
				if (!$obju_PMRAmbulantVendors->getbysql("NAME='".$data["vendorname"]."'")) {
					$obju_PMRAmbulantVendors->prepareadd();
					$obju_PMRAmbulantVendors->code="?";
					$obju_PMRAmbulantVendors->name=$data["vendorname"];
					$actionReturn = $obju_PMRAmbulantVendors->add();
				}
				
				if ($actionReturn) {
					$obju_MobileSales->setudfvalue("u_date",formatDateToDB($data["date"]));
					$obju_MobileSaleItems->prepareadd();
					$obju_MobileSaleItems->docid = $obju_MobileSales->docid;
					$obju_MobileSaleItems->lineid = getNextIdByBranch("u_pmrambulantcollectiontickets",$objConnection);
					$obju_MobileSaleItems->setudfvalue("u_trxno", $data["docno"]);
					$obju_MobileSaleItems->setudfvalue("u_vendorcode", $obju_PMRAmbulantVendors->code);
                                        if ($obju_PMRCashTickets->getbysql("U_DENOMINATION='".$data["unitprice"]."'")) {
                                            $obju_MobileSaleItems->setudfvalue("u_feecode", $obju_PMRCashTickets->code);
                                        }
					$obju_MobileSaleItems->setudfvalue("u_vendorname", $data["vendorname"]);
					$obju_MobileSaleItems->setudfvalue("u_quantity", $data["quantity"]);
					$obju_MobileSaleItems->setudfvalue("u_unitprice", $data["unitprice"]);
					$obju_MobileSaleItems->setudfvalue("u_linetotal", $data["linetotal"]);
					$obju_MobileSaleItems->privatedata["header"] = $obju_MobileSales;
					$actionReturn = $obju_MobileSaleItems->add();
				}	
				if (!$actionReturn) break; 
				$totalamount+=floatval($data["linetotal"]);
			}
			
			if ($actionReturn && $totalamount>0) {
				$obju_MobileSales->setudfvalue("u_totalamount",$totalamount);
				$obju_MobileSales->docseries = getDefaultSeries("u_pmrambulantcollections",$objConnection);
				$obju_MobileSales->docno = getNextSeriesNoByBranch("u_pmrambulantcollections",$obju_MobileSales->docseries,'',$objConnection);
				$obju_MobileSales->docstatus = 'O';
				$actionReturn = $obju_MobileSales->add();
			}
			
			//if ($actionReturn) $actionReturn = raiseError($obju_PMRAmbulantVendors->code);
			if ($actionReturn) $result["returnstatus"] = "Save Successful";
			else $result["returnstatus"] = $_SESSION["errormessage"];
			array_push($objs["pickitems"],$result);
			file_put_contents("c:\users\error.txt",serialize($objs));
			echo json_encode($objs); 
 			break;	
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			if ($actionReturn) echo "Save Successful";
			else echo $_SESSION["errormessage"];
			break;	
	}
	if ($actionReturn) $objConnection->commit();
	else $objConnection->rollback();
	
	
?>
