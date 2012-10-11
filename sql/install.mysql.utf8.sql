DROP TABLE IF EXISTS `#__mailrelay`;

CREATE TABLE `#__mailrelay` (
	id int(11) not null auto_increment,
	params text not null default '',
	primary key (id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__mailrelay` (`id`, `params`) VALUES (1, '');
