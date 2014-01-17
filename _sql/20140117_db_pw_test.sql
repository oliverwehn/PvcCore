# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.25)
# Datenbank: db_pw_test
# Erstellungsdauer: 2014-01-17 19:42:11 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Export von Tabelle field_body
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_body`;

CREATE TABLE `field_body` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`pages_id`),
  FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_body` WRITE;
/*!40000 ALTER TABLE `field_body` DISABLE KEYS */;

INSERT INTO `field_body` (`pages_id`, `data`)
VALUES
	(27,'<h3>The page you were looking for is not found.</h3>\r\n<p>Please use our search engine or navigation above to find the page.</p>'),
	(1,'<h2>What is ProcessWire?</h2><p>ProcessWire gives you full control over your fields, templates and markup. It provides a powerful template system that works the way you do. Not to mention, ProcessWire\'s API makes working with your content easy and enjoyable. <a href=\"http://processwire.com\">Learn more</a> </p><h2>Basic Site Profile</h2><p>This is a basic starter site for you to use in developing your own site. There are a few pages here to serve as examples, but this site profile does not make any attempt to demonstrate all that ProcessWire can do. To learn more or ask questions, visit the <a href=\"http://www.processwire.com/talk/\" target=\"_blank\">ProcessWire forums</a>. If you are building a new site, this basic profile is a good place to start. You may use these existing templates and design as they are, or you may replace them entirely. <a href=\"./templates/\">Read more</a></p><h2>Browse the Site</h2>'),
	(1002,'<h2>Ut capio feugiat saepius torqueo olim</h2><h3>In utinam facilisi eum vicis feugait nimis</h3><p>Iusto incassum appellatio cui macto genitus vel. Lobortis aliquam luctus, roto enim, imputo wisi tamen. Ratis odio, genitus acsi, neo illum consequat consectetuer ut. </p><p>Wisi fere virtus cogo, ex ut vel nullus similis vel iusto. Tation incassum adsum in, quibus capto premo diam suscipere facilisi. Uxor laoreet mos capio premo feugait ille et. Pecus abigo immitto epulae duis vel. Neque causa, indoles verto, decet ingenium dignissim. </p><p>Patria iriure vel vel autem proprius indoles ille sit. Tation blandit refoveo, accumsan ut ulciscor lucidus inhibeo capto aptent opes, foras. </p><h3>Dolore ea valde refero feugait utinam luctus</h3><p>Usitas, nostrud transverbero, in, amet, nostrud ad. Ex feugiat opto diam os aliquam regula lobortis dolore ut ut quadrum. Esse eu quis nunc jugis iriure volutpat wisi, fere blandit inhibeo melior, hendrerit, saluto velit. Eu bene ideo dignissim delenit accumsan nunc. Usitas ille autem camur consequat typicus feugait elit ex accumsan nutus accumsan nimis pagus, occuro. Immitto populus, qui feugiat opto pneum letalis paratus. Mara conventio torqueo nibh caecus abigo sit eum brevitas. Populus, duis ex quae exerci hendrerit, si antehabeo nobis, consequat ea praemitto zelus. </p><p>Immitto os ratis euismod conventio erat jus caecus sudo. Appellatio consequat, et ibidem ludus nulla dolor augue abdo tego euismod plaga lenis. Sit at nimis venio venio tego os et pecus enim pneum magna nobis ad pneum. Saepius turpis probo refero molior nonummy aliquam neque appellatio jus luctus acsi. Ulciscor refero pagus imputo eu refoveo valetudo duis dolore usitas. Consequat suscipere quod torqueo ratis ullamcorper, dolore lenis, letalis quia quadrum plaga minim. </p>'),
	(1003,'<h2>The site template files are located in /site/templates/</h2><p>Each of the template files in this site profile includes the header template (head.inc), outputs the bodycopy, and then includes the footer template (foot.inc). This is to avoid duplication of the markup that is the same across all pages in the site. This is just one strategy you can use for templates. </p><p>You could of course make each template completely self contained with it\'s own markup, but if you have more than one template with some of the same markup, then it wouldn\'t be very efficient to do that.</p><p>Another strategy would be to use a have a main template that contains all your markup and has placeholder variables for the dynamic parts. Then your other templates would populate the placeholder variables before including the main template. See the <a href=\"http://processwire.com/download/\">skyscrapers</a> site profile for an example of that strategy. </p><p>Regardless of what strategy you use in your own site, I hope that you find ProcessWire easy to develop with. See the <a href=\"http://processwire.com/api/\">Developer API</a>, and the section on <a href=\"http://processwire.com/api/templates/\">Templates</a> to get you started.</p>'),
	(1001,'<h2>Si lobortis singularis genitus ibidem saluto.</h2><p>Dolore ad nunc, mos accumsan paratus duis suscipit luptatum facilisis macto uxor iaceo quadrum. Demoveo, appellatio elit neque ad commodo ea. Wisi, iaceo, tincidunt at commoveo rusticus et, ludus. Feugait at blandit bene blandit suscipere abdo duis ideo bis commoveo pagus ex, velit. Consequat commodo roto accumsan, duis transverbero.</p>'),
	(1004,'<h2>Pertineo vel dignissim, natu letalis fere odio</h2><h3>Si lobortis singularis genitus ibidem saluto</h3><p>Magna in gemino, gilvus iusto capto jugis abdo mos aptent acsi qui. Utrum inhibeo humo humo duis quae. Lucidus paulatim facilisi scisco quibus hendrerit conventio adsum. Feugiat eligo foras ex elit sed indoles hos elit ex antehabeo defui et nostrud. Letatio valetudo multo consequat inhibeo ille dignissim pagus et in quadrum eum eu. Aliquam si consequat, ut nulla amet et turpis exerci, adsum luctus ne decet, delenit. Commoveo nunc diam valetudo cui, aptent commoveo at obruo uxor nulla aliquip augue. </p><p>Iriure, ex velit, praesent vulpes delenit capio vero gilvus inhibeo letatio aliquip metuo qui eros. Transverbero demoveo euismod letatio torqueo melior. Ut odio in suscipit paulatim amet huic letalis suscipere eros causa, letalis magna. </p>');

