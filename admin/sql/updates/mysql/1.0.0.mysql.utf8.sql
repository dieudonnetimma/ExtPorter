   
ALTER TABLE `#__extporter_extension`  
ADD COLUMN `extid`  int(11) NOT NULL  AUTO_INCREMENT 
,
ADD COLUMN `title`  varchar(255) NOT NULL 
AFTER extid,
ADD COLUMN `extname`  text 
AFTER title,
ADD COLUMN `type`  varchar(255) NOT NULL 
AFTER extname,
ADD COLUMN `model`  text 
AFTER type,
ADD COLUMN `asset_id`  int(11) NOT NULL DEFAULT "0"
AFTER model,
ADD COLUMN `state`  tinyint(1) NOT NULL DEFAULT "0"
AFTER asset_id,
ADD COLUMN `ordering`  int(11) NOT NULL 
AFTER state,
ADD COLUMN `checked_out_time`  datetime NOT NULL DEFAULT "0000-00-00 00:00:00"
AFTER ordering,
ADD COLUMN `checked_out`  int(11) NOT NULL 
AFTER checked_out_time,
ADD COLUMN `created_by`  int(11) NOT NULL 
AFTER checked_out,
ADD COLUMN `published`  tinyint(1) DEFAULT "0"
AFTER created_by,
ADD COLUMN `params`  text 
AFTER published
;

 ALTER TABLE `#__extporter_extension`  
 ADD INDEX KEY (`title` )
 ;
 ALTER TABLE `#__extporter_extension`  
  ;
 
