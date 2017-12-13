 /**
 * @version 1.0.0
 * @category Joomla component
 * @package     Joomla.Administrator
 * @subpackage  com_extporter
 * @name extporter 
 * @author Dieudonne Timma   <dieudonne.timma.meyatchie@mni.thm.de> 
 * @copyright GNU 3
 * @license Open Source
 */
 
 		
 		
 
jQuery(document).ready(function() {
		jQuery("select[generated='true']").each(function(){
			jQuery(this).trigger('onchange');
		})
	});
	
	/**
	  * this function set the values of references for many foreign attributes 
	  */
function setMultipleValueForeignKeys(element) {

    var data = [];
		var id = "#" + element.id + " option:selected";
		jQuery(id).each(function (){
			data.push(JSON.parse(jQuery(this).prop("value")));
		});
		if(data.length == 0)
			return;

    var allkeys = Object.keys(data[0])
		var all_item = [];

		for(var a = 0; a< allkeys.length; a++){
			all_item[allkeys[a]] = [];
		}

    for(var i =0; i<data.length; i++){
		var attr_obj = data[i];
		var attr_obj_keys = Object.keys(attr_obj);
		for(var j =0; j< attr_obj_keys.length; j++){
			var attr_key_value = attr_obj_keys[j];
			var attr_value = attr_obj[attr_key_value][0];
			all_item[attr_key_value].push(attr_value);

		}
    }
		for(var c =0; c < allkeys.length; c++){
			var value = all_item[allkeys[c]];
			jQuery("#"+ allkeys[c]).attr("value",JSON.stringify(value));

		}
   }