/*!40000 ALTER TABLE `field_body` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_email
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_email`;

CREATE TABLE `field_email` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pages_id`),
  KEY `data_exact` (`data`),
  FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_email` WRITE;
/*!40000 ALTER TABLE `field_email` DISABLE KEYS */;

INSERT INTO `field_email` (`pages_id`, `data`)
VALUES
	(41,'hello@oliverwehn.com');

/*!40000 ALTER TABLE `field_email` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_headline
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_headline`;

CREATE TABLE `field_headline` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`pages_id`),
  FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_headline` WRITE;
/*!40000 ALTER TABLE `field_headline` DISABLE KEYS */;

INSERT INTO `field_headline` (`pages_id`, `data`)
VALUES
	(1,'Basic Example Site'),
	(1001,'About Us'),
	(1003,'Developing Site Templates');

/*!40000 ALTER TABLE `field_headline` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_images`;

CREATE TABLE `field_images` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` varchar(255) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`pages_id`,`sort`),
  KEY `data` (`data`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_images` WRITE;
/*!40000 ALTER TABLE `field_images` DISABLE KEYS */;

INSERT INTO `field_images` (`pages_id`, `data`, `sort`, `description`)
VALUES
	(1,'westin_interior2.jpg',7,'Westin Peachtree Atlanta hotel lobby area.'),
	(1,'marquis_interior7b.jpg',5,'Elevator at the Atlanta Marriott Marquis hotel.'),
	(1,'marquis_interior13b_med.jpg',6,'Atrium at the Atlanta Marriott Marquis hotel.'),
	(1,'marquis_interior3.jpg',4,'Elevator core at the Atlanta Marriott Marquis hotel.'),
	(1,'hyatt_interior11.jpg',3,'Looking up from the lobby area at the Atlanta Hyatt hotel.'),
	(1,'hyatt2.jpg',2,'Detail from Atlanta Hyatt Hotel.'),
	(1,'hyatt_interior9.jpg',1,'Detail from Atlanta Hyatt Hotel.'),
	(1,'westin_interior1.jpg',0,'Westin Peachtree Atlanta hotel lobby area.');

/*!40000 ALTER TABLE `field_images` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_pass
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_pass`;

CREATE TABLE `field_pass` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` char(40) NOT NULL,
  `salt` char(32) NOT NULL,
  PRIMARY KEY (`pages_id`),
  KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii;

LOCK TABLES `field_pass` WRITE;
/*!40000 ALTER TABLE `field_pass` DISABLE KEYS */;

INSERT INTO `field_pass` (`pages_id`, `data`, `salt`)
VALUES
	(41,'jn510wvlwxgxMjKy7/7bZ7o/hgkgw8O','$2y$11$S/vmGP2Qf9RoNUr6IvaqzO'),
	(40,'','');

/*!40000 ALTER TABLE `field_pass` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_permissions`;

CREATE TABLE `field_permissions` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` int(11) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pages_id`,`sort`),
  KEY `data` (`data`,`pages_id`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_permissions` WRITE;
/*!40000 ALTER TABLE `field_permissions` DISABLE KEYS */;

