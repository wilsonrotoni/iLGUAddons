<div class="tabbertab" title="General (UDF)">
	<div id="divtabudf" style="overflow:auto;">
		<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
			<tr>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_medtechflag"]) ?> >Medical Technologist</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_medtechflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_medtechflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_medtechflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_medtechflag"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_cashierflag"]) ?> >Cashier</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashierflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashierflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cashierflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cashierflag"]) ?> >Yes</label></td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_radtechflag"]) ?> >Radiologist Technologist</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_radtechflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_medtechflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_radtechflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_radtechflag"]) ?> >Yes</label></td>
				<td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_cancelpayflag"]) ?> >Cancel In/Out Payment</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cancelpayflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cancelpayflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cancelpayflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cancelpayflag"]) ?> >Yes</label></td>
			</tr>
			<tr>
				<td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_cardiacsonographerflag"]) ?> >Cardiac Sonographer</label></td>
				<td ><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cardiacsonographerflag"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cardiacsonographerflag"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_cardiacsonographerflag"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_cardiacsonographerflag"]) ?> >Yes</label></td>
				<td >&nbsp;</td>
				<td >&nbsp;</td>
			</tr>
			<tr>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
		  </tr>
			<tr>
			  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_position"]) ?> >Position</label></td>
			  <td >&nbsp;<input type="text" size="45" <?php genInputTextHtml($page->udfs["header"][""]["u_position"],""); ?>  /></td>
			  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_inpayseries"]) ?> >Incoming Payment Series</label></td>
			  <td >&nbsp;<select <?php genSelectHtml($page->udfs["header"][""]["u_inpayseries"],array("loadudflinktable","docseries:docseries:docseriesname:doctype='INCOMINGPAYMENT'",":")); ?>  /></select></td>
		  </tr>
			<tr>
			  <td >&nbsp;</td>
			  <td >&nbsp;</td>
			  <td ><label <?php genCaptionHtml($page->udfs["header"][""]["u_outpayseries"]) ?> >Outgoing Payment Series</label></td>
			  <td >&nbsp;<select <?php genSelectHtml($page->udfs["header"][""]["u_outpayseries"],array("loadudflinktable","docseries:docseries:docseriesname:doctype='OUTGOINGPAYMENT'",":")); ?>  /></select></td>
		  </tr>
			
			<tr class="fillerRow10px">
				<td ></td>
				<td></td>
				<td ></td>
				<td></td>
			</tr>
		</table>
  </div>
</div>

	
<?php $page->resize->addudftabpage("tabudf",0,0); ?>	