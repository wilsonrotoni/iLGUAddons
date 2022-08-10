<div <?php genPopWinHDivHtml("popupFrameCopyFrom","Copy From Purchase Request",200,800,800,false) ?>>
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
			                <?php $objGridDetailDocno->draw(false); ?>	  
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
			                <?php $objGridDetail->draw(false); ?>	 	  
                        </td>
                    </tr>
                 </table> 
            </div>
        </td></tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
		<tr><td >&nbsp;</td>
			<td align="right"><a id="Next" class="button" href="" onClick="setNextGPSPPSB();return false;" >Next</a></td>
            <td align="right"><a id="Prev" class="button" href="" onClick="setPrevGPSPPSB();return false;" >Prev</a></td>
            <td align="right"><a id="OK" class="button" href="" onClick="getCFAccessGPSPPSB();return false;" >OK</a></td>
		</tr>
		<tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td>
<td width="10">&nbsp;</td>
</tr>
</table>
</div>