INSERT INTO `field_permissions` (`pages_id`, `data`, `sort`)
VALUES
	(38,32,1),
	(38,34,2),
	(38,35,3),
	(37,36,0),
	(38,36,0),
	(38,50,4),
	(38,51,5),
	(38,52,7),
	(38,53,8),
	(38,54,6);

/*!40000 ALTER TABLE `field_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_process
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_process`;

CREATE TABLE `field_process` (
  `pages_id` int(11) NOT NULL DEFAULT '0',
  `data` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pages_id`),
  KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_process` WRITE;
/*!40000 ALTER TABLE `field_process` DISABLE KEYS */;

INSERT INTO `field_process` (`pages_id`, `data`)
VALUES
	(6,17),
	(3,12),
	(8,12),
	(9,14),
	(10,7),
	(11,47),
	(16,48),
	(300,104),
	(21,50),
	(29,66),
	(23,10),
	(304,138),
	(31,136),
	(22,76),
	(30,68),
	(303,129),
	(2,87),
	(302,121),
	(301,109),
	(28,76);

/*!40000 ALTER TABLE `field_process` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_roles`;

CREATE TABLE `field_roles` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` int(11) NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pages_id`,`sort`),
  KEY `data` (`data`,`pages_id`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_roles` WRITE;
/*!40000 ALTER TABLE `field_roles` DISABLE KEYS */;

INSERT INTO `field_roles` (`pages_id`, `data`, `sort`)
VALUES
	(40,37,0),
	(41,37,0),
	(41,38,1);

/*!40000 ALTER TABLE `field_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_sidebar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_sidebar`;

CREATE TABLE `field_sidebar` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`pages_id`),
  FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_sidebar` WRITE;
/*!40000 ALTER TABLE `field_sidebar` DISABLE KEYS */;

INSERT INTO `field_sidebar` (`pages_id`, `data`)
VALUES
	(1,'<h3>About ProcessWire</h3><p>ProcessWire is an open source CMS and web application framework aimed at the needs of designers, developers and their clients. </p><p><a href=\"http://processwire.com/about/\" target=\"_blank\">About ProcessWire</a><br /><a href=\"http://processwire.com/api/\">Developer API</a><br /><a href=\"http://processwire.com/contact/\">Contact Us</a><br /><a href=\"http://twitter.com/rc_d\">Follow Us on Twitter</a></p>'),
	(1002,'<h3>Sudo nullus</h3><p>Et torqueo vulpes vereor luctus augue quod consectetuer antehabeo causa patria tation ex plaga ut. Abluo delenit wisi iriure eros feugiat probo nisl aliquip nisl, patria. Antehabeo esse camur nisl modo utinam. Sudo nullus ventosus ibidem facilisis saepius eum sino pneum, vicis odio voco opto.</p>');

/*!40000 ALTER TABLE `field_sidebar` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_summary
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_summary`;

CREATE TABLE `field_summary` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`pages_id`),
  FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_summary` WRITE;
/*!40000 ALTER TABLE `field_summary` DISABLE KEYS */;

INSERT INTO `field_summary` (`pages_id`, `data`)
VALUES
	(1002,'Dolore ea valde refero feugait utinam luctus. Probo velit commoveo et, delenit praesent, suscipit zelus, hendrerit zelus illum facilisi, regula. '),
	(1001,'This is a placeholder page with two child pages to serve as an example. '),
	(1005,'View this template\'s source for a demonstration of how to create a basic site map. '),
	(1003,'More about the templates included in this basic site profile. '),
	(1004,'Mos erat reprobo in praesent, mara premo, obruo iustum pecus velit lobortis te sagaciter populus.'),
	(1,'ProcessWire is an open source CMS and web application framework aimed at the needs of designers, developers and their clients. ');

/*!40000 ALTER TABLE `field_summary` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle field_title
# ------------------------------------------------------------

DROP TABLE IF EXISTS `field_title`;

CREATE TABLE `field_title` (
  `pages_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`pages_id`),
  KEY `data_exact` (`data`(255)),
  FULLTEXT KEY `data` (`data`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `field_title` WRITE;
/*!40000 ALTER TABLE `field_title` DISABLE KEYS */;

