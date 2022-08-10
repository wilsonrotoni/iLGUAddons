<tr><td>
	<div id="divsetup" style="overflow:auto;">
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		
		<tr>
		  <td width="300" ><label <?php $page->businessobject->items->userfields->draw->caption("u_dfltreg") ?>>Default Registration</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_dfltreg","IP") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_dfltreg","IP") ?>>In-Patient</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_dfltreg","OP") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_dfltreg","OP") ?>>Out-Patient</label></td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
	    </tr>
		<tr>
		  <td width="300" ><label <?php $page->businessobject->items->userfields->draw->caption("u_pharmapos") ?>>Medicines & Supplies Cash Sales Payments</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_pharmapos",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pharmapos") ?>>Pharmacy</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_pharmapos",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_pharmapos") ?>>Cashier</label></td>
	      <td width="300" ><label class="lblobjs"><b>Dietary Request Cut-Off:</b></label></td>
	      <td width="168" >&nbsp;</td>
	  </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_dfltpharmatfs") ?>>Default Medicines & Supplies Transfers</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_dfltpharmatfs",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_dfltpharmatfs") ?>>In-Transit</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_dfltpharmatfs",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_dfltpharmatfs") ?>>Direct</label></td>
	      <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dietary_cutoff1") ?>>Breakfast</label></td>
	      <td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_dietary_cutoff1") ?> /></td>
	  </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_opdpricelist") ?>>Out-Patient Price List</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_opdpricelist",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dietary_cutoff2") ?>>Lunch</label></td>
	      <td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_dietary_cutoff2") ?> /></td>
	  </tr>
		<tr>
	      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_medsupmarkup") ?>>Medicines & Supplies Markup</label></td>
	      <td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_medsupmarkup") ?> /></td>
	      <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dietary_cutoff3") ?>>Dinner</label></td>
	      <td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_dietary_cutoff3") ?> /></td>
	  </tr>
		<tr>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td ><label class="lblobjs"><b>Default Sections:</b></label></td>
		  <td >&nbsp;</td>
	      <td ><label class="lblobjs"><b>Default Payment Terms:</b></label></td>
	      <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltipd") ?>>In-Patient</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltipd",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltoppayterm") ?>>Out-Patient</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltoppayterm",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltopd") ?>>Out-Patient</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltopd",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltippayterm") ?>>In-Patient</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltippayterm",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltcsrdept") ?>>Central Supply</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltcsrdept",null,null,null,null,"width:158px") ?> /></select></td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltpharmadept") ?>>Pharmacy</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltpharmadept",null,null,null,null,"width:158px") ?> /></select></td>
	      <td ><label class="lblobjs"><b>Default Discount:</b></label></td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltlabdept") ?>>Laboratory</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltlabdept",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltopdisccode") ?>>Out-Patient</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltopdisccode",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltradiodept") ?>>Radiology</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltradiodept",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltipdisccode") ?>>In-Patient</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltipdisccode",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltheartstationdept") ?>>Heart Station</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltheartstationdept",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;</td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltdietarydept") ?>>Dietary</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltdietarydept",null,null,null,null,"width:158px") ?> /></select></td>
	      <td ><label class="lblobjs"><b>Senior Citizen Discount:</b></label></td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltpulmonarydept") ?>>Pulmonary</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltpulmonarydept",null,null,null,null,"width:158px") ?> /></select></td>
		  <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_scdiscpricelist") ?>>Price List</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_scdiscpricelist",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dflthousekeepingdept") ?>>Housekeeping</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dflthousekeepingdept",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_scdiscperc") ?>>Discount (%)</label></td>
	      <td >&nbsp;<input type="text" size="7" <?php $page->businessobject->items->userfields->draw->text("u_scdiscperc") ?> /></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_dfltwarehousedept") ?>>Warehouse</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_dfltwarehousedept",null,null,null,null,"width:158px") ?> /></select></td>
	      <td >&nbsp;</td>
	      <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td height="26" >&nbsp;</td>
		  <td >&nbsp;</td>
	      <td >&nbsp;</td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td ><label class="lblobjs"><b>Medicines & Supplies Charging:</b></label></td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_medsupchrgpharma") ?>>Medicines</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_medsupchrgpharma",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_medsupchrgpharma") ?>>Nursing Service</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_medsupchrgpharma",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_medsupchrgpharma") ?>>Pharmacy</label></td>
	      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_doctorpayablecutoff") ?>>Doctor's Payable Cut-Off</label></td>
	      <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_doctorpayablecutoff") ?> /></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_medsupchrgcsr") ?>>Supplies</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_medsupchrgcsr",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_medsupchrgcsr") ?>>Nursing Service</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_medsupchrgcsr",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_medsupchrgcsr") ?>>Central Supply</label></td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_posarcashcard") ?>>Cash Card - A/R</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_posarcashcard",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td >&nbsp;&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_medsupchrglab") ?>>Blood</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_medsupchrglab",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_medsupchrglab") ?>>Nursing Service</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_medsupchrglab",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_medsupchrglab") ?>>Laboratory</label></td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bnkcmpfcashcard") ?>>Cash Card - Direct PF</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bnkcmpfcashcard",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
	      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bnkcmdccashcard") ?>>Cash Card - Discount</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bnkcmdccashcard",null,null,null,null,"width:158px") ?> /></select></td>
	  </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_billasone") ?>>Billing Generation</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_billasone",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_billasone") ?>>Bill As One</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_billasone",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_billasone") ?>>Hospital & PF Fees</label></td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bnkcmwtcashcard") ?>>Cash Card - W/Tax</label></td>
		  <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bnkcmwtcashcard",null,null,null,null,"width:158px") ?> /></select></td>
	    </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_stocklink") ?>>Linked to Stocks</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_stocklink",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_stocklink") ?>>No</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_stocklink",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_stocklink") ?>>Yes</label></td>
	      <td >&nbsp;</td>
	      <td >&nbsp;</td>
	  </tr>
		<tr>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_phavatpos") ?>>Pharmacy POS - Vatable</label></td>
		  <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_phavatpos",0) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_phavatpos") ?>>No</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_phavatpos",1) ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_phavatpos") ?>>Yes</label></td>
		  <td ><label class="lblobjs"><b>Queueing:</b></label></td>
		  <td >&nbsp;<input type="checkbox" size="20" <?php $page->businessobject->items->userfields->draw->checkbox("u_queueinggenlink",1) ?> /><label <?php $page->businessobject->items->userfields->draw->caption("u_queueinggenlink") ?>>Link Generator</label></td>
	    </tr>
		<tr>
		  <td >&nbsp;</td>
		  <td >&nbsp;</td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_queueingprinter") ?>>Printer</label></td>
	      <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_queueingprinter") ?> /></td>
	    </tr>
		<tr>
		  <td ><label class="lblobjs"><b>Laboratory Information System (LIS):</b></label></td>
		  <td >&nbsp;</td>
		  <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_queingfilepath") ?>>Display Video</label></td>
	      <td >&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_queingfilepath") ?> /></td>
	  </tr>
		<tr>
	      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_lissenddir") ?>>Sending Folder</label></td>
	      <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lissenddir") ?> /></td>
	      <td >&nbsp;</td>
	      <td >&nbsp;</td>
	  </tr>
		<tr>
	      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_lisrecvdir") ?>>Receiving Folder</label></td>
	      <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lisrecvdir") ?> /></td>
	      <td >&nbsp;</td>
	      <td >&nbsp;</td>
	  </tr>
		<tr>
	      <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_lislogsdir") ?>>Logs Folder</label></td>
	      <td >&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_lislogsdir") ?> /></td>
	      <td >&nbsp;</td>
	      <td >&nbsp;</td>
	  </tr>
	</table>
	</div>
</td></tr>
<?php $page->resize->addelement("divsetup",20,60) ?>	
	
<?php //$page->resize->addtab("tab1",-1,181); ?>
<?php //$page->resize->addgridobject($objGrids[0],20,240) ?>		

