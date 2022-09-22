
<tr><td>
        
        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                    <tr>
                        <td width="143"><label <?php $page->businessobject->items->userfields->draw->caption("u_pin") ?>>Application Type</label></td>
                        <td colspan="2"><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype", "NEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>New</label>&nbsp;
                                <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype", "RENEW") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Renewal</label>&nbsp;
                                 <input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_apptype", "ADJUSTMENT") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_apptype") ?>>Adjustment</label> 
                        </td>
                        <td width="18">&nbsp;</td>
                        <td width="4">&nbsp;</td>
                        <td width="22">&nbsp;</td>
                        <td ><label <?php genCaptionHtml($schema["regdate"],"") ?>>Registration Date</label></td>
                        <td ><input type="text" size="15" <?php genInputTextHtml($schema["regdate"]) ?> /></td>
                        <td width="109" >&nbsp;&nbsp;<label <?php $page->businessobject->items->draw->caption("docno") ?> >Reference No.</label></td>
                        <td width="220">&nbsp;<select name="select" <?php $page->businessobject->items->draw->select("docseries",array("loaddocseries",$page->getobjectdoctype(),"-1:Manual")) ?> ></select>&nbsp;<input type="text" size="11" <?php $page->businessobject->items->draw->text("docno") ?> /></td>
                    </tr>
                                    
                    <tr>
                        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_appno") ?>>Account Number</label></td>
                        <td colspan="2">&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_appno") ?>/></td>
                        <td >&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_appdate") ?>>Application Date</label></td>
                        <td ><input type="text" size="15" <?php $page->businessobject->items->userfields->draw->text("u_appdate") ?>/></td>
                        <td >&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_onhold",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_onhold") ?>>On-Hold</label></td>
                        <td >&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_retired",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_retired") ?>>Retired</label> &nbsp;<?php if ($page->getitemstring("u_retired") == 1) { ?><?php genLinkedButtonHtml("u_retireddate", "", "u_PopUpRetireGPSBPLS()") ?><input type="text" size="10" <?php $page->businessobject->items->userfields->draw->text("u_retireddate") ?>/> <?php } ?></td>
                    </tr>
                    <tr>
                        <td ></td>
                        <td colspan="2">&nbsp;</td>
                        <td >&nbsp;</td>
                        <td>&nbsp;</td>
                        <td ></td>
                        <td ></td>
                        <td ></td>
                        <td colspan="2">&nbsp;&nbsp;<input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_isonlineapp",1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_isonlineapp") ?>>Online Application</label></td>
                    </tr>
             </table>  
        <div class="tabber" id="tab1" >  
<!--            <div class="tabbertab" title="General">
                <div id="divudf" style="overflow:auto;">
                  
                </div>
            </div>            -->
