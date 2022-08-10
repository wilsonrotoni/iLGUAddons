<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
	  <td >&nbsp;</td>
	  <td  align=left>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","LGU") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>LGU</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","Company") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>Company</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_type","Account") ?>/><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_type") ?>>Account</label></td>
	  </tr>
		<tr><td width="168"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="80"><label <?php $page->businessobject->items->draw->caption("code") ?> > Code</label></td><td align="right"><?php if($page->settings->data["autogenerate"]==1) { ?><select <?php $page->businessobject->items->userfields->draw->select("u_series",array("loaddocseries",$page->objectcode,"-1:Manual"),null,null,null,"width:108px") ?> ></select><?php } ?></td></tr></table></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> /></td>
	    </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->draw->caption("name") ?>>Name</label></td>
		  <td>&nbsp;<input type="text" size="52" <?php $page->businessobject->items->draw->text("name") ?> /></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_glacctno") ?>>G/L Acct No.</label></td>
		<td  align=left>&nbsp;<input type="text" <?php $page->businessobject->items->userfields->draw->text("u_glacctno") ?>/></td>
	  </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_glacctname") ?>>G/L Acct Name</label></td>
		<td  align=left>&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_glacctname") ?>/></td>
	  </tr>
	</table>
</td></tr>	
<?php // $page->resize->addtab("tab1",-1,141); ?>
<?php // $page->resize->addtabpage("tab1","udf") ?>
		

