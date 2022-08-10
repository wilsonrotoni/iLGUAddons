<?php
	if(!empty($_POST)) extract($_POST);
	if(!empty($_GET)) extract($_GET);
	$httpVars = array_merge($_POST,$_GET);
	include_once("../common/classes/connection.php");
	include_once("../common/classes/recordset.php");
	include_once("./classes/collections.php");
	include_once("./classes/marketingdocuments.php");
	include_once("./classes/marketingdocumentcashcards.php");
	include_once("./series.php");
	$actionReturn = true;
	
	$objConnection->beginwork();

	$objRs3 = new recordset (NULL,$objConnection);
	$objRs2 = new recordset (NULL,$objConnection);
	$objJvHdr = new journalvouchers(null,$objConnection);
	$objJvHdrCN = new journalvouchers(null,$objConnection);
	$objJvDtl = new journalvoucheritems(null,$objConnection);
	$objJeHdr = new journalentries(null,$objConnection);
	$objJeDtl = new journalentryitems(null,$objConnection);
	$objJeHdrCN = new journalentries(null,$objConnection);

	$objARHdr = new marketingdocuments(null,$objConnection,"arinvoices");
	$objARCashCards = new marketingdocumentcashcards(null,$objConnection,"arinvoicecashcards");

	$objARCMHdr = new marketingdocuments(null,$objConnection,"arcreditmemos");
	$objARCMCashCards = new marketingdocumentcashcards(null,$objConnection,"arcreditmemocashcards");
	//$objRs3->shareddatatype = "branch";
	//$objRs3->shareddatacode = $objRs3->fields["BRANCH"];
	
	$ctr=0;
	var_dump('2015-02-28');
	if ($actionReturn) {
		$objRs3->queryopen("select a.docno, a.docid, a.u_billno, a.u_patientname, a.u_jvdocno, b.u_glacctno, b.u_glacctname from u_hisinclaims a inner join u_hishealthins b on b.code=a.u_inscode where a.u_hmo=2 and a.docstatus<>'CN' and a.u_docdate between '2015-02-01' and '2015-02-28'");
		while ($objRs3->queryfetchrow("NAME")) {
			//echo $objRs3->fields["u_jvdocno"] . "<br>";
			if ($objJvHdr->getbykey($objRs3->fields["u_jvdocno"])) {
				$olddebit = 0;
				$newdebit = 0;
				if ($actionReturn) {
					$objRs2->queryopen("select debit from journalvoucheritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid = '$objJvHdr->docid' and itemtype='A' and itemno='".$objRs3->fields["u_glacctno"]."'");
					if ($objRs2->queryfetchrow("NAME")) $olddebit = $objRs2->fields["debit"];
					
					$actionReturn = $objRs2->executesql("delete from journalvoucheritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid = '$objJvHdr->docid' and itemtype='A' and itemno='".$objRs3->fields["u_glacctno"]."'");
				}	
				
				$objRs2->queryopen("select u_profitcenter, sum(u_thisamount) as u_thisamount from (

select if(d.u_profitcenter<>'',d.u_profitcenter,c.u_department) as u_profitcenter, b.u_thisamount from u_hisinclaims a
  inner join u_hisinclaimsplrooms b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
  inner join u_hisbills e on e.company=a.company and e.branch=a.branch and e.docno=a.u_billno
  inner join u_hisbillsplrooms f on f.company=a.company and f.branch=a.branch and f.docid=e.docid and f.lineid=b.u_lineid
  inner join u_hischarges c on c.company=a.company and c.branch=a.branch and c.docno=b.u_refno
  inner join u_hisitems d on d.code=f.u_itemcode
  where a.u_hmo=2 and a.docno='".$objRs3->fields["docno"]."'
  union all
select 'HIS-ADMITTING' as u_profitcenter, b.u_thisamount from u_hisinclaims a
  inner join u_hisinclaimrooms b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
  where a.u_hmo=2 and a.docno='".$objRs3->fields["docno"]."'
  union all
select if(d.u_profitcenter<>'',d.u_profitcenter,c.u_department), b.u_thisamount from u_hisinclaims a
  inner join u_hisinclaimmedsups b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
  inner join u_hischarges c on c.company=a.company and c.branch=a.branch and c.docno=b.u_refno
  inner join u_hisitems d on d.code=b.u_itemcode
  where a.u_hmo=2 and a.docno='".$objRs3->fields["docno"]."'
  union all
select if(d.u_profitcenter<>'',d.u_profitcenter,c.u_department), b.u_thisamount from u_hisinclaims a
  inner join u_hisinclaimlabs b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
  inner join u_hischarges c on c.company=a.company and c.branch=a.branch and c.docno=b.u_refno
  left outer join u_hisitems d on d.code=b.u_itemcode
  where a.u_hmo=2 and a.docno='".$objRs3->fields["docno"]."'

  ) as x group by u_profitcenter having u_thisamount<>'0'");
				while ($objRs2->queryfetchrow("NAME")) {
					//var_dump(array($objRs3->fields["u_jvdocno"],$objRs2->fields["u_profitcenter"],$objRs2->fields["u_thisamount"]));
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $objRs3->fields["u_glacctno"];
					$objJvDtl->itemname = $objRs3->fields["u_glacctname"];
					$objJvDtl->profitcenter = $objRs2->fields["u_profitcenter"];
					$objJvDtl->debit = $objRs2->fields["u_thisamount"];
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					if (!$actionReturn) break;
					$newdebit+=$objRs2->fields["u_thisamount"];
				}
				if ($actionReturn && bcsub($olddebit,$newdebit,14)!=0) $actionReturn = raiseError("Unbalance old debit[$olddebit] and new debit [$newdebit] for In claims No [".$objRs3->fields["docno"]."/".$objRs3->fields["docid"]."/".$objRs3->fields["u_billno"]."/".$objRs3->fields["u_patientname"]."]");
				if ($actionReturn) {
					if ($objJvHdr->sbo_post_flag==1) {
						$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('JV') and docno='$objJvHdr->docno'",false);
						if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('JV') and docno='$objJvHdr->docno'",false);
						if ($actionReturn) $objJvHdr->sbo_post_flag=0;
					}
					$actionReturn = $objJvHdr->update($objJvHdr->docno,$objJvHdr->rcdversion);
				}	
				if ($actionReturn) {
					$actionReturn = sboBatchPostJournalVoucher("JOURNALVOUCHER",$objRs3->fields["u_jvdocno"],null,true);
				}	

			} else $actionReturn = raiseError("Unable to find JV No [".$objRs3->fields["u_jvdocno"]."]");
			if (!$actionReturn) break;

			$ctr++;
			echo $objRs3->fields["docno"]."<br>";
		}
	}	
	
	$ctr=0;
	
	if ($actionReturn) {
		$objRs3->queryopen("select a.docno, a.docstatus,a.docid, a.u_billno, a.u_patientname, a.u_jvdocno, a.u_jvcndocno, a.u_pnamount, b.u_glacctno, b.u_glacctname, b.u_department from u_hispronotes a inner join u_hishealthins b on b.code=a.u_guarantorcode where a.u_hmo=2 and  a.docstatus<>'CN' and a.u_docdate between '2015-02-01' and '2015-02-28'");
		while ($objRs3->queryfetchrow("NAME")) {
			if ($objJvHdr->getbykey($objRs3->fields["u_jvdocno"])) {
				//echo "found:" . $objJvHdr->docno . "<br>";
				if ($objRs3->fields["docstatus"]=="CN") {
					if (!$objJvHdrCN->getbykey($objRs3->fields["u_jvcndocno"])) {
						$actionReturn = raiseError("Unable to find CN JV No [".$objRs3->fields["u_jvcndocno"]."]");
					}
				}	
			
				$olddebit = 0;
				$newdebit = 0;
				if ($actionReturn) {
					$objRs2->setdebug();
					$objRs2->queryopen("select debit from journalvoucheritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid = '$objJvHdr->docid' and itemtype='A' and itemno='".$objRs3->fields["u_glacctno"]."'");
					if ($objRs2->queryfetchrow("NAME")) $olddebit = $objRs2->fields["debit"];
					$actionReturn = $objRs2->executesql("delete from journalvoucheritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid = '$objJvHdr->docid' and itemtype='A' and itemno='".$objRs3->fields["u_glacctno"]."'");
				}	
				if ($actionReturn && $objRs3->fields["docstatus"]=="CN") {
					$actionReturn = $objRs2->executesql("delete from journalvoucheritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid = '$objJvHdrCN->docid' and itemtype='A' and itemno='".$objRs3->fields["u_glacctno"]."'");
				}
				$profitcenters = array();
				$discountable = 0;
				if ($objRs3->fields["u_department"]=="") {
					$objRs2->queryopen("select u_profitcenter, sum(u_amount) as u_amount from (
	select if(d.u_profitcenter<>'',d.u_profitcenter,c.u_department) as u_profitcenter, b.u_amount
	  from u_hisbills a
	  inner join u_hisbillmedsups b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
	  inner join u_hischarges c on c.company=a.company and c.branch=a.branch and c.docno=b.u_refno
	  inner join u_hisitems d on d.code=b.u_itemcode and d.u_billdiscount=1
	  where a.docno='".$objRs3->fields["u_billno"]."'
	  union all
	select if(d.u_profitcenter<>'',d.u_profitcenter,c.u_department) as u_profitcenter, b.u_amount
	  from u_hisbills a
	  inner join u_hisbillmiscs b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
	  inner join u_hischarges c on c.company=a.company and c.branch=a.branch and c.docno=b.u_refno
	  inner join u_hisitems d on d.code=b.u_itemcode and d.u_billdiscount=1
	  where a.docno='".$objRs3->fields["u_billno"]."'
	  union all
	select if(d.u_profitcenter<>'',d.u_profitcenter,c.u_department) as u_profitcenter, b.u_amount
	  from u_hisbills a
	  inner join u_hisbilllabtests b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
	  inner join u_hischarges c on c.company=a.company and c.branch=a.branch and c.docno=b.u_refno
	  inner join u_hisitems d on d.code=b.u_type and d.u_billdiscount=1
	  where a.docno='".$objRs3->fields["u_billno"]."'
	  union all
	select if(d.u_profitcenter<>'',d.u_profitcenter,c.u_department) as u_profitcenter, b.u_amount
	  from u_hisbills a
	  inner join u_hisbillsplrooms b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
	  inner join u_hischarges c on c.company=a.company and c.branch=a.branch and c.docno=b.u_refno
	  inner join u_hisitems d on d.code=b.u_itemcode and d.u_billdiscount=1
	  where a.docno='".$objRs3->fields["u_billno"]."'
	  union all
	select 'HIS-ADMITTING' as u_profitcenter, b.u_amount
	  from u_hisbills a
	  inner join u_hisbillrooms b on b.company=a.company and b.branch=a.branch and b.docid=a.docid
	  where a.docno='".$objRs3->fields["u_billno"]."') as x group by u_profitcenter having u_amount>0");
					while ($objRs2->queryfetchrow("NAME")) {
						$profitcenters[$objRs2->fields["u_profitcenter"]] = $objRs2->fields["u_amount"];
						$discountable+=$objRs2->fields["u_amount"];
					}
				} else {
						$profitcenters[$objRs3->fields["u_department"]] = $objRs3->fields["u_pnamount"];
						$discountable+=$objRs2->fields["u_amount"];
				}	
				$discountable = $objRs3->fields["u_pnamount"]/$discountable;
				$lineno=0;
				foreach ($profitcenters as $key => $value) {
					$lineno++;
					if ($lineno==count($profitcenters)) {
						$discamount = ($objRs3->fields["u_pnamount"] - $newdebit);
					} else $discamount = round($value * $discountable,2);
					if ($discamount==0) continue;
					//var_dump(array($objRs3->fields["u_jvdocno"],$objRs2->fields["u_profitcenter"],$objRs2->fields["u_thisamount"]));
					$objJvDtl->prepareadd();
					$objJvDtl->docid = $objJvHdr->docid;
					$objJvDtl->objectcode = $objJvHdr->objectcode;
					$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
					$objJvDtl->refbranch = $_SESSION["branch"];
					$objJvDtl->itemtype = "A";
					$objJvDtl->itemno = $objRs3->fields["u_glacctno"];
					$objJvDtl->itemname = $objRs3->fields["u_glacctname"];
					$objJvDtl->profitcenter = $key;
					$objJvDtl->debit = $discamount;
					$objJvDtl->grossamount = $objJvDtl->debit;
					$objJvDtl->privatedata["header"] = $objJvHdr;
					$actionReturn = $objJvDtl->add();
					//var_dump(array($objJvDtl->profitcenter,$objJvDtl->debit));
					if (!$actionReturn) break;
					$newdebit+=$discamount;
					
					if ($actionReturn && $objRs3->fields["docstatus"]=="CN") {					
						$objJvDtl->prepareadd();
						$objJvDtl->docid = $objJvHdrCN->docid;
						$objJvDtl->objectcode = $objJvHdr->objectcode;
						$objJvDtl->lineid = getNextIdByBranch("JOURNALVOUCHERITEMS",$objConnection);
						$objJvDtl->refbranch = $_SESSION["branch"];
						$objJvDtl->itemtype = "A";
						$objJvDtl->itemno = $objRs3->fields["u_glacctno"];
						$objJvDtl->itemname = $objRs3->fields["u_glacctname"];
						$objJvDtl->profitcenter = $key;
						$objJvDtl->credit = $discamount;
						$objJvDtl->grossamount = $objJvDtl->credit;
						$objJvDtl->privatedata["header"] = $objJvHdrCN;
						$actionReturn = $objJvDtl->add();
						if (!$actionReturn) break;
					}
				}
				//var_dump(array($olddebit,$newdebit));
				if ($actionReturn && bcsub($olddebit,$newdebit,14)!=0) $actionReturn = raiseError("Unbalance old debit[$olddebit] and new debit [$newdebit] for In claims No [".$objRs3->fields["docno"]."/".$objRs3->fields["docid"]."/".$objRs3->fields["u_billno"]."/".$objRs3->fields["u_patientname"]."]");
				if ($actionReturn) {
					if ($objJvHdr->sbo_post_flag==1) {
						$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('JV') and docno='$objJvHdr->docno'",false);
						if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('JV') and docno='$objJvHdr->docno'",false);
						if ($actionReturn) $objJvHdr->sbo_post_flag=0;
					}
					$actionReturn = $objJvHdr->update($objJvHdr->docno,$objJvHdr->rcdversion);

				}	
				if ($actionReturn && $objRs3->fields["docstatus"]=="CN") {
					if ($objJvHdrCN->sbo_post_flag==1) {
						$actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('JV') and docno='$objJvHdrCN->docno'",false);
						if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and doctype in ('JV') and docno='$objJvHdrCN->docno'",false);
						if ($actionReturn) $objJvHdrCN->sbo_post_flag=0;
					}
					$actionReturn = $objJvHdrCN->update($objJvHdrCN->docno,$objJvHdrCN->rcdversion);

				}	
				//var_dump(array($actionReturn,$objJvHdr->docno));
				
				if ($actionReturn) {
					$actionReturn = sboBatchPostJournalVoucher("JOURNALVOUCHER",$objJvHdr->docno,null,true);
					//var_dump($actionReturn);
				}	

				if ($actionReturn && $objRs3->fields["docstatus"]=="CN") {
					$actionReturn = sboBatchPostJournalVoucher("JOURNALVOUCHER",$objJvHdrCN->docno,null,true);
					//var_dump($actionReturn);
				}	

			}// else $actionReturn = raiseError("Unable to find JV No [".$objRs3->fields["u_jvdocno"]."]");
			if (!$actionReturn) break;
				
			$ctr++;
			echo $objRs3->fields["docno"]."<br>";
			//break;
		}
	}	
	
	if ($actionReturn) {
		$objRs3->queryopen("select b.docno,b.docid from u_hispronotes a inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docno=a.u_jvdocno where a.company='".$_SESSION["company"]."' and a.branch='".$_SESSION["branch"]."' and a.u_hmo=2 and a.u_docdate <= '2014-10-31' and a.docstatus='CN' 
		
		union all 
		
		select b.docno,b.docid from u_hispronotes a inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docno=a.u_jvcndocno where a.company='his' and a.branch='ho' and a.u_hmo=2 and a.u_docdate <= '2014-10-31' and a.docstatus='CN' 
		
		union all 
		
		select b.docno,b.docid from u_hisinclaims a inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docno=a.u_jvdocno where a.company='his' and a.branch='ho' and a.u_hmo=2 and a.u_docdate <= '2014-10-31' and a.docstatus='CN'
		
		union all
		
		select b.docno,b.docid from u_hisinclaims a inner join journalvouchers b on b.company=a.company and b.branch=a.branch and b.docno=a.u_jvcndocno where a.company='his' and a.branch='ho' and a.u_hmo=2 and a.u_docdate <= '2014-10-31' and a.docstatus='CN'
		
		");// and a.u_docdate between '2014-03-29' and '2014-04-30'
		while ($objRs3->queryfetchrow("NAME")) {
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalvoucheritems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid = '".$objRs3->fields["docid"]."'");
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalvouchers where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docid = '".$objRs3->fields["docid"]."'");
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentryitems where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno = '".$objRs3->fields["docno"]."'");
			if ($actionReturn) $actionReturn = $objRs2->executesql("delete from journalentries where company='".$_SESSION["company"]."' and branch='".$_SESSION["branch"]."' and docno = '".$objRs3->fields["docno"]."'");
			if (!$actionReturn) break;
		}
	}


	if ($actionReturn) {
		///$objConnection->rollback();
		$objConnection->commit();
		printf("%1 Records where successfully updated.",$ctr);
	} else { 	
		$objConnection->rollback();
		echo $_SESSION["errormessage"];
	}

	
?>
