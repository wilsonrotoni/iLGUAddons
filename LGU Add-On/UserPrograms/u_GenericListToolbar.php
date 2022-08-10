<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> </tr>
  <tr >
    <td colspan="9">
	<?php if ($httpVars["formNoPrint"]=="") { ?>	
	<div align="left"><a id="btnPrint" name="btnPrint" class="buttonDDT" href="" onClick="<?php echo @$toolbarframe ?>OpenReportSelect();return false;" >Preview</a><a id="btnPrintDD" name="btnPrintDD"  class="buttonDD" href="" onClick="<?php echo @$toolbarframe ?>formPrintTo(this);return false;" >&nbsp;&nbsp;&nbsp;</a>
<!--	
	<a class="button" href="" onclick="<?php echo @$toolbarframe ?>deleteTableRow('T1');return false;">Delete</a>
	-->
	</div>
	<?php } ?>	
	</td>
  </tr>
</table>