<div class="tabbertab" title="General (UDF Bayambang)">
	<div id="divtabudfpo" style="overflow:auto;">
		<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
			  <td><label class="lblobjs" ><b>Purchase Request</b></label></td>
			  <td >&nbsp;</td>
			  <td ></td>
			  <td >&nbsp;</td>
			  <td>&nbsp;</td>
			  <td >&nbsp;</td>
                    </tr>
                    <tr>
                            <td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_treasury"]) ?> >Treasury Office</label></td>
                            <td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_treasury"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_treasury"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_treasury"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_treasury"]) ?> >Yes</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td >&nbsp;</td>
                    </tr>
                    <tr>
                            <td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_budget"]) ?> >Budget Office</label></td>
                            <td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_budget"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_budget"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_budget"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_budget"]) ?> >Yes</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td >&nbsp;</td>
                    </tr>
                    <tr>
                            <td width="188"><label <?php genCaptionHtml($page->udfs["header"][""]["u_goodsissueapprove"]) ?> >Goods Issue Approve</label></td>
                            <td width="188"><input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_goodsissueapprove"],"0"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_goodsissueapprove"]) ?> >No</label>&nbsp;<input type="radio" <?php genInputRadioHtml($page->udfs["header"][""]["u_goodsissueapprove"],"1"); ?>  /><label <?php genOptionCaptionHtml($page->udfs["header"][""]["u_goodsissueapprove"]) ?> >Yes</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td >&nbsp;</td>
                    </tr>
			
		</table>
  </div>
</div>

	
<?php $page->resize->addudftabpage("tabudfpo",0,0); ?>	