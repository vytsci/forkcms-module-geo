DROP TABLE IF EXISTS `geo_countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_countries` (
  `id` int(11) NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '1',
  `continent_code` varchar(2) NOT NULL,
  `iso_numeric` varchar(3) NOT NULL,
  `iso_alpha_3` varchar(3) NOT NULL,
  `postal_code_format` varchar(50) DEFAULT NULL,
  `currency_code` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `CONTINENT` (`continent_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `geo_countries_locale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_countries_locale` (
  `id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language`),
  CONSTRAINT `fk_geo_countries_locale_to_countries` FOREIGN KEY (`id`) REFERENCES `geo_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `geo_states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_states` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  `fcode` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `COUNTRY` (`country_id`),
  CONSTRAINT `fk_geo_children_to_countries` FOREIGN KEY (`country_id`) REFERENCES `geo_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `geo_states_locale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_states_locale` (
  `id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language`),
  CONSTRAINT `fk_geo_children_locale_to_children` FOREIGN KEY (`id`) REFERENCES `geo_states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `geo_cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_cities` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  `fcode` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `STATE` (`state_id`),
  CONSTRAINT `fk_geo_cities_to_states` FOREIGN KEY (`state_id`) REFERENCES `geo_states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `geo_cities_locale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_cities_locale` (
  `id` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`language`),
  CONSTRAINT `fk_geo_cities_locale_to_cities` FOREIGN KEY (`id`) REFERENCES `geo_cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
