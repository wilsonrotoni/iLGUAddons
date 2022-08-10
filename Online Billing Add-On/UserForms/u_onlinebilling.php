<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" >&nbsp;</td>
		<td align=left>&nbsp;</td>
		<td width="168" ><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="40"><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td><td align="right"><select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->objectcode,"-1:Manual")) ?> ></select></td></tr></table></td>
		<td width="168" align=left>&nbsp;<input type="text" size = "15" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
	</tr>
	<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_module") ?>>Module</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_module") ?>></select></td>
            <td ><label <?php $page->businessobject->items->draw->caption("docstatus") ?> >Status</label></td>
            <td align=left>&nbsp;<select <?php $page->businessobject->items->draw->select("docstatus",array("loadenumdocstatus","","")) ?> ></select></td>
	</tr>
	<tr>
             <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_custname") ?>>Customer Name</label></td>
            <td>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_custname") ?>/></td>
          
	  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date</label></td>
          <td>&nbsp;<input type="text" size="15"  <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/></td>
	  </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email Address</label></td>
            <td>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
	<tr>
            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_alertmobileno") ?>>Alert Mobile No.</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_alertmobileno") ?>/></td>
	   <td width="168"></td>
            <td>&nbsp;</td>
	  </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>/></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr class="fillerRow5px"><td colspan="2"></td></tr>
	</table>
</td></tr>	
 <?php // $page->resize->addgridobject($objGrids[0], 800, 550) ?>
<?php //$page->resize->addtab("tab1",-1,161); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
		

