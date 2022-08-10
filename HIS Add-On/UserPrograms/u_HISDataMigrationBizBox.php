F<?php
	set_time_limit(0);
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	$progid = "u_HISDataMigrationBizBox";
//	print_r($httpVars);	
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/classes/grid.php");
	include_once("./series.php");
	include_once("./schema/marketingdocuments.php");
	include_once("./classes/masterdataschema_br.php");
	include_once("./classes/documentschema_br.php");
	include_once("./classes/brancheslist.php");
	include_once("./sls/brancheslist.php");
	include_once("./inc/formutils.php");
	include_once("./utils/marketingdocuments.php");
	include_once("./utils/branches.php");
	include_once("./utils/companies.php");
	include_once("./utils/sbosyncSBOPrc.php");
	include_once("./JimacDataTrxSync.php");
	require_once("./dtw/udos.php");
	require_once("./dtw/suppliers.php");
	require_once("./dtw/purchaserequests.php");
	require_once("./dtw/purchaseorders.php");
	require_once("./dtw/apinvoices.php");
	require_once("./dtw/apcreditmemos.php");
	require_once("./dtw/items.php");
	include_once("./series.php");
	//$progaccessid="companies";
	include_once("./inc/formaccess.php");
	$post = false;
	$validate = false;
	$page->objectcode = $progid;
	$httpVars["df_errormessages"] = "";
	$branchdata = getcurrentbranchdata("MIGRATEDATE");
	
	require_once("./inc/dtw.php");

	include_once("../Addons/GPS/HIS Add-On/UserProcs/DataTransferWorkbench.php");
	reset($_SESSION["addons"]);
	foreach($_SESSION["addons"] as $key => $addon) {
		if ($addon["status"]=="Started") {
			$userprocs = "../AddOns/" . $addon["namespace"] . "/" . $key . "/UserProcs/DataTransferWorkbench.php";
			if (file_exists($userprocs)) include_once($userprocs);
		}
	}	
	
	function parseString($value) {
		return str_replace("(N/A)","",str_replace("NULL","",trim($value)));
	}
	function parseNumeric($value) {
		return intval(str_replace("NULL","",trim($value)));
	}
	function parseFloat($value) {
		return floatval(str_replace("NULL","",trim($value)));
	}
	function parseDate($value) {
		$date = date('Y-m-d',strtotime(trim($value)));
		if ($date=="1970-01-01") $date="";
		return $date;
	}
		
	function onFormAction($action) {
		global $httpVars;
		global $objConnection;
		global $branch;
		global $branchdata;
		global $page;
		
		if ($action!="u_migrate") return true;
		
		$actionReturn = true;
		
		/*
		$migratedate = dateadd("d",-1,formatDateToDB($page->getitemstring("migratedate")));
		
		$docdate_fr = formatDateToDB($page->getitemstring("docdate_fr"));
		$docdate_to = formatDateToDB($page->getitemstring("docdate_to"));
		$type = $page->getitemstring("type");
		$dsn = $page->getitemstring("dsn");
		$dbfpath = $page->getitemstring("dbfpath");
		*/
		$objDTWu_HISPatients = new dtw_udos("hispatients");
		$objDTWu_HISIPs = new dtw_udos("hisips");
		$objDTWu_HISMedRecs = new dtw_udos("hismedrecs");
		$objDTWu_HISConsultancyFees = new dtw_udos("hisconsultancyfees");
		$objDTWu_HISItems = new dtw_udos("hisitems");
		$objDTWSuppliers = new dtw_suppliers();
		$objDTWPurchaseRequests = new dtw_purchaserequests();
		$objDTWPurchaseOrders = new dtw_purchaseorders();
		$objDTWAPInvoices = new dtw_apinvoices();
		$objDTWAPCreditMemos = new dtw_apcreditmemos();
		$objDTWItems = new dtw_items();
		$objRs = new recordset(null,$objConnection);
		$objRs2 = new recordset(null,$objConnection);
		//$objDTWu_Payments = new dtw_udos("payments");
		
		$objConnection->beginwork();
		try {
			$objADO = new COM("ADODB.Connection");
			if ($page->getitemstring("dbuser")!="") {
				$objADO->Open("Provider=SQLOLEDB; Data Source=".$page->getitemstring("dbserver").";User ID=".$page->getitemstring("dbuser").";Password=".$page->getitemstring("dbpwd").";Initial Catalog=".$page->getitemstring("dbname").";Secure=0");
			} else {
				$objADO->Open("Provider=SQLOLEDB; Data Source=".$page->getitemstring("dbserver").";Initial Catalog=".$page->getitemstring("dbname").";Trusted_Connection=yes;Secure=0");
			}
			//$rs = $objADO->Execute($sql);
			/*			
			if ($type=="OB") {
				*/
				
			if ($actionReturn && $page->getitemstring("patient")=="1") {
				$data = array();
				$data["Patients"] = array();
				array_push($data["Patients"],array("Code","Name","U_FIRSTNAME","U_LASTNAME","U_MIDDLENAME","U_STREET","U_BARANGAY","U_CITY","U_ZIP","U_PROVINCE","U_ADDRESS","U_TELNO","U_SSSNO","U_TINNO","U_BIRTHTIME","U_NEWBORNSTAT","U_EXPIREDATE","U_EXPIRED","U_EXTNAME","U_OCCUPATION","U_BIRTHDATE","U_BIRTHPLACE","U_GENDER","U_NATIONALITY","U_CIVILSTATUS","U_RELIGION","U_EMPLOYERNAME","U_EMPLOYERSTREET","U_EMPLOYERBARANGAY","U_EMPLOYERCITY","U_EMPLOYERZIP","U_EMPLOYERPROVINCE","U_EMPLOYERADDRESS","U_EMPLOYERTELNO","U_FATHERNAME","U_FATHERSTREET","U_FATHERBARANGAY","U_FATHERCITY","U_FATHERZIP","U_FATHERPROVINCE","U_FATHERADDRESS","U_FATHERTELNO","U_MOTHERNAME","U_MOTHERSTREET","U_MOTHERBARANGAY","U_MOTHERCITY","U_MOTHERZIP","U_MOTHERPROVINCE","U_MOTHERADDRESS","U_MOTHERTELNO","U_SPOUSENAME","U_SPOUSESTREET","U_SPOUSEBARANGAY","U_SPOUSECITY","U_SPOUSEZIP","U_SPOUSEPROVINCE","U_SPOUSEADDRESS","U_SPOUSETELNO","U_RESPONSIBLENAME","U_RESPONSIBLESTREET","U_RESPONSIBLEBARANGAY","U_RESPONSIBLECITY","U_RESPONSIBLEZIP","U_RESPONSIBLEPROVINCE","U_RESPONSIBLEADDRESS","U_RESPONSIBLERELATIONSHIP","U_RESPONSIBLETELNO","U_CONTACTNAME","U_CONTACTSTREET","U_CONTACTBARANGAY","U_CONTACTCITY","U_CONTACTZIP","U_CONTACTPROVINCE","U_CONTACTADDRESS","U_CONTACTRELATIONSHIP","U_CONTACTTELNO","U_CONTACTEMPLOYER"));

				$rs = $objADO->Execute("select d.street, d.barangay, d.towncity, d.zip, d.province,d.address, d.telephone, 
		e.street as employerstreet, e.barangay as employerbarangay,
		e.towncity as employertowncity, e.zip as employerzip, e.province as employerprovince,
		e.address as employeraddress, e.telephone as employertelephone, a.abbrev, a.fullname, a.fname, a.lname, a.mname, a.sssgsisno, a.tin, b.birthtime, a.expiredflag, b.occupation, a.deathdate, a.birthdate, b.bplace, b.sex, b.nationality, b.civilstatus, b.religion, b.employername, b.fathername, b.fastreet, b.fabarangay, b.fatowncity, b.fazipcode, b.faprovince, b.faaddress, b.fatelephone, b.mothername, b.mostreet, b.mobarangay, b.motowncity, b.mozipcode, b.moprovince, b.moaddress, b.motelephone, b.spname, b.spstreet, b.spbarangay, b.sptowncity, b.spzipcode, b.spprovince, b.spaddress, b.sptelephone, b.pnname, b.pnstreet, b.pnbarangay, b.pntowncity, b.pnzipcode, b.pnprovince, b.pnaddress, b.pnrelation, b.pntelephone, b.icname, b.icstreet, b.icbarangay, b.ictowncity, b.iczipcode, b.icprovince,b.icaddress, b.icrelation, b.ictelephone from datacenter a inner join patient b on b.dcno=a.dcno and ldoctor=0 and lvendor=0 and birthdate is not null and b.regdate is not null 
		left join addrmstr d on d.addrcode=a.homecode 
		left join addrmstr e on e.addrcode=a.offccode 
order by a.abbrev");
				while (!$rs->EOF) {
					$rowdata = array();
					
					array_push($rowdata,parseString($rs->Fields["abbrev"]->Value));
					array_push($rowdata,parseString($rs->Fields["fullname"]->Value));
					array_push($rowdata,parseString($rs->Fields["fname"]->Value));
					array_push($rowdata,parseString($rs->Fields["lname"]->Value));
					array_push($rowdata,parseString($rs->Fields["mname"]->Value));
					array_push($rowdata,parseString($rs->Fields["street"]->Value));
					array_push($rowdata,parseString($rs->Fields["barangay"]->Value));
					array_push($rowdata,parseString($rs->Fields["towncity"]->Value));
					array_push($rowdata,parseString($rs->Fields["zip"]->Value));
					array_push($rowdata,parseString($rs->Fields["province"]->Value));
					array_push($rowdata,parseString($rs->Fields["address"]->Value));
					array_push($rowdata,parseString($rs->Fields["telephone"]->Value));
					array_push($rowdata,parseString($rs->Fields["sssgsisno"]->Value));
					array_push($rowdata,parseString($rs->Fields["tin"]->Value));
					if(strtotime($rs->Fields["birthtime"]->Value)) {
						array_push($rowdata,date('H:i:s',strtotime($rs->Fields["birthtime"]->Value)));
						array_push($rowdata,1);
					} else {
						array_push($rowdata,"");
						array_push($rowdata,0);
					}	
					if($rs->Fields["expiredflag"]->Value==1) {
						array_push($rowdata,parseDate($rs->Fields["deathdate"]->Value));
						array_push($rowdata,1);
					} else {
						array_push($rowdata,"");
						array_push($rowdata,0);
					}
					array_push($rowdata,"");
					array_push($rowdata,parseString($rs->Fields["occupation"]->Value));
					if(strtotime($rs->Fields["birthdate"]->Value)) {
						array_push($rowdata,parseDate($rs->Fields["birthdate"]->Value));
					} else {
						array_push($rowdata,"");
					}	
					
					array_push($rowdata,parseString($rs->Fields["bplace"]->Value));
					array_push($rowdata,parseString($rs->Fields["sex"]->Value));
					array_push($rowdata,parseString($rs->Fields["nationality"]->Value));
					array_push($rowdata,parseString($rs->Fields["civilstatus"]->Value));
					array_push($rowdata,parseString($rs->Fields["religion"]->Value));
					array_push($rowdata,parseString($rs->Fields["employername"]->Value));
					array_push($rowdata,parseString($rs->Fields["employerstreet"]->Value));
					array_push($rowdata,parseString($rs->Fields["employerbarangay"]->Value));
					array_push($rowdata,parseString($rs->Fields["employertowncity"]->Value));
					array_push($rowdata,parseString($rs->Fields["employerzip"]->Value));
					array_push($rowdata,parseString($rs->Fields["employerprovince"]->Value));
					array_push($rowdata,parseString($rs->Fields["employeraddress"]->Value));
					array_push($rowdata,parseString($rs->Fields["employertelephone"]->Value));
					array_push($rowdata,parseString($rs->Fields["fathername"]->Value));
					array_push($rowdata,parseString($rs->Fields["fastreet"]->Value));
					array_push($rowdata,parseString($rs->Fields["fabarangay"]->Value));
					array_push($rowdata,parseString($rs->Fields["fatowncity"]->Value));
					array_push($rowdata,parseString($rs->Fields["fazipcode"]->Value));
					array_push($rowdata,parseString($rs->Fields["faprovince"]->Value));
					array_push($rowdata,parseString($rs->Fields["faaddress"]->Value));
					array_push($rowdata,parseString($rs->Fields["fatelephone"]->Value));
					array_push($rowdata,parseString($rs->Fields["mothername"]->Value));
					array_push($rowdata,parseString($rs->Fields["mostreet"]->Value));
					array_push($rowdata,parseString($rs->Fields["mobarangay"]->Value));
					array_push($rowdata,parseString($rs->Fields["motowncity"]->Value));
					array_push($rowdata,parseString($rs->Fields["mozipcode"]->Value));
					array_push($rowdata,parseString($rs->Fields["moprovince"]->Value));
					array_push($rowdata,parseString($rs->Fields["moaddress"]->Value));
					array_push($rowdata,parseString($rs->Fields["motelephone"]->Value));
					array_push($rowdata,parseString($rs->Fields["spname"]->Value));
					array_push($rowdata,parseString($rs->Fields["spstreet"]->Value));
					array_push($rowdata,parseString($rs->Fields["spbarangay"]->Value));
					array_push($rowdata,parseString($rs->Fields["sptowncity"]->Value));
					array_push($rowdata,parseString($rs->Fields["spzipcode"]->Value));
					array_push($rowdata,parseString($rs->Fields["spprovince"]->Value));
					array_push($rowdata,parseString($rs->Fields["spaddress"]->Value));
					array_push($rowdata,parseString($rs->Fields["sptelephone"]->Value));
					array_push($rowdata,parseString($rs->Fields["pnname"]->Value));
					array_push($rowdata,parseString($rs->Fields["pnstreet"]->Value));
					array_push($rowdata,parseString($rs->Fields["pnbarangay"]->Value));
					array_push($rowdata,parseString($rs->Fields["pntowncity"]->Value));
					array_push($rowdata,parseString($rs->Fields["pnzipcode"]->Value));
					array_push($rowdata,parseString($rs->Fields["pnprovince"]->Value));
					array_push($rowdata,parseString($rs->Fields["pnaddress"]->Value));
					array_push($rowdata,parseString($rs->Fields["pnrelation"]->Value));
					array_push($rowdata,substr(parseString($rs->Fields["pntelephone"]->Value),0,45));
					array_push($rowdata,parseString($rs->Fields["icname"]->Value));
					array_push($rowdata,parseString($rs->Fields["icstreet"]->Value));
					array_push($rowdata,parseString($rs->Fields["icbarangay"]->Value));
					array_push($rowdata,parseString($rs->Fields["ictowncity"]->Value));
					array_push($rowdata,parseString($rs->Fields["iczipcode"]->Value));
					array_push($rowdata,parseString($rs->Fields["province"]->Value));
					array_push($rowdata,parseString($rs->Fields["icaddress"]->Value));
					array_push($rowdata,parseString($rs->Fields["icrelation"]->Value));
					array_push($rowdata,parseString($rs->Fields["ictelephone"]->Value));
					array_push($rowdata,"");
					
					array_push($data["Patients"],$rowdata);
					unset($rowdata);
					
					$rs->MoveNext();
					//break;
				}
				//var_dump($data["Patients"]);
				$actionReturn = $objDTWu_HISPatients->upload($data,false);
				unset($data);
			}
			
			if ($actionReturn && $page->getitemstring("ip")=="1") {
				$data = array();
				$data["In-Patients"] = array();
				$data["Rooms"] = array();
				$data["Health Insurance"] = array();
				array_push($data["In-Patients"],array("Document No.","Document Status","U_NURSED","U_STARTDATE","U_STARTTIME","U_PATIENTNAME","U_PATIENTID","U_INFORMANTNAME","U_INFORMANTADDRESS","U_INFORMANTRELATIONSHIP","U_PAYMENTTERM","U_MOTHERPATIENTNAME","U_MOTHERPATIENTID","U_MOTHERDOCNO","U_CASETYPE","U_ARRIVEDBY","U_COMPLAINT","U_IMPRESSION","U_HEIGHT_FT","U_HEIGHT_IN","U_HEIGHT_CM","U_WEIGHT_LB","U_WEIGHT_KG","U_DIETTYPE","U_DIETTYPEDESC","U_DIETREMARKS","U_ALLERGY1","U_ALLERGY2","U_ALLERGY3","U_ALLERGY4","U_ALLERGY5","U_ALLERGY6","U_ALLERGY7","U_TYPEOFDISCHARGE","U_ROOMDESC","U_ADMITRESULT","U_DOCTORID","U_DOCTORSERVICE","U_EXPIREHOURS","U_RESULTAUTOPSIED","U_ADMITTEDBY","U_DISCHARGED","U_ENDDATE","U_ENDTIME","U_DISCHARGEDBY","U_BEDNO","U_ROOMPRICE","U_DEPARTMENT","U_MGH","U_MGHDATE","U_MGHTIME","U_MGHBY","U_UNTAGMGHREMARKS","U_BILLCOUNT","U_BILLDATETIME","U_BILLBY","U_BILLREMARKS","U_REMARKS","U_TREATMENT","U_VISITNO","U_NEWBORNSTAT","U_PREVDIETTYPE","U_PREVDIETTYPEDESC","U_TYPEOFEXPIRE","U_TYPEOFBIRTH","U_CONFIDENTIAL","U_SUSPENDBY","U_SUSPENDTIME","U_SUSPENDDATE","U_SUSPENDREMARKS","U_ORREMARKS","U_ORPROC","U_ALLERGYTYPE","U_DISCASSIGNBY","U_DISCASSIGNDATE","U_DISCTYPE","U_NOOFDAYS","U_EXPIREDATE","U_EXPIRED","U_OLDPATIENT","U_GENDER","U_RELIGION"));
				array_push($data["Rooms"],array("Document No.","U_BEDNO","U_ROOMDESC","U_RATE","U_QUANTITY","U_AMOUNT","U_STARTDATE","U_STARTTIME","U_ENDDATE","U_ENDTIME"));
				array_push($data["Health Insurance"],array("Document No.","U_INSCODE","U_HMO","U_MEMBERTYPE"));
				//array_push($data["In-Patients"],array("Document No.","Document Status","U_NURSED","U_STARTDATE","U_STARTTIME","U_PATIENTNAME","U_PATIENTID","U_INFORMANTNAME","U_INFORMANTADDRESS","U_INFORMANTRELATIONSHIP","U_PAYMENTTERM","U_MOTHERPATIENTNAME","U_MOTHERPATIENTID","U_MOTHERDOCNO","U_CASETYPE","U_ARRIVEDBY","U_COMPLAINT","U_IMPRESSION","U_HEIGHT_FT","U_HEIGHT_IN","U_HEIGHT_CM","U_WEIGHT_LB","U_WEIGHT_KG","U_DIETTYPE","U_DIETTYPEDESC","U_DIETREMARKS","U_ALLERGY1","U_ALLERGY2","U_ALLERGY3","U_ALLERGY4","U_ALLERGY5","U_ALLERGY6","U_ALLERGY7","U_ADMITDISPOSITION","U_ROOMDESC","U_ADMITRESULT","U_DOCTORID","U_DOCTORSERVICE","U_FINALDOCTORID","U_FINALDOCTORSERVICE","U_EXPIREHOURS","U_RESULTAUTOPSIED","U_ADMITTEDBY","U_DISCHARGED","U_ENDDATE","U_ENDTIME","U_DISCHARGEDBY","U_BEDNO","U_ROOMPRICE","U_DEPARTMENT","U_MGH","U_MGHDATE","U_MGHTIME","U_MGHBY","U_UNTAGMGHREMARKS","U_BILLCOUNT","U_BILLDATETIME","U_BILLBY","U_BILLREMARKS","U_REMARKS","U_FINALREMARKS","U_ICDCODE","U_ICDDESC","U_TREATMENT","U_VISITNO","U_NEWBORNSTAT","U_PREVDIETTYPE","U_PREVDIETTYPEDESC","U_TYPEOFEXPIRE","U_TYPEOFBIRTH","U_CONFIDENTIAL","U_SUSPENDBY","U_SUSPENDTIME","U_SUSPENDDATE","U_SUSPENDREMARKS","U_ORREMARKS","U_ORPROC","U_ALLERGYTYPE","U_DISCASSIGNBY","U_DISCASSIGNDATE","U_DISCTYPE","U_NOOFDAYS","U_EXPIREDATE","U_EXPIRED","U_OLDPATIENT","U_GENDER","U_RELIGION"));
				$data2["Medical Records"] = array();
				array_push($data2["Medical Records"],array("Document No.","Document Series","Document Status","U_DOCDATE","U_REFTYPE","U_REFNO","U_PATIENTID","U_PATIENTNAME","U_DOCTORID","U_DOCTORSERVICE","U_REMARKS","U_ICDCODE","U_ICDDESC"));
				
				$rs = $objADO->Execute("select c.admno, c.discharge, c.admdate, c.admtime, a.fullname, a.abbrev, c.informname, c.informaddr, c.informrelation, c.hospplan, c.phicclass, c.casetype, c.howadmit, c.chiefcomplaint, c.admimpression, c.heightunit, c.height, c.weightunit, c.weight, c.dietcode, c.dietdesc, c.dietremarks, c.allergy1, c.allergy2, c.allergy3, c.allergy4, c.allergy5, c.allergy6, c.allergy7, c.admdisposition, c.rmclass, c.admresult, c.servicetype, c.deathhours, c.deathautopsy, c.admuser, c.dischdate, c.dischtime, c.dischuser, c.rmno, c.rmprice, c.nrstatcode, c.mgh, c.mghdate, c.mghtime, c.mghuser, c.untagmghrem, c.billprintcount, c.billprintdate, c.billremarks, c.admindiagnosis, c.dischdiagnosis, c.finaldiagcode, c.finaldiagnosis, c.treatment, c.admcounter, c.newbornstat, c.prevdietcode, c.prevdietdesc, c.typeofdeath, c.typeofbirth, c.confidential, c.suspendby, c.suspendtime, c.suspenddate, c.suspendremarks, c.ordiagnosis, c.allergytype, a.discassignuser, a.discassigndt, a.disctype, a.noofdays, a.deathdate, a.expiredflag, b.sex, b.religion, d.dctrdcno, d.dctrname from admission c inner join datacenter a on c.dcno=a.dcno inner join patient b on b.dcno=a.dcno and ldoctor=0 and lvendor=0 and birthdate is not null and b.regdate is not null left join admdctrledger d on d.pattranno=c.pattranno order by c.keyno
");
				$admno="";
				while (!$rs->EOF) {
					if ($admno==parseString($rs->Fields["admno"]->Value)) {
						$rs->MoveNext();
						continue;
					}
					
					$admno=parseString($rs->Fields["admno"]->Value);
					
					$rowdata = array();
					
					array_push($rowdata,parseString($rs->Fields["admno"]->Value));
					if (parseNumeric($rs->Fields["discharge"]->Value)==1) {
						array_push($rowdata,"Discharged");
					} else {
						array_push($rowdata,"Active");
					}	
					array_push($rowdata,1);
				
					array_push($rowdata,parseDate($rs->Fields["admdate"]->Value));
					array_push($rowdata,date('H:i:s',strtotime($rs->Fields["admtime"]->Value)));
					array_push($rowdata,parseString($rs->Fields["fullname"]->Value));
					array_push($rowdata,parseString($rs->Fields["abbrev"]->Value));
					array_push($rowdata,parseString($rs->Fields["informname"]->Value));
					array_push($rowdata,parseString($rs->Fields["informaddr"]->Value));
					array_push($rowdata,parseString($rs->Fields["informrelation"]->Value));
					array_push($rowdata,parseString($rs->Fields["hospplan"]->Value));
					array_push($rowdata,"");
					array_push($rowdata,"");
					array_push($rowdata,"");
					array_push($rowdata,parseString($rs->Fields["casetype"]->Value));
					array_push($rowdata,parseString($rs->Fields["howadmit"]->Value));
					array_push($rowdata,parseString($rs->Fields["chiefcomplaint"]->Value));
					array_push($rowdata,parseString($rs->Fields["admimpression"]->Value));
					switch (parseString($rs->Fields["heightunit"]->Value)) {
						case "C":
							$height_cm = parseFloat($rs->Fields["height"]->Value);
							$height_ft=intval(objutils::divide($height_cm,30.48));
							$height_in=intval(objutils::divide($height_cm % 30.48,2.54));
							array_push($rowdata,$height_ft);
							array_push($rowdata,$height_in);
							array_push($rowdata,$height_cm);
							//$page->console->insertVar(array($height_cm,$height_ft,$height_in,$height_cm));
							break;
						case "F":
							$height_ft=0;
							$height_in=0;
							$tmp = parseString($rs->Fields["height"]->Value);
							$pos1 = strpos($tmp, "'");
							if (!($pos1 === false)) {
								$tmp1 = explode("'",$tmp);
								$height_ft=floatval($tmp1[0]);
								$tmp1 = explode('"',$tmp1[1]);
								$height_in=floatval($tmp1[0]);
							} else {
								$tmp1 = explode('"',$tmp);
								$height_in=floatval($tmp1[0]);
							}
							$height_cm = (($height_ft*12)+$height_in)*2.54;
							array_push($rowdata,$height_ft);
							array_push($rowdata,$height_in);
							array_push($rowdata,$height_cm);
							//$page->console->insertVar(array(parseString($rs->Fields["height"]->Value),$height_ft,$height_in,$height_cm));
							break;
						default:
							array_push($rowdata,0);
							array_push($rowdata,0);
							array_push($rowdata,0);
							break;
					}
					switch (parseString($rs->Fields["weightunit"]->Value)) {
						case "L":
							array_push($rowdata,parseFloat($rs->Fields["weight"]->Value));
							array_push($rowdata,parseFloat($rs->Fields["weight"]->Value)/2.20462);
							break;
						case "K":
							array_push($rowdata,parseFloat($rs->Fields["weight"]->Value)*2.20462);
							array_push($rowdata,parseFloat($rs->Fields["weight"]->Value));
							break;
						default:
							array_push($rowdata,0);
							array_push($rowdata,0);
							break;
					}
					
					array_push($rowdata,parseString($rs->Fields["dietcode"]->Value));
					array_push($rowdata,parseString($rs->Fields["dietdesc"]->Value));
					array_push($rowdata,substr(parseString($rs->Fields["dietremarks"]->Value),0,254));
					array_push($rowdata,parseString($rs->Fields["allergy1"]->Value));
					array_push($rowdata,parseString($rs->Fields["allergy2"]->Value));
					array_push($rowdata,parseString($rs->Fields["allergy3"]->Value));
					array_push($rowdata,parseString($rs->Fields["allergy4"]->Value));
					array_push($rowdata,parseString($rs->Fields["allergy5"]->Value));
					array_push($rowdata,parseString($rs->Fields["allergy6"]->Value));
					array_push($rowdata,parseString($rs->Fields["allergy7"]->Value));
					
					array_push($rowdata,parseString($rs->Fields["admdisposition"]->Value));
					array_push($rowdata,parseString($rs->Fields["rmclass"]->Value));
					array_push($rowdata,parseString($rs->Fields["admresult"]->Value));
					array_push($rowdata,parseString($rs->Fields["dctrdcno"]->Value));
					array_push($rowdata,parseString($rs->Fields["servicetype"]->Value));
					array_push($rowdata,parseString($rs->Fields["dctrdcno"]->Value));
					//array_push($rowdata,parseString($rs->Fields["servicetype"]->Value));
					//array_push($rowdata,parseNumeric($rs->Fields["deathhours"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["deathautopsy"]->Value));
					array_push($rowdata,parseString($rs->Fields["admuser"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["discharge"]->Value));
					array_push($rowdata,parseDate($rs->Fields["dischdate"]->Value));
					array_push($rowdata,date('H:i:s',strtotime($rs->Fields["dischtime"]->Value)));
					array_push($rowdata,parseString($rs->Fields["dischuser"]->Value));
					array_push($rowdata,parseString($rs->Fields["rmno"]->Value));
					array_push($rowdata,parseFloat($rs->Fields["rmprice"]->Value));
					array_push($rowdata,parseString($rs->Fields["nrstatcode"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["mgh"]->Value));
					array_push($rowdata,parseDate($rs->Fields["mghdate"]->Value));
					array_push($rowdata,date('H:i:s',strtotime($rs->Fields["mghtime"]->Value)));
					array_push($rowdata,parseString($rs->Fields["mghuser"]->Value));
					array_push($rowdata,parseString($rs->Fields["untagmghrem"]->Value));
					array_push($rowdata,parseString($rs->Fields["billprintcount"]->Value));
					
					$billdatetime = date('Y-m-d H:i:s',strtotime($rs->Fields["billprintdate"]->Value));
					if (substr($billdatetime,0,10)!="1970-01-01") array_push($rowdata,$billdatetime);
					else array_push($rowdata,"");
					
					array_push($rowdata,"");
					array_push($rowdata,parseString($rs->Fields["billremarks"]->Value));
					array_push($rowdata,parseString($rs->Fields["admindiagnosis"]->Value));
					//array_push($rowdata,parseString($rs->Fields["dischdiagnosis"]->Value));
					//array_push($rowdata,parseString($rs->Fields["finaldiagcode"]->Value));
					//array_push($rowdata,parseString($rs->Fields["finaldiagnosis"]->Value));
					array_push($rowdata,parseString($rs->Fields["treatment"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["admcounter"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["newbornstat"]->Value));
					array_push($rowdata,parseString($rs->Fields["prevdietcode"]->Value));
					array_push($rowdata,parseString($rs->Fields["prevdietdesc"]->Value));
					array_push($rowdata,parseString($rs->Fields["typeofdeath"]->Value));
					array_push($rowdata,parseString($rs->Fields["typeofbirth"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["confidential"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["suspendby"]->Value));
					array_push($rowdata,date('H:i:s',strtotime($rs->Fields["suspendtime"]->Value)));
					array_push($rowdata,parseDate($rs->Fields["suspenddate"]->Value));
					array_push($rowdata,parseString($rs->Fields["suspendremarks"]->Value));
					array_push($rowdata,parseString($rs->Fields["ordiagnosis"]->Value));
					array_push($rowdata,"");
					array_push($rowdata,parseString($rs->Fields["allergytype"]->Value));
					array_push($rowdata,parseString($rs->Fields["discassignuser"]->Value));
					array_push($rowdata,parseDate($rs->Fields["discassigndt"]->Value));
					array_push($rowdata,parseString($rs->Fields["disctype"]->Value));
					array_push($rowdata,parseString($rs->Fields["noofdays"]->Value));					
					array_push($rowdata,parseDate($rs->Fields["deathdate"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["expiredflag"]->Value));					
					if (parseNumeric($rs->Fields["admcounter"]->Value)<=1) {
						array_push($rowdata,0);
					} else {
						array_push($rowdata,1);
					}
					array_push($rowdata,parseString($rs->Fields["sex"]->Value));					
					array_push($rowdata,parseString($rs->Fields["religion"]->Value));					
					
					array_push($data["In-Patients"],$rowdata);
					unset($rowdata);
					
					$rowdata = array();

					array_push($rowdata,parseString($rs->Fields["admno"]->Value));
					array_push($rowdata,parseString($rs->Fields["rmno"]->Value));
					array_push($rowdata,parseString($rs->Fields["rmclass"]->Value));
					//array_push($rowdata,parseString($rs->Fields["nrstatcode"]->Value));
					array_push($rowdata,parseFloat($rs->Fields["rmprice"]->Value));
					array_push($rowdata,parseNumeric($rs->Fields["noofdays"]->Value));					
					array_push($rowdata,parseFloat($rs->Fields["noofdays"]->Value)*parseFloat($rs->Fields["rmprice"]->Value));					
					array_push($rowdata,parseDate($rs->Fields["admdate"]->Value));
					array_push($rowdata,date('H:i:s',strtotime($rs->Fields["admtime"]->Value)));
					if (parseNumeric($rs->Fields["discharge"]->Value)==1) {
						array_push($rowdata,parseDate($rs->Fields["dischdate"]->Value));
						array_push($rowdata,date('H:i:s',strtotime($rs->Fields["dischtime"]->Value)));
					} else {
						array_push($rowdata,"");
						array_push($rowdata,"");
					}	
					
					array_push($data["Rooms"],$rowdata);
					unset($rowdata);
					
					$membertype = parseString($rs->Fields["phicclass"]->Value);
					if ($membertype!="" && $membertype!="OT" && $membertype!="NN") {
						$rowdata = array();
						array_push($rowdata,parseString($rs->Fields["admno"]->Value));
						array_push($rowdata,"PHIC");
						array_push($rowdata,0);
						array_push($rowdata,$membertype);
						array_push($data["Health Insurance"],$rowdata);
						unset($rowdata);
					}
															
					if (parseString($rs->Fields["finaldiagcode"]->Value)!="") {
						$rowdata2 = array();
						array_push($rowdata2,parseString($rs->Fields["admno"]->Value));
						array_push($rowdata2,"Primary");
						array_push($rowdata2,"O");
						array_push($rowdata2,parseDate($rs->Fields["dischdate"]->Value));
						array_push($rowdata2,"IP");
						array_push($rowdata2,parseString($rs->Fields["admno"]->Value));
						array_push($rowdata2,parseString($rs->Fields["abbrev"]->Value));
						array_push($rowdata2,parseString($rs->Fields["fullname"]->Value));
						array_push($rowdata2,parseString($rs->Fields["dctrdcno"]->Value));
						array_push($rowdata2,parseString($rs->Fields["servicetype"]->Value));
						array_push($rowdata2,parseString($rs->Fields["dischdiagnosis"]->Value));
						array_push($rowdata2,parseString($rs->Fields["finaldiagcode"]->Value));
						array_push($rowdata2,parseString($rs->Fields["finaldiagnosis"]->Value));
						
						array_push($data2["Medical Records"],$rowdata2);
						unset($rowdata2);
					}
										
					$rs->MoveNext();
					//break;
				}
				//var_dump($data["In-Patients"]);
				$actionReturn = $objDTWu_HISIPs->upload($data,false);
				if ($actionReturn) $actionReturn = $objDTWu_HISMedRecs->upload($data2,false);
				unset($data);
			}
			

			if ($actionReturn && $page->getitemstring("item")=="1") {
				$data3 = array();
				$data3["Items"] = array();
				
				array_push($data3["Items"],array("Code", "U_MANAGEBY", "U_ISSTOCK"));

				$data4 = array();
				$data4["Items"] = array();
				
				array_push($data4["Items"],array("Code", "U_GROUP"));

				$data5 = array();
				$data5["Items"] = array();
				
				array_push($data5["Items"],array("Code", "U_VATABLE"));
				
				$data = array();
				$data["Items"] = array();
				
				array_push($data["Items"],array("Code", "Name", "U_GENERICNAME", "U_UOM", "U_UOMPU", "U_GROUP", "U_MANAGEBY", "U_NUMPERUOMPU", "U_ACTIVE", "U_ALLOWDISCOUNT", "U_VATABLE", "U_SOACATEGORY", "U_ISSTAT", "U_STATPERC", "U_TEMPLATE", "U_PROCTYPE", "U_ISFIXEDASSET", "U_ISSTOCK", "U_PREFERREDSUPPNO"));
				
				$data2["Items"] = array();
				$data2["Item - Prices"] = array();
				//array_push($data2["Items"],array("Item Code","Item Description","Item Type","Item Group"));
				array_push($data2["Items"],array("Item Code"));
				array_push($data2["Item - Prices"],array("Item Code","Price List","Price"));
				
				$rs = $objADO->Execute("select itemid, itemdesc, genericname,unit,bigunit,category,primeclass,classgroup3,classgroup,stkcategory,conversion,active,
allowdiscount,deductable,classgroup2,printcateg,allowstat,statpercent,resultcateg,oprntype,
fixedasset,vendorname,purcprice,costbulk,saleprice1,saleprice6,saleprice8,saleprice9,saleprice10,saleprice12,saleprice14,saleprice15 from items order by itemid");
				while (!$rs->EOF) {
					if (substr(trim($rs->Fields["itemid"]->Value),0,5)=="00000") {
						$itemcode = substr(trim($rs->Fields["itemid"]->Value),5);
					} else $itemcode = trim($rs->Fields["itemid"]->Value);
					if ($itemcode=="") continue;
							
					$vatable=0;
					$manageby=0;
					$isstock=0;
					$allowdiscount=0;
					$isfixedasset=0;
					$isstat=0;
					$uom=trim($rs->Fields["unit"]->Value);
					$uompu=trim($rs->Fields["bigunit"]->Value);
					$preferredsuppno = trim($rs->Fields["vendorname"]->Value);
					if ($uom=="NONE") $uom="";
					if ($uompu=="NONE") $uompu="";
					if ($preferredsuppno=="N/A") $preferredsuppno="";
					if (trim($rs->Fields["category"]->Value)=="MED" || trim($rs->Fields["category"]->Value)=="SUP") $manageby=1;
					if (trim($rs->Fields["classgroup2"]->Value)=="VATABLE") $vatable=1;
					if (trim($rs->Fields["primeclass"]->Value)=="GOODS") $isstock=1;
					if (trim($rs->Fields["allowdiscount"]->Value)=="1") $allowdiscount=1;
					if (trim($rs->Fields["fixedasset"]->Value)=="1") $isfixedasset=1;
					if (trim($rs->Fields["allowstat"]->Value)=="1") $isstat=1;
					$objRs->queryopen("select suppno from suppliers where suppname='".addslashes($preferredsuppno)."'");
					if ($objRs->queryfetchrow("NAME")) {
						$preferredsuppno = $objRs->fields["suppno"];
					}
					
					/*
					array_push($data5["Items"],array($itemcode,
												$vatable)
											);
						
					array_push($data["Items"],array($itemcode,
									trim($rs->Fields["itemdesc"]->Value),
									trim($rs->Fields["genericname"]->Value),
									$uom,
									$uompu,
									trim($rs->Fields["category"]->Value),
									$manageby,
									trim($rs->Fields["conversion"]->Value),
									trim($rs->Fields["active"]->Value),
									$allowdiscount,
									$vatable,									
									trim($rs->Fields["printcateg"]->Value),
									$isstat,
									trim($rs->Fields["statpercent"]->Value),
									trim($rs->Fields["resultcateg"]->Value),
									trim($rs->Fields["oprntype"]->Value),
									$isfixedasset,
									$isstock,
									$preferredsuppno
									));		
					*/				
					
					/*array_push($data2["Items"],array($itemcode,
									trim($rs->Fields["itemdesc"]->Value),
									"PA",
									trim($rs->Fields["category"]->Value),
									$uom,
									$uompu
									));*/
					array_push($data2["Items"],array($itemcode
									));
							
							
					if (floatval($rs->Fields["costbulk"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									12, //purchase
									trim($rs->Fields["costbulk"]->Value)
									));
					}		
					/*
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									2, //ward
									trim($rs->Fields["saleprice1"]->Value)
									));
					}				
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									1, //opd
									trim($rs->Fields["saleprice6"]->Value)
									));
					}				
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									8, // iso
									trim($rs->Fields["saleprice8"]->Value)
									));
					}				
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									3, // semi-private
									trim($rs->Fields["saleprice9"]->Value)
									));
					}				
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									4,   // private
									trim($rs->Fields["saleprice10"]->Value)
									));
					}				
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									6, // nicu
									trim($rs->Fields["saleprice12"]->Value)
									));
					}				
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									7, // icu
									trim($rs->Fields["saleprice14"]->Value)
									));
					}				
					if (floatval($rs->Fields["saleprice1"]->Value)>0) {
						array_push($data2["Item - Prices"],array($itemcode,
									5, //suite
									trim($rs->Fields["saleprice15"]->Value)
									));
					}				
					*/
															
					$rs->MoveNext();
				}
				
				/*
				if ($actionReturn) {
					$actionReturn = $objRs->executesql("update items set manageby=0",false);
					if ($actionReturn) $objRs->executesql("update u_hisitems set u_manageby=0",false);
					
					if ($actionReturn) {
						$rs = $objADO->Execute("select itemid,itemdesc,category from (select x.itemid,i.itemdesc, i.category,isnull(xx.itemid,'') as gotlot from (
		SELECT [itemid]
		  FROM [smartapp].[dbo].[apitem] where lotno <>'' group by itemid) as x inner join items i on i.itemid=x.itemid left join apitem xx on xx.itemid=x.itemid  and lotno=''
		  ) as z where gotlot='' order by itemid");
						while (!$rs->EOF) {
							if (substr(trim($rs->Fields["itemid"]->Value),0,5)=="00000") {
								$itemcode = substr(trim($rs->Fields["itemid"]->Value),5);
							} else $itemcode = trim($rs->Fields["itemid"]->Value);	
							if ($itemcode=="") continue;
							
							$manageby=1;
							$isstock=1;
							
							array_push($data3["Items"],array($itemcode,
											$manageby,
											$isstock)
										);
							
							$rs->MoveNext();
						}
						//file_put_contents('d:\items.txt',var_export($data["Items"],true));
					}
				}
				*/
				
				/*
				if ($actionReturn) {
					$actionReturn = $objRs->executesql("update items set itemgroup=''",false);
					if ($actionReturn) $objRs->executesql("update u_hisitems set u_group=''",false);
					
					if ($actionReturn) {
						$rs = $objADO->Execute("select a.itemid, a.inventoryglcode,b.acctdesc from wareitem a inner join glacct b on b.acctcode=a.inventoryglcode");
						while (!$rs->EOF) {
							if (substr(trim($rs->Fields["itemid"]->Value),0,5)=="00000") {
								$itemcode = substr(trim($rs->Fields["itemid"]->Value),5);
							} else $itemcode = trim($rs->Fields["itemid"]->Value);	
							if ($itemcode=="") continue;
							
							$itemgroup='';
							$objRs->queryopen("select u_group from u_hismigrateitemgroupgls where code='".trim($rs->Fields["inventoryglcode"]->Value)."'",false);
							if ($objRs->queryfetchrow()) {
								$itemgroup = $objRs->fields[0];
							}
							if ($itemgroup!="") {
								array_push($data4["Items"],array($itemcode,
												$itemgroup)
											);
							}				
							
							$rs->MoveNext();
						}
						//file_put_contents('d:\items.txt',var_export($data["Items"],true));
					}
				}
				*/
				//file_put_contents('d:\items.txt',var_export($data["Items"],true));
				//$actionReturn = $objDTWu_HISItems->upload($data,false);
				if ($actionReturn) $actionReturn = $objDTWItems->upload($data2,false);
				//if ($actionReturn) $actionReturn = $objDTWu_HISItems->upload($data3,false);
				//if ($actionReturn) $actionReturn = $objDTWu_HISItems->upload($data4,false);
				//if ($actionReturn) $actionReturn = $objDTWu_HISItems->upload($data5,false);

				
				unset($data);
			}

			if ($actionReturn && $page->getitemstring("supplier")=="1") {
				$data = array();
				$data["Suppliers"] = array();
				$data["Supplier - Contacts"] = array();
				$data["Supplier - Addresses"] = array();
				array_push($data["Suppliers"],array("Supplier Code","Supplier Name","Supplier Group","Payment Term","Account Payable Account","Advances Account","U_VATABLE"));
				
				$rs = $objADO->Execute("select b.abbrev, b.fullname, a.terms, a.vatcond, a.glcode from vendor a inner join datacenter b on b.dcno=a.dcno and b.lvendor=1 inner join mscterms c on c.terms=a.terms order by b.abbrev");
				while (!$rs->EOF) {
					$vatable=0;
					if (trim($rs->Fields["vatcond"]->Value)=="VAT") $vatable=1;
					
					$objRs->queryopen("select name from u_hismigrateglaccts where code='".$rs->Fields["glcode"]->Value."'");
					if ($objRs->queryfetchrow("NAME")) {
						array_push($data["Suppliers"],array(trim($rs->Fields["abbrev"]->Value),
										trim($rs->Fields["fullname"]->Value),
										"5",
										trim($rs->Fields["terms"]->Value),
										$objRs->fields["name"],
										$objRs->fields["name"],
										$vatable
										));				
					} else {
						echo "Supplier: " . $rs->Fields["abbrev"]->Value . " - " . $rs->Fields["fullname"]->Value . " - No Mapping for G/L Code " .$rs->Fields["glcode"]->Value. " found.<br>";			
						$actionReturn = false;;		
					}	
					$rs->MoveNext();
				}
				if ($actionReturn) $actionReturn = $objDTWSuppliers->upload($data,false);
				unset($data);
			}
			//				var_dump($page->getitemdate("poasof"));
			if ($actionReturn && $page->getitemstring("po")=="1") {
				$data = array();
				$data["Purchase Orders"] = array();
				$data["Purchase Order - Items"] = array();

				array_push($data["Purchase Orders"],array("Document No.","Document Series","Supplier Code","Posting Date","Reference No.","Remarks"));
				array_push($data["Purchase Order - Items"],array("Document No.","Item Code","Quantity","Warehouse","Unit Price","UoM","Num per UoM"));
				
				//$rs = $objADO->Execute("select a.ctrlno, a.dcno, c.abbrev, c.fullname from poinv a inner join poitem b on b.ctrlno=a.ctrlno and b.qty>b.servqty inner join items d on d.itemid=b.itemid left join datacenter c on c.dcno=a.dcno and c.lvendor=1 where a.cancelflag=0 group by a.ctrlno, a.dcno, c.abbrev, c.fullname order by a.ctrlno");
				$rs = $objADO->Execute("select xx.ctrlno, xx.dcno, xxx.abbrev, xxx.fullname from (
	select ctrlno, dcno, uniqueno, itemid, sum(qty) as poqty, SUM(servqty) as servqty, SUM(apqty) as apqty, sum(qty)-(SUM(servqty)-SUM(apqty)) as obqty from (
		select a.ctrlno, a.dcno, b.uniqueno, b.itemid, b.qty,b.servqty, 0 as apqty from poinv a inner join poitem b on b.ctrlno=a.ctrlno where a.cancelflag=0 union all
		select a.pono as ctrlno, a.dcno, b.poitmctrl as uniqueno, b.itemid,0 as qty,0 as servqty, sum(b.qty) as apqty from apinv a inner join apitem b on b.ctrlno=a.ctrlno where a.cancelflag=0 and a.docdate>'".$page->getitemdate("poasof")."' and b.poitmctrl is not null group by a.pono, a.dcno, b.poitmctrl, b.itemid
	) as x group by ctrlno, dcno, uniqueno,itemid
) as xx 
   left join datacenter xxx on xxx.dcno=xx.dcno and xxx.lvendor=1  where obqty>0 group by xx.ctrlno, xx.dcno, xxx.abbrev, xxx.fullname 
   order by xx.ctrlno");
				while (!$rs->EOF) {
					$objRs->queryopen("select suppname from suppliers where suppno='".$rs->Fields["abbrev"]->Value."'");
					if ($objRs->queryfetchrow("NAME")) {
						//echo "Supplier Found: " . $rs->Fields["abbrev"]->Value . " - " .$rs->Fields["fullname"]->Value. "<br>";
						//$rs2 = $objADO->Execute("select a.ctrlno,a.dcno,a.docdate,a.remarks,a.prno,b.itemid,b.qty,b.unit,b.price,b.whcode,b.whabbrev from poinv a inner join poitem b on b.ctrlno=a.ctrlno and b.qty>b.servqty inner join items d on d.itemid=b.itemid where a.ctrlno='".$rs->Fields["ctrlno"]->Value."' and a.cancelflag=0 order by a.docdate desc");
						$ctr=0;
						
						$rs2 = $objADO->Execute("select ctrlno, dcno, docdate, prno, remarks, uniqueno, itemid, unit, price, sum(obqty) as openqty,whcode,whabbrev,conv from (
select a.ctrlno, a.dcno, a.docdate, a.prno, CAST(a.remarks AS varchar(max)) as remarks, b.uniqueno, b.itemid, b.unit, b.price, b.qty-b.servqty as obqty,b.whcode,b.whabbrev,b.conv from poinv a inner join poitem b on b.ctrlno=a.ctrlno inner join items c on c.itemid=b.itemid where  b.qty-b.servqty>0 and a.ctrlno='".$rs->Fields["ctrlno"]->Value."' and  a.cancelflag=0
 union all 
select c.ctrlno, c.dcno, c.docdate, c.prno, CAST(c.remarks AS varchar(max)) as remarks , d.uniqueno, d.itemid, d.unit, d.price, sum(b.qty) as obqty,d.whcode,d.whabbrev,d.conv from apinv a 
inner join apitem b on b.ctrlno=a.ctrlno 
inner join poinv c on c.ctrlno=a.pono 
inner join poitem d on d.dcno=c.dcno and d.uniqueno=b.poitmctrl inner join items e on e.itemid=d.itemid where c.ctrlno='".$rs->Fields["ctrlno"]->Value."' and a.cancelflag=0 and a.docdate>'".$page->getitemdate("poasof")."' and b.poitmctrl is not null group by c.ctrlno, c.dcno,c.docdate, c.prno, CAST(c.remarks AS varchar(max)), d.uniqueno, d.itemid, d.unit, d.price, d.whcode, d.whabbrev, d.conv
) as x group by ctrlno, dcno, docdate, prno, remarks, uniqueno, itemid, unit, price, whcode, whabbrev, conv");

						while (!$rs2->EOF) {
							if (substr(trim($rs2->Fields["itemid"]->Value),0,5)=="00000") {
								$itemcode = substr(trim($rs2->Fields["itemid"]->Value),5);
							} else $itemcode = trim($rs2->Fields["itemid"]->Value);	
							$whscode=trim($rs2->Fields["whabbrev"]->Value);	
							$price = 0;
							if (trim($rs2->Fields["price"]->Value)!="") $price = trim($rs2->Fields["price"]->Value);
							switch ($whscode) {
								case "ADMIN": $whscode="FIN-ADMIN"; break;
								case "ADMITTING": $whscode="HIS-ADMITTING"; break;
								case "BILLING": $whscode="FIN-BILLING"; break;        
								case "BLOOD": $whscode="LAB"; break;
								case "CSR": $whscode="FIN-CSR"; break;
								case "CTSCAN": $whscode="RAD-CT-SCAN"; break;
								case "DIALYSIS": $whscode="NSG-HDU"; break;
								case "DIETARY": $whscode="DIETARY"; break;
								case "DR": $whscode="NSG-DR"; break;
								case "FAM": $whscode="FAMILYRES"; break;
								case "FINANCE": $whscode="FIN-ACCT"; break;
								case "FLOOR 3": $whscode="NSG-NU-FL.3"; break;
								case "FLOOR1": $whscode="NSG-NU-FL.1"; break;
								case "FLOOR2": $whscode="NSG-NU-FL.2"; break;
								case "GENSERV": $whscode="GSO"; break;
								case "HEART STN": $whscode="NSG-HS"; break;
								case "HOUSEKEEP": $whscode="GSO-HOUSEKEEPING"; break;
								case "HR": $whscode="HRD-HR"; break;
								case "ICU": $whscode="NSG-ICU"; break;
								case "NICU": $whscode="NSG-NICU"; break;
								case "LAB": $whscode="LAB"; break;
								case "MAIN1": $whscode="NSG-NU-MAIN.1"; break;
								case "OR": $whscode="NSG-OR"; break;
								case "PHARMACY": $whscode="PHA"; break;
								case "PURCHASING": $whscode="FIN-PURCH"; break;
								case "REHAB": $whscode="PMR"; break;
								case "SANCU": $whscode="SANCU"; break;
								case "WAREHOUSE": $whscode="FIN-PURCH"; break;
								case "XRAY": $whscode="RAD-X-RAY"; break;
								case "ER": $whscode="NSG-ER"; break;
								case "QA": $whscode="QAO"; break;
							}
							if ($ctr==0) {
								array_push($data["Purchase Orders"],
									array(trim($rs2->Fields["ctrlno"]->Value),
										"manual",
										trim($rs->Fields["abbrev"]->Value),
										trim($rs2->Fields["docdate"]->Value),
										trim($rs2->Fields["prno"]->Value),
										trim($rs2->Fields["remarks"]->Value)));
							}
							array_push($data["Purchase Order - Items"],
								array(trim($rs2->Fields["ctrlno"]->Value),
									$itemcode,
									trim($rs2->Fields["openqty"]->Value),
									$whscode,
									$price,
									trim($rs2->Fields["unit"]->Value),
									trim($rs2->Fields["conv"]->Value)));
							$ctr++;
							$rs2->MoveNext();
						}
					}// else echo "Supplier: " . $rs->Fields["dcno"]->Value . " - " . $rs->Fields["abbrev"]->Value . " - " .$rs->Fields["fullname"]->Value. "<br>";
					$rs->MoveNext();
				}				
				$actionReturn = $objDTWPurchaseOrders->upload($data,false);
				unset($data);
			
			}

			if ($actionReturn && $page->getitemstring("pr")=="1") {
				$companydata = getcurrentcompanydata("PRSUPPNO,PURCHASINGPRICELIST");
				
				$data = array();
				$data["Purchase Requests"] = array();
				$data["Purchase Request - Items"] = array();

				array_push($data["Purchase Requests"],array("Document No.","Document Series","Supplier No.","Posting Date","Remarks"));
				array_push($data["Purchase Request - Items"],array("Document No.","Item Code","Quantity","Warehouse","Unit Price","UoM","Num per UoM"));

				$rs = $objADO->Execute("select a.ctrlno,a.docdate,a.remarks from prinv a where a.docdate>='".$page->getitemdate("prfr")."' and a.docdate<='".$page->getitemdate("prto")."' and a.cancelflag=0 order by a.ctrlno");
				while (!$rs->EOF) {
					$objRs->queryopen("select suppname from suppliers where suppno='".$companydata["PRSUPPNO"]."'");
					if ($objRs->queryfetchrow("NAME")) {
						$docstatus="O";
						$ctr=0;
						$docno = "PR".trim($rs->Fields["ctrlno"]->Value);
						$rs2 = $objADO->Execute("select a.docdate, a.remarks, b.itemid, b.unit, b.price, b.qty, a.whcode, a.dept as whabbrev, b.conversion from prinv a inner
join pritem b on b.ctrlno=a.ctrlno and a.ctrlno='".$rs->Fields["ctrlno"]->Value."'");
						while (!$rs2->EOF) {
							if (substr(trim($rs2->Fields["itemid"]->Value),0,5)=="00000") {
								$itemcode = substr(trim($rs2->Fields["itemid"]->Value),5);
							} else $itemcode = trim($rs2->Fields["itemid"]->Value);	
							$whscode=trim($rs2->Fields["whabbrev"]->Value);	
							$uom=trim($rs2->Fields["unit"]->Value);	
							$numperuom=trim($rs2->Fields["conversion"]->Value);	
							$price = 0;
							if (trim($rs2->Fields["price"]->Value)!="") $price = trim($rs2->Fields["price"]->Value);
							switch ($whscode) {
								case "ADMIN": $whscode="FIN-ADMIN"; break;
								case "ADMITTING": $whscode="HIS-ADMITTING"; break;
								case "BILLING": $whscode="FIN-BILLING"; break;        
								case "BLOOD": $whscode="LAB"; break;
								case "CSR": $whscode="FIN-CSR"; break;
								case "CTSCAN": $whscode="RAD-CT-SCAN"; break;
								case "DIALYSIS": $whscode="NSG-HDU"; break;
								case "DIETARY": $whscode="DIETARY"; break;
								case "DR": $whscode="NSG-DR"; break;
								case "FAM": $whscode="FAMILYRES"; break;
								case "FINANCE": $whscode="FIN-ACCT"; break;
								case "FLOOR 3": $whscode="NSG-NU-FL.3"; break;
								case "FLOOR1": $whscode="NSG-NU-FL.1"; break;
								case "FLOOR2": $whscode="NSG-NU-FL.2"; break;
								case "GENSERV": $whscode="GSO"; break;
								case "HEART STN": $whscode="NSG-HS"; break;
								case "HOUSEKEEP": $whscode="GSO-HOUSEKEEPING"; break;
								case "HR": $whscode="HRD-HR"; break;
								case "ICU": $whscode="NSG-ICU"; break;
								case "NICU": $whscode="NSG-NICU"; break;
								case "LAB": $whscode="LAB"; break;
								case "MAIN1": $whscode="NSG-NU-MAIN.1"; break;
								case "OR": $whscode="NSG-OR"; break;
								case "PHARMACY": $whscode="PHA"; break;
								case "PURCHASING": $whscode="FIN-PURCH"; break;
								case "REHAB": $whscode="PMR"; break;
								case "SANCU": $whscode="SANCU"; break;
								case "WAREHOUSE": $whscode="FIN-PURCH"; break;
								case "XRAY": $whscode="RAD-X-RAY"; break;
								case "ER": $whscode="NSG-ER"; break;
								case "QA": $whscode="QAO"; break;
							}
							array_push($data["Purchase Request - Items"],
								array($docno,
									$itemcode,
									trim($rs2->Fields["qty"]->Value),
									$whscode,
									$price,
									$uom,
									$numperuom
								)	
							);
							$rs2->MoveNext();
						}
						array_push($data["Purchase Requests"],
							array($docno,
								"manual",
								$companydata["PRSUPPNO"],
								trim($rs->Fields["docdate"]->Value),
								trim($rs->Fields["remarks"]->Value)
							)
						);
					} else return raiseError("Invalid Supplier [".$companydata["PRSUPPNO"]."]");
					$rs->MoveNext();
				}				
				$page->console->insertVar($data);
				$actionReturn = $objDTWPurchaseRequests->upload($data,false);
				//if ($actionReturn) $actionReturn = raiseError("rey");
				unset($data);
			
			}			
						
			if ($actionReturn && $page->getitemstring("ap")=="1") {
				$data = array();
				$data["AP Invoices"] = array();
				$data["AP Invoice - Items"] = array();
				$data["AP Invoice - Batches"] = array();
				$data["AP Invoice - Services"] = array();

				array_push($data["AP Invoices"],array("Document No.","Document Series","Document Status","Supplier No.","Posting Date","Reference No.","Remarks"));
				array_push($data["AP Invoice - Items"],array("Document No.","Item Code","Quantity","Warehouse","Unit Price","UoM","Num per UoM","Discount","Base Type","Base Document"));
				array_push($data["AP Invoice - Batches"],array("Document No.","Item Code","Quantity","Warehouse","Unit Price","UoM","Num per UoM","Discount","Base Type","Base Document","Batch","Batches.U_EXPDATE"));
				array_push($data["AP Invoice - Services"],array("Document No.","VAT Code","Unit Price"));

				$data2 = array();
				$data2["AP Credit Memos"] = array();
				$data2["AP Credit Memo - Items"] = array();
				$data2["AP Credit Memo - Batches"] = array();
				$data2["AP Credit Memo - Services"] = array();

				array_push($data2["AP Credit Memos"],array("Document No.","Document Series","Document Status","Supplier No.","Posting Date","Reference No.","Remarks"));
				array_push($data2["AP Credit Memo - Items"],array("Document No.","Item Code","Quantity","Warehouse","Unit Price","Discount","Base Type","Base Document"));
				array_push($data2["AP Credit Memo - Batches"],array("Document No.","Item Code","Quantity","Warehouse","Unit Price","Discount","Base Type","Base Document","Batch","Batches.U_EXPDATE"));
				array_push($data2["AP Credit Memo - Services"],array("Document No.","VAT Code","Unit Price"));
				
				//$rs = $objADO->Execute("select a.ctrlno, a.dcno, c.abbrev, c.fullname from poinv a inner join poitem b on b.ctrlno=a.ctrlno and b.qty>b.servqty inner join items d on d.itemid=b.itemid left join datacenter c on c.dcno=a.dcno and c.lvendor=1 where a.cancelflag=0 group by a.ctrlno, a.dcno, c.abbrev, c.fullname order by a.ctrlno");
				$rs = $objADO->Execute("select a.docid,a.docno,a.ctrlno,a.docdate,a.refno,a.pono,a.remarks,a.dcno,a.curramt,b.abbrev, b.fullname from apinv a inner join datacenter b on b.dcno=a.dcno where docdate>='".$page->getitemdate("apfr")."' and docdate<='".$page->getitemdate("apto")."' and a.cancelflag=0 order by a.docno");
				while (!$rs->EOF) {
					$objRs->queryopen("select suppname from suppliers where suppno='".$rs->Fields["abbrev"]->Value."'");
					if ($objRs->queryfetchrow("NAME")) {
						//echo "Supplier Found: " . $rs->Fields["abbrev"]->Value . " - " .$rs->Fields["fullname"]->Value. "<br>";
						//$rs2 = $objADO->Execute("select a.ctrlno,a.dcno,a.docdate,a.remarks,a.prno,b.itemid,b.qty,b.unit,b.price,b.whcode,b.whabbrev from poinv a inner join poitem b on b.ctrlno=a.ctrlno and b.qty>b.servqty inner join items d on d.itemid=b.itemid where a.ctrlno='".$rs->Fields["ctrlno"]->Value."' and a.cancelflag=0 order by a.docdate desc");					
						$docstatus="O";
						$ctr=0;
						$totalamount= floatval(trim($rs->Fields["curramt"]->Value));
						switch ($rs->Fields["docid"]->Value) {
							case "RR":
								$docno = "RR".trim($rs->Fields["docno"]->Value);
								$rs2 = $objADO->Execute("select a.docno, a.dcno, a.docdate, a.pono, a.remarks, b.itemid, b.unit, b.fobprice, b.qty,b.disc1, b.whcode,b.whabbrev, b.lotno,b.expdate,b.fgoods,b.poextract,b.conversion from apinv a inner
		join apitem b on b.ctrlno=a.ctrlno and a.ctrlno='".$rs->Fields["ctrlno"]->Value."'");
								while (!$rs2->EOF) {
									if (substr(trim($rs2->Fields["itemid"]->Value),0,5)=="00000") {
										$itemcode = substr(trim($rs2->Fields["itemid"]->Value),5);
									} else $itemcode = trim($rs2->Fields["itemid"]->Value);	
									$whscode=trim($rs2->Fields["whabbrev"]->Value);	
									$uom=trim($rs2->Fields["unit"]->Value);	
									$numperuom=trim($rs2->Fields["conversion"]->Value);	
									$price = 0;
									$discamount = 0;
									$basetype = "";
									$basedocno = "";
									if (trim($rs->Fields["pono"]->Value)!="" && intval(trim($rs2->Fields["poextract"]->Value))==1) {
										$basetype = "PURCHASEORDER";
										$basedocno = "PO".trim($rs->Fields["pono"]->Value);
									}
									if (trim($rs2->Fields["fobprice"]->Value)!="") $price = trim($rs2->Fields["fobprice"]->Value);
									if (trim($rs2->Fields["disc1"]->Value)!="") $discamount = (trim($rs2->Fields["disc1"]->Value)/100) * $price;
									if (intval(trim($rs2->Fields["fgoods"]->Value))==1) $discamount = $price ;
									switch ($whscode) {
										case "ADMIN": $whscode="FIN-ADMIN"; break;
										case "ADMITTING": $whscode="HIS-ADMITTING"; break;
										case "BILLING": $whscode="FIN-BILLING"; break;        
										case "BLOOD": $whscode="LAB"; break;
										case "CSR": $whscode="FIN-CSR"; break;
										case "CTSCAN": $whscode="RAD-CT-SCAN"; break;
										case "DIALYSIS": $whscode="NSG-HDU"; break;
										case "DIETARY": $whscode="DIETARY"; break;
										case "DR": $whscode="NSG-DR"; break;
										case "FAM": $whscode="FAMILYRES"; break;
										case "FINANCE": $whscode="FIN-ACCT"; break;
										case "FLOOR 3": $whscode="NSG-NU-FL.3"; break;
										case "FLOOR1": $whscode="NSG-NU-FL.1"; break;
										case "FLOOR2": $whscode="NSG-NU-FL.2"; break;
										case "GENSERV": $whscode="GSO"; break;
										case "HEART STN": $whscode="NSG-HS"; break;
										case "HOUSEKEEP": $whscode="GSO-HOUSEKEEPING"; break;
										case "HR": $whscode="HRD-HR"; break;
										case "ICU": $whscode="NSG-ICU"; break;
										case "NICU": $whscode="NSG-NICU"; break;
										case "LAB": $whscode="LAB"; break;
										case "MAIN1": $whscode="NSG-NU-MAIN.1"; break;
										case "OR": $whscode="NSG-OR"; break;
										case "PHARMACY": $whscode="PHA"; break;
										case "PURCHASING": $whscode="FIN-PURCH"; break;
										case "REHAB": $whscode="PMR"; break;
										case "SANCU": $whscode="SANCU"; break;
										case "WAREHOUSE": $whscode="FIN-PURCH"; break;
										case "XRAY": $whscode="RAD-X-RAY"; break;
										case "ER": $whscode="NSG-ER"; break;
										case "QA": $whscode="QAO"; break;
									}
									$manageby=0;
									$objRs2->queryopen("select manageby from items where itemcode='$itemcode'");
									if ($objRs2->queryfetchrow("NAME")) $manageby = intval($objRs2->fields["manageby"]);
									switch ($manageby) {
										case 1:
											$lotno=trim($rs2->Fields["lotno"]->Value);
											$expdate=substr(trim($rs2->Fields["expdate"]->Value),0,10);
											if ($lotno=="") {
												$lotno="none";
												$expdate="";
												//$docstatus="D";
											}	
											if ($totalamount>=0) {
												array_push($data["AP Invoice - Batches"],
													array($docno,
														$itemcode,
														trim($rs2->Fields["qty"]->Value),
														$whscode,
														$price,
														$uom,
														$numperuom,
														$discamount,
														$basetype,
														$basedocno,
														$lotno,
														$expdate
													)	
												);
											} else {
												$docstatus = "D";
												array_push($data2["AP Credit Memo - Batches"],
													array($docno,
														$itemcode,
														floatval(trim($rs2->Fields["qty"]->Value))*-1,
														$whscode,
														$price,
														$uom,
														$numperuom,
														$discamount,
														$basetype,
														$basedocno,
														$lotno,
														$expdate
													)	
												);
											}	
											break;
										default:
											if ($totalamount>=0) {
												array_push($data["AP Invoice - Items"],
													array($docno,
														$itemcode,
														trim($rs2->Fields["qty"]->Value),
														$whscode,
														$price,
														$uom,
														$numperuom,
														$discamount,
														$basetype,
														$basedocno
													)
												);
											} else {
												array_push($data2["AP Credit Memo - Items"],
													array($docno,
														$itemcode,
														floatval(trim($rs2->Fields["qty"]->Value))*-1,
														$whscode,
														$price,
														$discamount,
														$basetype,
														$basedocno
													)
												);
											}	
											break;
									}				
									$ctr++;
									$rs2->MoveNext();
								}
								if ($ctr>0) {
									if ($totalamount>=0) {
										array_push($data["AP Invoices"],
											array($docno,
												"manual",
												$docstatus,
												trim($rs->Fields["abbrev"]->Value),
												trim($rs->Fields["docdate"]->Value),
												trim($rs->Fields["refno"]->Value),
												trim($rs->Fields["remarks"]->Value)
											)
										);
									} else {
										array_push($data2["AP Credit Memos"],
											array($docno,
												"manual",
												$docstatus,
												trim($rs->Fields["abbrev"]->Value),
												trim($rs->Fields["docdate"]->Value),
												trim($rs->Fields["refno"]->Value),
												trim($rs->Fields["remarks"]->Value)
											)
										);
									}	
								}
								break;
							case "CM":
								$docno = "CM".trim($rs->Fields["docno"]->Value);
								$docstatus = "D";
								array_push($data["AP Invoices"],
									array($docno,
										"manual",
										$docstatus,
										trim($rs->Fields["abbrev"]->Value),
										trim($rs->Fields["docdate"]->Value),
										trim($rs->Fields["refno"]->Value),
										trim($rs->Fields["remarks"]->Value)
									)
								);
								array_push($data["AP Invoice - Services"],
									array($docno,
										"VATIX",
										$totalamount
									)
								);
								break;
							case "DM":
								$docno = "DM".trim($rs->Fields["docno"]->Value);
								$docstatus = "D";
								array_push($data2["AP Credit Memos"],
									array($docno,
										"manual",
										$docstatus,
										trim($rs->Fields["abbrev"]->Value),
										trim($rs->Fields["docdate"]->Value),
										trim($rs->Fields["refno"]->Value),
										trim($rs->Fields["remarks"]->Value)));
								array_push($data2["AP Credit Memo - Services"],
									array($docno,
										"VATIX",
										$totalamount*-1
									)
								);
								break;
						}									
					}// else echo "Supplier: " . $rs->Fields["dcno"]->Value . " - " . $rs->Fields["abbrev"]->Value . " - " .$rs->Fields["fullname"]->Value. "<br>";
					$rs->MoveNext();
				}				
				//$page->console->insertVar($data);
				$actionReturn = $objDTWAPInvoices->upload($data,false);
				if ($actionReturn) $actionReturn = $objDTWAPCreditMemos->upload($data2,false);
				//if ($actionReturn) $actionReturn = raiseError("rey");
				unset($data);
			
			}			

			if ($actionReturn && $page->getitemstring("pf")=="1") {
				$data = array();
				$data["In-Patients"] = array();
				array_push($data["Medical Records"],array("Document No.","Document Series","Document Status","U_DOCDATE","U_REFTYPE","U_REFNO","U_PATIENTID","U_PATIENTNAME","U_DOCTORID","U_DOCTORSERVICE","U_REMARKS","U_ICDCODE","U_ICDDESC"));
			}									

			if ($actionReturn) $objConnection->commit();
			else $objConnection->rollback();
			
			$_SESSION["dbmodified"] = 1;
			
		} catch (Exception $e) {
			$actionReturn = raiseError($e->getMessage());
			$objConnection->rollback();
		}
		//$objConnection->beginwork();
		
		//if ($actionReturn) $actionReturn = raiseError("rey");
		
		//if ($actionReturn) $objConnection->commit();
	//	else $objConnection->rollback();

		return $actionReturn;
	}
	
	function onFormDefault() {
		global $page;
		global $branchdata;
		$page->setitem("branch",$_SESSION["branch"]);
		$page->setitem("migratedate",formatDateToHttp($branchdata["MIGRATEDATE"]));
		$page->setitem("customer",1);
		$page->setitem("cash",1);
		$page->setitem("installment",1);
		$page->setitem("payment",1);
		$page->setitem("foreclosure",1);
		
	}
	
	$page->reportlayouts = true;
	$page->settings->load();

	//var_dump($branchdata);
	
	$migratedate = formatDateToDB($page->getitemstring('migratedate'));
	
	$branchcode = $page->getitemstring('branch');
	$branchdata = getbranchdata($company,$branch,"MIGRATEDATE");
	
	$objrs = new recordset(NULL,$objConnection);
	$objrs2 = new recordset(NULL,$objConnection);
	$objrs->logtrx = false;
	
	$actionReturn=true;
	
		
	
	require("./inc/formactions.php");
	
	$schema_batchpost["patient"] = createSchema("patient");
	$schema_batchpost["patientfr"] = createSchema("patientfr");
	$schema_batchpost["patientto"] = createSchema("patientto");
	
	$schema_batchpost["ip"] = createSchemaDate("ip");
	$schema_batchpost["ipfr"] = createSchemaDate("ipfr");
	$schema_batchpost["ipto"] = createSchemaDate("ipto");

	$schema_batchpost["item"] = createSchema("item");
	$schema_batchpost["supplier"] = createSchema("supplier");
	$schema_batchpost["po"] = createSchema("po");
	$schema_batchpost["poasof"] = createSchemaDate("poasof");

	$schema_batchpost["pr"] = createSchemaDate("pr");
	$schema_batchpost["prfr"] = createSchemaDate("prfr");
	$schema_batchpost["prto"] = createSchemaDate("prto");

	$schema_batchpost["ap"] = createSchemaDate("ap");
	$schema_batchpost["apfr"] = createSchemaDate("apfr");
	$schema_batchpost["apto"] = createSchemaDate("apto");

	$schema_batchpost["dbserver"] = createSchema("dbserver");
	$schema_batchpost["dbuser"] = createSchema("dbuser");
	$schema_batchpost["dbpwd"] = createSchema("dbpwd");
	$schema_batchpost["dbname"] = createSchema("dbname");
	

	//$page->setitem("branch",$branch);
	
	$page->popup->addgroup("popupPrintTo");
	$page->popup->additem("popupPrintTo","Export to Excel","formPosting('excel')");
	$page->popup->additem("popupPrintTo","Export to PDF","formPosting('pdf')");
	$page->popup->additem("popupPrintTo","Print","formPosting('printer')");

	saveErrorMsg();
	//var_dump($sql);
	//var_dump($objGrid->recordcount);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo @$pageTitle ?></title>
<link rel="stylesheet" type="text/css" href="css/mainheader.css">
<link rel="stylesheet" type="text/css" href="css/mainheader_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/standard.css">
<link rel="stylesheet" type="text/css" href="css/standard_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tblbox.css">
<link rel="stylesheet" type="text/css" href="css/tblbox_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/deobjs.css">
<link rel="stylesheet" type="text/css" href="css/deobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn.css">
<link rel="stylesheet" type="text/css" href="css/lnkbtn_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs.css">
<link rel="stylesheet" type="text/css" href="css/txtobjs_<?php echo $page->theme ; ?>.css">
<link rel="stylesheet" type="text/css" href="css/tabber.css">
<link rel="stylesheet" type="text/css" href="css/tabber_<?php echo $page->theme ; ?>.css">
<STYLE>
</STYLE>
</head>
<SCRIPT language=JavaScript src="js/utilities.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/ajax.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formobjs.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/formtbls.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/pdfcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/cflcalendar.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupframe.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupmenu.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/popupcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/lnkbtncommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="js/rptcommon.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/businesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlgetreportlayoutdata.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="ajax/xmlvalidatebusinesspartners.js" type=text/javascript></SCRIPT>
<SCRIPT language=JavaScript src="cfls/directories.js"></SCRIPT>
<script type="text/javascript">
document.write('<style type="text/css">.tabber{display:none;}<\/style>');
var tabberOptions = {
  'manualStartup':true,
  'onLoad': function(argsObj) {},
  'onClick': function(argsObj) {return true;},
  'addLinkId': true
};
</script>
<SCRIPT language=JavaScript src="js/tabber.js" type=text/javascript></SCRIPT>

<script>

	function formPrintTo(element) {
		popupPrintTo(element.offsetLeft + 10,browserInnerHeight());
		return false;
	}
	
	function onPageLoad() {
		focusInput("patient");
		if (!isInputChecked("patient")) {
			disableInput("patientfr");
			disableInput("patientto");
		}
		if (!isInputChecked("ip")) {
			disableInput("ipfr");
			disableInput("ipto");
		}
		if (!isInputChecked("po")) {
			disableInput("poasof");
		}
		if (!isInputChecked("pr")) {
			disableInput("prfr");
			disableInput("prto");
		}
		if (!isInputChecked("ap")) {
			disableInput("apfr");
			disableInput("apto");
		}
		
		
	}
	
	function onElementClick(element,column,table,row) {
		switch (column) {
			case "patient":
				if (isInputChecked(column)) {
					enableInput("patientfr");
					enableInput("patientto");
					focusInput("patientfr");
				} else {
					setInput("patientfr","");
					setInput("patientto","");
					disableInput("patientfr");
					disableInput("patientto");
				}
				break;
			case "ip":
				if (isInputChecked(column)) {
					enableInput("ipfr");
					enableInput("ipto");
					focusInput("ipfr");
				} else {
					setInput("ipfr","");
					setInput("ipto","");
					disableInput("ipfr");
					disableInput("ipto");
				}
				break;
			case "po":
				if (isInputChecked(column)) {
					enableInput("poasof");
					focusInput("poasof");
				} else {
					setInput("poasof","");
					disableInput("poasof");
				}
				break;
			case "pr":
				if (isInputChecked(column)) {
					enableInput("prfr");
					enableInput("prto");
					focusInput("prfr");
				} else {
					setInput("prfr","");
					setInput("prto","");
					disableInput("prfr");
					disableInput("prto");
				}
				break;
			case "ap":
				if (isInputChecked(column)) {
					enableInput("apfr");
					enableInput("apto");
					focusInput("apfr");
				} else {
					setInput("apfr","");
					setInput("apto","");
					disableInput("apfr");
					disableInput("apto");
				}
				break;
		}
	}
	
	function onFormSubmit(action) {
		if(action=="u_migrate") {
			if (!isInputChecked("patient") && !isInputChecked("ip") && !isInputChecked("item") && !isInputChecked("supplier") && !isInputChecked("po") && !isInputChecked("pr") && !isInputChecked("ap")) {
				page.statusbar.showError("At least 1 data to migrate option must be check.");
				return false;
			}
			if (isInputChecked("po")) {
				if (isInputEmpty("poasof")) return false; 
			}
			if (isInputChecked("pr")) {
				if (isInputEmpty("prfr")) return false; 
				if (isInputEmpty("prto")) return false; 
			}
			if (isInputChecked("ap")) {
				if (isInputEmpty("apfr")) return false; 
				if (isInputEmpty("apto")) return false; 
			}
			
			if (isInputEmpty("dbserver")) return false; 
			//if (isInputEmpty("dbuser")) return false; 
			if (isInputEmpty("dbname")) return false; 
			
		}
		showAjaxProcess();
		return true;
	}	

	function onFormSubmitReturn(action,sucess,error) {
		if (sucess)	alert('Data Migration ended successfully.');
		else alert(error);
	}
	
</script>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onResize="pageResize()">
<FORM name="formData" method="post" enctype="multipart-form-data"
	action="<?php echo $_SERVER['PHP_SELF']?>?">
<input type="hidden" id="batchpostingmode" name="batchpostingmode" value="<?php echo $companydata["BATCHPOSTINGMODE"];  ?>">	
<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td width="10">&nbsp;</td><td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<!-- start content -->
<tr><td>
<?php require("./inc/formobjs.php") ?>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td class="labelPageHeader" >&nbsp;<?php echo @$pageHeader ?>&nbsp;</td></tr>
</table></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	
	<tr>
	  <td width="320">&nbsp;</td>
	  <td align=left colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
<tr>
	  <td >&nbsp;<label class="lblobjs"><b>Data to Migrate:</b></label></td>
	  <td colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
		  
	<tr><td ><input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["patient"],"1") ?> /><label <?php genCaptionHtml($schema_batchpost["patient"],"") ?> >Patient Records</label></td>
		<td align=left colspan="2"><label <?php genCaptionHtml($schema_batchpost["patientfr"],"") ?> >From :</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["patientfr"]) ?> /><label <?php genCaptionHtml($schema_batchpost["patientto"],"") ?> >To:</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["patientto"]) ?> /></td>
		<td >&nbsp;</td>
   	</tr>
	<tr><td ><input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["ip"],"1") ?> /><label <?php genCaptionHtml($schema_batchpost["ip"],"") ?> >In-Patient Records</label></td>
		<td align=left colspan="2"><label <?php genCaptionHtml($schema_batchpost["ipfr"],"") ?> >From :</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["ipfr"]) ?> /><label <?php genCaptionHtml($schema_batchpost["ipto"],"") ?> >To:</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["ipto"]) ?> /></td>
		<td >&nbsp;</td>
   	</tr>
	<tr><td ><input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["supplier"],"1") ?> /><label <?php genCaptionHtml($schema_batchpost["supplier"],"") ?> >Suppliers</label></td>
		<td align=left colspan="2">&nbsp;</td>
		<td >&nbsp;</td>
   	</tr>
	<tr><td ><input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["item"],"1") ?> /><label <?php genCaptionHtml($schema_batchpost["item"],"") ?> >Items</label></td>
		<td align=left colspan="2">&nbsp;</td>
		<td >&nbsp;</td>
   	</tr>
	<tr><td ><input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["po"],"1") ?> /><label <?php genCaptionHtml($schema_batchpost["po"],"") ?> >Open Purchase Orders</label></td>
		<td align=left colspan="2"><label <?php genCaptionHtml($schema_batchpost["poasof"],"") ?> >As of:</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["poasof"]) ?> /></td>
		<td >&nbsp;</td>
   	</tr>
	<tr><td ><input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["pr"],"1") ?> /><label <?php genCaptionHtml($schema_batchpost["pr"],"") ?> >Purchase Request</label></td>
		<td align=left colspan="2"><label <?php genCaptionHtml($schema_batchpost["prfr"],"") ?> >From :</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["prfr"]) ?> /><label <?php genCaptionHtml($schema_batchpost["prto"],"") ?> >To:</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["prto"]) ?> /></td>
		<td >&nbsp;</td>
   	</tr>
	<tr><td ><input type="checkbox" <?php genInputCheckboxHtml($schema_batchpost["ap"],"1") ?> /><label <?php genCaptionHtml($schema_batchpost["ap"],"") ?> >Account Payables</label></td>
		<td align=left colspan="2"><label <?php genCaptionHtml($schema_batchpost["apfr"],"") ?> >From :</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["apfr"]) ?> /><label <?php genCaptionHtml($schema_batchpost["apto"],"") ?> >To:</label>&nbsp;<input type="text" size="15" <?php genInputTextHtml($schema_batchpost["apto"]) ?> /></td>
		<td >&nbsp;</td>
   	</tr>
	
	<tr>
	  <td >&nbsp;</td>
	  <td align=left colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;<label class="lblobjs"><b>Database Server Connection:</b></label></td>
	  <td colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;&nbsp;<label <?php genCaptionHtml($schema_batchpost["dbserver"],"") ?> >Server</label></td>
	  <td colspan="2"><input type="text" <?php genInputTextHtml($schema_batchpost["dbserver"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;&nbsp;<label <?php genCaptionHtml($schema_batchpost["dbuser"],"") ?> >User</label></td>
	  <td colspan="2"><input type="text" <?php genInputTextHtml($schema_batchpost["dbuser"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;&nbsp;<label <?php genCaptionHtml($schema_batchpost["dbpwd"],"") ?> >Password</label></td>
	  <td colspan="2"><input type="password" <?php genInputTextHtml($schema_batchpost["dbpwd"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;&nbsp;<label <?php genCaptionHtml($schema_batchpost["dbname"],"") ?> >Database</label></td>
	  <td colspan="2"><input type="text" <?php genInputTextHtml($schema_batchpost["dbname"]) ?> /></td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="2">&nbsp;</td>
	  <td >&nbsp;</td>
	  </tr>
	<tr>
	  <td >&nbsp;</td>
	  <td colspan="2"><a class="button" href="" onClick="formSubmit('u_migrate');return false;">Execute</a></td>
	  <td >&nbsp;</td>
	  </tr>
	
</table></td></tr>			
<?php if ($requestorId == "") { ?>
	<tr><td>&nbsp;</td></tr>
	<?php //require("./sboBatchPostingToolbar.php");  ?>
<?php } ?>    
<!-- end content -->
</table></td><td width="10">&nbsp;</td></tr></table>		
</FORM>
<?php require("./inc/rptobjs.php") ?>
<?php require("./bofrms/ajaxprocess.php"); ?>
<?php $page->writePopupMenuHTML();?>
</body>

</html>
<?php $page->writeEndScript(); ?>
<?php	
	restoreErrorMsg();

	//$parentref = "parent.";
	//$htmltoolbarbottom = "./bottomtoolbar.php?&formAccess=" . $httpVars['formAccess'] . "&toolbarscript=" . getUserProgramFilePath("u_DataMigrationToolbar.php");
?>
<?php 
	include("setbottomtoolbar.php"); 
	include("setstatusmsg.php");
?>