INSERT INTO `field_title` (`pages_id`, `data`)
VALUES
	(14,'Edit Template'),
	(15,'Add Template'),
	(12,'Templates'),
	(11,'Templates'),
	(19,'Field groups'),
	(20,'Edit Fieldgroup'),
	(16,'Fields'),
	(17,'Fields'),
	(18,'Edit Field'),
	(22,'Setup'),
	(3,'Pages'),
	(6,'Add Page'),
	(8,'Page List'),
	(9,'Save Sort'),
	(10,'Edit Page'),
	(21,'Modules'),
	(29,'Users'),
	(30,'Roles'),
	(2,'Admin'),
	(7,'Trash'),
	(27,'404 Page Not Found'),
	(302,'Insert Link'),
	(23,'Login'),
	(304,'Profile'),
	(301,'Empty Trash'),
	(300,'Search'),
	(303,'Insert Image'),
	(28,'Access'),
	(31,'Permissions'),
	(32,'Edit pages'),
	(34,'Delete pages'),
	(35,'Move pages (change parent)'),
	(36,'View pages'),
	(50,'Sort child pages'),
	(51,'Change templates on pages'),
	(52,'Administer users (role must also have template edit access)'),
	(53,'User can update profile/password'),
	(54,'Lock or unlock a page'),
	(1,'Home'),
	(1001,'About'),
	(1002,'Child page example 1'),
	(1000,'Search'),
	(1003,'Templates'),
	(1004,'Child page example 2'),
	(1005,'Site Map');

/*!40000 ALTER TABLE `field_title` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle fieldgroups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fieldgroups`;

CREATE TABLE `fieldgroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET ascii NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `fieldgroups` WRITE;
/*!40000 ALTER TABLE `fieldgroups` DISABLE KEYS */;

INSERT INTO `fieldgroups` (`id`, `name`)
VALUES
	(2,'admin'),
	(3,'user'),
	(4,'role'),
	(5,'permission'),
	(1,'home'),
	(88,'sitemap'),
	(83,'basic-page'),
	(80,'search');

/*!40000 ALTER TABLE `fieldgroups` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle fieldgroups_fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fieldgroups_fields`;

CREATE TABLE `fieldgroups_fields` (
  `fieldgroups_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fields_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) unsigned NOT NULL DEFAULT '0',
  `data` text,
  PRIMARY KEY (`fieldgroups_id`,`fields_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `fieldgroups_fields` WRITE;
/*!40000 ALTER TABLE `fieldgroups_fields` DISABLE KEYS */;

INSERT INTO `fieldgroups_fields` (`fieldgroups_id`, `fields_id`, `sort`, `data`)
VALUES
	(2,2,1,NULL),
	(2,1,0,NULL),
	(3,3,0,NULL),
	(3,4,2,NULL),
	(4,5,0,NULL),
	(5,1,0,NULL),
	(3,92,1,NULL),
	(1,1,0,NULL),
	(1,44,5,NULL),
	(1,76,3,NULL),
	(80,1,0,NULL),
	(83,1,0,NULL),
	(83,44,5,NULL),
	(83,76,3,NULL),
	(83,82,4,NULL),
	(1,78,1,NULL),
	(83,78,1,NULL),
	(83,79,2,NULL),
	(88,79,1,NULL),
	(1,79,2,NULL),
	(1,82,4,NULL),
	(88,1,0,NULL);

/*!40000 ALTER TABLE `fieldgroups_fields` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle fields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fields`;

CREATE TABLE `fields` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(128) CHARACTER SET ascii NOT NULL,
  `name` varchar(255) CHARACTER SET ascii NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `label` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `fields` WRITE;
/*!40000 ALTER TABLE `fields` DISABLE KEYS */;

