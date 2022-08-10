<?php

function loadu_pf($p_args = array(),$p_selected) {
	global $objConnection;
	$_html = "";	
	$_selected = "";
	$obj = new recordset(NULL,$objConnection);
	$obj->queryopen("SELECT b.code,b.name FROM u_premploymentdeployment a INNER JOIN u_prprofitcenter b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_branch WHERE a.code = '".$_SESSION["userid"]."'");
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$obj->queryclose();
	echo @$_html;
}

function loadu_tr($p_args = array(),$p_selected) {
	global $objConnection;
	$_html = "";	
	$_selected = "";
	$obj = new recordset(NULL,$objConnection);
	$obj->queryopen("SELECT code,name FROM tktypeofrequestnote");
		if ($obj->recordcount() > 0) {
			while ($obj->queryfetchrow()) {
				$_selected = "";
				if ($p_selected == $obj->fields[0]) $_selected = "selected";
				$_html = $_html . "<option " . $_selected . " value=\"" . $obj->fields[0]  . "\">" . $obj->fields[1] . "</option>";
			}
		}	
		$obj->queryclose();
	echo @$_html;
}

?>

<tr><td>
	<table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr><td width="168"><label <?php $page->businessobject->items->draw->caption("code") ?>>Application No.</label></td>
			<td >&nbsp;<input type="text" <?php $page->businessobject->items->draw->text("code") ?> />
			<input type="hidden" size="50" <?php $page->businessobject->items->draw->text("name") ?> />
			<input type="hidden" size="20" <?php $page->businessobject->items->userfields->draw->text("u_status") ?>/></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Profit Center</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter",array("loadu_pf",$page->getitemstring("u_profitcenter"),":")) ?>></select></td>
		</tr>
		<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_empid") ?>>ID & Name</label></td>
			<td>&nbsp;<input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_empid") ?>/>
			&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_empname") ?>/></td>
		</tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		
		<div class="tabbertab" title="General">
			<div id="divudf" style="overflow:auto;">
				<table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Application Date</label></td>
						<td>&nbsp;<input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
					</tr>
					
					
				
					<tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_note") ?>>Note</label></td>
						<td >&nbsp;<TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_note") ?>rows="2" cols="50"><?php echo $httpVars['df_u_note'] ?></TEXTAREA></td>
					</tr>
                    
                    <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_typeofrequest") ?>>Profit Center</label></td>
			<td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_typeofrequest",array("loadu_tr",$page->getitemstring("u_typeofrequest"),":")) ?>></select></td>
					</tr>
		
				</table>
			</div>
		</div>
	</div>
</td></tr>		
<?php $page->resize->addtab("tab1",-1,141); ?>
<?php $page->resize->addtabpage("tab1","udf") ?>
		

