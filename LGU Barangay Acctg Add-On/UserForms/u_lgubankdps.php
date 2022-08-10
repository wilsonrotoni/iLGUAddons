<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td >&nbsp;</td>
		<td  align=left>&nbsp;</td>
		<td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
		<td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>	
    <tr>
      <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
        <td >&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
        <td width="168">&nbsp;</td>
        <td width="168">&nbsp;</td>
    </tr>
    <tr>
      <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bank") ?>>Bank</label></td>
        <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bank",array("loadhousebanksbycountry",$_SESSION["branch"].":PH",":")) ?>></select></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevseries") ?>>JEV Series</label></td>
						<td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_jevseries") ?>></select></td>
    </tr>
    <tr>
      <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_bankacctno") ?>>Bank Acct No.</label></td>
        <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bankacctno",array("loadhousebankaccounts",$_SESSION["branch"].":PH:".$page->getitemstring("u_bank"),"")) ?>></select></td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_jevno") ?>>JEV No.</label></td>
						<td><?php if($page->getitemstring("docstatus")!="D") genLinkedButtonHtml("u_jevno","","OpenLnkBtnJournalVouchers()"); else echo "&nbsp;"; ?><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_jevno") ?>/></td>
    </tr>
    <tr>
      <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_amount") ?>>Amount</label></td>
        <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_amount") ?>/></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td >&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_cashglacctno") ?>>G/L Account No.</label></td>
	  <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_cashglacctno") ?>/>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_cashglacctname") ?>/></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
       <tr>
          <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
            <td rowspan="3">&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_remarks") ?> rows="2" cols="50"><?php echo $page->getitemstring("u_remarks"); ?></TEXTAREA></td>
          <td width="168">&nbsp;</td>
            <td width="240">&nbsp;</td>
        </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	</table>
</td></tr>	
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

