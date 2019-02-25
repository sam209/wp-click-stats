jQuery(document).ready(function($) {
	///////////reset data/////////////////
	
	$('#wcs_admin_page_btn').on('click',function(event) {
		var data2={'action':'wcs_reset_data','security':my_ajax_object2.nonce};
 $.ajax({url: my_ajax_object2.ajax_url,type : 'POST',data : data2,
success: function(data) {
         $('#wcs_admin_page_btn').parent().append(" <b>Data reset successfully</b>");
      }
 
 });
		
	});
////////////////////download csv///////////////////
$('#wcs_admin_page_csv_btn').on('click',function(event) {
		var data2={'action':'wcs_download_csv','security':my_ajax_object3.nonce};
 $.ajax({url: my_ajax_object3.ajax_url,type : 'POST',data : data2,
success: function(data) {
         $('#wcs_admin_page_csv_btn').parent().append(" <b></b>");
      }
 
 });
		
	});
//////////////////////
});