<div class="tabbertab" title="Owner/Business Details">
                <div id="divudf" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr><td colspan="2"><label class="lblobjs"><b>Name of Tax Payer</b></label></td>
                            <td ><label class="lblobjs"><b>Business Address</b></label>
                            <td >Google Map View&nbsp;<img id="GoogleMap" height=19 src="../Addons/GPS/BPLS Add-On/UserPrograms/Images/googlemap.jpg" height=20 width=20 align="absmiddle" border=0 onClick="u_googlemapGPSBPLS()"></td>
                            <td colspan="2"><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype", "Commercial") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Commercial</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_bunittype", "Residential") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_bunittype") ?>>Residential</label></td>
                        </tr>
                        <tr><td width="106"><label <?php $page->businessobject->items->userfields->draw->caption("u_lastname") ?>>Last Name</label></td>
                            <td width="450"><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_lastname") ?>/></td>
                            <td width="106"><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Barangay</label></td>
                            <td width="200"><select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy",null,null,null,null, "width:150px") ?>></select></td>
                            <td width="106"><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
                            <td width="200"><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bvillage") ?>/></td>
                            <td rowspan="6"  align="center"><?php if ($photopath != "") { ?><img id="PhotoImg" height=120 src="<?php echo $photopath; ?>" width=120 align="absmiddle" border=1 onDblClick="uploadPhoto()"><?php } ?></td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_firstname") ?>>First Name</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_firstname") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Prefix Name</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/> </td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Building Name</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_middlename") ?>>Middle Name</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_middlename") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bblock") ?>>Block</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bblock") ?>/> </td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_telno") ?>>Telephone No</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_telno") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_blotno") ?>>Lot No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_blotno") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Floor No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bfloorno") ?>/></td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email Address</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Street</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td>
                        </tr>
                        <tr><td colspan="2" ></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bphaseno") ?>>Phase No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bphaseno") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_baddressno") ?>>Address No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_baddressno") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_blandmark") ?>>Land Mark</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_blandmark") ?>/></td>
                             <td align="center"><?php if ($photopath != "") { ?><img id="CameraImg" height=19 src="../Addons/GPS/BPLS Add-On/UserPrograms/Images/camera.jpg" width=20 align="absmiddle" border=0 onClick="takePhoto()"><?php } ?></td>
                        </tr>
                         <tr><td colspan="2" ><label class="lblobjs"><b>Owner Address</b></label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_btelno") ?>>Business Contact No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_btelno") ?>/></td>
                            <td></td>
                            <td></td>
                            <td rowspan="6"  align="center"><?php if ($qrpath != "") { ?><img id="QRImg" height=100 src="<?php echo $qrpath; ?>" width=100 align="absmiddle" border=1 ><?php } ?></td>
                        
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;<textarea rows=1 cols=72 <?php $page->businessobject->items->userfields->draw->text("u_owneraddress") ?>/><?php echo $page->getitemstring("u_owneraddress") ?></textarea></td>
                            <td colspan="4" valign="top">&nbsp;</td>
                             <td align="center"></td>
                        </tr>
                        <tr>
                            <td width="269"><label class="lblobjs"><b>Trade Name/Franchise</b></label></td>
                            <td>&nbsp;</td>
                            <td><label class="lblobjs"><b>Corporation Name</b></label></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_tradename") ?>/></td>
                            <td colspan="3">&nbsp;<input type="text" size="50" <?php $page->businessobject->items->userfields->draw->text("u_corpname") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_regno") ?>>DTI No</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_regdate") ?>>DTI Date</label></td>
                            <td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_secregno") ?>>SEC No</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_secregdate") ?>>SEC Date</label></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_regno") ?>/></td>
                            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_regdate") ?>/></td>
                            <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_secregno") ?>/></td>
                            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_secregdate") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                         <tr>
                            <td  colspan="2"><label class="lblobjs"><b>Business Type</b></label></td>
                            <td  colspan="2"><label class="lblobjs"><b>Business Name</b></label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "SINGLE") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Single</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "PARTNERSHIP") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Partnership</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "CORPORATION") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Corporation</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "COOPERATIVE") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Cooperative</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "FOUNDATION") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Foundation</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_orgtype", "ASSOCIATION") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_orgtype") ?>>Association</label></td>
                            <td colspan="2">&nbsp;<input type="text" size="44" <?php $page->businessobject->items->userfields->draw->text("u_businessname") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        
                        <tr><td colspan="2"><label class="lblobjs"><b>Name of President/Treasurer of Corporation</b></label></td>
                             <td  colspan="2"><label class="lblobjs"><b>Lessor's Name</b></label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;<input type="text" size="44" <?php $page->businessobject->items->userfields->draw->text("u_tlastname") ?>/></td>
                            <td colspan="2">&nbsp;<input type="text" size="44" <?php $page->businessobject->items->userfields->draw->text("u_llastname") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        