INSERT INTO `fields` (`id`, `type`, `name`, `flags`, `label`, `data`)
VALUES
	(1,'FieldtypePageTitle','title',13,'Title','{\"required\":1,\"textformatters\":[\"TextformatterEntities\"],\"size\":0,\"maxlength\":255}'),
	(2,'FieldtypeModule','process',25,'Process','{\"description\":\"The process that is executed on this page. Since this is mostly used by ProcessWire internally, it is recommended that you don\'t change the value of this unless adding your own pages in the admin.\",\"collapsed\":1,\"required\":1,\"moduleTypes\":[\"Process\"],\"permanent\":1}'),
	(3,'FieldtypePassword','pass',24,'Set Password','{\"collapsed\":1,\"size\":50,\"maxlength\":128}'),
	(5,'FieldtypePage','permissions',24,'Permissions','{\"derefAsPage\":0,\"parent_id\":31,\"labelFieldName\":\"title\",\"inputfield\":\"InputfieldCheckboxes\"}'),
	(4,'FieldtypePage','roles',24,'Roles','{\"derefAsPage\":0,\"parent_id\":30,\"labelFieldName\":\"name\",\"inputfield\":\"InputfieldCheckboxes\",\"description\":\"User will inherit the permissions assigned to each role. You may assign multiple roles to a user. When accessing a page, the user will only inherit permissions from the roles that are also assigned to the page\'s template.\"}'),
	(92,'FieldtypeEmail','email',9,'E-Mail Address','{\"size\":70,\"maxlength\":255}'),
	(82,'FieldtypeTextarea','sidebar',0,'Sidebar','{\"inputfieldClass\":\"InputfieldTinyMCE\",\"rows\":5,\"theme_advanced_buttons1\":\"formatselect,styleselect|,bold,italic,|,bullist,numlist,|,link,unlink,|,image,|,code,|,fullscreen\",\"theme_advanced_blockformats\":\"p,h2,h3,h4,blockquote,pre,code\",\"plugins\":\"inlinepopups,safari,table,media,paste,fullscreen,preelementfix\",\"valid_elements\":\"@[id|class],a[href|target|name],strong\\/b,em\\/i,br,img[src|id|class|width|height|alt],ul,ol,li,p[class],h2,h3,h4,blockquote,-p,-table[border=0|cellspacing|cellpadding|width|frame|rules|height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor|scope],#th[colspan|rowspan|width|height|align|valign|scope],pre,code\"}'),
	(44,'FieldtypeImage','images',0,'Images','{\"extensions\":\"gif jpg jpeg png\",\"entityEncode\":1,\"adminThumbs\":1,\"inputfieldClass\":\"InputfieldImage\",\"maxFiles\":0,\"descriptionRows\":1}'),
	(79,'FieldtypeTextarea','summary',1,'Summary','{\"textformatters\":[\"TextformatterEntities\"],\"inputfieldClass\":\"InputfieldTextarea\",\"collapsed\":2,\"rows\":3}'),
	(76,'FieldtypeTextarea','body',0,'Body','{\"inputfieldClass\":\"InputfieldTinyMCE\",\"collapsed\":0,\"rows\":10,\"theme_advanced_buttons1\":\"formatselect,|,bold,italic,|,bullist,numlist,|,link,unlink,|,image,|,code,|,fullscreen\",\"theme_advanced_blockformats\":\"p,h2,h3,h4,blockquote,pre\",\"plugins\":\"inlinepopups,safari,media,paste,fullscreen\",\"valid_elements\":\"@[id|class],a[href|target|name],strong\\/b,em\\/i,br,img[src|id|class|width|height|alt],ul,ol,li,p[class],h2,h3,h4,blockquote,-p,-table[border=0|cellspacing|cellpadding|width|frame|rules|height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor|scope],#th[colspan|rowspan|width|height|align|valign|scope],code,pre\"}'),
	(78,'FieldtypeText','headline',0,'Headline','{\"description\":\"Use this instead of the Title if a longer headline is needed than what you want to appear in navigation.\",\"textformatters\":[\"TextformatterEntities\"],\"collapsed\":2,\"size\":0,\"maxlength\":1024}');

