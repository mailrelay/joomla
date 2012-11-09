DROP TABLE IF EXISTS `#__mailrelay`;

CREATE TABLE `#__mailrelay` (
	id int(11) not null auto_increment,
	automatically_sync_user tinyint(1) unsigned null default 1,
	automatically_unsync_user tinyint(1) unsigned null default 1,
	host varchar(255) null,
	user varchar(255) null,
	password varchar(255) null,
	groups varchar(255) null,
	primary key (id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__mailrelay` (`id`) VALUES (1);