<!--                        <tr><td colspan="2"></td>
                            <td><input type="checkbox" <?php $page->businessobject->items->userfields->draw->checkbox("u_sameasowneraddr", 1) ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_sameasowneraddr") ?>>Same as Business Address</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                       
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgname") ?>>Building Name</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bbldgno") ?>>Prefix</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgno") ?>>Building No.</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bldgname") ?>>Building Name</label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgname") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bbldgno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bldgname") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Unit No.</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bstreet") ?>>Street</label></td>
                            <td width="170"><label <?php $page->businessobject->items->userfields->draw->caption("u_unitno") ?>>Unit No.</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Street</label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bunitno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bstreet") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_unitno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_street") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bunitno") ?>>Floor No</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bstreet") ?>>Block</label></td>
                            <td width="170"><label <?php $page->businessobject->items->userfields->draw->caption("u_unitno") ?>>Floor No.</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_street") ?>>Block</label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bfloorno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bblock") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_floorno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_block") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_blotno") ?>>Lot No</label></td>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_bbrgy") ?>>Barangay</label></td>
                            <td width="170"><label <?php $page->businessobject->items->userfields->draw->caption("u_village") ?>>Subdivision</label></td>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_brgy") ?>>Barangay</label></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_blotno") ?>/></td>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bbrgy",null,null,null,null, "width:150px") ?>></select></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_village") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_brgy") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bvillage") ?>>Subdivision</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_bcity") ?>>City</label></td>
                            <td width="170"><label <?php $page->businessobject->items->userfields->draw->caption("u_city") ?>>City</label></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_province") ?>>Province</label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_bvillage",null,null,null,null, "width:150px") ?>></select></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bcity") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_city") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_province") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_btelno") ?>>Tel No.</label></td>
                            <td>&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_bprovince") ?>>Province</label></td>
                            <td width="170"><label <?php $page->businessobject->items->userfields->draw->caption("u_telno") ?>>Tel No.</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_btelno") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_bprovince") ?>/></td>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_telno") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_bemail") ?>>Email Address</label></td>
                            <td>&nbsp;</td>
                            <td width="170"><label <?php $page->businessobject->items->userfields->draw->caption("u_email") ?>>Email Address</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_bemail") ?>/></td>
                            <td colspan="3">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_email") ?>/></td>
                        </tr>
                        <tr><td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_pin") ?>>Property Index No. (PIN)</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_pin") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>-->
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_businessarea") ?>>Business Area (in sqm)</label></td>
                            <td></td>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_monthlyrental") ?>>Monthly Rental</label></td>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_ltelno") ?>>Telephone No</label></td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_businessarea") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_mempcount") ?>>Male</label>&nbsp;<input type="text" size="1" <?php $page->businessobject->items->userfields->draw->text("u_mempcount") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_fempcount") ?>>Female</label>&nbsp;<input type="text" size="1" <?php $page->businessobject->items->userfields->draw->text("u_fempcount") ?>/><label <?php $page->businessobject->items->userfields->draw->caption("u_empcount") ?>>Total</label>&nbsp;<input type="text" size="2" <?php $page->businessobject->items->userfields->draw->text("u_empcount") ?>/></td>
                            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_monthlyrental") ?>/></td>
                            <td>&nbsp;<input type="text" size="18" <?php $page->businessobject->items->userfields->draw->text("u_ltelno") ?>/></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_emplgucount") ?>>No of Employees Residing in LGU</label></td>
                            <td width="170"><label class="lblobjs"><b>Lessor Address</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_emplgucount") ?>/></td>
                            <td colspan="3" valign="top">&nbsp;<textarea rows=1 cols=72 <?php $page->businessobject->items->userfields->draw->text("u_lessoraddress") ?>/><?php echo $page->getitemstring("u_lessoraddress") ?></textarea></td>
                        </tr>
                        
                        <tr><td colspan="2"><label class="lblobjs"><b>Alerts & Notifications</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_alertmobileno") ?>>Mobile No.</label></td>
                            <td>&nbsp;</td>
                            <td width="170">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_alertmobileno") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>            
<!--            <div class="tabbertab" title="Lessor Details">
                <div id="divlessor" style="overflow:auto;">
                    
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr><td colspan="2"><label class="lblobjs"><b>Lessor's Name</b></label></td>
                            <td ><label class="lblobjs"><b>Other Details</b></label>
                            <td ></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr><td width="338"><label <?php $page->businessobject->items->userfields->draw->caption("u_llastname") ?>>Last Name</label></td>
                            <td width="338"><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_llastname") ?>/></td>
                            <td width="300"><label <?php $page->businessobject->items->userfields->draw->caption("u_monthlyrental") ?>>Monthly Rental</label></td>
                            <td width="200"><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_monthlyrental") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_lfirstname") ?>>First Name</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_lfirstname") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_regno") ?>>DTI/SEC/CDA Registration No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_regno") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_lmiddlename") ?>>Middle Name</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_lmiddlename") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_regdate") ?>>DTI/SEC/CDA Registration Date</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_regdate") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_ltelno") ?>>Telephone No</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_ltelno") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcno") ?>>CTC No</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_ctcno") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td><label <?php $page->businessobject->items->userfields->draw->caption("u_lemail") ?>>Email Address</label></td>
                            <td><input type="text" size="35" <?php $page->businessobject->items->userfields->draw->text("u_lemail") ?>/></td>
                            <td><label <?php $page->businessobject->items->userfields->draw->caption("u_tin") ?>>TIN</label></td>
                            <td><input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_tin") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td colspan="2" ><label class="lblobjs"><b>Lessor Address</b></label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;<textarea rows=1 cols=72 <?php $page->businessobject->items->userfields->draw->text("u_lessoraddress") ?>/><?php echo $page->getitemstring("u_lessoraddress") ?></textarea></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                       
                   
                    </table>
                    
                      <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        
                        <tr><td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_contactperson") ?>>In case of emergency contact person name</label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;<input type="text" size="40" <?php $page->businessobject->items->userfields->draw->text("u_contactperson") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3"><label <?php $page->businessobject->items->userfields->draw->caption("u_contacttelno") ?>>Contact person Tel No./Mobile No.</label></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;<input type="text" size="20" <?php $page->businessobject->items->userfields->draw->text("u_contacttelno") ?>/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr><td width="206"></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>     -->
     <?php if ($_SESSION["userid"] != "bplcommon") { ?>     
            <div class="tabbertab" title="Business Assessment">
                <div id="divba" style="overflow:auto;">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr><td width="300"><label <?php $page->businessobject->items->userfields->draw->caption("u_businesschar") ?>>Business Character</label></td>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_businesskind") ?>>Kind of Business</label></td>
                            <td width="168">&nbsp;</td>
<!--                            <td width="250">&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_paymode") ?>>Mode of Payment (Business Tax)</label></td>
                            <td width="200">&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode", "A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode", "S") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Semi-Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_paymode", "Q") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_paymode") ?>>Quarterly</label></td>-->
