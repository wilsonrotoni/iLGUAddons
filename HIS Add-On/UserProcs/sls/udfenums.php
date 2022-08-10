<?php
	function loadudfenumsdataGPSHIS($tablename,$fieldname,$objrs,&$include) {
		global $objConnection;
		switch($tablename) {
			case "u_hispos":
				switch($fieldname) {
					case "payreftype":
						$objRs = new recordset(null,$objConnection);
						$objRs->queryopen("select u_pharmapos from u_hissetup");
						if ($objRs->queryfetchrow()) {
							if ($objRs->fields[0]==1) {
								if ($objrs->fields[0]=="MEDSUP") $include=false;
							}
						}
						break;
				}
				break;
		}
	}
	
?>
