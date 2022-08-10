<!--<div <?php // genPopWinHDivHtml("popupFrameCopyFrom", "List of Purchase Request", 200, 800, 800, false) ?>>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr><td width="10">&nbsp;</td>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr><td colspan="4">
                            <div id = "doc" class="doc" title="Documents">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr><td><label class="lblobjs"><b>Documents</b></label></td></tr>
                                    <tr class="fillerRow5px"><td></td></tr>
                                    <tr>
                                        <td>
                                            <?php // $objGridDetailDocno->draw(false); ?>	  
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="items" class="items" title="Items">
                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                    <tr><td><label class="lblobjs"><b>Items</b></label></td></tr>
                                    <tr class="fillerRow5px"><td></td></tr>
                                    <tr>
                                        <td>
                                            <?php // $objGridDetail->draw(false); ?>	 	  
                                        </td>
                                    </tr>
                                </table> 
                            </div>
                        </td></tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    <tr><td >&nbsp;</td>
                        <td align="right"><a id="Next" class="button" href="" onClick="setNextGPSLGUPurchasing();return false;" >Choose</a>&nbsp;<a id="Prev" class="button" href="" onClick="setPrevGPSLGUPurchasing();return false;" >< Back</a>&nbsp;<a id="OK" class="button" href="" onClick="getCFAccessGPSLGUPurchasing();return false;" >Finish</a>&nbsp;<a id="Close" class="button" href="" onClick="closepopupframeGPSLGUPurchasing();return false;" >Cancel</a></td> 
                    </tr>
                    <tr class="fillerRow5px"><td colspan="2"></td></tr>
                    
                </table>
            </td>
            <td width="10">&nbsp;</td>
        </tr>
    </table>
</div>-->

<div <?php genPopWinHDivHtml("popupFrameAddEditRemarks","Add/Edit Remarks",50,50,550,false) ?>>
<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr class="fillerRow5px"><td colspan="3"></td></tr>
<tr><td width="10">&nbsp;</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td valign="top"><label <?php genCaptionHtml(createSchema("remarks"),"T51") ?> >Remarks</label></td>
			<td >&nbsp;<TEXTAREA <?php genTextAreaHtml(createSchema("remarks"),"T51") ?>  rows="10" cols="60"></TEXTAREA></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a class="button" href="" onClick="setRemarksGPSLGUPurchasing();return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>