<!--                            <td colspan="2"><label <?php $page->businessobject->items->userfields->draw->caption("u_taxclass") ?>>Tax Classification</label></td>-->
                        </tr>
                        <tr>
                            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_businesschar",null,null,null,null,"width:200px") ?>></select></td>
                            <td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_businesskind", array("loadu_bplkinds", $page->getitemstring("u_businesschar"),":")) ?>></select></td>
                            <!--<td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_taxclass",null,null,null,null,"width:500px") ?>></select></td>-->
<!--                            <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_envpaymode") ?>>Mode of Payment (Environmental)</label></td>
                            <td >&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_envpaymode", "A") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_envpaymode") ?>>Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_envpaymode", "S") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_envpaymode") ?>>Semi-Annually</label>&nbsp;<input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_envpaymode", "Q") ?> /><label <?php $page->businessobject->items->userfields->draw->optioncaption("u_envpaymode") ?>>Quarterly</label></td>-->
                        </tr>
                        <tr>
                            <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_businesscategory") ?>>Category of Business</label></td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                            <td >&nbsp;</td>
                            <!--<td align="left">&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_decisiondate") ?>>Decision Date</label>&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_decisiondate") ?>/></td>-->
                        </tr>
                        <tr>
                            <td >&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_businesscategory",null,null,null,null,"width:200px") ?>></select></td>
                            <td colspan="2">&nbsp;</td>
<!--                             <td >&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_reqappno") ?>>Application Serial No</label>&nbsp;<?php genLinkedButtonHtml("u_reqappno", "", "OpenLnkBtnu_bplreqapps()") ?><input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_reqappno") ?>/></td>
                            <td align="left">&nbsp;<label <?php $page->businessobject->items->userfields->draw->caption("u_assyearto") ?>>Up-to-Year</label>&nbsp;&nbsp;&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_assyearto",array("loadenumyear","",":[Select]")) ?>/></select></td>-->
                        </tr>
<!--                        <tr><td width="206"><label <?php $page->businessobject->items->userfields->draw->caption("u_weightstax") ?>>Weight and Measure Tax</label></td>
                            <td >&nbsp;</td>
                            <td width="170" ><label <?php $page->businessobject->items->userfields->draw->caption("u_garbagetax") ?>>Garbage Tax</label></td>
                            <td >&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_weightstax") ?>></select></td>
                            <td colspan="2">&nbsp;<select <?php $page->businessobject->items->userfields->draw->select("u_garbagetax") ?>></select></td>
                        </tr>-->
                        <tr class="fillerRow5px">
                            <td colspan="4"></td>
                        </tr>
                    </table>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                            <td>
                                <?php $objGrids[0]->draw(true); ?> 
                            </td>
                            <td>
                                <div class="tabber" id="tab1" >  
                                    <div class="tabbertab" title="Assessments">
                                        <div id="divudf" style="overflow:auto;">
                                            <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                                <tr class="fillerRow5px"> <td></td> <td></td></tr>
                                            </table>
                                            <?php $objGrids[4]->draw(true); ?> 
                                        </div>
                                    </div>  
