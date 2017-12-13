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
 
 		
 		
 /**
 * this function set the value of reference for a foreign attribute 
 */
function setValueForeignKeys(element) {

    var data = JSON.parse(element.value);
    var item;

    for(item in data){
        jQuery("#"+item).attr("value",data[item]);
    }
   }
