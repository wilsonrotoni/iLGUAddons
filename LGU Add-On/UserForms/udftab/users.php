<div class="tabbertab" title="General (UDF iLGU)">
	<div id="divtabudfpo" style="overflow:auto;">
		<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                                <td><label class="lblobjs" ><b>Position</b></label></td>
                                <td ><input type="text" <?php genInputTextHtml($page->udfs["header"][""]["u_position"]) ?> ></td>
                                <td><label class="lblobjs" ><b>Department</b></label></td>
                                <td >&nbsp;<select <?php genSelectHtml(array("name"=>"deptid"),array("loadudflinktable","departments:department:departmentname",":All")) ?> > </select></td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                        </tr>
			<tr>
                                <td><label class="lblobjs" ><b>Business Permit</b></label></td>
                                <td >&nbsp;</td>
                                <td ><label class="lblobjs" ><b>Real Property Tax</b></label></td>
                                <td >&nbsp;</td>
                                <td><label class="lblobjs" ><b>Treasury Management</b></label></td>
                                <td >&nbsp;</td>
                        </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bplencoder"]) ?> >Encoder</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplencoder"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_rptencoder"]) ?> >Encoder</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_rptencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_rptencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_rptencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_rptencoder"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_cancelpayflag"]) ?> >Cancel Payment</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cancelpayflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cancelpayflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cancelpayflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cancelpayflag"]) ?> >Yes</label></td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_bplassessor"]) ?> >Assessor</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplassessor"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplassessor"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplassessor"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplassessor"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_rptassessor"]) ?> >Assessor</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_rptassessor"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_rptassessor"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_rptassessor"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_rptassessor"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_paymentvalidator"]) ?> >Payment Validator</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_paymentvalidator"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_paymentvalidator"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_paymentvalidator"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_paymentvalidator"]) ?> >Yes</label></td>
			</tr>
			<tr>
			  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_bplapprover"]) ?> >Approver</label></td>
			  <td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplapprover"]) ?> >Yes</label></td>
                          <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_rptapprover"]) ?> >Approver</label></td>
			  <td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_rptapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_rptapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_rptapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_rptapprover"]) ?> >Yes</label></td>
                          <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_manualpostingflag"]) ?> >Manual Posting</label></td>
                          <td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_manualpostingflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_manualpostingflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_manualpostingflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_manualpostingflag"]) ?> >Yes</label></td>
			</tr>
                        <tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bplhold"]) ?> >Hold</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplhold"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplhold"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplhold"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplhold"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                        </tr>
                        <tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bpldelete"]) ?> >Delete</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bpldelete"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bpldelete"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bpldelete"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bpldelete"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                        </tr>
                        <tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bplretire"]) ?> >Retire</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplretire"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplretire"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplretire"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplretire"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                        </tr>
                        <tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bplinserthistory"]) ?> >Insert Payment History</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplinserthistory"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplinserthistory"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplinserthistory"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplinserthistory"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                        </tr>
                        <tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bplviewpaymenthistory"]) ?> >View Payment History</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplviewpaymenthistory"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplviewpaymenthistory"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bplviewpaymenthistory"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bplviewpaymenthistory"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                        </tr>
                      
			<tr>
			  <td ><label class="lblobjs" ><b>Fire Dept.</b></label></td>
			  <td >&nbsp;</td>
			  <td ><label class="lblobjs" ><b>Zoning Dept.</b></label></td>
			  <td >&nbsp;</td>
			  <td ><label class="lblobjs" ><b>Sanitary Dept.</b></label></td>
			  <td >&nbsp;</td>
                        </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_fsispector"]) ?> >Inspector</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fsispector"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fsispector"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fsispector"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fsispector"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_zoningispector"]) ?> >Inspector</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_zoningispector"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_zoningispector"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_zoningispector"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_zoningispector"]) ?> >Yes</label></td>
				<td><label <?php genCaptionHtml($page->udfs["header"][""]["u_sanitaryispector"]) ?> >Inspector</label></td>
                                <td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_sanitaryispector"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_sanitaryispector"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_sanitaryispector"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_sanitaryispector"]) ?> >Yes</label></td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_fsrecommend"]) ?> >Recommending Officer</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fsrecommend"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fsrecommend"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fsrecommend"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fsrecommend"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_zoningrecommend"]) ?> >Recommending Officer</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_zoningrecommend"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_zoningrecommend"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_zoningrecommend"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_zoningrecommend"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_sanitaryrecommend"]) ?> >Recommending Officer</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_sanitaryrecommend"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_sanitaryrecommend"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_sanitaryrecommend"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_sanitaryrecommend"]) ?> >Yes</label></td>
			</tr>
			<tr class="fillerRow10px">
				<td ></td>
				<td></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_zoningapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_zoningapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_zoningapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_zoningapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_zoningapprover"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_sanitaryapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_sanitaryapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_sanitaryapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_sanitaryapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_sanitaryapprover"]) ?> >Yes</label></td>
			</tr>
			<tr>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
			  <td ><label class="lblobjs" ><b>MTOP</b></label></td>
			  <td >&nbsp;</td>
			  <td ><label class="lblobjs" ><b>Public Market Rental</b></label></td>
			  <td >&nbsp;</td>
			  <td ><label class="lblobjs" ><b>Building Official</b></label></td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_mtopencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_mtopencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_mtopencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_mtopencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_mtopencoder"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_pmrencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pmrencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_pmrencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pmrencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_pmrencoder"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bldgispector"]) ?> >Inspector</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bldgispector"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bldgispector"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bldgispector"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bldgispector"]) ?> >Yes</label></td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_mtopassessor"]) ?> >Assessor</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_mtopassessor"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_mtopassessor"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_mtopassessor"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_mtopassessor"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_pmrassessor"]) ?> >Assessor</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pmrassessor"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_pmrassessor"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pmrassessor"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_pmrassessor"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_bldgrecommend"]) ?> >Recommending Officer</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bldgrecommend"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bldgrecommend"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bldgrecommend"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bldgrecommend"]) ?> >Yes</label></td>
			</tr>
			<tr>
			  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_mtopapprover"]) ?> >Approver</label></td>
                          <td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_mtopapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_mtopapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_mtopapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_mtopapprover"]) ?> >Yes</label></td>
			  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_pmrapprover"]) ?> >Approver</label></td>
                          <td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pmrapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_pmrapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_pmrapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_pmrapprover"]) ?> >Yes</label></td>
			  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_bldgapprover"]) ?> >Approver</label></td>
                          <td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bldgapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bldgapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bldgapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bldgapprover"]) ?> >Yes</label></td>
		  </tr>
			
			<tr class="fillerRow10px">
				<td ></td>
				<td></td>
				<td></td>
				<td></td>
				<td ></td>
				<td></td>
			</tr>
		</table>
  </div>
</div>

	
<?php $page->resize->addudftabpage("tabudfpo",0,0); ?>	