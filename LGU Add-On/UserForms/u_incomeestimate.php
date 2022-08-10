<?php include_once("./sls/enumyear.php");?>
<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168" ><label <?php $page->businessobject->items->draw->caption("u_year") ?> >Year</label></td>
                    <td align=left>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_year",array("loadenumyear","",":[Select]")) ?>/></select></td>
		</tr>
                <tr class="fillerRow5px">
					  <td ></td>
					  <td></td>
		  </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Items">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>		
<?php //$page->resize->addtab("tab1",-1,141); ?>
<?php //$page->resize->addtabpage("tab1","udf") ?>
<?php $page->resize->addgridobject($objGrids[0],20,200) ?>		