/*!40000 ALTER TABLE `fields` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle modules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `modules`;

CREATE TABLE `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(128) CHARACTER SET ascii NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `class` (`class`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;

INSERT INTO `modules` (`id`, `class`, `flags`, `data`)
VALUES
	(1,'FieldtypeTextarea',0,''),
	(2,'FieldtypeNumber',0,''),
	(3,'FieldtypeText',0,''),
	(4,'FieldtypePage',0,''),
	(30,'InputfieldForm',0,''),
	(6,'FieldtypeFile',0,''),
	(7,'ProcessPageEdit',1,''),
	(10,'ProcessLogin',0,''),
	(147,'TextformatterPstripper',1,''),
	(12,'ProcessPageList',0,'{\"pageLabelField\":\"title\",\"paginationLimit\":25,\"limit\":50}'),
	(121,'ProcessPageEditLink',1,''),
	(14,'ProcessPageSort',0,''),
	(15,'InputfieldPageListSelect',0,''),
	(117,'JqueryUI',1,''),
	(17,'ProcessPageAdd',0,''),
	(125,'SessionLoginThrottle',3,''),
	(122,'InputfieldPassword',0,''),
	(25,'InputfieldAsmSelect',0,''),
	(116,'JqueryCore',1,''),
	(27,'FieldtypeModule',0,''),
	(28,'FieldtypeDatetime',0,''),
	(29,'FieldtypeEmail',0,''),
	(108,'InputfieldURL',0,''),
	(32,'InputfieldSubmit',0,''),
	(33,'InputfieldWrapper',0,''),
	(34,'InputfieldText',0,''),
	(35,'InputfieldTextarea',0,''),
	(36,'InputfieldSelect',0,''),
	(37,'InputfieldCheckbox',0,''),
	(38,'InputfieldCheckboxes',0,''),
	(39,'InputfieldRadios',0,''),
	(40,'InputfieldHidden',0,''),
	(41,'InputfieldName',0,''),
	(43,'InputfieldSelectMultiple',0,''),
	(45,'JqueryWireTabs',0,''),
	(46,'ProcessPage',0,''),
	(47,'ProcessTemplate',0,''),
	(48,'ProcessField',0,''),
	(50,'ProcessModule',0,''),
	(114,'PagePermissions',3,''),
	(97,'FieldtypeCheckbox',1,''),
	(115,'PageRender',3,'{\"clearCache\":1}'),
	(55,'InputfieldFile',0,''),
	(56,'InputfieldImage',0,''),
	(57,'FieldtypeImage',0,''),
	(60,'InputfieldPage',0,'{\"inputfieldClasses\":[\"InputfieldSelect\",\"InputfieldSelectMultiple\",\"InputfieldCheckboxes\",\"InputfieldRadios\",\"InputfieldAsmSelect\",\"InputfieldPageListSelect\",\"InputfieldPageListSelectMultiple\"]}'),
	(61,'TextformatterEntities',0,''),
	(145,'TextformatterMarkdownExtra',1,''),
	(146,'TextformatterSmartypants',1,''),
	(66,'ProcessUser',0,'{\"showFields\":[\"name\",\"email\",\"roles\"]}'),
	(67,'MarkupAdminDataTable',0,''),
	(68,'ProcessRole',0,'{\"showFields\":[\"name\"]}'),
	(76,'ProcessList',0,''),
	(78,'InputfieldFieldset',0,''),
	(79,'InputfieldMarkup',0,''),
	(80,'InputfieldEmail',0,''),
	(89,'FieldtypeFloat',1,''),
	(83,'ProcessPageView',0,''),
	(84,'FieldtypeInteger',0,''),
	(85,'InputfieldInteger',0,''),
	(86,'InputfieldPageName',0,''),
	(87,'ProcessHome',0,''),
	(90,'InputfieldFloat',0,''),
	(92,'InputfieldTinyMCE',0,''),
	(94,'InputfieldDatetime',0,''),
	(98,'MarkupPagerNav',0,''),
	(129,'ProcessPageEditImageSelect',1,''),
	(102,'JqueryFancybox',1,''),
	(103,'JqueryTableSorter',1,''),
	(104,'ProcessPageSearch',1,'{\"searchFields\":\"title body\",\"displayField\":\"title path\"}'),
	(105,'FieldtypeFieldsetOpen',1,''),
	(106,'FieldtypeFieldsetClose',1,''),
	(107,'FieldtypeFieldsetTabOpen',1,''),
	(109,'ProcessPageTrash',1,''),
	(111,'FieldtypePageTitle',1,''),
	(112,'InputfieldPageTitle',0,''),
	(113,'MarkupPageArray',3,''),
	(131,'InputfieldButton',0,''),
	(133,'FieldtypePassword',1,''),
	(134,'ProcessPageType',1,'{\"showFields\":[]}'),
	(135,'FieldtypeURL',1,''),
	(136,'ProcessPermission',1,'{\"showFields\":[\"name\",\"title\"]}'),
	(137,'InputfieldPageListSelectMultiple',0,''),
	(138,'ProcessProfile',1,'{\"profileFields\":[\"pass\",\"email\"]}'),
	(139,'SystemUpdater',1,'{\"systemVersion\":3}');

/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `templates_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(128) CHARACTER SET ascii NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_users_id` int(10) unsigned NOT NULL DEFAULT '2',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_users_id` int(10) unsigned NOT NULL DEFAULT '2',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_parent_id` (`name`,`parent_id`),
  KEY `parent_id` (`parent_id`),
  KEY `templates_id` (`templates_id`),
  KEY `modified` (`modified`),
  KEY `created` (`created`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `parent_id`, `templates_id`, `name`, `status`, `modified`, `modified_users_id`, `created`, `created_users_id`, `sort`)
VALUES
	(1,0,1,'home',9,'2011-09-06 14:50:01',41,'0000-00-00 00:00:00',2,0),
	(2,1,2,'processwire',1035,'2014-01-17 20:40:53',40,'0000-00-00 00:00:00',2,5),
	(3,2,2,'page',21,'2011-03-29 21:37:06',41,'0000-00-00 00:00:00',2,0),
	(6,3,2,'add',21,'2011-03-29 21:37:06',41,'0000-00-00 00:00:00',2,0),
	(7,1,2,'trash',1039,'2011-08-14 22:04:52',41,'2010-02-07 05:29:39',2,6),
	(8,3,2,'list',21,'2011-03-29 21:37:06',41,'0000-00-00 00:00:00',2,1),
	(9,3,2,'sort',23,'2011-03-29 21:37:06',41,'0000-00-00 00:00:00',2,2),
	(10,3,2,'edit',21,'2011-03-29 21:37:06',41,'0000-00-00 00:00:00',2,3),
	(11,22,2,'template',21,'2011-03-29 21:37:06',41,'2010-02-01 11:04:54',2,0),
	(16,22,2,'field',21,'2011-03-29 21:37:06',41,'2010-02-01 12:44:07',2,2),
	(21,2,2,'module',21,'2011-03-29 21:37:06',41,'2010-02-02 10:02:24',2,2),
	(22,2,2,'setup',21,'2011-03-29 21:37:06',41,'2010-02-09 12:16:59',2,1),
	(23,2,2,'login',1035,'2011-05-03 23:38:10',41,'2010-02-17 09:59:39',2,4),
	(27,1,29,'http404',1035,'2011-08-14 22:04:52',41,'2010-06-03 06:53:03',3,4),
	(28,2,2,'access',13,'2011-05-03 23:38:10',41,'2011-03-19 19:14:20',2,3),
	(29,28,2,'users',29,'2011-04-05 00:39:08',41,'2011-03-19 19:15:29',2,0),
	(30,28,2,'roles',29,'2011-04-05 00:38:39',41,'2011-03-19 19:15:45',2,1),
	(31,28,2,'permissions',29,'2011-04-05 00:53:52',41,'2011-03-19 19:16:00',2,2),
	(32,31,5,'page-edit',25,'2011-09-06 15:34:24',41,'2011-03-19 19:17:03',2,2),
	(34,31,5,'page-delete',25,'2011-09-06 15:34:33',41,'2011-03-19 19:17:23',2,3),
	(35,31,5,'page-move',25,'2011-09-06 15:34:48',41,'2011-03-19 19:17:41',2,4),
	(36,31,5,'page-view',25,'2011-09-06 15:34:14',41,'2011-03-19 19:17:57',2,0),
	(37,30,4,'guest',25,'2011-04-05 01:37:19',41,'2011-03-19 19:18:41',2,0),
	(38,30,4,'superuser',25,'2011-08-17 14:34:39',41,'2011-03-19 19:18:55',2,1),
	(41,29,3,'admin',1,'2014-01-17 20:40:53',40,'2011-03-19 19:41:26',2,0),
	(40,29,3,'guest',25,'2011-08-17 14:26:09',41,'2011-03-20 17:31:59',2,1),
	(50,31,5,'page-sort',25,'2011-09-06 15:34:58',41,'2011-03-26 22:04:50',41,5),
	(51,31,5,'page-template',25,'2011-09-06 15:35:09',41,'2011-03-26 22:25:31',41,6),
	(52,31,5,'user-admin',25,'2011-09-06 15:35:42',41,'2011-03-30 00:06:47',41,10),
	(53,31,5,'profile-edit',1,'2011-08-16 22:32:48',41,'2011-04-26 00:02:22',41,13),
	(54,31,5,'page-lock',1,'2011-08-15 17:48:12',41,'2011-08-15 17:45:48',41,8),
	(300,3,2,'search',21,'2011-03-29 21:37:06',41,'2010-08-04 05:23:59',2,5),
	(301,3,2,'trash',23,'2011-03-29 21:37:06',41,'2010-09-28 05:39:30',2,5),
	(302,3,2,'link',17,'2011-03-29 21:37:06',41,'2010-10-01 05:03:56',2,6),
	(303,3,2,'image',17,'2011-03-29 21:37:06',41,'2010-10-13 03:56:48',2,7),
	(304,2,2,'profile',1025,'2011-05-03 23:38:10',41,'2011-04-25 23:57:18',41,5),
	(1000,1,26,'search',1025,'2011-08-31 19:17:38',41,'2010-09-06 05:05:28',2,3),
	(1001,1,29,'about',1,'2011-09-05 16:02:24',41,'2010-10-25 22:39:33',2,0),
	(1002,1001,29,'what',1,'2011-09-06 14:50:53',41,'2010-10-25 23:21:34',2,0),
	(1003,1,29,'templates',1,'2011-09-05 16:08:59',41,'2010-10-26 01:59:44',2,1),
	(1004,1001,29,'background',1,'2011-08-18 14:47:47',41,'2010-11-29 22:11:36',2,1),
	(1005,1,34,'site-map',1,'2011-08-31 19:17:38',41,'2010-11-30 21:16:49',2,2);

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle pages_access
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages_access`;

CREATE TABLE `pages_access` (
  `pages_id` int(11) NOT NULL,
  `templates_id` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pages_id`),
  KEY `templates_id` (`templates_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `pages_access` WRITE;
/*!40000 ALTER TABLE `pages_access` DISABLE KEYS */;

