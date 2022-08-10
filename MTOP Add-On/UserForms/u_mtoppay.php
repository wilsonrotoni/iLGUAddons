<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                    <td><label <?php $page->businessobject->items->draw->caption("docno") ?> >No.</label></td>
                    <td width="109">&nbsp;</td> 
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_assdate") ?>>Date</label></td>
                    <td><label <?php $page->businessobject->items->userfields->draw->caption("u_year") ?>>Year</label></td>
                    
                    
                    <td>&nbsp;</td>
                  
            </tr>
           
            <tr>
                <td colspan="2" width = "100">&nbsp;&nbsp;<select <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select>&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                    <td width="100">&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_assdate") ?>/></td>
                    <td width="100">&nbsp;<input type="text" size="5" <?php $page->businessobject->items->userfields->draw->text("u_year") ?>/></td>
                    <td>&nbsp;</td>
                    <td >&nbsp;</td>
            </tr>
             <tr>
                    <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_operatorname") ?>>Operator Name</label></td>
                    <td width="249">&nbsp;</td>
                    <td width="249"><label <?php $page->businessobject->items->userfields->draw->caption("u_totalamount") ?>>Total Amount</label></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            </tr>
             <tr>
                 <td width="249">&nbsp;&nbsp;<input type="text" size ="30" <?php $page->businessobject->items->userfields->draw->text("u_operatorname") ?>></td>
                    <td width="249">&nbsp;</td>
                     <td width="249">&nbsp;<input type="text" size ="15" <?php $page->businessobject->items->userfields->draw->text("u_totalamount") ?>></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                    <td width="249">&nbsp;<a class="button" href="" onClick="formSearch();return false;">Search</a> &nbsp;<input type="text" size ="22" <?php $page->businessobject->items->userfields->draw->text("u_declaredowner") ?>> </td>
                    <td align=left></td>
                    <td align=left></td>
            </tr>
	</table>
        <?php $objGrids[0]->draw(true); ?>
</td></tr>	
<tr><td></td></tr>		
<?php // $page->resize->addtab("tab1",-1,141); ?>
<?php // $page->resize->addtabpage("tab1","udf") ?>
		
<?php $page->resize->addgridobject($objGrids[0],0,335) ?>
		

