<?php
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("../common/utility/utilities.php");
	include_once("./utils/login.php");
	include_once("./classes/masterdataschema_br.php");

	$actionReturn = true;
	$corid=$_GET["corid"];

	file_put_contents("c:\\users\ajaxMobileHISSearch.Input-".$_GET["type"]."-".$_GET["userid"].".txt",serialize($_GET));
	switch ($_GET["type"]) {
		case "CheckConnection":
			$objs["connection"] = array();
			array_push($objs["connection"],array("status"=>"connected"));
			echo json_encode($objs);
			file_put_contents("c:\\users\ajaxMobileHISSearch.Output-".$_GET["type"]."-".$_GET["userid"].".txt",serialize($objs));
			break;
		case "Registrations":
			$objs["registrations"] = array();
			$objRs = new recordset(NULL,$objConnection);
			if ($_GET["regtype"]=="In-Patient") {
				if ($_GET["docno"]=="%") {
					$objRs->queryopen("select a.docno, a.u_patientname from u_hisips a inner join u_hisipdoctors b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_doctorid='".$_GET["userid"]."' where a.company='HIS' and a.branch='HO' and a.docstatus='Active'");
				} else {
					$objRs->queryopen("select a.docno, a.u_startdate, a.u_starttime, a.u_patientname, a.u_historyillness, a.u_pastmedhistory, a.u_complaint, a.u_remarks from u_hisips a  inner join u_hisipdoctors b on b.company=a.company and b.branch=a.branch and b.docid=a.docid and b.u_doctorid='".$_GET["userid"]."' where a.company='HIS' and a.branch='HO' and a.docno='".$_GET["docno"]."' and a.docstatus='Active'");
				}	
			} else {
				if ($_GET["docno"]=="%") {
					$objRs->queryopen("select a.docno, a.u_patientname from u_hisops a where a.company='HIS' and a.branch='HO' and a.docstatus='Active' and u_paymentterm='PHILHEALTH' and a.u_doctorid='".$_GET["userid"]."' and u_startdate>='".dateadd("d",-1,date('Y-m-d'))."' order by u_startdate desc");
				} else {
					$objRs->queryopen("select a.docno, a.u_startdate, a.u_starttime, a.u_patientname, a.u_historyillness, a.u_pastmedhistory, a.u_complaint, a.u_remarks from u_hisops a where a.company='HIS' and a.branch='HO' and a.docno='".$_GET["docno"]."' and a.docstatus='Active' and u_paymentterm='PHILHEALTH' and a.u_doctorid='".$_GET["userid"]."' and u_startdate>='".dateadd("d",-1,date('Y-m-d'))."'");
				}	
			}	
			while ($objRs->queryfetchrow("NAME")) {
				$data = array();
				$data["docno"] = $objRs->fields["docno"];
				$data["patientname"] = $objRs->fields["u_patientname"];
				if ($_GET["docno"]!="%") {
					$data["startdate"] = formatDateToHttp($objRs->fields["u_startdate"]);
					$data["starttime"] = formatTimeToHttp($objRs->fields["u_starttime"]);
					$data["historyillness"] = $objRs->fields["u_historyillness"];
					$data["pastmedhistory"] = $objRs->fields["u_pastmedhistory"];
					$data["complaint"] = $objRs->fields["u_complaint"];
					$data["remarks"] = $objRs->fields["u_remarks"];
				}	
				array_push($objs["registrations"],$data);
 			}
			echo json_encode($objs); 
			file_put_contents("c:\\users\ajaxMobileHISSearch.Output-".$_GET["type"]."-".$_GET["userid"].".txt",serialize($objs));
			break;
		default:
			$actionReturn = raiseError("Invalid Type [".$_GET["type"]."].");
			break;	
	}
	
	
?>
