function u_ajaxloadu_hisdoctorservicetypes(p_elementid, p_u_doctorid,p_value,p_filler) {
	http = getHTTPObject(); // We create the HTTP Object
	document.getElementById(p_elementid).innerHTML = "<option>Loading..</option>"
	http.open("GET", "udp.php?ajax=1&objectcode=u_ajaxslu_hisdoctorservicetypes&u_doctorid=" + p_u_doctorid + "&value=" + p_value + "&filler=" + p_filler, false);
//		http.onreadystatechange = function () {if (http.readyState == 4) {  document.getElementById(p_elementid).innerHTML = http.responseText;} }
	http.send(null);
	document.getElementById(p_elementid).innerHTML = http.responseText;
	document.getElementById('ajaxPending').value = "";	
	
}