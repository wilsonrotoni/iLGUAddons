<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
	  <tr>
	    <td >&nbsp;</td>
	   <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_billing",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_billing") ?>>Cash Only</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_billing",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_billing") ?>>Billing Statement</label></td>
	    <td >&nbsp;</td>
	    <td align=left>&nbsp;</td>
      </tr>
	  <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_reftype") ?>>Payment Term</label></td>
	    <td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->draw->text("code") ?>/></td>
	    <td width="168" >&nbsp;</td>
	    <td width="168" align=left>&nbsp;</td>
      </tr>
	<tr>
	  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_patientid") ?>>Description</label></td>
	  <td  align=left>&nbsp;<input type="text" size="30" <?php $page->businessobject->items->draw->text("name") ?>/></td>
	  <td >&nbsp;</td>
	  <td align=left>&nbsp;</td>
	  </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td >&nbsp;</td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
	  </tr>
<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prepaidmedsup") ?>>Medicines & Supplies</label></td>
	    <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidmedsup",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidipmedsup") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidmedsup",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidmedsup") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidmedsup",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidmedsup") ?>>Downpayment</label></td>
		  <td  align=left>&nbsp;</td>
		  <td  align=left>&nbsp;</td>
	    </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_prepaidsplroom") ?>>Special Rooms</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidsplroom",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidsplroom") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidsplroom",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidsplroom") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidsplroom",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidsplroom") ?>>Downpayment</label></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_prepaidlab") ?>>Examinations</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidlab",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidlab") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidlab",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidlab") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidlab",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidlab") ?>>Downpayment</label></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>				  
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_prepaidmisc") ?>>Miscellaneous</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidmisc",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidmisc") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidmisc",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidmisc") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidmisc",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidmisc") ?>>Downpayment</label></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>				  
					<tr>
					  <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_prepaidpf") ?>>Professional Fees</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidpf",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidpf") ?>>Charged</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidpf",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidpf") ?>>Cash</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_prepaidpf",2) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_prepaidpf") ?>>Downpayment</label></td>
					  <td  align=left>&nbsp;</td>
					  <td  align=left>&nbsp;</td>
				  </tr>
	</table>
</td></tr>	