INSERT INTO `pages_access` (`pages_id`, `templates_id`, `ts`)
VALUES
	(37,2,'2011-09-06 12:10:09'),
	(38,2,'2011-09-06 12:10:09'),
	(32,2,'2011-09-06 12:10:09'),
	(34,2,'2011-09-06 12:10:09'),
	(35,2,'2011-09-06 12:10:09'),
	(36,2,'2011-09-06 12:10:09'),
	(50,2,'2011-09-06 12:10:09'),
	(51,2,'2011-09-06 12:10:09'),
	(52,2,'2011-09-06 12:10:09'),
	(53,2,'2011-09-06 12:10:09'),
	(54,2,'2011-09-06 12:10:09');

/*!40000 ALTER TABLE `pages_access` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle pages_parents
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages_parents`;

CREATE TABLE `pages_parents` (
  `pages_id` int(10) unsigned NOT NULL,
  `parents_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pages_id`,`parents_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `pages_parents` WRITE;
/*!40000 ALTER TABLE `pages_parents` DISABLE KEYS */;

INSERT INTO `pages_parents` (`pages_id`, `parents_id`)
VALUES
	(2,1),
	(3,1),
	(3,2),
	(7,1),
	(22,1),
	(22,2),
	(28,1),
	(28,2),
	(29,1),
	(29,2),
	(29,28),
	(30,1),
	(30,2),
	(30,28),
	(31,1),
	(31,2),
	(31,28),
	(1001,1),
	(1002,1),
	(1002,1001),
	(1003,1),
	(1004,1),
	(1004,1001),
	(1005,1);