<!--                                    <div class="tabbertab" title="Requirement Assessment">
                                        <div id="divudf" style="overflow:auto;">
                                            <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                                <tr class="fillerRow5px"> <td></td> <td></td></tr>
                                            </table>
                                            <?php //$objGrids[8]->draw(true); ?>
                                            <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                                <tr class="fillerRow5px"><td></td><td></td></tr>
                                                <tr><td width="140"><label <?php $page->businessobject->items->userfields->draw->caption("u_reqappfeestotal") ?>>Total Amount</label></td>
                                                    <td align="left">&nbsp;<input type="text" size="17" <?php $page->businessobject->items->userfields->draw->text("u_reqappfeestotal") ?>/></td>
                                                   
                                                </tr>
                                            </table>
                                        </div>
                                    </div>  -->
                                </div>  
                                
                            </td>
                        </tr>
                        
                    </table>
                      
                    </div>
                </div>

                <div class="tabbertab" title="Incorporators">
                    <div id="divemp" style="overflow:auto;">
                        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                         <tr>
                            <td><?php $objGrids[5]->draw(true); ?></td>
                        </tr>
                        </table>
                    </div>
                </div>

                
<!--                <div class="tabbertab" title="Fire Dept Assessments">
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr><td ></td>
                            <td>&nbsp;</td>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_forissuance") ?>>Business Permit Issuance</label></td>
                            <td width="168">&nbsp;<label <?php $page->businessobject->items->userfields->draw->optioncaption("u_forissuance") ?>>Yes</label><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_forissuance", "1") ?> />&nbsp;<label <?php $page->businessobject->items->userfields->draw->optioncaption("u_forissuance") ?>>No</label><input type="radio" <?php $page->businessobject->items->userfields->draw->radio("u_forissuance", "2") ?> /></td>
                        </tr>
                        <tr class="fillerRow5px">
                            <td></td><td></td>
                            <td></td>
                        </tr>
                    </table>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >

                        <tr class="fillerRow5px">
                            <td></td><td></td>
                            <td></td>
                        </tr>
                    </table>	
                    <?php $objGrids[7]->draw(true); ?>
                    <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr class="fillerRow5px">
                            <td></td><td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr><td width="168" ><label <?php $page->businessobject->items->userfields->draw->caption("u_firedeptremarks") ?>>Remarks</label></td>
                            <td rowspan="3" valign="top">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_firedeptremarks") ?>/><?php echo $page->getitemstring("u_firedeptremarks") ?></textarea></td>
                            <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_fireasstotal") ?>>Total Assessment</label></td>
                            <td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_fireasstotal") ?>/></td>
                        </tr>


                    </table>	
                </div>-->
                <div class="tabbertab" title="Approver Remarks">	
                    <table class="tableFreeForm" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr><td><TEXTAREA <?php $page->businessobject->items->userfields->draw->textarea("u_approverremarks") ?>rows="3" cols="50"><?php echo $page->getitemstring("u_approverremarks") ?></TEXTAREA>
                        </td></tr>
                    </table>  
                </div>
                <div class="tabbertab" title="Requirements">
                    <div id="divreq" style="overflow:auto;">
                        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                         <tr>
                            <td><?php $objGrids[1]->draw(true); ?></td>
                        </tr>
                        </table>
                    </div>
                </div>
                <div class="tabbertab" title="Audited Gross History">
                    <div id="divaudited" style="overflow:auto;">
                        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
                         <tr>
                            <td><?php $objGridA->draw(true); ?></td>
                        </tr>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
        
        <table class="tableFreeForm" width="100%" border="0" cellpadding="0" cellspacing="0" >
            <tr class="fillerRow5px">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_remarks") ?>>Remarks</label></td>
                <td rowspan="4" valign="top">&nbsp;<textarea rows=2 cols=50 <?php $page->businessobject->items->userfields->draw->text("u_remarks") ?>/><?php echo $page->getitemstring("u_remarks") ?></textarea></td>
                <td width="140"><label <?php $page->businessobject->items->userfields->draw->caption("u_capital") ?>><b>Total Capitalization</b></label></td>
                <td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_capital") ?>/></td>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_btaxamount") ?>><b>Business Tax</b></label></td>
                <td width="168">&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_btaxamount") ?>/></td>
                <td width="168"><label <?php $page->businessobject->items->userfields->draw->caption("u_ctcasstotal") ?>><b>Cedula Total</b></label></td>
                <td width="168">&nbsp;<input  type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_ctcasstotal") ?>/></td>
            </tr>
            <tr>
                <td >&nbsp;</td>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_nonessential") ?>><b>Total Gross Sales</b></label></td>
                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_nonessential") ?>/></td>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_prevbtaxamount") ?>><b>Previous Business Tax</b></label></td>
                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_prevbtaxamount") ?>/></td>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_fireasstotal") ?>><b>Fire Safety Fee</b></label></td>
                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_fireasstotal") ?>/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                 <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_nonessential") ?>><b>Last Paid Yr/Qtr</b></label></td>
                <td >&nbsp;<input type="text" size="6" <?php genInputTextHtml($schema["lastpayyear"]) ?> />/<input type="text" size="6" <?php genInputTextHtml($schema["lastpayqtr"]) ?> /></td>
                <td><label <?php $page->businessobject->items->userfields->draw->caption("u_prevappno") ?>><b>Previous Application</b></label></td>
                <td><?php genLinkedButtonHtml("u_prevappno", "", "OpenLnkBtnu_bplapps()") ?><input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_prevappno") ?>/></td>
                <td ><label <?php $page->businessobject->items->userfields->draw->caption("u_asstotal") ?>><b>Grand Total</b></label></td>
                <td >&nbsp;<input type="text" size="12" <?php $page->businessobject->items->userfields->draw->text("u_asstotal") ?>/></td>
            </tr>
        </table>	
        
        
        <div <?php genPopWinHDivHtml("popupFrameRetire","Retire Business Application",10,30,400,false) ?>>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr class="fillerRow5px"><td colspan="3"></td></tr>
        <tr><td width="10">&nbsp;</td>
        <td>
                <table width="100%" cellpadding="0" cellspacing="0" border="0" height="120">
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                        <tr>
                                <td valign="top" width="300">
                                        <div>
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_retireddate") ?>>Retired Date</label></td>
                                                            <td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_retireddate") ?>/></td>
                                                        </tr>
                                                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_retiredorno") ?>>OR Number</label></td>
                                                                <td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_retiredorno") ?>/></td>
                                                        </tr>
                                                        <tr><td ><label <?php $page->businessobject->items->userfields->draw->caption("u_retiredpaidamount") ?>>Amount Paid</label></td>
                                                                <td >&nbsp;<input type="text" size="24" <?php $page->businessobject->items->userfields->draw->text("u_retiredpaidamount") ?>/></td>
                                                        </tr>
                                                </table>
                                        </div>
                                </td>
                        </tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                        <tr ><td colspan="2" align="right"><a class="button" href="" onClick="hidePopupFrame('popupFrameRetire');return false;" >Go Back</a>&nbsp;<?php if ($page->getitemstring("u_retired") != 1) { ?><a class="button" href="" onClick="u_retireGPSBPLS();return false;" >Confirm</a> <?php } ?></td></tr>
                        <tr class="fillerRow5px"><td colspan="2"></td></tr>
                </table>
        </td>
        </tr>
        </table>
        </div>
    </td></tr>	
