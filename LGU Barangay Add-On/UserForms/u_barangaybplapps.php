<tr><td>
	<div class="tabber" id="tab1">
	  <div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr>
                    <tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_apptype") ?>>*Application Type</label></td>
					  <td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
                    <td colspan="4">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>New</label>&nbsp;
                                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Renewal</label>&nbsp;
                                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype","RETIRED") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Retire/Revoke</label>
                                            </td>
					</tr>  
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_paymode") ?>>*Mode of Payment</label></td>
					  <td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","S") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Semi-Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode","Q") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Quarterly</label></td>
					  <td>&nbsp;</td>
					  <td align="center"></td>
			      </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td></td>
					  <td colspan="2">&nbsp;</td>
				  </tr>
					<tr><td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Date of Application</label></td>
					  <td></td>
						<td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_regno") ?>>DTI/SEC/CDA Registration No.</label></td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_regno") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >Reference No.</label></td>
					  <td>&nbsp;</td>
					  <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_regdate") ?>>DTI/SEC/CDA Registration Date</label></td>
				  </tr>
					<tr>
					  <td colspan="2">&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
					  <td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_regdate") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
                  
                  <tr><td width="150"><label <?php $page->businessobject->items->userfields->draw->caption("u_orgtype") ?>>*Ownership</label></td>
					  <td width="250">&nbsp;</td>
						<td width="325"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno") ?>>CTC No.</label></td>
						<td width="325">&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2" width="300">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype","SINGLE") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Single</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype","PARTNERSHIP") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Partnership</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype","CORPORATION") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Corporation</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype","COOPERATIVE") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Cooperative</label></td>
					  <td>&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_ctcno") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessname") ?>>*Business Name</label></td>
					  <td>&nbsp;</td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>TIN</label></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
					  <td>&nbsp;<input type="text" size="16" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					
					<tr><td colspan="2"><label class="lblobjs"><b>Name of Tax Payer</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>*Last Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>*First Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
						<td ><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
					  <td>&nbsp;</td>
			      </tr>
				</table>
       	  </div>
        </div>            
	  <div class="tabbertab" title="Owner/Business Details">
			<div id="divob" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					
					<tr><td colspan="2">&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_sameasowneraddr",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_sameasowneraddr") ?>>Same as Owner Address</label></td>
						<td>&nbsp;</td>
						<td width="537">&nbsp;</td>
					  <td width="17">&nbsp;</td>
				  </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Business Address</b></label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype","Commercial") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Commercial</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype","Residential") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Residential</label></td>
						<td colspan="3"><label class="lblobjs"><b>Owner's Address</b></label></td>
						
					</tr>
					<tr><td width="224"><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Bldg No.</label></td>
					  <td width="244"><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Bldg Name</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgno") ?>>Bldg No.</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgname") ?>>Bldg Name</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgname") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="224"><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No.</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bstreet") ?>>Street</label></td>
						<td width="214"><label <?php $page->businessobject->items->userfields->draw->caption("u_unitno") ?>>Unit No.</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_unitno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="224"><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>*Brgy.</label></td>
						<td width="214"><label <?php $page->businessobject->items->userfields->draw->caption("u_village") ?>>Subdivision</label></td>
					  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_brgy") ?>>Brgy.</label></td>
						<td >&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bvillage") ?>/></td>
					  <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy") ?>></select></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_village") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_brgy") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="224"><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City/Municipality</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
						<td width="214"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City/Municipality</label></td>
					  <td><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="224"><label <?php $page->businessobject->items->userfields->draw->caption("u_btelno") ?>>Tel No.</label></td>
					  <td>&nbsp;</td>
						<td width="214"><label <?php $page->businessobject->items->userfields->draw->caption("u_telno") ?>>Tel No.</label></td>
					  <td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_btelno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_telno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="224"><label <?php $page->businessobject->items->userfields->draw->caption("u_bemail") ?>>Email Address</label></td>
					  <td>&nbsp;</td>
						<td width="214"><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email Address</label></td>
					  <td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_bemail") ?>/></td>
					  <td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
				  </tr>
					
				</table>
   	    </div>
        </div>            
<!--	 <div class="tabbertab" title="Lessor Details">
			<div id="divlessor" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td colspan="2"><label class="lblobjs">If Place of Business is Rented please specify the following</label></td>
						<td width="170">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Lessor's Name</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_llastname") ?>>Last Name</label></td>
						<td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lfirstname") ?>>First Name</label></td>
						<td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lmiddlename") ?>>Middle Name</label></td>
						<td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_monthlyrental") ?>>Monthly Rental</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_llastname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lfirstname") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lmiddlename") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_monthlyrental") ?>/></td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td colspan="2"><label class="lblobjs"><b>Lessor's Address</b></label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lbldgno") ?>>Bldg No.</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_lvillage") ?>>Subdivision</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lbldgno") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lvillage") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lstreet") ?>>Street</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_lcity") ?>>City/Municipality</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lstreet") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lcity") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
			      </tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_lbrgy") ?>>Brgy</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_lprovince") ?>>Province</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lbrgy") ?>/></td>
						<td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_lprovince") ?>/></td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
					</tr>
					<tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_ltelno") ?>>Tel No.</label></td>
						<td><label <?php $page->businessobject->items->userfields->draw->caption("u_lemail") ?>>Email Address</label></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ltelno") ?>/></td>
					  <td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_lemail") ?>/></td>
                      <td>&nbsp;</td>
					  
				  </tr>
					<tr><td width="206"></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr><td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_contactperson") ?>>In case of emergency contact person name</label></td>
						<td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_contacttelno") ?>>Contact person Tel No./Mobile No.</label></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
					  <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_contactperson") ?>/></td>
					  <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_contacttelno") ?>/></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
				  </tr>
					<tr><td width="206"></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
               	</table>
       	  </div>
        </div> -->
        <div class="tabbertab" title="Requirements">
       	 <div id="divlessor" style="overflow:auto;">
                <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                        <td><?php $objGrids[1]->draw(true); ?></td>
                    </tr>
                </table>
		</div>   
        </div> 
        <div class="tabbertab" title="Payment">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>Receipt Number</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_paiddate") ?>>Paid Date</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_paiddate") ?>/></td>
					</tr>
                    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dueamount") ?>>Due Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_dueamount") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_paidamount") ?>>Paid Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_paidamount") ?>/></td>
					</tr>
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_changeamount") ?>>Change Amount</label></td>
						<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_changeamount") ?>/></td>
					</tr>
                    <tr><td width="168" colspan="2"><?php $objGrids[0]->draw(true) ?>	</td>
						<td>&nbsp;</td>
					</tr>
				 
				</table>
			</div>
		</div> 
	

</td></tr>	

<?php $page->resize->addtab("tab1",-1,81); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
<?php //$page->resize->addtabpage("tab1","ob") ?>
<?php //$page->resize->addtabpage("tab1","lessor") ?>
<?php //$page->resize->addtabpage("tab1","ba") ?>
<?php $page->resize->addgridobject($objGrids[0],20,247)?>
<?php $page->resize->addgridobject($objGrids[1],20,234) ?>
<?php //$page->resize->addgridobject($objGrids[5],20,135) ?>
<?php //$page->resize->addgridobject($objGrids[7],20,234) ?>
<?php //$page->resize->addgridobject($objGrids[8],20,234) ?>
<?php //$page->resize->addgridobject($objGrids[2],575,-1) ?>
<?php //$page->resize->addgridobject($objGrids[3],575,410) ?>
<?php //$page->resize->addgridobject($objGrids[6],-1,410) ?>

