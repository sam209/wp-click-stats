   

jQuery(document).ready(function($) {
	////////////link//////////////////////
	$('a').on('click',function(event) {
		var cs_url=window.location.pathname;
		var cs_text=$(this).text();
//
 var data2={'action':'add_data','security':my_ajax_object.nonce,'cs_is_link':1,'cs_is_button':0,'cs_url':cs_url,'cs_text':cs_text};
 $.ajax({url: my_ajax_object.ajax_url,type : 'POST',data : data2});
  
});
////////////link//////////////////////
////////////button//////////////////////
$('button').on('click',function(event) {
		var cs_url=window.location.pathname;
		var cs_text=$(this).text();
//
 var data2={'action':'add_data','security':my_ajax_object.nonce,'cs_is_link':0,'cs_is_button':1,'cs_url':cs_url,'cs_text':cs_text};
 $.ajax({url: my_ajax_object.ajax_url,type : 'POST',data : data2});
  
});
////////////button//////////////////////
///////////////submit//////////////
$(':submit').on('click',function(event) {
		var cs_url=window.location.pathname;
		var cs_text=$(this).val();
//
 var data2={'action':'add_data','security':my_ajax_object.nonce,'cs_is_link':0,'cs_is_button':1,'cs_url':cs_url,'cs_text':cs_text};
 $.ajax({url: my_ajax_object.ajax_url,type : 'POST',data : data2});
  
});
//////////////submit///////////////////
////////////img link//////////////////////
	$('img').on('click',function(event) {
		if ($(this).parent().is('a')){
		var cs_url=window.location.pathname;
		var cs_text=$(this).parent().html();
//
 var data2={'action':'add_data','security':my_ajax_object.nonce,'cs_is_link':1,'cs_is_button':0,'cs_url':cs_url,'cs_text':cs_text};
 $.ajax({url: my_ajax_object.ajax_url,type : 'POST',data : data2});
	}
});
////////////img link//////////////////////
});
	
 