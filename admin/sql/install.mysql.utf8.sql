
   CREATE TABLE  IF NOT EXISTS `#__extporter_extension` (
`extid` int(11) not null  auto_increment ,
`title` varchar(255) not null ,
`extname` text ,
`type` varchar(255) not null ,
`model` text ,
`asset_id` int(11) not null default "0",
`state` tinyint(1) not null default "0",
`ordering` int(11) not null ,
`checked_out_time` datetime not null default "0000-00-00 00:00:00",
`checked_out` int(11) not null ,
`created_by` int(11) not null ,
`published` tinyint(1) default "0",
`params` text ,

UNIQUE KEY (title),
    PRIMARY KEY (`extid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
