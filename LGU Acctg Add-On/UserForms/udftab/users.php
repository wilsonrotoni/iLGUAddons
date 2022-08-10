<div class="tabbertab" title="General (UDF)">
	<div id="divtabudf" style="overflow:auto;">
		<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr>
			  <td><label class="lblobjs" ><b>Budget Request</b></label></td>
			  <td >&nbsp;</td>
			  <td ><label class="lblobjs" ><b>Cash Receipt</b></label></td>
			  <td >&nbsp;</td>
			  <td>&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_budgetencoder"]) ?> >Encoder</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_budgetencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_budgetencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_budgetencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_budgetencoder"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_cashrencoder"]) ?> >Encoder</label></td>
				<td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashrencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashrencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashrencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashrencoder"]) ?> >Yes</label></td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_budgetapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_budgetapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_budgetapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_budgetapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_budgetapprover"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_cashrapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashrapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashrapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashrapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashrapprover"]) ?> >Yes</label></td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
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
			  <td ><label class="lblobjs" ><b>Obligation Request</b></label></td>
			  <td >&nbsp;</td>
			  <td ><label class="lblobjs" ><b>Bank Deposit</b></label></td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_obrencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_obrencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_obrencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_obrencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_obrencoder"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_bankdepositencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bankdepositencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bankdepositencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bankdepositencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bankdepositencoder"]) ?> >Yes</label></td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_obrapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_obrapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_obrapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_obrapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_obrapprover"]) ?> >Yes</label></td>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_bankdepositapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bankdepositapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bankdepositapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_bankdepositapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_bankdepositapprover"]) ?> >Yes</label></td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr class="fillerRow10px">
				<td ></td>
				<td></td>
				<td></td>
				<td></td>
				<td ></td>
				<td></td>
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
			  <td ><label class="lblobjs" ><b>Check Disbursement</b></label></td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_checkdencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_checkdencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_checkdencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_checkdencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_checkdencoder"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_checkdapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_checkdapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_checkdapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_checkdapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_checkdapprover"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
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
			  <td ><label class="lblobjs" ><b>Cash Disbursement</b></label></td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_cashdencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashdencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashdencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashdencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashdencoder"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_cashdapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashdapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashdapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashdapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashdapprover"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr class="fillerRow10px">
				<td ></td>
				<td></td>
				<td></td>
				<td></td>
				<td ></td>
				<td></td>
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
			  <td ><label class="lblobjs" ><b>Funds Voucher</b></label></td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_fundencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fundencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fundencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fundencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fundencoder"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_fundapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fundapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fundapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_fundapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_fundapprover"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr class="fillerRow10px">
				<td ></td>
				<td></td>
				<td></td>
				<td></td>
				<td ></td>
				<td></td>
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
			  <td ><label class="lblobjs" ><b>A/P Voucher</b></label></td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_apencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_apencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_apencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_apencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_apencoder"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_apapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_apapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_apapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_apapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_apapprover"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
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
			  <td ><label class="lblobjs" ><b>Journal Voucher</b></label></td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_jvencoder"]) ?> >Encoder</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_jvencoder"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_jvencoder"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_jvencoder"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_jvencoder"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_jvapprover"]) ?> >Approver</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_jvapprover"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_jvapprover"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_jvapprover"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_jvapprover"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
				<td>&nbsp;</td>
			  <td >&nbsp;</td>
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

	
<?php $page->resize->addudftabpage("tabudf",0,0); ?>	