<?php 
	include_once("../Addons/GPS/HIS Add-On/Userprograms/utils/u_hissetup.php");
function getPricelistsGPSHIS($bptype='') {
	global $objConnection;
	global $objGridPrices;
	global $objMaster;
	
	$u_hissetupdata = getu_hissetup("U_SCDISCPRICELIST,U_SCDISCPERC");
	
	$objItemPriceLists = new itempricelists(null,$objConnection);
	$objRs = new recordset(null,$objConnection);
	$objGridPrices->clear();
	
	if ($bptype=="") $bptypeExp = "BPTYPE IN ('C','S')";
	else  $bptypeExp = "BPTYPE = '$bptype'";
	
	$objRs->queryopen("SELECT PRICELIST, PRICELISTNAME, BASEPRICELIST, CURRENCY, BPTYPE, RCDVERSION FROM PRICELISTS WHERE $bptypeExp ORDER BY U_SEQ, PRICELISTNAME");
	while ($objRs->queryfetchrow("NAME")) {
		$objGridPrices->addrow();
		$objGridPrices->setrowstatus(null,"E");
		$objGridPrices->setitem(null,"pricelist",$objRs->fields["PRICELIST"]);
		$objGridPrices->setitem(null,"pricelistname",$objRs->fields["PRICELISTNAME"]);
		$objGridPrices->setitem(null,"currency",$objRs->fields["CURRENCY"]);
		$objGridPrices->setitem(null,"scdiscperc",0);
		$objGridPrices->setitem(null,"scdiscpricelist","");
		$objGridPrices->setitem(null,"bptype",$objRs->fields["BPTYPE"]);
		if ($u_hissetupdata["U_SCDISCPRICELIST"]==$objRs->fields["PRICELIST"]) {
			$objGridPrices->setitem(null,"scdiscperc",$u_hissetupdata["U_SCDISCPERC"]/100);
			$objGridPrices->setitem(null,"scdiscpricelist",$objRs->fields["PRICELIST"]);
		}
		if ($objRs->fields["PRICELIST"]!=$objRs->fields["BASEPRICELIST"] && $objRs->fields["BASEPRICELIST"]!="") {
			$objGridPrices->setitem(null,"factored",1);
		} else $objGridPrices->setitem(null,"factored",0);
		
		if ($objItemPriceLists->getbykey($objMaster->code, $objRs->fields["PRICELIST"],0)) {
			$objGridPrices->setitem(null,"price",formatNumericPrice($objItemPriceLists->price));
		} else $objGridPrices->setitem(null,"price",formatNumericPrice(0));
	}

}

function updatePricelistsGPSHIS($delete=false) {
	global $objConnection;
	global $page;
	global $objMaster;
	global $objGridPrices;

	$objItemPriceLists = new itempricelists(null,$objConnection);
	$objItems = new items(null,$objConnection);

	$actionReturn = true;
	if ($objItems->getbykey($objMaster->code)) {
		for ($i = 1; $i <= $objGridPrices->recordcount; $i++) {
			if ($objGridPrices->getrowstatus($i)=="U" || $delete) {
				if ($objGridPrices->getitemdecimal($i,"price") > 0) {
					if ($delete) {
						if ($objItemPriceLists->getbykey($objItems->itemcode,$objGridPrices->getitemstring($i,"pricelist"),0)) {
							$objItemPriceLists->privatedata["header"] = $objItems;
							$actionReturn = $objItemPriceLists->delete();
						}
					} else {
						if (!$objItemPriceLists->getbykey($objItems->itemcode,$objGridPrices->getitemstring($i,"pricelist"),0)) {
							$objItemPriceLists->prepareadd();
							$objItemPriceLists->itemcode = $objItems->itemcode;
							$objItemPriceLists->pricelist = $objGridPrices->getitemstring($i,"pricelist");
							$objItemPriceLists->currency = $objGridPrices->getitemstring($i,"currency");
							$objItemPriceLists->price = $objGridPrices->getitemdecimal($i,"price");
							$objItemPriceLists->privatedata["header"] = $objItems;
							$actionReturn = $objItemPriceLists->add();
						} else {
							$objItemPriceLists->price = $objGridPrices->getitemdecimal($i,"price");
							$objItemPriceLists->privatedata["header"] = $objItems;
							$actionReturn = $objItemPriceLists->update($objItemPriceLists->itemcode,$objItemPriceLists->pricelist,$objItemPriceLists->rcdversion);
						}					
					}	
				} else {
					if ($objItemPriceLists->getbykey($objItems->itemcode,$objGridPrices->getitemstring($i,"pricelist"),0)) {
						$objItemPriceLists->privatedata["header"] = $objItems;
						$actionReturn = $objItemPriceLists->delete();
					}
				}
			}	
			if (!$actionReturn) break;
		}
	} else $actionReturn = raiseError("Unable to find Item Master record.");
	return $actionReturn;
}

$objGridPrices = new grid("T110",$httpVars,true);
$objGridPrices->addcolumn("pricelist");
$objGridPrices->addcolumn("pricelistname");
$objGridPrices->addcolumn("currency");
$objGridPrices->addcolumn(createSchemaPrice("price"));
$objGridPrices->addcolumn("factored");
$objGridPrices->addcolumn("scdiscperc");
$objGridPrices->addcolumn("scdiscpricelist");
$objGridPrices->addcolumn("bptype");
$objGridPrices->columntitle("code","Code");
$objGridPrices->columntitle("pricelistname","Price Lists");
$objGridPrices->columntitle("price","Price");
$objGridPrices->columnwidth("pricelistname",30);
$objGridPrices->columnwidth("price",15);
$objGridPrices->columnalignment("price","right");
$objGridPrices->columninput("price","type","text");
$objGridPrices->columnvisibility("pricelist",false);
$objGridPrices->columnvisibility("currency",false);
$objGridPrices->columnvisibility("factored",false);
$objGridPrices->columnvisibility("scdiscperc",false);
$objGridPrices->columnvisibility("scdiscpricelist",false);
$objGridPrices->columnvisibility("bptype",false);
$objGridPrices->dataentry = false;
$objGridPrices->width = 400;

	
?>