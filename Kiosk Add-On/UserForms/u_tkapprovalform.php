<?php

function loadu_app($p_args = array(),$p_selected) {
		global $objConnection;
		$_html = "";	
		$_selected = "";
		$obj = new recordset(NULL,$objConnection);
		$obj->queryopen("SELECT a.u_stageno,b.name FROM u_tkapproverfileassigned a INNER JOIN u_premploymentinfo b ON b.company = a.company AND b.branch = a.branch AND b.code = a.u_stagename WHERE a.u_stagename = '".$_SESSION["userid"]."'");
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
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_profitcenter") ?>>Cost Center</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_profitcenter") ?>></select>
            	&nbsp;<input type="hidden" <?php $page->businessobject->items->draw->text("code") ?> />
            	&nbsp;<input type="hidden" <?php $page->businessobject->items->draw->text("name") ?> /></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_approverby") ?>>Approval By</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_approverby",array("loadu_app","",":")) ?>></select></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_typeofrequest") ?>>Type of Request</label></td>
            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_typeofrequest") ?>></select></td>
        </tr>
        <tr><td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_date") ?>>Date :</label></td>
            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_date") ?>/>
            	&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_dayname") ?>/></td>
        </tr>
	</table>
</td></tr>	
<tr><td>
	<div class="tabber" id="tab1">
		<div class="tabbertab" title="Employee List">	
			<?php $objGrids[0]->draw(true) ?>	  
		</div>
	</div>
</td></tr>	
<?php $page->resize->addgridobject($objGrids[0],20,240) ?>