/*!40000 ALTER TABLE `pages_parents` ENABLE KEYS */;
UNLOCK TABLES;


# Export von Tabelle pages_sortfields
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages_sortfields`;

CREATE TABLE `pages_sortfields` (
  `pages_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sortfield` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`pages_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Export von Tabelle session_login_throttle
# ------------------------------------------------------------

DROP TABLE IF EXISTS `session_login_throttle`;

CREATE TABLE `session_login_throttle` (
  `name` varchar(128) NOT NULL,
  `attempts` int(10) unsigned NOT NULL DEFAULT '0',
  `last_attempt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Export von Tabelle templates
# ------------------------------------------------------------

DROP TABLE IF EXISTS `templates`;

CREATE TABLE `templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET ascii NOT NULL,
  `fieldgroups_id` int(10) unsigned NOT NULL DEFAULT '0',
  `flags` int(11) NOT NULL DEFAULT '0',
  `cache_time` mediumint(9) NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `fieldgroups_id` (`fieldgroups_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;

INSERT INTO `templates` (`id`, `name`, `fieldgroups_id`, `flags`, `cache_time`, `data`)
VALUES
	(2,'admin',2,8,0,'{\"useRoles\":1,\"parentTemplates\":[2],\"allowPageNum\":1,\"redirectLogin\":23,\"slashUrls\":1,\"noGlobal\":1}'),
	(3,'user',3,8,0,'{\"useRoles\":1,\"noChildren\":1,\"parentTemplates\":[2],\"slashUrls\":1,\"pageClass\":\"User\",\"noGlobal\":1,\"noMove\":1,\"noTrash\":1,\"noSettings\":1,\"noChangeTemplate\":1,\"nameContentTab\":1}'),
	(4,'role',4,8,0,'{\"noChildren\":1,\"parentTemplates\":[2],\"slashUrls\":1,\"pageClass\":\"Role\",\"noGlobal\":1,\"noMove\":1,\"noTrash\":1,\"noSettings\":1,\"noChangeTemplate\":1,\"nameContentTab\":1}'),
	(5,'permission',5,8,0,'{\"noChildren\":1,\"parentTemplates\":[2],\"slashUrls\":1,\"guestSearchable\":1,\"pageClass\":\"Permission\",\"noGlobal\":1,\"noMove\":1,\"noTrash\":1,\"noSettings\":1,\"noChangeTemplate\":1,\"nameContentTab\":1}'),
	(1,'home',1,0,0,'{\"useRoles\":1,\"noParents\":1,\"slashUrls\":1,\"roles\":[37]}'),
	(29,'basic-page',83,0,0,'{\"slashUrls\":1}'),
	(26,'search',80,0,0,'{\"noChildren\":1,\"noParents\":1,\"allowPageNum\":1,\"slashUrls\":1}'),
	(34,'sitemap',88,0,0,'{\"noChildren\":1,\"noParents\":1,\"redirectLogin\":23,\"slashUrls\":1}');

/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
