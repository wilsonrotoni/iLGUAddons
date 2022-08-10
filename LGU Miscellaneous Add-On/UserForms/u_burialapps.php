<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size = "15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_custname") ?>>Payer Name</label></td>
            <td >&nbsp;<input type="text" size="50"  <?php $page->businessobject->items->userfields->draw->text("u_custname") ?>/></td>
            <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
            <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
             <td width="168"></td>
            <td>&nbsp;</td>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docdate") ?>>Application Date</label></td>
          <td>&nbsp;<input type="text" size="15"  <?php $page->businessobject->items->userfields->draw->text("u_docdate") ?>/></td>
	  </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_name") ?>>Name</label></td>
                <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_name") ?>/></td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
        </tr>
        <tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_nationality") ?>>Nationality</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_nationality") ?>/></td>
	   <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>	
        <tr>
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_age") ?>>Age</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_age") ?>/></td>
	   <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
	    <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_gender") ?>>Gender</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_gender") ?>></select></td>
            <td width="168"></td>
            <td>&nbsp;</td>
        </tr>	
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_dateofdeath") ?>>Date of Death</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_dateofdeath") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_causeofdeath") ?>>Cause of Death</label></td>
                <td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_causeofdeath") ?>rows="2" cols="50"><?php echo $page->getitemstring("u_causeofdeath"); ?></TEXTAREA></td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_cemeteryname") ?>>Cemetery Name</label></td>
                <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_cemeteryname") ?>/></td>
            <td >&nbsp;</td>
            <td >&nbsp;</td>
        </tr>
	
         <tr> <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_docseries") ?>>Official Receipt Series</label></td>
            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_docseries", array("loadu_lguposterminalseries",$_SESSION["userid"],":")) ?>></select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_orno") ?>>Official Receipt No.</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_orno") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ordate") ?>>Official Receipt Date</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ordate") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px"><td colspan="2"></td></tr>
        <tr><td width="168"></td>
            <td  colspan="3">&nbsp;&nbsp;<?php $objGrids[0]->draw(true); ?></td>
            <td>&nbsp; </td>
            <td >&nbsp;</td>
        </tr>
	</table>
</td></tr>	
 <?php $page->resize->addgridobject($objGrids[0], 800, 550) ?>
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