<?php if ($_SESSION["userid"] != "bplcommon") { ?>
    <?php $page->resize->addtab("tab1", -1, 240); ?>
    <?php $page->resize->addtabpage("tab1", "udf") ?>
    <?php // $page->resize->addtabpage("tab1", "ob") ?>
    <?php // $page->resize->addtabpage("tab1", "lessor") ?>
    <?php $page->resize->addtabpage("tab1", "ba") ?>
    <?php $page->resize->addtabpage("tab1", "req") ?>
    <?php $page->resize->addtabpage("tab1", "audited") ?>
    <?php $page->resize->addtabpage("tab1", "emp") ?>
    <?php $page->resize->addgridobject($objGridA, 20, 400) ?>
    <?php $page->resize->addgridobject($objGrids[0], 675, 400) ?>
    <?php // $page->resize->addgridobject($objGrids[4], -1, 440) ?>
    <?php $page->resize->addgridobject($objGrids[5], 20, 400) ?>
    <?php $page->resize->addgridobject($objGrids[1], 20, 400) ?>
    <?php // $page->resize->addgridobject($objGrids[7], 20, 234) ?>
    <?php // $page->resize->addgridobject($objGrids[8], -1, 420) ?>
    <?php //$page->resize->addgridobject($objGrids[2], 575, -1) ?>
    <?php //$page->resize->addgridobject($objGrids[3], 575, 410) ?>
    <?php //$page->resize->addgridobject($objGrids[6], -1, 410) ?>
    <?php $page->resize->addinput("u_approverremarks",30,240); ?>

<?php } ?>