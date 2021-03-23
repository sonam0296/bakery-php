-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `acesso`;
CREATE TABLE `acesso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `super_administrador` tinyint(4) DEFAULT '0',
  `username` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `password_salt` varchar(10) DEFAULT NULL,
  `cod_recupera` varchar(255) DEFAULT NULL,
  `nivel` tinyint(4) DEFAULT '1',
  `nome` varchar(200) DEFAULT NULL,
  `store_name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `funcao` varchar(40) DEFAULT NULL,
  `observacoes` text,
  `imagem1` varchar(250) DEFAULT '041415_1_2194_joaninha.jpg',
  `last_activity` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `notif_from` datetime DEFAULT NULL,
  `lingua` varchar(20) DEFAULT 'pt',
  `n_enc` int(11) DEFAULT NULL,
  `n_tick` int(11) DEFAULT NULL,
  `n_cli` int(11) DEFAULT NULL,
  `activo` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`username`,`activo`,`email`,`super_administrador`,`last_activity`,`last_login`,`notif_from`,`n_enc`,`n_tick`,`n_cli`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `acesso` (`id`, `super_administrador`, `username`, `password`, `password_salt`, `cod_recupera`, `nivel`, `nome`, `store_name`, `email`, `telefone`, `funcao`, `observacoes`, `imagem1`, `last_activity`, `last_login`, `notif_from`, `lingua`, `n_enc`, `n_tick`, `n_cli`, `activo`) VALUES
(1,	1,	'bbakery',	'netg1357',	'7f5',	NULL,	1,	'Administrador Master',	'ADMIN',	'mithilchauhan@gmail.com',	'1332',	'343',	'563',	'041415_1_2194_joaninha.jpg',	'2021-03-17 14:59:07',	'2021-03-17 14:58:10',	'2021-03-17 14:58:10',	'pt',	0,	0,	0,	1),
(2,	0,	'Washwoodheath',	'Wwh1919',	'434',	NULL,	1,	'Netgócio',	'WASHWOOD HEATH BRANCH',	'',	'123',	'',	'',	'041415_1_2194_joaninha.jpg',	'2021-03-11 12:28:16',	'2021-03-11 12:27:49',	'2021-03-11 12:27:49',	'pt',	0,	0,	0,	1),
(3,	0,	'Arock',	'Arock1919',	'570',	NULL,	1,	'Natura',	'ALUM ROCK BRANCH',	'mithilchauhan@gmail.com',	'',	'',	'',	'041415_1_2194_joaninha.jpg',	'2021-03-17 10:16:06',	'2021-03-17 09:25:05',	'2021-03-17 09:25:05',	'pt',	1,	0,	0,	1),
(4,	0,	'DudleyR',	'Dudley1919',	NULL,	NULL,	1,	NULL,	'DUDLEY ROAD BRANCH',	NULL,	NULL,	NULL,	NULL,	'041415_1_2194_joaninha.jpg',	'2021-03-09 10:45:58',	'2021-03-09 10:45:57',	'2021-03-09 10:45:57',	'pt',	0,	0,	0,	1),
(5,	0,	'Sparkhill',	'Shill1919',	NULL,	NULL,	1,	NULL,	'SPARK HILL BRANCH',	NULL,	NULL,	NULL,	NULL,	'041415_1_2194_joaninha.jpg',	'2021-03-09 10:13:02',	'2021-03-09 10:13:01',	'2021-03-09 10:13:01',	'pt',	0,	0,	0,	1),
(6,	0,	'Smallheath',	'Sheath1919',	NULL,	NULL,	1,	NULL,	'SMALL HEATH BRANCH',	NULL,	NULL,	NULL,	NULL,	'041415_1_2194_joaninha.jpg',	'2021-03-13 14:14:11',	'2021-03-13 14:14:11',	'2021-03-13 14:14:11',	'pt',	0,	0,	0,	1),
(7,	0,	'Lozells',	'Lozells1919',	NULL,	NULL,	1,	NULL,	'LOZELLS BRANCH',	NULL,	NULL,	NULL,	NULL,	'041415_1_2194_joaninha.jpg',	'2021-03-13 14:14:19',	'2021-03-13 14:14:19',	'2021-03-13 14:14:19',	'pt',	0,	0,	0,	1),
(8,	0,	'admin',	'Gt3livery!1',	NULL,	NULL,	1,	NULL,	'ADMIN',	NULL,	NULL,	NULL,	NULL,	'041415_1_2194_joaninha.jpg',	'2021-03-17 15:02:37',	'2021-03-17 15:00:34',	'2021-03-17 15:00:34',	'pt',	1,	0,	0,	1);

DROP TABLE IF EXISTS `analytics`;
CREATE TABLE `analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `activo` tinyint(4) DEFAULT '0',
  `ecommerce` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `analytics` (`id`, `email`, `password`, `activo`, `ecommerce`) VALUES
(1,	'mithilchauhan@gmail.com',	'password',	0,	0);

DROP TABLE IF EXISTS `banners_h_en`;
CREATE TABLE `banners_h_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(4) DEFAULT '1' COMMENT '1 - Imagem; 2 - Vídeo',
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(250) DEFAULT NULL,
  `video` text,
  `link_class` varchar(255) DEFAULT NULL COMMENT '"vazio", invert, invert2',
  `link` varchar(250) DEFAULT NULL,
  `target` varchar(250) DEFAULT '0',
  `texto_link` varchar(250) DEFAULT NULL,
  `cor1` varchar(50) DEFAULT NULL,
  `align_h1` varchar(255) DEFAULT 'center',
  `align_v1` varchar(255) DEFAULT 'center',
  `mascara1` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `cor2` varchar(50) DEFAULT NULL,
  `align_h2` varchar(255) DEFAULT 'center',
  `align_v2` varchar(255) DEFAULT 'center',
  `mascara2` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `imagem1` text,
  `imagem2` text,
  `imagem3` text,
  `d_imagem_full` text,
  `m_imagem_full` text,
  `m_d_radio` int(11) DEFAULT NULL,
  `bg_color` varchar(50) DEFAULT NULL,
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `text_alignv` varchar(50) DEFAULT NULL COMMENT 'top, middle, bottom',
  `text_alignh` varchar(50) DEFAULT NULL COMMENT 'left, center, right',
  `slide_duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`datai`,`dataf`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `banners_h_en` (`id`, `tipo`, `nome`, `titulo`, `subtitulo`, `video`, `link_class`, `link`, `target`, `texto_link`, `cor1`, `align_h1`, `align_v1`, `mascara1`, `cor2`, `align_h2`, `align_v2`, `mascara2`, `imagem1`, `imagem2`, `imagem3`, `d_imagem_full`, `m_imagem_full`, `m_d_radio`, `bg_color`, `datai`, `dataf`, `ordem`, `visivel`, `text_alignv`, `text_alignh`, `slide_duration`) VALUES
(1,	1,	'Home Banner',	'',	'',	'',	NULL,	'',	'_blank',	'',	'',	'center',	'center',	0,	'',	'center',	'center',	0,	NULL,	NULL,	NULL,	'events-2607706_1920.jpg',	'events-2607706_1920.jpg',	1,	'#E5F8E9',	NULL,	NULL,	99,	1,	NULL,	NULL,	NULL),
(2,	1,	'Banner2',	'',	'',	'',	NULL,	'',	'_blank',	'',	'',	'center',	'center',	0,	'',	'center',	'center',	0,	NULL,	NULL,	NULL,	'Capture.PNG',	'cakes-2600951_1920.jpg',	1,	'#E5F8E9',	NULL,	NULL,	99,	1,	NULL,	NULL,	NULL),
(3,	1,	'Banner 3',	'',	'',	'',	NULL,	'',	'_blank',	'',	'',	'center',	'center',	0,	'',	'center',	'center',	0,	NULL,	NULL,	NULL,	'birthday-1835443_1920.jpg',	'birthday-1835443_1920.jpg',	1,	'#E5F8E9',	NULL,	NULL,	99,	1,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `banners_h_pt`;
CREATE TABLE `banners_h_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(4) DEFAULT '1' COMMENT '1 - Imagem; 2 - Vídeo',
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(250) DEFAULT NULL,
  `video` text,
  `link_class` varchar(255) DEFAULT NULL COMMENT '"vazio", invert, invert2',
  `link` varchar(250) DEFAULT NULL,
  `target` varchar(250) DEFAULT '0',
  `texto_link` varchar(250) DEFAULT NULL,
  `cor1` varchar(50) DEFAULT NULL,
  `align_h1` varchar(255) DEFAULT 'center',
  `align_v1` varchar(255) DEFAULT 'center',
  `mascara1` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `cor2` varchar(50) DEFAULT NULL,
  `align_h2` varchar(255) DEFAULT 'center',
  `align_v2` varchar(255) DEFAULT 'center',
  `mascara2` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `imagem1` text,
  `imagem2` text,
  `imagem3` text,
  `d_imagem_full` text,
  `m_imagem_full` text,
  `m_d_radio` int(11) DEFAULT NULL,
  `bg_color` varchar(50) DEFAULT NULL,
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `text_alignv` varchar(50) DEFAULT NULL COMMENT 'top, middle, bottom',
  `text_alignh` varchar(50) DEFAULT NULL COMMENT 'left, center, right',
  `slide_duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`datai`,`dataf`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `banners_popups_en`;
CREATE TABLE `banners_popups_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(4) DEFAULT '1' COMMENT '1 - Geral; 2 - Newsleter',
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `texto` text,
  `link` varchar(250) DEFAULT NULL,
  `target` varchar(250) DEFAULT '0',
  `texto_link` varchar(250) DEFAULT NULL,
  `cor1` varchar(50) DEFAULT NULL,
  `align_h1` varchar(255) DEFAULT 'center',
  `align_v1` varchar(255) DEFAULT 'center',
  `mascara1` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `cor2` varchar(50) DEFAULT NULL,
  `align_h2` varchar(255) DEFAULT 'center',
  `align_v2` varchar(255) DEFAULT 'center',
  `mascara2` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `imagem1` text,
  `imagem2` text,
  `imagem3` text,
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `tipo_cliente` int(11) DEFAULT NULL,
  `cliente_registo` int(11) DEFAULT '3',
  `timer` int(11) DEFAULT '0',
  `cod_prom` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`datai`,`dataf`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `banners_popups_en` (`id`, `tipo`, `nome`, `titulo`, `subtitulo`, `texto`, `link`, `target`, `texto_link`, `cor1`, `align_h1`, `align_v1`, `mascara1`, `cor2`, `align_h2`, `align_v2`, `mascara2`, `imagem1`, `imagem2`, `imagem3`, `datai`, `dataf`, `codigo`, `tipo_cliente`, `cliente_registo`, `timer`, `cod_prom`, `ordem`, `visivel`) VALUES
(1,	2,	'Banner Popup 1',	'Titulo Simulado ',	'',	'Subtitulo simulado para preencher espa&ccedil;o',	'https://www.pontodasartes.com/pt/',	'_blank',	'Saiba Mais',	NULL,	NULL,	NULL,	0,	'',	'center',	'center',	1,	'034623_1_5849_Derwent_Procolour_2017.jpg',	'034636_2_7428_Derwent_Procolour_2017.jpg',	'pq_034623_1_5849_Derwent_Procolour_2017.jpg',	'2020-09-03',	'2025-12-29',	'K0op3mKaknSbpfbcEoyNdmDK',	3,	3,	10,	1,	2,	0),
(2,	1,	'Banner PopUp 2',	'Titulo Simulado 2',	'',	'Subtitulo simulado 2 para preencher espa&ccedil;o',	'https://www.pontodasartes.com/pt/',	'_blank',	'Saiba Mais',	'',	'center',	'center',	0,	'',	'center',	'center',	0,	'034714_1_4283_ponto_artes.jpg',	'034725_2_9654_ponto_artes.jpg',	'pq_034714_1_4283_ponto_artes.jpg',	NULL,	NULL,	'SxzWlTuLaV7m8ePBqcwTx6gX',	3,	3,	0,	0,	3,	0),
(3,	2,	'Banner PopUp Newsletter',	'Titulo Simulado para Newsletter',	NULL,	'',	'',	'0',	'Ver Mais',	'',	'center',	'center',	0,	'',	'center',	'center',	0,	'044145_1_252_newsletter.jpg',	'034946_2_8780_banner_cheque2.jpg',	'pq_034938_1_6172_banner_cheque2.jpg',	'2020-09-07',	'2020-09-12',	'C9hMEaFKcDB1r0IAhMMuMuM3',	1,	2,	0,	NULL,	4,	0),
(4,	1,	'444',	'Título exemplo1',	NULL,	'ertretetreert',	'',	'0',	'',	NULL,	'center',	'center',	0,	NULL,	'center',	'center',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'tmZxRlZugRwksvieTbZR1m1u',	3,	3,	3,	0,	1,	0),
(5,	1,	'Popup 2',	'Titulo de Exemplo 2',	NULL,	'Exemplo 2',	'#',	'_parent',	'Link',	NULL,	'center',	'center',	0,	NULL,	'center',	'center',	0,	NULL,	NULL,	NULL,	'2020-09-01',	'2020-12-31',	'Hz3Yrpy9QU4K5RL3Opd9ia7r',	1,	3,	3,	0,	99,	0),
(6,	1,	'Teste Daniel 001',	'Titulo Teste Daniel 001',	NULL,	'Texto Teste Daniel 001<br />\r\nsdfasdfasdkfj al sdkjfhakldhflkahdflahsdfkajshdfkahlsdf<br />\r\naskjdfa ksdjfhl',	'www.google.com.br',	'_blank',	'Google',	NULL,	'center',	'center',	0,	NULL,	'center',	'center',	0,	NULL,	NULL,	NULL,	'2020-09-01',	'2020-09-30',	'KlQzEs0dgq0esxKtuUfocgqO',	3,	3,	1,	0,	99,	0);

DROP TABLE IF EXISTS `banners_popups_pt`;
CREATE TABLE `banners_popups_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(4) DEFAULT '1' COMMENT '1 - Geral; 2 - Newsleter',
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `subtitulo` varchar(255) DEFAULT NULL,
  `texto` text,
  `link` varchar(250) DEFAULT NULL,
  `target` varchar(250) DEFAULT '0',
  `texto_link` varchar(250) DEFAULT NULL,
  `cor1` varchar(50) DEFAULT NULL,
  `align_h1` varchar(255) DEFAULT 'center',
  `align_v1` varchar(255) DEFAULT 'center',
  `mascara1` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `cor2` varchar(50) DEFAULT NULL,
  `align_h2` varchar(255) DEFAULT 'center',
  `align_v2` varchar(255) DEFAULT 'center',
  `mascara2` tinyint(4) DEFAULT '0' COMMENT '0 - Não aplicar máscara; 1 - Aplicar',
  `imagem1` text,
  `imagem2` text,
  `imagem3` text,
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `tipo_cliente` int(11) DEFAULT NULL,
  `cliente_registo` int(11) DEFAULT '3',
  `timer` int(11) DEFAULT '0',
  `cod_prom` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`datai`,`dataf`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `banners_popups_pt` (`id`, `tipo`, `nome`, `titulo`, `subtitulo`, `texto`, `link`, `target`, `texto_link`, `cor1`, `align_h1`, `align_v1`, `mascara1`, `cor2`, `align_h2`, `align_v2`, `mascara2`, `imagem1`, `imagem2`, `imagem3`, `datai`, `dataf`, `codigo`, `tipo_cliente`, `cliente_registo`, `timer`, `cod_prom`, `ordem`, `visivel`) VALUES
(1,	2,	'Banner Popup 1',	' Aqui temos um título',	NULL,	'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.',	'https://www.pontodasartes.com/pt/',	'_blank',	'Subscrever',	NULL,	NULL,	NULL,	0,	'',	'center',	'center',	1,	'035052_1_9065_404-2x.jpg',	'034636_2_7428_Derwent_Procolour_2017.jpg',	'pq_035052_1_9065_404-2x.jpg',	'2020-09-03',	'2025-12-29',	'K0op3mKaknSbpfbcEoyNdmDK',	3,	3,	10,	1,	2,	0),
(2,	1,	'Banner PopUp 2',	'Titulo Simulado 2',	NULL,	'teste&nbsp; &nbsp;Subtitulo simulado 2 para preencher espa&ccedil;o',	'https://www.pontodasartes.com/pt/',	'_blank',	'Saiba Mais',	'',	'center',	'center',	0,	'',	'center',	'center',	0,	'034714_1_4283_ponto_artes.jpg',	'034725_2_9654_ponto_artes.jpg',	'pq_034714_1_4283_ponto_artes.jpg',	NULL,	NULL,	'SxzWlTuLaV7m8ePBqcwTx6gX',	3,	3,	0,	0,	3,	0),
(3,	2,	'Banner PopUp Newsletter',	'Titulo Simulado para Newsletter',	NULL,	'',	'',	'0',	'Ver Mais',	'',	'center',	'center',	0,	'',	'center',	'center',	0,	'044122_1_6932_newsletter.jpg',	'034946_2_8780_banner_cheque2.jpg',	'pq_034938_1_6172_banner_cheque2.jpg',	'2020-09-07',	'2020-09-12',	'C9hMEaFKcDB1r0IAhMMuMuM3',	1,	2,	0,	NULL,	4,	0),
(4,	1,	'4441',	'Título exemplo1',	NULL,	'ertretetreert1',	'',	'0',	'',	NULL,	'center',	'center',	0,	NULL,	'center',	'center',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'tmZxRlZugRwksvieTbZR1m1u',	3,	3,	3,	0,	1,	0),
(5,	1,	'Popup 2',	'Titulo de Exemplo 2',	NULL,	'Exemplo 2',	'#',	'_parent',	'Link',	NULL,	'center',	'center',	0,	NULL,	'center',	'center',	0,	NULL,	NULL,	NULL,	'2020-09-01',	'2020-12-31',	'Hz3Yrpy9QU4K5RL3Opd9ia7r',	1,	3,	3,	0,	99,	0),
(6,	1,	'Teste Daniel 001',	'Titulo Teste Daniel 001',	NULL,	'Texto Teste Daniel 001<br />\r\nsdfasdfasdkfj al sdkjfhakldhflkahdflahsdfkajshdfkahlsdf<br />\r\naskjdfa ksdjfhl',	'www.google.com.br',	'_blank',	'Google',	NULL,	'center',	'center',	0,	NULL,	'center',	'center',	0,	'110144_1_9567_vinyl-pvc-banners-1.jpg',	NULL,	NULL,	'2020-09-01',	'2020-09-30',	'KlQzEs0dgq0esxKtuUfocgqO',	3,	3,	1,	0,	99,	0);

DROP TABLE IF EXISTS `branch_list`;
CREATE TABLE `branch_list` (
  `id` int(8) NOT NULL,
  `roll_id` int(8) DEFAULT NULL,
  `branch_name` text NOT NULL,
  `ordem` text NOT NULL,
  `visivel` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `branch_list` (`id`, `roll_id`, `branch_name`, `ordem`, `visivel`) VALUES
(1,	7,	'WashWood Heath',	'',	1),
(2,	7,	'Alum Rock',	'',	1),
(3,	7,	'Dudley',	'',	1),
(4,	7,	'Sparkhill',	'',	0),
(5,	7,	'Lozells',	'',	0),
(6,	7,	'admin',	'',	0),
(7,	7,	'Smallheath',	'',	0);

DROP TABLE IF EXISTS `carrinho`;
CREATE TABLE `carrinho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `id_linha` int(11) DEFAULT NULL,
  `id_oferta` int(11) DEFAULT '0',
  `session` bigint(20) DEFAULT NULL,
  `produto` int(11) DEFAULT NULL,
  `opcoes` text,
  `quantidade` int(11) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `message` text,
  `desconto` int(11) DEFAULT '0',
  `op1` int(11) DEFAULT '0',
  `op2` int(11) DEFAULT '0',
  `op3` int(11) DEFAULT '0',
  `op4` int(11) DEFAULT '0',
  `op5` int(11) DEFAULT '0',
  `data` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `cheque_prenda` tinyint(4) DEFAULT '0',
  `cheque_nome` varchar(100) DEFAULT NULL,
  `cheque_email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_cliente`,`session`,`produto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `carrinho` (`id`, `id_cliente`, `id_linha`, `id_oferta`, `session`, `produto`, `opcoes`, `quantidade`, `preco`, `message`, `desconto`, `op1`, `op2`, `op3`, `op4`, `op5`, `data`, `ip`, `cheque_prenda`, `cheque_nome`, `cheque_email`) VALUES
(4,	55,	NULL,	0,	1616421783,	161,	'Size: 8\";',	1,	12.00,	's',	0,	4,	0,	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL);

DROP TABLE IF EXISTS `carrinho_cliente`;
CREATE TABLE `carrinho_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `enviado` int(11) DEFAULT '0',
  `data_enviado` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `carrinho_cliente` (`id`, `id_cliente`, `data`, `enviado`, `data_enviado`) VALUES
(8,	1,	'2020-07-13 06:21:29',	0,	NULL),
(9,	13,	'2020-07-17 11:53:37',	0,	NULL),
(12,	18,	'2020-08-27 06:32:47',	0,	NULL),
(28,	60,	'2020-09-18 15:02:11',	0,	NULL),
(29,	7,	'2020-09-21 18:06:14',	0,	NULL),
(32,	63,	'2020-09-18 15:01:17',	0,	NULL),
(33,	54,	'2020-10-27 08:04:23',	0,	NULL),
(36,	58,	'2020-12-21 09:43:52',	0,	NULL),
(38,	57,	'2021-03-01 09:35:55',	0,	NULL),
(57,	59,	'2021-03-03 10:47:19',	0,	NULL),
(65,	55,	'2021-03-22 14:03:17',	0,	NULL);

DROP TABLE IF EXISTS `carrinho_cliente_hist`;
CREATE TABLE `carrinho_cliente_hist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `data_enviado` datetime DEFAULT NULL,
  `total_produtos` int(11) DEFAULT '0',
  `total_carrinho` decimal(10,2) DEFAULT '0.00',
  `codigo` varchar(255) DEFAULT NULL,
  `aberto` tinyint(4) DEFAULT '0',
  `data_aberto` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `carrinho_cod_prom`;
CREATE TABLE `carrinho_cod_prom` (
  `session` bigint(20) DEFAULT NULL,
  `id_codigo` int(11) DEFAULT '0',
  `codigo` varchar(30) DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT '0.00',
  KEY `indice` (`session`,`id_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `carrinho_comprar`;
CREATE TABLE `carrinho_comprar` (
  `session` bigint(20) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT '0.00',
  KEY `indice` (`session`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `category_store`;
CREATE TABLE `category_store` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` varchar(10) DEFAULT NULL,
  `store_id` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `primery` varchar(3) DEFAULT NULL,
  `ordem` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `category_store` (`id`, `category_id`, `store_id`, `phone`, `primery`, `ordem`) VALUES
(9,	'3',	'4',	NULL,	NULL,	NULL),
(10,	'3',	'6',	NULL,	NULL,	NULL),
(11,	'3',	'7',	NULL,	NULL,	NULL),
(12,	'4',	'4',	NULL,	NULL,	NULL),
(13,	'4',	'6',	NULL,	NULL,	NULL),
(14,	'4',	'7',	NULL,	NULL,	NULL),
(16,	'11',	'7',	NULL,	NULL,	NULL),
(17,	'11',	'9',	NULL,	NULL,	NULL),
(18,	'13',	'4',	NULL,	NULL,	NULL),
(19,	'13',	'7',	NULL,	NULL,	NULL),
(20,	'13',	'8',	NULL,	NULL,	NULL),
(21,	'12',	'2',	NULL,	NULL,	NULL),
(22,	'12',	'6',	NULL,	NULL,	NULL),
(23,	'12',	'7',	NULL,	NULL,	NULL),
(24,	'12',	'8',	NULL,	NULL,	NULL),
(25,	'2',	'7',	NULL,	NULL,	NULL),
(26,	'11',	'6',	NULL,	NULL,	NULL),
(27,	'3',	'2',	NULL,	NULL,	NULL),
(28,	'6',	'7',	NULL,	NULL,	NULL),
(29,	'10',	'2',	NULL,	NULL,	NULL),
(30,	'4',	'2',	NULL,	NULL,	NULL),
(32,	'11',	'2',	NULL,	NULL,	NULL),
(33,	'14',	'2',	NULL,	NULL,	NULL),
(34,	'14',	'7',	NULL,	NULL,	NULL),
(35,	'14',	'8',	NULL,	NULL,	NULL),
(36,	'14',	'2',	NULL,	NULL,	NULL),
(37,	'19',	'2',	NULL,	NULL,	NULL),
(38,	'20',	'2',	NULL,	NULL,	NULL),
(39,	'21',	'2',	NULL,	NULL,	NULL),
(40,	'21',	'7',	NULL,	NULL,	NULL),
(43,	'8',	'2',	NULL,	NULL,	NULL),
(45,	'5',	'2',	NULL,	NULL,	NULL),
(46,	'7',	'2',	NULL,	NULL,	NULL),
(47,	'9',	'2',	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `chronopost_pickme`;
CREATE TABLE `chronopost_pickme` (
  `id_pickme_shop` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pickme_id` varchar(30) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `location` varchar(400) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_pickme_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `chronopost_pickme` (`id_pickme_shop`, `pickme_id`, `name`, `address`, `location`, `postal_code`) VALUES
(1,	'X0021',	'TABACARIA PINGUIM',	'RUA DONA ESTEFANIA, 163 B ',	'LISBOA',	'1000-154'),
(2,	'X0061',	'OFFICEPAK DUQUE AVILA',	'AV.DUQUE DE AVILA, 92 APISO -1',	'LISBOA',	'1050-084'),
(3,	'X0051',	'5 A SEC SALDANHA',	'C.C. SALDANHA RESIDENCE LJ 28 ',	'LISBOA',	'1050-105'),
(4,	'X5631',	'TABACARIA APOLO 70',	'AV JULIO DINIS 10 A LJ 3CC APOLO 70',	'LISBOA',	'1050-131'),
(5,	'X5751',	'PAP FIGUEIREDO E GOMES',	'R PROF SOUSA CAMARA 206 ACAMPOLIDE',	'LISBOA',	'1070-219'),
(6,	'X0031',	'COSTURAS E COMPANHIA',	'RUA SAMPAIO PINA, 13 ',	'LISBOA',	'1070-249'),
(7,	'X5041',	'TABACARIA GLORIA',	'RUA DO OURO 178 ',	'LISBOA',	'1100-064'),
(8,	'X0091',	'TABACARIA DO DESTERRO',	'RUA NOVA DO DESTERRO, 29 G ',	'LISBOA',	'1150-241'),
(9,	'X5031',	'PAPELARIA VISSRAM',	'RUA FORNO DO TIJOLO 16B ',	'LISBOA',	'1170-135'),
(10,	'X3981',	'PAPELARIA TIMOTEO',	'AV. GENERAL ROCADAS, 72 C ',	'LISBOA',	'1170-164'),
(11,	'X4981',	'PAPELARIA MEDALHAS NOVIDADES',	'AV D CARLOS I 53 ',	'LISBOA',	'1200'),
(12,	'X0181',	'5 A SEC DA AJUDA',	'CALCADA DA AJUDA, 135 A ',	'LISBOA',	'1300-008'),
(13,	'X3561',	'PAPELARIA KAKA',	'CALCADA DA TAPADA, 170 A ',	'LISBOA',	'1300-551'),
(14,	'X0171',	'5 A FIL C. OURIQUE',	'MER. MUNICIPAL FRENTE AO N 100RUA COELHO ROCHA',	'LISBOA',	'1350-303'),
(15,	'X0201',	'TAB IMPERIO DO RESTELO',	'R GONCALVES ZARCO 20 ARESTELO',	'LISBOA',	'1400-192'),
(16,	'X0191',	'LAVANDARIA SELF AMERICA',	'AV BOMB. VOLUNTARIOS 43-C ',	'ALGES',	'1495-025'),
(17,	'X0221',	'PAP. TABACARIA AFRODITE',	'AV GOMES PEREIRA, 41 B ',	'LISBOA',	'1500-328'),
(18,	'X0211',	'LAV. NORSEC/TOP COSTURA COLOMBO',	'AV LUSIADAC.C. COLOMBO LOJA 0.513 PISO -1',	'LISBOA',	'1500-392'),
(19,	'X0231',	'PAP. DUARTE CORDEIRO BENF.',	'CALCADA DO TOJAL 90 A ',	'LISBOA',	'1500-596'),
(20,	'X0261',	'5 A SEC ESTRADA LUZ',	'ESTRADA DA LUZ 191-A ',	'LISBOA',	'1600-155'),
(21,	'X0251',	'5 A SEC TELHEIRAS',	'RUA PROF. FRANCISCO GENTILEDIFICIO E1 - LOJA 2 I',	'LISBOA',	'1600-225'),
(22,	'X0241',	'FLORISTA VIOLETA-MERCADO',	'PC BENT0 J CARACA,15ACAVE C',	'PONTINHA',	'1675-103'),
(23,	'X0321',	'TABACARIA NOVA',	'LG CRISTOVAO AIRES, 6A/6B ',	'LISBOA',	'1700-126'),
(24,	'X0281',	'COSTURAS E COMPANHIA',	'RUA JOSE DURO 26 BALVALADE',	'LISBOA',	'1700-261'),
(25,	'X0311',	'5 A SEC QTA LAMBERT CONTINENTE',	'R AGOSTINHO NETO,5LT 6 A VILA LAMBERT',	'LISBOA',	'1750-006'),
(26,	'X0301',	'PAPEL E VICIOS',	'RUA M HELENA VIEIRA SILVA,LOTE 22 B',	'LISBOA',	'1750-010'),
(27,	'X4691',	'FORCA DE ELITE',	'AV ALVARO CUNHAL LJ 11 LUMIAR ',	'LISBOA',	'1750-316'),
(28,	'X4131',	'TRATAROUPA',	'RUA CIDADE DE CABINDA, 8 B ',	'LISBOA',	'1800-080'),
(29,	'X3511',	'CHRONOPOST LISBOA PICKME',	'AV.INF.D.HENRIQUE,LT10OLIVAIS SUL',	'LISBOA',	'1849-003'),
(30,	'X4951',	'PAPELARIA SOCOMOL',	'AV MOSCAVIDE 5 A/B ',	'MOSCAVIDE',	'1885-064'),
(31,	'X3891',	'PAP JARDINS CRISTO REI',	'AV CAP SALG  MAIA 15 LJ 4 ',	'PORTELA',	'1885-091'),
(32,	'X5061',	'LOJINHA DA ANITA',	'R PROF MIRA FERNANDES LT 18 LJA ',	'LISBOA',	'1900-380'),
(33,	'X5581',	'PAP NOVO ESTILO',	'R ATRIZ PALMIRA BASTOS L43 LJ5CHELAS',	'LISBOA',	'1950-004'),
(34,	'X5941',	'5 A SEC SANTAREM',	'QTA S BERNAR CONTINENTE,LJ13MARVILA',	'SANTAREM',	'2000'),
(35,	'X5821',	'PAPELARIA PRESS NEWS',	'W SHOPPING LOJA 1.33RUA PEDRO SANTAREM 29',	'SANTAREM',	'2000-223'),
(36,	'X4211',	'PAP. ISAMAR',	'R.MAESTRO FERNANDO CARVALHO 13A ',	'RIO MAIOR',	'2040-327'),
(37,	'X3841',	'LAVANDARIA BELIMPA II',	'ATRIUM AZAMBUJA, LJ 2RUA ENGENHEIRO MONIZ DA MAIA',	'AZAMBUJA',	'2050-356'),
(38,	'X4501',	'MUNDO DIDAKTICO',	'RUA JOSE RIBEIRO DA COSTA, 1LOJA CAVE',	'CARTAXO',	'2070-099'),
(39,	'X0441',	'5 A SEC ALMEIRIM',	'CENTRO COMERCIAL INTERMARCHE ',	'ALMEIRIM',	'2080-001'),
(40,	'X4031',	'PAP. LAPIS E COMPANHIA',	'RUA DA MISERICORDIA, 34 ',	'CORUCHE',	'2100-134'),
(41,	'X5051',	'PAPELARIA O TOSTAO',	'EN 367 N223 ',	'MARINHAIS',	'2125-120'),
(42,	'X0461',	'5 A SEC SAMORA CORREIA',	'AV EGAS MONIZ 12 ',	'SAMORA CORREIA',	'2135-232'),
(43,	'X5931',	'5 A SEC ABRANTES',	'C.C. PINGO DOCE, LJ 3EST NAC 3 ENCOSTA A BARATA',	'ABRANTES',	'2200-100'),
(44,	'X5331',	'5 A SEC TOMAR',	'CC MODELO LJ 14LUGAR PALHAVA',	'TOMAR',	'2300-410'),
(45,	'X0521',	'LIVRO SABIO',	'AV D NUNO ALV PEREIRA 5 ',	'ENTRONCAMENTO',	'2330-141'),
(46,	'X0511',	'5 A SEC ENTRONCAMENTO',	'C.C. E LECLERC LJ 3 ',	'ENTRONCAMENTO',	'2330-218'),
(47,	'X0501',	'5 A SEC  T. NOVAS',	'C.C.MODELO LJ 12,S.ANTONIOCHAS- SANTA MARIA',	'TORRES NOVAS',	'2350-537'),
(48,	'X0481',	'PAPELARIA TRIVIAL',	'RUA DA SAUDADEMERCADO MUNICIPAL, LOJA 8',	'ALCANENA',	'2380-054'),
(49,	'X5341',	'SENHOR SAPATO',	'CC LIS LJ 24RUA WENCESLAU DE MORAIS 11',	'LEIRIA',	'2400-259'),
(50,	'X0551',	'5 A SEC LEIRIA',	'LEIRIA SHOPPING,LOJA 70PARCEIROS',	'LEIRIA',	'2400-441'),
(51,	'X0531',	'BOTA.COM',	'RUA MIGUEL TORGA,147 ',	'LEIRIA',	'2410-134'),
(52,	'X5991',	'PTELEMOVEIS',	'ESTR CARREIRA TIRO LT 29/20 ',	'GANDARA DOS OLIVAIS',	'2415-598'),
(53,	'X0611',	'REAL ESTUDO',	'RUA LEIRIA,23 A ',	'MONTE REAL',	'2425-039'),
(54,	'X0581',	'JORNALINHO AVENIDA',	'AV VITOR GALLO 51,RC ',	'MARINHA GRANDE',	'2430-171'),
(55,	'X3761',	'LCORTES INFORMATICA PAP.',	'RUA DA INDIA, 39ORDEM',	'MARINHA GRANDE',	'2430-370'),
(56,	'X5741',	'PAPELARIA CONDESTAVEL',	'PRACA D JOAO I LJ 3C ',	'BATALHA',	'2440-108'),
(57,	'X0631',	'PAPELARIA ARTE E VELAS',	'AV. VIEIRA GUIMARAES,15 A ',	'NAZARE',	'2450-110'),
(58,	'X3741',	'FLORISTA TREVO',	'RUA AFONSO DE ALBUQUERQUE, 11 ',	'ALCOBACA',	'2460-020'),
(59,	'X0621',	'LAVANDARIA SAO PEDRO',	'RUA 5 DE OUTUBRO 5LOJA B',	'PORTO DE MOS',	'2480-326'),
(60,	'X5911',	'PAPELARIA INTERMARCHE',	'C.C. VILA SHOPPING LJ 13RUA NAMORADOS',	'OUREM',	'2490-339'),
(61,	'X4041',	'PAPELARIA BEMPOSTA',	'RUA FRANCISCO ASSISEDIFICIO PANORAMICO LJ 12',	'FATIMA',	'2495-432'),
(62,	'X3501',	'PAPELARIA VOGAL',	'AVENIDA 1 MAIO,8LOJA DTA, CAVE',	'CALDAS DA RAINHA',	'2500-081'),
(63,	'X0721',	'5 A SEC MODELO',	'C.C. MODELO LJA 2/3R. DR. BER. ANT. CARV. PARGANA',	'CALDAS DA RAINHA',	'2500-136'),
(64,	'X0691',	'5 A SEC VIVACI',	'C.C. VIVACIRUA BELCHIOR MATOS 11 LJ 11',	'CALDAS DA RAINHA',	'2500-303'),
(65,	'X0671',	'PAP.PERGAMINHO',	'AV 25 DE ABRIL, 106 ',	'PENICHE',	'2520-203'),
(66,	'X0641',	'LOURIPAPEL',	'PRACA MARQUES DE POMBAL, 10LOURINHA',	'LOURINHA',	'2530-127'),
(67,	'X4531',	'PAP. ACETATO',	'AV DR JOAQUIM ALBUQUERQUE 40 LJA ',	'BOMBARRAL',	'2540-004'),
(68,	'X0651',	'CASA DA SORTE CADAVAL',	'LG NUNO ALVARES PEREIRA,3 ',	'CADAVAL',	'2550-116'),
(69,	'X4271',	'PAP UNIAO II',	'RUA ANTO LEAL DASCENSAO 6 C ',	'TORRES VEDRAS',	'2560-309'),
(70,	'X0661',	'PAP.UNIAO V',	'R HENRIQUES NOGUEIRA 35 ',	'TORRES VEDRAS',	'2560-346'),
(71,	'X4521',	'O FESTAROLAS',	'RUA SACADURA CABRAL BL A RC DT ',	'ALENQUER',	'2580-366'),
(72,	'X5891',	'PAPELARIA INTERMARCHE',	'EDIF INTERMARCHEQTA COLONIA LT 2',	'CARREGADO',	'2580-491'),
(73,	'X4671',	'PAP TIMOTEO',	'RUA HEROIS BELGICA 85 ',	'SOBRAL DE MONTE AGRACO',	'2590-022'),
(74,	'X3681',	'PAP. EXTREMIS',	'R. DR. MIGUEL BOMBARDA, LJ E1EDIFICIO ALVES REDOL',	'VILA FRANCA',	'2600-197'),
(75,	'X4821',	'PAP BELLAS',	'PRACA 5 OUTUBRO 11 LJ ESQ ',	'BELAS',	'2605-021'),
(76,	'X4101',	'LIVRARIA GAVETO',	'PRACA D AFONSO V 5 A ',	'ALVERCA',	'2615-357'),
(77,	'X4831',	'PAP CINDERELA',	'RUA CANDIDO REIS 123 ',	'ARRUDA DOS VINHOS',	'2630-216'),
(78,	'X0941',	'5 A SEC DA RINCHOA',	'R  PARQUE INFANTIL, 1,LJRINCHOA',	'RIO DE MOURO',	'2635-336'),
(79,	'X0891',	'PAP PIRAMIDE DO SUCESSO',	'R NUNO GONCALVES,18MERCES',	'RIO DE MOURO',	'2635-438'),
(80,	'X4391',	'BAZAR DEL RUI',	'AV. BOMBEIROS VOLUNTARIOS 1B ',	'MAFRA',	'2640-462'),
(81,	'X0791',	'5 A SEC MAFRA',	'C.C.MODELO-EN 116 LJ 3 SALGADOS ',	'MAFRA',	'2640-577'),
(82,	'X1171',	'5 A SEC CASCAIS SHOPPING',	'ESTRADA NACIONAL 9C.C. CASCAIS SHOPPING LJ 49',	'ALCABIDECHE',	'2645-243'),
(83,	'X5641',	'PAP SEQUEIRA',	'CC STO ANTO LJ 18/22RUA STO ANTO PONTE FRIELAS',	'STO ANTO CAVALEIROS',	'2660-321'),
(84,	'X0771',	'5 A SEC MALVEIRA',	'EDIF. INTERMARCHE 27,LJ 3ESTRADA NACIONAL 8,LAGOA',	'MALVEIRA',	'2665-258'),
(85,	'X5881',	'PERFUMARIA TAVARES E FILHOS',	'RUA DA REPUBLICA 61 ',	'LOURES',	'2670-473'),
(86,	'X0881',	'LIVRARIA PAMINU',	'RUA DR. CAMARA PESTANA N 1 ',	'ODIVELAS',	'2675-307'),
(87,	'X4851',	'MOBILSTORE',	'RUA PULIDO VALENTE 16 LJ1COLINAS CRUZEIRO',	'ODIVELAS',	'2675-487'),
(88,	'X4061',	'PAP PINCELADAS DE SONHOS',	'LARGO MERCADO 1 MAIO, 8 ',	'SACAVEM',	'2685-099'),
(89,	'X0861',	'PAP CAST PALAVRAS',	'UB QT CASTELO,7 RC DTPIRESCOXE',	'STA IRIA DE AZOIA',	'2690-414'),
(90,	'X0871',	'PETER S COPY',	'RUA ANTO FERREIRA,10 ',	'BOBADELA',	'2695-019'),
(91,	'X5471',	'NUVEM LAVANDA',	'R ALFR VICTORINO COSTA 72BVALE FIGUEIRA',	'SAO JOAO DA TALHA',	'2695-772'),
(92,	'X1261',	'PAPELARIA SISSI',	'RUA CANDIDO DOS REIS, 23BMINA',	'AMADORA',	'2700-142'),
(93,	'X1111',	'5 A SEC BABILONIA AMADORA',	'C.C BABILONIA LJ 10B ',	'AMADORA',	'2700-405'),
(94,	'X1191',	'5 A SEC ALFORNELOS',	'C. C. COLINA DO SOL,LOJA 11ALFORNELOS',	'ALFORNELOS',	'2700-554'),
(95,	'X1231',	'PAPELARIA SAO BRAS',	'ESTRADA SERRA DA MIRA, 44CASAL SAO BRAS',	'AMADORA',	'2700-788'),
(96,	'X1201',	'LAVALIMPA',	'RUA TOME BARROS QUEIROZ, 29A ',	'SINTRA',	'2710-624'),
(97,	'X1001',	'FEITO AO MOMENTO',	'LG CRIACAO MOV J F PINH.LT 4 LOJA DTA',	'PERO PINHEIRO',	'2715-003'),
(98,	'X1241',	'PAP. DUARTE CORDEIRO REBOL.',	'R. PEDRO DE NEGRO 3AREBOLEIRA',	'AMADORA',	'2720-449'),
(99,	'X5841',	'HAVANEZA DA DAMAIA',	'RUA VIEIRA LUSITANO 7ADAMAIA',	'AMADORA',	'2720-539'),
(100,	'X5811',	'PAP CANTINHO DAS FOFOCAS',	'AV ALM GAGO COUT 22 ',	'MEM MARTINS',	'2725-319'),
(101,	'X1271',	'PAPELARIA JULIA',	'ESTRADA DE MEM MARTINS, 177 ',	'MEM MARTINS',	'2725-389'),
(102,	'X1311',	'RODNIC PAPELARIA',	'AV. MIGUEL TORGA, 24 ATAPADA DAS MERCES',	'MEM MARTINS',	'2725-563'),
(103,	'X1301',	'TABACARIA PAPELARIA COSTA',	'LARGO D.MARIA IIC.C. MUNICIPAL DO CACEM LJ 70',	'CACEM',	'2735-001'),
(104,	'X1221',	'PAPELARIA KNICK KNACK',	'RUA ANTO NUNES SEQUEIRA, 32LJ 2 - C.C. 81',	'CACEM',	'2735-056'),
(105,	'X4931',	'PAPELARIA DIURNALE',	'CC FONTE DAS EIRASAV BONS AMIGOS 87 LJ5',	'CACEM',	'2735-081'),
(106,	'X1281',	'PAPELARIA AQUARELA',	'C.C. SAO MARCOSPISO 1 LJ 4',	'CACEM',	'2735-222'),
(107,	'X0921',	'PAPELARIA ESTUDANTINA',	'RUA COMB GRANDE GUERRA,52A ',	'QUELUZ',	'2745-094'),
(108,	'X1131',	'5 A SEC RUA DIREITA',	'RUA DIREITA DE MASSAMA, 97BMASSAMA',	'QUELUZ',	'2745-756'),
(109,	'X1141',	'5 A SEC MONTE ABRAAO',	'AV D ANTO. CORREIA SA,17 LJQUELUZ OCIDENTAL',	'QUELUZ',	'2745-807'),
(110,	'X0981',	'5 A SEC DE MASSAMA',	'AV 25 DE ABRIL LT 221 2-3MASSAMA',	'QUELUZ',	'2745-822'),
(111,	'X4441',	'PAP CRISMAR',	'AV. INFANTE D. HENRIQUE 16 LJ 2 ',	'CASCAIS',	'2750-167'),
(112,	'X1061',	'5 A SEC CASCAIS VILLA',	'C.C CASCAIS VILLA, LJ 1.43AVENIDA D.PEDRO I',	'CASCAIS',	'2750-786'),
(113,	'X3771',	'PAP FOSSATI E MATIAS',	'LARGO OSTENDE, LJ 14A ',	'MONTE ESTORIL',	'2765-431'),
(114,	'X5541',	'KOISAS GIRAS BAZAR',	'PRACA CARREIRA LT 33 ASAO JOAO',	'SAO JOAO ESTORIL',	'2765-472'),
(115,	'X1251',	'PAP B.COSTA SILVA',	'PRAC DIONISIO MATIAS,5APACO DE ARCOS',	'PACO DE ARCOS',	'2770-051'),
(116,	'X3961',	'LAV ALTO DA LOBA',	'R. JOSE PEDRO DA SILVABL 1 LJ 11 A',	'PACO DE ARCOS',	'2770-107'),
(117,	'X1181',	'PAPELARIA MILENIO DA SORTE',	'PRACA 5 DE OUTUBRO, 4LJ D',	'PAREDE',	'2775-184'),
(118,	'X1161',	'5 A SEC DA PAREDE',	'RUA JAIME CORTESAO, 29A ',	'PAREDE',	'2775-207'),
(119,	'X4761',	'PAPELARIA SASSOEIROS',	'ESTRADA DE SASSOEIROS 4BSASSOEIROS',	'CARCAVELOS',	'2775-530'),
(120,	'X1211',	'LAVANDARIA LAVATU',	'AV. MARIA DA CONCEICAO, 86LJ',	'CARCAVELOS',	'2775-605'),
(121,	'X4841',	'LAVANDARIA BUBBLES',	'AV D JOAO I 10 B ',	'OEIRAS',	'2780-065'),
(122,	'X1121',	'5 A SEC S. D. RANA',	'C.C. INTERMARCHEQUINTA TORRE DA AGUILHA',	'SAO DOMINGOS DE RANA',	'2785-599'),
(123,	'X5981',	'PAPELARIA 2G',	'LARGO DOS DUARTES, 74ATIRES',	'SAO DOMINGOS DE RANA',	'2785-619'),
(124,	'X3951',	'FOTOSPORT ALEGRO',	'C.C. ALEGRO, LJ 42E.N 117 OUTURELA',	'CARNAXIDE',	'2790-045'),
(125,	'X5371',	'CASA SIMOES',	'LARGO DA TERRA GRANDE 11 ',	'CARNAXIDE',	'2790-157'),
(126,	'X1291',	'PAPELARIA DIVERTILANDIA',	'AV. D PEDRO V,8A ',	'LINDA A VELHA',	'2795-150'),
(127,	'X1341',	'5 A SEC ALMADA',	'LARGO GABRIEL PEDRO, 7 ',	'ALMADA',	'2800-094'),
(128,	'X1481',	'PAPELARIA ATLAS',	'RUA D JOAO DE CASTRO, 82BPRAGAL',	'ALMADA',	'2800-105'),
(129,	'X1491',	'OFFICESET',	'AV. RAINHA D. LEONOR, 23B ',	'COVA DA PIEDADE',	'2805-012'),
(130,	'X1471',	'PAPELARIA PARATI',	'R. DR. ANTONIO ELVASMERCADO MUNICIPAL  FEIJO LJ 3',	'FEIJO',	'2810-165'),
(131,	'X5611',	'PAPELARIA MAMICA',	'RUA EUCALIPTOS 38 ALARANJEIRO',	'LARANJEIRO',	'2810-207'),
(132,	'X4751',	'PAP UNIVERSO',	'R PRESIDENTE ARRIAGA 5 ',	'CHARNECA DA CAPARICA',	'2820-401'),
(133,	'X3661',	'PAP GRAO D AREIA',	'PCTA JOAQ M COSTA 6 CSANTO ANTONIO',	'COSTA CAPARICA',	'2825-472'),
(134,	'X1431',	'PAPELARIA SANTA MARIA',	'RUA 5 DE OUTUBRO, 6AALTO SEIXALINHO',	'BARREIRO',	'2830-036'),
(135,	'X4681',	'LAVANDARIA IDEAL',	'RUA D AFONSO ALBUQUERQUE 12 ASANTO ANDRE',	'BARREIRO',	'2830-176'),
(136,	'X1541',	'PAPELARIA JOAO',	'ESTR. NACIONAL 11-1,62 ',	'BAIXA DA BANHEIRA',	'2835-172'),
(137,	'X3721',	'PAP. AGUARELA',	'RUA CAND. MANUEL PEREIRA, LJ 3MERCADO DO LAVRADIO',	'LAVRADIO',	'2835-440'),
(138,	'X3821',	'PALETES DE SONHOS',	'AV. 25 ABRIL, 51 BTORRE DA MARINHA',	'ARRENTELA',	'2840-400'),
(139,	'X1521',	'PAP S VICENTE',	'AV G HUMB DELGADO,20R/C',	'ALDEIA DE PAIO PIRES',	'2840-607'),
(140,	'X1531',	'PAPELARIA EDISA',	'AV. MARCOS PORTUGAL, 1 ',	'AMORA',	'2845-545'),
(141,	'X1501',	'TABACARIA MIZE',	'MERCADO MUNICIPAL MIRATEJO,LJ 99MIRATEJO',	'CORROIOS',	'2855-001'),
(142,	'X1351',	'5 A SEC CORROIOS',	'RUA CASA DO POVO, 10C ',	'CORROIOS',	'2855-111'),
(143,	'X1441',	'PAPELARIA LISABEL',	'RUA 25 DE ABRIL, 5VALE MILHACOS',	'CORROIOS',	'2855-400'),
(144,	'X3911',	'PAPELARIA LUSO',	'RUA JOAQUIM ALMEIDA, 107 ',	'MONTIJO',	'2870-339'),
(145,	'X1411',	'5 A SEC FORUM MONTIJO',	'C.C. FORUM MONTIJO LJ 6 ',	'MONTIJO',	'2870-480'),
(146,	'X5601',	'PAP VIKTORIAS',	'LG ANTONIO DOS SANTOS JORGE 7/8 ',	'ALCOCHETE',	'2890-046'),
(147,	'X1601',	'COPIARRABIDA',	'AV DR RODRIGUES MANITO, 55A LJ18 ',	'SETUBAL',	'2900-066'),
(148,	'X5351',	'OFFICEPAK SETUBAL',	'AV MARIANO CARVALHO 19 B LJ C ',	'SETUBAL',	'2900-487'),
(149,	'X1571',	'5 A SEC SETUBAL',	'AV. BENTO GONCALVES, 21B ',	'SETUBAL',	'2910-433'),
(150,	'X1591',	'PAPELARIA JOMIL',	'AV. DA LIBERDADELT 8, LJ 13',	'PALMELA',	'2950-201'),
(151,	'X1561',	'5 A SEC PINHAL NOVO',	'AV LIBERDADE N 15 ',	'PINHAL NOVO',	'2955-114'),
(152,	'X5731',	'PAPELARIA ARTE E MAR',	'AV 25 DE ABRIL 9 LJC ',	'SESIMBRA',	'2970-634'),
(153,	'X5831',	'LAVANDARIA FADA MAGICA',	'R DAMIAO GOIS LT 1690 LJA ',	'QTA DO CONDE',	'2975-264'),
(154,	'X1701',	'PAPELARIA ALMEIDA',	'EDIFICIO CRUZEIRO LOJA 2LARGO CRUZ CELAS N 4',	'COIMBRA',	'3000-132'),
(155,	'X1611',	'TABACARIA ARQUIVO',	'R. DR MANUEL RODRIGUES,9R/C',	'COIMBRA',	'3000-258'),
(156,	'X1671',	'TABACARIA AMARELINHA',	'R DAS CHAVES, LOTE 5 LOJA 3EIRAS',	'COIMBRA',	'3020-169'),
(157,	'X4451',	'TABACARIA S JOSE',	'RUA DO BRASIL N400 ',	'COIMBRA',	'3030-775'),
(158,	'X1651',	'5 A SEC MEALHADA',	'C.C INTERMARCHECAVADAS',	'MEALHADA',	'3050-343'),
(159,	'X1641',	'LIVROS A ALTURA',	'LUGAR DO FREIXIALCENTRO COMERCIAL INTERMARCHE',	'CANTANHEDE',	'3060-228'),
(160,	'X1691',	'TABACARIA AC',	'RUA FERNANDES TOMAS, 30SAO JULIAO',	'FIGUEIRA DA FOZ',	'3080-151'),
(161,	'X4221',	'PAP. 2 MIL',	'PCTA DR JOAQ. LOPES FETEIRA 6 ',	'FIGUEIRA DA FOZ',	'3080-204'),
(162,	'X4881',	'PAP 2 MIL',	'RUA DAS TAMARGUEIRAS 17 ',	'BUARCOS',	'3080-279'),
(163,	'X1721',	'CLEAN UP LAVANDARIA',	'C.C POMBAL SHOPPING,LJ 1.10RUA SANTA LUZIA',	'POMBAL',	'3100-483'),
(164,	'X1731',	'PERFUMARIA SOLDOURADO',	'AV DOMINGUES E TAVARES,5 ',	'LOURICAL',	'3105-165'),
(165,	'X1741',	'PAPELARIA MILENIO',	'AVENIDA CONSELHEIRO MATOSO,5 B ',	'SOURE',	'3130-203'),
(166,	'X5121',	'PAP DAS LAGES',	'R FERNAO M PINTO LJ 15 ',	'MONT-O-VELHO',	'3140-276'),
(167,	'X1711',	'AQUACARE LAV',	'R. D.M ELSA F SOTTOMAYOR,9 ',	'CONDEIXA A NOVA',	'3150-133'),
(168,	'X1751',	'LIVRARIA MAGRO',	'AVENIDA COELHO DA GAMA, 22 ',	'LOUSA',	'3200-200'),
(169,	'X5951',	'JAMARKET',	'AV 25 ABRIL, 18 LJ E ',	'ANSIAO',	'3240-154'),
(170,	'X1761',	'PAPELARIA CAMPANARIO',	'RUA OLIVEIRA MATOS N1 ',	'ARGANIL',	'3300-062'),
(171,	'X5071',	'PAPELARIA A TEIA',	'AV. 5 DE OUTRUBRO 1 ',	'PENACOVA',	'3360-317'),
(172,	'X1791',	'MEIO MUNDO',	'R ANT R G. VASCONCELOS 22ALOTE 41',	'OLIVEIRA DO HOSP.',	'3400-132'),
(173,	'X1771',	'5 A SEC CARREGAL',	'R JOSE AUGUST CAPELO RCLOJA E',	'CARREGAL DO SAL',	'3430-056'),
(174,	'X5081',	'O KIOSKE DA RIBEIRA',	'R DR TAVARES FESTAS 6 ',	'STA COMBAO DAO',	'3440-374'),
(175,	'X4471',	'5 A SEC TONDELA',	'R BRANCA GONTA COLACO LT4 LJ 1 ',	'TONDELA',	'3460-613'),
(176,	'X1821',	'5 A SEC AV.GULBENKIAN',	'AV CALOUSTE GULBENKIAN,32N ',	'VISEU',	'3500-001'),
(177,	'X1801',	'PAPELARIA AQUARELA',	'QUINTA DO GALOLOTE 2, R/C DTO',	'VISEU',	'3500-001'),
(178,	'X1831',	'5 A SEC CONTINENTE',	'C.C.CONTINENTE, LOJA 11/12AVENIDA DA BELGICA',	'VISEU',	'3500-150'),
(179,	'X4311',	'LOJA POSTAL',	'AV. ANT JOSE ALMEIDA 348 ',	'VISEU',	'3510-044'),
(180,	'X5151',	'TAB. TENTE A SORTE',	'RUA LUIS DE CAMOES 13 ',	'NELAS',	'3520-062'),
(181,	'X1811',	'5 A SEC DE MANGUALDE',	'LARGO DO ROSSIO, 22 ',	'MANGUALDE',	'3530-133'),
(182,	'X1841',	'PAPELARIA ANTOBEL',	'R DR FRANCISCO SA CARNEIRO 2B ',	'SATAO',	'3560-152'),
(183,	'X4511',	'PAP AGUARELA',	'AV DR FRAN SA CARNEIRO 48 ',	'CASTRO DAIRE',	'3600-180'),
(184,	'X5131',	'MOIMENTIRQ',	'AV 25 DE ABRIL 106 R/C ',	'MOIMENTA DA BEIRA',	'3620-304'),
(185,	'X1851',	'PAPELARIA O PAPIRO',	'RUA DE ANCIAES,2 ',	'SAO PEDRO DO SUL',	'3660-481'),
(186,	'X4601',	'PAP. ALBUQUERQUE',	'AV DESCOBRIMENTOS 8 ',	'OLIVEIRA DE FRADES',	'3680-110'),
(187,	'X1881',	'CASA FIFI',	'PC LUIS RIBEIRO, 21C.C. PARQUE AMERICA , LOJA 308',	'SAO JOAO DA MADEIRA',	'3700-240'),
(188,	'X5551',	'KIOSK TABACARIA',	'C.C. INTERMARCHERUA ALIANCA FUTEBOL CLUBE',	'ARRIFANA',	'3700-414'),
(189,	'X1901',	'5 A SEC O AZEMEIS',	'C.C. MODELO LJ 2/3LUGAR DO CERRO',	'OLIVEIRA DE AZEMEIS',	'3720-001'),
(190,	'X4351',	'INFORECO',	'R 16 DE MAIO N 106 FRAC B ',	'O.DE AZEMEIS',	'3720-246'),
(191,	'X1931',	'LIVRARIA MAGISTERIO',	'R. DUARTE PACHECO,65 ',	'VALE DE CAMBRA',	'3730-001'),
(192,	'X4641',	'PAP. MARLINDA',	'AV. AUG. MARTINS PEREIRA 55 ',	'SEVER DO VOUGA',	'3740-254'),
(193,	'X1891',	'5 A SEC DE AGUEDA',	'C.C. FEIRA NOVA, LOJA 5 ',	'AGUEDA',	'3750-001'),
(194,	'X4811',	'PAPELARIA AM',	'RUA ENG. J. BASTOS XAVIER, 6LJ 3',	'AGUEDA',	'3750-144'),
(195,	'X1921',	'LIVR SATURNO',	'RUA DR FRANCA MARTINS,7 ',	'OLIVEIRA DO BAIRRO',	'3770-222'),
(196,	'X1861',	'PAP LITA LIVRARIA',	'R DR ALEXANDRE SEABRA 25RC ESQ ',	'ANADIA',	'3780-230'),
(197,	'X1981',	'PAPELARIA RODRIGUES',	'RUA ENG VON HAFF,41 B ',	'AVEIRO',	'3800-117'),
(198,	'X4741',	'LOJA MPM',	'TRV DO MILAO N9QTA DO SIMAO ESGUEIRA',	'AVEIRO',	'3800-314'),
(199,	'X5921',	'A TABACARIA',	'C.C. GLICINIAS PLAZA, LJ 13 DRUA MAN BARBUDA VASCONCELOS',	'AVEIRO',	'3810-498'),
(200,	'X1941',	'LIVRARIA SANTOS-ILHAVO',	'RUA DE SANTO ANTONIO, N46 ',	'ILHAVO',	'3830-153'),
(201,	'X1991',	'COPIPAPEL',	'AV JOSE ESTEVAO 61 A ',	'GAFANHA DA NAZARE',	'3830-556'),
(202,	'X2021',	'PAPELARIA AMS',	'R. AMERICO M PEREIRA 7 ',	'ALBERGARIA A VELHA',	'3850-049'),
(203,	'X2001',	'LIVR PAPELARIA MODERNA',	'R BOMB. VOLUNTARIOS,59 ',	'ESTARREJA',	'3860-367'),
(204,	'X1961',	'CASA REIS',	'LARGO DA FAMILIA SOARES PINTO,16 ',	'OVAR',	'3880-128'),
(205,	'X2011',	'LIVRARIA E PAPELARIA PAPIRO',	'AVENIDA PRAIAEFIFICIO ROSSIO, LOJA 74',	'ESMORIZ',	'3885-403'),
(206,	'X2051',	'VAPOR EXPRESS',	'RUA DA ALEGRIA,29 B ',	'PORTO',	'4000-041'),
(207,	'X2071',	'LIVR. PAPELARIA LU',	'PC MOUZINHO DE ALBUQUERQUE 54 ',	'PORTO',	'4050-414'),
(208,	'X2121',	'NUPATEX,PAPELARIA,TABACARIA',	'RUA SERPA PINTO,255 ',	'PORTO',	'4050-586'),
(209,	'X2091',	'NORSEC/TOP COSTURA',	'RUA PADRE HIMALAYA, 110 B ',	'PORTO',	'4100-553'),
(210,	'X2081',	'PAPELARIA TINTA AZUL',	'TRAVESSA DAS CONDOMINHAS,94 ',	'PORTO',	'4150-225'),
(211,	'X5111',	'AQUAFOZ LAVANDARIA',	'RUA MARECHAL SALDANHA, 294 ',	'PORTO',	'4150-651'),
(212,	'X5451',	'PAP ATLANTICA EXPRESSO',	'RUA COSTA CABRAL, 1748 ',	'PORTO',	'4200-216'),
(213,	'X4631',	'PAP. NOTICIAS MARAVILHOSAS',	'R. JOAQ. PIRES LIMA 147 ',	'PORTO',	'4200-350'),
(214,	'X5231',	'PAPELARIA CASTRO',	'RUA NOVA DA CORUJEIRA, 62 ',	'PORTO',	'4300-360'),
(215,	'X4571',	'PAPELARIA CHOK',	'AV FERNAO MAGALHAES N 891 ',	'PORTO',	'4350-166'),
(216,	'X4361',	'PAP. NETO E ANAS',	'RUA DA BELGICA 2387 CANIDELO ',	'V. N. GAIA',	'4400-053'),
(217,	'X3591',	'PAP. PAPELANDO',	'R. DR. FRANC. SA CARNEIRO 1436MAFAMUDE',	'V. N. GAIA',	'4400-129'),
(218,	'X2311',	'5 A SEC DAS DEVESAS',	'R JOSE MARIANI,101 ',	'VILA NOVA DE GAIA',	'4400-196'),
(219,	'X2291',	'5 A SEC ARRABIDA',	'LUG DO CANDAL AFURADA ',	'VILA NOVA DE GAIA',	'4400-346'),
(220,	'X2151',	'PAPELARIA POPARTE',	'RUA PROF. AMADEU SANTOS,15 ',	'VALADARES',	'4405-594'),
(221,	'X2321',	'LIMPEZA RAPIDO',	'R BOAVISTA,BUSINESS PARKC.C. PINGO DOCE, LOJA 4',	'GRIJO',	'4415-001'),
(222,	'X2371',	'PAPELARIA DOS CARVALHOS',	'RUA DO PADRAO,50,RC LJ1CARVALHOS',	'PEDROSO',	'4415-284'),
(223,	'X5861',	'ESTORES GONDOMAR',	'RUA BENTO JESUS CARACA 266SAO COSME',	'GONDOMAR',	'4420-044'),
(224,	'X4081',	'GIFTS E HOME',	'RUA DR. JOAQ. MANU. DA COSTA, 6VALBOM',	'VALBOM',	'4420-437'),
(225,	'X2331',	'5 A SEC DO MAIA SHOPPING',	'LUGAR DE ARDEGAESC.C. MAIA SHOPPING, LOJA 117',	'MAIA',	'4425-500'),
(226,	'X3481',	'5 A SEC O. DOURO',	'R. CAETANO DE MELO,375R/C',	'OLIVEIRA DO DOURO',	'4430-269'),
(227,	'X5721',	'PAP POSSIVELMATIK',	'R D GLORIA CASTRO 240 ',	'VILAR ANDORINHO',	'4430-627'),
(228,	'X2401',	'TABACARIA PAPEL CENTRAL',	'R MONTE DA GIESTA,38 ',	'RIO TINTO',	'4435-049'),
(229,	'X2181',	'FLORISTA O MEU BOUQUET',	'R. DAS PERLINHAS,397AO LADO DA ANTIGA ESTACAO CTT',	'RIO TINTO',	'4435-393'),
(230,	'X4021',	'CASA BRANCO PAPELARIA',	'C.C. VALLIS LONGUS LOJA MAV. 5 OUTUBRO',	'VALONGO',	'4440-503'),
(231,	'X3521',	'CHRONOPOST PORTO PICKME',	'R NS SRA AMPARO,2501 ',	'ALFENA',	'4445-036'),
(232,	'X2281',	'PAPELARIA MONTEIRO',	'R RODRIGUES DE FREITAS,1428 ',	'ERMESINDE',	'4445-636'),
(233,	'X2251',	'AQUAFOZ MATOSINHOS',	'AV DA REPUBLICA, 530 ',	'MATOSINHOS',	'4450-001'),
(234,	'X2231',	'ATELIER D COSTURA AVENIDA',	'AV SERPA PINTO,565C.C. NEW CITY, LOJA 09',	'MATOSINHOS',	'4450-001'),
(235,	'X5141',	'QUIOSQUE DO MAR',	'C.C. MAR SHOPP,LJ 1005AV. OSCAR LOPES',	'LECA PALMEIRA',	'4450-337'),
(236,	'X3531',	'PAPNET',	'RUA DO SOL POENTE, 704/724 ',	'LECA DA PALMEIRA',	'4450-794'),
(237,	'X2171',	'FLOR BOTAO ROSA',	'AV ANT DOMINGUES SANTOS,71SENHORA DA HORA',	'MATOSINHOS',	'4460-237'),
(238,	'X5691',	'HEDERA FLORISTA',	'RUA DE S. GENS 3974PADRAO DA LEGUA',	'CUSTOIAS',	'4460-815'),
(239,	'X5701',	'CLIPS PAP E PRESENTES',	'RUA DA CUSTIO 1746 ',	'LECA DO BALIO',	'4465-605'),
(240,	'X2271',	'ARTSEC LAVANDARIA',	'RUA LUCIANO SILVA BARROS, LJ 5GUEIFAES',	'MAIA',	'4470-001'),
(241,	'X2381',	'PAPELARIA MAIASPACE2',	'RUA SIMAO BOLIVAR, 83C.C. VENEPOR, LJ 24',	'MAIA',	'4470-008'),
(242,	'X2391',	'LAVANDARIA TEMA',	'AVENIDA D. MANUEL II, 1177VERMOIN',	'VERMOIM MAI',	'4470-337'),
(243,	'X4011',	'PAP A LUISA',	'PRACA LUIS DE CAMOES, 107 ',	'VILA DO CONDE',	'4480-719'),
(244,	'X2301',	'LAV A SEC',	'RUA MANUEL SILVA, N7 R/C ',	'POVOA DE VARZIM',	'4490-657'),
(245,	'X2431',	'5 A SEC ESPINHO',	'RUA 18, 627 ',	'ESPINHO',	'4500-255'),
(246,	'X2451',	'5 A SEC FEIRA',	'C.C. E LECLERC, LOJA 9 ',	'SANTA MARIA DA FEIRA',	'4520-162'),
(247,	'X2471',	'AM INFORMATICA LDA',	'AVENIDA DE LOUROSA 566EDIFICIO MONTE',	'LOUROSA',	'4535-031'),
(248,	'X4381',	'LAVANDARIA SABAO ROSA',	'RUA DO CEDRO N 291 ',	'MOZELOS',	'4535-198'),
(249,	'X5671',	'TAB O CACHIMBO',	'R ALFERES DIOGO MALAFAIALOJA 3A',	'AROUCA',	'4540-117'),
(250,	'X5221',	'GOMAS E COMPANHIA',	'RUA DAS ESCOLASEDIF SOPE S. PEDRO BL B RC DTO',	'CASTELO DE PAIVA',	'4550-274'),
(251,	'X2491',	'CANTINHO DA ESTACAO',	'R ESTACAO NOVA-ESTACAO CPNOVELAS',	'PENAFIEL',	'4560-315'),
(252,	'X2411',	'PAPELARIA AGENCIA BECA',	'PRACA MUNICIPAL, 12 ',	'PENAFIEL',	'4560-481'),
(253,	'X3971',	'COUTYFIL PAPELARIA',	'RUA ABILIO MIRANDA, 220EDIFICIO ESTADIO LJ R',	'PENAFIEL',	'4560-501'),
(254,	'X2481',	'LAV. PENG PENG',	'AV FRANC SA CARNEIROBLOCO 2 LOJA AS',	'ALPENDURADA E MATOS',	'4575-032'),
(255,	'X2421',	'ATELIER LIZA FLORES',	'RUA 1 DEZEMBRO, 51EDIFICIO CASTELOES, LOJA H',	'PAREDES',	'4580-021'),
(256,	'X5401',	'PAP CARLA E SERGIO',	'AV. BOMB VOLUNTARIOS 647 ',	'REBORDOSA',	'4585-359'),
(257,	'X3731',	'BOX 23',	'RUA D. JOSE LENCASTRE, 23 ',	'PACOS DE FERREIRA',	'4590-506'),
(258,	'X4331',	'PAPELARIA ANA',	'LARGO SERTORIO DE CARVALHO 185EDIF. MIRANTE',	'AMARANTE',	'4600-037'),
(259,	'X2531',	'5 A SEC DE AMARANTE',	'C.C. MODELO, LOJA 7SAO LAZARO',	'AMARANTE',	'4600-279'),
(260,	'X2511',	'5 A SEC MODELO',	'RUA PROF. JOAQUIM BARROS LEITECC MODELO, LJ 11',	'FELGUEIRAS',	'4610-001'),
(261,	'X5281',	'MERCADO XANA',	'PRACA MARECHAL CARMONAR/C',	'LIXA',	'4615-583'),
(262,	'X4771',	'PAPELARIA NOVIDADES',	'RUA SANTO ANDRE, 257 ',	'LOUSADA',	'4620-122'),
(263,	'X2521',	'5 A SEC MARCO',	'C.C PC DA CIDADE LJ 27AV. DR. FRANC. SA CARNEIRO',	'MARCO DE CANAVESES',	'4630-001'),
(264,	'X2551',	'PAPELARIA TABACARIA SANDRA',	'RUA CAMOES, 400 ',	'BAIAO',	'4640-147'),
(265,	'X4591',	'PAPELARIA BORGES',	'TRAVESSA DOS BOMBEIROS 23 LJ W ',	'RESENDE',	'4660-216'),
(266,	'X4581',	'PAPELARIA JOVEM',	'RUA MAJOR MONTEIRO LEITE 53 ',	'CINFAES',	'4690-042'),
(267,	'X2611',	'5 A SEC BRAGA PARQUE',	'GALERIA FEIRA NOVA LJ 3/4 ',	'BRAGA',	'4700-111'),
(268,	'X2561',	'DIOL',	'C.C. AVENIDA LOJA 28 ',	'BRAGA',	'4710-228'),
(269,	'X2621',	'5 A SEC CONTINENTE BRAGA',	'C.C. CONTINENTE LOJA 40FRAIAO',	'BRAGA',	'4715-249'),
(270,	'X5961',	'LIVRARIA PAPELARIA PALOMA',	'RUA 25 ABRIL, 25 ',	'VILA VERDE',	'4730-735'),
(271,	'X4891',	'LAVANDARIA GAIVOTA',	'AV ENG LOSA FARIA 165 LJ 13 ',	'ESPOSENDE',	'4740-268'),
(272,	'X4611',	'PAPELARIA ETC',	'R. IRMAOS S. JOAO DE DEUS, 147LJ 6, URB. CALCADAS',	'ARCOZELO',	'4750-169'),
(273,	'X2631',	'5 A SEC DE BARCELOS',	'C.C. LECLERC, LOJA 09LUGAR DE VESSADAS',	'BARCELINHOS',	'4755-071'),
(274,	'X4861',	'INFORECO',	'RUA CONS SANTOS VIEGAS 58 LJ 1 ',	'FAMALICAO',	'4760-129'),
(275,	'X2591',	'LAV EL DOURADO',	'R VASCONCELOS E CASTRO,39 ',	'FAMALICAO',	'4760-169'),
(276,	'X5771',	'PAP RISCOS RABISCOS',	'AV NARCISO FERREIRA 136 ',	'RIBA DE AVE',	'4765-202'),
(277,	'X2651',	'QUIOSQUE UNIVERSAL',	'PRAC CAMILO CAST BRANCO,21 ',	'STO TIRSO',	'4780-374'),
(278,	'X3801',	'SKYPHONE',	'RUA INFANTE D. HENRIQUE, 628 ',	'TROFA',	'4785-326'),
(279,	'X5311',	'AZEVEDO E FILHOS',	'R. ANTO MARTINS RIBEIRO,21 ',	'VL DAS AVES',	'4795-035'),
(280,	'X4341',	'TABACARIA BLACK',	'RUA TEIXEIRA PASCOAIS 473 R/C ',	'GUIMARAES',	'4800-073'),
(281,	'X2721',	'PAPEL LIVR. MILENIO',	'R CALOUSTE GULBENKIAN,323OLIVEIRA DO CASTELO',	'GUIMARAES',	'4810-257'),
(282,	'X5711',	'QIOSQUE DAS CHAVES',	'PRACA DA REPUBLICA 128 ',	'VIZELA',	'4815-475'),
(283,	'X5901',	'PAPELARIA PAPYRUS',	'AV FORCAS ARMADAS, 31EDIF CENTRO LJ R',	'FAFE',	'4820-119'),
(284,	'X2691',	'LIPOVOA LIV',	'PC E ARMANDO RODRIGUES 142R/C',	'POVOA DE LANHOSO',	'4830-520'),
(285,	'X2711',	'5 A SEC DE GUIMARAES',	'AL DR MARIANO FELGUEIRASLOJA 155, CREIXOMIL',	'GUIMARAES',	'4835-075'),
(286,	'X2681',	'LAVANDARIA MONICA',	'PRACA DA REPUBLICA ',	'CABECEIRAS DE BASTO',	'4860-001'),
(287,	'X5291',	'PAPELARIA VELOSO',	'EDIF MILENIO BL B LJ 5TORRAO',	'MONDIM DE BASTO',	'4880-206'),
(288,	'X5651',	'A PRENDINHA BAZAR',	'R SERPA PINTOEDIF SANTIAGO BRITELO',	'CELORICO DE BASTO',	'4890-236'),
(289,	'X2751',	'KOPIAS XL',	'C. C. ACTIVE CENTER LJ Y ',	'VIANA DO CASTELO',	'4900-415'),
(290,	'X2731',	'TOP COSTURA',	'CC ESTACAO VIANA SHOPPINGPISO 0, LOJA 04',	'VIANA DO CASTELO',	'4900-496'),
(291,	'X2771',	'LIVR. PAPELARIA ROCHA',	'R CONSELH. JOAO CUNHA,4/6 ',	'MONCAO',	'4950-469'),
(292,	'X4871',	'NORBERTO SEGUROS',	'R. JOSE SEBAST DIAS LJ27 ',	'ARCOS VALDEVEZ',	'4970-744'),
(293,	'X2741',	'PAPELARIA PAPELIMA',	'URB ESCOLA SECUNDARIALOJA D',	'PONTE DE LIMA',	'4990-106'),
(294,	'X4461',	'NIKO ARTE',	'RUA VISC CARNAXIDE BL A, LJ 8MANTAS',	'VILA REAL',	'5000-556'),
(295,	'X4781',	'PAPELARIA MARLEI',	'AV. D. DINIS, 8 ',	'VILA REAL',	'5000-600'),
(296,	'X2781',	'CASA BORRAJO',	'RUA DOS CAMILOS, 4 ',	'PESO DA REGUA',	'5050-272'),
(297,	'X5411',	'SMILEVIRTUAL',	'LARGO DESTERRO,AV E MONIZBL 1 RC DTO',	'LAMEGO',	'5100-193'),
(298,	'X2831',	'5 A SEC BRAGANCA',	'HIPER FEIRA NOVA - LOJA 06 ',	'BRAGANCA',	'5300-107'),
(299,	'X2821',	'VITOR ESCUDEIRO',	'R GIL VICENTE,15 C ',	'MACEDO DE CAVALEIROS',	'5340-271'),
(300,	'X2841',	'5 A SEC DE MIRANDELA',	'C. COMERCIAL FEIRA NOVALOJA 02',	'MIRANDELA',	'5370-203'),
(301,	'X4321',	'INFORECO',	'R IRMAOS RUI GARCIA LOPESEDF.S.NEUTEL BLA LJ 33',	'CHAVES',	'5400-310'),
(302,	'X2851',	'5 A SEC DE CHAVES',	'AVENIDA D.JOAO IC.C. LECLERC, LOJA 2, MADALENA',	'CHAVES',	'5400-323'),
(303,	'X5621',	'PAPELARIA ROSSIO',	'AV ENG LUIS C SARAIVA, 3 ',	'VALPACOS',	'5430-491'),
(304,	'X2861',	'LOJA STORM',	'AV PEDRO A CABRAL LJ 36,37,38 ',	'CASTELO BRANCO',	'6000-085'),
(305,	'X5091',	'QUIOSQUE ABC',	'R. SRA MERCULES, BL 94 LJ 1 ',	'CASTELO BRANCO',	'6000-280'),
(306,	'X2881',	'CASA PAULINO',	'AV.GONCALO RODRIGUES CALDEIRA,46 A',	'SERTA',	'6100-732'),
(307,	'X2901',	'POSTWEB-RUA DIREITA',	'R COMENDADOR CAMPOS MELO,27 ',	'COVILHA',	'6200-066'),
(308,	'X2891',	'5 A FIL COVILHA',	'C.C. SERRA SHOPPING,LJ 43/44AV EUROPA LT 7',	'COVILHA',	'6200-546'),
(309,	'X4481',	'LIVRARIA MARINA',	'LARGO CHAFARIZ DAS 8 BICAS ',	'FUNDAO',	'6230-398'),
(310,	'X2911',	'PAPELARIA HENRIQUES',	'C.C.INTERMARCHE-VALE CANASS.MARCOS',	'FUNDAO',	'6230-481'),
(311,	'X2921',	'5 A SEC DE SEIA',	'C.C. INTERM., LOJA 03LUGAR DO CRESTELO, S. ROMAO',	'SEIA',	'6270-479'),
(312,	'X4421',	'PAPELARIA PEROLA',	'RUA D. JOAO SARAIVA, 3 ',	'SEIA',	'6270-522'),
(313,	'X2941',	'PAPELARIA ISA',	'RUA DO OUTEIRO, 18LOJA 01',	'GOUVEIA',	'6290-324'),
(314,	'X2961',	'5 A SEC INTERMARCHE',	'C.C. INTERMARCHE,LJ 13BO NS REMEDIOS',	'GUARDA',	'6300-309'),
(315,	'X2951',	'5 A SEC GUARDA',	'C.C. GARDEN LJ 1/2/3 ',	'GUARDA',	'6300-752'),
(316,	'X2971',	'INFORCINCO PAPELARIA',	'RUA FORMOSA,54 UB. S MIGUEL ',	'GUARDA',	'6300-837'),
(317,	'X5171',	'QUIOSQUE AVENIDA',	'RUA DR. MANUEL ARIAGA, 51 ',	'PINHEL',	'6400-378'),
(318,	'X5791',	'CABELEIREIRO SILVIA FERREIRA',	'RUA EDUARDA LAPA ',	'TRANCOSO',	'6420-055'),
(319,	'X3021',	'PAPELARIA TABACARIA ARCADA',	'RUA DA REPUBLICA,59/61 ',	'EVORA',	'7000-656'),
(320,	'X5361',	'PAPELARIA FINO',	'RUA ANTONIO JOSE COUVINHA 14 RCALAMOS',	'EVORA',	'7005-296'),
(321,	'X4541',	'EBORPAPERS',	'RUA CATARINA EUFEMIA 11-13HORTA DAS FIGUEIRAS',	'EVORA',	'7005-320'),
(322,	'X3011',	'ANTONIO MARQUES',	'RUA 1 DE MAIO, 7 ',	'MONTEMOR-O-NOVO',	'7050-139'),
(323,	'X5261',	'PAPELARIA ABC',	'ALAMEDA JOSE M C E FERREIRALT 16 LJ 3',	'VENDAS NOVAS',	'7080-185'),
(324,	'X5271',	'DROG. E DECORACOES',	'R. DR ANT J ALMEIDA, 15 ',	'V DO ALENTEJO',	'7090-269'),
(325,	'X3031',	'5 A SEC ESTREMOZ',	'ESTRADA DA QTA DO CARMOC.C. MODELO LJ 3',	'ESTREMOZ',	'7100-055'),
(326,	'X5181',	'PRESSCENTER',	'RUA MANOEL JOAQUIM DA SILVA, 5B ',	'REDONDO',	'7170-014'),
(327,	'X5851',	'FLORES CUCO',	'CAMPO 25 DE ABRIL 10 ',	'REGUENGOS DE MONSARAZ',	'7200-368'),
(328,	'X5251',	'PAPELARIA CENTRAL',	'RUA DR MANUEL ARRIAGA, 7 ',	'PORTEL',	'7220-419'),
(329,	'X3051',	'PAPELARIA SARITA',	'RUA D NUNO ALV. PEREIRA,25 ',	'PORTALEGRE',	'7300-200'),
(330,	'X3061',	'PAPELARIA NOVA ELVENSE',	'RUA DE ALCAMIM, 26 ',	'ELVAS',	'7350-074'),
(331,	'X3071',	'RETROSARIA A NANA',	'RUA SOEIRO PER. GOMES,2 ',	'PONTE DE SOR',	'7400-279'),
(332,	'X3091',	'5 A SEC DE SINES',	'RUA JOAO SOARES,21 ',	'SINES',	'7520-216'),
(333,	'X4651',	'FLORISTA O MALMEQUER',	'R. GEN. HUMB. DELGADO 26 ',	'SANTIAGO',	'7540-191'),
(334,	'X4261',	'FRESCOS E COMPANHIA',	'R MARQ. DE POMBAL 24 ',	'ALCACER DO SAL',	'7580-166'),
(335,	'X4961',	'JR REPRESENTACOES',	'R. ADANJO E MADEIRA,7 ',	'ALJUSTREL',	'7600-056'),
(336,	'X3121',	'PAPELARIA NELA',	'PRACA JOSE MARIA LOPES FALCAO,24 ',	'ODEMIRA',	'7630-140'),
(337,	'X5211',	'DROGARIA ALMEIDA',	'RUA MOINHO VENTO, 5A ',	'V.N. MILFONTES',	'7645-282'),
(338,	'X5201',	'PAP CANTINHO DAS LETRAS',	'R. ENG. DUARTE PACHECO,1 ',	'OURIQUE',	'7670-286'),
(339,	'X4971',	'PAP. LIVR. COM ESTORIA',	'R. ADRO DOS JUDEUS,2 ',	'ALMODOVAR',	'7700-016'),
(340,	'X5191',	'TIFA BAZAR E PAPELARIA',	'PCTA DAS LOJAS, 1 ',	'MERTOLA',	'7750-312'),
(341,	'X3131',	'5 A SEC CASTRO VERDE',	'RUA DE ALJUSTREL, 26 ',	'CASTRO VERDE',	'7780-170'),
(342,	'X3151',	'BEJAFLOR',	'PRACA ANTONIO RAPOSO TAVARES ',	'BEJA',	'7800-454'),
(343,	'X3161',	'BOTAPAX',	'RUA PORTAS DE MERTOLA,20 ',	'BEJA',	'7800-467'),
(344,	'X5531',	'FOTO LUSITANA',	'RUA DOS CAVALOS, 35-37 ',	'SERPA',	'7830-341'),
(345,	'X5241',	'PAPELARIA ARTICOR II',	'LG CASCATA, 16 ',	'VIDIGUEIRA',	'7960-116'),
(346,	'X4921',	'MENU DOURADO',	'RUA GUILHERME CENTAZZI 5 E 7 ',	'FARO',	'8000'),
(347,	'X4291',	'PAPELARIA THEOREMA',	'RUA DE PORTUGAL, 31 ',	'FARO',	'8000-281'),
(348,	'X3221',	'EURONEWS PAPELARIA DINAMICA',	'RUA JOSE AFONSO, 63R/C DTO',	'LOULE',	'8100-001'),
(349,	'X3211',	'PAPELARIA DINAMICA I',	'LARGO BARTOLOMEU DIASLT D R/C DTO',	'LOULE',	'8100-518'),
(350,	'X3231',	'PAPELARIA PAPELNET',	'AV. FRANCISCO SA CARNEIROEDIFICIO DUNAS VII LJ 15',	'QUARTEIRA',	'8125-124'),
(351,	'X4701',	'CASA RUI',	'RUA ANTONIO ROSA BRITO 36 ',	'SAO BRAS DE ALPORTEL',	'8150-118'),
(352,	'X3241',	'5 A SEC ALBUFEIRA',	'AV. DOS DESCOBRIMENTOSC.C BELLAVISTA LJ9',	'ALBUFEIRA',	'8200-260'),
(353,	'X4991',	'TABACARIA MILENA',	'C.C. TROPICO, LJ 15RUA D. JOAO II',	'ARMACAO',	'8365-130'),
(354,	'X3261',	'PAPELADA E COMPANHIA',	'RUA DO CENTRO DE SAUDE LT 11R/C',	'LAGOA',	'8400-413'),
(355,	'X3251',	'5 A SEC LAGOA',	'C.C. INTERMARCHE LJ 3/4POCO PARTIDO',	'LAGOA',	'8400-557'),
(356,	'X3281',	'5 A SEC DE PORTIMAO',	'AV. DO BRASIL, LT 11 LJ 4QUINTA DO AMPARO',	'PORTIMAO',	'8500-504'),
(357,	'X3291',	'CRIASORTE PAPELARIA',	'LARG HELIODORIO SALGADO,22PORTIMAO',	'PORTIMAO',	'8500-537'),
(358,	'X4071',	'PAPELARIA FRASES E LETRAS',	'URBANIZ. VILA ROSALT 54 LJ 5',	'PORTIMAO',	'8500-782'),
(359,	'X3271',	'PAPELARIA RAMINHA',	'EDIF BELA RAMINHA LT 20 LJ 5HORTA S PEDRO',	'PORTIMAO',	'8500-826'),
(360,	'X5001',	'JOGAKI',	'LARGO DOS CHOROES,6 ',	'MONCHIQUE',	'8550-427'),
(361,	'X5971',	'TABACARIA PAPELARIA AMERICA',	'ESTRADA PONTA PIEDADELT 24 LJ C',	'LAGOS',	'8600-527'),
(362,	'X3331',	'LAVANDARIA OLHAOPASSA',	'RUA PATRAO JOAQUIM CASACALOTE 3 B, R/C DTO',	'OLHAO',	'8700-507'),
(363,	'X5871',	'QUIOSQUE AMERICA',	'RUA AMALIA RODRIGUES 5 ',	'TAVIRA',	'8800-354'),
(364,	'X5801',	'LIV PAP ODIANA',	'RUA ECA QUEIROS, 14 ',	'V.R.S.A.',	'8900-304'),
(365,	'X5781',	'PAPELARIA JULBER',	'GALERIAS S. LOURENCO LJ 12AV. ARRIAGA, 33',	'FUNCHAL',	'9000-060'),
(366,	'X3361',	'DECOARTE',	'C.C. EDEN MAR,LJ 14 ARUA GORGULHO,LIDO',	'FUNCHAL',	'9000-777'),
(367,	'X4801',	'MILITACOR',	'R DR. HUGO MOREIRA, 2, S. PEDRO ',	'PONTA DELGADA',	'9500-792'),
(368,	'X4791',	'PFA PISCINAS (JTO EX APA)',	'BICAS DE CABO VERDE 18ARMAZEM 5 A',	'ANGRA DO HEROISMO',	'9700-217'),
(369,	'X3441',	'LAVANDARIA ROSA',	'RUA COM. FERNANDO COSTA,11 RCANGUSTIAS',	'HORTA',	'9900-011');

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(4) DEFAULT '1',
  `data_registo` datetime DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `password_salt` varchar(255) DEFAULT NULL,
  `data_nasc` date DEFAULT NULL,
  `nome` text,
  `empresa` varchar(255) DEFAULT NULL,
  `branch_name` text,
  `atividade` varchar(255) DEFAULT NULL,
  `atividade2` varchar(255) DEFAULT NULL,
  `morada` text,
  `cod_postal` varchar(255) DEFAULT NULL,
  `localidade` varchar(255) DEFAULT NULL,
  `roll` varchar(50) NOT NULL,
  `pais` int(11) DEFAULT '0',
  `telefone` varchar(150) DEFAULT NULL,
  `telemovel` varchar(50) DEFAULT NULL,
  `nif` varchar(20) DEFAULT NULL,
  `pessoa` varchar(250) DEFAULT NULL,
  `pvp` tinyint(4) DEFAULT '1',
  `desconto` int(11) DEFAULT '0',
  `activation_hash` text,
  `validado` tinyint(4) DEFAULT '0',
  `ativo` tinyint(4) DEFAULT '0',
  `ultima_entrada` datetime DEFAULT NULL,
  `news` int(11) DEFAULT '0',
  `lingua` varchar(20) DEFAULT NULL,
  `referer` int(11) DEFAULT '0',
  `cod_bonus` varchar(50) DEFAULT NULL,
  `cod_bonus_primeiro` varchar(50) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT '0.00',
  `pontos` int(11) DEFAULT '0',
  `novo` tinyint(4) DEFAULT '1',
  `aceita_encomendas` tinyint(4) DEFAULT '1',
  `data_notificacoes` datetime DEFAULT NULL,
  `cod_recupera` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`email`,`validado`,`ativo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `clientes` (`id`, `tipo`, `data_registo`, `email`, `password`, `password_salt`, `data_nasc`, `nome`, `empresa`, `branch_name`, `atividade`, `atividade2`, `morada`, `cod_postal`, `localidade`, `roll`, `pais`, `telefone`, `telemovel`, `nif`, `pessoa`, `pvp`, `desconto`, `activation_hash`, `validado`, `ativo`, `ultima_entrada`, `news`, `lingua`, `referer`, `cod_bonus`, `cod_bonus_primeiro`, `saldo`, `pontos`, `novo`, `aceita_encomendas`, `data_notificacoes`, `cod_recupera`) VALUES
(55,	1,	'2020-10-27 08:09:12',	'prajapativishal999991@gmail.com',	'a403eadb99ab8414821b6968ac8c4e5994e5f7cf70ee84e07691a00a03ecc47b',	'8d1',	NULL,	'vishal',	NULL,	NULL,	NULL,	NULL,	'india',	'769836',	'india',	'franchise',	26,	'456123',	'7698365262',	'',	NULL,	1,	0,	'c6f07ec48ada3ebd349f40ba31786cdfdc861202',	1,	1,	'2021-03-22 14:03:17',	0,	'pt',	0,	'KTGGPKQV',	NULL,	0.00,	0,	0,	1,	NULL,	'xxEGdskeJzf279NT6P2qxhk5Iy8PPt3I'),
(56,	1,	'2020-10-27 08:13:46',	'hitesh@gmail.com',	'f1d2d3796aa37ac51c7b105d9257997ea9ed2f091404a26fbb437bf18d9b157e',	'355',	NULL,	'hitesh',	NULL,	NULL,	NULL,	NULL,	'india',	'635262',	'india',	'customer',	2,	'789456',	'7698365262',	'',	NULL,	1,	0,	'e6718219ebe0c2581c5bd6360a28cf1edce6e279',	1,	1,	'2020-10-27 08:17:32',	0,	'pt',	0,	'26YFAIIM',	NULL,	0.00,	0,	0,	1,	NULL,	'Kr7leEANPZsyugQbFdPnYq2RvuWjM73C'),
(57,	1,	'2020-10-27 10:35:31',	'dhaval@gmail.com',	'86fed2aadb13c74493dd820aa1b46f48aa91a863e5be411b2c31b0ae1a9d9149',	'fc1',	NULL,	'dhaval',	NULL,	NULL,	NULL,	NULL,	'india',	'363310',	'india',	'customer',	2,	'07874876868',	'7636526214',	'',	NULL,	1,	0,	'9c490eb48739d93b6247761e5b34d285a9f94313',	1,	1,	'2021-03-01 09:03:43',	0,	'pt',	0,	'CFVJ5GZW',	NULL,	0.00,	0,	0,	1,	NULL,	'N3N5MJRyUhUP7yCc6FpKzXYGnxiJHq84'),
(58,	1,	'2020-10-27 10:40:39',	'kisan@gmail.com',	'94653373475d31e74406f8f5f21dfb987859c5bdef759cba8d9b97e29ec744ff',	'e50',	NULL,	'kisan ',	NULL,	NULL,	NULL,	NULL,	'india',	'769836526',	'india',	'customer',	74,	'5345346457',	'99741906784',	'',	NULL,	1,	0,	'4e2fd7144f17d16c30eb6373304d9892c17f917d',	1,	1,	'2020-12-21 09:43:35',	0,	'pt',	0,	'NLJBNMOV',	NULL,	0.00,	0,	0,	1,	NULL,	'lheyGYmoflPdgnh74Yr1Sfqkchvsybno'),
(59,	1,	'2021-02-03 10:46:46',	'washwoodheath@bbakery.co.uk',	'92d935b0d8cc7187d6efb4c904a97cb44a0f20d8717ef88ee81306285049cfef',	'b26',	'2021-03-03',	'WashWoodheath',	NULL,	'WashWood Heath',	'WashWood Heath',	'',	'604 Washwood Heath Road',	'B8 2HG',	'Washwood heath',	'franchise',	252,	'01213267500',	'1213267500',	'',	'WashWoodheath',	1,	0,	NULL,	1,	1,	'2021-03-03 10:47:19',	0,	'pt',	0,	'KEGVQYNG',	NULL,	0.00,	0,	1,	1,	NULL,	NULL),
(60,	1,	'2021-03-04 05:30:24',	'lozells_bakery@yahoo.co.uk',	'0bce724c358417b3de003247f448860f324c2fd477990d3fa7a8fea252b2344d',	'426',	NULL,	'Lozells',	NULL,	'Lozells',	'Lozells',	'',	'91 lozells road\r\nlozells',	'b192tr',	'birmingham',	'franchise',	252,	'01215239886',	'01215239886',	'',	'Lozells',	1,	0,	NULL,	1,	1,	'2021-03-04 05:31:54',	0,	'pt',	0,	'E4ZHYV7L',	NULL,	0.00,	0,	1,	1,	NULL,	NULL);

DROP TABLE IF EXISTS `clientes_atividades_en`;
CREATE TABLE `clientes_atividades_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clientes_atividades_pt`;
CREATE TABLE `clientes_atividades_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `clientes_atividades_pt` (`id`, `nome`) VALUES
(1,	'Agência de Publicidade'),
(2,	'Gráfica'),
(3,	'Agência de Comunicação integrada e/ou Digital'),
(4,	'Revendedor / Distribuidor'),
(5,	'Marca / Produto'),
(6,	'Designer');

DROP TABLE IF EXISTS `clientes_blocos_en`;
CREATE TABLE `clientes_blocos_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(4) DEFAULT '0' COMMENT '1 - Imagem; 2 - Imagem & Texto',
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(250) DEFAULT NULL,
  `descricao` text,
  `imagem1` varchar(250) DEFAULT NULL,
  `mostra_botao` tinyint(4) DEFAULT '0',
  `texto_botao` varchar(250) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `target` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clientes_blocos_pt`;
CREATE TABLE `clientes_blocos_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` tinyint(4) DEFAULT '0' COMMENT '1 - Imagem; 2 - Imagem & Texto',
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(250) DEFAULT NULL,
  `descricao` text,
  `imagem1` varchar(250) DEFAULT NULL,
  `mostra_botao` tinyint(4) DEFAULT '0',
  `texto_botao` varchar(250) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  `target` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `clientes_blocos_pt` (`id`, `tipo`, `nome`, `titulo`, `descricao`, `imagem1`, `mostra_botao`, `texto_botao`, `link`, `target`, `ordem`, `visivel`) VALUES
(1,	1,	'Bloco 1',	'Título Simulado - teste',	'-30% - teste',	'040953_1_9160_bloco1.jpg',	1,	'',	'http://netgocio.pt',	'0',	1,	0),
(2,	1,	'Bloco 2 ',	'Outro título teste',	'-50% - teste',	'041016_1_1596_bloco2.jpg',	0,	'',	'',	'0',	2,	0),
(3,	2,	'Bloco 3',	'Título exemplo',	'teste Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas felis arcu, tristique cursus turpis at, accumsan bibendum sem. Fusce sed eros vel nisl condimentum dictum vitae id lorem. Donec mi tortor, suscipit et libero non, viverra porta lacus. Aliquam enim turpis, facilisis id venenatis vitae, consequat id tortor. Suspendisse tincidunt lacus eget justo molestie, nec eleifend magna varius. Donec ac vestibulum turpis. Quisque quis leo et nisi convallis scelerisque ut et felis. Potenti nullam ac tortor vitae purus. Quisque id diam vel quam. Ipsum suspendisse ultrices gravida dictum. Magnis dis parturient montes nascetur ridiculus mus. Aliquet nibh praesent tristique magna sit amet purus gravida. Pellentesque habitant morbi tristique senectus. Ipsum suspendisse ultrices gravida dictum fusce ut placerat orci nulla. Aliquet nec ullamcorper sit amet risus nullam eget felis.',	'041054_1_2117_bloco3.jpg',	1,	'saiba mais',	'http://netgocio.pt',	'_blank',	3,	0),
(4,	2,	'Bloco 4',	'Título',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas felis arcu, tristique cursus turpis at, accumsan bibendum sem. Fusce sed eros vel nisl condimentum dictum vitae id lorem. Donec mi tortor, suscipit et libero non, viverra porta lacus. Aliquam enim turpis, facilisis id venenatis vitae, consequat id tortor.   Suspendisse tincidunt lacus eget justo molestie, nec eleifend magna varius. Donec ac vestibulum turpis. Quisque quis leo et nisi convallis scelerisque ut et felis.',	'041114_1_8457_bloco4.jpg',	0,	'',	'',	'0',	4,	0);

DROP TABLE IF EXISTS `clientes_grupos`;
CREATE TABLE `clientes_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `market` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `clientes_grupos` (`id`, `nome`, `market`) VALUES
(1,	'Grupo 1',	1);

DROP TABLE IF EXISTS `clientes_login_en`;
CREATE TABLE `clientes_login_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `texto` text,
  `titulo2` varchar(255) DEFAULT NULL,
  `texto2` text,
  `imagem1` varchar(255) DEFAULT NULL,
  `texto_password` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clientes_login_pt`;
CREATE TABLE `clientes_login_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `texto` text,
  `titulo2` varchar(255) DEFAULT NULL,
  `texto2` text,
  `imagem1` varchar(255) DEFAULT NULL,
  `texto_password` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `clientes_login_pt` (`id`, `titulo`, `texto`, `titulo2`, `texto2`, `imagem1`, `texto_password`) VALUES
(1,	'Ainda não está registado?',	'O processo de registo &eacute; f&aacute;cil e r&aacute;pido e permite ter v&aacute;rias vantagens',	'Criar uma conta tem várias vantagens!!',	'<ul>\r\n	<li>Em futuras compras n&atilde;o precisa de inserir todos os seus dados novamente</li>\r\n	<li>Tenha&nbsp;acesso a promo&ccedil;&otilde;es e novidades</li>\r\n</ul>\r\n',	'teste.jpg',	'Introduza o seu e-mail para recebre um link de recupera&ccedil;&atilde;o de password.');

DROP TABLE IF EXISTS `clientes_moradas`;
CREATE TABLE `clientes_moradas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `morada` text,
  `localidade` varchar(255) DEFAULT NULL,
  `distrito` varchar(255) DEFAULT NULL,
  `cod_postal` varchar(255) DEFAULT NULL,
  `pais` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clientes_obs`;
CREATE TABLE `clientes_obs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT NULL,
  `descricao` text,
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clientes_remocao`;
CREATE TABLE `clientes_remocao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) DEFAULT '0',
  `email` varchar(255) DEFAULT NULL,
  `descricao` text,
  `data_pedido` datetime DEFAULT NULL,
  `data_remocao` datetime DEFAULT NULL,
  `removido` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `clientes_remocao` (`id`, `id_cliente`, `email`, `descricao`, `data_pedido`, `data_remocao`, `removido`) VALUES
(1,	11,	'webtech.dev@gmail.com',	'teste',	'2020-07-06 10:39:43',	NULL,	NULL);

DROP TABLE IF EXISTS `clientes_textos_en`;
CREATE TABLE `clientes_textos_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assunto` varchar(255) DEFAULT NULL,
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `clientes_textos_pt`;
CREATE TABLE `clientes_textos_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assunto` varchar(255) DEFAULT NULL,
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `clientes_textos_pt` (`id`, `assunto`, `texto`) VALUES
(1,	'O seu registo no site - www.teste.pt',	'Caro <strong>#nome#</strong>,<br />\n<br />\nO seu registo foi validado com sucesso. Poder&aacute; efetuar login <a href=\"http://www.teste.pt/login.php\"><strong>aqui</strong></a> usando os dados indicados abaixo.<br />\nDever&aacute; alterar a sua password assim que poss&iacute;vel.<br />\n<br />\nObrigado.'),
(2,	'O seu registo no site - www.teste.pt',	'Caro(a) <strong>#nome#</strong>,<br /><br />Procedemos ao cancelamento da sua conta, perdeu a possibilidade de efetuar compras, para al&eacute;m de outros benef&iacute;cios, em www.teste.pt.<br />A qualquer momento poder&aacute; fazer um <strong><a href=\"!link!\">novo registo</a></strong> no nosso website.<br /><br />Esperamos v&ecirc;-o em breve.');

DROP TABLE IF EXISTS `codigos_promocionais`;
CREATE TABLE `codigos_promocionais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_codigo` int(11) DEFAULT '1' COMMENT '1 - Particular; 2 - Aniversário; 3 - Compra X dias; 4 - Registo X dias s/ compras; 5 - Encomenda não paga X dias',
  `nome` varchar(250) DEFAULT NULL,
  `codigo` varchar(30) DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT '0.00',
  `tipo_desconto` tinyint(4) DEFAULT '1',
  `valor_minimo` decimal(10,2) DEFAULT '0.00',
  `tipo_cliente` int(11) DEFAULT '0',
  `grupo` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT '0',
  `limite_cliente` int(11) DEFAULT '1',
  `limite_total` int(11) DEFAULT '0',
  `tipo` int(11) DEFAULT '1',
  `id_categoria` int(11) DEFAULT '0',
  `id_peca` int(11) DEFAULT '0',
  `id_country` int(11) DEFAULT NULL,
  `tamanho` bigint(20) DEFAULT '0',
  `id_marca` int(11) DEFAULT '0',
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `data_email` datetime DEFAULT NULL,
  `pagina` int(11) DEFAULT NULL,
  `visivel_listagem` tinyint(4) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo_codigo`,`codigo`,`id_categoria`,`id_peca`,`id_marca`,`datai`,`dataf`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `codigos_promocionais` (`id`, `tipo_codigo`, `nome`, `codigo`, `desconto`, `tipo_desconto`, `valor_minimo`, `tipo_cliente`, `grupo`, `id_cliente`, `limite_cliente`, `limite_total`, `tipo`, `id_categoria`, `id_peca`, `id_country`, `tamanho`, `id_marca`, `datai`, `dataf`, `data_email`, `pagina`, `visivel_listagem`, `visivel`) VALUES
(1,	1,	'rvkumar',	'RVKUMAR',	50.00,	1,	2.00,	0,	0,	0,	20,	20,	1,	0,	0,	197,	0,	0,	'2020-06-01',	NULL,	NULL,	0,	NULL,	1),
(2,	1,	'Teste',	'45GG6',	10.00,	1,	0.00,	0,	0,	0,	1,	0,	1,	0,	0,	NULL,	0,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	0),
(3,	1,	'Vera',	'Yior1voPw',	10.00,	1,	20.00,	0,	0,	64,	1,	0,	1,	0,	0,	0,	0,	0,	'2020-09-14',	'2020-09-30',	NULL,	0,	0,	1),
(4,	1,	'Teste Daniel',	'kTbIFqCe7',	15.00,	1,	0.00,	0,	0,	0,	1,	0,	1,	0,	0,	197,	0,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	1);

DROP TABLE IF EXISTS `codigos_promocionais_emails`;
CREATE TABLE `codigos_promocionais_emails` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_codigo` bigint(20) DEFAULT NULL,
  `id_cliente` bigint(20) DEFAULT '0',
  `email` varchar(250) DEFAULT NULL,
  `data` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `codigos_promocionais_texto_en`;
CREATE TABLE `codigos_promocionais_texto_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_prom` int(11) DEFAULT '0',
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `codigos_promocionais_texto_en` (`id`, `cod_prom`, `texto`) VALUES
(1,	2,	''),
(2,	3,	''),
(3,	4,	''),
(4,	5,	''),
(5,	6,	''),
(6,	7,	'');

DROP TABLE IF EXISTS `codigos_promocionais_texto_pt`;
CREATE TABLE `codigos_promocionais_texto_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_prom` int(11) DEFAULT '0',
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `codigos_promocionais_texto_pt` (`id`, `cod_prom`, `texto`) VALUES
(1,	2,	''),
(2,	3,	''),
(3,	4,	''),
(4,	5,	''),
(5,	6,	''),
(6,	7,	'');

DROP TABLE IF EXISTS `codigos_promocionais_tipos`;
CREATE TABLE `codigos_promocionais_tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(250) DEFAULT NULL,
  `dias` int(11) DEFAULT NULL,
  `validade` int(11) DEFAULT NULL,
  `desconto` int(11) DEFAULT '0',
  `tipo_desconto` tinyint(4) DEFAULT '1',
  `valor_minimo` int(11) DEFAULT '0',
  `desconto2` int(11) DEFAULT '0',
  `tipo_desconto2` int(11) DEFAULT '1',
  `valor_minimo2` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `codigos_promocionais_tipos` (`id`, `nome`, `dias`, `validade`, `desconto`, `tipo_desconto`, `valor_minimo`, `desconto2`, `tipo_desconto2`, `valor_minimo2`) VALUES
(1,	'Manual',	0,	0,	0,	NULL,	0,	0,	1,	0),
(2,	'Aniversários',	NULL,	30,	15,	1,	30,	15,	1,	30),
(3,	'Não efectuou compras há X dias',	10,	15,	10,	1,	30,	5,	1,	50),
(4,	'Fez registo há X dias e não comprou',	15,	15,	5,	2,	50,	3,	2,	30),
(5,	'Encomenda por pagar há X dias',	30,	15,	10,	1,	50,	10,	1,	75);

DROP TABLE IF EXISTS `codigos_promocionais_tipos_textos_en`;
CREATE TABLE `codigos_promocionais_tipos_textos_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(11) DEFAULT '0',
  `assunto` varchar(255) DEFAULT NULL,
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `codigos_promocionais_tipos_textos_en` (`id`, `tipo`, `assunto`, `texto`) VALUES
(1,	2,	'Ponto das Artes deseja-lhe um Feliz Aniversário',	'<strong>#cnome#</strong><br />\r\n<br />\r\nNeste dia especial, o Ponto das Artes deseja-lhe um&nbsp;<strong>Feliz Anivers&aacute;rio</strong>.<br />\r\n<br />\r\nPorque as datas especiais devem ser comemoradas, oferecemos-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em pontodasartes.com, de valor igual ou superior a #cminimo#&euro;.'),
(2,	3,	'Ponto das Artes - Não faz compras há #cdias# ou mais dias',	'<strong>#cnome#</strong><br />\r\n<br />\r\nVerificamos que h&aacute; #cdias# ou mais dias n&atilde;o efectua compras. Ponto das Artes oferece-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em www.pontodasartes.com, de valor igual ou superior a #cminimo#&euro;.'),
(3,	4,	'Ponto das Artes - Registou-se há #cdias# ou mais dias e ainda não fez nenhuma compra',	'<strong>#cnome#</strong><br />\r\n<br />\r\nVerificamos que se registou h&aacute; #cdias# ou mais dias mas ainda n&atilde;o efectuou compras. O Ponto das Artes oferece-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em www.pontodasartes.com, de valor igual ou superior a #cminimo#&euro;.'),
(4,	5,	'Ponto das Artes - Tem uma encomenda por pagar ou anulada há #cdias# ou mais dias',	'<strong>#cnome#</strong><br />\r\n<br />\r\nVerificamos que tem uma encomenda por pagar h&aacute; #cdias# ou mais dias. O Ponto das Artes oferece-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em www.pontodasartes.com, de valor igual ou superior a #cminimo#&euro;.');

DROP TABLE IF EXISTS `codigos_promocionais_tipos_textos_pt`;
CREATE TABLE `codigos_promocionais_tipos_textos_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` int(11) DEFAULT '0',
  `assunto` varchar(255) DEFAULT NULL,
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `codigos_promocionais_tipos_textos_pt` (`id`, `tipo`, `assunto`, `texto`) VALUES
(1,	2,	'Natura Saúde deseja-lhe um Feliz Aniversário',	'<strong>#cnome#</strong><br />\r\n<br />\r\nNeste dia especial, a Natura Sa&uacute;de deseja-lhe um&nbsp;<strong>Feliz Anivers&aacute;rio</strong>.<br />\r\n<br />\r\nPorque as datas especiais devem ser comemoradas, oferecemos-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em www.teste.pt, de valor igual ou superior a #cminimo#&euro;.'),
(2,	3,	'Natura Saúde - Não faz compras há #cdias# ou mais dias',	'<strong>#cnome#</strong><br />\r\n<br />\r\nVerificamos que h&aacute; #cdias# ou mais dias n&atilde;o efectua compras. A Natura Sa&uacute;de oferece-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em www.teste.com, de valor igual ou superior a #cminimo#&euro;.'),
(3,	4,	'Natura Saúde - Registou-se há #cdias# ou mais dias e ainda não fez nenhuma compra',	'<strong>#cnome#</strong><br />\r\n<br />\r\nVerificamos que se registou h&aacute; #cdias# ou mais dias mas ainda n&atilde;o efectuou compras. A Natura Sa&uacute;de oferece-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em www.teste.com, de valor igual ou superior a #cminimo#&euro;.'),
(4,	5,	'Natura Saúde - Tem uma encomenda por pagar ou anulada há #cdias# ou mais dias',	'<strong>#cnome#</strong><br />\r\n<br />\r\nVerificamos que tem uma encomenda por pagar h&aacute; #cdias# ou mais dias. A Natura Sa&uacute;de oferece-lhe um Vale de Desconto de #cdesconto#* para utilizar na sua pr&oacute;xima compra em www.teste.pt, de valor igual ou superior a #cminimo#&euro;.');

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dominio` varchar(50) DEFAULT NULL,
  `url_metatags` varchar(50) NOT NULL,
  `manutencao` int(11) NOT NULL DEFAULT '0',
  `ips` text,
  `imagem_popup` varchar(255) DEFAULT NULL,
  `link_popup` varchar(255) DEFAULT NULL,
  `ativo` tinyint(4) DEFAULT '0' COMMENT '0- Inativo; 1 - Ativo; ',
  `trans_time` int(11) NOT NULL,
  `tipo_popup` int(11) DEFAULT '1' COMMENT '1 - Abre uma vez; 2 - Abre sempre',
  `popup_run` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `config` (`id`, `dominio`, `url_metatags`, `manutencao`, `ips`, `imagem_popup`, `link_popup`, `ativo`, `trans_time`, `tipo_popup`, `popup_run`) VALUES
(1,	'http://bbakery.co.uk/beta/',	'http://bbakery.co.uk/beta/',	0,	'',	NULL,	'',	0,	5000,	1,	1);

DROP TABLE IF EXISTS `config_ecommerce`;
CREATE TABLE `config_ecommerce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor_embrulho` decimal(10,2) DEFAULT NULL,
  `ativar_vales` tinyint(4) DEFAULT '0',
  `max_saldo` decimal(10,2) DEFAULT NULL,
  `euros` decimal(10,2) DEFAULT NULL COMMENT 'X euros igual 1 ponto',
  `pontos` int(11) DEFAULT NULL COMMENT 'X pontos igual a Y euros em saldo',
  `euro_saldo` decimal(10,2) DEFAULT NULL COMMENT 'X pontos igual a Y euros em saldo',
  `saldo_por_compra_amigo` int(11) DEFAULT '0' COMMENT 'Apenas para 1ª Compra',
  `tipo_compra_amigo` tinyint(4) DEFAULT '1' COMMENT '1 - %; 2 - €',
  `valor_por_compra_amigo` int(11) DEFAULT '0' COMMENT 'Apenas para 1ª Compra',
  `ecommerce` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `config_ecommerce` (`id`, `valor_embrulho`, `ativar_vales`, `max_saldo`, `euros`, `pontos`, `euro_saldo`, `saldo_por_compra_amigo`, `tipo_compra_amigo`, `valor_por_compra_amigo`, `ecommerce`) VALUES
(1,	0.00,	2,	NULL,	1.00,	300,	10.00,	20,	1,	15,	1);

DROP TABLE IF EXISTS `config_imagens`;
CREATE TABLE `config_imagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL COMMENT 'Pasta /imgs/',
  `imagem1` varchar(255) DEFAULT NULL COMMENT 'Imagem Zoom',
  `min_height1` varchar(255) DEFAULT NULL,
  `max_width1` varchar(255) DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL COMMENT 'Imagem Normal',
  `min_height2` varchar(255) DEFAULT NULL,
  `max_width2` varchar(255) DEFAULT NULL,
  `imagem3` varchar(255) DEFAULT NULL COMMENT 'Imagem List',
  `min_height3` varchar(255) DEFAULT NULL,
  `max_width3` varchar(255) DEFAULT NULL,
  `imagem4` varchar(255) DEFAULT NULL COMMENT 'Imagem Pequena',
  `min_height4` varchar(255) DEFAULT NULL,
  `max_width4` varchar(255) DEFAULT NULL,
  `ativo` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`titulo`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `config_imagens` (`id`, `titulo`, `nome`, `imagem1`, `min_height1`, `max_width1`, `imagem2`, `min_height2`, `max_width2`, `imagem3`, `min_height3`, `max_width3`, `imagem4`, `min_height4`, `max_width4`, `ativo`) VALUES
(1,	'Produtos',	'produtos',	'1500x1350',	'',	'',	'450x405',	'',	'',	'982x870',	'',	'446',	'100x90',	'',	'',	1),
(2,	'Banners',	'banners',	'1920x600',	NULL,	NULL,	'1000x600',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(3,	'Paginas',	'paginas',	'3840x650',	'250',	NULL,	'2574x1240',	NULL,	NULL,	'1260x620',	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(4,	'Noticias',	'noticias',	'1000x350',	NULL,	NULL,	'900x485',	NULL,	'506',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(5,	'Testemunhos',	'testemunhos',	'373x276',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(6,	'Categorias',	'categorias',	'1920x300',	'200',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(7,	'Marcas',	'marcas',	'600x240',	'240',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(8,	'Destaques',	'destaques',	'1540x750',	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(9,	'Blog',	'blog',	'1920x400',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(10,	'Equipa',	'equipa',	'1920x400',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(11,	'Catalogos',	'catalogos',	'1920x400',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(12,	'Imagens Topo',	'imagens_topo',	'3840x900',	'250',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(13,	'Contactos',	'contactos',	'1920x600',	'400',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(14,	'Área Reservada',	'area_reservada',	'906x728',	NULL,	NULL,	NULL,	NULL,	NULL,	'785x450',	NULL,	NULL,	NULL,	NULL,	NULL,	1),
(15,	'Blog',	'blog/posts',	'540x500',	NULL,	NULL,	'2880x1428',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1);

DROP TABLE IF EXISTS `config_sessions`;
CREATE TABLE `config_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `nome_session` varchar(255) NOT NULL,
  `query` varchar(1000) DEFAULT '*',
  `query2` varchar(1000) DEFAULT NULL,
  `query3` varchar(1000) DEFAULT NULL,
  `onlyRow` tinyint(4) DEFAULT '0' COMMENT '0: Não; 1: Sim',
  `geral` tinyint(4) DEFAULT '0',
  `refresh` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `indice` (`nome`,`nome_session`,`onlyRow`,`geral`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `config_sessions` (`id`, `nome`, `nome_session`, `query`, `query2`, `query3`, `onlyRow`, `geral`, `refresh`) VALUES
(1,	'Banners',	'banners',	'SELECT * FROM banners_h#extensao# WHERE visivel=\'1\' AND ((datai<=\'#data#\' OR datai IS NULL OR datai=\'\') AND (dataf>=\'#data#\' OR dataf IS NULL OR dataf=\'\')) ORDER BY ordem ASC, rand()',	NULL,	NULL,	0,	0,	'2021-03-03 10:25:03'),
(2,	'Redes Sociais',	'redes',	'SELECT * FROM redes_sociais WHERE visivel = \'1\' AND link IS NOT NULL AND link!=\'\' ORDER BY ordem ASC',	NULL,	NULL,	0,	1,	'0000-00-00 00:00:00'),
(3,	'Linguas',	'linguas',	'SELECT * FROM linguas WHERE visivel = \'1\' AND ativo = \'1\' ORDER BY id ASC',	NULL,	NULL,	0,	1,	'0000-00-00 00:00:00'),
(4,	'Noticias',	'noticias',	'SELECT * FROM noticias#extensao# WHERE visivel = 1 ORDER BY ordem ASC, data DESC, id DESC',	'SELECT * FROM noticias_imagens WHERE visivel = \'1\' AND id_peca = #id# ORDER BY ordem ASC',	NULL,	0,	0,	'2020-10-08 09:02:49'),
(5,	'Contactos',	'contactos',	'SELECT * FROM contactos#extensao# WHERE id = 1',	'SELECT * FROM contactos_locais#extensao# WHERE visivel = 1 ORDER BY ordem ASC',	NULL,	1,	1,	'2021-03-15 13:23:08'),
(6,	'Paises',	'paises',	'SELECT * FROM paises WHERE visivel=1 ORDER BY nome ASC',	NULL,	NULL,	0,	1,	'2021-02-24 09:19:11'),
(7,	'Paginas',	'paginas',	'SELECT * FROM paginas#extensao# WHERE visivel = 1 ORDER BY ordem ASC',	'SELECT * FROM paginas_blocos#extensao# WHERE pagina = #id# AND visivel = \'1\' ORDER BY ordem ASC',	'SELECT * FROM paginas_blocos_imgs WHERE visivel = \'1\' AND bloco = #id# ORDER BY ordem ASC',	0,	1,	'2021-03-03 15:30:26'),
(8,	'Metatags',	'metatags',	'SELECT * FROM metatags#extensao# WHERE visivel=1 ORDER BY ordem ASC',	NULL,	NULL,	0,	1,	'2020-11-07 05:37:49'),
(9,	'Faqs',	'faqs',	'SELECT cats.* FROM faqs_categorias#extensao# AS cats LEFT JOIN faqs#extensao# AS faqs ON cats.id = faqs.categoria WHERE cats.visivel = 1 AND faqs.visivel = 1 ORDER BY cats.ordem ASC',	'SELECT * FROM faqs#extensao# WHERE visivel = 1 AND categoria = #id# ORDER BY ordem ASC',	NULL,	0,	0,	'2021-03-04 06:56:52'),
(10,	'Categorias',	'categorias',	'SELECT cat.* FROM l_categorias#extensao# AS cat, l_pecas#extensao# AS pecas WHERE cat.visivel = \'1\' AND cat.cat_mae = 0 AND pecas.visivel=\'1\'  GROUP BY cat.id ORDER BY cat.ordem ASC, cat.id ASC',	'SELECT cat.* FROM l_categorias#extensao# AS cat, l_pecas#extensao# AS pecas WHERE cat.visivel = \'1\' AND cat_mae = #id# AND pecas.visivel=\'1\' GROUP BY cat.id ORDER BY cat.ordem ASC, cat.id ASC',	'SELECT cat.* FROM l_categorias#extensao# AS cat, l_pecas#extensao# AS pecas WHERE cat.visivel = \'1\' AND cat_mae = #id# AND pecas.visivel=\'1\' GROUP BY cat.id ORDER BY cat.ordem ASC, cat.id ASC',	0,	1,	'2021-03-12 09:19:54'),
(11,	'Paginas fixas menu',	'paginas_fixas',	'SELECT * FROM paginas#extensao# WHERE visivel = 1 AND fixo = 1 AND mostrar_menu = 0 ORDER BY ordem ASC',	'SELECT * FROM paginas_blocos#extensao# WHERE pagina = #id# AND visivel = \'1\' ORDER BY ordem ASC',	'SELECT * FROM paginas_blocos_imgs WHERE visivel = \'1\' AND bloco = #id# ORDER BY ordem ASC',	0,	1,	'2021-03-03 15:30:26'),
(12,	'Paginas menu',	'paginas_menu',	'SELECT * FROM paginas#extensao# WHERE visivel = 1 AND mostrar_menu = 1 ORDER BY ordem ASC',	'SELECT * FROM paginas_blocos#extensao# WHERE pagina = #id# AND visivel = \'1\' ORDER BY ordem ASC',	'SELECT * FROM paginas_blocos_imgs WHERE visivel = \'1\' AND bloco = #id# ORDER BY ordem ASC',	0,	1,	'2021-03-03 15:30:26'),
(13,	'Faqs2',	'faqs2',	'SELECT cats.* FROM faqs2_categorias#extensao# AS cats LEFT JOIN faqs#extensao# AS faqs ON cats.id = faqs.categoria WHERE cats.visivel = 1 AND faqs.visivel = 1 ORDER BY cats.ordem ASC',	'SELECT * FROM faqs2#extensao# WHERE visivel = 1 AND categoria = #id# ORDER BY ordem ASC',	NULL,	0,	0,	'0000-00-00 00:00:00'),
(14,	'Supplier',	'supplier',	'SELECT * FROM supplier#extensao# WHERE visivel = 1 ORDER BY ordem ASC, data DESC, id DESC',	NULL,	NULL,	0,	0,	'2021-03-01 08:36:03');

DROP TABLE IF EXISTS `contactos_en`;
CREATE TABLE `contactos_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texto` text,
  `mapa` text,
  `email` varchar(250) DEFAULT NULL,
  `telefone` varchar(250) DEFAULT NULL,
  `gps` varchar(100) DEFAULT NULL,
  `link_google_maps` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `contactos_en` (`id`, `texto`, `mapa`, `email`, `telefone`, `gps`, `link_google_maps`) VALUES
(1,	'<span style=\"font-size:36px;\">Customer support </span><br />\r\n<br />\r\n<font><font><font><font><font><font><font><font>Lorem ipsum dolor sit amet, consectetur adipiscing elit. </font></font></font></font></font></font></font><font><font><font><font><font><font><font>Vestibulum nec nibh ut lacus bibendum ullamcorper nec ac orci. </font></font></font></font></font></font></font><font><font><font><font><font><font><font>Aliquam non dapibus elit. </font></font></font></font></font></font></font><font><font><font><font><font><font><font>Sed convallis erat nec tortor suscipit, ac sodales odio pellentesque. </font></font></font></font></font></font></font><font><font><font><font><font><font><font>Fusce consequat a tortor pulvinar imperdiet.</font></font></font></font></font></font></font></font>',	'',	'webtech.dev@gmail.com',	'+0121 285 2911',	NULL,	NULL);

DROP TABLE IF EXISTS `contactos_locais_en`;
CREATE TABLE `contactos_locais_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `texto` text,
  `gps` varchar(250) DEFAULT NULL,
  `link_google_maps` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `footer` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`,`footer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `contactos_locais_pt`;
CREATE TABLE `contactos_locais_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `texto` text,
  `gps` varchar(250) DEFAULT NULL,
  `link_google_maps` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `footer` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`,`footer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `contactos_pt`;
CREATE TABLE `contactos_pt` (
  `id` int(11) NOT NULL,
  `texto` text,
  `mapa` text,
  `email` varchar(250) DEFAULT NULL,
  `telefone` varchar(250) DEFAULT NULL,
  `gps` varchar(100) DEFAULT NULL,
  `link_google_maps` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `contactos_pt` (`id`, `texto`, `mapa`, `email`, `telefone`, `gps`, `link_google_maps`) VALUES
(1,	'<span style=\"color:#000000;\"><span style=\"font-size:36px;\">Store Locater</span></span><br />\r\n<br />\r\n&nbsp;',	'',	'mithilchauhan@gmail.com',	'+351 258 108 955',	NULL,	NULL);

DROP TABLE IF EXISTS `convidar_amigos`;
CREATE TABLE `convidar_amigos` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT '0',
  `email_convidado` varchar(255) DEFAULT NULL,
  `data_convite` datetime DEFAULT NULL,
  `aceite` tinyint(4) DEFAULT '0',
  `id_novo_cliente` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `destaques_en`;
CREATE TABLE `destaques_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `target` varchar(50) DEFAULT '0',
  `texto` text,
  `imagem1` text,
  `imagem2` text,
  `cor` varchar(30) DEFAULT NULL,
  `bg_cor` varchar(30) DEFAULT NULL,
  `texto_botao` varchar(100) DEFAULT NULL,
  `tema` tinyint(4) DEFAULT NULL,
  `destacado` tinyint(4) NOT NULL DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `destaques_en` (`id`, `nome`, `titulo`, `link`, `target`, `texto`, `imagem1`, `imagem2`, `cor`, `bg_cor`, `texto_botao`, `tema`, `destacado`, `ordem`, `visivel`) VALUES
(1,	'Birthday Cakes',	'Birthday Cakes',	'http://bbakery.co.uk/beta/cakes-icing-birthday-cakes',	'_parent',	'',	'012253_1_3997_birthday-cakes-300x220-300x220.png',	'',	'#FFFFFF',	'#222222',	'',	1,	0,	1,	1),
(2,	'Wedding Cakes',	'Wedding Cakes',	'http://bbakery.co.uk/beta/wedding-cakes',	'_parent',	NULL,	'012345_1_4891_wedding-cakes-300x220-300x220.png',	'',	'#FFFFFF',	'#FFFFFF',	NULL,	1,	0,	2,	1),
(3,	'Photo Cakes',	'Photo Cakes',	'http://bbakery.co.uk/beta/cakes-photo-cakes',	'_parent',	NULL,	'012409_1_8058_photo-cake-300x220-300x220.png',	'',	'#FFFFFF',	'#FFFFFF',	NULL,	1,	0,	3,	1),
(4,	'Click and Collect 1 Hr',	'Click and Collect 1 Hr',	'http://bbakery.co.uk/beta/cakes-click-amp',	'',	NULL,	'102937_1_3760_click-and-colloect-300x220-300x220.png',	NULL,	'#FFFFFF',	'#FFFFFF',	NULL,	1,	0,	99,	1);

DROP TABLE IF EXISTS `destaques_pt`;
CREATE TABLE `destaques_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `target` varchar(50) DEFAULT '0',
  `texto` text,
  `imagem1` text,
  `imagem2` text,
  `cor` varchar(30) DEFAULT NULL,
  `bg_cor` varchar(30) DEFAULT NULL,
  `texto_botao` varchar(100) DEFAULT NULL,
  `tema` tinyint(4) DEFAULT NULL,
  `destacado` tinyint(4) NOT NULL DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `destaques_pt` (`id`, `nome`, `titulo`, `link`, `target`, `texto`, `imagem1`, `imagem2`, `cor`, `bg_cor`, `texto_botao`, `tema`, `destacado`, `ordem`, `visivel`) VALUES
(1,	'Birthday Cakes',	'Birthday Cakes',	'suplementacao-alimentar',	'_parent',	NULL,	'012253_1_3997_birthday-cakes-300x220-300x220.png',	NULL,	'#FFFFFF',	'#222222',	NULL,	2,	0,	1,	1),
(2,	'Wedding Cakes',	'Wedding Cakes',	'nutricao-desportiva',	'_parent',	NULL,	'012345_1_4891_wedding-cakes-300x220-300x220.png',	NULL,	'#FFFFFF',	'#222222',	NULL,	2,	0,	2,	1),
(3,	'Photo Cakes',	'Photo Cakes',	'higiene-e-cosmetica',	'_parent',	NULL,	'012409_1_8058_photo-cake-300x220-300x220.png',	'',	'#FFFFFF',	'#222222',	NULL,	2,	0,	3,	1);

DROP TABLE IF EXISTS `encomendas`;
CREATE TABLE `encomendas` (
  `id` int(11) NOT NULL,
  `prepration` varchar(11) DEFAULT NULL,
  `store_name` varchar(155) DEFAULT NULL,
  `pickup_data` datetime DEFAULT NULL,
  `deliverystatus` varchar(10) DEFAULT NULL,
  `message` text,
  `numero` int(11) DEFAULT NULL,
  `txn_id` varchar(500) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `nome_envio` varchar(255) DEFAULT NULL,
  `morada_envio` text,
  `codpostal_envio` varchar(250) DEFAULT NULL,
  `localidade_envio` varchar(250) DEFAULT NULL,
  `pais_envio` varchar(250) DEFAULT NULL,
  `cod_pais` varchar(10) DEFAULT NULL,
  `morada_fatura` text,
  `codpostal_fatura` varchar(250) DEFAULT NULL,
  `localidade_fatura` varchar(250) DEFAULT NULL,
  `pais_fatura` varchar(250) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `telemovel` varchar(250) DEFAULT NULL,
  `nif` varchar(250) DEFAULT NULL,
  `met_pagamt_id` int(11) DEFAULT '0',
  `pagamento` text,
  `entidade` varchar(255) DEFAULT NULL,
  `ref_pagamento` varchar(250) DEFAULT NULL,
  `url_pagamento` text,
  `url_paypal` text,
  `prods_total` decimal(10,2) DEFAULT NULL,
  `valor_total` decimal(10,2) DEFAULT NULL,
  `valor_iva` decimal(10,2) DEFAULT NULL,
  `valor_c_iva` decimal(10,2) DEFAULT NULL,
  `observacoes` longtext,
  `portes_pagamento` decimal(10,2) DEFAULT NULL,
  `portes_entrega` decimal(10,2) DEFAULT NULL,
  `entrega_id` int(11) DEFAULT NULL,
  `entrega` text,
  `opcao_texto` varchar(100) DEFAULT NULL,
  `opcao` decimal(10,2) DEFAULT '0.00',
  `fatura_digital` varchar(250) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `estado` varchar(250) DEFAULT NULL,
  `email_enviado` tinyint(4) DEFAULT '0',
  `cheque` int(11) DEFAULT NULL,
  `finalizada` int(11) DEFAULT '0',
  `lingua` varchar(20) DEFAULT NULL,
  `link_seguir_envio` varchar(200) DEFAULT NULL,
  `saldo_compra` decimal(10,2) DEFAULT '0.00',
  `saldo_compra_utilizado` tinyint(4) DEFAULT '0',
  `compra_valor_saldo` decimal(10,2) DEFAULT '0.00',
  `codigo_promocional` varchar(20) DEFAULT NULL,
  `codigo_promocional_desconto` varchar(250) DEFAULT NULL,
  `codigo_promocional_valor` decimal(10,2) DEFAULT NULL,
  `pontos_compra` decimal(10,2) DEFAULT NULL,
  `pontos_compra_utilizado` tinyint(4) DEFAULT '0',
  `envio_link` varchar(350) DEFAULT NULL,
  `envio_ref` varchar(30) DEFAULT NULL,
  `moeda` varchar(50) DEFAULT NULL,
  `valor_conversao` decimal(10,5) DEFAULT NULL,
  `nova` tinyint(4) DEFAULT '1',
  `notificado` tinyint(4) DEFAULT '0',
  `cancle_mes` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`numero`,`id_cliente`,`estado`,`data`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `encomendas` (`id`, `prepration`, `store_name`, `pickup_data`, `deliverystatus`, `message`, `numero`, `txn_id`, `id_cliente`, `nome`, `nome_envio`, `morada_envio`, `codpostal_envio`, `localidade_envio`, `pais_envio`, `cod_pais`, `morada_fatura`, `codpostal_fatura`, `localidade_fatura`, `pais_fatura`, `email`, `telefone`, `telemovel`, `nif`, `met_pagamt_id`, `pagamento`, `entidade`, `ref_pagamento`, `url_pagamento`, `url_paypal`, `prods_total`, `valor_total`, `valor_iva`, `valor_c_iva`, `observacoes`, `portes_pagamento`, `portes_entrega`, `entrega_id`, `entrega`, `opcao_texto`, `opcao`, `fatura_digital`, `data`, `estado`, `email_enviado`, `cheque`, `finalizada`, `lingua`, `link_seguir_envio`, `saldo_compra`, `saldo_compra_utilizado`, `compra_valor_saldo`, `codigo_promocional`, `codigo_promocional_desconto`, `codigo_promocional_valor`, `pontos_compra`, `pontos_compra_utilizado`, `envio_link`, `envio_ref`, `moeda`, `valor_conversao`, `nova`, `notificado`, `cancle_mes`) VALUES
(1,	'3',	'SPARK HILL BRANCH',	'0000-00-00 00:00:00',	'0',	'Nice Cake',	1,	NULL,	55,	'vishal',	'vishal',	'india',	'769836',	'india',	'Portugal Continental',	'PT',	'india',	'769836',	'india',	'Portugal Continental',	'prajapativishal999991@gmail.com',	NULL,	'7698365262',	'',	10,	'WorldPay<br><font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Pay with your credit card through WorldPay. Transactions are 100% secure.</font></font>',	'',	'',	'',	NULL,	12.00,	12.00,	2.24,	12.00,	NULL,	0.00,	0.00,	8,	'Store Pickup',	NULL,	NULL,	NULL,	'2021-03-16 07:08:10',	'1',	1,	NULL,	0,	'en',	NULL,	NULL,	0,	NULL,	'',	'0',	NULL,	NULL,	0,	'',	NULL,	'&pound;',	1.00000,	1,	0,	NULL);

DROP TABLE IF EXISTS `encomendas_msg`;
CREATE TABLE `encomendas_msg` (
  `id` bigint(20) NOT NULL,
  `encomenda` int(11) DEFAULT NULL,
  `id_pai` int(11) DEFAULT '0',
  `id_cliente` int(11) DEFAULT NULL,
  `remetente` varchar(255) DEFAULT NULL,
  `assunto` varchar(255) DEFAULT NULL,
  `descricao` text,
  `data` datetime DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1',
  `visto` tinyint(4) DEFAULT '0',
  `lingua` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`encomenda`,`id_pai`,`id_cliente`,`data`,`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `encomendas_obs`;
CREATE TABLE `encomendas_obs` (
  `id` bigint(20) NOT NULL,
  `id_encomenda` int(11) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `descricao` text,
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_encomenda`,`id_cliente`,`data`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `encomendas_produtos`;
CREATE TABLE `encomendas_produtos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_encomenda` int(11) DEFAULT NULL,
  `id_oferta` int(11) DEFAULT '0',
  `cat_mea` int(5) NOT NULL,
  `produto` varchar(250) DEFAULT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `imagem1` varchar(250) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `opcoes` text,
  `qtd` int(11) DEFAULT '1',
  `preco` decimal(10,2) DEFAULT NULL,
  `desconto` int(11) DEFAULT '0',
  `iva` int(11) DEFAULT '0',
  `produto_id` int(11) DEFAULT NULL,
  `opcoes_id` int(11) DEFAULT '0',
  `user_message` text,
  `cheque_prenda` tinyint(4) DEFAULT '0',
  `cheque_nome` varchar(100) DEFAULT NULL,
  `cheque_email` varchar(100) DEFAULT NULL,
  `cheque_usado` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_encomenda`,`produto_id`,`opcoes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `encomendas_produtos` (`id`, `id_encomenda`, `id_oferta`, `cat_mea`, `produto`, `ref`, `imagem1`, `url`, `opcoes`, `qtd`, `preco`, `desconto`, `iva`, `produto_id`, `opcoes_id`, `user_message`, `cheque_prenda`, `cheque_nome`, `cheque_email`, `cheque_usado`) VALUES
(36,	1,	0,	0,	'tests',	'',	'http://bbakery.co.uk/beta/imgs/produtos/pq_1458213804_web_6_.jpg',	'test',	'Size: 8\";',	1,	12.00,	0,	20,	161,	1,	'Nice Cake',	0,	'',	'',	0);

DROP TABLE IF EXISTS `enc_estados`;
CREATE TABLE `enc_estados` (
  `id` int(11) NOT NULL,
  `nome_pt` varchar(250) DEFAULT NULL,
  `nome_en` varchar(250) DEFAULT NULL,
  `nome_es` varchar(250) DEFAULT NULL,
  `nome_fr` varchar(250) DEFAULT NULL,
  `nome_de` varchar(250) DEFAULT NULL,
  `nome_it` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `enc_estados` (`id`, `nome_pt`, `nome_en`, `nome_es`, `nome_fr`, `nome_de`, `nome_it`, `ordem`) VALUES
(1,	'A aguardar pagamento',	'Waiting for payment',	'Esperando Pago',	'En attente de paiement',	'Warten auf zahlung',	'In attesa di pagamento',	1),
(2,	'Em processamento',	'In process',	'En proceso',	'In process',	'Im prozess',	'Elaborazione',	2),
(3,	'Enviada',	'Sent',	'Enviado',	'Envoyé',	'Verschickt',	'Inviata',	4),
(4,	'Concluída',	'Completed',	'Terminado',	'Terminé',	'Fertiggestellt',	'Completato',	5),
(5,	'Anulada',	'canceled',	'Anulado',	'Annulé',	'Annulliert',	'Annullato',	6),
(6,	'Pronta para Levantamento',	'Ready for Pickup',	'Listo para Recojer',	'Prêt pour le ramassage',	'Abholbereit',	'Pronto per il ritiro',	3),
(7,	'New Order',	'New Order',	'New Order',	'New Order',	'New Order',	'New Order',	99),
(8,	'Enquiry',	'Enquiry',	'Enquiry',	'Enquiry',	'Enquiry',	'Enquiry',	99);

DROP TABLE IF EXISTS `enc_estados_historico`;
CREATE TABLE `enc_estados_historico` (
  `id` bigint(20) NOT NULL,
  `id_encomenda` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `notificado` tinyint(4) DEFAULT NULL,
  `automatico` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_encomenda`,`estado`,`data`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `enc_estados_historico` (`id`, `id_encomenda`, `estado`, `data`, `notificado`, `automatico`) VALUES
(0,	1,	5,	'2021-02-23 14:57:39',	1,	0),
(1,	1,	4,	'2021-02-23 14:50:45',	1,	0);

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` text,
  `start_event` date NOT NULL,
  `visivel` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `events` (`id`, `title`, `start_event`, `visivel`) VALUES
(4,	'Janmashthmi',	'2021-03-02',	1),
(5,	'Second Janmasthmi',	'2020-11-10',	1);

DROP TABLE IF EXISTS `faq2_content_en`;
CREATE TABLE `faq2_content_en` (
  `id` int(11) NOT NULL,
  `pergunta` varchar(1000) COLLATE utf8_bin NOT NULL,
  `resposta` varchar(5000) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `faq2_content_pt`;
CREATE TABLE `faq2_content_pt` (
  `id` int(11) NOT NULL,
  `pergunta` varchar(1000) COLLATE utf8_bin NOT NULL,
  `resposta` varchar(5000) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `faqs2_categorias_en`;
CREATE TABLE `faqs2_categorias_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs2_categorias_en` (`id`, `nome`, `ordem`, `visivel`) VALUES
(1,	'cat1',	99,	1),
(2,	'category2',	99,	1);

DROP TABLE IF EXISTS `faqs2_categorias_pt`;
CREATE TABLE `faqs2_categorias_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs2_categorias_pt` (`id`, `nome`, `ordem`, `visivel`) VALUES
(1,	'cat1',	99,	1),
(2,	'category2',	99,	1);

DROP TABLE IF EXISTS `faqs2_en`;
CREATE TABLE `faqs2_en` (
  `id` int(11) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `pergunta` varchar(250) DEFAULT NULL,
  `resposta` text,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs2_en` (`id`, `categoria`, `pergunta`, `resposta`, `ordem`, `visivel`) VALUES
(1,	1,	'First faq',	'lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;',	99,	1),
(2,	2,	'Second faq',	'lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;<br />\r\nlorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lor',	99,	1);

DROP TABLE IF EXISTS `faqs2_pt`;
CREATE TABLE `faqs2_pt` (
  `id` int(11) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `pergunta` varchar(250) DEFAULT NULL,
  `resposta` text,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs2_pt` (`id`, `categoria`, `pergunta`, `resposta`, `ordem`, `visivel`) VALUES
(1,	1,	'First faq',	'lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;',	99,	1),
(2,	2,	'Second faq',	'lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;<br />\r\nlorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lorem ispum&nbsp;lor',	99,	1);

DROP TABLE IF EXISTS `faqs_categorias_en`;
CREATE TABLE `faqs_categorias_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs_categorias_en` (`id`, `nome`, `ordem`, `visivel`) VALUES
(1,	'All',	99,	1),
(2,	'cat1',	99,	1),
(3,	'cat2',	99,	1);

DROP TABLE IF EXISTS `faqs_categorias_pt`;
CREATE TABLE `faqs_categorias_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs_categorias_pt` (`id`, `nome`, `ordem`, `visivel`) VALUES
(1,	'All',	99,	1),
(2,	'cat',	99,	1),
(3,	'cat2',	99,	1);

DROP TABLE IF EXISTS `faqs_en`;
CREATE TABLE `faqs_en` (
  `id` int(11) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `pergunta` varchar(250) DEFAULT NULL,
  `resposta` text,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs_en` (`id`, `categoria`, `pergunta`, `resposta`, `ordem`, `visivel`) VALUES
(1,	1,	'ARE ALL YOUR CAKES FRESH CREAM?',	'NO. WE HAVE LOTS OF DIFFERENT TYPES OF CREAM, INCLUDING VANILLA SWEET CREAM, CHOCOLATE SWEET CREAM, SOYA CREAM, AND A VAREITY OF FILLINGS.',	2,	1),
(2,	1,	'ALLERGIES?',	'PLEASE ASK A MEMBER OF STAFF IF YOU HAVE AN IREGGULAR ALLERGY',	1,	1),
(3,	1,	'ARE ANY OF YOUR PRODUCTS FROZEN?',	'NO. WE BAKE FRESH EVERYDAY.',	3,	1),
(4,	1,	'ARE WE DAIRY FREE?',	'NO, WE USE FRESH DAIRY WHIPPING CREAM IN ALL OUR FRESH CREAM PRODUCTS',	4,	1),
(5,	1,	'ARE WE VEGETARIAN?',	'YES, ALL OUR PRODUCTS ARE SUITABLE FOR VEGETARIAN, BUT NOT VEGANS',	5,	1),
(6,	1,	'ARE YOU BETTER THAN THE CAKE BOX?',	'YES 100%',	6,	1),
(7,	1,	'ARE YOUR CAKES EGGFREE?',	'YES 100%<br />\r\n&nbsp;<br />\r\n&nbsp;',	99,	1),
(8,	1,	'CAN I GET A CAKE NOW?',	'WE ALWAYS HAVE A SELECTION OF CASH AND CARRY CAKES (8&acirc;€&amp;10&acirc;€) IN OUR COUNTERS READY TO GO WHICH YOU CAN HAVE A MESSAGE ON WHILE YOU WAIT, WAITING TIME NORMALLY AROUND 5 MINIUTES',	99,	1),
(9,	1,	'CAN I HAVE MORE THAN ONE TYPE OF CREAM ON MY CAKE.?',	'IT&rsquo;S YOUR CAKE, YOU CAN HAVE WHATEVER COMBINATION YOU WANT, ALTHOUGH WE TAKE NO RESPONSIBILITY FOR YOUR TASTE BUDS',	99,	1),
(10,	1,	'CAN I ORDER A CAKE ON THE SAME DAY?',	'YES WE MAKE ALL OUR CAKES FROM START TO FINISH ON THE PREMISES, THESE ENABLE YOU TO ORDER ANY COMBINATION OF SPONGE AND CREAM FILLING. IF YOUR LOCAL BRANCH CANNOT MAKE WHAT YOU REQUIRE, OUR STAFF WILL FIND YOU THE NEAREST BRANCH THAT CAN',	99,	1),
(11,	1,	'CAN I ORDER AT ONE BRANCH AND PICK UP AT ANOTHER?',	'YES, BUT IT WILL BE BETTER TO ORDER ONLINE AT WWW.BBAKERY.CO.UK AND CHOOSING THE STORE PICK UP OPTION AT CHECKOUT',	99,	1),
(12,	1,	'CAN I TASTE BEFORE I BUY?',	'YES WE HAVE SLICES AVAILABLE TO BUY ALL DAY',	99,	1),
(13,	1,	'CAN YOU MAKE A DESIGN FROM ANOTHER COMPANY?',	'YES WE CAN, BUT IT WILL NEVER BE 100% THE SAME AS WE USE A DIFFERENT TECHNIQUE AND TOOLS',	99,	1),
(14,	1,	'DO YOU DELIVER?',	'ONLY OUR RANGE OF ICED WEDDING CAKES',	99,	1),
(15,	1,	'DO YOU DO CAKES WITH EGGS?',	'NO, WE HAVE GONE WITH OUR CUSTOMERS TASTE, EGGS REALLY ARE ONLY IN SPONGE CAKES TO HELP MAKE A SOLID BASE, AND IF YOU CAN TASTE THE EGG THEN ITS PROBERLY GOT TO MANY IN, OUR EGG FREE CAKES ARE LIGHT AND FLUFFY.',	99,	1),
(16,	1,	'HALAL?',	'OUR CAKES CONTAIN MILK AND CREAM, THEY DO NOT CONTAIN ANY OTHER ANIMAL PRODUCTS. 95% OF OUR CUSTOMER BASE IS FROM ETHNIC BACKGROUND, MUSLIM, SIKH, HINDU AND OUR CAKES ARE FAMOUS WITH THESE COMMUNITIES',	99,	1),
(17,	1,	'HOW DO I ORDER?',	'VISIT YOU LOCAL STORE, TELEPHONE OR ONLINE (YOU CANNOT ORDER SAME DAY SERVICE ONLINE)',	99,	1),
(18,	1,	'HOW LONG NOTICE DO I NEED TO PLACE ORDER?',	'WE CAN GENERALLY DO MOST CAKES THE SAME DAY. ALTHOUGH ITS ALWAYS BEST TO GIVE AS MUCH NOTICE AS POSSIBLE',	99,	1),
(19,	1,	'I HAVE A NUT ALLERGY',	'WE TAKE EVERY CARE WITH YOUR ORDER IF YOU TELL US YOUR ALLERGY, BUT PLEASE BE AWARE WE USE NUTS IN ALL BISMILLAH BAKERY PREMISES, WE ONLY PUT NUTS ON OUR CAKES TO ORDER, AND YOU WILL GENERALLY NEVER SEE THEM IN OUR CASH AND CARRY RANGE',	99,	1),
(20,	1,	'WHAT DO YOU REPLACE EGGS WITH?',	'WE USE OUR OWN DEVELOPED CAKE MIX, AND THE EGGS ARE REPLACED WITH MILK AND SOY PROTIENS',	99,	1),
(21,	1,	'WHAT SIZE DO I NEED?',	'WE HAVE APPROX PORTION GUIDS IN STORE, BUT IT WILL DEPEND ON HOW YOU CUT YOU CAKE',	99,	1),
(22,	1,	'WILL MY CAKE BE THE SAME AS YOUR PICTURE IN YOUR CATALOGUE?',	'ALL OUR CAKES ARE HAND MADE, THEREFORE COLOURS AND DESIGNS MAY CHANGE SLIGHTLY, OUR PICTURES ARE FOR GUIDANCE ONLY',	99,	1);

DROP TABLE IF EXISTS `faqs_pt`;
CREATE TABLE `faqs_pt` (
  `id` int(11) NOT NULL,
  `categoria` int(11) DEFAULT NULL,
  `pergunta` varchar(250) DEFAULT NULL,
  `resposta` text,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `faqs_pt` (`id`, `categoria`, `pergunta`, `resposta`, `ordem`, `visivel`) VALUES
(1,	1,	'Quais são as áreas de intervenção?',	'Na Natura Online, realizamos uma anamnese adequada, com v&aacute;rios m&eacute;todos de diagn&oacute;stico n&atilde;o convencionais, utilizando v&aacute;rias t&eacute;cnicas naturais que respeitam as leis naturais que regulam a fun&ccedil;&atilde;o do corpo.<br />\r\nO objetivo &eacute; equilibrar o seu organismo, recorrendo a mecanismos naturais que promovem a auto recupera&ccedil;&atilde;o, a preven&ccedil;&atilde;o de doen&ccedil;a e de envelhecimento prematuro e com pouca qualidade de vida.<br />\r\nO nosso modo de vida, a forma como encaramos e lidamos com a vida e o nosso dia a dia, s&atilde;o determinantes na manuten&ccedil;&atilde;o da qualidade de vida e da sa&uacute;de.<br />\r\nNa Natura Online olhamos para o ser humano como um todo procuramos solu&ccedil;&otilde;es integradas, se precisa do nosso apoio entre em contato com a Natura Online. Juntos encontraremos o caminho para o seu equil&iacute;brio, temos ao vosso dispor terapias e tratamentos naturais que facilitem o processo de auto-cura.<br />\r\n&nbsp;',	2,	1),
(2,	1,	'A quem se dirige?',	'<strong>Pessoas saud&aacute;veis</strong> &ndash; que pretendem manter o n&iacute;vel de sa&uacute;de, prevenir doen&ccedil;a e otimizar todas as suas fun&ccedil;&otilde;es metab&oacute;licas.<br />\r\nDesportistas, adolescentes, pessoas sujeitas a maior esfor&ccedil;o f&iacute;sico ou mental.<br />\r\n<strong>Pessoas com alguns sinais de desconforto</strong> &ndash; Com o passar do tempo e com as altera&ccedil;&otilde;es do ambiente em que vivemos, desde qu&iacute;micos, pesticidas, alimenta&ccedil;&atilde;o, stress, sedentarismo, obesidade, entre outros, os sinais de desconforto e envelhecimento aparecem cada vez mais cedo e em maior n&uacute;mero de pessoas.<br />\r\nExcesso de peso, dores, enxaquecas, problemas de pele, dist&uacute;rbios intestinais e digestivos, irritabilidade, altera&ccedil;&otilde;es do sono, do humor, nos valores de tens&atilde;o arterial, colesterol, glicose, s&atilde;o alguns exemplos de sinais que devem ser interpretados e que indicam que o nosso corpo est&aacute; em desequil&iacute;brio.<br />\r\n<strong>Pessoas doentes</strong> &ndash; As pessoas que j&aacute; sofrem de algum problema tamb&eacute;m podem beneficiar com os tratamentos naturais. A corre&ccedil;&atilde;o da alimenta&ccedil;&atilde;o, modo de vida, dete&ccedil;&atilde;o de necessidades micronutricionais s&atilde;o determinantes para a recupera&ccedil;&atilde;o r&aacute;pida e maior efic&aacute;cia dos tratamentos aplicados pela Medicina Convencional.',	1,	1),
(3,	1,	'1Como proceder para pedir aconselhamento?',	'Deve enviar email, com a sua identifica&ccedil;&atilde;o, idade, morada e telefone e colocar em &ldquo;assunto&rdquo;: &ldquo;pedido de aconselhamento&rdquo;.<br />\r\nAcrescentar breve descri&ccedil;&atilde;o do da sua hist&oacute;ria cl&iacute;nica.<br />\r\n&nbsp;',	3,	1),
(4,	1,	'Posso pedir aconselhamento e não comprar produtos?',	'Se pedir aconselhamento e n&atilde;o comprar produtos, se pedir segundo aconselhamento s&oacute; com marca&ccedil;&atilde;o de consulta.',	4,	1),
(5,	1,	'Como proceder para marcação de consulta?',	'Deve enviar email, com a sua identifica&ccedil;&atilde;o, idade, morada e telefone e colocar em &ldquo;assunto&rdquo;: &ldquo;marca&ccedil;&atilde;o de consulta&rdquo;.<br />\r\nAcrescentar breve descri&ccedil;&atilde;o da hist&oacute;ria cl&iacute;nica e disponibilidade para posteriormente procedermos &agrave; marca&ccedil;&atilde;o em hor&aacute;rio compat&iacute;vel.',	5,	1),
(6,	1,	'Qual a duração e o valor da consulta?',	'Ap&oacute;s a rece&ccedil;&atilde;o de primeiro e-mail a pedir marca&ccedil;&atilde;o, fazemos triagem para determinar o tipo de consulta e enviamos a dura&ccedil;&atilde;o m&eacute;dia a consulta e os dados para efetuar pr&eacute;-pagamento, ap&oacute;s envio de comprovativo do pagamento a marca&ccedil;&atilde;o fica confirmada.',	6,	1),
(7,	1,	'Como devo proceder para esclarecer dúvidas?',	'Deve enviar email e colocar no &ldquo;assunto&rdquo;: esclarecimento de d&uacute;vidas.<br />\r\n&nbsp;<br />\r\n&nbsp;',	99,	1);

DROP TABLE IF EXISTS `faq_content_en`;
CREATE TABLE `faq_content_en` (
  `id` int(11) NOT NULL,
  `pergunta` varchar(1000) COLLATE utf8_bin NOT NULL,
  `resposta` varchar(5000) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `faq_content_en` (`id`, `pergunta`, `resposta`) VALUES
(1,	'Head',	'test');

DROP TABLE IF EXISTS `faq_content_pt`;
CREATE TABLE `faq_content_pt` (
  `id` int(11) NOT NULL,
  `pergunta` varchar(1000) COLLATE utf8_bin NOT NULL,
  `resposta` varchar(5000) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `faq_content_pt` (`id`, `pergunta`, `resposta`) VALUES
(1,	'Title',	'As terapias naturais e hol&iacute;sticas visam o bem-estar, atrav&eacute;s da natureza. A filosofia da medicina natural afirma que a cura e a sa&uacute;de prevalecem quando existem condi&ccedil;&otilde;es saud&aacute;veis. Assim, o objetivo da NATURA ONLINE &eacute; promover a sa&uacute;de de forma natural.<br />\r\nAs terapias naturais e integrativas como a Naturopatia, Osteopatia, Homeopatia, Fitoterapia, Aromaterapia entre outras, visam a integridade do bio-sistema humano, sendo uma abordagem orientada a uma conex&atilde;o sin&eacute;rgica integral do ser humano. Combina assim diferentes t&eacute;cnicas com suporte cient&iacute;fico para restabelecer a sa&uacute;de.<br />\r\nEsta abordagem avalia o seu corpo de uma forma global e integrada que permite identificar e tratar as causas de forma hol&iacute;stica e n&atilde;o apenas os sintomas e assenta em 5 pilares fundamentais:\r\n<ul>\r\n	<li>Assist&ecirc;ncia centrada na rela&ccedil;&atilde;o com o paciente;</li>\r\n	<li>Elimina&ccedil;&atilde;o dos obst&aacute;culos que dificultam a resposta de auto restabelecimento do organismo;</li>\r\n	<li>Utiliza&ccedil;&atilde;o de meios terap&ecirc;uticos naturais e menos invasivos;</li>\r\n	<li>Restabelecimento integral da sa&uacute;de de forma trivetorial (mente-corpo e esp&iacute;rito);</li>\r\n	<li>Restitui&ccedil;&atilde;o da sa&uacute;de sempre que poss&iacute;vel e sempre baseada na evidencia cient&iacute;fica. &nbsp;</li>\r\n</ul>\r\n<br />\r\nA terapia Natural integrativa permite reestabelecer a sa&uacute;de, prevenir doen&ccedil;as e minimizar os efeitos do envelhecimento, com base em processos biol&oacute;gicos, portanto naturais, sem recorrer a qu&iacute;micos e a m&eacute;todos invasivos. No entanto, em determinados casos poder&aacute; complementar tratamentos qu&iacute;micos j&aacute; institu&iacute;dos<br />\r\n&nbsp;');

DROP TABLE IF EXISTS `homepage_en`;
CREATE TABLE `homepage_en` (
  `id` int(11) NOT NULL,
  `titulo1` varchar(250) DEFAULT NULL,
  `titulo_link1` text,
  `subtitulo1` varchar(255) DEFAULT NULL,
  `icone1` varchar(255) DEFAULT NULL,
  `link1` text,
  `titulo2` varchar(255) DEFAULT NULL,
  `titulo_link2` text,
  `subtitulo2` varchar(255) DEFAULT NULL,
  `icone2` varchar(255) DEFAULT NULL,
  `link2` text,
  `titulo3` varchar(255) DEFAULT NULL,
  `subtitulo3` varchar(255) DEFAULT NULL,
  `icone3` varchar(255) DEFAULT NULL,
  `link3` varchar(255) DEFAULT NULL,
  `imagem_news` varchar(255) DEFAULT NULL,
  `texto_news` text,
  `imagem_form` varchar(255) DEFAULT NULL,
  `texto_form` text,
  `texto_header` varchar(1000) DEFAULT NULL,
  `home_pft` varchar(1000) DEFAULT NULL,
  `home_pfc` text,
  `home_cft` varchar(1000) DEFAULT NULL,
  `home_cfc` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `homepage_en` (`id`, `titulo1`, `titulo_link1`, `subtitulo1`, `icone1`, `link1`, `titulo2`, `titulo_link2`, `subtitulo2`, `icone2`, `link2`, `titulo3`, `subtitulo3`, `icone3`, `link3`, `imagem_news`, `texto_news`, `imagem_form`, `texto_form`, `texto_header`, `home_pft`, `home_pfc`, `home_cft`, `home_cfc`) VALUES
(1,	'Free shipping over 20 &pound; | Delivery in 3-4 working days',	'http://bbakery.co.uk/beta/',	NULL,	'053917_1_4239_angle-left.svg',	NULL,	'',	'http://bbakery.co.uk/beta/',	NULL,	NULL,	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'mithilchauhan@gmail.com',	'77788899',	'Freshly Whipped Cream Cake Shop in Birmingham',	'Welcome to Bismillah Bakery, an established and highly experienced cake shop, and makers of mouth-watering Freshly whipped cream cakes, halal cakes and vegan cakes. Based in Birmingham in the West Midlands all our cakes are handmade using the finest ingredients sourced from local suppliers.\r\n\r\n',	'Cakes for All Occasions',	'With a long running history, we have grown to become one of the best cake shops in Birmingham and the West Midlands. From novelty birthday cakes to traditional wedding cakes and everything in between, we can create a bespoke cake for any occasion. All cakes are available as Halal, vegan or Freshly whipped cream options so please let us know what you need, and we’ll be happy to create something for your special occasion.'),
(2,	'ENTREGAS GRATUITAS',	NULL,	'Encomendas online acima de 100 €',	'055149_1_7647_delivery.svg',	'http://www.netgocio.pt/',	'COMPRA SEGURA',	NULL,	'Vários tipos de pagamento disponíveis',	'055303_2_1807_shield.svg',	'http://www.netgocio.pt/',	'DEVOLUÇÕES',	'Nao esta satisfeito? Devolvemos o valor.',	'055334_3_1306_revart.svg',	'http://www.netgocio.pt/',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `homepage_image_en`;
CREATE TABLE `homepage_image_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(600) COLLATE utf8_bin NOT NULL,
  `titulo` varchar(1000) COLLATE utf8_bin NOT NULL,
  `link` varchar(1000) COLLATE utf8_bin NOT NULL,
  `imagem` varchar(1000) COLLATE utf8_bin NOT NULL,
  `visivel` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `homepage_image_en` (`id`, `nome`, `titulo`, `link`, `imagem`, `visivel`) VALUES
(1,	'ENTREGAS GRATUITAS',	'Encomendas online acima de 100 €',	'http://propostas.netgocio.pt/naturasaude/pt/entregas-e-devolucoes',	'100556_1_1993_055149_1_7647_delivery.svg',	1),
(2,	'COMPRA SEGURA',	'Vários tipos de pagamento disponíveis',	'http://propostas.netgocio.pt/naturasaude/pt/metodos-de-pagamento',	'101004_1_2779_055303_2_1807_shield.svg',	1),
(3,	'DEVOLUÇÕES',	'Não está satisfeito? Devolvemos o valor.',	'http://propostas.netgocio.pt/naturasaude/pt/entregas-e-devolucoes',	'101113_1_4625_055334_3_1306_revart.svg',	1);

DROP TABLE IF EXISTS `homepage_image_pt`;
CREATE TABLE `homepage_image_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(600) COLLATE utf8_bin NOT NULL,
  `titulo` varchar(1000) COLLATE utf8_bin NOT NULL,
  `link` varchar(1000) COLLATE utf8_bin NOT NULL,
  `imagem` varchar(1000) COLLATE utf8_bin NOT NULL,
  `visivel` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `homepage_image_pt` (`id`, `nome`, `titulo`, `link`, `imagem`, `visivel`) VALUES
(1,	'ENTREGAS GRATUITAS',	'Encomendas online acima de 20 €',	'http://propostas.netgocio.pt/naturasaude/pt/entregas-e-devolucoes',	'100556_1_1993_055149_1_7647_delivery.svg',	1),
(2,	'COMPRA SEGURA',	'Vários tipos de pagamento disponíveis',	'http://propostas.netgocio.pt/naturasaude/pt/metodos-de-pagamento',	'101004_1_2779_055303_2_1807_shield.svg',	1),
(3,	'DEVOLUÇÕES',	'Não está satisfeito? Devolvemos o valor.',	'http://www.netgocio.pt/',	'101113_1_4625_055334_3_1306_revart.svg',	1);

DROP TABLE IF EXISTS `homepage_pt`;
CREATE TABLE `homepage_pt` (
  `id` int(11) NOT NULL,
  `titulo1` text,
  `titulo_link1` text,
  `subtitulo1` varchar(255) DEFAULT NULL,
  `icone1` varchar(255) DEFAULT NULL,
  `link1` text,
  `titulo2` varchar(255) DEFAULT NULL,
  `titulo_link2` text,
  `subtitulo2` varchar(255) DEFAULT NULL,
  `icone2` varchar(255) DEFAULT NULL,
  `link2` text,
  `titulo3` varchar(255) DEFAULT NULL,
  `subtitulo3` varchar(255) DEFAULT NULL,
  `icone3` varchar(255) DEFAULT NULL,
  `link3` text,
  `imagem_news` varchar(255) DEFAULT NULL,
  `texto_news` text,
  `imagem_form` varchar(255) DEFAULT NULL,
  `texto_form` text,
  `texto_header` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `homepage_pt` (`id`, `titulo1`, `titulo_link1`, `subtitulo1`, `icone1`, `link1`, `titulo2`, `titulo_link2`, `subtitulo2`, `icone2`, `link2`, `titulo3`, `subtitulo3`, `icone3`, `link3`, `imagem_news`, `texto_news`, `imagem_form`, `texto_form`, `texto_header`) VALUES
(1,	'Portes gratuitos acima de 20&euro; | <strong>Entrega em 3-4 dias &uacute;ties</strong>',	'http://propostas.netgocio.pt/naturasaude/pt/entregas-e-devolucoes',	NULL,	'053917_1_4239_angle-left.svg',	NULL,	'',	'https://www.netgocio.pt',	NULL,	NULL,	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	'mithilchauhan@gmail.com',	'77788899'),
(2,	'ENTREGAS GRATUITAS',	NULL,	'Encomendas online acima de 100 €',	'055149_1_7647_delivery.svg',	'http://www.netgocio.pt/',	'COMPRA SEGURA',	NULL,	'Vários tipos de pagamento disponíveis',	'055303_2_1807_shield.svg',	'http://www.netgocio.pt/',	'DEVOLUÇÕES',	'Nao esta satisfeito? Devolvemos o valor.',	'055334_3_1306_revart.svg',	'http://www.netgocio.pt/',	NULL,	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `imagens_topo`;
CREATE TABLE `imagens_topo` (
  `id` int(11) NOT NULL,
  `faqs` varchar(255) DEFAULT NULL,
  `faqs_masc` tinyint(4) NOT NULL DEFAULT '0',
  `contactos` varchar(255) DEFAULT NULL,
  `contactos_masc` tinyint(4) NOT NULL DEFAULT '0',
  `noticias` varchar(255) DEFAULT NULL,
  `noticias_masc` tinyint(4) NOT NULL DEFAULT '0',
  `blog` varchar(255) DEFAULT NULL,
  `blog_masc` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `imagens_topo` (`id`, `faqs`, `faqs_masc`, `contactos`, `contactos_masc`, `noticias`, `noticias_masc`, `blog`, `blog_masc`) VALUES
(1,	'',	1,	NULL,	0,	'',	0,	'',	0);

DROP TABLE IF EXISTS `linguas`;
CREATE TABLE `linguas` (
  `id` int(11) NOT NULL,
  `sufixo` varchar(5) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `nome_para_loja` varchar(250) DEFAULT NULL,
  `nome_para_noticias` varchar(250) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `ativo` tinyint(4) DEFAULT '1',
  `consola` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `indice` (`visivel`,`ativo`,`consola`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `linguas` (`id`, `sufixo`, `nome`, `nome_para_loja`, `nome_para_noticias`, `visivel`, `ativo`, `consola`, `ordem`) VALUES
(1,	'pt',	'Português',	'loja',	'noticias',	0,	0,	0,	99),
(2,	'en',	'English',	'Store',	'news',	1,	1,	1,	99);

DROP TABLE IF EXISTS `lista_desejo`;
CREATE TABLE `lista_desejo` (
  `id` int(11) NOT NULL,
  `cliente` int(11) DEFAULT NULL,
  `session` varchar(255) DEFAULT NULL,
  `produto` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT '0.00',
  `opcoes` text,
  `ult_atualizacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`cliente`,`session`,`produto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `lista_desejo` (`id`, `cliente`, `session`, `produto`, `nome`, `preco`, `opcoes`, `ult_atualizacao`) VALUES
(0,	55,	'1614332203',	3,	'sq23',	25.00,	'a:0:{}',	'2021-02-26 09:42:32');

DROP TABLE IF EXISTS `l_caract_categorias_en`;
CREATE TABLE `l_caract_categorias_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT '0' COMMENT '0=select; 1=radio(ex: cores)',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_caract_categorias_en` (`id`, `nome`, `label`, `tipo`, `ordem`) VALUES
(1,	'Size',	'Size',	0,	99),
(2,	'Color',	'Color',	0,	99),
(3,	'packup',	'packup',	0,	99);

DROP TABLE IF EXISTS `l_caract_categorias_pt`;
CREATE TABLE `l_caract_categorias_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT '0' COMMENT '0=select; 1=radio(ex: cores)',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_caract_opcoes_en`;
CREATE TABLE `l_caract_opcoes_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `categoria` int(11) DEFAULT '0',
  `cor` varchar(10) DEFAULT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_caract_opcoes_en` (`id`, `nome`, `categoria`, `cor`, `imagem1`, `ordem`) VALUES
(4,	'8\"',	1,	NULL,	NULL,	1),
(5,	'10\"',	1,	NULL,	NULL,	2),
(6,	'12\"',	1,	NULL,	NULL,	3),
(7,	'14\"',	1,	NULL,	NULL,	4),
(8,	'16\"',	1,	NULL,	NULL,	5),
(9,	'Yellow',	2,	NULL,	NULL,	99),
(10,	'blue',	2,	NULL,	NULL,	99),
(11,	'5',	3,	NULL,	NULL,	99);

DROP TABLE IF EXISTS `l_caract_opcoes_pt`;
CREATE TABLE `l_caract_opcoes_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `categoria` int(11) DEFAULT '0',
  `cor` varchar(10) DEFAULT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_categorias_en`;
CREATE TABLE `l_categorias_en` (
  `id` int(11) NOT NULL,
  `type` int(5) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `cat_mae` int(11) DEFAULT '0',
  `nav_name` text,
  `highlight_visible` int(2) NOT NULL,
  `cate_mae_name` varchar(255) DEFAULT NULL,
  `imagem1` varchar(250) DEFAULT NULL,
  `descricao` text,
  `promocao` tinyint(4) DEFAULT '0',
  `promocao_desconto` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `franchise` int(5) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `mascara` int(11) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `cor_fundo` varchar(255) DEFAULT NULL,
  `tipo_fundo` int(11) DEFAULT NULL,
  `mostra_titulo` int(11) DEFAULT NULL,
  `cor_titulo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`cat_mae`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_categorias_en` (`id`, `type`, `nome`, `cat_mae`, `nav_name`, `highlight_visible`, `cate_mae_name`, `imagem1`, `descricao`, `promocao`, `promocao_desconto`, `ordem`, `franchise`, `visivel`, `url`, `title`, `mascara`, `description`, `keywords`, `cor_fundo`, `tipo_fundo`, `mostra_titulo`, `cor_titulo`) VALUES
(1,	0,	'Candles',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'candles',	'Candles',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(2,	0,	'Cakes',	0,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes',	'Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(3,	0,	'Eid Cakes',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'eid-cakes',	'Eid Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(4,	0,	'Wedding Cakes',	0,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'wedding-cakes',	'Wedding Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(5,	0,	'Sparklers',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'sparklers',	'Sparklers',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(6,	0,	'Ice Fountains',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'ice-fountains',	'Ice Fountains',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(7,	0,	'Party Poppers',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'party-poppers',	'Party Poppers',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(8,	0,	'Color Smokes',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'color-smokes',	'Color Smokes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(9,	0,	'Party Decoration',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'party-decoration',	'Party Decoration',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(10,	0,	'Icing Birthday Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-icing-birthday-cakes',	'Icing Birthday Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(11,	0,	'Freshly Whipped Cream Wedding Cakes',	4,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'wedding-cakes-freshly-whipped-cream-wedding-cakes',	'Freshly Whipped Cream Wedding Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(12,	0,	'Freshly Whipped Cream Birthday Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-freshly-whipped-cream-birthday-cakes',	'Freshly Whipped Cream Birthday Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(13,	0,	'Iced Wedding Cakes',	4,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'wedding-cakes-iced-wedding-cakes',	'Iced Wedding Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(14,	0,	'Other Occasions Wedding Cakes',	4,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'wedding-cakes-other-occasions-wedding-cakes',	'Other Occasions Wedding Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(15,	0,	'Fruit Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-fruit-cakes',	'Fruit Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(16,	0,	'Round Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-round-cakes',	'Round Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(17,	0,	'Click & Collect',	2,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-click-amp',	'Click & Collect',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(19,	0,	'Square Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-square-cakes',	'Square Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(20,	0,	'Chocolate Deco Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-chocolate-deco-cakes',	'Chocolate Deco Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(21,	0,	'Slab Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-slab-cakes',	'Slab Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(22,	0,	'Heart Cakes',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-heart-cakes',	'Heart Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(23,	0,	'Rose Pipping',	2,	NULL,	0,	NULL,	NULL,	NULL,	0,	NULL,	99,	NULL,	1,	'cakes-rose-pipping',	'Rose Pipping',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(24,	0,	'Flower Cakes',	2,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-flower-cakes',	'Flower Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(25,	0,	'Mehndi',	2,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-mehndi',	'Mehndi',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(26,	0,	'Number Cakes',	2,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-number-cakes',	'Number Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(27,	0,	'Photo Cakes',	2,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-photo-cakes',	'Photo Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(28,	0,	'Rose Pipping',	2,	'Rose Pipping Cakes',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-rose-pipping-28',	'Rose Pipping',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(29,	0,	'Icing Birthday',	29,	'',	0,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-icing-birthday',	'Icing Birthday',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(30,	0,	'Party Accessories',	0,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'party-accessories',	'Party Accessories',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(31,	0,	'Slab Cakes',	2,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-slab-cakes-31',	'Slab Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(32,	0,	'Drip Cakes',	2,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'cakes-drip-cakes',	'Drip Cakes',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(33,	0,	'Latex Balloons',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'party-accessories-latex-balloons',	'Latex Balloons',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(34,	0,	'Foil Balloons',	30,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'party-accessories-foil-balloons',	'Foil Balloons',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL),
(35,	0,	'Cake Toppers',	35,	'',	1,	NULL,	NULL,	'',	0,	NULL,	99,	NULL,	1,	'party-accessories-cake-toppers',	'Cake Toppers',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `l_categorias_pt`;
CREATE TABLE `l_categorias_pt` (
  `id` int(11) NOT NULL,
  `type` int(5) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `cat_mae` int(11) DEFAULT '0',
  `cate_mae_name` varchar(250) DEFAULT NULL,
  `imagem1` varchar(250) DEFAULT NULL,
  `descricao` text,
  `promocao` tinyint(4) DEFAULT '0',
  `promocao_desconto` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `mascara` int(11) DEFAULT NULL,
  `keywords` text,
  `cor_fundo` varchar(255) DEFAULT NULL,
  `tipo_fundo` int(11) DEFAULT NULL,
  `mostra_titulo` int(11) DEFAULT NULL,
  `cor_titulo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`cat_mae`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_filt_categorias_en`;
CREATE TABLE `l_filt_categorias_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT '1' COMMENT '1 - Checkbox; 2 - Radio; 3 - Select',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_filt_categorias_pt`;
CREATE TABLE `l_filt_categorias_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT '1' COMMENT '1 - Checkbox; 2 - Radio; 3 - Select',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`tipo`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_filt_opcoes_en`;
CREATE TABLE `l_filt_opcoes_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) DEFAULT NULL,
  `categoria` int(11) DEFAULT '0',
  `cor` varchar(50) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_filt_opcoes_pt`;
CREATE TABLE `l_filt_opcoes_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `categoria` int(11) DEFAULT '0',
  `cor` varchar(50) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`categoria`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_marcas_en`;
CREATE TABLE `l_marcas_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `descricao` text,
  `imagem1` varchar(250) DEFAULT NULL,
  `imagem2` varchar(350) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `keywords` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_marcas_pt`;
CREATE TABLE `l_marcas_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `descricao` text,
  `imagem1` varchar(250) DEFAULT NULL,
  `imagem2` varchar(350) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `keywords` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_caract`;
CREATE TABLE `l_pecas_caract` (
  `id` bigint(20) NOT NULL,
  `id_peca` int(11) DEFAULT NULL,
  `id_caract` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_peca`,`id_caract`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_categorias`;
CREATE TABLE `l_pecas_categorias` (
  `id` bigint(20) NOT NULL,
  `id_peca` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_peca`,`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_pecas_categorias` (`id`, `id_peca`, `id_categoria`) VALUES
(1,	1,	2),
(2,	1,	2),
(3,	1,	15),
(5,	1,	15),
(4,	1,	16),
(6,	1,	16),
(7,	3,	2),
(8,	3,	2),
(9,	3,	15),
(11,	3,	15),
(10,	3,	19),
(12,	3,	19),
(13,	4,	2),
(14,	4,	2),
(15,	4,	2),
(19,	4,	16),
(23,	4,	16),
(27,	4,	16),
(17,	4,	17),
(21,	4,	17),
(25,	4,	17),
(18,	4,	18),
(22,	4,	18),
(26,	4,	18),
(16,	4,	20),
(20,	4,	20),
(24,	4,	20),
(28,	5,	2),
(29,	5,	2),
(31,	5,	19),
(33,	5,	19),
(30,	5,	20),
(32,	5,	20),
(34,	6,	2),
(35,	6,	2),
(36,	6,	2),
(38,	6,	15),
(41,	6,	15),
(44,	6,	15),
(39,	6,	19),
(42,	6,	19),
(45,	6,	19),
(37,	6,	20),
(40,	6,	20),
(43,	6,	20),
(46,	7,	2),
(47,	7,	2),
(48,	7,	15),
(50,	7,	15),
(49,	7,	21),
(51,	7,	21),
(52,	8,	2),
(53,	8,	2),
(54,	8,	22),
(56,	8,	22),
(55,	8,	23),
(57,	8,	23),
(391,	9,	1),
(59,	10,	1),
(60,	11,	1),
(61,	12,	1),
(62,	13,	1),
(63,	14,	2),
(64,	14,	10),
(65,	15,	2),
(66,	15,	10),
(67,	16,	2),
(68,	16,	10),
(69,	17,	2),
(70,	17,	10),
(71,	18,	2),
(72,	18,	10),
(73,	19,	2),
(74,	19,	10),
(366,	20,	2),
(367,	20,	10),
(77,	21,	2),
(78,	21,	10),
(79,	22,	3),
(80,	23,	1),
(81,	24,	1),
(82,	25,	4),
(83,	25,	11),
(84,	26,	2),
(85,	26,	12),
(86,	27,	4),
(87,	27,	13),
(88,	28,	2),
(89,	28,	12),
(90,	29,	2),
(91,	29,	12),
(377,	30,	2),
(379,	30,	12),
(378,	30,	17),
(380,	30,	19),
(94,	31,	2),
(95,	31,	12),
(96,	32,	4),
(97,	32,	11),
(98,	33,	4),
(99,	33,	11),
(100,	34,	4),
(101,	34,	11),
(102,	35,	4),
(103,	35,	11),
(104,	36,	4),
(105,	36,	11),
(106,	37,	4),
(107,	37,	11),
(108,	38,	4),
(109,	38,	11),
(110,	39,	4),
(111,	39,	11),
(112,	40,	4),
(113,	40,	11),
(114,	41,	4),
(115,	41,	11),
(116,	42,	4),
(117,	42,	11),
(118,	43,	4),
(119,	43,	11),
(120,	44,	4),
(121,	44,	11),
(122,	45,	4),
(123,	45,	11),
(124,	46,	4),
(125,	46,	11),
(126,	47,	4),
(127,	47,	11),
(128,	48,	4),
(129,	48,	11),
(130,	49,	4),
(131,	49,	11),
(132,	50,	4),
(133,	50,	11),
(134,	51,	4),
(135,	51,	11),
(136,	52,	4),
(137,	52,	11),
(138,	53,	4),
(139,	53,	11),
(140,	54,	4),
(141,	54,	11),
(142,	55,	4),
(143,	55,	11),
(144,	56,	4),
(145,	56,	11),
(146,	57,	4),
(147,	57,	11),
(148,	58,	4),
(149,	58,	11),
(150,	59,	4),
(151,	59,	11),
(152,	60,	4),
(153,	60,	11),
(154,	61,	4),
(155,	61,	11),
(156,	62,	4),
(157,	62,	11),
(158,	63,	4),
(159,	63,	11),
(160,	64,	4),
(161,	64,	11),
(162,	65,	4),
(163,	65,	11),
(164,	66,	4),
(165,	66,	11),
(166,	67,	4),
(167,	67,	11),
(168,	68,	4),
(169,	68,	11),
(170,	69,	4),
(171,	69,	11),
(172,	70,	4),
(173,	70,	11),
(174,	71,	4),
(175,	71,	13),
(176,	72,	4),
(177,	72,	11),
(178,	73,	4),
(179,	73,	13),
(180,	74,	4),
(181,	74,	13),
(182,	75,	4),
(183,	75,	13),
(184,	76,	4),
(185,	76,	13),
(186,	77,	4),
(187,	77,	13),
(188,	78,	4),
(189,	78,	13),
(190,	79,	4),
(191,	79,	13),
(192,	80,	4),
(193,	80,	13),
(194,	81,	4),
(195,	81,	13),
(196,	82,	4),
(197,	82,	13),
(198,	83,	4),
(199,	83,	13),
(200,	84,	4),
(201,	84,	13),
(202,	85,	4),
(203,	85,	13),
(204,	86,	4),
(205,	86,	13),
(206,	87,	4),
(207,	87,	13),
(208,	88,	4),
(209,	88,	13),
(210,	89,	4),
(211,	89,	13),
(212,	90,	4),
(213,	90,	13),
(214,	91,	4),
(215,	91,	13),
(216,	92,	4),
(217,	92,	13),
(218,	93,	4),
(219,	93,	13),
(220,	94,	4),
(221,	94,	13),
(222,	95,	4),
(223,	95,	13),
(224,	96,	4),
(225,	96,	13),
(226,	97,	4),
(227,	97,	13),
(228,	98,	4),
(229,	98,	13),
(230,	99,	4),
(231,	99,	13),
(232,	100,	4),
(233,	100,	13),
(234,	101,	4),
(235,	101,	13),
(236,	102,	4),
(237,	102,	13),
(238,	103,	4),
(239,	103,	14),
(240,	104,	4),
(241,	104,	14),
(242,	105,	4),
(243,	105,	13),
(244,	106,	2),
(245,	106,	12),
(246,	107,	2),
(247,	107,	12),
(248,	108,	2),
(249,	108,	12),
(373,	109,	2),
(375,	109,	15),
(374,	109,	17),
(376,	109,	19),
(265,	110,	2),
(266,	110,	2),
(268,	110,	16),
(270,	110,	16),
(267,	110,	20),
(269,	110,	20),
(271,	111,	2),
(272,	111,	2),
(273,	111,	15),
(275,	111,	15),
(274,	111,	19),
(276,	111,	19),
(277,	112,	2),
(278,	112,	2),
(280,	112,	19),
(282,	112,	19),
(279,	112,	20),
(281,	112,	20),
(368,	113,	2),
(370,	113,	16),
(369,	113,	20),
(289,	114,	2),
(290,	114,	2),
(291,	114,	15),
(293,	114,	15),
(292,	114,	16),
(294,	114,	16),
(295,	116,	2),
(296,	116,	2),
(298,	116,	16),
(300,	116,	16),
(297,	116,	20),
(299,	116,	20),
(301,	117,	2),
(302,	117,	2),
(304,	117,	19),
(306,	117,	19),
(303,	117,	20),
(305,	117,	20),
(307,	118,	2),
(308,	118,	2),
(309,	118,	15),
(311,	118,	15),
(310,	118,	16),
(312,	118,	16),
(388,	119,	2),
(389,	119,	15),
(390,	119,	19),
(319,	120,	5),
(320,	121,	5),
(321,	122,	5),
(322,	123,	5),
(323,	124,	5),
(324,	125,	5),
(325,	126,	6),
(327,	127,	5),
(326,	127,	6),
(329,	128,	5),
(328,	128,	6),
(330,	129,	6),
(331,	130,	6),
(332,	131,	6),
(333,	132,	5),
(334,	133,	1),
(335,	134,	1),
(336,	134,	6),
(337,	135,	5),
(338,	136,	1),
(339,	136,	5),
(340,	137,	1),
(341,	137,	5),
(342,	138,	5),
(343,	139,	1),
(344,	139,	6),
(345,	140,	7),
(346,	141,	7),
(347,	142,	5),
(348,	143,	7),
(349,	144,	8),
(350,	145,	8),
(351,	146,	8),
(352,	147,	8),
(353,	148,	8),
(354,	149,	1),
(355,	150,	1),
(356,	151,	1),
(357,	152,	1),
(358,	153,	9),
(359,	154,	1),
(360,	155,	1),
(361,	156,	6),
(362,	157,	7),
(363,	158,	5),
(364,	159,	1),
(365,	160,	9),
(386,	161,	2),
(387,	161,	20),
(381,	162,	2),
(385,	162,	16),
(382,	162,	17),
(383,	162,	23),
(384,	162,	28);

DROP TABLE IF EXISTS `l_pecas_desconto`;
CREATE TABLE `l_pecas_desconto` (
  `id` bigint(20) NOT NULL,
  `id_peca` int(11) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  `desconto` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_peca`,`min`,`max`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_en`;
CREATE TABLE `l_pecas_en` (
  `id` int(11) NOT NULL,
  `ref` varchar(30) DEFAULT NULL,
  `cod_barras` text,
  `nome` varchar(250) DEFAULT NULL,
  `categoria` int(11) DEFAULT '0',
  `marca` int(11) DEFAULT NULL,
  `on_order` varchar(10) NOT NULL,
  `regiao` int(11) DEFAULT NULL,
  `descricao` text,
  `short_descricao` text,
  `caracteristicas` text,
  `informacoes` text,
  `composition_per_capsule` text,
  `modo_de_tomar` text,
  `detalhes_advertencias` text,
  `filtro_1` text,
  `filtro_2` text,
  `filtro_3` text,
  `download` varchar(450) DEFAULT NULL,
  `video` varchar(500) DEFAULT NULL,
  `info` varchar(250) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT '0.00',
  `preco_old` decimal(10,2) DEFAULT '0.00' COMMENT 'Apenas usado nas Atualização de Preço',
  `preco_forn` decimal(10,2) DEFAULT '0.00',
  `preco_ant` decimal(10,2) DEFAULT '0.00',
  `markup` varchar(50) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `qr_code` varchar(500) DEFAULT NULL,
  `custom_variation` int(11) DEFAULT NULL,
  `custom_variation_options` varchar(250) DEFAULT NULL,
  `imagem1` varchar(250) DEFAULT NULL,
  `imagem2` varchar(250) DEFAULT NULL,
  `imagem3` varchar(250) DEFAULT NULL,
  `imagem4` varchar(250) DEFAULT NULL,
  `novidade` tinyint(4) DEFAULT '0',
  `destaque` tinyint(4) DEFAULT '0',
  `mega_destaque` tinyint(4) DEFAULT '0',
  `mega_destaque_tit` varchar(150) DEFAULT NULL,
  `mega_destaque_txt` text,
  `promocao` tinyint(4) DEFAULT '0',
  `promocao_ordem` int(11) DEFAULT NULL,
  `promocao_desconto` decimal(10,2) DEFAULT NULL,
  `promocao_datai` date DEFAULT NULL,
  `promocao_dataf` date DEFAULT NULL,
  `promocao_pagina` int(11) DEFAULT '0',
  `promocao_titulo` varchar(25) DEFAULT NULL,
  `promocao_texto` text,
  `saldo` tinyint(4) DEFAULT '0',
  `iva` int(11) DEFAULT '23',
  `quantidades_descricao` text,
  `favoritos` int(11) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `contagem_vendas` int(11) DEFAULT '0',
  `peso` decimal(10,3) DEFAULT NULL,
  `stock` int(11) DEFAULT '0',
  `prepare` varchar(5) DEFAULT NULL,
  `enquiry_type` int(5) NOT NULL,
  `message_text` int(5) DEFAULT NULL,
  `role_customer` varchar(255) DEFAULT NULL,
  `reguler_price_customer` varchar(255) DEFAULT NULL,
  `selling_price_customer` varchar(255) DEFAULT NULL,
  `product_qulity_customer` varchar(255) DEFAULT NULL,
  `role_franchise` varchar(255) DEFAULT NULL,
  `reguler_price_franchise` varchar(255) DEFAULT NULL,
  `selling_price_franchise` varchar(255) DEFAULT NULL,
  `product_qulity_franchise` varchar(255) DEFAULT NULL,
  `guia_tamanhos` int(11) DEFAULT '0',
  `nao_limitar_stock` tinyint(4) DEFAULT '0',
  `descricao_stock` varchar(150) DEFAULT NULL,
  `maxstock` varchar(8) NOT NULL,
  `tem_conjunto` tinyint(4) DEFAULT '0',
  `url` varchar(150) DEFAULT NULL,
  `title` text,
  `description` text,
  `keywords` text,
  `pro_id` int(11) DEFAULT NULL,
  `temp_image` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`ref`,`categoria`,`marca`,`ordem`,`visivel`,`novidade`,`destaque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_pecas_en` (`id`, `ref`, `cod_barras`, `nome`, `categoria`, `marca`, `on_order`, `regiao`, `descricao`, `short_descricao`, `caracteristicas`, `informacoes`, `composition_per_capsule`, `modo_de_tomar`, `detalhes_advertencias`, `filtro_1`, `filtro_2`, `filtro_3`, `download`, `video`, `info`, `preco`, `preco_old`, `preco_forn`, `preco_ant`, `markup`, `cost`, `qr_code`, `custom_variation`, `custom_variation_options`, `imagem1`, `imagem2`, `imagem3`, `imagem4`, `novidade`, `destaque`, `mega_destaque`, `mega_destaque_tit`, `mega_destaque_txt`, `promocao`, `promocao_ordem`, `promocao_desconto`, `promocao_datai`, `promocao_dataf`, `promocao_pagina`, `promocao_titulo`, `promocao_texto`, `saldo`, `iva`, `quantidades_descricao`, `favoritos`, `ordem`, `visivel`, `contagem_vendas`, `peso`, `stock`, `prepare`, `enquiry_type`, `message_text`, `role_customer`, `reguler_price_customer`, `selling_price_customer`, `product_qulity_customer`, `role_franchise`, `reguler_price_franchise`, `selling_price_franchise`, `product_qulity_franchise`, `guia_tamanhos`, `nao_limitar_stock`, `descricao_stock`, `maxstock`, `tem_conjunto`, `url`, `title`, `description`, `keywords`, `pro_id`, `temp_image`) VALUES
(1,	'',	NULL,	'RND1',	0,	NULL,	'',	NULL,	'FRESH CREAM CAKE TOPPED WITH FRESH FRUIT WITH DOUBLE COLOUR PIPPING FINISHED AROUND THE EDGE',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	15.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'18.jpg',	'18.jpg',	'18.jpg',	'18.jpg',	0,	1,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'rnd1',	NULL,	NULL,	NULL,	111487,	'https://bbakery.co.uk/wp-content/uploads/2020/04/18.jpg|https://bbakery.co.uk/wp-content/uploads/2020/04/1.jpg'),
(2,	'',	NULL,	'SQ 14',	0,	NULL,	'',	NULL,	'Fresh Cream Cake',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'8.jpg',	'8.jpg',	'8.jpg',	'8.jpg',	1,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'sq-14',	NULL,	NULL,	NULL,	111630,	'https://bbakery.co.uk/wp-content/uploads/2020/04/8.jpg'),
(3,	'',	NULL,	'sq23',	0,	NULL,	'0',	NULL,	'Freshly whipped cream cake',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	'',	'0',	NULL,	NULL,	NULL,	'8.jpg',	'8.jpg',	'8.jpg',	'8.jpg',	1,	1,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	1,	99,	1,	34,	NULL,	450,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	'',	'',	0,	'sq23',	NULL,	NULL,	NULL,	111649,	'https://bbakery.co.uk/wp-content/uploads/2020/04/8.jpg'),
(4,	'',	NULL,	'RND 2',	0,	NULL,	'',	NULL,	'Fresh Cream Cake\r\n\r\nTop Decorated With Strawberries And Mixed Chocolate Curls\r\n\r\nChoice Of Different Side Decoration\r\n\r\nPersonalised Message With Your Choice Of Colour',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	20.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'7.jpg',	'7.jpg',	'7.jpg',	'7.jpg',	1,	1,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'rnd-2',	NULL,	NULL,	NULL,	111564,	'https://bbakery.co.uk/wp-content/uploads/2020/04/7.jpg'),
(5,	'',	NULL,	'SQ23',	0,	NULL,	'',	NULL,	'Fresh Cream Cake\r\n\r\nTopped With Chocolate Curls And Flake Bars\r\n\r\nChoice Of Side Decorations\r\n\r\nPersonalised Message With A Choice Of Different Colours',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	'',	'',	NULL,	NULL,	NULL,	'15.jpg',	'15.jpg',	'15.jpg',	'15.jpg',	1,	1,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'sq23-5',	NULL,	NULL,	NULL,	111579,	'https://bbakery.co.uk/wp-content/uploads/2020/04/15.jpg'),
(6,	'',	NULL,	'sq45',	0,	NULL,	'',	NULL,	'Fresh Cream Cake\r\n\r\nChoice Of Sponge\r\n\r\nChoice Of Side Decoration\r\n\r\nChoice Of Pipping Colour\r\n\r\nChoice Of Writing Colour',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'19.jpg',	'19.jpg',	'19.jpg',	'19.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'sq45',	NULL,	NULL,	NULL,	111592,	'https://bbakery.co.uk/wp-content/uploads/2020/04/19.jpg'),
(7,	'',	NULL,	'SLB33',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	49.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'9.jpg',	'9.jpg',	'9.jpg',	'9.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'slb33',	NULL,	NULL,	NULL,	111602,	'https://bbakery.co.uk/wp-content/uploads/2020/04/9.jpg'),
(8,	'',	NULL,	'HRTRS01',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	35.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'10.jpg',	'10.jpg',	'10.jpg',	'10.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'hrtrs01',	NULL,	NULL,	NULL,	111938,	'https://bbakery.co.uk/wp-content/uploads/2020/06/10.jpg'),
(9,	'whiteglitcandles',	NULL,	'White Glitter Spiral Candles (Pack of 10)',	0,	NULL,	'',	NULL,	'White Glitter Spiral Candles (Pack of 10)',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.50,	0.00,	NULL,	1.50,	'',	NULL,	NULL,	NULL,	NULL,	'products-37598-1-1.jpg',	'products-37598-1-1.jpg',	'products-37598-1-1.jpg',	'products-37598-1-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	1,	0,	0.000,	300,	'',	1,	NULL,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'',	'',	0,	'white-glitter-spiral-candles-pack-of-10',	NULL,	NULL,	NULL,	109630,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-37598-1-1.jpg'),
(10,	'pinkglitter1',	NULL,	'Pink Glitter Candles (Pack of 12)',	0,	NULL,	'',	NULL,	'Pink Glitter Candles (Pack of 12)',	'Pink Glitter Candles (Pack of 12)',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.50,	0.00,	0.00,	1.50,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-37597-1-1.jpg',	'products-37597-1-1.jpg',	'products-37597-1-1.jpg',	'products-37597-1-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'pink-glitter-candles-pack-of-12',	NULL,	NULL,	NULL,	109631,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-37597-1-1.jpg'),
(11,	'goldspiralcand',	NULL,	'Gold Spiral Candles (Pack of 10)',	0,	NULL,	'',	NULL,	'Gold Spiral Candles (Pack of 10)',	'Gold Spiral Candles (Pack of 10)',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.50,	0.00,	0.00,	1.50,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-37599-1-1.jpg',	'products-37599-1-1.jpg',	'products-37599-1-1.jpg',	'products-37599-1-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'gold-spiral-candles-pack-of-10',	NULL,	NULL,	NULL,	109632,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-37599-1-1.jpg'),
(12,	'silverspiralcand',	NULL,	'Silver Spiral Candles (Pack of 10)',	0,	NULL,	'',	NULL,	'Silver Spiral Candles (Pack of 10)',	'Silver Spiral Candles (Pack of 10)',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.50,	0.00,	0.00,	1.50,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-_0.30-1.jpg',	'products-_0.30-1.jpg',	'products-_0.30-1.jpg',	'products-_0.30-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'silver-spiral-candles-pack-of-10',	NULL,	NULL,	NULL,	109633,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-_0.30-1.jpg'),
(13,	'pearlblucand',	NULL,	'Pearl Blue Twist Candles (Pack of 12)',	0,	NULL,	'',	NULL,	'Pearl Blue Twist Candles (Pack of 12)',	'Pearl Blue Twist Candles (Pack of 12)',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.50,	0.00,	0.00,	1.50,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-37596-1-1.jpg',	'products-37596-1-1.jpg',	'products-37596-1-1.jpg',	'products-37596-1-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'pearl-blue-twist-candles-pack-of-12',	NULL,	NULL,	NULL,	109634,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-37596-1-1.jpg'),
(14,	'B120',	NULL,	'Figure Cake - B120',	0,	NULL,	'',	NULL,	'Victorian style sponge with multiple layers of our flavour some Rich Better Cream and a layer of delicious mixed fruit Jam.\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\nIdeal for Birthdays or any other special occasion you may have\r\nMore About this Cake\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary slightly\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nRequires 4 days notice',	'Victorian style sponge with multiple layers of our flavour some Rich Better Cream and a layer of delicious mixed fruit Jam.\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\nIdeal for Birthdays or any other special occasion you may have\r\nMore About this Cake\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary slightly\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nRequires 4 days notice',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	59.00,	0.00,	0.00,	59.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'B120.png',	'B120.png',	'B120.png',	'B120.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'5',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'figure-cake-b120',	NULL,	NULL,	NULL,	109636,	'https://bbakery.co.uk/wp-content/uploads/2019/08/B120.png'),
(15,	'B15',	NULL,	'Marvel x DC - 2 Tier Cake B15',	0,	NULL,	'',	NULL,	'Marvel x DC - 2 Tier Cake B15\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 4 days notice',	'Marvel x DC - 2 Tier Cake\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 4 days notice',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	80.00,	0.00,	0.00,	80.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'B15.jpg',	'B15.jpg',	'B15.jpg',	'B15.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'5',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'marvel-x-dc-2-tier-cake-b15',	NULL,	NULL,	NULL,	109638,	'https://bbakery.co.uk/wp-content/uploads/2019/08/B15.jpg'),
(16,	'architect-cake-b170-8',	NULL,	'Architect Cake - B170 8',	0,	NULL,	'',	NULL,	'&nbsp;\r\n\r\nArchitect Cake\r\n\r\n8 inch Cake\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 3 days notice',	'Architect Cake\r\n\r\n8 inch Cake\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 3 days notice',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	79.00,	0.00,	0.00,	79.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'B170.png',	'B170.png',	'B170.png',	'B170.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'5',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'architect-cake-b170-8',	NULL,	NULL,	NULL,	109639,	'https://bbakery.co.uk/wp-content/uploads/2019/08/B170.png'),
(17,	'blocks-name-cake-b171-6-letter',	NULL,	'Blocks Name Cake - B171 ( 6 Letters ONLY )',	0,	NULL,	'',	NULL,	'<img class=alignnone size-medium wp-image-89903 src=https://bbakery.co.uk/wp-content/uploads/2018/12/B171-300x212.png alt= width=300 height=212 />\r\n\r\nBlocks Name Cake - B171 ( 6 Letters ONLY )\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 5 days notice',	'Blocks Name Cake - B171 ( 6 Letters ONLY )\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 5 days notice',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	175.00,	0.00,	0.00,	175.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'B171.png',	'B171.png',	'B171.png',	'B171.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'5',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'blocks-name-cake-b171-6-letters-only-',	NULL,	NULL,	NULL,	109640,	'https://bbakery.co.uk/wp-content/uploads/2019/08/B171.png'),
(18,	'toy-story-cake-b172',	NULL,	'Toy Story Cake - B172',	0,	NULL,	'',	NULL,	'Toy Story Cake - B172\r\nCake Size 2 Tier : 12 Base + 8 Top\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 5 days notice',	'Toy Story Cake - B172\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 5 days notice',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	199.00,	0.00,	0.00,	199.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'B172.png',	'B172.png',	'B172.png',	'B172.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'5',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'toy-story-cake-b172',	NULL,	NULL,	NULL,	109641,	'https://bbakery.co.uk/wp-content/uploads/2019/08/B172.png'),
(19,	'bmw-logo-cake-b119',	NULL,	'BMW Logo Cake - B119',	0,	NULL,	'',	NULL,	'<img class=size-medium wp-image-89949 src=https://bbakery.co.uk/wp-content/uploads/2018/12/B119-300x200.png alt= width=300 height=200 />\r\n\r\nBMW Logo Cake - B119\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 4 days notice',	'BMW Logo Cake - B119\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 4 days notice',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	55.00,	0.00,	0.00,	55.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'B119.png',	'B119.png',	'B119.png',	'B119.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'5',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'bmw-logo-cake-b119',	NULL,	NULL,	NULL,	109642,	'https://bbakery.co.uk/wp-content/uploads/2019/08/B119.png'),
(20,	'B170-apple',	NULL,	'Apple Phone Cake 8 inches - ISP33',	0,	NULL,	'',	NULL,	'8 Apple Logo Victorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam. All our Icing Cakes are hand-made and beautifully crafted to the finest detail. Ideal for Birthdays or any other special occasion you may have Icing cake Inside Delicious vanilla Flavoured Better cream Coloured cream may stain Colour &amp; design may vary suitable to consume within 3 days of collection All our cakes can have a personalised message requires 4 days notice',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	49.00,	0.00,	NULL,	49.00,	'',	NULL,	NULL,	NULL,	NULL,	'ISP33.png',	'ISP33.png',	'ISP33.png',	'ISP33.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	1,	0,	200.000,	300,	'5',	1,	NULL,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'2',	'2',	0,	'apple-phone-cake-8-inches-isp33',	NULL,	NULL,	NULL,	109643,	'https://bbakery.co.uk/wp-content/uploads/2019/08/ISP33.png'),
(21,	'channel-gift-box-cake-b122',	NULL,	'Channel Gift Box Cake - B122',	0,	NULL,	'',	NULL,	'<img class=size-medium wp-image-89954 src=https://bbakery.co.uk/wp-content/uploads/2018/12/B122-300x200.png alt=Channel Gift Box Cake - B122 width=300 height=200 />\r\n\r\nChannel Gift Box Cake - B122\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 4 days notice',	'<img class=size-medium wp-image-89954 src=https://bbakery.co.uk/wp-content/uploads/2018/12/B122-300x200.png alt=Channel Gift Box Cake - B122 width=300 height=200 />\r\n\r\nChannel Gift Box Cake - B122\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Better Cream and a layer of delicious mixed fruit Jam.\r\n\r\nAll our Icing Cakes are hand-made and beautifully crafted to the finest detail.\r\n\r\nIdeal for Birthdays or any other special occasion you may have\r\n\r\nIcing cake\r\nInside Delicious vanilla Flavoured Better cream\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nsuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nrequires 4 days notice',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	55.00,	0.00,	0.00,	55.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'B122.png',	'B122.png',	'B122.png',	'B122.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'5',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'channel-gift-box-cake-b122',	NULL,	NULL,	NULL,	109644,	'https://bbakery.co.uk/wp-content/uploads/2019/08/B122.png'),
(22,	'12-square-cake',	NULL,	'12 Square Cake &acirc;&#128;&#147; Eid Special',	0,	NULL,	'',	NULL,	'12 Square Cake with Victorian sponge and vanilla flavour.',	'12 Square Cake with the Victorian sponge and vanilla flavour.',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	35.00,	0.00,	0.00,	35.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'01eid.jpg',	'01eid.jpg',	'01eid.jpg',	'01eid.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	299,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'12-square-cake-a-eid-special',	NULL,	NULL,	NULL,	109683,	'https://bbakery.co.uk/wp-content/uploads/2019/08/01eid.jpg'),
(23,	'fountaincandlesgold',	NULL,	'Fountain Candles Gold (Pack of 2)',	0,	NULL,	'',	NULL,	'Fountain Candles Gold (Pack of 2)\r\nThese fountain candles produce little smoke, little odour and little or no residue after burning. Burning time is approximately 60 seconds.',	'Fountain Candles Gold (Pack of 2 or 3)\r\nThese fountain candles produce little smoke, little odour and little or no residue after burning. Burning time is approximately 60 seconds.',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-m23034gld-1.jpg',	'products-m23034gld-1.jpg',	'products-m23034gld-1.jpg',	'products-m23034gld-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fountain-candles-gold-pack-of-2',	NULL,	NULL,	NULL,	109695,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-m23034gld-1.jpg'),
(24,	'fountaincandlessilver',	NULL,	'Fountain Candles Silver (Pack of 2)',	0,	NULL,	'',	NULL,	'Fountain Candles Silver (Pack of 2)\r\nThese fountain candles produce little smoke, little odour and little or no residue after burning. Burning time is approximately 60 seconds.',	'Fountain Candles Silver (Pack of 2 or 3)\r\nThese fountain candles produce little smoke, little odour and little or no residue after burning. Burning time is approximately 60 seconds.',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-m23034sil-1.jpg',	'products-m23034sil-1.jpg',	'products-m23034sil-1.jpg',	'products-m23034sil-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	300,	'',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fountain-candles-silver-pack-of-2',	NULL,	NULL,	NULL,	109698,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-m23034sil-1.jpg'),
(25,	'W98',	NULL,	'2 Tier Heart Tower Cake code W98',	0,	NULL,	'',	NULL,	'A Fresh Cream Heart Cake 2 Tier Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Step Stand Style cake',	'A Fresh Cream Heart Cake 2 Tier Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Step Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	200.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w98-1.jpg',	'products-w98-1.jpg',	'products-w98-1.jpg',	'products-w98-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-heart-tower-cake-code-w98',	NULL,	NULL,	NULL,	109728,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w98-1.jpg'),
(26,	'chocolate-covered-fresh-cream-',	NULL,	'Chocolate Covered Freshly Whipped Cream Cake (B29)',	0,	NULL,	'',	NULL,	'<p style=margin-left: -49.65pt;>Victorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have</p>\r\n<p style=margin-left: -49.65pt;>More About this Cake</p>\r\n<p style=margin: 5.0pt -66.1pt 5.0pt -49.65pt;>Fresh cream\r\nSides are covered with finest quality 100*1000 Coloured Sprinkles\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary</p>',	'<p style=margin-left: -49.65pt;>Victorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have</p>\r\n<p style=margin-left: -49.65pt;>More About this Cake</p>\r\n<p style=margin: 5.0pt -66.1pt 5.0pt -49.65pt;>Fresh cream\r\nSides are covered with finest quality 100*1000 Coloured Sprinkles\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary</p>',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-b29-1.jpg',	'products-b29-1.jpg',	'products-b29-1.jpg',	'products-b29-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'chocolate-covered-freshly-whipped-cream-cake-b29',	NULL,	NULL,	NULL,	109734,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-b29-1.jpg'),
(27,	'W41',	NULL,	'2 Tier Icing Cake Code W41',	0,	NULL,	'',	NULL,	'A Icing Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Icing Stacked Style cake',	'A Icing Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	215.00,	0.00,	0.00,	0.00,	NULL,	NULL,	'1614614829.png',	NULL,	NULL,	'products-img_7211-1.png',	'products-img_7211-1.png',	'products-img_7211-1.png',	'products-img_7211-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-icing-cake-code-w41',	NULL,	NULL,	NULL,	109735,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7211-1.png'),
(28,	'chocolate-covered-fresh-cream-',	NULL,	'Chocolate Covered Freshly Whipped Cream Cake (B39)',	0,	NULL,	'',	NULL,	'&nbsp;\r\n\r\n&nbsp;\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have\r\n\r\nMore About this Cake\r\n\r\nFresh cream\r\nSides are covered with finest quality chocolate Shavings\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary\r\n\r\n&nbsp;',	'&nbsp;\r\n\r\n&nbsp;\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have\r\n\r\nMore About this Cake\r\n\r\nFresh cream\r\nSides are covered with finest quality chocolate Shavings\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary\r\n\r\n&nbsp;',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-b39_grande-1.jpg',	'products-b39_grande-1.jpg',	'products-b39_grande-1.jpg',	'products-b39_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'chocolate-covered-freshly-whipped-cream-cake-b39',	NULL,	NULL,	NULL,	109747,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-b39_grande-1.jpg'),
(29,	'mixed-chocolate-decorated-cake',	NULL,	'Mixed Chocolate Decorated Cake B36',	0,	NULL,	'',	NULL,	'Victorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have\r\nMore About this Cake\r\nFresh cream\r\nSides are covered with finest quality chocolate Shavings\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary',	'&nbsp;\r\n\r\nVictorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have\r\nMore About this Cake\r\nFresh cream\r\nSides are covered with finest quality chocolate Shavings\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-b36_grande-1.jpg',	'products-b36_grande-1.jpg',	'products-b36_grande-1.jpg',	'products-b36_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	2,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'mixed-chocolate-decorated-cake-b36',	NULL,	NULL,	NULL,	109758,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-b36_grande-1.jpg'),
(30,	'B33',	NULL,	'Simple Plain Freshcream Cake B33',	0,	NULL,	'',	NULL,	'Victorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have More About this Cake Fresh cream Plain Sides &amp; Top ( Only Your Personal Message on this Cake ) Suitable to consume within 3 days of collection All our cakes can have a personalised message',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	NULL,	0.00,	'',	NULL,	NULL,	NULL,	NULL,	'products-b33_grande-1.jpg',	'products-b33_grande-1.jpg',	'products-b33_grande-1.jpg',	'products-b33_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	1,	0,	0.000,	0,	'1',	0,	NULL,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'',	'',	0,	'simple-plain-freshcream-cake-b33',	NULL,	NULL,	NULL,	109760,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-b33_grande-1.jpg'),
(31,	'B38',	NULL,	'Photo Cake - Personalise B38',	0,	NULL,	'',	NULL,	'Victorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have\r\nMore About this Cake\r\nFresh cream\r\nSides are covered with finest quality chocolate Shavings\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary',	'Victorian style sponge with multiple layers of our flavoursome Rich Freshly whipped cream\r\nAll our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream. Ideal for Birthdays , Anniversaries or any other special occasions you may have\r\nMore About this Cake\r\nFresh cream\r\nSides are covered with finest quality chocolate Shavings\r\nColoured cream may stain\r\nColour &amp; design may vary\r\nSuitable to consume within 3 days of collection\r\nAll our cakes can have a personalised message\r\nTop decoration may vary',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	30.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-b38_grande-1.jpg',	'products-b38_grande-1.jpg',	'products-b38_grande-1.jpg',	'products-b38_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'photo-cake-personalise-b38',	NULL,	NULL,	NULL,	109761,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-b38_grande-1.jpg'),
(32,	'2-tier-heart-cake-code-w30',	NULL,	'2 Tier Heart Cake code W30',	0,	NULL,	'',	NULL,	'A Fresh Cream Heart Cake 2 Tier Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Step Stand Style cake',	'A Fresh Cream Heart Cake 2 Tier Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Step Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	180.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w287_grande-1.jpg',	'products-w287_grande-1.jpg',	'products-w287_grande-1.jpg',	'products-w287_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-heart-cake-code-w30',	NULL,	NULL,	NULL,	109773,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w287_grande-1.jpg'),
(33,	'2-tier-round-cake-code-w31',	NULL,	'2 Tier Round Cake code W31',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Cake 2 Tier Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Step Stand Style cake',	'A Fresh Cream Round Cake 2 Tier Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Step Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	185.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_6768_grande-1.png',	'products-img_6768_grande-1.png',	'products-img_6768_grande-1.png',	'products-img_6768_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-round-cake-code-w31',	NULL,	NULL,	NULL,	109774,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_6768_grande-1.png'),
(34,	'2-tier-tower-cake-code-w01',	NULL,	'2 Tier Tower Cake Code W01',	0,	NULL,	'',	NULL,	'A Fresh Cream Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Round Stacked Cake Style',	'A Fresh Cream Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Round Stacked Cake Style',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	225.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w01_grande-1.jpg',	'products-w01_grande-1.jpg',	'products-w01_grande-1.jpg',	'products-w01_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-tower-cake-code-w01',	NULL,	NULL,	NULL,	109775,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w01_grande-1.jpg'),
(35,	'2-tier-tower-cake-code-w02',	NULL,	'2 Tier Tower Cake Code W02',	0,	NULL,	'',	NULL,	'A Fresh Cream Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Round Stacked Cake Style',	'A Fresh Cream Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Round Stacked Cake Style',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	225.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w02_grande-1.jpg',	'products-w02_grande-1.jpg',	'products-w02_grande-1.jpg',	'products-w02_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-tower-cake-code-w02',	NULL,	NULL,	NULL,	109776,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w02_grande-1.jpg'),
(36,	'2-tier-tower-cake-code-w15',	NULL,	'2 Tier Tower cake code W15',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Round Cake Stacked Style',	'A Fresh Cream Round Cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n2 tier Freshly whipped cream Round Cake Stacked Style',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	230.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-93_grande-1.jpg',	'products-93_grande-1.jpg',	'products-93_grande-1.jpg',	'products-93_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-tower-cake-code-w15',	NULL,	NULL,	NULL,	109777,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-93_grande-1.jpg'),
(37,	'3-tier-cigroll-square-cake-cod',	NULL,	'3 Tier Cigroll Square Cake Code W48',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Cake Step Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\nHandmade cigrolls on the cake',	'A Fresh Cream Square Cake Step Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\nHandmade cigrolls on the cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	435.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-oocss_grande-1.jpg',	'products-oocss_grande-1.jpg',	'products-oocss_grande-1.jpg',	'products-oocss_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-cigroll-square-cake-code-w48',	NULL,	NULL,	NULL,	109778,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-oocss_grande-1.jpg'),
(38,	'3-tier-e-stand-cake-code-w27',	NULL,	'3 Tier E Stand cake code W27',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream E Stand Style cake',	'A Fresh Cream Round Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream E Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	210.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_6298_grande-1.png',	'products-img_6298_grande-1.png',	'products-img_6298_grande-1.png',	'products-img_6298_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-e-stand-cake-code-w27',	NULL,	NULL,	NULL,	109779,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_6298_grande-1.png'),
(39,	'3-tier-heart-cake-with-cupcake',	NULL,	'3 Tier Heart Cake with Cupcakes Code W43',	0,	NULL,	'',	NULL,	'A Fresh Cream Heart Cake Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\n100 cupcakes included',	'A Fresh Cream Heart Cake Step Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\n100 cupcakes included',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	410.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_7615_grande-1.png',	'products-img_7615_grande-1.png',	'products-img_7615_grande-1.png',	'products-img_7615_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-heart-cake-with-cupcakes-code-w43',	NULL,	NULL,	NULL,	109780,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7615_grande-1.png'),
(40,	'3-tier-rose-step-stand-cake-co',	NULL,	'3 Tier Rose Step Stand Cake Code W84',	0,	NULL,	'',	NULL,	'A Fresh Cream Rose Pipped Cake on a Step Stand,with four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Rose Pipped Freshly whipped cream Step Stand Style cake',	'A Fresh Cream Rose Pipped Cake on a Step Stand,with four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Rose Pipped Freshly whipped cream Step Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	340.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w84_d_grande-1.jpg',	'products-w84_d_grande-1.jpg',	'products-w84_d_grande-1.jpg',	'products-w84_d_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-rose-step-stand-cake-code-w84',	NULL,	NULL,	NULL,	109781,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w84_d_grande-1.jpg'),
(41,	'3-tier-tower-cake-code-w56',	NULL,	'3 Tier Tower Cake Code W56',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Tower Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream SStacked Style cake',	'A Fresh Cream Round Tower Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream SStacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	370.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-csssa_grande-1.jpg',	'products-csssa_grande-1.jpg',	'products-csssa_grande-1.jpg',	'products-csssa_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-code-w56',	NULL,	NULL,	NULL,	109783,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-csssa_grande-1.jpg'),
(42,	'3-tier-tower-fruit-cake-code-w',	NULL,	'3 Tier Tower Fruit Cake Code w91',	0,	NULL,	'',	NULL,	'A Butter Cream Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nButter Cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Butter Cream Stacked Style cake',	'A Butter Cream Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nButter Cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Butter Cream Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w91_grande-1.jpg',	'products-w91_grande-1.jpg',	'products-w91_grande-1.jpg',	'products-w91_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-fruit-cake-code-w91',	NULL,	NULL,	NULL,	109784,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w91_grande-1.jpg'),
(43,	'3-vase-cigroll-cake-with-cupca',	NULL,	'3 Vase Cigroll Cake With Cupcakes Code w08',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Cake Glass Vase Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Glass Vase Style cake\r\nWith 50 cupcakes\r\nHand made cigrolls',	'A Fresh Cream Round Cake Glass Vase Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Glass Vase Style cake\r\nWith 50 cupcakes\r\nHand made cigrolls',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	585.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w08_grande-1.jpg',	'products-w08_grande-1.jpg',	'products-w08_grande-1.jpg',	'products-w08_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-vase-cigroll-cake-with-cupcakes-code-w08',	NULL,	NULL,	NULL,	109785,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w08_grande-1.jpg'),
(44,	'4-tier-crystal-stand-collectio',	NULL,	'4 Tier Crystal Stand Collection Cake code W24',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Crystal Stand Style Cake four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream Cristal stand Style cake\r\nCrystal stand',	'A Fresh Cream Round Crystal Stand Style Cake four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream Cristal stand Style cake\r\nCrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	570.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_5062_grande-1.png',	'products-img_5062_grande-1.png',	'products-img_5062_grande-1.png',	'products-img_5062_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-crystal-stand-collection-cake-code-w24',	NULL,	NULL,	NULL,	109786,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_5062_grande-1.png'),
(45,	'4-tier-heart-cake-code-w50',	NULL,	'4 Tier Heart Cake Code W50',	0,	NULL,	'',	NULL,	'A Fresh Cream Heart Cake E Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream Step Stand Style cake',	'A Fresh Cream Heart Cake E Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream Step Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	395.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-heart-cake-code-w50',	NULL,	NULL,	NULL,	109787,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg'),
(46,	'4-tier-square-cake-e-stand-cak',	NULL,	'4 Tier Square Cake E Stand Cake Code W40',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream E Stand Style cake',	'A Fresh Cream Square Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream E Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	430.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_7178_grande-1.png',	'products-img_7178_grande-1.png',	'products-img_7178_grande-1.png',	'products-img_7178_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-square-cake-e-stand-cake-code-w40',	NULL,	NULL,	NULL,	109788,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7178_grande-1.png'),
(47,	'6-tier-crystal-collection-cake',	NULL,	'6 Tier Crystal Collection Cake Code W78',	0,	NULL,	'',	NULL,	'A Royal Icing Round Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nRoyal Icing.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n6 Tier Royal Icing Cristal stand Style cake\r\nCrystal stand',	'A Royal Icing Round Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nRoyal Icing.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n6 Tier Royal Icing Cristal stand Style cake\r\nCrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	599.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w78_grande.jpg',	'products-w78_grande.jpg',	'products-w78_grande.jpg',	'products-w78_grande.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'6-tier-crystal-collection-cake-code-w78',	NULL,	NULL,	NULL,	109789,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w78_grande.jpg'),
(48,	'8-tier-crystal-collection-cake',	NULL,	'8 Tier Crystal Collection Cake Code W53',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n7 tier Freshly whipped cream Cristal stand Style cake\r\nCrystal stand',	'A Fresh Cream Round Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n7 tier Freshly whipped cream Cristal stand Style cake\r\nCrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1150.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_8741_grande-1.jpg',	'products-img_8741_grande-1.jpg',	'products-img_8741_grande-1.jpg',	'products-img_8741_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'8-tier-crystal-collection-cake-code-w53',	NULL,	NULL,	NULL,	109790,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_8741_grande-1.jpg'),
(49,	'8-tier-heart-cake-code-w07',	NULL,	'8 Tier Heart Cake Code w07',	0,	NULL,	'',	NULL,	'A Fresh Cream Heart Cake 8 Tier Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n8 tier Freshly whipped cream 8 Tier Stand Style cake',	'A Fresh Cream Heart Cake 8 Tier Stand Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n8 tier Freshly whipped cream 8 Tier Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	630.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w07_grande-1.jpg',	'products-w07_grande-1.jpg',	'products-w07_grande-1.jpg',	'products-w07_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'8-tier-heart-cake-code-w07',	NULL,	NULL,	NULL,	109791,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w07_grande-1.jpg'),
(50,	'9-tier-crystal-collection-cake',	NULL,	'9 Tier Crystal Collection Cake Code W99',	0,	NULL,	'',	NULL,	'A 4 Tier Icing Round Stacked Cake &amp; 5 Single Cakes Style\r\n\r\nFour layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Icing Stacked Style cake\r\n5 tier Single Royal Icing Cakes on Cristal stand\r\nCrystal stand',	'A 4 Tier Icing Round Stacked Cake &amp; 5 Single Cakes Style\r\n\r\nFour layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Icing Stacked Style cake\r\n5 tier Single Royal Icing Cakes on Cristal stand\r\nCrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1200.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w99_grande-1.jpg',	'products-w99_grande-1.jpg',	'products-w99_grande-1.jpg',	'products-w99_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'9-tier-crystal-collection-cake-code-w99',	NULL,	NULL,	NULL,	109792,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w99_grande-1.jpg'),
(51,	'cigroll-crystal-stand-cake-cod',	NULL,	'Cigroll Crystal Stand Cake code W22',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Crystal Stand Style Cake four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Crystal stand Style cake\r\nHandmade cigrolls\r\nCrystal stand',	'A Fresh Cream Round Crystal Stand Style Cake four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Crystal stand Style cake\r\nHandmade cigrolls\r\nCrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	310.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_3815_grande-1.jpg',	'products-img_3815_grande-1.jpg',	'products-img_3815_grande-1.jpg',	'products-img_3815_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'cigroll-crystal-stand-cake-code-w22',	NULL,	NULL,	NULL,	109793,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_3815_grande-1.jpg'),
(52,	'cigroll-tower-cake-code-w04',	NULL,	'Cigroll Tower Cake Code w04',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Stacked Style cake\r\nHand made Cigrolls',	'A Fresh Cream Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Stacked Style cake\r\nHand made Cigrolls',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	380.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w04_grande-1.jpg',	'products-w04_grande-1.jpg',	'products-w04_grande-1.jpg',	'products-w04_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'cigroll-tower-cake-code-w04',	NULL,	NULL,	NULL,	109794,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w04_grande-1.jpg'),
(53,	'cigroll-tower-cake-code-w18',	NULL,	'Cigroll Tower Cake code W18',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream Square Tower Style cake',	'A Fresh Cream Square Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream Square Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	630.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-130_grande-1.jpg',	'products-130_grande-1.jpg',	'products-130_grande-1.jpg',	'products-130_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'cigroll-tower-cake-code-w18',	NULL,	NULL,	NULL,	109795,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-130_grande-1.jpg'),
(54,	'crystal-stand-collection-code-',	NULL,	'Crystal stand Collection Code w09',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Crystal stand Style cake\r\nCrystal stand',	'A Fresh Cream Round Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Crystal stand Style cake\r\nCrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	310.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-22.1.14_704_grande-1.jpg',	'products-22.1.14_704_grande-1.jpg',	'products-22.1.14_704_grande-1.jpg',	'products-22.1.14_704_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'crystal-stand-collection-code-w09',	NULL,	NULL,	NULL,	109796,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-22.1.14_704_grande-1.jpg'),
(55,	'e-stand-square-cake-code-w03',	NULL,	'E Stand Square Cake Code w03',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream E Stand Style cake',	'A Fresh Cream Square Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream E Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	230.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w03_grande-1.jpg',	'products-w03_grande-1.jpg',	'products-w03_grande-1.jpg',	'products-w03_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'e-stand-square-cake-code-w03',	NULL,	NULL,	NULL,	109797,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w03_grande-1.jpg'),
(56,	'gaint-heart-cake-style-w68',	NULL,	'Gaint Heart Cake Style (W68)',	0,	NULL,	'',	NULL,	'A Single Giant Fresh Cream Heart Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Giant Freshly whipped cream Heart Style cake\r\n50 cupcakes included\r\nHeart Back drop not included',	'A Single Giant Fresh Cream Heart Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Giant Freshly whipped cream Heart Style cake\r\n50 cupcakes included\r\nHeart Back drop not included',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	530.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-jijgfdf_grande-1.jpg',	'products-jijgfdf_grande-1.jpg',	'products-jijgfdf_grande-1.jpg',	'products-jijgfdf_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'gaint-heart-cake-style-w68',	NULL,	NULL,	NULL,	109798,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-jijgfdf_grande-1.jpg'),
(57,	'heart-cake-swan-stand-style-w2',	NULL,	'Heart Cake Swan Stand Style (W29)',	0,	NULL,	'',	NULL,	'A Fresh Cream Heart Cake Swan Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Swan Stand Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	'A Fresh Cream Heart Cake Swan Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Swan Stand Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	230.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_6370_grande-1.png',	'products-img_6370_grande-1.png',	'products-img_6370_grande-1.png',	'products-img_6370_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'heart-cake-swan-stand-style-w29',	NULL,	NULL,	NULL,	109799,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_6370_grande-1.png'),
(58,	'round-single-cake-with-cupcake',	NULL,	'Round Single cake with Cupcakes (W20)',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Single Cake with 4 Tired Stand Style and 40 Cupcakes with a four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream 4 Tire Stand Style cake\r\n40 cup cakes included\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	'A Fresh Cream Round Single Cake with 4 Tired Stand Style and 40 Cupcakes with a four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Freshly whipped cream 4 Tire Stand Style cake\r\n40 cup cakes included\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	140.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-164_grande-1.jpg',	'products-164_grande-1.jpg',	'products-164_grande-1.jpg',	'products-164_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'round-single-cake-with-cupcakes-w20',	NULL,	NULL,	NULL,	109800,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-164_grande-1.jpg'),
(59,	'single-cake-crystal-stand-styl',	NULL,	'Single Cake Crystal Stand Style with Cupcakes Code W42',	0,	NULL,	'',	NULL,	'A Fresh Cream Round Cake Crystal Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\nCrystal cake\r\n85 cupcakes included',	'A Fresh Cream Round Cake Crystal Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\nCrystal cake\r\n85 cupcakes included',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	225.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_7359_grande-1.png',	'products-img_7359_grande-1.png',	'products-img_7359_grande-1.png',	'products-img_7359_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'single-cake-crystal-stand-style-with-cupcakes-code-w42',	NULL,	NULL,	NULL,	109801,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7359_grande-1.png'),
(60,	'single-cake-with-cup-cakes-w19',	NULL,	'Single cake with cup cakes (W19)',	0,	NULL,	'',	NULL,	'A Fresh Cream Round single cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\nHand made cigrolls\r\n40 cupcakes included\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	'A Fresh Cream Round single cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Step Stand Style cake\r\nHand made cigrolls\r\n40 cupcakes included\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	150.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-138_grande-1.jpg',	'products-138_grande-1.jpg',	'products-138_grande-1.jpg',	'products-138_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'single-cake-with-cup-cakes-w19',	NULL,	NULL,	NULL,	109802,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-138_grande-1.jpg'),
(61,	'single-fresh-cream-cake-code-w',	NULL,	'Single Fresh Cream Cake code W38',	0,	NULL,	'',	NULL,	'A Single Fresh Cream Round Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	'A Single Fresh Cream Round Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	90.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_7130_grande-1.png',	'products-img_7130_grande-1.png',	'products-img_7130_grande-1.png',	'products-img_7130_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'single-fresh-cream-cake-code-w38',	NULL,	NULL,	NULL,	109803,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7130_grande-1.png'),
(62,	'single-rectangle-style-cake-w6',	NULL,	'Single Rectangle Style Cake (W65)',	0,	NULL,	'',	NULL,	'A Single Fresh Cream Rectangle Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery &amp; setup (Mileage applies)',	'A Single Fresh Cream Rectangle Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery &amp; setup (Mileage applies)',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	99.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-rrrgg_grande-1.jpg',	'products-rrrgg_grande-1.jpg',	'products-rrrgg_grande-1.jpg',	'products-rrrgg_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'single-rectangle-style-cake-w65',	NULL,	NULL,	NULL,	109804,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-rrrgg_grande-1.jpg'),
(63,	'single-round-wedding-cake-code',	NULL,	'Single Round Wedding Cake Code W59',	0,	NULL,	'',	NULL,	'A Single Fresh Cream Round Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake',	'A Single Fresh Cream Round Cake Style with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	90.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-vdssaa_grande-1.jpg',	'products-vdssaa_grande-1.jpg',	'products-vdssaa_grande-1.jpg',	'products-vdssaa_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'single-round-wedding-cake-code-w59',	NULL,	NULL,	NULL,	109805,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-vdssaa_grande-1.jpg'),
(64,	'single-tier-fresh-cream-rectan',	NULL,	'Single Tier Freshly Whipped Cream Rectangle Cake code W39',	0,	NULL,	'',	NULL,	'A Single Fresh Cream Rectangle Cake Style with four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake',	'A Single Fresh Cream Rectangle Cake Style with four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\nSingle Freshly whipped cream Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	79.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_7155_grande-1.png',	'products-img_7155_grande-1.png',	'products-img_7155_grande-1.png',	'products-img_7155_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'single-tier-freshly-whipped-cream-rectangle-cake-code-w39',	NULL,	NULL,	NULL,	109806,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7155_grande-1.png'),
(65,	'square-tower-style-cake-w23',	NULL,	'Square Tower Style cake (W23)',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Tower Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Square Tower Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	'A Fresh Cream Square Tower Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Square Tower Style cake\r\nPrice of wedding cakes includes\r\nWedding Cake\r\nDelivery (within Birmingham)\r\nCake Stand Hire',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	335.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-187_grande-1.jpg',	'products-187_grande-1.jpg',	'products-187_grande-1.jpg',	'products-187_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'square-tower-style-cake-w23',	NULL,	NULL,	NULL,	109807,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-187_grande-1.jpg'),
(66,	'swan-stand-cake-code-w05',	NULL,	'Swan Stand Cake Code W05',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream E Stand Style cake',	'A Fresh Cream Square Cake E Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream E Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	210.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-18_grande-1.jpg',	'products-18_grande-1.jpg',	'products-18_grande-1.jpg',	'products-18_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'swan-stand-cake-code-w05',	NULL,	NULL,	NULL,	109809,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-18_grande-1.jpg'),
(67,	'swan-stand-cake-code-w14',	NULL,	'Swan Stand cake code W14',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Cake Swan Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Swan Stand Style cake',	'A Fresh Cream Square Cake Swan Stand Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Swan Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	230.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-101_keep_bg_grande-1.jpg',	'products-101_keep_bg_grande-1.jpg',	'products-101_keep_bg_grande-1.jpg',	'products-101_keep_bg_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'swan-stand-cake-code-w14',	NULL,	NULL,	NULL,	109810,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-101_keep_bg_grande-1.jpg'),
(68,	'tower-cake-code-w12',	NULL,	'Tower cake code w12',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Tower Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Square Tower Style cake',	'A Fresh Cream Square Tower Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Square Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	385.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-62_keep_bg_grande-1.jpg',	'products-62_keep_bg_grande-1.jpg',	'products-62_keep_bg_grande-1.jpg',	'products-62_keep_bg_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'tower-cake-code-w12',	NULL,	NULL,	NULL,	109811,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-62_keep_bg_grande-1.jpg'),
(69,	'tower-cake-code-w21',	NULL,	'Tower Cake code W21',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Tower Cake Style',	'A Fresh Cream Square Tower Cake Style four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Tower Cake Style',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	370.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-142_keep_bg_grande-1.jpg',	'products-142_keep_bg_grande-1.jpg',	'products-142_keep_bg_grande-1.jpg',	'products-142_keep_bg_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'tower-cake-code-w21',	NULL,	NULL,	NULL,	109812,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-142_keep_bg_grande-1.jpg'),
(70,	'vase-cake-code-w06',	NULL,	'Vase Cake Code w06',	0,	NULL,	'',	NULL,	'A Fresh Cream Vase Cake four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers',	'A Fresh Cream Vase Cake four layer Victoria style sponge with a layer of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	370.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w06_grande-1.jpg',	'products-w06_grande-1.jpg',	'products-w06_grande-1.jpg',	'products-w06_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'vase-cake-code-w06',	NULL,	NULL,	NULL,	109813,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w06_grande-1.jpg'),
(71,	'10-tier-tower-cake-code-w64',	NULL,	'10 Tier Tower Cake Code W64',	0,	NULL,	'',	NULL,	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n10 tier Royal Icing Stacked Style cake\r\n( 8 + 9 + 10 + 11 + 12 + 13 + 14 + 15 + 16 Dummies ) + 24 Real Cake Fresh Cream or Icing',	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n10 tier Royal Icing Stacked Style cake\r\n( 8 + 9 + 10 + 11 + 12 + 13 + 14 + 15 + 16 Dummies ) + 24 Real Cake Fresh Cream or Icing',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1499.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-dsc_7459_grande-1.jpg',	'products-dsc_7459_grande-1.jpg',	'products-dsc_7459_grande-1.jpg',	'products-dsc_7459_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'10-tier-tower-cake-code-w64',	NULL,	NULL,	NULL,	109814,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-dsc_7459_grande-1.jpg'),
(72,	'2-tier-round-cake-code-w49',	NULL,	'2 Tier Round Cake Code W49',	0,	NULL,	'',	NULL,	'A Icing Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Icing Stacked Style cake',	'A Icing Round Stacked Cake Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	90.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_8319_grande-1.png',	'products-img_8319_grande-1.png',	'products-img_8319_grande-1.png',	'products-img_8319_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-round-cake-code-w49',	NULL,	NULL,	NULL,	109815,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_8319_grande-1.png'),
(73,	'3-tier-crystal-tower-collectio',	NULL,	'3 Tier Crystal Tower Collection With Freshly Whipped Cream Cakes Code W63',	0,	NULL,	'',	NULL,	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake\r\nplus 8x 8 Freshly whipped cream cakes\r\ncrystal stand',	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake\r\nplus 8x 8 Freshly whipped cream cakes\r\ncrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	920.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-dsc_3223_grande-1.jpg',	'products-dsc_3223_grande-1.jpg',	'products-dsc_3223_grande-1.jpg',	'products-dsc_3223_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-crystal-tower-collection-with-freshly-whipped-cream-cakes-code-w63',	NULL,	NULL,	NULL,	109816,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-dsc_3223_grande-1.jpg'),
(74,	'3-tier-golden-tower-cake-code-',	NULL,	'3 Tier Golden Tower Cake code w73',	0,	NULL,	'',	NULL,	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake\r\nplus 8x 8 Freshly whipped cream cakes\r\ncrystal stand',	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake\r\nplus 8x 8 Freshly whipped cream cakes\r\ncrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w73_grande-1.jpg',	'products-w73_grande-1.jpg',	'products-w73_grande-1.jpg',	'products-w73_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-golden-tower-cake-code-w73',	NULL,	NULL,	NULL,	109817,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w73_grande-1.jpg'),
(75,	'3-tier-heart-step-stand-cake-c',	NULL,	'3 Tier Heart Step Stand Cake Code W90',	0,	NULL,	'',	NULL,	'A Royal Icing Heart Cake Step Stand Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Cake Step Stand Style cake',	'A Royal Icing Heart Cake Step Stand Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Cake Step Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w90_grande-1.jpg',	'products-w90_grande-1.jpg',	'products-w90_grande-1.jpg',	'products-w90_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-heart-step-stand-cake-code-w90',	NULL,	NULL,	NULL,	109818,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w90_grande-1.jpg'),
(76,	'3-tier-icing-tower-cake-code-w',	NULL,	'3 Tier Icing Tower Cake Code W46',	0,	NULL,	'',	NULL,	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour & design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	340.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_7701_grande-1.jpg',	'products-img_7701_grande-1.jpg',	'products-img_7701_grande-1.jpg',	'products-img_7701_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-icing-tower-cake-code-w46',	NULL,	NULL,	NULL,	109819,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7701_grande-1.jpg'),
(77,	'3-tier-metallic-tower-cake-cod',	NULL,	'3 Tier metallic Tower Cake Code W113',	0,	NULL,	'',	NULL,	'A Round Stacked Cake Style with Victoria style sponge with a layer of our delicious rich Butter cream covered with royal icing. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\n3 tier Icing Stacked Style cake',	'A Round Stacked Cake Style with Victoria style sponge with a layer of our delicious rich Butter cream covered with royal icing. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\n3 tier Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	275.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-wedding_cake_fo_cov_road_grande-1.png',	'products-wedding_cake_fo_cov_road_grande-1.png',	'products-wedding_cake_fo_cov_road_grande-1.png',	'products-wedding_cake_fo_cov_road_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-metallic-tower-cake-code-w113',	NULL,	NULL,	NULL,	109820,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-wedding_cake_fo_cov_road_grande-1.png'),
(78,	'3-tier-round-crystal-tower-cak',	NULL,	'3 Tier Round Crystal Tower Cake Code W92',	0,	NULL,	'',	NULL,	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake\r\nCrystal Collection',	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake\r\nCrystal Collection',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w92_grande-1.jpg',	'products-w92_grande-1.jpg',	'products-w92_grande-1.jpg',	'products-w92_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-round-crystal-tower-cake-code-w92',	NULL,	NULL,	NULL,	109821,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w92_grande-1.jpg'),
(79,	'3-tier-step-stand-cake-code-w6',	NULL,	'3 Tier Step Stand Cake Code W66',	0,	NULL,	'',	NULL,	'A Royal Icing Square Cake Step Stand Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Cake Step Stand Style cake',	'A Royal Icing Square Cake Step Stand Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Cake Step Stand Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	370.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-kokohff_grande-1.jpg',	'products-kokohff_grande-1.jpg',	'products-kokohff_grande-1.jpg',	'products-kokohff_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-step-stand-cake-code-w66',	NULL,	NULL,	NULL,	109822,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-kokohff_grande-1.jpg'),
(80,	'3-tier-tower-cake-code-w103',	NULL,	'3 Tier Tower Cake Code W103',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w103_grande-1.jpg',	'products-w103_grande-1.jpg',	'products-w103_grande-1.jpg',	'products-w103_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-code-w103',	NULL,	NULL,	NULL,	109823,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w103_grande-1.jpg'),
(81,	'3-tier-tower-cake-code-w104',	NULL,	'3 Tier Tower Cake Code W104',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w104_grande-1.jpg',	'products-w104_grande-1.jpg',	'products-w104_grande-1.jpg',	'products-w104_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-code-w104',	NULL,	NULL,	NULL,	109824,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w104_grande-1.jpg'),
(82,	'3-tier-tower-cake-code-w105',	NULL,	'3 Tier Tower Cake Code W105',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w105_grande-1.jpg',	'products-w105_grande-1.jpg',	'products-w105_grande-1.jpg',	'products-w105_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-code-w105',	NULL,	NULL,	NULL,	109825,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w105_grande-1.jpg'),
(83,	'3-tier-tower-cake-code-w96',	NULL,	'3 Tier Tower Cake code w96',	0,	NULL,	'',	NULL,	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w96_grande-1.jpg',	'products-w96_grande-1.jpg',	'products-w96_grande-1.jpg',	'products-w96_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-code-w96',	NULL,	NULL,	NULL,	109826,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w96_grande-1.jpg'),
(84,	'3-tier-tower-cake-code-w97',	NULL,	'3 Tier Tower Cake code w97',	0,	NULL,	'',	NULL,	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w97_grande-1.jpg',	'products-w97_grande-1.jpg',	'products-w97_grande-1.jpg',	'products-w97_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-code-w97',	NULL,	NULL,	NULL,	109827,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w97_grande-1.jpg'),
(85,	'3-tier-tower-cake-code-w54',	NULL,	'3 Tier Tower Cake Code W54',	0,	NULL,	'',	NULL,	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	'A Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	315.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_8735_grande-1.jpg',	'products-img_8735_grande-1.jpg',	'products-img_8735_grande-1.jpg',	'products-img_8735_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-code-w54',	NULL,	NULL,	NULL,	109828,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_8735_grande-1.jpg'),
(86,	'3-tier-tower-cake-w87',	NULL,	'3 Tier Tower Cake W87',	0,	NULL,	'',	NULL,	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w87_grande-1.jpg',	'products-w87_grande-1.jpg',	'products-w87_grande-1.jpg',	'products-w87_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-w87',	NULL,	NULL,	NULL,	109829,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w87_grande-1.jpg'),
(87,	'3-tier-tower-cake-with-rose-fl',	NULL,	'3 Tier Tower Cake With Rose Flower Base Code W79',	0,	NULL,	'',	NULL,	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	250.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w104_grande_2-1.jpg',	'products-w104_grande_2-1.jpg',	'products-w104_grande_2-1.jpg',	'products-w104_grande_2-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-tower-cake-with-rose-flower-base-code-w79',	NULL,	NULL,	NULL,	109830,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w104_grande_2-1.jpg'),
(88,	'4-tier-crystal-tower-cake-code',	NULL,	'4 Tier Crystal Tower Cake Code W25',	0,	NULL,	'',	NULL,	'A Round Stacked Cake Style with Victoria style sponge with a layer of our delicious rich Butter cream covered with royal icing. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Icing Stacked Style cake\r\nCrystal Stand',	'A Round Stacked Cake Style with Victoria style sponge with a layer of our delicious rich Butter cream covered with royal icing. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Icing Stacked Style cake\r\nCrystal Stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	470.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w25_grande-1.jpg',	'products-w25_grande-1.jpg',	'products-w25_grande-1.jpg',	'products-w25_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-crystal-tower-cake-code-w25',	NULL,	NULL,	NULL,	109831,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w25_grande-1.jpg'),
(89,	'4-tier-crystal-tower-cake-code',	NULL,	'4 Tier Crystal Tower Cake code w76',	0,	NULL,	'',	NULL,	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake\r\nCrystal Stand',	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake\r\nCrystal Stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	420.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w76_grande-1.jpg',	'products-w76_grande-1.jpg',	'products-w76_grande-1.jpg',	'products-w76_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-crystal-tower-cake-code-w76',	NULL,	NULL,	NULL,	109832,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w76_grande-1.jpg'),
(90,	'4-tier-crystal-tower-cake-with',	NULL,	'4 Tier Crystal Tower Cake with Cupcakes Code W45',	0,	NULL,	'',	NULL,	'A Royal Icing Stacked Style cake with Victoria style sponge and layers of our delicious rich Butter cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Stacked Style cake\r\n30 cup cakes included\r\nCrystal Stand',	'A Royal Icing Stacked Style cake with Victoria style sponge and layers of our delicious rich Butter cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Stacked Style cake\r\n30 cup cakes included\r\nCrystal Stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	670.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-img_7702_grande-1.png',	'products-img_7702_grande-1.png',	'products-img_7702_grande-1.png',	'products-img_7702_grande-1.png',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-crystal-tower-cake-with-cupcakes-code-w45',	NULL,	NULL,	NULL,	109833,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-img_7702_grande-1.png'),
(91,	'4-tier-golden-tower-cake-w55',	NULL,	'4 Tier Golden Tower Cake (W55)',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	560.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-dvdd_grande-1.jpg',	'products-dvdd_grande-1.jpg',	'products-dvdd_grande-1.jpg',	'products-dvdd_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-golden-tower-cake-w55',	NULL,	NULL,	NULL,	109834,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-dvdd_grande-1.jpg'),
(92,	'4-tier-petal-tower-cake-code-w',	NULL,	'4 Tier Petal Tower Cake Code W89',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	450.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w89_grande-1.jpg',	'products-w89_grande-1.jpg',	'products-w89_grande-1.jpg',	'products-w89_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-petal-tower-cake-code-w89',	NULL,	NULL,	NULL,	109835,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w89_grande-1.jpg'),
(93,	'4-tier-round-crystal-tower-cak',	NULL,	'4 Tier Round Crystal Tower Cake code w77',	0,	NULL,	'',	NULL,	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake\r\nCrystal Stand',	'Royal Icing Tower Style\r\n\r\nA Royal Icing Round Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake\r\nCrystal Stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	450.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w77_grande-1.jpg',	'products-w77_grande-1.jpg',	'products-w77_grande-1.jpg',	'products-w77_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-round-crystal-tower-cake-code-w77',	NULL,	NULL,	NULL,	109836,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w77_grande-1.jpg'),
(94,	'4-tier-tower-cake-code-w101',	NULL,	'4 Tier Tower Cake Code W101',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	450.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w101_grande-1.jpg',	'products-w101_grande-1.jpg',	'products-w101_grande-1.jpg',	'products-w101_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-code-w101',	NULL,	NULL,	NULL,	109837,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w101_grande-1.jpg'),
(95,	'4-tier-tower-cake-code-w102',	NULL,	'4 Tier Tower Cake Code W102',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	450.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w102_grande-1.jpg',	'products-w102_grande-1.jpg',	'products-w102_grande-1.jpg',	'products-w102_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-code-w102',	NULL,	NULL,	NULL,	109838,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w102_grande-1.jpg'),
(96,	'4-tier-tower-cake-code-w109',	NULL,	'4 Tier Tower Cake Code W109',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	450.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w109_grande-1.jpg',	'products-w109_grande-1.jpg',	'products-w109_grande-1.jpg',	'products-w109_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-code-w109',	NULL,	NULL,	NULL,	109839,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w109_grande-1.jpg'),
(97,	'4-tier-tower-cake-code-w70',	NULL,	'4 Tier Tower Cake Code W70',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Stacked Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Stacked Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	545.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-dcwqdwqwqd_grande-1.jpg',	'products-dcwqdwqwqd_grande-1.jpg',	'products-dcwqdwqwqd_grande-1.jpg',	'products-dcwqdwqwqd_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-code-w70',	NULL,	NULL,	NULL,	109840,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-dcwqdwqwqd_grande-1.jpg'),
(98,	'4-tier-tower-cake-code-w80',	NULL,	'4 Tier Tower Cake Code W80',	0,	NULL,	'',	NULL,	'A Squre Stacked Cake Style with Victoria style sponge with a layer of our delicious rich Butter cream covered with royal icing. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Icing Stacked Style cake',	'A Squre Stacked Cake Style with Victoria style sponge with a layer of our delicious rich Butter cream covered with royal icing. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nIcing cake.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	450.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w80_grande-1.jpg',	'products-w80_grande-1.jpg',	'products-w80_grande-1.jpg',	'products-w80_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-code-w80',	NULL,	NULL,	NULL,	109841,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w80_grande-1.jpg'),
(99,	'4-tier-tower-cake-w116',	NULL,	'4 Tier Tower Cake W116',	0,	NULL,	'',	NULL,	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Stacked Style cake',	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	700.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-wedding_700_grande-1.jpg',	'products-wedding_700_grande-1.jpg',	'products-wedding_700_grande-1.jpg',	'products-wedding_700_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-w116',	NULL,	NULL,	NULL,	109842,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-wedding_700_grande-1.jpg'),
(100,	'4-tier-tower-cake-with-cupcake',	NULL,	'4 Tier Tower Cake With Cupcakes Code W85',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	450.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-shopify_template_for_pics_w85_grande-2.jpg',	'products-shopify_template_for_pics_w85_grande-2.jpg',	'products-shopify_template_for_pics_w85_grande-2.jpg',	'products-shopify_template_for_pics_w85_grande-2.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-with-cupcakes-code-w85',	NULL,	NULL,	NULL,	109843,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-shopify_template_for_pics_w85_grande-2.jpg'),
(101,	'5-tier-tower-cake-code-w107',	NULL,	'5 Tier Tower Cake Code W107',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	595.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w107_grande-1.jpg',	'products-w107_grande-1.jpg',	'products-w107_grande-1.jpg',	'products-w107_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'5-tier-tower-cake-code-w107',	NULL,	NULL,	NULL,	109844,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w107_grande-1.jpg'),
(102,	'5-tier-tower-cake-with-rose-ba',	NULL,	'5 Tier Tower Cake with Rose Base Code W106',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n5 tier Royal Icing Tower Style cake\r\nRose Cake Stand Base',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n5 tier Royal Icing Tower Style cake\r\nRose Cake Stand Base',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	750.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w106_grande-318.jpg',	'products-w106_grande-318.jpg',	'products-w106_grande-318.jpg',	'products-w106_grande-318.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'5-tier-tower-cake-with-rose-base-code-w106',	NULL,	NULL,	NULL,	109845,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w106_grande-318.jpg'),
(103,	'2-tier-tower-cake-code-w95',	NULL,	'2 Tier Tower Cake Code W95',	0,	NULL,	'',	NULL,	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk Flowers\r\n2 tier Royal Icing Stacked Style cake',	'A Royal Icing Round cake Stacked Style four layer Victoria style sponge with a layer of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated..\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk Flowers\r\n2 tier Royal Icing Stacked Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	200.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w95_grande-1.jpg',	'products-w95_grande-1.jpg',	'products-w95_grande-1.jpg',	'products-w95_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'2-tier-tower-cake-code-w95',	NULL,	NULL,	NULL,	109847,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w95_grande-1.jpg'),
(104,	'3-tier-crystal-square-cake-cod',	NULL,	'3 Tier Crystal Square Cake code W52',	0,	NULL,	'',	NULL,	'A Fresh Cream Square Cake and Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Crystal stand Style cake\r\nCrystal stand',	'A Fresh Cream Square Cake and Crystal Stand Style Cake with Victoria style sponge and layers of our delicious rich Freshly whipped cream. All our cakes are freshly hand-made and beautifully decorated with hand piped Freshly whipped cream.\r\n\r\nFresh cream.\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n3 tier Freshly whipped cream Crystal stand Style cake\r\nCrystal stand',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	395.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-mmo_grande_2-1.jpg',	'products-mmo_grande_2-1.jpg',	'products-mmo_grande_2-1.jpg',	'products-mmo_grande_2-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-tier-crystal-square-cake-code-w52',	NULL,	NULL,	NULL,	109848,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-mmo_grande_2-1.jpg'),
(105,	'4-tier-tower-cake-code-w108-2',	NULL,	'4 Tier Tower Cake Code W108',	0,	NULL,	'',	NULL,	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	'Royal Icing Stacked Style\r\n\r\nA Royal Icing Square Cake Tower Style with Victoria style sponge and layers of our delicious rich Butter Cream covered with Royal Icing. All our cakes are freshly hand-made and beautifully decorated.\r\n\r\nRoyal Icing.\r\nButter Cream\r\nColoured cream may stain.\r\nColour &amp; design may vary.\r\nsuitable to consume within 3 days of collection\r\nSilk flowers\r\n4 tier Royal Icing Tower Style cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	500.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'products-w108_grande-1.jpg',	'products-w108_grande-1.jpg',	'products-w108_grande-1.jpg',	'products-w108_grande-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'14',	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'4-tier-tower-cake-code-w108',	NULL,	NULL,	NULL,	110190,	'https://bbakery.co.uk/wp-content/uploads/2019/08/products-w108_grande-1.jpg|https://bbakery.co.uk/wp-content/uploads/2020/01/GOLD-2.jpg|https://bbakery.co.uk/wp-content/uploads/2020/02/CUPCAKE-CUPCAKE-TOPPER-CUPCAKE-STAND.jpg'),
(106,	'',	NULL,	'10 Round smooth Vanilla Cake',	0,	NULL,	'',	NULL,	'10 Round smooth Vanilla Cake',	'10 Round smooth Vanilla Cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	20.00,	0.00,	0.00,	20.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'hdb_cake1-1.jpeg',	'hdb_cake1-1.jpeg',	'hdb_cake1-1.jpeg',	'hdb_cake1-1.jpeg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'10-round-smooth-vanilla-cake',	NULL,	NULL,	NULL,	110534,	'https://bbakery.co.uk/wp-content/uploads/2019/08/hdb_cake1-1.jpeg'),
(107,	'',	NULL,	'12 Round Smooth Vanilla Cake',	0,	NULL,	'',	NULL,	'12 Round Smooth Vanilla Cake',	'12 Round Smooth Vanilla Cake',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	25.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'hdb_cake2-1.jpeg',	'hdb_cake2-1.jpeg',	'hdb_cake2-1.jpeg',	'hdb_cake2-1.jpeg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'12-round-smooth-vanilla-cake',	NULL,	NULL,	NULL,	110536,	'https://bbakery.co.uk/wp-content/uploads/2019/08/hdb_cake2-1.jpeg'),
(108,	'',	NULL,	'12 SQUARE SMOOTH VANILLA CAKE',	0,	NULL,	'',	NULL,	'12 Square Smooth Vanilla Cake',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	35.00,	0.00,	0.00,	35.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'hdb_cake3-1.jpeg',	'hdb_cake3-1.jpeg',	'hdb_cake3-1.jpeg',	'hdb_cake3-1.jpeg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'12-square-smooth-vanilla-cake',	NULL,	NULL,	NULL,	110538,	'https://bbakery.co.uk/wp-content/uploads/2019/08/hdb_cake3-1.jpeg'),
(109,	'',	NULL,	'Ramadan',	0,	NULL,	'',	NULL,	'Fresh Cream Vanilla Flavour Cake Victoria Style Sponge Choice Of Different Colours Of Writing Any Choice Of Side Decoration',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	NULL,	0.00,	'',	NULL,	NULL,	NULL,	NULL,	'Ramadan.jpg',	'Ramadan.jpg',	'Ramadan.jpg',	'Ramadan.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	1,	0,	0.000,	0,	'1',	0,	NULL,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'',	'',	0,	'ramadan',	NULL,	NULL,	NULL,	111533,	'https://bbakery.co.uk/wp-content/uploads/2020/04/Ramadan.jpg'),
(110,	'',	NULL,	'Iftar',	0,	NULL,	'',	NULL,	'Fresh Cream Vanilla Flavour Cream\r\n\r\nVictoria Style Sponge\r\n\r\nChocolate Decoration\r\n\r\nChoice Of Side Decoration\r\n\r\nChoice Of Writing Colours\r\n\r\nPersonalised Message',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	20.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'Iftar.jpg',	'Iftar.jpg',	'Iftar.jpg',	'Iftar.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'iftar',	NULL,	NULL,	NULL,	111558,	'https://bbakery.co.uk/wp-content/uploads/2020/04/Iftar.jpg'),
(111,	'',	NULL,	'Eid 111',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	'',	'',	NULL,	NULL,	NULL,	'4.jpg',	'4.jpg',	'4.jpg',	'4.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	15,	NULL,	40,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'eid-111',	NULL,	NULL,	NULL,	111772,	'https://bbakery.co.uk/wp-content/uploads/2020/05/4.jpg'),
(112,	'',	NULL,	'EID 113',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	'',	'',	NULL,	NULL,	NULL,	'3.jpg',	'3.jpg',	'3.jpg',	'3.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	33,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'eid-113',	NULL,	NULL,	NULL,	111845,	'https://bbakery.co.uk/wp-content/uploads/2020/05/3.jpg'),
(113,	'',	NULL,	'EID 110',	0,	NULL,	'',	NULL,	'',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	20.00,	0.00,	NULL,	0.00,	'',	NULL,	NULL,	NULL,	NULL,	'1.jpg',	'1.jpg',	'1.jpg',	'1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	1,	0,	0.000,	30,	'1',	0,	NULL,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'',	'',	0,	'eid-110',	NULL,	NULL,	NULL,	111851,	'https://bbakery.co.uk/wp-content/uploads/2020/05/1.jpg'),
(114,	'',	NULL,	'EID 109',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	20.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'2.jpg',	'2.jpg',	'2.jpg',	'2.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'eid-109',	NULL,	NULL,	NULL,	111856,	'https://bbakery.co.uk/wp-content/uploads/2020/05/2.jpg'),
(115,	'',	NULL,	'Fathers Day = Best Dad In The World',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fathers-day-best-dad-in-the-world',	NULL,	NULL,	NULL,	111974,	''),
(116,	'',	NULL,	'FDS1 VANILLA CREAM',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	20.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'1.jpg',	'1.jpg',	'1.jpg',	'1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fds1-vanilla-cream',	NULL,	NULL,	NULL,	111976,	'https://bbakery.co.uk/wp-content/uploads/2020/06/1.jpg'),
(117,	'',	NULL,	'FDS2 VANILLA CREAM',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'2.jpg',	'2.jpg',	'2.jpg',	'2.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fds2-vanilla-cream',	NULL,	NULL,	NULL,	111980,	'https://bbakery.co.uk/wp-content/uploads/2020/06/2.jpg'),
(118,	'',	NULL,	'FDS3',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	20.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'3.jpg',	'3.jpg',	'3.jpg',	'3.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fds3',	NULL,	NULL,	NULL,	111984,	'https://bbakery.co.uk/wp-content/uploads/2020/06/3.jpg'),
(119,	'',	NULL,	'FDS4',	0,	NULL,	'',	NULL,	'',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	25.00,	0.00,	NULL,	0.00,	'',	NULL,	NULL,	NULL,	NULL,	'4.jpg',	'4.jpg',	'4.jpg',	'4.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	1,	0,	0.000,	0,	'1',	0,	1,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'',	'',	0,	'fds4',	NULL,	NULL,	NULL,	111988,	'https://bbakery.co.uk/wp-content/uploads/2020/06/4.jpg'),
(120,	'',	NULL,	'Blue Number Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'Blue-sparklers.jpg',	'Blue-sparklers.jpg',	'Blue-sparklers.jpg',	'Blue-sparklers.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'1',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'blue-number-sparklers',	NULL,	NULL,	NULL,	113542,	'https://bbakery.co.uk/wp-content/uploads/2020/07/Blue-sparklers.jpg'),
(121,	'',	NULL,	'Gold Number Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'golden.jpg',	'golden.jpg',	'golden.jpg',	'golden.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'gold-number-sparklers',	NULL,	NULL,	NULL,	113555,	'https://bbakery.co.uk/wp-content/uploads/2020/07/golden.jpg'),
(122,	'',	NULL,	'Pink Number Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'pink-1.jpg',	'pink-1.jpg',	'pink-1.jpg',	'pink-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'pink-number-sparklers',	NULL,	NULL,	NULL,	113559,	'https://bbakery.co.uk/wp-content/uploads/2020/07/pink-1.jpg'),
(123,	'',	NULL,	'Silver Number Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'silver.jpg',	'silver.jpg',	'silver.jpg',	'silver.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'silver-number-sparklers',	NULL,	NULL,	NULL,	113562,	'https://bbakery.co.uk/wp-content/uploads/2020/07/silver.jpg'),
(124,	'',	NULL,	'5 Monster Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	1.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'5-monster-sparkles.jpg',	'5-monster-sparkles.jpg',	'5-monster-sparkles.jpg',	'5-monster-sparkles.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'5-monster-sparklers',	NULL,	NULL,	NULL,	113566,	'https://bbakery.co.uk/wp-content/uploads/2020/07/5-monster-sparkles.jpg'),
(125,	'',	NULL,	'Party Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'35.jpg',	'35.jpg',	'35.jpg',	'35.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'party-sparklers',	NULL,	NULL,	NULL,	113567,	'https://bbakery.co.uk/wp-content/uploads/2020/07/35.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/1404cada-d183-406a-bce9-2ec8502c34fc.jpg'),
(126,	'',	NULL,	'3 Ice Fountains',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	2.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'26.jpg',	'26.jpg',	'26.jpg',	'26.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-ice-fountains',	NULL,	NULL,	NULL,	113571,	'https://bbakery.co.uk/wp-content/uploads/2020/07/26.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/0d407d9f-a4e8-4134-ad55-825a69bcd71b.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/0fe7d3d8-6184-4d58-866f-7a0858d9df69.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/2eaeb7e2-0106-4fa4-bd0a-6e559633a3de.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/3bb950c7-b753-4716-9925-d9eae246bc09.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/06b6e0fe-2505-49a2-938b-7246c21c4518.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/6f5d558e-dc48-4611-ba06-7d5d78d376f9.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/7f436ccd-7ab2-4b1b-a1a0-6f4514bb27c1.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/8d357291-302f-4cae-a748-92bf1161bcaf.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/8da4a495-951d-4994-b0eb-cc09e25f91b6.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/15d13205-b6ba-43c7-a006-c488f18996a6.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/24d09f89-a7f4-4e7d-8f79-e4f54ebc55eb.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/26b58a14-5c75-4023-afeb-eb20e1b569ee.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/28e19428-a828-4df3-9dfb-e9ab882c3683.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/32e755a6-1ab0-4c89-907b-bfefac8706d2.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/72c61657-acfe-4840-bf4c-b0cf47052a2f.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/162d6c8c-7c1d-4259-9b31-571b4c0ab041.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/188a0155-0744-4514-8336-6079c21751c8.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/4119a3d8-a2b1-48f6-bd51-506c84f8b084.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/28866fda-250b-47fa-9774-20c78c109bb5.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/0077207b-a265-482a-90a4-f909bf3a9ad7.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/905613a9-862e-4214-bf45-acf111e8dceb.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/8884892b-76f4-42c3-ba96-6d04f5c38778.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/9237305a-74a9-4b19-b26f-e03714f2b18f.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/11266291-4105-498f-bd49-a0df775c85a1.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/abf17ff5-98ce-4134-85d4-8c6f65f46b06.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/aff01864-c05c-4197-994a-b5780071f27c.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/b4a3fa2e-d540-4916-8ad9-32b1ffd42299.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/c68562d9-ef99-4d2f-99b5-7c073ef04751.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/caed1d9c-1fc5-4514-af4a-c0421c54616f.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/e4dd4be1-9db0-46ee-8b86-2beed8a8782b.jpg'),
(127,	'',	NULL,	'Musical Ice Fountain',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	2.99,	0.00,	0.00,	2.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'21.jpg',	'21.jpg',	'21.jpg',	'21.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'musical-ice-fountain',	NULL,	NULL,	NULL,	113575,	'https://bbakery.co.uk/wp-content/uploads/2020/07/21.jpg'),
(128,	'',	NULL,	'Festa Clip Fountain',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'12.jpg',	'12.jpg',	'12.jpg',	'12.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'festa-clip-fountain',	NULL,	NULL,	NULL,	113576,	'https://bbakery.co.uk/wp-content/uploads/2020/07/12.jpg'),
(129,	'',	NULL,	'It\'s a Boy Fountain',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'9-1.jpg',	'9-1.jpg',	'9-1.jpg',	'9-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'it-s-a-boy-fountain',	NULL,	NULL,	NULL,	113577,	'https://bbakery.co.uk/wp-content/uploads/2020/07/9-1.jpg'),
(130,	'',	NULL,	'It\'s a Girl Fountain',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'43.jpg',	'43.jpg',	'43.jpg',	'43.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'it-s-a-girl-fountain',	NULL,	NULL,	NULL,	113578,	'https://bbakery.co.uk/wp-content/uploads/2020/07/43.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/870bc61e-286c-43ee-b956-06187927eb60.jpg'),
(131,	'',	NULL,	'Football Fountain',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'42.jpg',	'42.jpg',	'42.jpg',	'42.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'football-fountain',	NULL,	NULL,	NULL,	113579,	'https://bbakery.co.uk/wp-content/uploads/2020/07/42.jpg'),
(132,	'',	NULL,	'Super Sparkler Pack',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'1-1.jpg',	'1-1.jpg',	'1-1.jpg',	'1-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'super-sparkler-pack',	NULL,	NULL,	NULL,	113580,	'https://bbakery.co.uk/wp-content/uploads/2020/07/1-1.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/4ec0dca5-79f9-4086-8f0e-7c8b4c7657b2.jpg'),
(133,	'',	NULL,	'10 Birthday Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.50,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'25.jpg',	'25.jpg',	'25.jpg',	'25.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'10-birthday-candles',	NULL,	NULL,	NULL,	113584,	'https://bbakery.co.uk/wp-content/uploads/2020/07/25.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/b0162559-3c45-4c96-a72f-7f4d90e749b2.jpg'),
(134,	'',	NULL,	'Ice Fountain + 12 Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'39.jpg',	'39.jpg',	'39.jpg',	'39.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'ice-fountain-12-candles',	NULL,	NULL,	NULL,	113586,	'https://bbakery.co.uk/wp-content/uploads/2020/07/39.jpg'),
(135,	'',	NULL,	'Heart-shaped Stelline Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	4.99,	0.00,	0.00,	4.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'13.jpg',	'13.jpg',	'13.jpg',	'13.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'heart-shaped-stelline-sparklers',	NULL,	NULL,	NULL,	113587,	'https://bbakery.co.uk/wp-content/uploads/2020/07/13.jpg'),
(136,	'',	NULL,	'Colourful Sparkler Birthday Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	1.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'40.jpg',	'40.jpg',	'40.jpg',	'40.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'colourful-sparkler-birthday-candles',	NULL,	NULL,	NULL,	113588,	'https://bbakery.co.uk/wp-content/uploads/2020/07/40.jpg'),
(137,	'',	NULL,	'Gold Sparkler Birthday Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	1.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'gold-sparkler-birthday-candles',	NULL,	NULL,	NULL,	113589,	'https://bbakery.co.uk/wp-content/uploads/2020/07/11266291-4105-498f-bd49-a0df775c85a1.jpg'),
(138,	'',	NULL,	'Silver Big Stelline Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	1.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'31.jpg',	'31.jpg',	'31.jpg',	'31.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'silver-big-stelline-sparklers',	NULL,	NULL,	NULL,	113590,	'https://bbakery.co.uk/wp-content/uploads/2020/07/31.jpg'),
(139,	'',	NULL,	'Ice Fountain + 2 Heart Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'27.jpg',	'27.jpg',	'27.jpg',	'27.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'ice-fountain-2-heart-candles',	NULL,	NULL,	NULL,	113591,	'https://bbakery.co.uk/wp-content/uploads/2020/07/27.jpg'),
(140,	'',	NULL,	'20 Small Party Poppers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	4.99,	0.00,	0.00,	4.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'38.jpg',	'38.jpg',	'38.jpg',	'38.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'20-small-party-poppers',	NULL,	NULL,	NULL,	113598,	'https://bbakery.co.uk/wp-content/uploads/2020/07/38.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/25a609a2-ba3d-475d-a92c-d35a71e6b458.jpg'),
(141,	'',	NULL,	'3 Twist Poppers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	5.00,	0.00,	0.00,	5.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'23.jpg',	'23.jpg',	'23.jpg',	'23.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'3-twist-poppers',	NULL,	NULL,	NULL,	113601,	'https://bbakery.co.uk/wp-content/uploads/2020/07/23.jpg'),
(142,	'',	NULL,	'50 Indoor Fireworks',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	9.99,	0.00,	0.00,	9.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'34.jpg',	'34.jpg',	'34.jpg',	'34.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'50-indoor-fireworks',	NULL,	NULL,	NULL,	113602,	'https://bbakery.co.uk/wp-content/uploads/2020/07/34.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/4119a3d8-a2b1-48f6-bd51-506c84f8b084-1.jpg'),
(143,	'',	NULL,	'Mini Table Bombs',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	8.00,	0.00,	0.00,	8.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'20.jpg',	'20.jpg',	'20.jpg',	'20.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'mini-table-bombs',	NULL,	NULL,	NULL,	113603,	'https://bbakery.co.uk/wp-content/uploads/2020/07/20.jpg'),
(144,	'',	NULL,	'Fumogeno Nero Black',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	7.99,	0.00,	0.00,	7.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'22.jpg',	'22.jpg',	'22.jpg',	'22.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fumogeno-nero-black',	NULL,	NULL,	NULL,	113604,	'https://bbakery.co.uk/wp-content/uploads/2020/07/22.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/Black-smoke-bomb.jpg'),
(145,	'',	NULL,	'Fumogeno Rosa Pink',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	7.99,	0.00,	0.00,	7.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'17.jpg',	'17.jpg',	'17.jpg',	'17.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fumogeno-rosa-pink',	NULL,	NULL,	NULL,	113606,	'https://bbakery.co.uk/wp-content/uploads/2020/07/17.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/pink-smoke-bomb.jpg'),
(146,	'',	NULL,	'Fumogeno Bianco White',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	7.99,	0.00,	0.00,	7.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'15.jpg',	'15.jpg',	'15.jpg',	'15.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fumogeno-bianco-white',	NULL,	NULL,	NULL,	113607,	'https://bbakery.co.uk/wp-content/uploads/2020/07/15.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/White-Smoke-Bomb.jpg'),
(147,	'',	NULL,	'Fumogeno Verde Green',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	7.99,	0.00,	0.00,	7.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'14.jpg',	'14.jpg',	'14.jpg',	'14.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fumogeno-verde-green',	NULL,	NULL,	NULL,	113608,	'https://bbakery.co.uk/wp-content/uploads/2020/07/14.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/green.jpg'),
(148,	'',	NULL,	'Fumogeno Giallo Yellow',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	7.99,	0.00,	0.00,	7.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'28.jpg',	'28.jpg',	'28.jpg',	'28.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fumogeno-giallo-yellow',	NULL,	NULL,	NULL,	113609,	'https://bbakery.co.uk/wp-content/uploads/2020/07/28.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/Yellow-Smoke-BomB.jpg'),
(149,	'',	NULL,	'Gold Number Birthday Candle',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'41.jpg',	'41.jpg',	'41.jpg',	'41.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'gold-number-birthday-candle',	NULL,	NULL,	NULL,	113610,	'https://bbakery.co.uk/wp-content/uploads/2020/07/41.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/31b7428c-efd7-4066-a238-5213277b863d.jpg'),
(150,	'',	NULL,	'Blue Number Birthday Candle',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'32.jpg',	'32.jpg',	'32.jpg',	'32.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'blue-number-birthday-candle',	NULL,	NULL,	NULL,	113612,	'https://bbakery.co.uk/wp-content/uploads/2020/07/32.jpg'),
(151,	'',	NULL,	'Pink Number Birthday Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'7-1.jpg',	'7-1.jpg',	'7-1.jpg',	'7-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'pink-number-birthday-candles',	NULL,	NULL,	NULL,	113614,	'https://bbakery.co.uk/wp-content/uploads/2020/07/7-1.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/15d13205-b6ba-43c7-a006-c488f18996a6-1.jpg'),
(152,	'',	NULL,	'Small Gold Birthday Candle Bougie',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'45.jpg',	'45.jpg',	'45.jpg',	'45.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'small-gold-birthday-candle-bougie',	NULL,	NULL,	NULL,	113616,	'https://bbakery.co.uk/wp-content/uploads/2020/07/45.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/06b6e0fe-2505-49a2-938b-7246c21c4518-1.jpg'),
(153,	'',	NULL,	'Glowing Party Necklace',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'glowing-party-necklace',	NULL,	NULL,	NULL,	113620,	'https://bbakery.co.uk/wp-content/uploads/2020/07/f9131b98-aa58-4856-8af5-018af96748fc.jpg'),
(154,	'',	NULL,	'Double-Sided Number Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	2.00,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'2-1.jpg',	'2-1.jpg',	'2-1.jpg',	'2-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'double-sided-number-candles',	NULL,	NULL,	NULL,	113621,	'https://bbakery.co.uk/wp-content/uploads/2020/07/2-1.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/3bb950c7-b753-4716-9925-d9eae246bc09-1.jpg'),
(155,	'',	NULL,	'12 Mix-Coloured Birthday Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1.50,	0.00,	0.00,	1.50,	NULL,	NULL,	NULL,	NULL,	NULL,	'44.jpg',	'44.jpg',	'44.jpg',	'44.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'12-mix-coloured-birthday-candles',	NULL,	NULL,	NULL,	113625,	'https://bbakery.co.uk/wp-content/uploads/2020/07/44.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3-1.jpg'),
(156,	'',	NULL,	'Fountain Clip',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	3.99,	NULL,	NULL,	NULL,	NULL,	NULL,	'30.jpg',	'30.jpg',	'30.jpg',	'30.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'fountain-clip',	NULL,	NULL,	NULL,	113626,	'https://bbakery.co.uk/wp-content/uploads/2020/07/30.jpg'),
(157,	'',	NULL,	'Party Cannon',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	11.00,	0.00,	0.00,	11.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'18.jpg',	'18.jpg',	'18.jpg',	'18.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'party-cannon',	NULL,	NULL,	NULL,	113628,	'https://bbakery.co.uk/wp-content/uploads/2020/07/18.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/fe6b249b-7ae1-4fa1-ae8f-6243552765db.jpg'),
(158,	'',	NULL,	'70 cm Wedding Sparklers',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	11.00,	0.00,	0.00,	11.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'4-1.jpg',	'4-1.jpg',	'4-1.jpg',	'4-1.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'70-cm-wedding-sparklers',	NULL,	NULL,	NULL,	113629,	'https://bbakery.co.uk/wp-content/uploads/2020/07/4-1.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/1dfe6da6-a5da-4dd1-93af-ae99a12ddcc1.jpg'),
(159,	'',	NULL,	'24 Birthday Candles',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	2.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'24.jpg',	'24.jpg',	'24.jpg',	'24.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'24-birthday-candles',	NULL,	NULL,	NULL,	113694,	'https://bbakery.co.uk/wp-content/uploads/2020/07/24.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/c91a76b6-18e6-4691-85d0-bb6c40ec96a2.jpg'),
(160,	'',	NULL,	'Party Glow Sticks',	0,	NULL,	'',	NULL,	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	3.99,	0.00,	0.00,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	'33.jpg',	'33.jpg',	'33.jpg',	'33.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	23,	NULL,	0,	99,	1,	0,	NULL,	0,	'',	0,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	1,	NULL,	'',	0,	'party-glow-sticks',	NULL,	NULL,	NULL,	113697,	'https://bbakery.co.uk/wp-content/uploads/2020/07/33.jpg|https://bbakery.co.uk/wp-content/uploads/2020/07/4834a22b-8897-43a2-b8df-216ba254527c.jpg'),
(161,	'',	NULL,	'tests',	0,	NULL,	'1',	NULL,	'',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	12.00,	0.00,	NULL,	25.00,	'',	'',	NULL,	NULL,	'N;',	'gd_1458213804_web_6_.jpg',	'1458213804_web_6_.jpg',	'md_1458213804_web_6_.jpg',	'pq_1458213804_web_6_.jpg',	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	1,	202,	100.000,	5,	'3',	0,	1,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'5',	'3',	0,	'test',	'tests',	NULL,	NULL,	NULL,	NULL),
(162,	'Happy Mothers Day Cake 1',	NULL,	'Happy Mothers Day ',	0,	NULL,	'1',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	33.00,	0.00,	NULL,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	0,	0,	0.000,	0,	NULL,	0,	NULL,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	1,	'',	'',	0,	'happy-mothers-day',	'Happy Mothers Day ',	NULL,	NULL,	NULL,	NULL),
(163,	'test sq 1',	NULL,	'test sq 1',	0,	NULL,	'1',	NULL,	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	NULL,	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	0,	0,	NULL,	NULL,	0,	NULL,	NULL,	NULL,	NULL,	0,	NULL,	NULL,	0,	20,	NULL,	0,	99,	0,	0,	0.000,	0,	NULL,	0,	NULL,	'8',	'0',	'0',	'0',	'7',	'0',	'0',	'0',	0,	0,	'',	'',	0,	'test-sq-1',	'test sq 1',	NULL,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `l_pecas_filtros`;
CREATE TABLE `l_pecas_filtros` (
  `id` bigint(20) NOT NULL,
  `id_peca` int(11) DEFAULT NULL,
  `id_filtro` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_peca`,`id_filtro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_imagens`;
CREATE TABLE `l_pecas_imagens` (
  `id` bigint(20) NOT NULL,
  `id_peca` int(11) DEFAULT NULL,
  `id_tamanho` bigint(20) DEFAULT '0',
  `imagem1` varchar(250) DEFAULT NULL,
  `imagem2` varchar(250) DEFAULT NULL,
  `imagem3` varchar(250) DEFAULT NULL,
  `imagem4` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `video1` tinytext,
  `tipo` int(11) DEFAULT NULL,
  `nome` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_peca`,`id_tamanho`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_pecas_imagens` (`id`, `id_peca`, `id_tamanho`, `imagem1`, `imagem2`, `imagem3`, `imagem4`, `ordem`, `visivel`, `video1`, `tipo`, `nome`) VALUES
(1,	1,	0,	'18.jpg',	'18.jpg',	'18.jpg',	'18.jpg',	99,	1,	NULL,	NULL,	NULL),
(2,	1,	0,	'1.jpg',	'1.jpg',	'1.jpg',	'1.jpg',	99,	1,	NULL,	NULL,	NULL),
(3,	2,	0,	'8.jpg',	'8.jpg',	'8.jpg',	'8.jpg',	99,	1,	NULL,	NULL,	NULL),
(4,	3,	0,	'8.jpg',	'8.jpg',	'8.jpg',	'8.jpg',	99,	1,	NULL,	NULL,	NULL),
(5,	4,	0,	'7.jpg',	'7.jpg',	'7.jpg',	'7.jpg',	99,	1,	NULL,	NULL,	NULL),
(6,	5,	0,	'15.jpg',	'15.jpg',	'15.jpg',	'15.jpg',	99,	1,	NULL,	NULL,	NULL),
(7,	6,	0,	'19.jpg',	'19.jpg',	'19.jpg',	'19.jpg',	99,	1,	NULL,	NULL,	NULL),
(8,	7,	0,	'9.jpg',	'9.jpg',	'9.jpg',	'9.jpg',	99,	1,	NULL,	NULL,	NULL),
(9,	8,	0,	'10.jpg',	'10.jpg',	'10.jpg',	'10.jpg',	99,	1,	NULL,	NULL,	NULL),
(10,	9,	0,	'products-37598-1-1.jpg',	'products-37598-1-1.jpg',	'products-37598-1-1.jpg',	'products-37598-1-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(11,	10,	0,	'products-37597-1-1.jpg',	'products-37597-1-1.jpg',	'products-37597-1-1.jpg',	'products-37597-1-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(12,	11,	0,	'products-37599-1-1.jpg',	'products-37599-1-1.jpg',	'products-37599-1-1.jpg',	'products-37599-1-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(13,	12,	0,	'products-_0.30-1.jpg',	'products-_0.30-1.jpg',	'products-_0.30-1.jpg',	'products-_0.30-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(14,	13,	0,	'products-37596-1-1.jpg',	'products-37596-1-1.jpg',	'products-37596-1-1.jpg',	'products-37596-1-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(15,	14,	0,	'B120.png',	'B120.png',	'B120.png',	'B120.png',	99,	1,	NULL,	NULL,	NULL),
(16,	15,	0,	'B15.jpg',	'B15.jpg',	'B15.jpg',	'B15.jpg',	99,	1,	NULL,	NULL,	NULL),
(17,	16,	0,	'B170.png',	'B170.png',	'B170.png',	'B170.png',	99,	1,	NULL,	NULL,	NULL),
(18,	17,	0,	'B171.png',	'B171.png',	'B171.png',	'B171.png',	99,	1,	NULL,	NULL,	NULL),
(19,	18,	0,	'B172.png',	'B172.png',	'B172.png',	'B172.png',	99,	1,	NULL,	NULL,	NULL),
(20,	19,	0,	'B119.png',	'B119.png',	'B119.png',	'B119.png',	99,	1,	NULL,	NULL,	NULL),
(21,	20,	0,	'ISP33.png',	'ISP33.png',	'ISP33.png',	'ISP33.png',	99,	1,	NULL,	NULL,	NULL),
(22,	21,	0,	'B122.png',	'B122.png',	'B122.png',	'B122.png',	99,	1,	NULL,	NULL,	NULL),
(23,	22,	0,	'01eid.jpg',	'01eid.jpg',	'01eid.jpg',	'01eid.jpg',	99,	1,	NULL,	NULL,	NULL),
(24,	23,	0,	'products-m23034gld-1.jpg',	'products-m23034gld-1.jpg',	'products-m23034gld-1.jpg',	'products-m23034gld-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(25,	24,	0,	'products-m23034sil-1.jpg',	'products-m23034sil-1.jpg',	'products-m23034sil-1.jpg',	'products-m23034sil-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(26,	25,	0,	'products-w98-1.jpg',	'products-w98-1.jpg',	'products-w98-1.jpg',	'products-w98-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(27,	26,	0,	'products-b29-1.jpg',	'products-b29-1.jpg',	'products-b29-1.jpg',	'products-b29-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(28,	27,	0,	'products-img_7211-1.png',	'products-img_7211-1.png',	'products-img_7211-1.png',	'products-img_7211-1.png',	99,	1,	NULL,	NULL,	NULL),
(29,	28,	0,	'products-b39_grande-1.jpg',	'products-b39_grande-1.jpg',	'products-b39_grande-1.jpg',	'products-b39_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(30,	29,	0,	'products-b36_grande-1.jpg',	'products-b36_grande-1.jpg',	'products-b36_grande-1.jpg',	'products-b36_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(31,	30,	0,	'products-b33_grande-1.jpg',	'products-b33_grande-1.jpg',	'products-b33_grande-1.jpg',	'products-b33_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(32,	31,	0,	'products-b38_grande-1.jpg',	'products-b38_grande-1.jpg',	'products-b38_grande-1.jpg',	'products-b38_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(33,	32,	0,	'products-w287_grande-1.jpg',	'products-w287_grande-1.jpg',	'products-w287_grande-1.jpg',	'products-w287_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(34,	33,	0,	'products-img_6768_grande-1.png',	'products-img_6768_grande-1.png',	'products-img_6768_grande-1.png',	'products-img_6768_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(35,	34,	0,	'products-w01_grande-1.jpg',	'products-w01_grande-1.jpg',	'products-w01_grande-1.jpg',	'products-w01_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(36,	35,	0,	'products-w02_grande-1.jpg',	'products-w02_grande-1.jpg',	'products-w02_grande-1.jpg',	'products-w02_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(37,	36,	0,	'products-93_grande-1.jpg',	'products-93_grande-1.jpg',	'products-93_grande-1.jpg',	'products-93_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(38,	37,	0,	'products-oocss_grande-1.jpg',	'products-oocss_grande-1.jpg',	'products-oocss_grande-1.jpg',	'products-oocss_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(39,	38,	0,	'products-img_6298_grande-1.png',	'products-img_6298_grande-1.png',	'products-img_6298_grande-1.png',	'products-img_6298_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(40,	39,	0,	'products-img_7615_grande-1.png',	'products-img_7615_grande-1.png',	'products-img_7615_grande-1.png',	'products-img_7615_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(41,	40,	0,	'products-w84_d_grande-1.jpg',	'products-w84_d_grande-1.jpg',	'products-w84_d_grande-1.jpg',	'products-w84_d_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(42,	41,	0,	'products-csssa_grande-1.jpg',	'products-csssa_grande-1.jpg',	'products-csssa_grande-1.jpg',	'products-csssa_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(43,	42,	0,	'products-w91_grande-1.jpg',	'products-w91_grande-1.jpg',	'products-w91_grande-1.jpg',	'products-w91_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(44,	43,	0,	'products-w08_grande-1.jpg',	'products-w08_grande-1.jpg',	'products-w08_grande-1.jpg',	'products-w08_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(45,	44,	0,	'products-img_5062_grande-1.png',	'products-img_5062_grande-1.png',	'products-img_5062_grande-1.png',	'products-img_5062_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(46,	45,	0,	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	'products-img-20150610-wa0031_a7ccfe72-a59c-4cee-a53d-5701885c4b90_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(47,	46,	0,	'products-img_7178_grande-1.png',	'products-img_7178_grande-1.png',	'products-img_7178_grande-1.png',	'products-img_7178_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(48,	47,	0,	'products-w78_grande.jpg',	'products-w78_grande.jpg',	'products-w78_grande.jpg',	'products-w78_grande.jpg',	99,	1,	NULL,	NULL,	NULL),
(49,	48,	0,	'products-img_8741_grande-1.jpg',	'products-img_8741_grande-1.jpg',	'products-img_8741_grande-1.jpg',	'products-img_8741_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(50,	49,	0,	'products-w07_grande-1.jpg',	'products-w07_grande-1.jpg',	'products-w07_grande-1.jpg',	'products-w07_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(51,	50,	0,	'products-w99_grande-1.jpg',	'products-w99_grande-1.jpg',	'products-w99_grande-1.jpg',	'products-w99_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(52,	51,	0,	'products-img_3815_grande-1.jpg',	'products-img_3815_grande-1.jpg',	'products-img_3815_grande-1.jpg',	'products-img_3815_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(53,	52,	0,	'products-w04_grande-1.jpg',	'products-w04_grande-1.jpg',	'products-w04_grande-1.jpg',	'products-w04_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(54,	53,	0,	'products-130_grande-1.jpg',	'products-130_grande-1.jpg',	'products-130_grande-1.jpg',	'products-130_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(55,	54,	0,	'products-22.1.14_704_grande-1.jpg',	'products-22.1.14_704_grande-1.jpg',	'products-22.1.14_704_grande-1.jpg',	'products-22.1.14_704_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(56,	55,	0,	'products-w03_grande-1.jpg',	'products-w03_grande-1.jpg',	'products-w03_grande-1.jpg',	'products-w03_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(57,	56,	0,	'products-jijgfdf_grande-1.jpg',	'products-jijgfdf_grande-1.jpg',	'products-jijgfdf_grande-1.jpg',	'products-jijgfdf_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(58,	57,	0,	'products-img_6370_grande-1.png',	'products-img_6370_grande-1.png',	'products-img_6370_grande-1.png',	'products-img_6370_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(59,	58,	0,	'products-164_grande-1.jpg',	'products-164_grande-1.jpg',	'products-164_grande-1.jpg',	'products-164_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(60,	59,	0,	'products-img_7359_grande-1.png',	'products-img_7359_grande-1.png',	'products-img_7359_grande-1.png',	'products-img_7359_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(61,	60,	0,	'products-138_grande-1.jpg',	'products-138_grande-1.jpg',	'products-138_grande-1.jpg',	'products-138_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(62,	61,	0,	'products-img_7130_grande-1.png',	'products-img_7130_grande-1.png',	'products-img_7130_grande-1.png',	'products-img_7130_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(63,	62,	0,	'products-rrrgg_grande-1.jpg',	'products-rrrgg_grande-1.jpg',	'products-rrrgg_grande-1.jpg',	'products-rrrgg_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(64,	63,	0,	'products-vdssaa_grande-1.jpg',	'products-vdssaa_grande-1.jpg',	'products-vdssaa_grande-1.jpg',	'products-vdssaa_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(65,	64,	0,	'products-img_7155_grande-1.png',	'products-img_7155_grande-1.png',	'products-img_7155_grande-1.png',	'products-img_7155_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(66,	65,	0,	'products-187_grande-1.jpg',	'products-187_grande-1.jpg',	'products-187_grande-1.jpg',	'products-187_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(67,	66,	0,	'products-18_grande-1.jpg',	'products-18_grande-1.jpg',	'products-18_grande-1.jpg',	'products-18_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(68,	67,	0,	'products-101_keep_bg_grande-1.jpg',	'products-101_keep_bg_grande-1.jpg',	'products-101_keep_bg_grande-1.jpg',	'products-101_keep_bg_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(69,	68,	0,	'products-62_keep_bg_grande-1.jpg',	'products-62_keep_bg_grande-1.jpg',	'products-62_keep_bg_grande-1.jpg',	'products-62_keep_bg_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(70,	69,	0,	'products-142_keep_bg_grande-1.jpg',	'products-142_keep_bg_grande-1.jpg',	'products-142_keep_bg_grande-1.jpg',	'products-142_keep_bg_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(71,	70,	0,	'products-w06_grande-1.jpg',	'products-w06_grande-1.jpg',	'products-w06_grande-1.jpg',	'products-w06_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(72,	71,	0,	'products-dsc_7459_grande-1.jpg',	'products-dsc_7459_grande-1.jpg',	'products-dsc_7459_grande-1.jpg',	'products-dsc_7459_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(73,	72,	0,	'products-img_8319_grande-1.png',	'products-img_8319_grande-1.png',	'products-img_8319_grande-1.png',	'products-img_8319_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(74,	73,	0,	'products-dsc_3223_grande-1.jpg',	'products-dsc_3223_grande-1.jpg',	'products-dsc_3223_grande-1.jpg',	'products-dsc_3223_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(75,	74,	0,	'products-w73_grande-1.jpg',	'products-w73_grande-1.jpg',	'products-w73_grande-1.jpg',	'products-w73_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(76,	75,	0,	'products-w90_grande-1.jpg',	'products-w90_grande-1.jpg',	'products-w90_grande-1.jpg',	'products-w90_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(77,	76,	0,	'products-img_7701_grande-1.jpg',	'products-img_7701_grande-1.jpg',	'products-img_7701_grande-1.jpg',	'products-img_7701_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(78,	77,	0,	'products-wedding_cake_fo_cov_road_grande-1.png',	'products-wedding_cake_fo_cov_road_grande-1.png',	'products-wedding_cake_fo_cov_road_grande-1.png',	'products-wedding_cake_fo_cov_road_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(79,	78,	0,	'products-w92_grande-1.jpg',	'products-w92_grande-1.jpg',	'products-w92_grande-1.jpg',	'products-w92_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(80,	79,	0,	'products-kokohff_grande-1.jpg',	'products-kokohff_grande-1.jpg',	'products-kokohff_grande-1.jpg',	'products-kokohff_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(81,	80,	0,	'products-w103_grande-1.jpg',	'products-w103_grande-1.jpg',	'products-w103_grande-1.jpg',	'products-w103_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(82,	81,	0,	'products-w104_grande-1.jpg',	'products-w104_grande-1.jpg',	'products-w104_grande-1.jpg',	'products-w104_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(83,	82,	0,	'products-w105_grande-1.jpg',	'products-w105_grande-1.jpg',	'products-w105_grande-1.jpg',	'products-w105_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(84,	83,	0,	'products-w96_grande-1.jpg',	'products-w96_grande-1.jpg',	'products-w96_grande-1.jpg',	'products-w96_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(85,	84,	0,	'products-w97_grande-1.jpg',	'products-w97_grande-1.jpg',	'products-w97_grande-1.jpg',	'products-w97_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(86,	85,	0,	'products-img_8735_grande-1.jpg',	'products-img_8735_grande-1.jpg',	'products-img_8735_grande-1.jpg',	'products-img_8735_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(87,	86,	0,	'products-w87_grande-1.jpg',	'products-w87_grande-1.jpg',	'products-w87_grande-1.jpg',	'products-w87_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(88,	87,	0,	'products-w104_grande_2-1.jpg',	'products-w104_grande_2-1.jpg',	'products-w104_grande_2-1.jpg',	'products-w104_grande_2-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(89,	88,	0,	'products-w25_grande-1.jpg',	'products-w25_grande-1.jpg',	'products-w25_grande-1.jpg',	'products-w25_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(90,	89,	0,	'products-w76_grande-1.jpg',	'products-w76_grande-1.jpg',	'products-w76_grande-1.jpg',	'products-w76_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(91,	90,	0,	'products-img_7702_grande-1.png',	'products-img_7702_grande-1.png',	'products-img_7702_grande-1.png',	'products-img_7702_grande-1.png',	99,	1,	NULL,	NULL,	NULL),
(92,	91,	0,	'products-dvdd_grande-1.jpg',	'products-dvdd_grande-1.jpg',	'products-dvdd_grande-1.jpg',	'products-dvdd_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(93,	92,	0,	'products-w89_grande-1.jpg',	'products-w89_grande-1.jpg',	'products-w89_grande-1.jpg',	'products-w89_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(94,	93,	0,	'products-w77_grande-1.jpg',	'products-w77_grande-1.jpg',	'products-w77_grande-1.jpg',	'products-w77_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(95,	94,	0,	'products-w101_grande-1.jpg',	'products-w101_grande-1.jpg',	'products-w101_grande-1.jpg',	'products-w101_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(96,	95,	0,	'products-w102_grande-1.jpg',	'products-w102_grande-1.jpg',	'products-w102_grande-1.jpg',	'products-w102_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(97,	96,	0,	'products-w109_grande-1.jpg',	'products-w109_grande-1.jpg',	'products-w109_grande-1.jpg',	'products-w109_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(98,	97,	0,	'products-dcwqdwqwqd_grande-1.jpg',	'products-dcwqdwqwqd_grande-1.jpg',	'products-dcwqdwqwqd_grande-1.jpg',	'products-dcwqdwqwqd_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(99,	98,	0,	'products-w80_grande-1.jpg',	'products-w80_grande-1.jpg',	'products-w80_grande-1.jpg',	'products-w80_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(100,	99,	0,	'products-wedding_700_grande-1.jpg',	'products-wedding_700_grande-1.jpg',	'products-wedding_700_grande-1.jpg',	'products-wedding_700_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(101,	100,	0,	'products-shopify_template_for_pics_w85_grande-2.jpg',	'products-shopify_template_for_pics_w85_grande-2.jpg',	'products-shopify_template_for_pics_w85_grande-2.jpg',	'products-shopify_template_for_pics_w85_grande-2.jpg',	99,	1,	NULL,	NULL,	NULL),
(102,	101,	0,	'products-w107_grande-1.jpg',	'products-w107_grande-1.jpg',	'products-w107_grande-1.jpg',	'products-w107_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(103,	102,	0,	'products-w106_grande-318.jpg',	'products-w106_grande-318.jpg',	'products-w106_grande-318.jpg',	'products-w106_grande-318.jpg',	99,	1,	NULL,	NULL,	NULL),
(104,	103,	0,	'products-w95_grande-1.jpg',	'products-w95_grande-1.jpg',	'products-w95_grande-1.jpg',	'products-w95_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(105,	104,	0,	'products-mmo_grande_2-1.jpg',	'products-mmo_grande_2-1.jpg',	'products-mmo_grande_2-1.jpg',	'products-mmo_grande_2-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(106,	105,	0,	'products-w108_grande-1.jpg',	'products-w108_grande-1.jpg',	'products-w108_grande-1.jpg',	'products-w108_grande-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(107,	105,	0,	'GOLD-2.jpg',	'GOLD-2.jpg',	'GOLD-2.jpg',	'GOLD-2.jpg',	99,	1,	NULL,	NULL,	NULL),
(108,	105,	0,	'CUPCAKE-CUPCAKE-TOPPER-CUPCAKE-STAND.jpg',	'CUPCAKE-CUPCAKE-TOPPER-CUPCAKE-STAND.jpg',	'CUPCAKE-CUPCAKE-TOPPER-CUPCAKE-STAND.jpg',	'CUPCAKE-CUPCAKE-TOPPER-CUPCAKE-STAND.jpg',	99,	1,	NULL,	NULL,	NULL),
(109,	106,	0,	'hdb_cake1-1.jpeg',	'hdb_cake1-1.jpeg',	'hdb_cake1-1.jpeg',	'hdb_cake1-1.jpeg',	99,	1,	NULL,	NULL,	NULL),
(110,	107,	0,	'hdb_cake2-1.jpeg',	'hdb_cake2-1.jpeg',	'hdb_cake2-1.jpeg',	'hdb_cake2-1.jpeg',	99,	1,	NULL,	NULL,	NULL),
(111,	108,	0,	'hdb_cake3-1.jpeg',	'hdb_cake3-1.jpeg',	'hdb_cake3-1.jpeg',	'hdb_cake3-1.jpeg',	99,	1,	NULL,	NULL,	NULL),
(112,	109,	0,	'Ramadan.jpg',	'Ramadan.jpg',	'Ramadan.jpg',	'Ramadan.jpg',	99,	1,	NULL,	NULL,	NULL),
(113,	110,	0,	'Iftar.jpg',	'Iftar.jpg',	'Iftar.jpg',	'Iftar.jpg',	99,	1,	NULL,	NULL,	NULL),
(114,	111,	0,	'4.jpg',	'4.jpg',	'4.jpg',	'4.jpg',	99,	1,	NULL,	NULL,	NULL),
(115,	112,	0,	'3.jpg',	'3.jpg',	'3.jpg',	'3.jpg',	99,	1,	NULL,	NULL,	NULL),
(116,	113,	0,	'1.jpg',	'1.jpg',	'1.jpg',	'1.jpg',	99,	1,	NULL,	NULL,	NULL),
(117,	114,	0,	'2.jpg',	'2.jpg',	'2.jpg',	'2.jpg',	99,	1,	NULL,	NULL,	NULL),
(118,	116,	0,	'1.jpg',	'1.jpg',	'1.jpg',	'1.jpg',	99,	1,	NULL,	NULL,	NULL),
(119,	117,	0,	'2.jpg',	'2.jpg',	'2.jpg',	'2.jpg',	99,	1,	NULL,	NULL,	NULL),
(120,	118,	0,	'3.jpg',	'3.jpg',	'3.jpg',	'3.jpg',	99,	1,	NULL,	NULL,	NULL),
(121,	119,	0,	'4.jpg',	'4.jpg',	'4.jpg',	'4.jpg',	99,	1,	NULL,	NULL,	NULL),
(122,	120,	0,	'Blue-sparklers.jpg',	'Blue-sparklers.jpg',	'Blue-sparklers.jpg',	'Blue-sparklers.jpg',	99,	1,	NULL,	NULL,	NULL),
(123,	121,	0,	'golden.jpg',	'golden.jpg',	'golden.jpg',	'golden.jpg',	99,	1,	NULL,	NULL,	NULL),
(124,	122,	0,	'pink-1.jpg',	'pink-1.jpg',	'pink-1.jpg',	'pink-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(125,	123,	0,	'silver.jpg',	'silver.jpg',	'silver.jpg',	'silver.jpg',	99,	1,	NULL,	NULL,	NULL),
(126,	124,	0,	'5-monster-sparkles.jpg',	'5-monster-sparkles.jpg',	'5-monster-sparkles.jpg',	'5-monster-sparkles.jpg',	99,	1,	NULL,	NULL,	NULL),
(127,	125,	0,	'35.jpg',	'35.jpg',	'35.jpg',	'35.jpg',	99,	1,	NULL,	NULL,	NULL),
(128,	125,	0,	'1404cada-d183-406a-bce9-2ec8502c34fc.jpg',	'1404cada-d183-406a-bce9-2ec8502c34fc.jpg',	'1404cada-d183-406a-bce9-2ec8502c34fc.jpg',	'1404cada-d183-406a-bce9-2ec8502c34fc.jpg',	99,	1,	NULL,	NULL,	NULL),
(129,	126,	0,	'26.jpg',	'26.jpg',	'26.jpg',	'26.jpg',	99,	1,	NULL,	NULL,	NULL),
(130,	126,	0,	'0d407d9f-a4e8-4134-ad55-825a69bcd71b.jpg',	'0d407d9f-a4e8-4134-ad55-825a69bcd71b.jpg',	'0d407d9f-a4e8-4134-ad55-825a69bcd71b.jpg',	'0d407d9f-a4e8-4134-ad55-825a69bcd71b.jpg',	99,	1,	NULL,	NULL,	NULL),
(131,	126,	0,	'0fe7d3d8-6184-4d58-866f-7a0858d9df69.jpg',	'0fe7d3d8-6184-4d58-866f-7a0858d9df69.jpg',	'0fe7d3d8-6184-4d58-866f-7a0858d9df69.jpg',	'0fe7d3d8-6184-4d58-866f-7a0858d9df69.jpg',	99,	1,	NULL,	NULL,	NULL),
(132,	126,	0,	'2eaeb7e2-0106-4fa4-bd0a-6e559633a3de.jpg',	'2eaeb7e2-0106-4fa4-bd0a-6e559633a3de.jpg',	'2eaeb7e2-0106-4fa4-bd0a-6e559633a3de.jpg',	'2eaeb7e2-0106-4fa4-bd0a-6e559633a3de.jpg',	99,	1,	NULL,	NULL,	NULL),
(133,	126,	0,	'3bb950c7-b753-4716-9925-d9eae246bc09.jpg',	'3bb950c7-b753-4716-9925-d9eae246bc09.jpg',	'3bb950c7-b753-4716-9925-d9eae246bc09.jpg',	'3bb950c7-b753-4716-9925-d9eae246bc09.jpg',	99,	1,	NULL,	NULL,	NULL),
(134,	126,	0,	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3.jpg',	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3.jpg',	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3.jpg',	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3.jpg',	99,	1,	NULL,	NULL,	NULL),
(135,	126,	0,	'06b6e0fe-2505-49a2-938b-7246c21c4518.jpg',	'06b6e0fe-2505-49a2-938b-7246c21c4518.jpg',	'06b6e0fe-2505-49a2-938b-7246c21c4518.jpg',	'06b6e0fe-2505-49a2-938b-7246c21c4518.jpg',	99,	1,	NULL,	NULL,	NULL),
(136,	126,	0,	'6f5d558e-dc48-4611-ba06-7d5d78d376f9.jpg',	'6f5d558e-dc48-4611-ba06-7d5d78d376f9.jpg',	'6f5d558e-dc48-4611-ba06-7d5d78d376f9.jpg',	'6f5d558e-dc48-4611-ba06-7d5d78d376f9.jpg',	99,	1,	NULL,	NULL,	NULL),
(137,	126,	0,	'7f436ccd-7ab2-4b1b-a1a0-6f4514bb27c1.jpg',	'7f436ccd-7ab2-4b1b-a1a0-6f4514bb27c1.jpg',	'7f436ccd-7ab2-4b1b-a1a0-6f4514bb27c1.jpg',	'7f436ccd-7ab2-4b1b-a1a0-6f4514bb27c1.jpg',	99,	1,	NULL,	NULL,	NULL),
(138,	126,	0,	'8d357291-302f-4cae-a748-92bf1161bcaf.jpg',	'8d357291-302f-4cae-a748-92bf1161bcaf.jpg',	'8d357291-302f-4cae-a748-92bf1161bcaf.jpg',	'8d357291-302f-4cae-a748-92bf1161bcaf.jpg',	99,	1,	NULL,	NULL,	NULL),
(139,	126,	0,	'8da4a495-951d-4994-b0eb-cc09e25f91b6.jpg',	'8da4a495-951d-4994-b0eb-cc09e25f91b6.jpg',	'8da4a495-951d-4994-b0eb-cc09e25f91b6.jpg',	'8da4a495-951d-4994-b0eb-cc09e25f91b6.jpg',	99,	1,	NULL,	NULL,	NULL),
(140,	126,	0,	'15d13205-b6ba-43c7-a006-c488f18996a6.jpg',	'15d13205-b6ba-43c7-a006-c488f18996a6.jpg',	'15d13205-b6ba-43c7-a006-c488f18996a6.jpg',	'15d13205-b6ba-43c7-a006-c488f18996a6.jpg',	99,	1,	NULL,	NULL,	NULL),
(141,	126,	0,	'24d09f89-a7f4-4e7d-8f79-e4f54ebc55eb.jpg',	'24d09f89-a7f4-4e7d-8f79-e4f54ebc55eb.jpg',	'24d09f89-a7f4-4e7d-8f79-e4f54ebc55eb.jpg',	'24d09f89-a7f4-4e7d-8f79-e4f54ebc55eb.jpg',	99,	1,	NULL,	NULL,	NULL),
(142,	126,	0,	'26b58a14-5c75-4023-afeb-eb20e1b569ee.jpg',	'26b58a14-5c75-4023-afeb-eb20e1b569ee.jpg',	'26b58a14-5c75-4023-afeb-eb20e1b569ee.jpg',	'26b58a14-5c75-4023-afeb-eb20e1b569ee.jpg',	99,	1,	NULL,	NULL,	NULL),
(143,	126,	0,	'28e19428-a828-4df3-9dfb-e9ab882c3683.jpg',	'28e19428-a828-4df3-9dfb-e9ab882c3683.jpg',	'28e19428-a828-4df3-9dfb-e9ab882c3683.jpg',	'28e19428-a828-4df3-9dfb-e9ab882c3683.jpg',	99,	1,	NULL,	NULL,	NULL),
(144,	126,	0,	'32e755a6-1ab0-4c89-907b-bfefac8706d2.jpg',	'32e755a6-1ab0-4c89-907b-bfefac8706d2.jpg',	'32e755a6-1ab0-4c89-907b-bfefac8706d2.jpg',	'32e755a6-1ab0-4c89-907b-bfefac8706d2.jpg',	99,	1,	NULL,	NULL,	NULL),
(145,	126,	0,	'72c61657-acfe-4840-bf4c-b0cf47052a2f.jpg',	'72c61657-acfe-4840-bf4c-b0cf47052a2f.jpg',	'72c61657-acfe-4840-bf4c-b0cf47052a2f.jpg',	'72c61657-acfe-4840-bf4c-b0cf47052a2f.jpg',	99,	1,	NULL,	NULL,	NULL),
(146,	126,	0,	'162d6c8c-7c1d-4259-9b31-571b4c0ab041.jpg',	'162d6c8c-7c1d-4259-9b31-571b4c0ab041.jpg',	'162d6c8c-7c1d-4259-9b31-571b4c0ab041.jpg',	'162d6c8c-7c1d-4259-9b31-571b4c0ab041.jpg',	99,	1,	NULL,	NULL,	NULL),
(147,	126,	0,	'188a0155-0744-4514-8336-6079c21751c8.jpg',	'188a0155-0744-4514-8336-6079c21751c8.jpg',	'188a0155-0744-4514-8336-6079c21751c8.jpg',	'188a0155-0744-4514-8336-6079c21751c8.jpg',	99,	1,	NULL,	NULL,	NULL),
(148,	126,	0,	'4119a3d8-a2b1-48f6-bd51-506c84f8b084.jpg',	'4119a3d8-a2b1-48f6-bd51-506c84f8b084.jpg',	'4119a3d8-a2b1-48f6-bd51-506c84f8b084.jpg',	'4119a3d8-a2b1-48f6-bd51-506c84f8b084.jpg',	99,	1,	NULL,	NULL,	NULL),
(149,	126,	0,	'28866fda-250b-47fa-9774-20c78c109bb5.jpg',	'28866fda-250b-47fa-9774-20c78c109bb5.jpg',	'28866fda-250b-47fa-9774-20c78c109bb5.jpg',	'28866fda-250b-47fa-9774-20c78c109bb5.jpg',	99,	1,	NULL,	NULL,	NULL),
(150,	126,	0,	'0077207b-a265-482a-90a4-f909bf3a9ad7.jpg',	'0077207b-a265-482a-90a4-f909bf3a9ad7.jpg',	'0077207b-a265-482a-90a4-f909bf3a9ad7.jpg',	'0077207b-a265-482a-90a4-f909bf3a9ad7.jpg',	99,	1,	NULL,	NULL,	NULL),
(151,	126,	0,	'905613a9-862e-4214-bf45-acf111e8dceb.jpg',	'905613a9-862e-4214-bf45-acf111e8dceb.jpg',	'905613a9-862e-4214-bf45-acf111e8dceb.jpg',	'905613a9-862e-4214-bf45-acf111e8dceb.jpg',	99,	1,	NULL,	NULL,	NULL),
(152,	126,	0,	'8884892b-76f4-42c3-ba96-6d04f5c38778.jpg',	'8884892b-76f4-42c3-ba96-6d04f5c38778.jpg',	'8884892b-76f4-42c3-ba96-6d04f5c38778.jpg',	'8884892b-76f4-42c3-ba96-6d04f5c38778.jpg',	99,	1,	NULL,	NULL,	NULL),
(153,	126,	0,	'9237305a-74a9-4b19-b26f-e03714f2b18f.jpg',	'9237305a-74a9-4b19-b26f-e03714f2b18f.jpg',	'9237305a-74a9-4b19-b26f-e03714f2b18f.jpg',	'9237305a-74a9-4b19-b26f-e03714f2b18f.jpg',	99,	1,	NULL,	NULL,	NULL),
(154,	126,	0,	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	99,	1,	NULL,	NULL,	NULL),
(155,	126,	0,	'abf17ff5-98ce-4134-85d4-8c6f65f46b06.jpg',	'abf17ff5-98ce-4134-85d4-8c6f65f46b06.jpg',	'abf17ff5-98ce-4134-85d4-8c6f65f46b06.jpg',	'abf17ff5-98ce-4134-85d4-8c6f65f46b06.jpg',	99,	1,	NULL,	NULL,	NULL),
(156,	126,	0,	'aff01864-c05c-4197-994a-b5780071f27c.jpg',	'aff01864-c05c-4197-994a-b5780071f27c.jpg',	'aff01864-c05c-4197-994a-b5780071f27c.jpg',	'aff01864-c05c-4197-994a-b5780071f27c.jpg',	99,	1,	NULL,	NULL,	NULL),
(157,	126,	0,	'b4a3fa2e-d540-4916-8ad9-32b1ffd42299.jpg',	'b4a3fa2e-d540-4916-8ad9-32b1ffd42299.jpg',	'b4a3fa2e-d540-4916-8ad9-32b1ffd42299.jpg',	'b4a3fa2e-d540-4916-8ad9-32b1ffd42299.jpg',	99,	1,	NULL,	NULL,	NULL),
(158,	126,	0,	'c68562d9-ef99-4d2f-99b5-7c073ef04751.jpg',	'c68562d9-ef99-4d2f-99b5-7c073ef04751.jpg',	'c68562d9-ef99-4d2f-99b5-7c073ef04751.jpg',	'c68562d9-ef99-4d2f-99b5-7c073ef04751.jpg',	99,	1,	NULL,	NULL,	NULL),
(159,	126,	0,	'caed1d9c-1fc5-4514-af4a-c0421c54616f.jpg',	'caed1d9c-1fc5-4514-af4a-c0421c54616f.jpg',	'caed1d9c-1fc5-4514-af4a-c0421c54616f.jpg',	'caed1d9c-1fc5-4514-af4a-c0421c54616f.jpg',	99,	1,	NULL,	NULL,	NULL),
(160,	126,	0,	'e4dd4be1-9db0-46ee-8b86-2beed8a8782b.jpg',	'e4dd4be1-9db0-46ee-8b86-2beed8a8782b.jpg',	'e4dd4be1-9db0-46ee-8b86-2beed8a8782b.jpg',	'e4dd4be1-9db0-46ee-8b86-2beed8a8782b.jpg',	99,	1,	NULL,	NULL,	NULL),
(161,	127,	0,	'21.jpg',	'21.jpg',	'21.jpg',	'21.jpg',	99,	1,	NULL,	NULL,	NULL),
(162,	128,	0,	'12.jpg',	'12.jpg',	'12.jpg',	'12.jpg',	99,	1,	NULL,	NULL,	NULL),
(163,	129,	0,	'9-1.jpg',	'9-1.jpg',	'9-1.jpg',	'9-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(164,	130,	0,	'43.jpg',	'43.jpg',	'43.jpg',	'43.jpg',	99,	1,	NULL,	NULL,	NULL),
(165,	130,	0,	'870bc61e-286c-43ee-b956-06187927eb60.jpg',	'870bc61e-286c-43ee-b956-06187927eb60.jpg',	'870bc61e-286c-43ee-b956-06187927eb60.jpg',	'870bc61e-286c-43ee-b956-06187927eb60.jpg',	99,	1,	NULL,	NULL,	NULL),
(166,	131,	0,	'42.jpg',	'42.jpg',	'42.jpg',	'42.jpg',	99,	1,	NULL,	NULL,	NULL),
(167,	132,	0,	'1-1.jpg',	'1-1.jpg',	'1-1.jpg',	'1-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(168,	132,	0,	'4ec0dca5-79f9-4086-8f0e-7c8b4c7657b2.jpg',	'4ec0dca5-79f9-4086-8f0e-7c8b4c7657b2.jpg',	'4ec0dca5-79f9-4086-8f0e-7c8b4c7657b2.jpg',	'4ec0dca5-79f9-4086-8f0e-7c8b4c7657b2.jpg',	99,	1,	NULL,	NULL,	NULL),
(169,	133,	0,	'25.jpg',	'25.jpg',	'25.jpg',	'25.jpg',	99,	1,	NULL,	NULL,	NULL),
(170,	133,	0,	'b0162559-3c45-4c96-a72f-7f4d90e749b2.jpg',	'b0162559-3c45-4c96-a72f-7f4d90e749b2.jpg',	'b0162559-3c45-4c96-a72f-7f4d90e749b2.jpg',	'b0162559-3c45-4c96-a72f-7f4d90e749b2.jpg',	99,	1,	NULL,	NULL,	NULL),
(171,	134,	0,	'39.jpg',	'39.jpg',	'39.jpg',	'39.jpg',	99,	1,	NULL,	NULL,	NULL),
(172,	135,	0,	'13.jpg',	'13.jpg',	'13.jpg',	'13.jpg',	99,	1,	NULL,	NULL,	NULL),
(173,	136,	0,	'40.jpg',	'40.jpg',	'40.jpg',	'40.jpg',	99,	1,	NULL,	NULL,	NULL),
(174,	137,	0,	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	'11266291-4105-498f-bd49-a0df775c85a1.jpg',	99,	1,	NULL,	NULL,	NULL),
(175,	138,	0,	'31.jpg',	'31.jpg',	'31.jpg',	'31.jpg',	99,	1,	NULL,	NULL,	NULL),
(176,	139,	0,	'27.jpg',	'27.jpg',	'27.jpg',	'27.jpg',	99,	1,	NULL,	NULL,	NULL),
(177,	140,	0,	'38.jpg',	'38.jpg',	'38.jpg',	'38.jpg',	99,	1,	NULL,	NULL,	NULL),
(178,	140,	0,	'25a609a2-ba3d-475d-a92c-d35a71e6b458.jpg',	'25a609a2-ba3d-475d-a92c-d35a71e6b458.jpg',	'25a609a2-ba3d-475d-a92c-d35a71e6b458.jpg',	'25a609a2-ba3d-475d-a92c-d35a71e6b458.jpg',	99,	1,	NULL,	NULL,	NULL),
(179,	141,	0,	'23.jpg',	'23.jpg',	'23.jpg',	'23.jpg',	99,	1,	NULL,	NULL,	NULL),
(180,	142,	0,	'34.jpg',	'34.jpg',	'34.jpg',	'34.jpg',	99,	1,	NULL,	NULL,	NULL),
(181,	142,	0,	'4119a3d8-a2b1-48f6-bd51-506c84f8b084-1.jpg',	'4119a3d8-a2b1-48f6-bd51-506c84f8b084-1.jpg',	'4119a3d8-a2b1-48f6-bd51-506c84f8b084-1.jpg',	'4119a3d8-a2b1-48f6-bd51-506c84f8b084-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(182,	143,	0,	'20.jpg',	'20.jpg',	'20.jpg',	'20.jpg',	99,	1,	NULL,	NULL,	NULL),
(183,	144,	0,	'22.jpg',	'22.jpg',	'22.jpg',	'22.jpg',	99,	1,	NULL,	NULL,	NULL),
(184,	144,	0,	'Black-smoke-bomb.jpg',	'Black-smoke-bomb.jpg',	'Black-smoke-bomb.jpg',	'Black-smoke-bomb.jpg',	99,	1,	NULL,	NULL,	NULL),
(185,	145,	0,	'17.jpg',	'17.jpg',	'17.jpg',	'17.jpg',	99,	1,	NULL,	NULL,	NULL),
(186,	145,	0,	'pink-smoke-bomb.jpg',	'pink-smoke-bomb.jpg',	'pink-smoke-bomb.jpg',	'pink-smoke-bomb.jpg',	99,	1,	NULL,	NULL,	NULL),
(187,	146,	0,	'15.jpg',	'15.jpg',	'15.jpg',	'15.jpg',	99,	1,	NULL,	NULL,	NULL),
(188,	146,	0,	'White-Smoke-Bomb.jpg',	'White-Smoke-Bomb.jpg',	'White-Smoke-Bomb.jpg',	'White-Smoke-Bomb.jpg',	99,	1,	NULL,	NULL,	NULL),
(189,	147,	0,	'14.jpg',	'14.jpg',	'14.jpg',	'14.jpg',	99,	1,	NULL,	NULL,	NULL),
(190,	147,	0,	'green.jpg',	'green.jpg',	'green.jpg',	'green.jpg',	99,	1,	NULL,	NULL,	NULL),
(191,	148,	0,	'28.jpg',	'28.jpg',	'28.jpg',	'28.jpg',	99,	1,	NULL,	NULL,	NULL),
(192,	148,	0,	'Yellow-Smoke-BomB.jpg',	'Yellow-Smoke-BomB.jpg',	'Yellow-Smoke-BomB.jpg',	'Yellow-Smoke-BomB.jpg',	99,	1,	NULL,	NULL,	NULL),
(193,	149,	0,	'41.jpg',	'41.jpg',	'41.jpg',	'41.jpg',	99,	1,	NULL,	NULL,	NULL),
(194,	149,	0,	'31b7428c-efd7-4066-a238-5213277b863d.jpg',	'31b7428c-efd7-4066-a238-5213277b863d.jpg',	'31b7428c-efd7-4066-a238-5213277b863d.jpg',	'31b7428c-efd7-4066-a238-5213277b863d.jpg',	99,	1,	NULL,	NULL,	NULL),
(195,	150,	0,	'32.jpg',	'32.jpg',	'32.jpg',	'32.jpg',	99,	1,	NULL,	NULL,	NULL),
(196,	151,	0,	'7-1.jpg',	'7-1.jpg',	'7-1.jpg',	'7-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(197,	151,	0,	'15d13205-b6ba-43c7-a006-c488f18996a6-1.jpg',	'15d13205-b6ba-43c7-a006-c488f18996a6-1.jpg',	'15d13205-b6ba-43c7-a006-c488f18996a6-1.jpg',	'15d13205-b6ba-43c7-a006-c488f18996a6-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(198,	152,	0,	'45.jpg',	'45.jpg',	'45.jpg',	'45.jpg',	99,	1,	NULL,	NULL,	NULL),
(199,	152,	0,	'06b6e0fe-2505-49a2-938b-7246c21c4518-1.jpg',	'06b6e0fe-2505-49a2-938b-7246c21c4518-1.jpg',	'06b6e0fe-2505-49a2-938b-7246c21c4518-1.jpg',	'06b6e0fe-2505-49a2-938b-7246c21c4518-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(200,	153,	0,	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	'f9131b98-aa58-4856-8af5-018af96748fc.jpg',	99,	1,	NULL,	NULL,	NULL),
(201,	154,	0,	'2-1.jpg',	'2-1.jpg',	'2-1.jpg',	'2-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(202,	154,	0,	'3bb950c7-b753-4716-9925-d9eae246bc09-1.jpg',	'3bb950c7-b753-4716-9925-d9eae246bc09-1.jpg',	'3bb950c7-b753-4716-9925-d9eae246bc09-1.jpg',	'3bb950c7-b753-4716-9925-d9eae246bc09-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(203,	155,	0,	'44.jpg',	'44.jpg',	'44.jpg',	'44.jpg',	99,	1,	NULL,	NULL,	NULL),
(204,	155,	0,	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3-1.jpg',	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3-1.jpg',	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3-1.jpg',	'5f8b140a-2c0a-4bd6-b38e-f9bc71d0a0b3-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(205,	156,	0,	'30.jpg',	'30.jpg',	'30.jpg',	'30.jpg',	99,	1,	NULL,	NULL,	NULL),
(206,	157,	0,	'18.jpg',	'18.jpg',	'18.jpg',	'18.jpg',	99,	1,	NULL,	NULL,	NULL),
(207,	157,	0,	'fe6b249b-7ae1-4fa1-ae8f-6243552765db.jpg',	'fe6b249b-7ae1-4fa1-ae8f-6243552765db.jpg',	'fe6b249b-7ae1-4fa1-ae8f-6243552765db.jpg',	'fe6b249b-7ae1-4fa1-ae8f-6243552765db.jpg',	99,	1,	NULL,	NULL,	NULL),
(208,	158,	0,	'4-1.jpg',	'4-1.jpg',	'4-1.jpg',	'4-1.jpg',	99,	1,	NULL,	NULL,	NULL),
(209,	158,	0,	'1dfe6da6-a5da-4dd1-93af-ae99a12ddcc1.jpg',	'1dfe6da6-a5da-4dd1-93af-ae99a12ddcc1.jpg',	'1dfe6da6-a5da-4dd1-93af-ae99a12ddcc1.jpg',	'1dfe6da6-a5da-4dd1-93af-ae99a12ddcc1.jpg',	99,	1,	NULL,	NULL,	NULL),
(210,	159,	0,	'24.jpg',	'24.jpg',	'24.jpg',	'24.jpg',	99,	1,	NULL,	NULL,	NULL),
(211,	159,	0,	'c91a76b6-18e6-4691-85d0-bb6c40ec96a2.jpg',	'c91a76b6-18e6-4691-85d0-bb6c40ec96a2.jpg',	'c91a76b6-18e6-4691-85d0-bb6c40ec96a2.jpg',	'c91a76b6-18e6-4691-85d0-bb6c40ec96a2.jpg',	99,	1,	NULL,	NULL,	NULL),
(212,	160,	0,	'33.jpg',	'33.jpg',	'33.jpg',	'33.jpg',	99,	1,	NULL,	NULL,	NULL),
(213,	160,	0,	'4834a22b-8897-43a2-b8df-216ba254527c.jpg',	'4834a22b-8897-43a2-b8df-216ba254527c.jpg',	'4834a22b-8897-43a2-b8df-216ba254527c.jpg',	'4834a22b-8897-43a2-b8df-216ba254527c.jpg',	99,	1,	NULL,	NULL,	NULL),
(214,	161,	0,	'gd_1458213804_web_6_.jpg',	'1458213804_web_6_.jpg',	'md_1458213804_web_6_.jpg',	'pq_1458213804_web_6_.jpg',	99,	1,	NULL,	NULL,	NULL);

DROP TABLE IF EXISTS `l_pecas_linhas_en`;
CREATE TABLE `l_pecas_linhas_en` (
  `id` int(11) NOT NULL,
  `peca_pai` int(11) DEFAULT '0',
  `ref` varchar(30) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT '0.00',
  `preco2` decimal(10,2) DEFAULT '0.00',
  `preco_forn` decimal(10,2) DEFAULT '0.00',
  `preco_old` decimal(10,2) DEFAULT '0.00' COMMENT 'Apenas usado nas Atualização de Preço',
  `novidade` tinyint(4) DEFAULT '0',
  `destaque` tinyint(4) DEFAULT '0',
  `promocao` tinyint(4) DEFAULT '0',
  `promocao_desconto` decimal(10,2) DEFAULT NULL,
  `promocao_preco` decimal(10,2) DEFAULT NULL,
  `promocao_datai` date DEFAULT NULL,
  `promocao_dataf` date DEFAULT NULL,
  `quantidades_descricao` text,
  `favoritos` int(11) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `contagem_vendas` int(11) DEFAULT '0',
  `peso` decimal(10,3) DEFAULT NULL,
  `stock` int(11) DEFAULT '0',
  `nao_limitar_stock` tinyint(4) DEFAULT '0',
  `atualizado` tinyint(4) DEFAULT '0',
  `preco_ant` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `indice` (`ref`,`peca_pai`,`ordem`,`visivel`,`novidade`,`destaque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_linhas_pt`;
CREATE TABLE `l_pecas_linhas_pt` (
  `id` int(11) NOT NULL,
  `peca_pai` int(11) DEFAULT '0',
  `ref` varchar(30) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `img_cor` varchar(250) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT '0.00',
  `preco2` decimal(10,2) DEFAULT '0.00',
  `preco_old` decimal(10,2) DEFAULT '0.00' COMMENT 'Apenas usado nas Atualização de Preço',
  `preco_ant` decimal(10,2) DEFAULT '0.00',
  `novidade` tinyint(4) DEFAULT '0',
  `destaque` tinyint(4) DEFAULT '0',
  `promocao` tinyint(4) DEFAULT '0',
  `promocao_desconto` decimal(10,2) DEFAULT NULL,
  `promocao_preco` decimal(10,2) DEFAULT NULL,
  `promocao_datai` date DEFAULT NULL,
  `promocao_dataf` date DEFAULT NULL,
  `quantidades_descricao` text,
  `favoritos` int(11) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `contagem_vendas` int(11) DEFAULT '0',
  `peso` decimal(10,3) DEFAULT NULL,
  `stock` int(11) DEFAULT '0',
  `nao_limitar_stock` tinyint(4) DEFAULT '0',
  `atualizado` tinyint(4) DEFAULT '0',
  `preco_forn` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `indice` (`ref`,`peca_pai`,`ordem`,`visivel`,`novidade`,`destaque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_pt`;
CREATE TABLE `l_pecas_pt` (
  `id` int(11) NOT NULL,
  `ref` varchar(30) DEFAULT NULL,
  `cod_barras` text,
  `nome` varchar(250) DEFAULT NULL,
  `categoria` int(11) DEFAULT '0',
  `marca` int(11) DEFAULT NULL,
  `on_order` varchar(10) NOT NULL,
  `regiao` int(11) DEFAULT NULL,
  `descricao` text,
  `short_descricao` text,
  `caracteristicas` text,
  `informacoes` text,
  `composition_per_capsule` text,
  `modo_de_tomar` text,
  `detalhes_advertencias` text,
  `filtro_1` text,
  `filtro_2` text,
  `filtro_3` text,
  `download` varchar(450) DEFAULT NULL,
  `video` varchar(500) DEFAULT NULL,
  `info` varchar(250) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT '0.00',
  `preco_old` decimal(10,2) DEFAULT '0.00' COMMENT 'Apenas usado nas Atualização de Preço',
  `preco_forn` decimal(10,2) DEFAULT '0.00',
  `preco_ant` decimal(10,2) DEFAULT '0.00',
  `markup` varchar(50) DEFAULT NULL,
  `cost` varchar(50) DEFAULT NULL,
  `imagem1` varchar(250) DEFAULT NULL,
  `imagem2` varchar(250) DEFAULT NULL,
  `imagem3` varchar(250) DEFAULT NULL,
  `imagem4` varchar(250) DEFAULT NULL,
  `novidade` tinyint(4) DEFAULT '0',
  `destaque` tinyint(4) DEFAULT '0',
  `mega_destaque` tinyint(4) DEFAULT '0',
  `mega_destaque_tit` varchar(150) DEFAULT NULL,
  `mega_destaque_txt` text,
  `promocao` tinyint(4) DEFAULT '0',
  `promocao_ordem` int(11) DEFAULT NULL,
  `promocao_desconto` decimal(10,2) DEFAULT NULL,
  `promocao_datai` date DEFAULT NULL,
  `promocao_dataf` date DEFAULT NULL,
  `promocao_pagina` int(11) DEFAULT '0',
  `promocao_titulo` varchar(25) DEFAULT NULL,
  `promocao_texto` text,
  `saldo` tinyint(4) DEFAULT '0',
  `iva` int(11) DEFAULT '23',
  `quantidades_descricao` text,
  `favoritos` int(11) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `contagem_vendas` int(11) DEFAULT '0',
  `peso` decimal(10,3) DEFAULT NULL,
  `stock` int(11) DEFAULT '0',
  `prepare` varchar(5) DEFAULT NULL,
  `enquiry_type` int(5) NOT NULL,
  `role_customer` varchar(255) DEFAULT NULL,
  `reguler_price_customer` varchar(255) DEFAULT NULL,
  `selling_price_customer` varchar(255) DEFAULT NULL,
  `product_qulity_customer` varchar(255) DEFAULT NULL,
  `role_franchise` varchar(255) DEFAULT NULL,
  `reguler_price_franchise` varchar(255) DEFAULT NULL,
  `selling_price_franchise` varchar(255) DEFAULT NULL,
  `product_qulity_franchise` varchar(255) DEFAULT NULL,
  `guia_tamanhos` int(11) DEFAULT '0',
  `nao_limitar_stock` tinyint(4) DEFAULT '0',
  `descricao_stock` varchar(150) DEFAULT NULL,
  `maxstock` varchar(8) NOT NULL,
  `tem_conjunto` tinyint(4) DEFAULT '0',
  `url` varchar(150) DEFAULT NULL,
  `title` text,
  `description` text,
  `keywords` text,
  `pro_id` int(11) DEFAULT NULL,
  `temp_image` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`ref`,`categoria`,`marca`,`ordem`,`visivel`,`novidade`,`destaque`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_relacao`;
CREATE TABLE `l_pecas_relacao` (
  `id` bigint(20) NOT NULL,
  `id_peca` int(11) DEFAULT NULL,
  `id_relacao` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_peca`,`id_relacao`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_reviews_en`;
CREATE TABLE `l_pecas_reviews_en` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discription` text NOT NULL,
  `rating` int(11) NOT NULL,
  `average` int(11) NOT NULL,
  `rating_by` int(11) NOT NULL,
  `reviewer` text CHARACTER SET armscii8 NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `l_pecas_reviews_pt`;
CREATE TABLE `l_pecas_reviews_pt` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discription` text NOT NULL,
  `rating` int(11) NOT NULL,
  `average` int(11) NOT NULL,
  `rating_by` int(11) NOT NULL,
  `reviewer` text CHARACTER SET armscii8 NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `l_pecas_seguir`;
CREATE TABLE `l_pecas_seguir` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `id_opcao` int(11) DEFAULT '0',
  `preco` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_cliente`,`id_produto`,`id_opcao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_store`;
CREATE TABLE `l_pecas_store` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(10) NOT NULL,
  `b_name_pro` varchar(155) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `primery` varchar(11) NOT NULL,
  `ordem` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_pecas_store` (`id`, `product_id`, `b_name_pro`, `phone`, `primery`, `ordem`) VALUES
(169,	'1',	'2',	'1',	'',	''),
(170,	'2',	'2',	'1',	'',	''),
(171,	'3',	'2',	'1',	'',	''),
(172,	'4',	'2',	'1',	'',	''),
(173,	'5',	'2',	'1',	'',	''),
(174,	'6',	'2',	'1',	'',	''),
(175,	'7',	'2',	'1',	'',	''),
(176,	'8',	'2',	'1',	'',	''),
(177,	'9',	'2',	'1',	'',	''),
(178,	'10',	'2',	'1',	'',	''),
(179,	'11',	'2',	'1',	'',	''),
(180,	'12',	'2',	'1',	'',	''),
(181,	'13',	'2',	'1',	'',	''),
(182,	'14',	'2',	'1',	'',	''),
(183,	'15',	'2',	'1',	'',	''),
(184,	'16',	'2',	'1',	'',	''),
(185,	'17',	'2',	'1',	'',	''),
(186,	'18',	'2',	'1',	'',	''),
(187,	'19',	'2',	'1',	'',	''),
(188,	'20',	'2',	'1',	'',	''),
(189,	'21',	'2',	'1',	'',	''),
(190,	'22',	'2',	'1',	'',	''),
(191,	'23',	'2',	'1',	'',	''),
(192,	'24',	'2',	'1',	'',	''),
(193,	'25',	'2',	'1',	'',	''),
(194,	'26',	'2',	'1',	'',	''),
(195,	'27',	'2',	'1',	'',	''),
(196,	'28',	'2',	'1',	'',	''),
(197,	'29',	'2',	'1',	'',	''),
(198,	'30',	'2',	'1',	'',	''),
(199,	'31',	'2',	'1',	'',	''),
(200,	'32',	'2',	'1',	'',	''),
(201,	'33',	'2',	'1',	'',	''),
(202,	'34',	'2',	'1',	'',	''),
(203,	'35',	'2',	'1',	'',	''),
(204,	'36',	'2',	'1',	'',	''),
(205,	'37',	'2',	'1',	'',	''),
(206,	'38',	'2',	'1',	'',	''),
(207,	'39',	'2',	'1',	'',	''),
(208,	'40',	'2',	'1',	'',	''),
(209,	'41',	'2',	'1',	'',	''),
(210,	'42',	'2',	'1',	'',	''),
(211,	'43',	'2',	'1',	'',	''),
(212,	'44',	'2',	'1',	'',	''),
(213,	'45',	'2',	'1',	'',	''),
(214,	'46',	'2',	'1',	'',	''),
(215,	'47',	'2',	'1',	'',	''),
(216,	'48',	'2',	'1',	'',	''),
(217,	'49',	'2',	'1',	'',	''),
(218,	'50',	'2',	'1',	'',	''),
(219,	'51',	'2',	'1',	'',	''),
(220,	'52',	'2',	'1',	'',	''),
(221,	'53',	'2',	'1',	'',	''),
(222,	'54',	'2',	'1',	'',	''),
(223,	'55',	'2',	'1',	'',	''),
(224,	'56',	'2',	'1',	'',	''),
(225,	'57',	'2',	'1',	'',	''),
(226,	'58',	'2',	'1',	'',	''),
(227,	'59',	'2',	'1',	'',	''),
(228,	'60',	'2',	'1',	'',	''),
(229,	'61',	'2',	'1',	'',	''),
(230,	'62',	'2',	'1',	'',	''),
(231,	'63',	'2',	'1',	'',	''),
(232,	'64',	'2',	'1',	'',	''),
(233,	'65',	'2',	'1',	'',	''),
(234,	'66',	'2',	'1',	'',	''),
(235,	'67',	'2',	'1',	'',	''),
(236,	'68',	'2',	'1',	'',	''),
(237,	'69',	'2',	'1',	'',	''),
(238,	'70',	'2',	'1',	'',	''),
(239,	'71',	'2',	'1',	'',	''),
(240,	'72',	'2',	'1',	'',	''),
(241,	'73',	'2',	'1',	'',	''),
(242,	'74',	'2',	'1',	'',	''),
(243,	'75',	'2',	'1',	'',	''),
(244,	'76',	'2',	'1',	'',	''),
(245,	'77',	'2',	'1',	'',	''),
(246,	'78',	'2',	'1',	'',	''),
(247,	'79',	'2',	'1',	'',	''),
(248,	'80',	'2',	'1',	'',	''),
(249,	'81',	'2',	'1',	'',	''),
(250,	'82',	'2',	'1',	'',	''),
(251,	'83',	'2',	'1',	'',	''),
(252,	'84',	'2',	'1',	'',	''),
(253,	'85',	'2',	'1',	'',	''),
(254,	'86',	'2',	'1',	'',	''),
(255,	'87',	'2',	'1',	'',	''),
(256,	'88',	'2',	'1',	'',	''),
(257,	'89',	'2',	'1',	'',	''),
(258,	'90',	'2',	'1',	'',	''),
(259,	'91',	'2',	'1',	'',	''),
(260,	'92',	'2',	'1',	'',	''),
(261,	'93',	'2',	'1',	'',	''),
(262,	'94',	'2',	'1',	'',	''),
(263,	'95',	'2',	'1',	'',	''),
(264,	'96',	'2',	'1',	'',	''),
(265,	'97',	'2',	'1',	'',	''),
(266,	'98',	'2',	'1',	'',	''),
(267,	'99',	'2',	'1',	'',	''),
(268,	'100',	'2',	'1',	'',	''),
(269,	'101',	'2',	'1',	'',	''),
(270,	'102',	'2',	'1',	'',	''),
(271,	'103',	'2',	'1',	'',	''),
(272,	'104',	'2',	'1',	'',	''),
(273,	'105',	'2',	'1',	'',	''),
(274,	'106',	'2',	'1',	'',	''),
(275,	'107',	'2',	'1',	'',	''),
(276,	'108',	'2',	'1',	'',	''),
(277,	'109',	'2',	'1',	'',	''),
(278,	'110',	'2',	'1',	'',	''),
(279,	'111',	'2',	'1',	'',	''),
(280,	'112',	'2',	'1',	'',	''),
(281,	'113',	'2',	'1',	'',	''),
(282,	'114',	'2',	'1',	'',	''),
(283,	'115',	'2',	'1',	'',	''),
(284,	'116',	'2',	'1',	'',	''),
(285,	'117',	'2',	'1',	'',	''),
(286,	'118',	'2',	'1',	'',	''),
(287,	'119',	'2',	'1',	'',	''),
(288,	'120',	'2',	'1',	'',	''),
(289,	'121',	'2',	'1',	'',	''),
(290,	'122',	'2',	'1',	'',	''),
(291,	'123',	'2',	'1',	'',	''),
(292,	'124',	'2',	'1',	'',	''),
(293,	'125',	'2',	'1',	'',	''),
(294,	'126',	'2',	'1',	'',	''),
(295,	'127',	'2',	'1',	'',	''),
(296,	'128',	'2',	'1',	'',	''),
(297,	'129',	'2',	'1',	'',	''),
(298,	'130',	'2',	'1',	'',	''),
(299,	'131',	'2',	'1',	'',	''),
(300,	'132',	'2',	'1',	'',	''),
(301,	'133',	'2',	'1',	'',	''),
(302,	'134',	'2',	'1',	'',	''),
(303,	'135',	'2',	'1',	'',	''),
(304,	'136',	'2',	'1',	'',	''),
(305,	'137',	'2',	'1',	'',	''),
(306,	'138',	'2',	'1',	'',	''),
(307,	'139',	'2',	'1',	'',	''),
(308,	'140',	'2',	'1',	'',	''),
(309,	'141',	'2',	'1',	'',	''),
(310,	'142',	'2',	'1',	'',	''),
(311,	'143',	'2',	'1',	'',	''),
(312,	'144',	'2',	'1',	'',	''),
(313,	'145',	'2',	'1',	'',	''),
(314,	'146',	'2',	'1',	'',	''),
(315,	'147',	'2',	'1',	'',	''),
(316,	'148',	'2',	'1',	'',	''),
(317,	'149',	'2',	'1',	'',	''),
(318,	'150',	'2',	'1',	'',	''),
(319,	'151',	'2',	'1',	'',	''),
(320,	'152',	'2',	'1',	'',	''),
(321,	'153',	'2',	'1',	'',	''),
(322,	'154',	'2',	'1',	'',	''),
(323,	'155',	'2',	'1',	'',	''),
(324,	'156',	'2',	'1',	'',	''),
(325,	'157',	'2',	'1',	'',	''),
(326,	'158',	'2',	'1',	'',	''),
(327,	'159',	'2',	'1',	'',	''),
(328,	'160',	'2',	'1',	'',	''),
(329,	'161',	'2',	'1',	'',	''),
(330,	'1',	'4',	'null',	'',	''),
(331,	'2',	'4',	'null',	'',	''),
(332,	'3',	'4',	'null',	'',	''),
(333,	'4',	'4',	'null',	'',	''),
(334,	'5',	'4',	'null',	'',	''),
(335,	'6',	'4',	'null',	'',	''),
(336,	'7',	'4',	'null',	'',	''),
(337,	'8',	'4',	'null',	'',	''),
(338,	'9',	'4',	'null',	'',	''),
(339,	'10',	'4',	'null',	'',	''),
(340,	'11',	'4',	'null',	'',	''),
(341,	'12',	'4',	'null',	'',	''),
(342,	'13',	'4',	'null',	'',	''),
(343,	'14',	'4',	'null',	'',	''),
(344,	'15',	'4',	'null',	'',	''),
(345,	'16',	'4',	'null',	'',	''),
(346,	'17',	'4',	'null',	'',	''),
(347,	'18',	'4',	'null',	'',	''),
(348,	'19',	'4',	'null',	'',	''),
(349,	'20',	'4',	'null',	'',	''),
(350,	'21',	'4',	'null',	'',	''),
(351,	'22',	'4',	'null',	'',	''),
(352,	'23',	'4',	'null',	'',	''),
(353,	'24',	'4',	'null',	'',	''),
(354,	'25',	'4',	'null',	'',	''),
(355,	'26',	'4',	'null',	'',	''),
(356,	'27',	'4',	'null',	'',	''),
(357,	'28',	'4',	'null',	'',	''),
(358,	'29',	'4',	'null',	'',	''),
(359,	'30',	'4',	'null',	'',	''),
(360,	'31',	'4',	'null',	'',	''),
(361,	'32',	'4',	'null',	'',	''),
(362,	'33',	'4',	'null',	'',	''),
(363,	'34',	'4',	'null',	'',	''),
(364,	'35',	'4',	'null',	'',	''),
(365,	'36',	'4',	'null',	'',	''),
(366,	'37',	'4',	'null',	'',	''),
(367,	'38',	'4',	'null',	'',	''),
(368,	'39',	'4',	'null',	'',	''),
(369,	'40',	'4',	'null',	'',	''),
(370,	'41',	'4',	'null',	'',	''),
(371,	'42',	'4',	'null',	'',	''),
(372,	'43',	'4',	'null',	'',	''),
(373,	'44',	'4',	'null',	'',	''),
(374,	'45',	'4',	'null',	'',	''),
(375,	'46',	'4',	'null',	'',	''),
(376,	'47',	'4',	'null',	'',	''),
(377,	'48',	'4',	'null',	'',	''),
(378,	'49',	'4',	'null',	'',	''),
(379,	'50',	'4',	'null',	'',	''),
(380,	'51',	'4',	'null',	'',	''),
(381,	'52',	'4',	'null',	'',	''),
(382,	'53',	'4',	'null',	'',	''),
(383,	'54',	'4',	'null',	'',	''),
(384,	'55',	'4',	'null',	'',	''),
(385,	'56',	'4',	'null',	'',	''),
(386,	'57',	'4',	'null',	'',	''),
(387,	'58',	'4',	'null',	'',	''),
(388,	'59',	'4',	'null',	'',	''),
(389,	'60',	'4',	'null',	'',	''),
(390,	'61',	'4',	'null',	'',	''),
(391,	'62',	'4',	'null',	'',	''),
(392,	'63',	'4',	'null',	'',	''),
(393,	'64',	'4',	'null',	'',	''),
(394,	'65',	'4',	'null',	'',	''),
(395,	'66',	'4',	'null',	'',	''),
(396,	'67',	'4',	'null',	'',	''),
(397,	'68',	'4',	'null',	'',	''),
(398,	'69',	'4',	'null',	'',	''),
(399,	'70',	'4',	'null',	'',	''),
(400,	'71',	'4',	'null',	'',	''),
(401,	'72',	'4',	'null',	'',	''),
(402,	'73',	'4',	'null',	'',	''),
(403,	'74',	'4',	'null',	'',	''),
(404,	'75',	'4',	'null',	'',	''),
(405,	'76',	'4',	'null',	'',	''),
(406,	'77',	'4',	'null',	'',	''),
(407,	'78',	'4',	'null',	'',	''),
(408,	'79',	'4',	'null',	'',	''),
(409,	'80',	'4',	'null',	'',	''),
(410,	'81',	'4',	'null',	'',	''),
(411,	'82',	'4',	'null',	'',	''),
(412,	'83',	'4',	'null',	'',	''),
(413,	'84',	'4',	'null',	'',	''),
(414,	'85',	'4',	'null',	'',	''),
(415,	'86',	'4',	'null',	'',	''),
(416,	'87',	'4',	'null',	'',	''),
(417,	'88',	'4',	'null',	'',	''),
(418,	'89',	'4',	'null',	'',	''),
(419,	'90',	'4',	'null',	'',	''),
(420,	'91',	'4',	'null',	'',	''),
(421,	'92',	'4',	'null',	'',	''),
(422,	'93',	'4',	'null',	'',	''),
(423,	'94',	'4',	'null',	'',	''),
(424,	'95',	'4',	'null',	'',	''),
(425,	'96',	'4',	'null',	'',	''),
(426,	'97',	'4',	'null',	'',	''),
(427,	'98',	'4',	'null',	'',	''),
(428,	'99',	'4',	'null',	'',	''),
(429,	'100',	'4',	'null',	'',	''),
(430,	'101',	'4',	'null',	'',	''),
(431,	'102',	'4',	'null',	'',	''),
(432,	'103',	'4',	'null',	'',	''),
(433,	'104',	'4',	'null',	'',	''),
(434,	'105',	'4',	'null',	'',	''),
(435,	'106',	'4',	'null',	'',	''),
(436,	'107',	'4',	'null',	'',	''),
(437,	'108',	'4',	'null',	'',	''),
(438,	'109',	'4',	'null',	'',	''),
(439,	'110',	'4',	'null',	'',	''),
(440,	'111',	'4',	'null',	'',	''),
(441,	'112',	'4',	'null',	'',	''),
(442,	'113',	'4',	'null',	'',	''),
(443,	'114',	'4',	'null',	'',	''),
(444,	'115',	'4',	'null',	'',	''),
(445,	'116',	'4',	'null',	'',	''),
(446,	'117',	'4',	'null',	'',	''),
(447,	'118',	'4',	'null',	'',	''),
(448,	'119',	'4',	'null',	'',	''),
(449,	'120',	'4',	'null',	'',	''),
(450,	'121',	'4',	'null',	'',	''),
(451,	'122',	'4',	'null',	'',	''),
(452,	'123',	'4',	'null',	'',	''),
(453,	'124',	'4',	'null',	'',	''),
(454,	'125',	'4',	'null',	'',	''),
(455,	'126',	'4',	'null',	'',	''),
(456,	'127',	'4',	'null',	'',	''),
(457,	'128',	'4',	'null',	'',	''),
(458,	'129',	'4',	'null',	'',	''),
(459,	'130',	'4',	'null',	'',	''),
(460,	'131',	'4',	'null',	'',	''),
(461,	'132',	'4',	'null',	'',	''),
(462,	'133',	'4',	'null',	'',	''),
(463,	'134',	'4',	'null',	'',	''),
(464,	'135',	'4',	'null',	'',	''),
(465,	'136',	'4',	'null',	'',	''),
(466,	'137',	'4',	'null',	'',	''),
(467,	'138',	'4',	'null',	'',	''),
(468,	'139',	'4',	'null',	'',	''),
(469,	'140',	'4',	'null',	'',	''),
(470,	'141',	'4',	'null',	'',	''),
(471,	'142',	'4',	'null',	'',	''),
(472,	'143',	'4',	'null',	'',	''),
(473,	'144',	'4',	'null',	'',	''),
(474,	'145',	'4',	'null',	'',	''),
(475,	'146',	'4',	'null',	'',	''),
(476,	'147',	'4',	'null',	'',	''),
(477,	'148',	'4',	'null',	'',	''),
(478,	'149',	'4',	'null',	'',	''),
(479,	'150',	'4',	'null',	'',	''),
(480,	'151',	'4',	'null',	'',	''),
(481,	'152',	'4',	'null',	'',	''),
(482,	'153',	'4',	'null',	'',	''),
(483,	'154',	'4',	'null',	'',	''),
(484,	'155',	'4',	'null',	'',	''),
(485,	'156',	'4',	'null',	'',	''),
(486,	'157',	'4',	'null',	'',	''),
(487,	'158',	'4',	'null',	'',	''),
(488,	'159',	'4',	'null',	'',	''),
(489,	'160',	'4',	'null',	'',	''),
(491,	'1',	'6',	'null',	'',	''),
(492,	'2',	'6',	'null',	'',	''),
(493,	'3',	'6',	'null',	'',	''),
(494,	'4',	'6',	'null',	'',	''),
(495,	'5',	'6',	'null',	'',	''),
(496,	'6',	'6',	'null',	'',	''),
(497,	'7',	'6',	'null',	'',	''),
(498,	'8',	'6',	'null',	'',	''),
(499,	'9',	'6',	'null',	'',	''),
(500,	'10',	'6',	'null',	'',	''),
(501,	'11',	'6',	'null',	'',	''),
(502,	'12',	'6',	'null',	'',	''),
(503,	'13',	'6',	'null',	'',	''),
(504,	'14',	'6',	'null',	'',	''),
(505,	'15',	'6',	'null',	'',	''),
(506,	'16',	'6',	'null',	'',	''),
(507,	'17',	'6',	'null',	'',	''),
(508,	'18',	'6',	'null',	'',	''),
(509,	'19',	'6',	'null',	'',	''),
(510,	'20',	'6',	'null',	'',	''),
(511,	'21',	'6',	'null',	'',	''),
(512,	'22',	'6',	'null',	'',	''),
(513,	'23',	'6',	'null',	'',	''),
(514,	'24',	'6',	'null',	'',	''),
(515,	'25',	'6',	'null',	'',	''),
(516,	'26',	'6',	'null',	'',	''),
(517,	'27',	'6',	'null',	'',	''),
(518,	'28',	'6',	'null',	'',	''),
(519,	'29',	'6',	'null',	'',	''),
(520,	'30',	'6',	'null',	'',	''),
(521,	'31',	'6',	'null',	'',	''),
(522,	'32',	'6',	'null',	'',	''),
(523,	'33',	'6',	'null',	'',	''),
(524,	'34',	'6',	'null',	'',	''),
(525,	'35',	'6',	'null',	'',	''),
(526,	'36',	'6',	'null',	'',	''),
(527,	'37',	'6',	'null',	'',	''),
(528,	'38',	'6',	'null',	'',	''),
(529,	'39',	'6',	'null',	'',	''),
(530,	'40',	'6',	'null',	'',	''),
(531,	'41',	'6',	'null',	'',	''),
(532,	'42',	'6',	'null',	'',	''),
(533,	'43',	'6',	'null',	'',	''),
(534,	'44',	'6',	'null',	'',	''),
(535,	'45',	'6',	'null',	'',	''),
(536,	'46',	'6',	'null',	'',	''),
(537,	'47',	'6',	'null',	'',	''),
(538,	'48',	'6',	'null',	'',	''),
(539,	'49',	'6',	'null',	'',	''),
(540,	'50',	'6',	'null',	'',	''),
(541,	'51',	'6',	'null',	'',	''),
(542,	'52',	'6',	'null',	'',	''),
(543,	'53',	'6',	'null',	'',	''),
(544,	'54',	'6',	'null',	'',	''),
(545,	'55',	'6',	'null',	'',	''),
(546,	'56',	'6',	'null',	'',	''),
(547,	'57',	'6',	'null',	'',	''),
(548,	'58',	'6',	'null',	'',	''),
(549,	'59',	'6',	'null',	'',	''),
(550,	'60',	'6',	'null',	'',	''),
(551,	'61',	'6',	'null',	'',	''),
(552,	'62',	'6',	'null',	'',	''),
(553,	'63',	'6',	'null',	'',	''),
(554,	'64',	'6',	'null',	'',	''),
(555,	'65',	'6',	'null',	'',	''),
(556,	'66',	'6',	'null',	'',	''),
(557,	'67',	'6',	'null',	'',	''),
(558,	'68',	'6',	'null',	'',	''),
(559,	'69',	'6',	'null',	'',	''),
(560,	'70',	'6',	'null',	'',	''),
(561,	'71',	'6',	'null',	'',	''),
(562,	'72',	'6',	'null',	'',	''),
(563,	'73',	'6',	'null',	'',	''),
(564,	'74',	'6',	'null',	'',	''),
(565,	'75',	'6',	'null',	'',	''),
(566,	'76',	'6',	'null',	'',	''),
(567,	'77',	'6',	'null',	'',	''),
(568,	'78',	'6',	'null',	'',	''),
(569,	'79',	'6',	'null',	'',	''),
(570,	'80',	'6',	'null',	'',	''),
(571,	'81',	'6',	'null',	'',	''),
(572,	'82',	'6',	'null',	'',	''),
(573,	'83',	'6',	'null',	'',	''),
(574,	'84',	'6',	'null',	'',	''),
(575,	'85',	'6',	'null',	'',	''),
(576,	'86',	'6',	'null',	'',	''),
(577,	'87',	'6',	'null',	'',	''),
(578,	'88',	'6',	'null',	'',	''),
(579,	'89',	'6',	'null',	'',	''),
(580,	'90',	'6',	'null',	'',	''),
(581,	'91',	'6',	'null',	'',	''),
(582,	'92',	'6',	'null',	'',	''),
(583,	'93',	'6',	'null',	'',	''),
(584,	'94',	'6',	'null',	'',	''),
(585,	'95',	'6',	'null',	'',	''),
(586,	'96',	'6',	'null',	'',	''),
(587,	'97',	'6',	'null',	'',	''),
(588,	'98',	'6',	'null',	'',	''),
(589,	'99',	'6',	'null',	'',	''),
(590,	'100',	'6',	'null',	'',	''),
(591,	'101',	'6',	'null',	'',	''),
(592,	'102',	'6',	'null',	'',	''),
(593,	'103',	'6',	'null',	'',	''),
(594,	'104',	'6',	'null',	'',	''),
(595,	'105',	'6',	'null',	'',	''),
(596,	'106',	'6',	'null',	'',	''),
(597,	'107',	'6',	'null',	'',	''),
(598,	'108',	'6',	'null',	'',	''),
(599,	'109',	'6',	'null',	'',	''),
(600,	'110',	'6',	'null',	'',	''),
(601,	'111',	'6',	'null',	'',	''),
(602,	'112',	'6',	'null',	'',	''),
(603,	'113',	'6',	'null',	'',	''),
(604,	'114',	'6',	'null',	'',	''),
(605,	'115',	'6',	'null',	'',	''),
(606,	'116',	'6',	'null',	'',	''),
(607,	'117',	'6',	'null',	'',	''),
(608,	'118',	'6',	'null',	'',	''),
(609,	'119',	'6',	'null',	'',	''),
(610,	'120',	'6',	'null',	'',	''),
(611,	'121',	'6',	'null',	'',	''),
(612,	'122',	'6',	'null',	'',	''),
(613,	'123',	'6',	'null',	'',	''),
(614,	'124',	'6',	'null',	'',	''),
(615,	'125',	'6',	'null',	'',	''),
(616,	'126',	'6',	'null',	'',	''),
(617,	'127',	'6',	'null',	'',	''),
(618,	'128',	'6',	'null',	'',	''),
(619,	'129',	'6',	'null',	'',	''),
(620,	'130',	'6',	'null',	'',	''),
(621,	'131',	'6',	'null',	'',	''),
(622,	'132',	'6',	'null',	'',	''),
(623,	'133',	'6',	'null',	'',	''),
(624,	'134',	'6',	'null',	'',	''),
(625,	'135',	'6',	'null',	'',	''),
(626,	'136',	'6',	'null',	'',	''),
(627,	'137',	'6',	'null',	'',	''),
(628,	'138',	'6',	'null',	'',	''),
(629,	'139',	'6',	'null',	'',	''),
(630,	'140',	'6',	'null',	'',	''),
(631,	'141',	'6',	'null',	'',	''),
(632,	'142',	'6',	'null',	'',	''),
(633,	'143',	'6',	'null',	'',	''),
(634,	'144',	'6',	'null',	'',	''),
(635,	'145',	'6',	'null',	'',	''),
(636,	'146',	'6',	'null',	'',	''),
(637,	'147',	'6',	'null',	'',	''),
(638,	'148',	'6',	'null',	'',	''),
(639,	'149',	'6',	'null',	'',	''),
(640,	'150',	'6',	'null',	'',	''),
(641,	'151',	'6',	'null',	'',	''),
(642,	'152',	'6',	'null',	'',	''),
(643,	'153',	'6',	'null',	'',	''),
(644,	'154',	'6',	'null',	'',	''),
(645,	'155',	'6',	'null',	'',	''),
(646,	'156',	'6',	'null',	'',	''),
(647,	'157',	'6',	'null',	'',	''),
(648,	'158',	'6',	'null',	'',	''),
(649,	'159',	'6',	'null',	'',	''),
(650,	'160',	'6',	'null',	'',	''),
(651,	'161',	'6',	'null',	'',	''),
(652,	'1',	'7',	'null',	'',	''),
(653,	'2',	'7',	'null',	'',	''),
(654,	'3',	'7',	'null',	'',	''),
(655,	'4',	'7',	'null',	'',	''),
(656,	'5',	'7',	'null',	'',	''),
(657,	'6',	'7',	'null',	'',	''),
(658,	'7',	'7',	'null',	'',	''),
(659,	'8',	'7',	'null',	'',	''),
(660,	'9',	'7',	'null',	'',	''),
(661,	'10',	'7',	'null',	'',	''),
(662,	'11',	'7',	'null',	'',	''),
(663,	'12',	'7',	'null',	'',	''),
(664,	'13',	'7',	'null',	'',	''),
(665,	'14',	'7',	'null',	'',	''),
(666,	'15',	'7',	'null',	'',	''),
(667,	'16',	'7',	'null',	'',	''),
(668,	'17',	'7',	'null',	'',	''),
(669,	'18',	'7',	'null',	'',	''),
(670,	'19',	'7',	'null',	'',	''),
(671,	'20',	'7',	'null',	'',	''),
(672,	'21',	'7',	'null',	'',	''),
(673,	'22',	'7',	'null',	'',	''),
(674,	'23',	'7',	'null',	'',	''),
(675,	'24',	'7',	'null',	'',	''),
(676,	'25',	'7',	'null',	'',	''),
(677,	'26',	'7',	'null',	'',	''),
(678,	'27',	'7',	'null',	'',	''),
(679,	'28',	'7',	'null',	'',	''),
(680,	'29',	'7',	'null',	'',	''),
(681,	'30',	'7',	'null',	'',	''),
(682,	'31',	'7',	'null',	'',	''),
(683,	'32',	'7',	'null',	'',	''),
(684,	'33',	'7',	'null',	'',	''),
(685,	'34',	'7',	'null',	'',	''),
(686,	'35',	'7',	'null',	'',	''),
(687,	'36',	'7',	'null',	'',	''),
(688,	'37',	'7',	'null',	'',	''),
(689,	'38',	'7',	'null',	'',	''),
(690,	'39',	'7',	'null',	'',	''),
(691,	'40',	'7',	'null',	'',	''),
(692,	'41',	'7',	'null',	'',	''),
(693,	'42',	'7',	'null',	'',	''),
(694,	'43',	'7',	'null',	'',	''),
(695,	'44',	'7',	'null',	'',	''),
(696,	'45',	'7',	'null',	'',	''),
(697,	'46',	'7',	'null',	'',	''),
(698,	'47',	'7',	'null',	'',	''),
(699,	'48',	'7',	'null',	'',	''),
(700,	'49',	'7',	'null',	'',	''),
(701,	'50',	'7',	'null',	'',	''),
(702,	'51',	'7',	'null',	'',	''),
(703,	'52',	'7',	'null',	'',	''),
(704,	'53',	'7',	'null',	'',	''),
(705,	'54',	'7',	'null',	'',	''),
(706,	'55',	'7',	'null',	'',	''),
(707,	'56',	'7',	'null',	'',	''),
(708,	'57',	'7',	'null',	'',	''),
(709,	'58',	'7',	'null',	'',	''),
(710,	'59',	'7',	'null',	'',	''),
(711,	'60',	'7',	'null',	'',	''),
(712,	'61',	'7',	'null',	'',	''),
(713,	'62',	'7',	'null',	'',	''),
(714,	'63',	'7',	'null',	'',	''),
(715,	'64',	'7',	'null',	'',	''),
(716,	'65',	'7',	'null',	'',	''),
(717,	'66',	'7',	'null',	'',	''),
(718,	'67',	'7',	'null',	'',	''),
(719,	'68',	'7',	'null',	'',	''),
(720,	'69',	'7',	'null',	'',	''),
(721,	'70',	'7',	'null',	'',	''),
(722,	'71',	'7',	'null',	'',	''),
(723,	'72',	'7',	'null',	'',	''),
(724,	'73',	'7',	'null',	'',	''),
(725,	'74',	'7',	'null',	'',	''),
(726,	'75',	'7',	'null',	'',	''),
(727,	'76',	'7',	'null',	'',	''),
(728,	'77',	'7',	'null',	'',	''),
(729,	'78',	'7',	'null',	'',	''),
(730,	'79',	'7',	'null',	'',	''),
(731,	'80',	'7',	'null',	'',	''),
(732,	'81',	'7',	'null',	'',	''),
(733,	'82',	'7',	'null',	'',	''),
(734,	'83',	'7',	'null',	'',	''),
(735,	'84',	'7',	'null',	'',	''),
(736,	'85',	'7',	'null',	'',	''),
(737,	'86',	'7',	'null',	'',	''),
(738,	'87',	'7',	'null',	'',	''),
(739,	'88',	'7',	'null',	'',	''),
(740,	'89',	'7',	'null',	'',	''),
(741,	'90',	'7',	'null',	'',	''),
(742,	'91',	'7',	'null',	'',	''),
(743,	'92',	'7',	'null',	'',	''),
(744,	'93',	'7',	'null',	'',	''),
(745,	'94',	'7',	'null',	'',	''),
(746,	'95',	'7',	'null',	'',	''),
(747,	'96',	'7',	'null',	'',	''),
(748,	'97',	'7',	'null',	'',	''),
(749,	'98',	'7',	'null',	'',	''),
(750,	'99',	'7',	'null',	'',	''),
(751,	'100',	'7',	'null',	'',	''),
(752,	'101',	'7',	'null',	'',	''),
(753,	'102',	'7',	'null',	'',	''),
(754,	'103',	'7',	'null',	'',	''),
(755,	'104',	'7',	'null',	'',	''),
(756,	'105',	'7',	'null',	'',	''),
(757,	'106',	'7',	'null',	'',	''),
(758,	'107',	'7',	'null',	'',	''),
(759,	'108',	'7',	'null',	'',	''),
(760,	'109',	'7',	'null',	'',	''),
(761,	'110',	'7',	'null',	'',	''),
(762,	'111',	'7',	'null',	'',	''),
(763,	'112',	'7',	'null',	'',	''),
(764,	'113',	'7',	'null',	'',	''),
(765,	'114',	'7',	'null',	'',	''),
(766,	'115',	'7',	'null',	'',	''),
(767,	'116',	'7',	'null',	'',	''),
(768,	'117',	'7',	'null',	'',	''),
(769,	'118',	'7',	'null',	'',	''),
(770,	'119',	'7',	'null',	'',	''),
(771,	'120',	'7',	'null',	'',	''),
(772,	'121',	'7',	'null',	'',	''),
(773,	'122',	'7',	'null',	'',	''),
(774,	'123',	'7',	'null',	'',	''),
(775,	'124',	'7',	'null',	'',	''),
(776,	'125',	'7',	'null',	'',	''),
(777,	'126',	'7',	'null',	'',	''),
(778,	'127',	'7',	'null',	'',	''),
(779,	'128',	'7',	'null',	'',	''),
(780,	'129',	'7',	'null',	'',	''),
(781,	'130',	'7',	'null',	'',	''),
(782,	'131',	'7',	'null',	'',	''),
(783,	'132',	'7',	'null',	'',	''),
(784,	'133',	'7',	'null',	'',	''),
(785,	'134',	'7',	'null',	'',	''),
(786,	'135',	'7',	'null',	'',	''),
(787,	'136',	'7',	'null',	'',	''),
(788,	'137',	'7',	'null',	'',	''),
(789,	'138',	'7',	'null',	'',	''),
(790,	'139',	'7',	'null',	'',	''),
(791,	'140',	'7',	'null',	'',	''),
(792,	'141',	'7',	'null',	'',	''),
(793,	'142',	'7',	'null',	'',	''),
(794,	'143',	'7',	'null',	'',	''),
(795,	'144',	'7',	'null',	'',	''),
(796,	'145',	'7',	'null',	'',	''),
(797,	'146',	'7',	'null',	'',	''),
(798,	'147',	'7',	'null',	'',	''),
(799,	'148',	'7',	'null',	'',	''),
(800,	'149',	'7',	'null',	'',	''),
(801,	'150',	'7',	'null',	'',	''),
(802,	'151',	'7',	'null',	'',	''),
(803,	'152',	'7',	'null',	'',	''),
(804,	'153',	'7',	'null',	'',	''),
(805,	'154',	'7',	'null',	'',	''),
(806,	'155',	'7',	'null',	'',	''),
(807,	'156',	'7',	'null',	'',	''),
(808,	'157',	'7',	'null',	'',	''),
(809,	'158',	'7',	'null',	'',	''),
(810,	'159',	'7',	'null',	'',	''),
(811,	'160',	'7',	'null',	'',	''),
(812,	'161',	'7',	'null',	'',	''),
(813,	'1',	'8',	'null',	'',	''),
(814,	'2',	'8',	'null',	'',	''),
(815,	'3',	'8',	'null',	'',	''),
(816,	'4',	'8',	'null',	'',	''),
(817,	'5',	'8',	'null',	'',	''),
(818,	'6',	'8',	'null',	'',	''),
(819,	'7',	'8',	'null',	'',	''),
(820,	'8',	'8',	'null',	'',	''),
(821,	'9',	'8',	'null',	'',	''),
(822,	'10',	'8',	'null',	'',	''),
(823,	'11',	'8',	'null',	'',	''),
(824,	'12',	'8',	'null',	'',	''),
(825,	'13',	'8',	'null',	'',	''),
(826,	'14',	'8',	'null',	'',	''),
(827,	'15',	'8',	'null',	'',	''),
(828,	'16',	'8',	'null',	'',	''),
(829,	'17',	'8',	'null',	'',	''),
(830,	'18',	'8',	'null',	'',	''),
(831,	'19',	'8',	'null',	'',	''),
(832,	'20',	'8',	'null',	'',	''),
(833,	'21',	'8',	'null',	'',	''),
(834,	'22',	'8',	'null',	'',	''),
(835,	'23',	'8',	'null',	'',	''),
(836,	'24',	'8',	'null',	'',	''),
(837,	'25',	'8',	'null',	'',	''),
(838,	'26',	'8',	'null',	'',	''),
(839,	'27',	'8',	'null',	'',	''),
(840,	'28',	'8',	'null',	'',	''),
(841,	'29',	'8',	'null',	'',	''),
(842,	'30',	'8',	'null',	'',	''),
(843,	'31',	'8',	'null',	'',	''),
(844,	'32',	'8',	'null',	'',	''),
(845,	'33',	'8',	'null',	'',	''),
(846,	'34',	'8',	'null',	'',	''),
(847,	'35',	'8',	'null',	'',	''),
(848,	'36',	'8',	'null',	'',	''),
(849,	'37',	'8',	'null',	'',	''),
(850,	'38',	'8',	'null',	'',	''),
(851,	'39',	'8',	'null',	'',	''),
(852,	'40',	'8',	'null',	'',	''),
(853,	'41',	'8',	'null',	'',	''),
(854,	'42',	'8',	'null',	'',	''),
(855,	'43',	'8',	'null',	'',	''),
(856,	'44',	'8',	'null',	'',	''),
(857,	'45',	'8',	'null',	'',	''),
(858,	'46',	'8',	'null',	'',	''),
(859,	'47',	'8',	'null',	'',	''),
(860,	'48',	'8',	'null',	'',	''),
(861,	'49',	'8',	'null',	'',	''),
(862,	'50',	'8',	'null',	'',	''),
(863,	'51',	'8',	'null',	'',	''),
(864,	'52',	'8',	'null',	'',	''),
(865,	'53',	'8',	'null',	'',	''),
(866,	'54',	'8',	'null',	'',	''),
(867,	'55',	'8',	'null',	'',	''),
(868,	'56',	'8',	'null',	'',	''),
(869,	'57',	'8',	'null',	'',	''),
(870,	'58',	'8',	'null',	'',	''),
(871,	'59',	'8',	'null',	'',	''),
(872,	'60',	'8',	'null',	'',	''),
(873,	'61',	'8',	'null',	'',	''),
(874,	'62',	'8',	'null',	'',	''),
(875,	'63',	'8',	'null',	'',	''),
(876,	'64',	'8',	'null',	'',	''),
(877,	'65',	'8',	'null',	'',	''),
(878,	'66',	'8',	'null',	'',	''),
(879,	'67',	'8',	'null',	'',	''),
(880,	'68',	'8',	'null',	'',	''),
(881,	'69',	'8',	'null',	'',	''),
(882,	'70',	'8',	'null',	'',	''),
(883,	'71',	'8',	'null',	'',	''),
(884,	'72',	'8',	'null',	'',	''),
(885,	'73',	'8',	'null',	'',	''),
(886,	'74',	'8',	'null',	'',	''),
(887,	'75',	'8',	'null',	'',	''),
(888,	'76',	'8',	'null',	'',	''),
(889,	'77',	'8',	'null',	'',	''),
(890,	'78',	'8',	'null',	'',	''),
(891,	'79',	'8',	'null',	'',	''),
(892,	'80',	'8',	'null',	'',	''),
(893,	'81',	'8',	'null',	'',	''),
(894,	'82',	'8',	'null',	'',	''),
(895,	'83',	'8',	'null',	'',	''),
(896,	'84',	'8',	'null',	'',	''),
(897,	'85',	'8',	'null',	'',	''),
(898,	'86',	'8',	'null',	'',	''),
(899,	'87',	'8',	'null',	'',	''),
(900,	'88',	'8',	'null',	'',	''),
(901,	'89',	'8',	'null',	'',	''),
(902,	'90',	'8',	'null',	'',	''),
(903,	'91',	'8',	'null',	'',	''),
(904,	'92',	'8',	'null',	'',	''),
(905,	'93',	'8',	'null',	'',	''),
(906,	'94',	'8',	'null',	'',	''),
(907,	'95',	'8',	'null',	'',	''),
(908,	'96',	'8',	'null',	'',	''),
(909,	'97',	'8',	'null',	'',	''),
(910,	'98',	'8',	'null',	'',	''),
(911,	'99',	'8',	'null',	'',	''),
(912,	'100',	'8',	'null',	'',	''),
(913,	'101',	'8',	'null',	'',	''),
(914,	'102',	'8',	'null',	'',	''),
(915,	'103',	'8',	'null',	'',	''),
(916,	'104',	'8',	'null',	'',	''),
(917,	'105',	'8',	'null',	'',	''),
(918,	'106',	'8',	'null',	'',	''),
(919,	'107',	'8',	'null',	'',	''),
(920,	'108',	'8',	'null',	'',	''),
(921,	'109',	'8',	'null',	'',	''),
(922,	'110',	'8',	'null',	'',	''),
(923,	'111',	'8',	'null',	'',	''),
(924,	'112',	'8',	'null',	'',	''),
(925,	'113',	'8',	'null',	'',	''),
(926,	'114',	'8',	'null',	'',	''),
(927,	'115',	'8',	'null',	'',	''),
(928,	'116',	'8',	'null',	'',	''),
(929,	'117',	'8',	'null',	'',	''),
(930,	'118',	'8',	'null',	'',	''),
(931,	'119',	'8',	'null',	'',	''),
(932,	'120',	'8',	'null',	'',	''),
(933,	'121',	'8',	'null',	'',	''),
(934,	'122',	'8',	'null',	'',	''),
(935,	'123',	'8',	'null',	'',	''),
(936,	'124',	'8',	'null',	'',	''),
(937,	'125',	'8',	'null',	'',	''),
(938,	'126',	'8',	'null',	'',	''),
(939,	'127',	'8',	'null',	'',	''),
(940,	'128',	'8',	'null',	'',	''),
(941,	'129',	'8',	'null',	'',	''),
(942,	'130',	'8',	'null',	'',	''),
(943,	'131',	'8',	'null',	'',	''),
(944,	'132',	'8',	'null',	'',	''),
(945,	'133',	'8',	'null',	'',	''),
(946,	'134',	'8',	'null',	'',	''),
(947,	'135',	'8',	'null',	'',	''),
(948,	'136',	'8',	'null',	'',	''),
(949,	'137',	'8',	'null',	'',	''),
(950,	'138',	'8',	'null',	'',	''),
(951,	'139',	'8',	'null',	'',	''),
(952,	'140',	'8',	'null',	'',	''),
(953,	'141',	'8',	'null',	'',	''),
(954,	'142',	'8',	'null',	'',	''),
(955,	'143',	'8',	'null',	'',	''),
(956,	'144',	'8',	'null',	'',	''),
(957,	'145',	'8',	'null',	'',	''),
(958,	'146',	'8',	'null',	'',	''),
(959,	'147',	'8',	'null',	'',	''),
(960,	'148',	'8',	'null',	'',	''),
(961,	'149',	'8',	'null',	'',	''),
(962,	'150',	'8',	'null',	'',	''),
(963,	'151',	'8',	'null',	'',	''),
(964,	'152',	'8',	'null',	'',	''),
(965,	'153',	'8',	'null',	'',	''),
(966,	'154',	'8',	'null',	'',	''),
(967,	'155',	'8',	'null',	'',	''),
(968,	'156',	'8',	'null',	'',	''),
(969,	'157',	'8',	'null',	'',	''),
(970,	'158',	'8',	'null',	'',	''),
(971,	'159',	'8',	'null',	'',	''),
(972,	'160',	'8',	'null',	'',	''),
(973,	'161',	'8',	'null',	'',	''),
(974,	'1',	'9',	'null',	'',	''),
(975,	'2',	'9',	'null',	'',	''),
(976,	'3',	'9',	'null',	'',	''),
(977,	'4',	'9',	'null',	'',	''),
(978,	'5',	'9',	'null',	'',	''),
(979,	'6',	'9',	'null',	'',	''),
(980,	'7',	'9',	'null',	'',	''),
(981,	'8',	'9',	'null',	'',	''),
(982,	'9',	'9',	'null',	'',	''),
(983,	'10',	'9',	'null',	'',	''),
(984,	'11',	'9',	'null',	'',	''),
(985,	'12',	'9',	'null',	'',	''),
(986,	'13',	'9',	'null',	'',	''),
(987,	'14',	'9',	'null',	'',	''),
(988,	'15',	'9',	'null',	'',	''),
(989,	'16',	'9',	'null',	'',	''),
(990,	'17',	'9',	'null',	'',	''),
(991,	'18',	'9',	'null',	'',	''),
(992,	'19',	'9',	'null',	'',	''),
(993,	'20',	'9',	'null',	'',	''),
(994,	'21',	'9',	'null',	'',	''),
(995,	'22',	'9',	'null',	'',	''),
(996,	'23',	'9',	'null',	'',	''),
(997,	'24',	'9',	'null',	'',	''),
(998,	'25',	'9',	'null',	'',	''),
(999,	'26',	'9',	'null',	'',	''),
(1000,	'27',	'9',	'null',	'',	''),
(1001,	'28',	'9',	'null',	'',	''),
(1002,	'29',	'9',	'null',	'',	''),
(1003,	'30',	'9',	'null',	'',	''),
(1004,	'31',	'9',	'null',	'',	''),
(1005,	'32',	'9',	'null',	'',	''),
(1006,	'33',	'9',	'null',	'',	''),
(1007,	'34',	'9',	'null',	'',	''),
(1008,	'35',	'9',	'null',	'',	''),
(1009,	'36',	'9',	'null',	'',	''),
(1010,	'37',	'9',	'null',	'',	''),
(1011,	'38',	'9',	'null',	'',	''),
(1012,	'39',	'9',	'null',	'',	''),
(1013,	'40',	'9',	'null',	'',	''),
(1014,	'41',	'9',	'null',	'',	''),
(1015,	'42',	'9',	'null',	'',	''),
(1016,	'43',	'9',	'null',	'',	''),
(1017,	'44',	'9',	'null',	'',	''),
(1018,	'45',	'9',	'null',	'',	''),
(1019,	'46',	'9',	'null',	'',	''),
(1020,	'47',	'9',	'null',	'',	''),
(1021,	'48',	'9',	'null',	'',	''),
(1022,	'49',	'9',	'null',	'',	''),
(1023,	'50',	'9',	'null',	'',	''),
(1024,	'51',	'9',	'null',	'',	''),
(1025,	'52',	'9',	'null',	'',	''),
(1026,	'53',	'9',	'null',	'',	''),
(1027,	'54',	'9',	'null',	'',	''),
(1028,	'55',	'9',	'null',	'',	''),
(1029,	'56',	'9',	'null',	'',	''),
(1030,	'57',	'9',	'null',	'',	''),
(1031,	'58',	'9',	'null',	'',	''),
(1032,	'59',	'9',	'null',	'',	''),
(1033,	'60',	'9',	'null',	'',	''),
(1034,	'61',	'9',	'null',	'',	''),
(1035,	'62',	'9',	'null',	'',	''),
(1036,	'63',	'9',	'null',	'',	''),
(1037,	'64',	'9',	'null',	'',	''),
(1038,	'65',	'9',	'null',	'',	''),
(1039,	'66',	'9',	'null',	'',	''),
(1040,	'67',	'9',	'null',	'',	''),
(1041,	'68',	'9',	'null',	'',	''),
(1042,	'69',	'9',	'null',	'',	''),
(1043,	'70',	'9',	'null',	'',	''),
(1044,	'71',	'9',	'null',	'',	''),
(1045,	'72',	'9',	'null',	'',	''),
(1046,	'73',	'9',	'null',	'',	''),
(1047,	'74',	'9',	'null',	'',	''),
(1048,	'75',	'9',	'null',	'',	''),
(1049,	'76',	'9',	'null',	'',	''),
(1050,	'77',	'9',	'null',	'',	''),
(1051,	'78',	'9',	'null',	'',	''),
(1052,	'79',	'9',	'null',	'',	''),
(1053,	'80',	'9',	'null',	'',	''),
(1054,	'81',	'9',	'null',	'',	''),
(1055,	'82',	'9',	'null',	'',	''),
(1056,	'83',	'9',	'null',	'',	''),
(1057,	'84',	'9',	'null',	'',	''),
(1058,	'85',	'9',	'null',	'',	''),
(1059,	'86',	'9',	'null',	'',	''),
(1060,	'87',	'9',	'null',	'',	''),
(1061,	'88',	'9',	'null',	'',	''),
(1062,	'89',	'9',	'null',	'',	''),
(1063,	'90',	'9',	'null',	'',	''),
(1064,	'91',	'9',	'null',	'',	''),
(1065,	'92',	'9',	'null',	'',	''),
(1066,	'93',	'9',	'null',	'',	''),
(1067,	'94',	'9',	'null',	'',	''),
(1068,	'95',	'9',	'null',	'',	''),
(1069,	'96',	'9',	'null',	'',	''),
(1070,	'97',	'9',	'null',	'',	''),
(1071,	'98',	'9',	'null',	'',	''),
(1072,	'99',	'9',	'null',	'',	''),
(1073,	'100',	'9',	'null',	'',	''),
(1074,	'101',	'9',	'null',	'',	''),
(1075,	'102',	'9',	'null',	'',	''),
(1076,	'103',	'9',	'null',	'',	''),
(1077,	'104',	'9',	'null',	'',	''),
(1078,	'105',	'9',	'null',	'',	''),
(1079,	'106',	'9',	'null',	'',	''),
(1080,	'107',	'9',	'null',	'',	''),
(1081,	'108',	'9',	'null',	'',	''),
(1082,	'109',	'9',	'null',	'',	''),
(1083,	'110',	'9',	'null',	'',	''),
(1084,	'111',	'9',	'null',	'',	''),
(1085,	'112',	'9',	'null',	'',	''),
(1086,	'113',	'9',	'null',	'',	''),
(1087,	'114',	'9',	'null',	'',	''),
(1088,	'115',	'9',	'null',	'',	''),
(1089,	'116',	'9',	'null',	'',	''),
(1090,	'117',	'9',	'null',	'',	''),
(1091,	'118',	'9',	'null',	'',	''),
(1092,	'119',	'9',	'null',	'',	''),
(1093,	'120',	'9',	'null',	'',	''),
(1094,	'121',	'9',	'null',	'',	''),
(1095,	'122',	'9',	'null',	'',	''),
(1096,	'123',	'9',	'null',	'',	''),
(1097,	'124',	'9',	'null',	'',	''),
(1098,	'125',	'9',	'null',	'',	''),
(1099,	'126',	'9',	'null',	'',	''),
(1100,	'127',	'9',	'null',	'',	''),
(1101,	'128',	'9',	'null',	'',	''),
(1102,	'129',	'9',	'null',	'',	''),
(1103,	'130',	'9',	'null',	'',	''),
(1104,	'131',	'9',	'null',	'',	''),
(1105,	'132',	'9',	'null',	'',	''),
(1106,	'133',	'9',	'null',	'',	''),
(1107,	'134',	'9',	'null',	'',	''),
(1108,	'135',	'9',	'null',	'',	''),
(1109,	'136',	'9',	'null',	'',	''),
(1110,	'137',	'9',	'null',	'',	''),
(1111,	'138',	'9',	'null',	'',	''),
(1112,	'139',	'9',	'null',	'',	''),
(1113,	'140',	'9',	'null',	'',	''),
(1114,	'141',	'9',	'null',	'',	''),
(1115,	'142',	'9',	'null',	'',	''),
(1116,	'143',	'9',	'null',	'',	''),
(1117,	'144',	'9',	'null',	'',	''),
(1118,	'145',	'9',	'null',	'',	''),
(1119,	'146',	'9',	'null',	'',	''),
(1120,	'147',	'9',	'null',	'',	''),
(1121,	'148',	'9',	'null',	'',	''),
(1122,	'149',	'9',	'null',	'',	''),
(1123,	'150',	'9',	'null',	'',	''),
(1124,	'151',	'9',	'null',	'',	''),
(1125,	'152',	'9',	'null',	'',	''),
(1126,	'153',	'9',	'null',	'',	''),
(1127,	'154',	'9',	'null',	'',	''),
(1128,	'155',	'9',	'null',	'',	''),
(1129,	'156',	'9',	'null',	'',	''),
(1130,	'157',	'9',	'null',	'',	''),
(1131,	'158',	'9',	'null',	'',	''),
(1132,	'159',	'9',	'null',	'',	''),
(1133,	'160',	'9',	'null',	'',	''),
(1134,	'161',	'9',	'null',	'',	''),
(1135,	'161',	'4',	NULL,	'',	'');

DROP TABLE IF EXISTS `l_pecas_supplier`;
CREATE TABLE `l_pecas_supplier` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL,
  `primery` varchar(11) DEFAULT '0',
  `ordem` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_pecas_tamanhos`;
CREATE TABLE `l_pecas_tamanhos` (
  `id` bigint(20) NOT NULL,
  `peca` int(11) DEFAULT NULL,
  `ref` varchar(250) DEFAULT NULL,
  `l_caract_categorias_en_id` int(11) DEFAULT NULL,
  `l_caract_opcoes_en_id` int(11) DEFAULT NULL,
  `car1` int(11) DEFAULT '0',
  `op1` int(11) DEFAULT '0',
  `car2` int(11) DEFAULT '0',
  `op2` int(11) DEFAULT '0',
  `car3` int(11) DEFAULT '0',
  `op3` int(11) DEFAULT '0',
  `car4` int(11) DEFAULT '0',
  `op4` int(11) DEFAULT '0',
  `car5` int(11) DEFAULT '0',
  `op5` int(11) DEFAULT '0',
  `preco` decimal(10,2) DEFAULT '0.00',
  `preco_f` decimal(10,2) DEFAULT NULL,
  `preco_old` decimal(10,2) DEFAULT '0.00' COMMENT 'Apenas usado nas Atualização de Preço',
  `preco_forn` decimal(10,2) DEFAULT '0.00',
  `peso` decimal(10,3) DEFAULT '0.000',
  `volume` decimal(10,3) DEFAULT '0.000',
  `stock` int(11) DEFAULT '0',
  `defeito` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`peca`,`ref`,`stock`,`defeito`,`l_caract_categorias_en_id`, `l_caract_opcoes_en_id`,`car1`,`op1`,`car2`,`op2`,`car3`,`op3`,`car4`,`op4`,`car5`,`op5`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_pecas_tamanhos` (`id`, `peca`, `ref`,`l_caract_categorias_en_id`, `l_caract_opcoes_en_id`, `car1`, `op1`, `car2`, `op2`, `car3`, `op3`, `car4`, `op4`, `car5`, `op5`, `preco`, `preco_f`, `preco_old`, `preco_forn`, `peso`, `volume`, `stock`, `defeito`) VALUES
(1,	161,	NULL,	1,	4,	0,	0,	0,	0,	0,	0,	0,	0,	0.00,	NULL,	0.00,	0.00,	0.000,	0.000,	0,	0),
(2,	161,	NULL,	1,	5,	0,	0,	0,	0,	0,	0,	0,	0,	0.00,	NULL,	0.00,	0.00,	0.000,	0.000,	0,	0),
(3,	9,	NULL,	2,	10,	3,	11,	1,	4,	0,	0,	0,	0,	0.00,	NULL,	0.00,	0.00,	0.000,	0.000,	0,	0),
(4,	9,	NULL,	2,	10,	3,	11,	1,	5,	0,	0,	0,	0,	0.00,	NULL,	0.00,	0.00,	0.000,	0.000,	0,	0);

DROP TABLE IF EXISTS `l_precos_historial`;
CREATE TABLE `l_precos_historial` (
  `id` int(11) NOT NULL,
  `geral` tinyint(4) DEFAULT '0' COMMENT '1 - Geral',
  `id_categoria` int(11) DEFAULT '0',
  `id_marca` int(11) DEFAULT '0',
  `tipo` tinyint(4) DEFAULT '0' COMMENT '1 - Somar; 2 - Subtrair',
  `valor` decimal(10,2) DEFAULT '0.00',
  `data` datetime DEFAULT NULL,
  `revertido` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`geral`,`id_categoria`,`id_marca`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_promocoes_en`;
CREATE TABLE `l_promocoes_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `pagina` int(11) DEFAULT '0',
  `titulo` varchar(20) DEFAULT NULL,
  `texto` text,
  `desconto` decimal(10,2) DEFAULT '0.00',
  `id_area` int(11) DEFAULT '0',
  `id_categoria` int(11) DEFAULT '0',
  `id_marca` int(11) DEFAULT '0',
  `id_peca` int(11) DEFAULT '0',
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_area`,`id_categoria`,`id_marca`,`id_peca`,`datai`,`dataf`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_promocoes_en` (`id`, `nome`, `pagina`, `titulo`, `texto`, `desconto`, `id_area`, `id_categoria`, `id_marca`, `id_peca`, `datai`, `dataf`, `visivel`) VALUES
(1,	'vishal',	0,	'título',	'<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Lorem ipsum dolor sit amet, consectetur adipiscing elit. </font><font style=\"vertical-align: inherit;\">Donec tempus sed risus id commodo. </font><font style=\"vertical-align: inherit;\">Etiam venenatis bibendum lorem, porttitor egestas dui maximus eget.</font></font>',	35.00,	0,	13,	1,	2,	'2020-11-23',	'2020-12-30',	1),
(2,	'teste',	0,	'título',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus sed risus id commodo. Etiam venenatis bibendum lorem, porttitor egestas dui maximus eget.',	20.00,	0,	2,	0,	0,	'2020-09-30',	'2020-09-30',	1);

DROP TABLE IF EXISTS `l_promocoes_pt`;
CREATE TABLE `l_promocoes_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `pagina` int(11) DEFAULT '0',
  `titulo` varchar(20) DEFAULT NULL,
  `texto` text,
  `desconto` decimal(10,2) DEFAULT '0.00',
  `id_area` int(11) DEFAULT '0',
  `id_categoria` int(11) DEFAULT '0',
  `id_marca` int(11) DEFAULT '0',
  `id_peca` int(11) DEFAULT '0',
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_area`,`id_categoria`,`id_marca`,`id_peca`,`datai`,`dataf`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_promocoes_pt` (`id`, `nome`, `pagina`, `titulo`, `texto`, `desconto`, `id_area`, `id_categoria`, `id_marca`, `id_peca`, `datai`, `dataf`, `visivel`) VALUES
(1,	'Promoção de Verão',	0,	'título',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus sed risus id commodo. Etiam venenatis bibendum lorem, porttitor egestas dui maximus eget.',	35.00,	0,	2,	0,	0,	'2020-06-23',	'2020-09-30',	1),
(2,	'teste',	0,	'título',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus sed risus id commodo. Etiam venenatis bibendum lorem, porttitor egestas dui maximus eget.',	20.00,	0,	2,	0,	0,	'2020-09-30',	'2020-09-30',	1);

DROP TABLE IF EXISTS `l_promocoes_textos_en`;
CREATE TABLE `l_promocoes_textos_en` (
  `id` int(11) NOT NULL,
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `titulo` varchar(20) DEFAULT NULL,
  `texto` text,
  `pagina` int(11) DEFAULT '0',
  `texto_listagem` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `l_promocoes_textos_pt`;
CREATE TABLE `l_promocoes_textos_pt` (
  `id` int(11) NOT NULL,
  `datai` date DEFAULT NULL,
  `dataf` date DEFAULT NULL,
  `titulo` varchar(20) DEFAULT NULL,
  `texto` text,
  `pagina` int(11) DEFAULT '0',
  `texto_listagem` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `l_promocoes_textos_pt` (`id`, `datai`, `dataf`, `titulo`, `texto`, `pagina`, `texto_listagem`) VALUES
(1,	'2020-06-19',	'2020-06-25',	'título',	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tempus sed risus id commodo. Etiam venenatis bibendum lorem, porttitor egestas dui maximus eget.',	6,	'');

DROP TABLE IF EXISTS `menus_paginas_en`;
CREATE TABLE `menus_paginas_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `menus_paginas_pt`;
CREATE TABLE `menus_paginas_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `metatags_en`;
CREATE TABLE `metatags_en` (
  `id` int(11) NOT NULL,
  `pagina` varchar(250) DEFAULT NULL,
  `title` text,
  `description` text,
  `keywords` text,
  `blog` int(11) DEFAULT '0',
  `url` varchar(250) DEFAULT NULL,
  `ficheiro` varchar(250) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  `editar` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`,`editar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `metatags_en` (`id`, `pagina`, `title`, `description`, `keywords`, `blog`, `url`, `ficheiro`, `visivel`, `ordem`, `editar`) VALUES
(1,	'Homepage',	NULL,	NULL,	NULL,	0,	'index.php',	'index.php',	1,	99,	1),
(2,	'Store-locater',	'Store-locater',	'Store-locater',	'Store-locater',	0,	'store-locater.php',	'store-locater.php',	1,	99,	1),
(3,	'Produtos',	'Produtos',	'Produtos',	'Produtos',	0,	'produtos.php',	'produtos.php',	1,	99,	1),
(4,	'Novidades',	'Novidades',	'Novidades',	'Novidades',	0,	NULL,	NULL,	1,	99,	1),
(5,	'Promoções',	'Promoções',	'Promoções',	'Promoções',	0,	NULL,	NULL,	1,	99,	1),
(6,	'Loja Online',	'Loja Online',	'Loja Online',	'Loja Online',	0,	NULL,	NULL,	1,	99,	1),
(7,	'Faqs',	'Faqs',	'Faqs',	'Faqs',	0,	'faqs.php',	'faqs.php',	1,	99,	1);

DROP TABLE IF EXISTS `metatags_pt`;
CREATE TABLE `metatags_pt` (
  `id` int(11) NOT NULL,
  `pagina` varchar(250) DEFAULT NULL,
  `title` text,
  `description` text,
  `keywords` text,
  `blog` int(11) DEFAULT '0',
  `url` varchar(250) DEFAULT NULL,
  `ficheiro` varchar(250) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  `editar` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`,`editar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `metatags_pt` (`id`, `pagina`, `title`, `description`, `keywords`, `blog`, `url`, `ficheiro`, `visivel`, `ordem`, `editar`) VALUES
(1,	'Homepage',	NULL,	NULL,	NULL,	0,	'index.php',	'index.php',	1,	1,	0),
(2,	'Contactos',	'Store-locater',	'Store-locater',	'Store-locater',	0,	'store-locater.php',	'contactos.php',	1,	99,	1),
(3,	'Produtos',	'Produtos',	'Produtos',	'Produtos',	0,	'produtos.php',	'produtos.php',	1,	99,	1),
(4,	'Novidades',	'Novidades',	'Novidades',	'Novidades',	0,	'',	'',	1,	99,	1),
(5,	'Promoções',	'Promoções',	'Promoções',	'Promoções',	0,	'',	'',	1,	99,	1),
(6,	'Loja Online',	'Loja Online',	'Loja Online',	'Loja Online',	0,	'loja',	'',	1,	99,	1),
(7,	'Faqs',	'Faqs',	'Faqs',	'Faqs',	0,	'faqs.php',	'faqs.php',	1,	99,	1);

DROP TABLE IF EXISTS `met_envio_en`;
CREATE TABLE `met_envio_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `descricao` text,
  `link` varchar(150) DEFAULT NULL,
  `visivel_footer` tinyint(4) DEFAULT '0',
  `imagem` varchar(350) DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel_footer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `met_envio_en` (`id`, `nome`, `ordem`, `descricao`, `link`, `visivel_footer`, `imagem`, `imagem2`) VALUES
(1,	'CTT - 19 M Continente',	1,	'Delivery the next day until 7 pm. There are 2 delivery attempts.',	'http://www.ctt.pt/feapl_2/app/open/tools.jspx?tool=0',	0,	'ctt.png',	'ctt_ft.svg'),
(2,	'Chronopost International',	2,	'Deliver to all europe in 48h.',	'http://www.chronopost.fr/transport-express/livraison-colis/tracking',	0,	'chronopost.png',	'chronopost_ft.svg'),
(3,	'Levantamento na Loja',	5,	NULL,	NULL,	0,	'loja.png',	'loja_ft.png'),
(4,	'MRW',	4,	'Next day delivery until 7pm.',	'http://www.mrw.pt/seguimiento_envios/MRW_paqueteria_nacional_multiple.asp',	0,	'dll.png',	'dll_ft.svg'),
(5,	'Chronopost - Pickup',	3,	'Levante a sua encomenda num Ponto Pickup perto de si. Ideal para um horário alargado. Selecione para ver os Pontos Pickup disponíveis.',	'http://www.chronopost.pt/',	0,	'chronopost.png',	'chronopost_ft.svg'),
(6,	'CTT Prá Amanhã - Continente',	99,	'Next day delivery until 7pm. Has 1 delivery attempt.',	'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx',	0,	'094223_1_3282_ctt.png',	NULL),
(8,	'Store Pickup',	99,	'',	'',	1,	'100642_1_3550_codigos.png',	NULL),
(10,	'MRW',	99,	'',	'',	0,	'022952_1_8122_mrw-2.png',	'050028_2_6807_022952_1_8122_mrw-2.png'),
(11,	'DPD UK Shipping',	99,	'DPD Tracked Delivery 2-3 working days - from £6.99 (Free Delivery When you spend £100): £6.99',	'',	0,	'014437_1_1113_DPDfeature.png',	NULL),
(12,	'Royal Mail',	99,	'Royal Mail / DPD Tracked Delivery 3-5 working days - from £3.99: £3.99',	'',	0,	'014627_1_2769_download.png',	NULL),
(13,	'Royal Mail / DPD Delivery',	99,	'Royal Mail / DPD Delivery 5-7 working days - from Â£2.99 (Free Delivery When you spend Â£50): Â£2.99',	'',	0,	NULL,	NULL),
(14,	'Home Delivery',	99,	NULL,	NULL,	1,	NULL,	NULL);

DROP TABLE IF EXISTS `met_envio_pt`;
CREATE TABLE `met_envio_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `descricao` text,
  `link` varchar(150) DEFAULT NULL,
  `visivel_footer` tinyint(4) DEFAULT '0',
  `imagem` varchar(350) DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel_footer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `met_envio_pt` (`id`, `nome`, `ordem`, `descricao`, `link`, `visivel_footer`, `imagem`, `imagem2`) VALUES
(1,	'CTT - 19 M Continente',	1,	'Entrega no dia seguinte até às 19H. Tem 2 tentativas de entrega.',	'http://www.ctt.pt/feapl_2/app/open/tools.jspx?tool=0',	0,	'ctt.png',	'ctt_ft.svg'),
(2,	'Chronopost International',	2,	'Deliver to all europe in 48h.',	'http://www.chronopost.fr/transport-express/livraison-colis/tracking',	0,	'chronopost.png',	'chronopost_ft.svg'),
(3,	'Levantamento na Loja',	5,	NULL,	NULL,	0,	'loja.png',	'loja_ft.png'),
(4,	'MRW - Amanhã 19H',	4,	'Entrega no dia seguinte até às 19H.',	'http://www.mrw.pt/seguimiento_envios/MRW_paqueteria_nacional_multiple.asp',	0,	'dll.png',	'dll_ft.svg'),
(5,	'Chronopost - Pickup',	3,	'Levante a sua encomenda num Ponto Pickup perto de si. Ideal para um horário alargado. Selecione para ver os Pontos Pickup disponíveis.',	'http://www.chronopost.pt/',	0,	'chronopost.png',	'chronopost_ft.svg'),
(6,	'CTT Prá Amanhã - Continente',	99,	'Entrega no dia seguinte até às 19H. Tem 1 tentativa de entrega.',	'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx',	0,	'094223_1_3282_ctt.png',	NULL),
(7,	'CTT Economy',	99,	'Entrega padrão de 3 a 5 dias.',	'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx',	0,	NULL,	NULL),
(8,	'Store Pickup',	99,	'',	'',	1,	'100642_1_3550_codigos.png',	NULL),
(9,	'CTT',	99,	'',	'http://www.ctt.pt/feapl_2/app/open/tools.jspx?tool=0',	1,	'053131_1_3338_ctt.png',	'053131_2_3829_ctt.png'),
(10,	'MRW',	99,	'',	'',	1,	'022952_1_8122_mrw-2.png',	'050028_2_6807_022952_1_8122_mrw-2.png'),
(11,	'DPD UK Shipping',	99,	'DPD Tracked Delivery 2-3 working days - from £6.99 (Free Delivery When you spend £100): £6.99',	'',	1,	'014437_1_1113_DPDfeature.png',	NULL),
(12,	'Royal Mail / DPD Tracked',	99,	'Royal Mail / DPD Tracked Delivery 3-5 working days - from £3.99: £3.99\r\n',	'',	1,	'014627_1_2769_download.png',	NULL),
(13,	'Royal Mail / DPD Delivery',	99,	'Royal Mail / DPD Delivery 5-7 working days - from £2.99 (Free Delivery When you spend £50): £2.99',	'',	1,	NULL,	NULL),
(14,	'Home Delivery',	99,	NULL,	NULL,	1,	NULL,	NULL);

DROP TABLE IF EXISTS `met_pagamento_en`;
CREATE TABLE `met_pagamento_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `nome_interno` varchar(250) DEFAULT NULL,
  `descricao` text,
  `descricao2` text,
  `imagem` varchar(350) DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `client_key` varchar(250) DEFAULT NULL,
  `service_key` varchar(250) DEFAULT NULL,
  `paypal_key` varchar(250) DEFAULT NULL,
  `entidade` varchar(20) DEFAULT NULL,
  `subentidade` varchar(20) DEFAULT NULL,
  `valor_encomenda` decimal(10,2) DEFAULT '0.00',
  `valor_encomenda2` decimal(10,2) DEFAULT '0.00',
  `visivel_footer` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`,`visivel_footer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `met_pagamento_en` (`id`, `nome`, `nome_interno`, `descricao`, `descricao2`, `imagem`, `imagem2`, `email`, `client_key`, `service_key`, `paypal_key`, `entidade`, `subentidade`, `valor_encomenda`, `valor_encomenda2`, `visivel_footer`, `ordem`, `visivel`) VALUES
(1,	'Paypal/Cartão de Crédito',	'PayPal',	NULL,	'<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Pay with your credit card through PayPal. Transactions are 100% secure.</font></font>',	'paypal.png',	'paypal_ft.png',	'',	NULL,	NULL,	'',	NULL,	NULL,	0.00,	1500.00,	0,	6,	0),
(2,	'Transferência Bancária',	'Transferência Bancária',	'<strong>NIB:</strong> 0000 0000 00000000 000 00<br />\r\n<strong>IBAN:</strong> PT50 0000 0000 00000000000 00',	'Ap&oacute;s a compra tem 48h para efetuar a transfer&ecirc;ncia. Caso contr&aacute;rio a encomenda ser&aacute; anulada.<br />\r\n<br />\r\nPor favor utilize os seguintes dados para solicitar ao seu banco para proceder &agrave; transfer&ecirc;ncia banc&aacute;ria:<br />\r\n<br />\r\n<strong>BIC SWIFT:</strong> CGDIPTPL (Quando necess&aacute;rio) Descritivo: N&uacute;mero da encomenda<br />\r\nN&atilde;o se esque&ccedil;a de mencionar a refer&ecirc;ncia da sua encomenda.<br />\r\n<br />\r\nA <strong>loja</strong> n&atilde;o se responsabiliza por quaisquer atrasos provocados pela falta desta informa&ccedil;&atilde;o.<br />\r\n<br />\r\n<strong>ATEN&Ccedil;&Atilde;O</strong><br />\r\nEnvie por favor, o comprovativo da transfer&ecirc;ncia com a indica&ccedil;&atilde;o da refer&ecirc;ncia da<br />\r\nencomenda, para o Email: <strong>email</strong><br />\r\n<br />\r\nO envio do comprovativo &eacute; obrigat&oacute;rio!',	'transferencia.png',	'transferencia_ft.png',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	5000.00,	0,	2,	0),
(3,	'Cobrança',	'Cobrança',	'',	'',	'cobranca.png',	'cobranca_ft.png',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	0,	4,	0),
(4,	'Loja Física',	'Loja Física',	NULL,	NULL,	'escritorio.png',	'escritorio_ft.png',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	0,	5,	0),
(5,	'Envio de Cheque',	'Envio de Cheque',	'11',	'2245',	'cheque.png',	'cheque_ft.png',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	0,	1,	0),
(6,	'Multibanco',	'Multibanco IfThenPay',	'',	'<div style=\"text-align:center\">O tal&atilde;o emitido pela caixa autom&aacute;tica<br />\r\nfaz prova de pagamento. Conserve-o!</div>\r\n',	'multibanco.png',	'mb_ft.svg',	'11111',	NULL,	NULL,	NULL,	'11111',	'111',	10.00,	100.00,	0,	3,	0),
(7,	'Multibanco',	'Multibanco Easypay',	'',	'',	'multibanco.png',	'mb_ft.svg',	'',	NULL,	NULL,	NULL,	'',	'',	0.00,	0.00,	0,	99,	0),
(8,	'Cartão de Crédito',	'Cartão de Crédito Easypay',	'',	'',	'',	NULL,	'',	NULL,	NULL,	NULL,	'',	'',	0.00,	0.00,	0,	99,	0),
(9,	'Cash On Delevey',	'Cash On Delevey',	'',	'',	'074527_1_8848_download.jpg',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	0,	99,	1),
(10,	'WorldPay',	'WorldPay',	'<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Pay with your credit card through WorldPay. Transactions are 100% secure.</font></font>',	'',	NULL,	'082842_2_1986_payment-cards.png',	NULL,	'T_C_2272dab2-95ab-42a0-ab07-afecbcab1cc1',	'T_S_abeb4f22-0355-43bc-8ed3-6f07b0bfd082',	NULL,	NULL,	NULL,	0.00,	0.00,	1,	7,	1);

DROP TABLE IF EXISTS `met_pagamento_pt`;
CREATE TABLE `met_pagamento_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `nome_interno` varchar(250) DEFAULT NULL,
  `descricao` text,
  `descricao2` text,
  `imagem` varchar(350) DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `paypal_key` varchar(250) DEFAULT NULL,
  `entidade` varchar(20) DEFAULT NULL,
  `subentidade` varchar(20) DEFAULT NULL,
  `valor_encomenda` decimal(10,2) DEFAULT '0.00',
  `valor_encomenda2` decimal(10,2) DEFAULT '0.00',
  `visivel_footer` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`visivel`,`visivel_footer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `met_pagamento_pt` (`id`, `nome`, `nome_interno`, `descricao`, `descricao2`, `imagem`, `imagem2`, `email`, `paypal_key`, `entidade`, `subentidade`, `valor_encomenda`, `valor_encomenda2`, `visivel_footer`, `ordem`, `visivel`) VALUES
(1,	'Paypal/Cartão de Crédito',	'PayPal',	NULL,	'<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Pay with your credit card through PayPal. Transactions are 100% secure.</font></font>',	'paypal.png',	'paypal_ft.png',	'',	'',	NULL,	NULL,	0.00,	1500.00,	1,	6,	1),
(2,	'Transferência Bancária',	'Transferência Bancária',	'<strong>NIB:</strong> 0000 0000 00000000 000 00<br />\r\n<strong>IBAN:</strong> PT50 0000 0000 00000000000 00',	'Ap&oacute;s a compra tem 48h para efetuar a transfer&ecirc;ncia. Caso contr&aacute;rio a encomenda ser&aacute; anulada.<br />\r\n<br />\r\nPor favor utilize os seguintes dados para solicitar ao seu banco para proceder &agrave; transfer&ecirc;ncia banc&aacute;ria:<br />\r\n<br />\r\n<strong>BIC SWIFT:</strong> CGDIPTPL (Quando necess&aacute;rio) Descritivo: N&uacute;mero da encomenda<br />\r\nN&atilde;o se esque&ccedil;a de mencionar a refer&ecirc;ncia da sua encomenda.<br />\r\n<br />\r\nA <strong>loja</strong> n&atilde;o se responsabiliza por quaisquer atrasos provocados pela falta desta informa&ccedil;&atilde;o.<br />\r\n<br />\r\n<strong>ATEN&Ccedil;&Atilde;O</strong><br />\r\nEnvie por favor, o comprovativo da transfer&ecirc;ncia com a indica&ccedil;&atilde;o da refer&ecirc;ncia da<br />\r\nencomenda, para o Email: <strong>email</strong><br />\r\n<br />\r\nO envio do comprovativo &eacute; obrigat&oacute;rio!',	'transferencia.png',	'transferencia_ft.png',	NULL,	NULL,	NULL,	NULL,	0.00,	5000.00,	0,	2,	1),
(3,	'Cobrança',	'Cobrança',	'',	'',	'cobranca.png',	'cobranca_ft.png',	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	0,	4,	1),
(4,	'Loja Física',	'Loja Física',	NULL,	NULL,	'escritorio.png',	'escritorio_ft.png',	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	0,	5,	1),
(5,	'Envio de Cheque',	'Envio de Cheque',	'11',	'2245',	'cheque.png',	'cheque_ft.png',	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	1,	1,	1),
(6,	'Multibanco',	'Multibanco IfThenPay',	'',	'<div style=\"text-align:center\">O tal&atilde;o emitido pela caixa autom&aacute;tica<br />\r\nfaz prova de pagamento. Conserve-o!</div>\r\n',	'multibanco.png',	'mb_ft.svg',	'11111',	NULL,	'11111',	'111',	10.00,	100.00,	1,	3,	1),
(7,	'Multibanco',	'Multibanco Easypay',	'',	'',	'multibanco.png',	'mb_ft.svg',	'',	NULL,	'',	'',	0.00,	0.00,	0,	99,	0),
(8,	'Cartão de Crédito',	'Cartão de Crédito Easypay',	'',	'',	'',	NULL,	'',	NULL,	'',	'',	0.00,	0.00,	0,	99,	0),
(9,	'Cash On Delevey',	'Cash On Delevey',	'',	'',	'074527_1_8848_download.jpg',	NULL,	NULL,	NULL,	NULL,	NULL,	0.00,	0.00,	1,	99,	1);

DROP TABLE IF EXISTS `met_pag_envio`;
CREATE TABLE `met_pag_envio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `met_pagamento` int(11) DEFAULT NULL,
  `met_envio` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`met_pagamento`,`met_envio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `met_pag_envio` (`id`, `met_pagamento`, `met_envio`) VALUES
(80,	2,	1),
(81,	2,	2),
(82,	2,	3),
(83,	2,	4),
(84,	2,	5),
(85,	2,	6),
(86,	2,	7),
(87,	2,	9),
(44,	3,	1),
(45,	3,	2),
(46,	3,	4),
(47,	3,	5),
(22,	4,	3),
(1,	5,	1),
(2,	5,	2),
(3,	5,	4),
(4,	5,	5),
(91,	6,	9),
(92,	6,	10),
(203,	9,	8),
(204,	9,	14),
(238,	10,	8),
(239,	10,	11),
(240,	10,	12),
(241,	10,	13),
(242,	10,	14);

DROP TABLE IF EXISTS `moeda`;
CREATE TABLE `moeda` (
  `id` int(11) NOT NULL,
  `nome` varchar(10) DEFAULT NULL,
  `taxa` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `moeda` (`id`, `nome`, `taxa`) VALUES
(1,	'USD',	1.09700),
(2,	'GBP',	0.71900);

DROP TABLE IF EXISTS `moedas`;
CREATE TABLE `moedas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `taxa` decimal(10,5) DEFAULT '0.00000',
  `abreviatura` varchar(20) DEFAULT NULL,
  `simbolo` varchar(20) DEFAULT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `local` tinyint(4) DEFAULT '1' COMMENT '1 - Símbolo depois do valor; 2 - Símbolo antes do valor',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `moedas` (`id`, `nome`, `taxa`, `abreviatura`, `simbolo`, `codigo`, `local`, `visivel`) VALUES
(1,	'lb',	0.00000,	'lb',	'£',	'&pound;',	1,	1);

DROP TABLE IF EXISTS `newsletters`;
CREATE TABLE `newsletters` (
  `id` int(11) NOT NULL,
  `tipo_envio` tinyint(4) DEFAULT '1' COMMENT '1 - sendMail; 2 - Mailgun',
  `titulo` varchar(150) DEFAULT NULL,
  `topo` int(11) DEFAULT '0',
  `conteudo` int(11) DEFAULT '0',
  `tipo` int(11) DEFAULT '0',
  `nome_from` varchar(250) DEFAULT NULL,
  `email_from` varchar(250) DEFAULT NULL,
  `email_reply` varchar(250) DEFAULT NULL,
  `email_bounce` varchar(250) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_envio` date DEFAULT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `mailgun_key` varchar(255) DEFAULT NULL,
  `mailgun_dominio` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`topo`,`conteudo`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newsletters` (`id`, `tipo_envio`, `titulo`, `topo`, `conteudo`, `tipo`, `nome_from`, `email_from`, `email_reply`, `email_bounce`, `data_criacao`, `data_envio`, `codigo`, `mailgun_key`, `mailgun_dominio`) VALUES
(1,	1,	'Products Newslatter',	0,	3,	1,	'Rvkumar',	'rileshvashnav@gmail.com',	'rileshvashnav@gmail.com',	'rileshvashnav@gmail.com',	'2020-07-20 13:02:52',	'2020-07-29',	NULL,	'34030a1d6e10cc6ee3da5310fd39d197-ffefc4e4-1ad79006',	'sandbox1e31b7541c6a48bfa1810609d589dccc.mailgun.org'),
(2,	1,	'Test newsletter construction',	0,	4,	1,	'Vera',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'2020-07-23 14:03:02',	NULL,	NULL,	'34030a1d6e10cc6ee3da5310fd39d197-ffefc4e4-1ad79006',	'sandbox1e31b7541c6a48bfa1810609d589dccc.mailgun.org');

DROP TABLE IF EXISTS `newsletters_atualizacoes`;
CREATE TABLE `newsletters_atualizacoes` (
  `id` int(11) NOT NULL,
  `newsletter_id_historico` bigint(20) DEFAULT '0',
  `enviados` int(11) DEFAULT '0',
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`newsletter_id_historico`,`enviados`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `newsletters_config`;
CREATE TABLE `newsletters_config` (
  `id` int(11) NOT NULL,
  `mostra_tipo` tinyint(4) DEFAULT '0',
  `tipo_envio` tinyint(4) DEFAULT '1' COMMENT '1 - sendMail; 2 - Mailgun',
  `max_emails` int(11) NOT NULL COMMENT 'Número máximo de emails enviados permitidos por hora',
  `emails_sent` int(11) NOT NULL COMMENT 'Números de emails enviados na última hora',
  `nome_from` varchar(250) DEFAULT NULL,
  `email_from` varchar(250) DEFAULT NULL,
  `email_reply` varchar(250) DEFAULT NULL,
  `email_bounce` varchar(250) DEFAULT NULL,
  `mailgun_key` varchar(255) DEFAULT NULL,
  `mailgun_dominio` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `texto_link` varchar(30) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `texto_email1` varchar(30) DEFAULT NULL,
  `email1` varchar(50) DEFAULT NULL,
  `texto_email2` varchar(30) DEFAULT NULL,
  `email2` varchar(50) DEFAULT NULL,
  `texto_email3` varchar(30) DEFAULT NULL,
  `email3` varchar(50) DEFAULT NULL,
  `texto_email4` varchar(30) DEFAULT NULL,
  `email4` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newsletters_config` (`id`, `mostra_tipo`, `tipo_envio`, `max_emails`, `emails_sent`, `nome_from`, `email_from`, `email_reply`, `email_bounce`, `mailgun_key`, `mailgun_dominio`, `url`, `texto_link`, `telefone`, `texto_email1`, `email1`, `texto_email2`, `email2`, `texto_email3`, `email3`, `texto_email4`, `email4`) VALUES
(1,	1,	1,	500,	0,	'',	'webtech.dev@gmail.com',	'webtech.dev@gmail.com',	'webtech.dev@gmail.com',	'34030a1d6e10cc6ee3da5310fd39d197-ffefc4e4-1ad79006',	'sandbox1e31b7541c6a48bfa1810609d589dccc.mailgun.org',	'',	'',	'',	'INFORMATION',	'',	'ASSIST. TECHNIQUE',	'',	'TEXTILE DESIGN',	'',	'PUBLICITY',	'');

DROP TABLE IF EXISTS `newsletters_historico`;
CREATE TABLE `newsletters_historico` (
  `id` bigint(20) NOT NULL,
  `tipo_envio` tinyint(4) DEFAULT '1' COMMENT '1 - sendMail; 2 - Mailgun',
  `newsletter_id` int(11) DEFAULT '0',
  `grupo` int(11) DEFAULT '0',
  `utilizador` varchar(50) DEFAULT '0',
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `emails_total` int(11) DEFAULT '0',
  `emails_enviados` int(11) DEFAULT '0',
  `emails_vistos` int(11) DEFAULT '0',
  `emails_vistos_unicos` int(11) DEFAULT '0',
  `limite` int(11) DEFAULT '0',
  `envios_hora` int(11) DEFAULT '0',
  `data_inicio` date DEFAULT NULL,
  `hora_inicio` varchar(10) DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `hora_fim` varchar(10) DEFAULT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '0',
  `nome_from` varchar(250) DEFAULT NULL,
  `email_from` varchar(250) DEFAULT NULL,
  `email_reply` varchar(250) DEFAULT NULL,
  `email_bounce` varchar(250) DEFAULT NULL,
  `mailgun_key` varchar(255) DEFAULT NULL,
  `mailgun_dominio` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`newsletter_id`,`grupo`,`data`,`hora`,`limite`,`envios_hora`,`estado`,`emails_total`,`emails_enviados`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newsletters_historico` (`id`, `tipo_envio`, `newsletter_id`, `grupo`, `utilizador`, `data`, `hora`, `emails_total`, `emails_enviados`, `emails_vistos`, `emails_vistos_unicos`, `limite`, `envios_hora`, `data_inicio`, `hora_inicio`, `data_fim`, `hora_fim`, `estado`, `nome_from`, `email_from`, `email_reply`, `email_bounce`, `mailgun_key`, `mailgun_dominio`) VALUES
(2,	1,	2,	0,	'netg',	'2020-07-23',	'15:00:00',	0,	0,	0,	0,	0,	0,	NULL,	NULL,	'2020-07-28',	'19:52:32',	3,	'Ricardo',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'34030a1d6e10cc6ee3da5310fd39d197-ffefc4e4-1ad79006',	'sandbox1e31b7541c6a48bfa1810609d589dccc.mailgun.org'),
(3,	1,	1,	0,	'netg',	'2020-07-29',	'09:00:00',	1,	1,	8,	1,	0,	0,	'2020-07-29',	'09:07:57',	'2020-07-29',	'09:07:58',	3,	'Rvkumar',	'rileshvashnav@gmail.com',	'rileshvashnav@gmail.com',	'rileshvashnav@gmail.com',	'34030a1d6e10cc6ee3da5310fd39d197-ffefc4e4-1ad79006',	'sandbox1e31b7541c6a48bfa1810609d589dccc.mailgun.org'),
(4,	1,	2,	0,	'netg',	'2020-08-10',	'16:00:00',	4,	0,	0,	0,	0,	0,	NULL,	NULL,	NULL,	NULL,	1,	'Vera',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'34030a1d6e10cc6ee3da5310fd39d197-ffefc4e4-1ad79006',	'sandbox1e31b7541c6a48bfa1810609d589dccc.mailgun.org');

DROP TABLE IF EXISTS `newsletters_historico_estados`;
CREATE TABLE `newsletters_historico_estados` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newsletters_historico_estados` (`id`, `nome`) VALUES
(1,	'Aguardar Envio'),
(2,	'Em Processamento'),
(3,	'Enviado'),
(4,	'Suspenso'),
(5,	'Pausa');

DROP TABLE IF EXISTS `newsletters_historico_listas`;
CREATE TABLE `newsletters_historico_listas` (
  `id` bigint(20) NOT NULL,
  `newsletter_historico` bigint(20) DEFAULT '0',
  `lista` int(11) DEFAULT NULL,
  `newsletter_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`newsletter_historico`,`lista`,`newsletter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newsletters_historico_listas` (`id`, `newsletter_historico`, `lista`, `newsletter_id`) VALUES
(5,	2,	2,	2),
(17,	3,	2,	1),
(18,	4,	1,	2);

DROP TABLE IF EXISTS `newsletters_logs`;
CREATE TABLE `newsletters_logs` (
  `id` bigint(20) NOT NULL,
  `utilizador` varchar(350) DEFAULT NULL,
  `newsletter` varchar(350) DEFAULT NULL,
  `newsletter_id` int(11) DEFAULT '0',
  `que_fez` varchar(350) DEFAULT NULL,
  `data` varchar(350) DEFAULT NULL,
  `listas` varchar(350) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`newsletter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newsletters_logs` (`id`, `utilizador`, `newsletter`, `newsletter_id`, `que_fez`, `data`, `listas`) VALUES
(1,	'netg',	'nutra',	1,	'criou newsletter',	'2020-07-20 :: 11:06:34',	''),
(2,	'netg',	'nutra',	1,	'criou agendamento para 2020-07-20 // 03:00:00',	'2020-07-20 :: 11:08:46',	'Clientes'),
(3,	'netg',	'nutra',	1,	'removeu agendamento de 2020-07-20 // 03:00:00',	'2020-07-20 :: 11:10:07',	''),
(4,	'netg',	'Products Newslatter',	1,	'criou newsletter',	'2020-07-20 :: 13:02:52',	''),
(5,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-21 // 01:00:00',	'2020-07-20 :: 21:03:06',	'Clientes'),
(6,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-21 // 01:00:00',	'2020-07-20 :: 21:17:12',	'Website'),
(7,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-21 // 01:00:00',	'2020-07-20 :: 21:17:33',	''),
(8,	'netg',	'Test newsletter construction',	2,	'criou newsletter',	'2020-07-23 :: 14:03:02',	''),
(9,	'netg',	'Test newsletter construction',	2,	'criou agendamento para 2020-07-23 // 14:00:00',	'2020-07-23 :: 14:12:20',	'Clientes'),
(10,	'netg',	'Test newsletter construction',	2,	'removeu agendamento de 2020-07-23 // 14:00:00',	'2020-07-23 :: 14:14:24',	''),
(11,	'netg',	'Test newsletter construction',	2,	'criou agendamento para 2020-07-23 // 15:00:00',	'2020-07-23 :: 14:15:00',	'Clientes'),
(12,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-21 // 07:39:30',	'2020-07-28 :: 19:19:39',	''),
(13,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-29 // 01:00:00',	'2020-07-28 :: 19:22:06',	'Website, Clientes'),
(14,	'netg',	'Test newsletter construction',	2,	'finalizou envio da newsletter às 2020-07-28 // 19:52:32',	'2020-07-28 :: 19:52:32',	''),
(15,	'netg',	'Products Newslatter',	1,	'iniciou o envio da newsletter às 2020-07-28 // 19:56:38',	'2020-07-28 :: 19:56:38',	''),
(16,	'netg',	'Products Newslatter',	1,	'continuou o envio da newsletter às 2020-07-28 // 19:57:02',	'2020-07-28 :: 19:57:02',	''),
(17,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-28 // 19:57:03',	'2020-07-28 :: 19:57:03',	''),
(18,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-29 // 07:29:00',	'2020-07-29 :: 08:01:57',	''),
(19,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:03:19',	'Website, Clientes'),
(20,	'netg',	'Products Newslatter',	1,	'iniciou o envio da newsletter às 2020-07-29 // 08:03:52',	'2020-07-29 :: 08:03:52',	''),
(21,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-29 // 08:03:59',	'2020-07-29 :: 08:03:59',	''),
(22,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:45:17',	''),
(23,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:45:35',	'Website, Clientes'),
(24,	'netg',	'Products Newslatter',	1,	'iniciou o envio da newsletter às 2020-07-29 // 08:46:18',	'2020-07-29 :: 08:46:18',	''),
(25,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-29 // 08:46:24',	'2020-07-29 :: 08:46:24',	''),
(26,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:49:35',	''),
(27,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:50:00',	'Website, Clientes'),
(28,	'netg',	'Products Newslatter',	1,	'iniciou o envio da newsletter às 2020-07-29 // 08:50:09',	'2020-07-29 :: 08:50:09',	''),
(29,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-29 // 08:50:18',	'2020-07-29 :: 08:50:18',	''),
(30,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:52:54',	''),
(31,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:53:12',	'Website, Clientes'),
(32,	'netg',	'Products Newslatter',	1,	'iniciou o envio da newsletter às 2020-07-29 // 08:53:25',	'2020-07-29 :: 08:53:25',	''),
(33,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-29 // 08:53:32',	'2020-07-29 :: 08:53:32',	''),
(34,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-29 // 08:00:00',	'2020-07-29 :: 08:58:41',	''),
(35,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-29 // 09:00:00',	'2020-07-29 :: 08:59:56',	'Clientes'),
(36,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-29 // 09:00:40',	'2020-07-29 :: 09:00:40',	''),
(37,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-29 // 09:03:49',	'2020-07-29 :: 09:03:49',	''),
(38,	'netg',	'Products Newslatter',	1,	'removeu agendamento de 2020-07-29 // 09:01:00',	'2020-07-29 :: 09:07:18',	''),
(39,	'netg',	'Products Newslatter',	1,	'criou agendamento para 2020-07-29 // 09:00:00',	'2020-07-29 :: 09:07:37',	'Clientes'),
(40,	'netg',	'Products Newslatter',	1,	'iniciou o envio da newsletter às 2020-07-29 // 09:07:57',	'2020-07-29 :: 09:07:57',	''),
(41,	'netg',	'Products Newslatter',	1,	'finalizou envio da newsletter às 2020-07-29 // 09:07:58',	'2020-07-29 :: 09:07:58',	''),
(42,	'netg',	'Test newsletter construction',	2,	'criou agendamento para 2020-08-10 // 16:00:00',	'2020-08-10 :: 15:00:49',	'Website');

DROP TABLE IF EXISTS `newsletters_vistos`;
CREATE TABLE `newsletters_vistos` (
  `id` int(11) NOT NULL,
  `newsletter_id_historico` int(11) NOT NULL,
  `newsletter_id` int(11) NOT NULL,
  `email_id` int(11) NOT NULL,
  `data_envio` datetime NOT NULL,
  `data_visto` datetime DEFAULT NULL,
  `visto` tinyint(4) NOT NULL DEFAULT '0',
  `vistos` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`newsletter_id_historico`,`newsletter_id`,`email_id`,`visto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `newsletters_vistos` (`id`, `newsletter_id_historico`, `newsletter_id`, `email_id`, `data_envio`, `data_visto`, `visto`, `vistos`) VALUES
(16,	3,	1,	3,	'2020-07-29 09:07:58',	'2020-07-29 09:08:50',	1,	8);

DROP TABLE IF EXISTS `news_banners`;
CREATE TABLE `news_banners` (
  `id` int(11) NOT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `imagem2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `news_conteudo`;
CREATE TABLE `news_conteudo` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `mes` varchar(30) DEFAULT NULL,
  `ano` varchar(30) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `texto_link` varchar(30) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `texto_email1` varchar(30) DEFAULT NULL,
  `email1` varchar(50) DEFAULT NULL,
  `texto_email2` varchar(30) DEFAULT NULL,
  `email2` varchar(50) DEFAULT NULL,
  `texto_email3` varchar(30) DEFAULT NULL,
  `email3` varchar(50) DEFAULT NULL,
  `texto_email4` varchar(30) DEFAULT NULL,
  `email4` varchar(50) DEFAULT NULL,
  `topo` int(11) DEFAULT '0',
  `lingua` tinyint(4) DEFAULT '1' COMMENT '1 - PT; 2 - EN',
  PRIMARY KEY (`id`),
  KEY `indice` (`topo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_conteudo` (`id`, `nome`, `mes`, `ano`, `url`, `texto_link`, `telefone`, `texto_email1`, `email1`, `texto_email2`, `email2`, `texto_email3`, `email3`, `texto_email4`, `email4`, `topo`, `lingua`) VALUES
(1,	'Teste',	'JULHO',	'2020',	'',	'',	'',	'INFORMAÇÕES',	'',	'ASSIST. TÉCNICA',	'',	'DESENHO TÊXTIL',	'',	'PUBLICIDADE',	'',	NULL,	1),
(2,	'Teste 2',	'JULHO',	'2020',	'',	'',	'',	'INFORMAÇÕES',	'',	'ASSIST. TÉCNICA',	'',	'DESENHO TÊXTIL',	'',	'PUBLICIDADE',	'',	NULL,	1),
(3,	'Naturasaude Newsletter',	'',	'',	'https://www.netgocio.pt',	'www.naturasaude.pt',	'+351 258 108 955',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1),
(4,	'Test newsletter construction',	'JULHO',	'2020',	'https://bbakery.co.uk/',	'www.netgocio.pt',	'(+351) 987654321',	'INFORMAÇÕES',	'mithilchauhan@gmail.com',	'ASSIST. TÉCNICA',	'mithilchauhan@gmail.com',	'DESENHO TÊXTIL',	'mithilchauhan@gmail.com',	'PUBLICIDADE',	'mithilchauhan@gmail.com',	NULL,	1);

DROP TABLE IF EXISTS `news_cores`;
CREATE TABLE `news_cores` (
  `id` int(11) NOT NULL,
  `cor` varchar(250) DEFAULT NULL,
  `cor2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `news_emails`;
CREATE TABLE `news_emails` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `data` datetime DEFAULT NULL,
  `origem` varchar(255) DEFAULT NULL,
  `aceita` tinyint(4) DEFAULT '0',
  `data_remocao` datetime DEFAULT NULL,
  `origem_remocao` varchar(255) DEFAULT NULL,
  `visivel` tinyint(4) NOT NULL,
  `codigo` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `indice` (`email`,`visivel`,`codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `news_emails` (`id`, `nome`, `empresa`, `cargo`, `telefone`, `email`, `data`, `origem`, `aceita`, `data_remocao`, `origem_remocao`, `visivel`, `codigo`) VALUES
(1,	'Mithil Chauhan',	'',	'',	'',	'mithilchauhan@gmail.com',	'2020-06-11 13:34:21',	'0',	1,	NULL,	NULL,	1,	'SxlekQS6dQCX0sR2xPwpEtpZ');

DROP TABLE IF EXISTS `news_emails_devolvidos`;
CREATE TABLE `news_emails_devolvidos` (
  `id` bigint(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `newsletter_id` int(11) DEFAULT '0',
  `newsletter_id_historico` bigint(20) DEFAULT '0',
  `data_processamento` datetime DEFAULT NULL,
  `erro` int(11) DEFAULT '0' COMMENT '1 - Email não existe; 2 - Caixa de entrada cheia; 3 - Erro geral ',
  PRIMARY KEY (`id`),
  KEY `index` (`email`,`newsletter_id`,`newsletter_id_historico`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `news_emails_listas`;
CREATE TABLE `news_emails_listas` (
  `id` bigint(20) NOT NULL,
  `email` int(11) DEFAULT '0',
  `lista` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`email`,`lista`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_emails_listas` (`id`, `email`, `lista`) VALUES
(29,	1,	1);

DROP TABLE IF EXISTS `news_emails_temp`;
CREATE TABLE `news_emails_temp` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `data` datetime DEFAULT NULL,
  `origem` varchar(255) DEFAULT NULL,
  `aceita` tinyint(4) DEFAULT '0',
  `codigo` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `indice` (`email`,`codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `news_emails_temp` (`id`, `nome`, `email`, `data`, `origem`, `aceita`, `codigo`) VALUES
(1,	'undefined undefined',	'vishal@webtech-evolution.com',	'2020-12-09 07:39:50',	'0',	NULL,	'gml55VJv30B9R56prA8p6qMk');

DROP TABLE IF EXISTS `news_grupos`;
CREATE TABLE `news_grupos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `news_links`;
CREATE TABLE `news_links` (
  `id` bigint(20) NOT NULL,
  `newsletter_id_historico` bigint(20) DEFAULT '0',
  `codigo` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `tipo_link` int(11) DEFAULT '0',
  `n_clicks` int(11) DEFAULT '0',
  `data_ultimo_click` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Index` (`newsletter_id_historico`,`codigo`),
  KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_links` (`id`, `newsletter_id_historico`, `codigo`, `url`, `tipo_link`, `n_clicks`, `data_ultimo_click`) VALUES
(1,	3,	'QRIWp4lAtECwW7YH82dTZJhL',	'http://propostas.netgocio.pt/naturasaude/',	0,	0,	NULL),
(2,	3,	'QRIWp4lAtECwW7YH82dTZJhL',	'#',	1,	0,	NULL),
(3,	3,	'QRIWp4lAtECwW7YH82dTZJhL',	'http://propostas.netgocio.pt/naturasaude/suplementacao-alimentar-biotona-leite-de-coco-100-po-natural',	0,	0,	NULL),
(4,	3,	'QRIWp4lAtECwW7YH82dTZJhL',	'http://propostas.netgocio.pt/naturasaude/suplementacao-alimentar-corpore-sano-tonico-capilar-100-natural',	0,	0,	NULL),
(5,	3,	'QRIWp4lAtECwW7YH82dTZJhL',	'https://www.netgocio.pt',	0,	0,	NULL),
(6,	3,	'QRIWp4lAtECwW7YH82dTZJhL',	'https://www.facebook.com',	0,	0,	NULL),
(7,	3,	'QRIWp4lAtECwW7YH82dTZJhL',	'https://www.instagram.com/',	0,	0,	NULL);

DROP TABLE IF EXISTS `news_listas`;
CREATE TABLE `news_listas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT '99',
  `permite_editar` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`ordem`,`permite_editar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_listas` (`id`, `nome`, `ordem`, `permite_editar`) VALUES
(1,	'Website',	1,	0),
(2,	'Clientes',	2,	0);

DROP TABLE IF EXISTS `news_mailgun_bounces`;
CREATE TABLE `news_mailgun_bounces` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `erro` text,
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `news_produtos`;
CREATE TABLE `news_produtos` (
  `id` bigint(20) NOT NULL,
  `categoria_id` int(11) DEFAULT '0',
  `produto_id` int(11) DEFAULT '0',
  `tipo_produto` int(11) DEFAULT '0' COMMENT '1 - Produtos; 2 - Outlet',
  `id_tema` int(11) DEFAULT '0',
  `tipo` tinyint(4) DEFAULT '1',
  `tipo2` tinyint(4) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `titulo2` varchar(255) DEFAULT NULL,
  `ref` varchar(250) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `preco_ant` decimal(10,2) DEFAULT NULL,
  `desconto` decimal(10,2) DEFAULT '0.00',
  `saldo` decimal(10,2) DEFAULT NULL,
  `mostrar_preco` tinyint(4) DEFAULT '1',
  `descricao` text,
  `descricao2` text,
  `imagem1` varchar(250) DEFAULT NULL,
  `imagem2` varchar(250) DEFAULT NULL,
  `tipo_link1` int(11) DEFAULT '1',
  `tipo_link2` int(11) DEFAULT '1',
  `link` varchar(250) DEFAULT NULL,
  `link2` varchar(250) DEFAULT NULL,
  `texto_botao` varchar(255) DEFAULT NULL,
  `texto_botao2` varchar(255) DEFAULT NULL,
  `novidade` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`produto_id`,`categoria_id`,`id_tema`,`tipo`,`tipo2`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_produtos` (`id`, `categoria_id`, `produto_id`, `tipo_produto`, `id_tema`, `tipo`, `tipo2`, `nome`, `titulo`, `titulo2`, `ref`, `preco`, `preco_ant`, `desconto`, `saldo`, `mostrar_preco`, `descricao`, `descricao2`, `imagem1`, `imagem2`, `tipo_link1`, `tipo_link2`, `link`, `link2`, `texto_botao`, `texto_botao2`, `novidade`, `ordem`, `visivel`) VALUES
(1,	13,	3,	0,	1,	1,	NULL,	'Biotona Leite de Coco 100% pó natural',	NULL,	NULL,	'',	15.00,	0.00,	0.00,	NULL,	1,	'',	NULL,	'produto5.jpg',	NULL,	1,	1,	'suplementacao-alimentar-biotona-leite-de-coco-100-po-natural',	NULL,	NULL,	NULL,	1,	99,	1),
(2,	0,	0,	0,	2,	2,	1,	'SHOES',	'COOKIES POLICY',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'',	'',	NULL,	NULL,	1,	1,	'',	'',	'',	'',	0,	99,	1),
(3,	0,	0,	0,	2,	2,	1,	'SHOES',	'COOKIES POLICY',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'',	'',	NULL,	NULL,	1,	1,	'',	'',	'',	'',	0,	99,	1),
(4,	0,	0,	0,	2,	2,	1,	'SHOES',	'COOKIES POLICY',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'',	'',	NULL,	NULL,	1,	1,	'',	'',	'',	'',	0,	99,	1),
(5,	0,	0,	0,	2,	2,	2,	'SHOES',	'COOKIES POLICY',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'teste',	'',	NULL,	NULL,	1,	1,	'',	'',	'',	'',	0,	99,	1),
(6,	0,	0,	0,	3,	2,	3,	'Image',	'',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'',	'',	'084947_1_4033_cosmetica-news.jpg',	NULL,	1,	1,	'',	'',	'',	'',	0,	99,	1),
(7,	0,	0,	0,	4,	2,	2,	'Title From Text 1',	'Title From Text 1',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',	'',	NULL,	NULL,	1,	1,	'',	'',	'',	'',	0,	99,	1),
(8,	0,	0,	0,	5,	2,	1,	'Title From Text 1',	'Title From Text 2',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',	'',	'125042_1_4683_alimentacao.jpg',	NULL,	1,	1,	'#',	'',	'Sabir Mais',	'',	0,	99,	1),
(9,	0,	0,	0,	6,	2,	6,	'Only On Title Featured with CTA',	'Only On Title Featured with CTA',	'',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'',	'',	NULL,	NULL,	1,	1,	'#',	'',	'Button Text',	'',	0,	99,	1),
(10,	13,	3,	0,	7,	1,	NULL,	'Biotona Leite de Coco 100% pó natural',	NULL,	NULL,	'123456',	15.00,	20.00,	0.00,	NULL,	1,	'',	NULL,	'produto5.jpg',	NULL,	1,	1,	'suplementacao-alimentar-biotona-leite-de-coco-100-po-natural',	NULL,	NULL,	NULL,	1,	99,	1),
(11,	13,	5,	0,	7,	1,	NULL,	'Corpore Sano Tónico Capilar 100% natural',	NULL,	NULL,	'',	29.00,	0.00,	0.00,	NULL,	1,	'',	NULL,	'produto4.jpg',	NULL,	1,	1,	'suplementacao-alimentar-corpore-sano-tonico-capilar-100-natural',	NULL,	NULL,	NULL,	0,	99,	1),
(12,	0,	0,	0,	8,	2,	4,	'BLUSA MANGA COMPRIDA LACE ROMANTIC',	'BLUSA MANGA COMPRIDA LACE ROMANTIC',	'BLUSA MANGA COMPRIDA LACE ROMANTIC 2',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'Saldos!&nbsp;',	'Saldos!&nbsp;',	'124652_1_2436_natura-1.png',	'124652_2_879_natura-2.png',	1,	1,	'https://www.naturastore.pt/manga-comprida/155582-blusa-manga-comprida-lace-romantic-azul-2000000984605.html',	'https://www.naturastore.pt/manga-comprida/155582-blusa-manga-comprida-lace-romantic-azul-2000000984605.html',	'Ver Detalhes',	'Ver Detalhes',	0,	99,	1),
(13,	0,	0,	0,	8,	2,	4,	'CASACO LIANNE',	'CASACO LIANNE',	'CASACO LIANNE 2',	NULL,	NULL,	NULL,	0.00,	NULL,	1,	'Casaco Lianne , Em Branco',	'Casaco Lianne , Em Branco',	'015126_1_9646_natura-3.png',	'015126_2_8835_natura-4.png',	1,	1,	'https://www.naturastore.pt/curto/121370-casaco-lianne-branco-2000000642482.html',	'https://www.naturastore.pt/curto/121370-casaco-lianne-branco-2000000642482.html',	'Ver Detalhes',	'Ver Detalhes',	0,	99,	1),
(14,	10,	10,	0,	9,	1,	NULL,	'&Oacute;leo de linha&ccedil;a BioGarrafa de vidro 250 ml',	NULL,	NULL,	'NO.OL.LINH.250',	5.25,	7.00,	0.00,	NULL,	1,	'Saldos',	NULL,	'NO.OL.LINH.250_1.jpg',	NULL,	1,	1,	'alimentacao-oleo-alimentar-biologico-oleo-de-linhaca-bio-garrafa-de-vidro-250-ml',	NULL,	NULL,	NULL,	1,	99,	1),
(15,	31,	360,	0,	9,	1,	NULL,	'CURARTI cremigel 100ml',	NULL,	NULL,	'NO.CURARTICREMIGEL.PLAMECA.CR',	13.25,	15.00,	0.00,	NULL,	1,	'Saldos',	NULL,	'NO.CURARTICREMIGEL.PLAMECA.CR_1.jpg',	NULL,	1,	1,	'suplementos-creme-curarti-cremigel-100ml',	NULL,	NULL,	NULL,	1,	99,	1),
(16,	11,	484,	0,	9,	1,	NULL,	'100% C&Eacute;REBRO PARA HOMEM 30 AMPOLAS',	NULL,	NULL,	'NO.100CEREBROHOM.PHYTOGOLD.AMP',	31.00,	0.00,	0.00,	NULL,	1,	'',	NULL,	'NO.100CEREBROHOM.PHYTOGOLD.AMP_1.jpg',	NULL,	1,	1,	'suplementos-suplementos-alimentares-100-cerebro-para-homem-30-ampolas',	NULL,	NULL,	NULL,	0,	99,	1);

DROP TABLE IF EXISTS `news_remover`;
CREATE TABLE `news_remover` (
  `id` bigint(20) NOT NULL,
  `newsletter_id` int(11) DEFAULT '0',
  `newsletter_id_historico` bigint(20) DEFAULT '0',
  `codigo` varchar(255) DEFAULT NULL,
  `data_pedido` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index` (`newsletter_id`,`newsletter_id_historico`,`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `news_temas`;
CREATE TABLE `news_temas` (
  `id` bigint(20) NOT NULL,
  `conteudo` int(11) DEFAULT '0',
  `nome` varchar(250) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `cor` int(11) DEFAULT '0',
  `tipo` int(11) DEFAULT '1',
  `espacamento` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  `visivel` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`conteudo`,`tipo`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_temas` (`id`, `conteudo`, `nome`, `titulo`, `cor`, `tipo`, `espacamento`, `ordem`, `visivel`) VALUES
(1,	1,	'teste q',	'Guia dos pais',	0,	1,	0,	99,	1),
(2,	2,	'SHOES',	'Guia dos pais',	0,	2,	0,	99,	1),
(3,	3,	'Banner Image',	'',	0,	2,	0,	1,	1),
(4,	3,	'Info section 1',	'',	0,	2,	0,	2,	1),
(5,	3,	'Info section 2',	'',	0,	2,	0,	3,	1),
(6,	3,	'Only On Title Featured with CTA',	'',	0,	2,	0,	4,	1),
(7,	3,	'Product Heading',	'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor',	0,	1,	0,	5,	1),
(8,	4,	'Test block',	'Main block',	0,	2,	0,	99,	1),
(9,	4,	'block 2',	'Main block 2',	0,	1,	0,	99,	1);

DROP TABLE IF EXISTS `news_tipos_en`;
CREATE TABLE `news_tipos_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_tipos_en` (`id`, `nome`) VALUES
(1,	'Test');

DROP TABLE IF EXISTS `news_tipos_pt`;
CREATE TABLE `news_tipos_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `news_tipos_pt` (`id`, `nome`) VALUES
(1,	'Test');

DROP TABLE IF EXISTS `news_topos`;
CREATE TABLE `news_topos` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `imagem1` varchar(250) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `noticias_en`;
CREATE TABLE `noticias_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `resumo` text,
  `descricao` text,
  `imagem1` varchar(350) DEFAULT NULL,
  `imagem2` varchar(350) DEFAULT NULL,
  `imagem3` varchar(350) DEFAULT NULL,
  `video` text,
  `ficheiro` varchar(250) DEFAULT NULL,
  `destaque` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `tags` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`data`,`destaque`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `noticias_en` (`id`, `nome`, `link`, `data`, `resumo`, `descricao`, `imagem1`, `imagem2`, `imagem3`, `video`, `ficheiro`, `destaque`, `ordem`, `visivel`, `url`, `title`, `description`, `keywords`, `tags`) VALUES
(1,	'Lorem Ipsum Post Generator',	NULL,	'2020-09-02',	'Lorem Ipsum Post Generator is a simple plugin that generates posts and comments automatically.',	'<p>Lorem Ipsum Post Generator is a simple plugin that generates posts and comments automatically. It&rsquo;s also super easy to use, All you need to do is to just specify the number of posts and number of comments per post, rest of the job will be done by this plugin. One thing missing in this plugin is that it doesn&rsquo;t have the ability to generate pages, categories, tags etc.</p>\r\n\r\n<p>&nbsp;</p>\r\n',	NULL,	NULL,	NULL,	NULL,	NULL,	0,	99,	1,	'news-lorem-ipsum-post-generator',	'Lorem Ipsum Post Generator',	'Lorem Ipsum Post Generator is a simple plugin that generates posts and comments automatically.',	NULL,	'Post'),
(2,	'Example Content',	NULL,	'2020-09-01',	'Example Content is one of the simplest plugins that helps',	'<p>Example Content is one of the simplest plugins that helps you to generate dummy content on your WordPress website. The plugin adds dummy text that helps you in developing a new themes or website. You can delete the whole content with the help of this plugin.</p>\r\n\r\n<p>One of the disadvantages of the plugin is that it only adds dummy posts. It doesn&rsquo;t category, tags, comments etc. to your website. Add six types of posts like multiple paragraph post, image post, post with links, Blockquote post, UL and OL post and post with header text from H1 to H5.</p>\r\n\r\n<p>Allows you to remove all the dummy content in just a single click. Format posts with different styles.</p>\r\n\r\n<p>&nbsp;</p>\r\n',	NULL,	NULL,	NULL,	NULL,	NULL,	0,	99,	1,	'news-example-content',	'Example Content',	'Example Content is one of the simplest plugins that helps',	NULL,	'web '),
(3,	'Lorem ipsum dolor sit amet,',	NULL,	'2020-07-29',	'Lorem ipsum dolor sit amet,',	'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br />\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,<br />\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo<br />\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse<br />\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non<br />\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.',	NULL,	NULL,	NULL,	NULL,	NULL,	0,	99,	1,	'news-lorem-ipsum-dolor-sit-amet',	'Lorem ipsum dolor sit amet,',	'Lorem ipsum dolor sit amet,',	NULL,	'');

DROP TABLE IF EXISTS `noticias_es`;
CREATE TABLE `noticias_es` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `resumo` text,
  `descricao` text,
  `imagem1` varchar(350) DEFAULT NULL,
  `imagem2` varchar(350) DEFAULT NULL,
  `imagem3` varchar(350) DEFAULT NULL,
  `video` text,
  `ficheiro` varchar(250) DEFAULT NULL,
  `destaque` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `tags` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`data`,`destaque`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `noticias_fr`;
CREATE TABLE `noticias_fr` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `resumo` text,
  `descricao` text,
  `imagem1` varchar(350) DEFAULT NULL,
  `imagem2` varchar(350) DEFAULT NULL,
  `imagem3` varchar(350) DEFAULT NULL,
  `video` text,
  `ficheiro` varchar(250) DEFAULT NULL,
  `destaque` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `tags` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`data`,`destaque`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `noticias_imagens`;
CREATE TABLE `noticias_imagens` (
  `id` int(11) NOT NULL,
  `id_peca` int(11) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `imagem1` varchar(250) DEFAULT NULL,
  `imagem2` varchar(250) DEFAULT NULL,
  `legenda` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_peca`,`id_tipo`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `noticias_imagens` (`id`, `id_peca`, `id_tipo`, `imagem1`, `imagem2`, `legenda`, `ordem`, `visivel`) VALUES
(1,	2,	NULL,	'vinyl_pvc_banners_1.jpg',	NULL,	NULL,	99,	1),
(2,	2,	NULL,	'vinyl_pvc_banners_1_1.jpg',	NULL,	NULL,	99,	1);

DROP TABLE IF EXISTS `noticias_pt`;
CREATE TABLE `noticias_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `resumo` text,
  `descricao` text,
  `imagem1` varchar(350) DEFAULT NULL,
  `imagem2` varchar(350) DEFAULT NULL,
  `imagem3` varchar(350) DEFAULT NULL,
  `video` text,
  `ficheiro` varchar(250) DEFAULT NULL,
  `destaque` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '0',
  `url` varchar(250) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `keywords` text,
  `tags` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`data`,`destaque`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `noticias_pt` (`id`, `nome`, `link`, `data`, `resumo`, `descricao`, `imagem1`, `imagem2`, `imagem3`, `video`, `ficheiro`, `destaque`, `ordem`, `visivel`, `url`, `title`, `description`, `keywords`, `tags`) VALUES
(1,	'Lorem Ipsum Post Generator',	NULL,	'2020-09-02',	'Lorem Ipsum Post Generator is a simple',	'<p>Lorem Ipsum Post Generator is a simple plugin that generates posts and comments automatically. It&rsquo;s also super easy to use, All you need to do is to just specify the number of posts and number of comments per post, rest of the job will be done by this plugin. One thing missing in this plugin is that it doesn&rsquo;t have the ability to generate pages, categories, tags etc.</p>\r\n\r\n<p>&nbsp;</p>\r\n',	'025935_1_2848_suplementacao_desportiva.jpg',	NULL,	NULL,	NULL,	NULL,	0,	99,	1,	'noticias-lorem-ipsum-post-generator',	'Lorem Ipsum Post Generator',	'Lorem Ipsum Post Generator is a simple',	NULL,	'Post'),
(2,	'Example Content',	NULL,	'2020-09-01',	'Example Content is one of the simplest plugins that helps',	'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam hendrerit felis vel nulla pretium, ut scelerisque mi placerat. Aliquam in dolor ut ex mattis aliquet eu in nibh. Integer malesuada elementum sem tristique cursus. Suspendisse ac libero vel dui maximus tristique non vel tellus. Phasellus eu laoreet mauris. Vivamus volutpat ultricies tellus, a eleifend ex semper et. Nam leo ipsum, sodales non tristique vitae, luctus non lorem.</p>\r\n\r\n<p>Aliquam erat volutpat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Proin venenatis felis quam, non ultrices mi ullamcorper sed. Proin facilisis semper pretium. Quisque sit amet arcu leo. Suspendisse pellentesque tortor vitae diam molestie, in vulputate elit cursus. Nam feugiat semper metus, iaculis tempus risus luctus eget. Mauris interdum imperdiet mauris eget fringilla. Phasellus dictum sollicitudin ante, ac convallis sem tempus eu. Curabitur faucibus feugiat odio, eu placerat ante molestie vel. Fusce placerat consequat ipsum ac scelerisque. Nam posuere augue nec viverra semper.</p>\r\n\r\n<p>Mauris condimentum, nisl laoreet tempor malesuada, massa arcu euismod justo, pellentesque venenatis ligula ante nec velit. Vestibulum aliquet ornare nisl, vel ornare nunc bibendum sit amet. Nullam ut tortor et risus molestie imperdiet. Quisque dictum dui ac eros porta, in varius mauris condimentum. Duis auctor mollis orci, vel rhoncus risus vestibulum sit amet. Phasellus ac condimentum mi, non dictum felis. Maecenas cursus, lectus nec tempus vehicula, lorem elit fermentum nunc, nec mollis augue tellus sit amet nisi. Nunc ac nibh sit amet magna eleifend pellentesque. Aenean dapibus, elit ac tincidunt venenatis, risus leo mollis purus, vitae lacinia massa justo eu augue. Vivamus at congue nisl. Curabitur venenatis velit consectetur dolor luctus consequat. Interdum et malesuada fames ac ante ipsum primis in faucibus. Donec congue sed mi a dapibus. Aenean lectus orci, facilisis nec est quis, ultrices sodales ipsum.</p>\r\n\r\n<p>Vestibulum felis odio, tempor ac turpis sit amet, euismod ultrices lacus. Ut non tempor mi. Integer id efficitur urna, vel rutrum quam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Suspendisse diam diam, convallis non eleifend quis, tempor ut arcu. Sed et commodo lectus. Sed id ullamcorper ex, quis dapibus justo. Aenean eget nibh massa.</p>\r\n\r\n<p>Duis euismod nulla nisl, sed vulputate ex vulputate eu. In pulvinar massa eu dolor congue porta. Cras tellus mi, ornare non blandit a, feugiat vel mi. Sed laoreet dolor vitae accumsan pharetra. Pellentesque eleifend vulputate dui, vel auctor erat hendrerit sed. Nulla egestas congue nunc, sit amet luctus enim finibus ac. Sed varius nulla eget nulla porttitor, vitae facilisis augue consequat. Nullam sed massa justo. Etiam vitae purus eu mauris vulputate vestibulum ac sit amet ipsum. Mauris dapibus posuere libero eget ultricies. Sed eleifend massa vitae vulputate maximus. Aliquam vitae elit id ante consectetur vehicula quis at arcu. Fusce sed fringilla ex, a mattis urna. Maecenas sollicitudin justo id pellentesque vehicula. Nunc facilisis, massa ut commodo pulvinar, velit massa bibendum lorem, tincidunt laoreet odio mauris vitae magna. Mauris volutpat pellentesque nulla a volutpat.</p>\r\n',	'030233_1_6156_alimentacao.jpg',	NULL,	NULL,	NULL,	'111513_1_8470_Resume-Daniel-Vieira-BA-Eng.pdf',	0,	99,	1,	'noticias-example-content',	'Example Content',	'Example Content is one of the simplest plugins that helps',	NULL,	'web '),
(3,	'Lorem ipsum dolor sit amet,',	NULL,	'2020-07-29',	'Lorem ipsum dolor sit amet,',	'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br />\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,<br />\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo<br />\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse<br />\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non<br />\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.',	'013314_1_4269_ortopedia.jpg',	NULL,	NULL,	NULL,	NULL,	0,	99,	1,	'noticias-lorem-ipsum-dolor-sit-amet',	'Lorem ipsum dolor sit amet,',	'Lorem ipsum dolor sit amet,',	NULL,	'');

DROP TABLE IF EXISTS `notificacoes_en`;
CREATE TABLE `notificacoes_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `email` text,
  `email2` text,
  `email3` text,
  `assunto` varchar(255) DEFAULT NULL,
  `assunto_cliente` varchar(255) DEFAULT NULL,
  `resposta` text,
  `sucesso` varchar(255) DEFAULT NULL,
  `resposta_editavel` tinyint(4) DEFAULT '1' COMMENT '0=Não editavel; 1=editavel; ',
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `notificacoes_en` (`id`, `nome`, `email`, `email2`, `email3`, `assunto`, `assunto_cliente`, `resposta`, `sucesso`, `resposta_editavel`, `visivel`, `ordem`) VALUES
(1,	'Formulário de contacto',	'mithilchauhan@gmail.com',	'',	'',	'Novo pedido de contacto',	'Pedido de contacto',	'<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Dear </font></font><strong><font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\"># name #</font></font></strong><font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\"> , </font></font><br />\r\n<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Your contact has been successfully received. </font></font><br />\r\n<br />\r\n<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">We will respond as soon as possible. </font></font><br />\r\n<br />\r\n<br />\r\n<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Thank you</font></font>',	'',	1,	1,	10),
(2,	'Novo Registo',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'Novo registo',	'Novo registo',	'Caro <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(3,	'Nova Encomenda',	'mithilchauhan@gmail.com',	'',	'',	'Nova encomenda',	'Nova encomenda',	'Caro <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(4,	'Novo Ticket',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'Novo ticket',	'Novo ticket',	'Caro <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(6,	'Formulário de contacto das páginas',	'	mithilchauhan@gmail.com',	'',	'',	'Novo pedido de informações',	'Novo pedido de informações',	'Caro <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	NULL,	1,	1,	10),
(9,	'Novo pedido de remoção de conta',	'mithilchauhan@gmail.com',	'',	'',	'Novo pedido de remoção de conta',	'Novo pedido de remoção de conta',	'Caro <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(10,	'consultorio online',	'mithilchauhan@gmail.com',	'',	'',	'Novo consultorio online',	'Pedido de consultorio online',	'<font><font><font><font>Dear </font></font></font></font><strong><font><font><font><font># name #</font></font></font></font></strong><font><font><font><font> , </font></font></font></font><br />\r\n<font><font><font><font>Your contact has been successfully received. </font></font></font></font><br />\r\n<br />\r\n<font><font><font><font>We will respond as soon as possible. </font></font></font></font><br />\r\n<br />\r\n<br />\r\n<font><font><font><font>Thank you</font></font></font></font>',	'',	1,	1,	10);

DROP TABLE IF EXISTS `notificacoes_pt`;
CREATE TABLE `notificacoes_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `email` text,
  `email2` text,
  `email3` text,
  `assunto` varchar(255) DEFAULT NULL,
  `assunto_cliente` varchar(255) DEFAULT NULL,
  `resposta` text,
  `sucesso` varchar(255) DEFAULT NULL,
  `resposta_editavel` tinyint(4) DEFAULT '1' COMMENT '0=Não editavel; 1=editavel; ',
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `notificacoes_pt` (`id`, `nome`, `email`, `email2`, `email3`, `assunto`, `assunto_cliente`, `resposta`, `sucesso`, `resposta_editavel`, `visivel`, `ordem`) VALUES
(1,	'Formulário de contacto',	'mithilchauhan@gmail.com',	'',	'',	'Novo pedido de contacto',	'Pedido de contacto',	'<font><font><font><font>Caro(a)</font></font></font></font><font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\"> <strong>#nome# </strong>,&nbsp;<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado</font></font>',	'',	1,	1,	10),
(2,	'Novo Registo',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'Novo registo',	'Novo registo',	'<font><font><font><font>Caro(a)</font></font></font></font> <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(3,	'Nova Encomenda',	'naturasaude@sapo.pt',	'',	'',	'Nova encomenda',	'Nova encomenda',	'<font><font><font><font>Caro(a)</font></font></font></font> <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(4,	'Novo Ticket',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'mithilchauhan@gmail.com',	'Novo ticket',	'Novo ticket',	'<font><font><font><font>Caro(a)</font></font></font></font> <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(6,	'Formulário de contacto das páginas',	'	carlossampaio@netgocio.pt',	'',	'',	'Novo pedido de informações',	'Novo pedido de informações',	'<font><font><font><font>Caro(a)</font></font></font></font> <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(9,	'Novo pedido de remoção de conta',	'mithilchauhan@gmail.com',	'',	'',	'Novo pedido de remoção de conta',	'Novo pedido de remoção de conta',	'<font><font><font><font>Caro(a)</font></font></font></font> <strong>#nome#</strong>,<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado',	'',	1,	1,	10),
(10,	'consultorio online',	'mithilchauhan@gmail.com',	'',	'',	'Novo consultorio online',	'Pedido de consultorio online',	'<font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\"><font style=\"vertical-align: inherit;\">Caro(a)&nbsp;<strong>#nome#</strong> ,&nbsp;<br />\r\nO seu contacto foi recebido com sucesso.<br />\r\n<br />\r\nIremos responder assim que possivel.<br />\r\n<br />\r\n<br />\r\nObrigado</font></font></font></font>',	'',	1,	1,	10);

DROP TABLE IF EXISTS `paginas_blocos_en`;
CREATE TABLE `paginas_blocos_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagina` int(11) DEFAULT NULL,
  `tem_fundo` tinyint(4) NOT NULL DEFAULT '0',
  `tipo_fundo` tinyint(4) NOT NULL DEFAULT '1',
  `cor_fundo` varchar(50) DEFAULT NULL,
  `imagem_fundo` varchar(255) DEFAULT NULL,
  `mascara_fundo` tinyint(4) DEFAULT '1',
  `tipo_imagens` tinyint(4) DEFAULT '1' COMMENT '1 - Galeria ; 0 - Sem Galeria',
  `esp_imagens` tinyint(4) DEFAULT '0' COMMENT 'Espaçamento entre as imagens (px)',
  `largura_imgs` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Largura Máxima / 2 - Largura específica',
  `valor_largura_imgs` int(11) DEFAULT NULL,
  `largura_texto` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Largura Máxima / 2 - Largura específica',
  `valor_largura_texto` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `titulo1` varchar(255) DEFAULT NULL,
  `texto` text,
  `titulo2` varchar(255) DEFAULT NULL,
  `texto2` text,
  `titulo3` varchar(255) DEFAULT NULL,
  `texto3` text,
  `video` varchar(500) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT NULL,
  `tipo_galeria` tinyint(4) DEFAULT '1' COMMENT '1 - Galeria; 0 - Vídeo',
  `fullscreen` tinyint(4) DEFAULT '0' COMMENT '1 - Galeria ocupa 100% largura',
  `texto_contorna` tinyint(4) DEFAULT '0',
  `orientacao` tinyint(4) DEFAULT '0',
  `colunas` int(11) DEFAULT '1',
  `link1` varchar(255) DEFAULT NULL,
  `target1` varchar(255) DEFAULT NULL,
  `texto_botao1` varchar(255) DEFAULT NULL,
  `link2` varchar(255) DEFAULT NULL,
  `target2` varchar(255) DEFAULT NULL,
  `texto_botao2` varchar(255) DEFAULT NULL,
  `link3` varchar(255) DEFAULT NULL,
  `target3` varchar(255) DEFAULT NULL,
  `texto_botao3` varchar(255) DEFAULT NULL,
  `mapa` text,
  `visivel` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`pagina`,`tipo_imagens`,`tipo`,`tipo_galeria`,`fullscreen`,`texto_contorna`,`orientacao`,`colunas`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `paginas_blocos_en` (`id`, `pagina`, `tem_fundo`, `tipo_fundo`, `cor_fundo`, `imagem_fundo`, `mascara_fundo`, `tipo_imagens`, `esp_imagens`, `largura_imgs`, `valor_largura_imgs`, `largura_texto`, `valor_largura_texto`, `nome`, `titulo`, `titulo1`, `texto`, `titulo2`, `texto2`, `titulo3`, `texto3`, `video`, `tipo`, `tipo_galeria`, `fullscreen`, `texto_contorna`, `orientacao`, `colunas`, `link1`, `target1`, `texto_botao1`, `link2`, `target2`, `texto_botao2`, `link3`, `target3`, `texto_botao3`, `mapa`, `visivel`, `ordem`) VALUES
(47,	7,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Métodos de Pagamento',	'Métodos de Pagamento',	'',	'',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(48,	8,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Refund Policy',	'Refund Policy',	'',	'<p>Refunds may occasionally be offered at the discretion of the management. Refunds are only taken into consideration in circumstances where there is a clear reason to do so, For example technical failure, or situations where you clearly did not receive what you have ordered.</p>\r\n\r\n<p>All claims for refunds will only be entertained if received in writing at the shop within 48 hours of collection.</p>\r\n\r\n<p>The maximum refund we offer is the value of the cake and delivery. Further compensation is not available.</p>\r\n\r\n<h3>Internet Sales</h3>\r\n\r\n<p>Internet sales orders can only be confirmed as received once a confirmation email has been received by the customer.</p>\r\n\r\n<p>No amendments can be made to internet orders once payment has been made.</p>\r\n\r\n<p>We must be notified of any complaints arising from IA Cakes within 24 hours. The cake and any portions need to be returned for testing and further investigation within 24 hours.</p>\r\n\r\n<p>In the event of a technical failure, website service outage or issues beyond our control, the extent of our liability will be limited to the total amount paid for the cake. Service Outages on our website will not include downtime due to Acts of God, nor outside security breaches.</p>\r\n\r\n<p>In the event that an incorrect price is charged for an online order due to any technical glitch or error on the online ordering system, IA Cakes reserve the right to charge the correct price. We will make every effort to notify you of this prior to collection.</p>\r\n\r\n<h3>Our legal registration info</h3>\r\n\r\n<p><strong>Registered Company Name</strong><br />\r\nFIS CAKES SUPPLIERS LIMITED<br />\r\n361-363 Stratford Road<br />\r\nSparkbrook<br />\r\nBirmingham<br />\r\nB11 4JY</p>\r\n\r\n<p><strong>Place of Registration</strong><br />\r\n361-363 Stratford Road<br />\r\nSparkbrook<br />\r\nBirmingham<br />\r\nB11 4JY</p>\r\n\r\n<p><strong>Reg.No</strong>&nbsp;:&nbsp;<strong>11369664</strong></p>\r\n',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(49,	10,	0,	1,	NULL,	NULL,	1,	1,	0,	1,	NULL,	1,	NULL,	'texto img',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	1,	0,	0,	0,	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	99),
(50,	6,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'TESTE',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	0,	0,	0,	2,	NULL,	NULL,	'_blank',	NULL,	NULL,	'',	NULL,	NULL,	'',	NULL,	NULL,	1,	99),
(54,	13,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'What We Look For',	'What We Look For',	'',	'<p>To successfully expand, it is very important that we recruit the right people to work with our Brand. We are looking for people with the right attitude who must be prepared to follow an established business model and be flexible as changes arise. As well as the usual business acumen, we are looking for:</p>\r\n\r\n<ul>\r\n	<li>A genuine commitment to providing the highest quality Bismillah Bakery to a diverse range of customers and communities</li>\r\n	<li>Dedicated and motivated to providing the highest levels of customer service in a retail environment</li>\r\n	<li>Team players &ndash; franchising is all about the wider network and you will become part of a supportive &lsquo;family&rsquo; of Franchise Owners</li>\r\n	<li>People who will follow systems and set procedures</li>\r\n</ul>\r\n',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'_parent',	'',	'',	'_blank',	'',	'',	'',	'',	NULL,	1,	99),
(56,	9,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Why Choose Us',	'Why Choose Us',	'',	'<p><strong>What is The Bismillah Bakery?</strong></p>\r\n\r\n<p>The Bismillah Bakery Retail Franchise is an exciting venture into Celebration Cakes with a leading High Street Brand.</p>\r\n\r\n<p>We are dedicated to making delicious, high quality, individually-crafted celebration Freshly whipped cream cakes instore and while you wait.All our cakes are egg free and suitable for any individuals following a lacto-vegetarian or egg free diet for personal reasons or because of an allergy.You can pick up a cake and have it personalised with a handwritten message while you wait !</p>\r\n\r\n<p>We are constantly investing in our product both financially, and in researching ways to stay ahead of the Celebration Cake industry.</p>\r\n\r\n<p>We have a wealth of talent and expertise within our organisation and this gives us the ability to maintain and monitor the strict standards and systems we follow &ndash; guaranteeing an exceptional level of quality and customer service.</p>\r\n\r\n<p><strong>Are you looking for your own business?</strong></p>\r\n\r\n<p>How about joining the Bismillah Bakery and opening your very own High Street cake shop ? We see franchising as a partnership where both parties are committed to creating long-term success for both the Company and the Franchisee.It is in our best interests that your new business starts off as well as possible and continues to grow.We provide full training and ongoing guidance and support to maximise the success of your store.</p>\r\n\r\n<p><strong>Do you want to be your own boss?</strong></p>\r\n\r\n<p>Imagine a life in which you can live on your own terms, have the freedom to choose how you spend your time and the projects on which you work.With enough planning, patience, and determination, this situation can be yours.While self-employment can be tough, as it comes with its own problems, stresses, and setbacks, the sense of accomplishment and pride you&rsquo;ll derive from it is worth the investment. We offer a product that is in high demand and the security of joining a proven franchise model that has stood the test of time is evidenced by our consistent growth over the last 10 years.</p>\r\n\r\n<p><strong>Field Support and Training</strong></p>\r\n\r\n<p>We provide start-up and regular training, guidance, and support every step of the way to ensure your success. You will receive the full support of the Franchise Team in helping you succeed in your Franchise venture.</p>\r\n\r\n<p><strong>Expanding Fast</strong></p>\r\n\r\n<p>The first Bismillah Bakery shop opened in July 2008 in East London.&Acirc;&nbsp; Since then the business has gone from strength to strength and we currently have 100+ Franchises up and running Nationwide, click here to see locations.</p>\r\n\r\n<p>Following a strong year of new openings in 2016/2017, the company envisages opening 2 to 3 new stores per month nationally, in 2018.</p>\r\n',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99);

DROP TABLE IF EXISTS `paginas_blocos_ficheiros_en`;
CREATE TABLE `paginas_blocos_ficheiros_en` (
  `id` int(11) NOT NULL,
  `bloco` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `ficheiro` varchar(255) DEFAULT NULL,
  `tamanho` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`bloco`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `paginas_blocos_ficheiros_pt`;
CREATE TABLE `paginas_blocos_ficheiros_pt` (
  `id` int(11) NOT NULL,
  `bloco` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `ficheiro` varchar(255) DEFAULT NULL,
  `tamanho` varchar(255) DEFAULT NULL,
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`bloco`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `paginas_blocos_imgs`;
CREATE TABLE `paginas_blocos_imgs` (
  `id` int(11) NOT NULL,
  `bloco` int(11) DEFAULT NULL,
  `coluna` int(11) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `tipo` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Imagem / 1 - Vídeo Upload / 2 - Link Vídeo',
  `proporcao_video` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - 16:9 / 2 - 4:3',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`bloco`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `paginas_blocos_imgs` (`id`, `bloco`, `coluna`, `link`, `imagem1`, `tipo`, `proporcao_video`, `ordem`, `visivel`) VALUES
(1,	49,	NULL,	NULL,	'Captura_de_ecra_2020_06_19_a_s_16.22.36.png',	0,	1,	99,	1);

DROP TABLE IF EXISTS `paginas_blocos_pt`;
CREATE TABLE `paginas_blocos_pt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pagina` int(11) DEFAULT NULL,
  `tem_fundo` tinyint(4) NOT NULL DEFAULT '0',
  `tipo_fundo` tinyint(4) NOT NULL DEFAULT '1',
  `cor_fundo` varchar(50) DEFAULT NULL,
  `imagem_fundo` varchar(255) DEFAULT NULL,
  `mascara_fundo` tinyint(4) DEFAULT '1',
  `tipo_imagens` tinyint(4) DEFAULT '1' COMMENT '1 - Galeria ; 0 - Sem Galeria',
  `esp_imagens` tinyint(4) DEFAULT '0' COMMENT 'Espaçamento entre as imagens (px)',
  `largura_imgs` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Largura Máxima / 2 - Largura específica',
  `valor_largura_imgs` int(11) DEFAULT NULL,
  `largura_texto` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Largura Máxima / 2 - Largura específica',
  `valor_largura_texto` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `titulo1` varchar(255) DEFAULT NULL,
  `texto` text,
  `titulo2` varchar(255) DEFAULT NULL,
  `texto2` text,
  `titulo3` varchar(255) DEFAULT NULL,
  `texto3` text,
  `video` varchar(500) DEFAULT NULL,
  `tipo` tinyint(4) DEFAULT NULL,
  `tipo_galeria` tinyint(4) DEFAULT '1' COMMENT '1 - Galeria; 0 - Vídeo',
  `fullscreen` tinyint(4) DEFAULT '0' COMMENT '1 - Galeria ocupa 100% largura',
  `texto_contorna` tinyint(4) DEFAULT '0',
  `orientacao` tinyint(4) DEFAULT '0',
  `colunas` int(11) DEFAULT '1',
  `link1` varchar(255) DEFAULT NULL,
  `target1` varchar(255) DEFAULT NULL,
  `texto_botao1` varchar(255) DEFAULT NULL,
  `link2` varchar(255) DEFAULT NULL,
  `target2` varchar(255) DEFAULT NULL,
  `texto_botao2` varchar(255) DEFAULT NULL,
  `link3` varchar(255) DEFAULT NULL,
  `target3` varchar(255) DEFAULT NULL,
  `texto_botao3` varchar(255) DEFAULT NULL,
  `mapa` text,
  `visivel` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`pagina`,`tipo_imagens`,`tipo`,`tipo_galeria`,`fullscreen`,`texto_contorna`,`orientacao`,`colunas`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `paginas_blocos_pt` (`id`, `pagina`, `tem_fundo`, `tipo_fundo`, `cor_fundo`, `imagem_fundo`, `mascara_fundo`, `tipo_imagens`, `esp_imagens`, `largura_imgs`, `valor_largura_imgs`, `largura_texto`, `valor_largura_texto`, `nome`, `titulo`, `titulo1`, `texto`, `titulo2`, `texto2`, `titulo3`, `texto3`, `video`, `tipo`, `tipo_galeria`, `fullscreen`, `texto_contorna`, `orientacao`, `colunas`, `link1`, `target1`, `texto_botao1`, `link2`, `target2`, `texto_botao2`, `link3`, `target3`, `texto_botao3`, `mapa`, `visivel`, `ordem`) VALUES
(22,	2,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Texto',	'',	'',	'Este website utiliza <em>cookies</em>&nbsp;para melhorar o seu servi&ccedil;o.<br />\r\n<br />\r\n<strong>1. O que s&atilde;o&nbsp;<em>cookies</em></strong>?<br />\r\nOs&nbsp;<em>cookies</em>&nbsp;s&atilde;o pequenos ficheiros de texto que um&nbsp;web<em>site</em>, ao ser visitado pelo utilizador, coloca no seu computador ou no seu dispositivo m&oacute;vel atrav&eacute;s do navegador de internet (browser). A coloca&ccedil;&atilde;o de&nbsp;<em>cookies</em>&nbsp;ajudar&aacute; o&nbsp;<em>site</em>&nbsp;a reconhecer o seu dispositivo na pr&oacute;xima vez que o utilizador o visita.&nbsp;<br />\r\n<br />\r\nUsamos o termo&nbsp;<em>cookies</em>&nbsp;nesta pol&iacute;tica para referir todos os ficheiros que recolhem informa&ccedil;&otilde;es desta forma.&nbsp;.Alguns cookies s&atilde;o essenciais para garantir as funcionalidades disponibilizadas, enquanto outros s&atilde;o destinadas a melhorar o desempenho e a experi&ecirc;ncia do utilizador.&nbsp;N&atilde;o dever&aacute; continuar a aceder ao nosso <em>website</em> ap&oacute;s o alerta sobre os cookies, se n&atilde;o concordar com a sua utiliza&ccedil;&atilde;o.<br />\r\n&nbsp;<br />\r\n<strong>Que tipo de c</strong><em><strong>ookies</strong></em><strong>&nbsp;utilizamos</strong>?<br />\r\n&nbsp;\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:596px\">\r\n	<thead>\r\n		<tr>\r\n			<td class=\"cor_op_1\">ORIGEM</td>\r\n			<td class=\"cor_op_05\">NOME COOKIE</td>\r\n			<td class=\"cor_op_1\">PROP&Oacute;SITO</td>\r\n			<td class=\"cor_op_05\">EXPIRA</td>\r\n		</tr>\r\n	</thead>\r\n	<tbody>\r\n		<tr class=\"cor3_op\">\r\n			<td class=\"cor2_op_05\" rowspan=\"2\">Natura Sa&uacute;de</td>\r\n			<td class=\"cor2_op_1\">PHPSESSID</td>\r\n			<td class=\"cor2_op_05\">&Eacute; utilizado pela linguagem PHP para permitir que as vari&aacute;veis de sess&atilde;o sejam guardadas no servidor web. Este cookie &eacute; essencial para o funcionamento do website.</td>\r\n			<td class=\"cor2_op_1\">Quando o browser &eacute; fechado</td>\r\n		</tr>\r\n		<tr class=\"cor3_op\">\r\n			<td class=\"cor2_op_1\">allowCookies</td>\r\n			<td class=\"cor2_op_05\">Utilizado para controlar a aceita&ccedil;&atilde;o do utilizador da pol&iacute;tica de cookies do site</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 1 ano</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_05\">AddThis</td>\r\n			<td class=\"cor2_op_1\">__atuvc</td>\r\n			<td class=\"cor2_op_05\">Criado e lido pelo AddThis, site JavaScript para a partilha nas redes sociais. Guarda o n&uacute;mero de partilhas de uma p&aacute;gina.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 2 anos</td>\r\n		</tr>\r\n		<tr class=\"cor3_op\">\r\n			<td class=\"cor2_op_05\" rowspan=\"4\">Google<br />\r\n			(<a href=\"https://developers.google.com/analytics/devguides/collection/gtagjs/cookie-usage\" target=\"_blank\">Saber mais</a>)</td>\r\n			<td class=\"cor2_op_1\">_ga</td>\r\n			<td class=\"cor2_op_05\">Utilizado para distinguir os utilizadores.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 2 anos</td>\r\n		</tr>\r\n		<tr class=\"cor3_op\">\r\n			<td class=\"cor2_op_1\">_gat</td>\r\n			<td class=\"cor2_op_05\">Utilizado para acelerar a percentagem de pedidos.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 1 minuto</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_1\">gat_gtag_UA</td>\r\n			<td class=\"cor2_op_05\">Cont&eacute;m informa&ccedil;&otilde;es relacionadas com campanhas para o utilizador. Ao vincular suas contas do Google Analytics e do Google AdWords, as tags de convers&atilde;o de site do Google AdWords analisar&atilde;o esse cookie, a menos que esteja inativo.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 1 minuto</td>\r\n		</tr>\r\n		<tr class=\"cor3_op\">\r\n			<td class=\"cor2_op_1\">gid</td>\r\n			<td class=\"cor2_op_05\">Utilizado para distinguir os utilizadores.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 1 dia</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_05\" rowspan=\"7\">Facebook<br />\r\n			(<a href=\"https://www.facebook.com/policies/cookies/\" target=\"_blank\">Saber mais</a>)</td>\r\n			<td class=\"cor2_op_1\">c_user</td>\r\n			<td class=\"cor2_op_05\">&Eacute; utilizado na integra&ccedil;&atilde;o do Facebook e na partilha de conte&uacute;dos.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 3 meses</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_1\">datr</td>\r\n			<td class=\"cor2_op_05\">&Eacute; utilizado na seguran&ccedil;a e integridade do website. Cont&eacute;m identifica&ccedil;&atilde;o do browser.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 2 anos</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_1\">fr</td>\r\n			<td class=\"cor2_op_05\">&Eacute; utilizado para fins publicit&aacute;rios. Cont&eacute;m informa&ccedil;&atilde;o cifrada da identifica&ccedil;&atilde;o do Facebook e do browser.</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 3 meses</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_1\">presence</td>\r\n			<td class=\"cor2_op_05\">Cont&eacute;m informa&ccedil;&atilde;o do estado do chat</td>\r\n			<td class=\"cor2_op_1\">Quando o browser &eacute; fechado</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_1\">sb</td>\r\n			<td class=\"cor2_op_05\">-</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 2 anos</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_1\">wd</td>\r\n			<td class=\"cor2_op_05\">Cont&eacute;m informa&ccedil;&atilde;o da dimens&atilde;o da janela do browser</td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 7 dias</td>\r\n		</tr>\r\n		<tr>\r\n			<td class=\"cor2_op_1\">xs</td>\r\n			<td class=\"cor2_op_05\">Cont&eacute;m n&uacute;mero de sess&atilde;o e&nbsp;<em>secret</em></td>\r\n			<td class=\"cor2_op_1\">Ap&oacute;s 3 meses</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>2. Aceitar/recusar cookies</strong><br />\r\nPoder&aacute; a qualquer momento optar por aceitar ou recusar a instala&ccedil;&atilde;o de <em>cookies </em>no seu terminal, configurando o software de navega&ccedil;&atilde;o.&nbsp;<br />\r\n<br />\r\n<strong>2.1 Se aceitar cookies</strong>&nbsp;<br />\r\nO registo de uma cookie no seu terminal depende da sua vontade. Em qualquer momento, poder&aacute; exprimir e modificar a sua escolha gratuitamente atrav&eacute;s das op&ccedil;&otilde;es disponibilizadas pelo seu software de navega&ccedil;&atilde;o.&nbsp;<br />\r\n<br />\r\nSe no software de navega&ccedil;&atilde;o que utiliza tiver aceitado a grava&ccedil;&atilde;o de cookies no seu terminal, as cookies integradas nas p&aacute;ginas e conte&uacute;dos que tiver consultado poder&atilde;o ficar temporariamente armazenadas num espa&ccedil;o espec&iacute;fico do seu terminal. Nesse local, ser&atilde;o leg&iacute;veis apenas pelo emissor das mesmas.&nbsp;<br />\r\n<br />\r\n<strong>2.2 Qual &eacute; o interesse de aceitar cookies?&nbsp;</strong><br />\r\nQuando visitar website www.teste.pt, estas cookies registar&atilde;o determinadas informa&ccedil;&otilde;es que est&atilde;o armazenadas no seu terminal. Estas informa&ccedil;&otilde;es servem, nomeadamente, para lhe prop&ocirc;r produtos em fun&ccedil;&atilde;o dos artigos que j&aacute; seleccionou aquando de suas visitas anteriores, e permitem-lhe assim beneficiar de uma melhor navega&ccedil;&atilde;o no nosso Site.&nbsp;<br />\r\n<br />\r\n<strong>3.2 Se recusar <em>cookies&nbsp;</em></strong><br />\r\nQuando recusa <em>cookies,</em> n&oacute;s instalamos contudo uma <em>cookie</em> de <em>&quot;recusa</em>&quot;. Esta <em>cookie </em>permite-nos memorizar a sua escolha, de modo a evitar que lhe perguntemos a cada visita se deseja aceitar ou recusar cookies.<br />\r\n&nbsp;<br />\r\n<strong>3.3 Configura&ccedil;&atilde;o do seu navegador</strong><br />\r\nA configura&ccedil;&atilde;o da gest&atilde;o das cookies depende de cada navegador e est&aacute; presente no menu de ajuda do seu navegador.<br />\r\n<br />\r\n<strong>No Internet Explorer</strong><br />\r\nPara personalizar as defini&ccedil;&otilde;es relativas &agrave;s cookies para uma p&aacute;gina web<br />\r\nNo Internet Explorer, clique no bot&atilde;o Ferramentas e depois em Op&ccedil;&otilde;es da Internet.<br />\r\nClique no separador Privacidade e depois em Sites.<br />\r\nNo espa&ccedil;o Endere&ccedil;o do Web site, insira o endere&ccedil;o completo (URL) da p&aacute;gina web cujas defini&ccedil;&otilde;es de privacidade deseja personalizar.&nbsp;<br />\r\nPor exemplo, http://www.microsoft.com.<br />\r\nPara autorizar o registo de cookies da p&aacute;gina web especificada no seu computador, clique em Permitir. Para proibir o registo de cookies da p&aacute;gina web especificada no seu computador, clique em Bloquear.<br />\r\nRepita as etapas 3 e 4 para cada p&aacute;gina web que queira bloquear ou permitir. Quando terminar, clique em OK duas vezes.<br />\r\n<br />\r\n<strong>No Safari</strong><br />\r\nV&aacute; a Prefer&ecirc;ncias, clique no painel Privacidade e, de seguida, Gerir as Cookies.<br />\r\n<br />\r\n<strong>No Chrome</strong><br />\r\nClique no menu do Chrome que est&aacute; situado na barra de ferramentas do navegador.<br />\r\nSeleccione Defini&ccedil;&otilde;es.<br />\r\nClique em Mostrar defini&ccedil;&otilde;es avan&ccedil;adas.<br />\r\nNa sec&ccedil;&atilde;o &quot;Privacidade&quot;, clique no bot&atilde;o Defini&ccedil;&otilde;es de conte&uacute;do.<br />\r\nNa sec&ccedil;&atilde;o &quot;Cookies&quot; poder&aacute; modificar as defini&ccedil;&otilde;es seguintes:<br />\r\nEliminar cookies&nbsp;<br />\r\nBloquear cookies por predefini&ccedil;&atilde;o&nbsp;<br />\r\nPermitir cookies por predefini&ccedil;&atilde;o<br />\r\nCriar excep&ccedil;&otilde;es para cookies de Websites ou dom&iacute;nios espec&iacute;ficos.<br />\r\n<br />\r\n<strong>No Firefox</strong><br />\r\nClique no bot&atilde;o Ferramentas e depois em Op&ccedil;&otilde;es.<br />\r\nClique no separador Privacidade.<br />\r\nNo Hist&oacute;rico, seleccione &quot;Usar defini&ccedil;&otilde;es personalizadas para o hist&oacute;rico.&quot;.<br />\r\nAssinalar a quadr&iacute;cula &quot;aceitar cookies&quot; e clicar em excep&ccedil;&otilde;es para escolher os s&iacute;tios web que ter&atilde;o ou n&atilde;o ter&atilde;o permiss&atilde;o para instalar cookies no seu terminal.<br />\r\n<br />\r\n<strong>No Opera</strong><br />\r\nPrefer&ecirc;ncias &gt; Avan&ccedil;ado&gt; Cookies&nbsp;<br />\r\nAs prefer&ecirc;ncias de cookies permitem-lhe controlar a forma como o Opera gere as cookies. A configura&ccedil;&atilde;o por defeito &eacute; aceitar todas as cookies.<br />\r\nAceitar cookies&nbsp;<br />\r\n- Todas as cookies s&atilde;o aceites (por defeito)<br />\r\nAceitar cookies apenas do site que visito&nbsp;<br />\r\n- As cookies de terceiros, de um dom&iacute;nio exterior &agrave;quele que est&aacute; a visitar, ser&atilde;o recusadas<br />\r\nNunca aceitar cookies&nbsp;<br />\r\n- Todas as cookies ser&atilde;o recusadas<br />\r\n&nbsp;<br />\r\n<strong>4. Atualiza&ccedil;&atilde;o da Notifica&ccedil;&atilde;o Legal</strong><br />\r\nA Natura Sa&uacute;de reserva-se o direito de fazer quaisquer altera&ccedil;&otilde;es ou corre&ccedil;&otilde;es a esta notifica&ccedil;&atilde;o de cookies. Por favor consulte esta p&aacute;gina regularmente de modo a rever a informa&ccedil;&atilde;o e verificar se existe alguma atualiza&ccedil;&atilde;o.<br />\r\n<br />\r\nEsta notifica&ccedil;&atilde;o foi atualizada em 3 de Maio de 2018',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	1),
(23,	4,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	NULL,	'RAL e RLL',	'',	'',	'<strong>RAL</strong><br />\r\n<br />\r\nEm caso de lit&iacute;gio o consumidor pode recorrer a uma entidade de Resolu&ccedil;&atilde;o Alternativa de Lit&iacute;gios de Consumo (RAL). As entidades de Resolu&ccedil;&atilde;o Alternativa de Lit&iacute;gios de Consumo (RAL) s&atilde;o as entidades autorizadas a efetuar a media&ccedil;&atilde;o, concilia&ccedil;&atilde;o e arbitragem de lit&iacute;gios de consumo em Portugal que estejam inscritas na lista de entidades RAL prevista pela Lei n.&ordm; 144/2015.<br />\r\nClique em&nbsp;<a href=\"http://www.ipai.pt/fotos/gca/i006245_1459446712.pdf\" target=\"_blank\">www.ipai.pt/fotos/gca/i006245_1459446712.pdf</a><br />\r\n<br />\r\n<strong>RLL</strong><br />\r\n<br />\r\nO consumidor pode recorrer &agrave; plataforma europeia de resolu&ccedil;&atilde;o de lit&iacute;gios em linha dispon&iacute;vel em&nbsp;<a href=\"https://ec.europa.eu/consumers/odr/main/index.cfm?event=main.home2.show&amp;lng=PT\" target=\"_blank\">ec.europa.eu/consumers/odr/main/index.cfm?event=main.home2.show&amp;lng=PT</a>',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	1),
(45,	3,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Privacy Policy',	'',	'',	'<strong>Pol&iacute;tica de Privacidade e Seguran&ccedil;a</strong><br />\r\n&nbsp;<br />\r\nTodos os dados recolhidos t&ecirc;m o &uacute;nico objetivo de identifica&ccedil;&atilde;o de clientes e para o cumprimento eficiente do servi&ccedil;o que nos propomos prestar.<br />\r\n<br />\r\nO tratamento dos seus dados &eacute; feito no cumprimento da legisla&ccedil;&atilde;o sobre a prote&ccedil;&atilde;o de dados pessoais. Os mesmos, sujeitos a tratamento inform&aacute;tico, constar&atilde;o na nossa base de dados e destina-se ao registo, opera&ccedil;&otilde;es estat&iacute;sticas e apresenta&ccedil;&atilde;o de outros produtos e servi&ccedil;os, bem como informa&ccedil;&atilde;o institucional.<br />\r\n<br />\r\nDe acordo com Regulamento (UE) 2016/679, do Parlamento Europeu e do conselho, de 26 de abril de 2016, vulgarmente conhecido por Regulamento Geral de Prote&ccedil;&atilde;o de Dados (RGPD), informamos que:<br />\r\n1. os dados pessoais recolhidos destinam-se exclusivamente a identificar os clientes;<br />\r\n2. a Entidade Gestora dos dados pessoais ora disponibilizados &eacute; a NATURA ONLINE, DE Nat&aacute;lia &amp; Nuno Lda., com morada na Rua Dr. Cassiano Baptista, fra&ccedil;&atilde;o N, n&ordm; 28, 4990-144 Ponte de Lima, e email mithilchauhan@gmail.com.<br />\r\n3. os dados pessoais recolhidos ser&atilde;o tratados informaticamente e destinam-se ao cumprimento da rela&ccedil;&atilde;o contratual com os clientes da loja online, &agrave; elabora&ccedil;&atilde;o de estat&iacute;sticas e ao envio de informa&ccedil;&otilde;es, a&ccedil;&otilde;es promocionais, envio de amostras ou produtos da marca/distribu&iacute;dos pelo NATURA ONLINE, titular dos dados e respons&aacute;vel pelo seu tratamento.<br />\r\n4. apenas foram recolhidos e ser&atilde;o tratados os dados necess&aacute;rios para os efeitos referidos na al&iacute;nea anterior;<br />\r\n5. Informamos, ainda, que:<br />\r\na) nunca disponibilizaremos os seus dados a terceiros, exceto empresa com rela&ccedil;&atilde;o profissional, no &acirc;mbito da presta&ccedil;&atilde;o de servi&ccedil;os da loja online (exemplo transportadora);<br />\r\nb) manteremos todos os seus dados confidenciais;<br />\r\nc) comprometemo-nos a cumprir as normas constantes do RGPD;<br />\r\nd) o titular dos dados pessoais (V/ Exa.) tem direito de solicitar o acesso aos seus dados pessoais, a sua retifica&ccedil;&atilde;o, portabilidade ou apagamento, assim como de limitar ou de se opor ao tratamento dos seus dados pessoais. O titular poder&aacute; retirar o consentimento em qualquer altura, sem comprometer a licitude do tratamento efetuado com base no consentimento anteriormente dado. Para exercer qualquer dos seus direitos, deve o titular contactar a Entidade Gestora anteriormente referida;<br />\r\ne) caso o titular considere que os seus dados pessoais n&atilde;o s&atilde;o tratados licitamente ou que os seus direitos n&atilde;o s&atilde;o respeitados, poder&aacute; apresentar uma reclama&ccedil;&atilde;o/queixa &agrave; Comiss&atilde;o Nacional de Prote&ccedil;&atilde;o de Dados, ou &agrave; Entidade que a venha a substituir.<br />\r\n<br />\r\nAs informa&ccedil;&otilde;es deste site poder&atilde;o conter incorre&ccedil;&otilde;es de natureza t&eacute;cnica e/ou tipogr&aacute;fica.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(46,	9,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Quem Somos Heading',	'Quem Somos',	'',	'<strong>Espa&ccedil;o Natura Sa&uacute;de</strong>, um espa&ccedil;o acolhedor onde se promove o equil&iacute;brio e a medicina natural.<br />\r\n<br />\r\nPorque acreditamos que a natureza disponibiliza os elementos essenciais para mantermos uma vida saud&aacute;vel, decidimos abra&ccedil;ar este projeto, aplicar o nosso conhecimento com o intuito de oferecer tratamentos de excelencia com produtos naturais, op&ccedil;&otilde;es complementares aos cuidados de sa&uacute;de convencionais. Atrav&eacute;s de uma abordagem integrativa, e do uso de suplementos alimentares, &oacute;leos essenciais, terapia ortomolecular, terapia qu&acirc;ntica, terapias manuais e aconselhamento personalizado realizados por profissionais adequadamente qualificados. Outro dos objetivos &eacute; estimular as pessoas para a import&acirc;ncia da forma como abordam a sua sa&uacute;de e para o uso respons&aacute;vel das formas de terapia dispon&iacute;veis, de forma a aumentar a sua qualidade de vida e bem-estar.<br />\r\n<br />\r\nA <strong>Natura Sa&uacute;de</strong> abriu ao publico em abril de 2011, na rua Dr. Cassiano Batista, n&ordm; 28, em Ponte de Lima. Acreditamos no nosso projeto, este foi crescendo e felizmente temos clientes em v&aacute;rias partes do mundo, que acreditam no nosso trabalho e dedica&ccedil;&atilde;o.&nbsp; Para chegar mais facilmente a estes clientes que est&atilde;o longe e para todos aqueles que preferem fazer as suas compras sem sair de casa, criamos a <strong>Natura Online</strong>.<br />\r\nSomos uma equipa jovem com elevada forma&ccedil;&atilde;o e sempre dispon&iacute;veis para esclarecer as d&uacute;vidas dos nossos clientes e prestar o melhor aconselhamento.<br />\r\n&nbsp;',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(47,	7,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Métodos de Pagamento',	'Métodos de Pagamento',	'',	'<strong>PAYPAL</strong><br />\r\nO pagamento por Paypal &eacute; um m&eacute;todo seguro utilizado em todo o mundo. Para mais informa&ccedil;&otilde;es sobre este m&eacute;todo de pagamento, visite www.paypal.pt.<br />\r\nGuardar dados para futuras transa&ccedil;&otilde;es Paypal<br />\r\nAo subscrever esta op&ccedil;&atilde;o, o cliente n&atilde;o ter&aacute; necessidade de aceder &agrave; p&aacute;gina do Paypal numa pr&oacute;xima compra. O valor da compra em quest&atilde;o ser&aacute; automaticamente debitado da conta Paypal utilizada aquando da subscri&ccedil;&atilde;o desta op&ccedil;&atilde;o.<br />\r\n&nbsp;<br />\r\nA qualquer momento, o cliente pode aceder &agrave; conta Paypal e remover esta op&ccedil;&atilde;o. Para tal, dever&aacute; aceder aos menus Perfil &gt;Meu dinheiro &gt;Os meus pagamentos pr&eacute;-aprovados. A&iacute;, aparecer&atilde;o todos os pagamentos pr&eacute;-aprovados e bastar&aacute; cancelar o pagamento associado &agrave; NATURA ONLINE.<br />\r\n&nbsp;<br />\r\n<strong>NOTA IMPORTANTE:</strong> a <strong>NATURA ONLINE</strong> n&atilde;o guarda quaisquer dados de login da conta Paypal do cliente.<br />\r\n&nbsp;<br />\r\n<strong>MULTIBANCO</strong><br />\r\nOs pagamentos por Multibanco podem ser efetuados atrav&eacute;s da Rede de Caixas Multibanco ou atrav&eacute;s do seu Homebanking.<br />\r\n&nbsp;<br />\r\nAo finalizar a sua encomenda, ser&aacute; gerada uma refer&ecirc;ncia Multibanco. O cliente receber&aacute; esta refer&ecirc;ncia no e-mail de confirma&ccedil;&atilde;o da encomenda, embora esta tamb&eacute;m seja disponibilizada nos &ldquo;Detalhes da encomenda&rdquo;, no separador &ldquo;Encomendas&rdquo; na conta <strong>NATURA ONLINE</strong> do cliente.<br />\r\n&nbsp;<br />\r\nPara efetuar o pagamento numa caixa Multibanco, o cliente tem de selecionar a op&ccedil;&atilde;o &ldquo;Pagamento de Servi&ccedil;os/Compras&rdquo; e inserir a entidade, refer&ecirc;ncia e valor correspondentes.<br />\r\n&nbsp;<br />\r\nA encomenda apenas ser&aacute; enviada ap&oacute;s a <strong>NATURA ONLINE</strong> receber a confirma&ccedil;&atilde;o de que o pagamento foi finalizado. As transa&ccedil;&otilde;es Multibanco necessitam de aproximadamente 1 dia &uacute;til para serem finalizadas.<br />\r\n&nbsp;<br />\r\nOs produtos s&atilde;o reservados durante 4 dias. Caso a <strong>NATURA ONLINE</strong> n&atilde;o receba o pagamento da encomenda durante este per&iacute;odo, a encomenda ser&aacute; cancelada.<br />\r\n&nbsp;<br />\r\n<strong>PAGAMENTO &Agrave; COBRAN&Ccedil;A</strong><br />\r\nAo escolher o pagamento &agrave; cobran&ccedil;a, o cliente apenas ter&aacute; de efetuar o pagamento da encomenda no ato da entrega da mesma.<br />\r\n&nbsp;<br />\r\nO pagamento ter&aacute; de ser feito em numer&aacute;rio no valor exato da cobran&ccedil;a, dado que os estafetas n&atilde;o est&atilde;o obrigados a dispor de troco. De facto, se o valor pago for superior ao valor da cobran&ccedil;a, o cliente arriscar-se-&aacute; a perder o valor da diferen&ccedil;a.<br />\r\n&nbsp;<br />\r\nA <strong>NATURA ONLINE</strong> apenas poder&aacute; enviar uma nova encomenda &agrave; cobran&ccedil;a se a &uacute;ltima encomenda &agrave; cobran&ccedil;a j&aacute; tiver sido entregue ao cliente.<br />\r\n&nbsp;<br />\r\nSe tiverem sido registadas duas encomendas &agrave; cobran&ccedil;a no mesmo dia, a segunda encomenda s&oacute; ser&aacute; enviada depois de a primeira ter sido entregue e assim sucessivamente.<br />\r\n&nbsp;<br />\r\nEste m&eacute;todo de pagamento apenas est&aacute; dispon&iacute;vel para Portugal continental.<br />\r\n&nbsp;<br />\r\nAten&ccedil;&atilde;o: Se optar pelo envio atrav&eacute;s dos CTT, o pagamento &agrave; cobran&ccedil;a ter&aacute; um custo adicional de 4,90 &euro;, exceto compras superiores a 30&euro;, em que o envio &eacute; gratuito.<br />\r\n&nbsp;',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(48,	8,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Entregas e Devoluções',	'Entregas e Devoluções',	'',	'<strong>DEVOLU&Ccedil;&Otilde;ES</strong><br />\r\nCaso o cliente pretenda devolver um ou mais artigos, poder&aacute; faz&ecirc;-lo no prazo de 14 dias ap&oacute;s a rece&ccedil;&atilde;o da encomenda, e respeitando os seguintes requisitos:\r\n<ol>\r\n	<li>&nbsp;A devolu&ccedil;&atilde;o s&oacute; ser&aacute; poss&iacute;vel se os produtos se encontrem intactos, selados, completos e dentro da embalagem original, a qual n&atilde;o poder&aacute; estar danificada, alterada ou riscada.</li>\r\n	<li>O cliente dever&aacute; garantir que o(s) artigo(s) devolvido(s) chega(m) &agrave;s nossas instala&ccedil;&otilde;es no prazo de 28 dias a contar da sua entrega.</li>\r\n	<li>Para exercer o seu direito de devolu&ccedil;&atilde;o, o cliente dever&aacute; contactar-nos, especificar o n&uacute;mero da encomenda e a descri&ccedil;&atilde;o ou a refer&ecirc;ncia do(s) produto(s) que pretende devolver.</li>\r\n	<li>Ap&oacute;s rece&ccedil;&atilde;o dos produtos ser&aacute; feito apenas por nota de cr&eacute;dito.</li>\r\n	<li>O cliente ter&aacute; tamb&eacute;m de ter em aten&ccedil;&atilde;o se o produto que pretende devolver foi adquirido no &acirc;mbito de alguma campanha promocional e se, por esse motivo, beneficiou de algum produto de oferta. Se tiver sido o caso, dever&aacute; devolver n&atilde;o s&oacute; o produto em quest&atilde;o, mas tamb&eacute;m a oferta que lhe foi atribu&iacute;da com a sua compra. Ambos os produtos dever&atilde;o cumprir as diretrizes anteriormente mencionadas.</li>\r\n	<li>Apenas &eacute; poss&iacute;vel a devolu&ccedil;&atilde;o de um pack na sua totalidade. Caso o cliente pretenda devolver um produto que perten&ccedil;a a um pack, dever&aacute; fazer a devolu&ccedil;&atilde;o de todos os produtos que constituem esse mesmo pack. Todos os produtos dever&atilde;o cumprir as diretrizes anteriormente referidas.</li>\r\n	<li>O cliente deve enviar-nos o(s) artigo(s) por correio registado para a seguinte morada:</li>\r\n</ol>\r\nNatura online<br />\r\nNat&aacute;lia &amp; Nuno Lda<br />\r\nRua Dr. Cassiano Baptista, Fra&ccedil;&atilde;o N, n&ordm; 28<br />\r\n4990-144 Ponte de Lima<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\nA devolu&ccedil;&atilde;o de produtos incompletos, danificados ou riscados, assim como de produtos sem a embalagem e/ou etiqueta original, ou produtos com sinais de terem sido utilizados, n&atilde;o ser&aacute; aceite.<br />\r\n<br />\r\nTamb&eacute;m n&atilde;o ser&aacute; aceite a devolu&ccedil;&atilde;o de produtos que tenham uma data de validade inferior a um m&ecirc;s, nem ofertas ou produtos trocados.<br />\r\n<br />\r\nSe recebermos um artigo nas condi&ccedil;&otilde;es acima mencionadas ou com a etiqueta do transit&aacute;rio colada diretamente na embalagem, o cliente perder&aacute; o direito a ser reembolsado e ter&aacute; 10 dias para recolher o(s) artigo(s) nas nossas instala&ccedil;&otilde;es, suportando os custos de recolha.<br />\r\n&nbsp;<br />\r\n<strong>TROCAS</strong><br />\r\nO cliente dever&aacute; proceder &agrave; devolu&ccedil;&atilde;o do(s) artigo(s), solicitar o respetivo reembolso e efetuar uma nova compra.<br />\r\nTodo o processo de devolu&ccedil;&atilde;o est&aacute; devidamente descrito no item DEVOLU&Ccedil;&Otilde;ES.<br />\r\n<br />\r\n<strong>M&Eacute;TODOS DE REEMBOLSO</strong><br />\r\nA NATURA ONLINE far&aacute; o poss&iacute;vel para enviar nota de cr&eacute;dito ao cliente rapidamente, salvaguardando, no entanto, um prazo m&aacute;ximo de 15 dias a contar da rece&ccedil;&atilde;o da devolu&ccedil;&atilde;o nos nossos armaz&eacute;ns.<br />\r\n&nbsp;<br />\r\n<strong>REEMBOLSO DOS GASTOS DE ENVIO</strong><br />\r\nCaso o motivo da devolu&ccedil;&atilde;o da encomenda tenha sido da responsabilidade da NATURA ONLNE, reembolsaremos os respetivos gastos de envio (exemplo: produtos com defeito ou diferentes dos artigos efetivamente encomendados).<br />\r\n<br />\r\nContudo, se, numa encomenda de m&uacute;ltiplos produtos, apenas um tiver apresentado algum problema, n&atilde;o procederemos ao reembolso dos gastos de envio. Se a devolu&ccedil;&atilde;o do produto for igual ao valor total da encomenda (o cliente no ato da compra beneficiou de portes gratuitos), a NATURA ONLINE apenas reembolsar&aacute; o valor do produto subtraindo o valor dos portes que foram imputados &agrave; empresa nesse envio.<br />\r\n<br />\r\nSe a devolu&ccedil;&atilde;o for da responsabilidade do cliente, a NATURA ONLINE emite nota de cr&eacute;dito, para o cliente usar numa compra posterior, apenas quando o produto for recebido em perfeitas condi&ccedil;&otilde;es (sem que tenha sido aberto e/ou danificado).<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;',	'',	'',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	1,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(49,	10,	0,	1,	NULL,	NULL,	1,	1,	0,	1,	NULL,	1,	NULL,	'texto img',	'',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	1,	0,	0,	0,	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	99),
(50,	6,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Termos e Condições',	'Termos e Condições',	'',	'<span style=\"font-size:14px;\"><strong>Termos e Condi&ccedil;&otilde;es Gerais de Venda</strong></span><br />\r\n<br />\r\n<strong>1. Pol&iacute;tica comercial</strong><br />\r\n1.1 A Natura Sa&uacute;de tem produtos para venda no site &ldquo;Natura Online&rdquo;, sendo que os servi&ccedil;os de &ldquo;ecommerce&rdquo; est&atilde;o dispon&iacute;veis para os utilizadores finais / &ldquo;consumidores&rdquo; e para &ldquo;terapeutas&rdquo;.<br />\r\n&nbsp;<br />\r\n1.2 O &ldquo;Consumidor&rdquo; indica o indiv&iacute;duo que atua/compra fora do seu neg&oacute;cio ou profiss&atilde;o. Se &eacute; um &ldquo;terapeuta&rdquo; solicitamos que antes de efetuar compras no site deve fazer o registo e enviar email com comprovativo de profiss&atilde;o/ terapeuta, posteriormente recebe as condi&ccedil;&otilde;es especiais para &ldquo;terapeuta&rdquo;.<br />\r\n&nbsp;<br />\r\n1.3 A Natura Sa&uacute;de reserva-se ao direito de n&atilde;o processar encomendas rececionadas de utilizadores: &ldquo;Consumidores&rdquo; ou &ldquo;terapeutas&rdquo;, aquelas que n&atilde;o est&atilde;o de acordo com as condi&ccedil;&otilde;es gerais de venda.<br />\r\n&nbsp;<br />\r\n<strong>2. Como encomendar</strong><br />\r\n2.1 Para encomendar em Natura Online &eacute; necess&aacute;rio que o utilizador fa&ccedil;a o seu registo, para isso &eacute; necess&aacute;rio que disponha de uma conta de e-mail e que o seu browser esteja configurado para aceitar cookies e pop-ups, no sentido de permitir o usufruto de todas as condi&ccedil;&otilde;es de navegabilidade da loja Natura Online.<br />\r\n&nbsp;<br />\r\nFazer uma encomenda em Natura Online muito &eacute; f&aacute;cil:<br />\r\n&nbsp;<br />\r\n1&ordm; Encontre os produtos que pretende adquirir;<br />\r\n&nbsp;<br />\r\n2&ordm; Adicione os produtos selecionados ao Carrinho;<br />\r\n&nbsp;<br />\r\n3&ordm; Finalize a compra;<br />\r\n&nbsp;<br />\r\n4&ordm; Selecione a op&ccedil;&atilde;o de pagamento;<br />\r\n&nbsp;<br />\r\n5&ordm; Reveja a sua encomenda, para confirmar se est&aacute; tudo conforme pretende;<br />\r\n&nbsp;<br />\r\n6&ordm; Confirme a encomenda e proceda ao pagamento;<br />\r\n&nbsp;<br />\r\n7&ordm; Ir&aacute; receber uma confirma&ccedil;&atilde;o da encomenda por e-mail;<br />\r\n&nbsp;<br />\r\nA sua encomenda est&aacute; confirmada quando seleciona o bot&atilde;o &ldquo;Confirmar Pagamento&quot; (Checkout) no final do processo de compra.<br />\r\n&nbsp;<br />\r\nQuando a Natura Online recebe a sua encomenda surgir&aacute; no ecr&atilde; uma mensagem com o seu n&uacute;mero de encomenda e todos os detalhes da mesma. O acordo de compra &eacute; finalizado nesse momento.&nbsp;<br />\r\n&nbsp;<br />\r\nUma confirma&ccedil;&atilde;o da encomenda &eacute; enviada para o seu e-mail. Sugerimos que o imprima ou efetue download desta c&oacute;pia para refer&ecirc;ncia futura.<br />\r\n&nbsp;<br />\r\nReservamos o direito de n&atilde;o aceitar a sua encomenda, ou cancel&aacute;-la mesmo depois da confirma&ccedil;&atilde;o autom&aacute;tica da mesma.<br />\r\n&nbsp;<br />\r\nAo enviar o formul&aacute;rio de encomenda est&aacute; a aceitar as condi&ccedil;&otilde;es gerais de compra, assim como outras condi&ccedil;&otilde;es existentes em Natura Online, atrav&eacute;s de links, incluindo as condi&ccedil;&otilde;es e termos gerais de utiliza&ccedil;&atilde;o.&nbsp;<br />\r\n&nbsp;<br />\r\nA Natura Online poder&aacute; n&atilde;o processar encomendas que n&atilde;o tenham garantia suficiente de boa cobran&ccedil;a; quando as encomendas est&atilde;o incompletas ou incorretas, ou os produtos encomendados j&aacute; n&atilde;o se encontram dispon&iacute;veis. Nos casos referidos, inform&aacute;-lo-emos por e-mail que o contrato de venda n&atilde;o foi efetuado e que o vendedor n&atilde;o prosseguiu com a venda, especificando as raz&otilde;es para tal.&nbsp;<br />\r\n&nbsp;<br />\r\nSe os produtos apresentados na loja Natura Online j&aacute; n&atilde;o se encontram dispon&iacute;veis no momento do seu &uacute;ltimo acesso ou quando o vendedor receciona o pedido, o vendedor dever&aacute; informar sobre indisponibilidade dos mesmos dentro de 30 dias ap&oacute;s a rece&ccedil;&atilde;o do pedido.<br />\r\nSe tiver sido feita uma encomenda e respetivo pagamento para produtos que j&aacute; n&atilde;o est&atilde;o dispon&iacute;veis, a Natura Online proceder&aacute; ao estorno do dinheiro pago pelos itens indispon&iacute;veis.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n2.2 Direito a n&atilde;o aceitar a encomenda ou cancelar a compra<br />\r\n&nbsp;<br />\r\nA Natura Online reserva-se ao direito de recusar qualquer encomenda por qualquer motivo. Reservamo-nos igualmente ao direito de cancelar qualquer compra (mesmo que j&aacute; tenha sido aceite por n&oacute;s), (entre outras) nas seguintes situa&ccedil;&otilde;es, isenta de responsabiliza&ccedil;&atilde;o por quaisquer danos ou custos:<br />\r\n&nbsp;<br />\r\nO produto j&aacute; n&atilde;o se encontra dispon&iacute;vel/em stock (os pagamentos ser&atilde;o devolvidos);<br />\r\n&nbsp;<br />\r\nA informa&ccedil;&atilde;o de fatura&ccedil;&atilde;o n&atilde;o &eacute; correta ou verific&aacute;vel;<br />\r\n&nbsp;<br />\r\nA encomenda &eacute; sinalizada pelos nossos Sistemas de Seguran&ccedil;a como uma encomenda incorreta ou uma encomenda suscet&iacute;vel de fraude;<br />\r\n&nbsp;<br />\r\nA transfer&ecirc;ncia para pagamento da encomenda n&atilde;o foi por n&oacute;s rececionada num per&iacute;odo de 5 dias ap&oacute;s a aceita&ccedil;&atilde;o da sua encomenda;<br />\r\n&nbsp;<br />\r\nSe acharmos que tem menos de 16 anos;<br />\r\n&nbsp;<br />\r\nN&atilde;o for poss&iacute;vel entregar no endere&ccedil;o fornecido.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n2.3 Verifica&ccedil;&atilde;o de dados<br />\r\n&nbsp;<br />\r\nAp&oacute;s rececionar a encomenda podemos executar algumas verifica&ccedil;&otilde;es sobre a mesma antes de a prepararmos.<br />\r\n&nbsp;<br />\r\nEstas verifica&ccedil;&otilde;es podem incluir a verifica&ccedil;&atilde;o de endere&ccedil;o, credibilidade e verifica&ccedil;&atilde;o de fraude. Em rela&ccedil;&atilde;o &agrave; verifica&ccedil;&atilde;o de fraude executamos verifica&ccedil;&otilde;es parcialmente automatizadas em todas as encomendas para filtrar transa&ccedil;&otilde;es incomuns, suspeitas ou identificadas como suscet&iacute;veis de fraude. As suspeitas de fraude ser&atilde;o investigadas e, se necess&aacute;rio, processadas. Ao confirmar o seu pedido significa que concorda com estas condi&ccedil;&otilde;es.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>3. Garantias e Indica&ccedil;&atilde;o do pre&ccedil;o do produto</strong><br />\r\n3.1 M&eacute;todos de Pagamento<br />\r\n&nbsp;<br />\r\nPode encontrar as formas de pagamento dispon&iacute;veis na sec&ccedil;&atilde;o de Ajuda/FAQ&#39;s do site. N&atilde;o aceitamos qualquer outro m&eacute;todo de pagamento al&eacute;m dos mencionados. Por favor n&atilde;o tente pagar por qualquer outro meio que n&atilde;o especificado. Se o fizer n&atilde;o poderemos ser responsabilizados por perda do pagamento ou quaisquer outros danos que da&iacute; possam advir.<br />\r\n&nbsp;<br />\r\n3.2 Processamento do pagamento<br />\r\n&nbsp;<br />\r\nSe proceder ao pagamento com Cart&atilde;o de Cr&eacute;dito deduziremos o montante devido na sua conta logo que a sua encomenda saia do nosso armaz&eacute;m. No caso do pagamento com Cart&atilde;o de Cr&eacute;dito, todos os detalhes (por exemplo n&uacute;mero de cart&atilde;o ou data de validade) ser&atilde;o enviados pelo encrypted protocol para a empresa que fornece os servi&ccedil;os de pagamento eletr&oacute;nico remoto, sem que terceiros possam ter acesso &agrave; informa&ccedil;&atilde;o transmitida.<br />\r\n&nbsp;<br />\r\nEstas informa&ccedil;&otilde;es n&atilde;o ser&atilde;o utilizadas pela Natura Online exceto para realizar os procedimentos necess&aacute;rios para a compra ou estorno, no caso de devolu&ccedil;&atilde;o, em conformidade com o exerc&iacute;cio do direito de retorno ou para comunica&ccedil;&atilde;o, &agrave; pol&iacute;cia, de casos de fraude.<br />\r\n&nbsp;<br />\r\nO pre&ccedil;o de compra dos produtos e os custos de transporte, tal como indicados no formul&aacute;rio de encomenda, ser&atilde;o cobrados &agrave; conta corrente quando os produtos adquiridos s&atilde;o efetivamente enviados.<br />\r\n&nbsp;<br />\r\n<strong>4. Seguran&ccedil;a da encomenda</strong><br />\r\n4.1 A Natura Online usa uma tecnologia de seguran&ccedil;a das mais robustas entre as plataformas de encripta&ccedil;&atilde;o dispon&iacute;veis.<br />\r\n&nbsp;<br />\r\nAs suas informa&ccedil;&otilde;es de pagamento ser&atilde;o encriptadas a partir do momento em que entra, at&eacute; que a sua transa&ccedil;&atilde;o seja processada e n&atilde;o ser&atilde;o guardadas num servidor p&uacute;blico. Apesar de utilizarmos um software de encripta&ccedil;&atilde;o, a seguran&ccedil;a da informa&ccedil;&atilde;o e pagamentos transmitidos pela Internet ou atrav&eacute;s de e-mail n&atilde;o pode ser garantida.<br />\r\n&nbsp;<br />\r\nA Natura Online n&atilde;o poder&aacute; ser responsabilizada por qualquer dano sofrido como resultado do uso de meios eletr&oacute;nicos de comunica&ccedil;&atilde;o incluindo, mas n&atilde;o limitado a danos recorrentes de falhas ou atrasos na entrega das comunica&ccedil;&otilde;es eletr&oacute;nicas, interce&ccedil;&otilde;es ou manipula&ccedil;&otilde;es das comunica&ccedil;&otilde;es eletr&oacute;nicas por terceiros ou por programas de computador utilizados para comunica&ccedil;&otilde;es ou transmiss&otilde;es de v&iacute;rus.<br />\r\n&nbsp;<br />\r\nSe optar pelo pagamento por Refer&ecirc;ncia Multibanco, iniciaremos o tratamento da encomenda, ap&oacute;s termos rececionado o pagamento. Tal pode demorar v&aacute;rios dias. Uma vez decorridos 5 dias de calend&aacute;rio ap&oacute;s a realiza&ccedil;&atilde;o da encomenda, e se n&atilde;o tivermos rececionado qualquer pagamento, a mesma ser&aacute; anulada.<br />\r\n&nbsp;<br />\r\nOs pagamentos s&oacute; poder&atilde;o ser processados se a informa&ccedil;&atilde;o a usar para fatura&ccedil;&atilde;o puder ser verificada.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>5. Reserva de propriedade</strong><br />\r\nA Natura Online det&eacute;m a propriedade dos produtos da empresa at&eacute; que tenha recebido o pagamento integral de todos esses produtos.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>6. Pre&ccedil;os</strong><br />\r\n6.1 Os pre&ccedil;os dos produtos exibidos no site cont&ecirc;m/comportam o Valor Acrescentado (IVA). As taxas de envio s&atilde;o aplicadas por encomenda. O pre&ccedil;o exato da taxa de envio depender&aacute; do pa&iacute;s onde a sua encomenda ser&aacute; entregue. Para informa&ccedil;&atilde;o adicional sobre as taxas de envio por pa&iacute;s consulte a se&ccedil;&atilde;o de Ajuda/FAQ&#39;s do site.<br />\r\n&nbsp;<br />\r\n6.2 Os pre&ccedil;os s&atilde;o todos cotados em euros. Por favor, note que a mudan&ccedil;a de pa&iacute;s de entrega pode ter influ&ecirc;ncia sobre os pre&ccedil;os devido aos custos de transporte.<br />\r\n&nbsp;<br />\r\n6.3 O seu pre&ccedil;o total<br />\r\nO pre&ccedil;o total especificado no menu final de checkout inclui impostos e custos de envio. Os pre&ccedil;os s&atilde;o cotados aquando da confirma&ccedil;&atilde;o da encomenda, a qual pode imprimir ou descarregar para utiliza&ccedil;&otilde;es futuras. Por favor pague apenas o montante exato especificado na confirma&ccedil;&atilde;o da encomenda e fa&ccedil;a pagamentos individuais por encomenda.<br />\r\n&nbsp;<br />\r\n6.4 Altera&ccedil;&otilde;es de pre&ccedil;o<br />\r\nOs pre&ccedil;os dos produtos apresentados no site s&atilde;o pass&iacute;veis de altera&ccedil;&atilde;o. A Natura Online reserva-se ao direito de alterar os pre&ccedil;os sem aviso pr&eacute;vio. Os pre&ccedil;os exibidos no momento da compra s&atilde;o os pre&ccedil;os aplicados a essa compra.<br />\r\n&nbsp;<br />\r\n6.5 Erros nos pre&ccedil;os<br />\r\nPor favor note que mesmo sendo o site atualizado com extrema aten&ccedil;&atilde;o &eacute; poss&iacute;vel que a informa&ccedil;&atilde;o sobre o pre&ccedil;o no site contenha erros. A Natura Online n&atilde;o se compromete com a oferta e reserva-se ao direito a cancelar a sua encomenda, no caso de tal erro ocorrer.<br />\r\n<br />\r\n<strong>8. Envio e entrega</strong><br />\r\n8.1 Entrega: onde e quando<br />\r\n&nbsp;<br />\r\nEntregamos os produtos de segunda a sexta-feira das 9h &agrave;s 18h. N&atilde;o efetuamos envios em determinados feriados. Apenas podemos cumprir a entrega de uma encomenda se o endere&ccedil;o de entrega for uma casa ou um escrit&oacute;rio situado num dos pa&iacute;ses mencionados na lista de pa&iacute;ses dispon&iacute;vel no menu Entrega.<br />\r\n&nbsp;<br />\r\nPara rececionar a encomenda &eacute; necess&aacute;rio garantir que algu&eacute;m esteja na morada. Este servi&ccedil;o n&atilde;o inclui op&ccedil;&atilde;o de agendamento ou marca&ccedil;&atilde;o, pelo que a entrega da encomenda ser&aacute; efetuada sem contacto pr&eacute;vio por parte da transportadora. N&atilde;o asseguramos este prazo nos per&iacute;odos de promo&ccedil;&otilde;es, saldos, Natal e rutura de stock.<br />\r\nUma entrega considera-se efetuada ou um produto considera-se entregue com a assinatura do recibo de entrega na morada acordada.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n8.2 Prazos de Entrega<br />\r\n&nbsp;<br />\r\nCaso confirme a sua encomenda num dia &uacute;til at&eacute; &agrave;s 12h e proceder ao pagamento com Cart&atilde;o de Cr&eacute;dito ou via Paypal, a sua encomenda ser&aacute; enviada no dia &uacute;til seguinte (fins de semana e feriados n&atilde;o s&atilde;o considerados dias &uacute;teis). Caso opte por pagamento por refer&ecirc;ncia multibanco, o envio &eacute; menos eficaz, uma vez que s&oacute; faremos o envio ap&oacute;s rececionarmos o pagamento. Tal pode demorar entre 5 a 7 dias.<br />\r\n&nbsp;<br />\r\nA Natura Online n&atilde;o ser&aacute; respons&aacute;vel por incumprimento, ou mora no cumprimento, de qualquer obriga&ccedil;&atilde;o que seja devida a um evento de for&ccedil;a maior, isto &eacute;, a um acontecimento, ato ou omiss&atilde;o, fora do seu controlo razo&aacute;vel (Evento de For&ccedil;a Maior - que compreende qualquer acontecimento, ato ou omiss&atilde;o, fora do controlo razo&aacute;vel do &quot;Vendedor&quot;).<br />\r\n&nbsp;<br />\r\nTentaremos entregar no tempo previsto, na medida em que tal seja comercialmente razo&aacute;vel. Por norma procedemos sempre desta forma, contudo, n&atilde;o podemos garantir a entrega atempada.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n8.3 Entregas Parciais<br />\r\n&nbsp;<br />\r\nSempre que poss&iacute;vel tentaremos entregar tudo em apenas um envio. No entanto, reservamo-nos o direito a dividir a entrega da encomenda, por exemplo (mas n&atilde;o limitado apenas a) se uma parte da encomenda est&aacute; atrasada ou n&atilde;o dispon&iacute;vel. No caso em que procedamos &agrave; divis&atilde;o da encomenda, o cliente ser&aacute; notificado, por e-mail, da nossa inten&ccedil;&atilde;o em proceder desta forma. Para o efeito ser&aacute; utilizado o endere&ccedil;o de e-mail fornecido pelo cliente no momento da compra.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n8.4 Inspe&ccedil;&atilde;o no momento da entrega<br />\r\n&nbsp;<br />\r\nNo momento da entrega, o cliente deve inspecionar a embalagem no sentido de averiguar eventuais danos. Se verificar que os produtos est&atilde;o danificados, o cliente n&atilde;o deve aceitar a entrega da encomenda, a qual ser&aacute; devolvida. A Natura Online proceder&aacute; ao reembolso dos valores pagos pela encomenda e pelo transporte, logo que o armaz&eacute;m rececione e complete o processo de devolu&ccedil;&atilde;o.<br />\r\n&nbsp;<br />\r\nQualquer reclama&ccedil;&atilde;o de n&atilde;o rece&ccedil;&atilde;o de encomenda, dever&aacute; ser efetuada num prazo m&aacute;ximo de 30 dias a partir da data da realiza&ccedil;&atilde;o da encomenda. Findo este prazo, a Natura Online n&atilde;o se poder&aacute; responsabilizar pelo extravio da mesma.<br />\r\n&nbsp;<br />\r\n<strong>9. Taxas de envio</strong><br />\r\n9.1 As taxas de envio s&atilde;o aplicadas por encomenda. O pre&ccedil;o exato de cada envio depende do pa&iacute;s de entrega. Para mais detalhes sobre o pre&ccedil;o de envio por pa&iacute;s, por favor, consulte o menu Entrega do site.<br />\r\n&nbsp;<br />\r\n9.2 Envio gr&aacute;tis<br />\r\nSe o cliente fizer uma encomenda cujo valor ultrapasse o valor m&iacute;nimo de encomenda definido para o seu pa&iacute;s ou campanha, o envio ser&aacute; feito sem qualquer custo, ou seja, gratuitamente.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>10. Devolu&ccedil;&otilde;es e cancelamentos</strong><br />\r\n10.1 Cancelamento da encomenda antes do envio<br />\r\n&nbsp;<br />\r\nO cliente pode cancelar a encomenda, desde que esta ainda n&atilde;o tenha sido enviada. Se o cliente efetuar uma encomenda e a pretender cancelar deve, num primeiro momento, verificar o status da encomenda atrav&eacute;s do menu A Minha Encomenda situado no canto superior direito da p&aacute;gina principal do site. E se o status da encomenda mencionar &ldquo;Registada&rdquo; existe a possibilidade de esta ainda ser cancelada. Contudo, o cliente dever&aacute; contactar o nosso Servi&ccedil;o de Apoio ao Cliente e pedir o cancelamento da sua encomenda. Apenas ap&oacute;s este procedimento &eacute; poss&iacute;vel o nosso Departamento de Apoio ao Cliente solicitar ao nosso armaz&eacute;m o cancelamento da encomenda.<br />\r\n&nbsp;<br />\r\nCaso o pedido de anula&ccedil;&atilde;o seja bem sucedido, o cliente ser&aacute; notificado por e-mail desta decis&atilde;o e a anula&ccedil;&atilde;o ser&aacute; processada sem custos para o cliente. Caso o pedido de anula&ccedil;&atilde;o n&atilde;o seja poss&iacute;vel os produtos ser&atilde;o enviados ao cliente que, caso mantenha a sua inten&ccedil;&atilde;o de anular a encomenda, dever&aacute; recusar a rece&ccedil;&atilde;o dos mesmos. Os produtos ser&atilde;o retornados e, ap&oacute;s rece&ccedil;&atilde;o, ser&aacute; processado o estorno.&nbsp;<br />\r\n&nbsp;<br />\r\n10.2 Devolu&ccedil;&otilde;es<br />\r\nCaso o cliente pretenda devolver um ou mais artigos, poder&aacute; faz&ecirc;-lo no prazo de 14 dias ap&oacute;s a rece&ccedil;&atilde;o da encomenda, e respeitando os seguintes requisitos:<br />\r\n&nbsp;<br />\r\ni.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; A devolu&ccedil;&atilde;o s&oacute; ser&aacute; poss&iacute;vel se os produtos se encontrem intactos, selados, completos e dentro da embalagem original, a qual n&atilde;o poder&aacute; estar danificada, alterada ou riscada.<br />\r\nii.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; O cliente dever&aacute; garantir que o(s) artigo(s) devolvido(s) chega(m) &agrave;s nossas instala&ccedil;&otilde;es no prazo de 28 dias a contar da sua entrega.<br />\r\niii.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Para exercer o seu direito de devolu&ccedil;&atilde;o, o cliente dever&aacute; contactar-nos, especificar o n&uacute;mero da encomenda e a descri&ccedil;&atilde;o ou a refer&ecirc;ncia do(s) produto(s) que pretende devolver.<br />\r\niv.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ap&oacute;s rece&ccedil;&atilde;o dos produtos ser&aacute; feito apenas por nota de cr&eacute;dito.<br />\r\nv.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; O cliente ter&aacute; tamb&eacute;m de ter em aten&ccedil;&atilde;o se o produto que pretende devolver foi adquirido no &acirc;mbito de alguma campanha promocional e se, por esse motivo, beneficiou de algum produto de oferta. Se tiver sido o caso, dever&aacute; devolver n&atilde;o s&oacute; o produto em quest&atilde;o, mas tamb&eacute;m a oferta que lhe foi atribu&iacute;da com a sua compra. Ambos os produtos dever&atilde;o cumprir as diretrizes anteriormente mencionadas.<br />\r\nvi.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Apenas &eacute; poss&iacute;vel a devolu&ccedil;&atilde;o de um pack na sua totalidade. Caso o cliente pretenda devolver um produto que perten&ccedil;a a um pack, dever&aacute; fazer a devolu&ccedil;&atilde;o de todos os produtos que constituem esse mesmo pack. Todos os produtos dever&atilde;o cumprir as diretrizes anteriormente referidas.<br />\r\nvii.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; O cliente deve enviar-nos o(s) artigo(s) por correio registado para a seguinte morada:<br />\r\nNatura online<br />\r\nNat&aacute;lia &amp; Nuno Lda<br />\r\nRua Dr. Cassiano Baptista, Fra&ccedil;&atilde;o N, n&ordm; 28<br />\r\n4990-144 Ponte de Lima<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\nA devolu&ccedil;&atilde;o de produtos incompletos, danificados ou riscados, assim como de produtos sem a embalagem e/ou etiqueta original, ou produtos com sinais de terem sido utilizados, n&atilde;o ser&aacute; aceite.<br />\r\nTamb&eacute;m n&atilde;o ser&aacute; aceite a devolu&ccedil;&atilde;o de produtos que tenham uma data de validade inferior a um m&ecirc;s, nem ofertas ou produtos trocados.<br />\r\nSe recebermos um artigo nas condi&ccedil;&otilde;es acima mencionadas ou com a etiqueta do transit&aacute;rio colada diretamente na embalagem, o cliente perder&aacute; o direito a ser reembolsado e ter&aacute; 10 dias para recolher o(s) artigo(s) nas nossas instala&ccedil;&otilde;es, suportando os custos de recolha.<br />\r\n&nbsp;<br />\r\n<strong>TROCAS</strong><br />\r\nO cliente dever&aacute; proceder &agrave; devolu&ccedil;&atilde;o do(s) artigo(s), solicitar o respetivo reembolso e efetuar uma nova compra.<br />\r\nTodo o processo de devolu&ccedil;&atilde;o est&aacute; devidamente descrito no item DEVOLU&Ccedil;&Otilde;ES.<br />\r\nM&Eacute;TODOS DE REEMBOLSO<br />\r\nA NATURA ONLINE far&aacute; o poss&iacute;vel para enviar nota de cr&eacute;dito ao cliente rapidamente, salvaguardando, no entanto, um prazo m&aacute;ximo de 15 dias a contar da rece&ccedil;&atilde;o da devolu&ccedil;&atilde;o nos nossos armaz&eacute;ns.<br />\r\n&nbsp;<br />\r\n<strong>REEMBOLSO DOS GASTOS DE ENVIO</strong><br />\r\nCaso o motivo da devolu&ccedil;&atilde;o da encomenda tenha sido da responsabilidade da NATURA ONLNE, reembolsaremos os respetivos gastos de envio (exemplo: produtos com defeito ou diferentes dos artigos efetivamente encomendados).<br />\r\nContudo, se, numa encomenda de m&uacute;ltiplos produtos, apenas um tiver apresentado algum problema, n&atilde;o procederemos ao reembolso dos gastos de envio. Se a devolu&ccedil;&atilde;o do produto for igual ao valor total da encomenda (o cliente no ato da compra beneficiou de portes gratuitos), a NATURA ONLINE apenas reembolsar&aacute; o valor do produto subtraindo o valor dos portes que foram imputados &agrave; empresa nesse envio.<br />\r\nSe a devolu&ccedil;&atilde;o for da responsabilidade do cliente, a NATURA ONLINE emite nota de cr&eacute;dito, para o cliente usar numa compra posterior, apenas quando o produto for recebido em perfeitas condi&ccedil;&otilde;es (sem que tenha sido aberto e/ou danificado).<br />\r\nPara obter informa&ccedil;&otilde;es adicionais sobre o processo de devolu&ccedil;&atilde;o e os tempos de reembolso consulte a se&ccedil;&atilde;o de Ajuda/FAQ&#39;s do site. Por favor, leia as instru&ccedil;&otilde;es de ajuda nessa sec&ccedil;&atilde;o e siga as instru&ccedil;&otilde;es cuidadosamente para evitar atrasos desnecess&aacute;rios.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>11. Apoio ao cliente</strong><br />\r\n11.1 Para informa&ccedil;&otilde;es adicionais, pode contactar a Natura Online via e-mail para mithilchauhan@gmail.com ou pode igualmente faz&ecirc;-lo por telefone para o seguinte n&uacute;mero +351258749154. Pode tamb&eacute;m obter informa&ccedil;&otilde;es no menu Ajuda/FAQ&#39;s do site.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>12. Condi&ccedil;&otilde;es aplic&aacute;veis aos C&oacute;digos Promocionais (descontos)</strong><br />\r\n12.1 Quando utilizado de acordo com as regras estabelecidas, um c&oacute;digo de promo&ccedil;&atilde;o habilita o utilizador a usufruir de uma promo&ccedil;&atilde;o/desconto relativo &agrave; compra de um produto espec&iacute;fico ou para um conjunto de produtos, durante o per&iacute;odo indicado/associado ao c&oacute;digo promocional/ campanha promocional.<br />\r\n&nbsp;<br />\r\n12.2 Os c&oacute;digos promocionais s&oacute; podem ser utilizados em compras on-line efetuadas no site. N&atilde;o &eacute; permitida a venda, a troca ou a doa&ccedil;&atilde;o de um c&oacute;digo promocional. O cliente portador do c&oacute;digo promocional n&atilde;o pode carregar ou disponibilizar um c&oacute;digo promocional em qualquer website ou outra forma p&uacute;blica de oferta, doa&ccedil;&atilde;o, venda ou troca. N&atilde;o &eacute; permitida a utiliza&ccedil;&atilde;o de c&oacute;digos promocionais para fins comerciais.<br />\r\n&nbsp;<br />\r\n12.3 O c&oacute;digo de promo&ccedil;&atilde;o est&aacute; conectado com a marca Natura Online. N&atilde;o &eacute; permitida a utiliza&ccedil;&atilde;o do c&oacute;digo promocional em conex&atilde;o com outra marca, empresa ou nome. N&atilde;o &eacute; igualmente permitida a utiliza&ccedil;&atilde;o do c&oacute;digo promocional de forma ilegal, abusiva ou desrespeitosa, bem como em qualquer outra forma que possa prejudicar a reputa&ccedil;&atilde;o da Natura Online.<br />\r\n&nbsp;<br />\r\n12.4 A utiliza&ccedil;&atilde;o do c&oacute;digo promocional est&aacute; condicionada apenas a uma utiliza&ccedil;&atilde;o por produto e por encomenda. Os c&oacute;digos promocionais n&atilde;o s&atilde;o cumul&aacute;veis com quaisquer outras promo&ccedil;&otilde;es ou ofertas especiais.<br />\r\n&nbsp;<br />\r\n12.5 Os c&oacute;digos promocionais n&atilde;o s&atilde;o troc&aacute;veis/cambi&aacute;veis por dinheiro.<br />\r\n&nbsp;<br />\r\n12.6 A Natura Online n&atilde;o tem qualquer obriga&ccedil;&atilde;o para reeditar ou restituir c&oacute;digos promocionais. Contudo, no caso de a Natura Online decidir reeditar ou reembolsar um c&oacute;digo promocional, necessita de ter acesso ao c&oacute;digo promocional inicial. Para tal, &eacute; necess&aacute;rio que guarde uma c&oacute;pia do seu c&oacute;digo promocional, pois caso contr&aacute;rio ele n&atilde;o poder&aacute; ser reeditado ou substitu&iacute;do em caso de perda.<br />\r\n&nbsp;<br />\r\n12.7 Nos casos em que a Natura Online reedita/reembolsa o c&oacute;digo promocional, o desconto desse valor s&oacute; poder&aacute; ser aplicado numa compra de valor igual ou superior &agrave; compra em que o desconto/c&oacute;digo promocional foi emitido.<br />\r\n&nbsp;<br />\r\n12.8 Sem preju&iacute;zo de quaisquer outros direitos, a Natura Online pode ter/tem o direito de invalidar de forma imediata o c&oacute;digo promocional, caso a Natura Online tenha motivos para suspeitar que um c&oacute;digo promocional est&aacute; a ser usado: com viola&ccedil;&atilde;o dos pressupostos acima mencionados, por usurpa&ccedil;&atilde;o ou em outros casos em que a Natura Online tenha raz&otilde;es para proceder dessa forma.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>13. Disponibilidade</strong><br />\r\nA satisfa&ccedil;&atilde;o de todas as encomendas realizadas no site est&aacute; sujeita &agrave; disponibilidade. A Natura Online tenta assegurar que todos os itens colocados &agrave; venda no site est&atilde;o em stock. Contudo, a Natura Online reserva-se o direito de n&atilde;o aceitar quaisquer encomendas ou cancelar encomendas j&aacute; confirmadas para produtos que j&aacute; n&atilde;o tenham stock. Nestes casos a Natura Online informar&aacute; o cliente de tal facto e proceder&aacute; ao reembolso do valor pago.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>14. Validade da promo&ccedil;&atilde;o</strong><br />\r\nAs ofertas do site s&atilde;o v&aacute;lidas apenas para o momento em que s&atilde;o exibidas no site, a menos que seja comunicada no site informa&ccedil;&atilde;o em contr&aacute;rio. A Natura Online n&atilde;o est&aacute; vinculada a qualquer oferta, caso se registem erros ou equ&iacute;vocos de ortografia, pre&ccedil;os ou outros na informa&ccedil;&atilde;o existente no site. A Natura Online reserva-se ao direito de cancelar qualquer compra e venda celebrada ao abrigo de tais erros.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>15. Responsabilidade</strong><br />\r\n15.1 A Natura Online s&oacute; ser&aacute; respons&aacute;vel por danos sofridos pelo cliente no caso de tais danos resultarem de viola&ccedil;&otilde;es imput&aacute;veis &agrave;s obriga&ccedil;&otilde;es contratuais a Natura Online perante o cliente, ou no caso da responsabilidade da Natura Online resultar do direito legal aplic&aacute;vel.<br />\r\n&nbsp;<br />\r\n15.2 Caso o cliente tenha sofrido danos relacionados com a atividade do site, a responsabilidade da Natura Online ser&aacute; limitada a:<br />\r\n&nbsp;<br />\r\nDanos nos produtos Natura Online ou outros materiais;<br />\r\n&nbsp;<br />\r\nCustos razo&aacute;veis e comprov&aacute;veis incorridos pelo cliente para encontrar a causa e o montante da indemniza&ccedil;&atilde;o imput&aacute;veis ao dano;<br />\r\n&nbsp;<br />\r\nO reembolso m&aacute;ximo da indemniza&ccedil;&atilde;o acima mencionada ser&aacute; (se aplic&aacute;vel) o pre&ccedil;o dos produtos em causa.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n15.3 A Natura Online n&atilde;o poder&aacute; ser responsabilizada pelos danos sofridos por terceiros resultantes do uso de qualquer dos nossos produtos. A Natura Online n&atilde;o poder&aacute; ser responsabilizada pelos danos sofridos pelo cliente como consequ&ecirc;ncia de uma utiliza&ccedil;&atilde;o indevida dos nossos produtos.<br />\r\n&nbsp;<br />\r\n15.4 A Natura Online n&atilde;o se responsabiliza por danos que sejam resultado de informa&ccedil;&atilde;o incorreta no site.<br />\r\n&nbsp;<br />\r\n15.5 No caso de o cliente sofrer danos que sejam resultado de neglig&ecirc;ncia grave ou dolo por parte da Natura Online n&atilde;o se aplica nenhuma das limita&ccedil;&otilde;es constantes neste artigo.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n<strong>16. Lei aplic&aacute;vel</strong><br />\r\nAs presentes condi&ccedil;&otilde;es gerais e todos os lit&iacute;gios emergentes que estejam relacionados com estas condi&ccedil;&otilde;es gerais, incluindo a sua validade, o uso do site ou qualquer compra no mesmo devem ser regidos pelo Direito Portugu&ecirc;s.<br />\r\n&nbsp;<br />\r\n<strong>17. Altera&ccedil;&atilde;o dos Termos &amp; Condi&ccedil;&otilde;es</strong><br />\r\nA Natura Online tem o direito de alterar os presentes Termos &amp; Condi&ccedil;&otilde;es em qualquer altura. O cliente est&aacute; sujeito aos princ&iacute;pios e termos em vigor &agrave; data da sua encomenda, salvo se a lei ou autoridade competente impuserem qualquer altera&ccedil;&atilde;o aos mesmos.<br />\r\n&nbsp;<br />\r\n<strong>18. Informa&ccedil;&otilde;es no site</strong><br />\r\nApesar de a Natura Online trabalhar o seu site com todo o cuidado, as informa&ccedil;&otilde;es, textos, documentos gr&aacute;ficos, filmes, m&uacute;sica e/ou outros servi&ccedil;os podem conter erros ou estarem incompletos ou incorretos.<br />\r\n&nbsp;<br />\r\nA Natura Online n&atilde;o poder&aacute; ser responsabilizada por quaisquer danos resultantes do uso (ou incapacidade de uso) do site, incluindo danos causados por v&iacute;rus ou qualquer inexatid&atilde;o ou imperfei&ccedil;&atilde;o das informa&ccedil;&otilde;es, salvo se tal dano for resultado de dolo ou neglig&ecirc;ncia por parte da Natura Online.<br />\r\n&nbsp;<br />\r\n<strong>19. Marcas registadas e direitos autorais do site</strong><br />\r\nTodos os nomes das marcas, nomes de produtos e t&iacute;tulos usados no site s&atilde;o marcas registadas e nomes registados de outros detentores.<br />\r\n&nbsp;<br />\r\nO utilizador/cliente do site n&atilde;o est&aacute; autorizado para a utiliza&ccedil;&atilde;o ou reprodu&ccedil;&atilde;o de qualquer uma dessas marcas registadas ou nomes registados pois tal constitui uma viola&ccedil;&atilde;o dos direitos dos titulares.<br />\r\n&nbsp;<br />\r\nTudo o que diz respeito ao design, textos, documentos, filmes, m&uacute;sica e/ou outros servi&ccedil;os do site, a sele&ccedil;&atilde;o e organiza&ccedil;&atilde;o do mesmo, bem como toda a compila&ccedil;&atilde;o de software (incluindo applets) e todos os outros materiais do site s&atilde;o propriedade da Natura Online e seus fornecedores.<br />\r\n&nbsp;<br />\r\nOs utilizadores/clientes est&atilde;o apenas autorizados a copiar eletronicamente e imprimir por&ccedil;&otilde;es do site caso tal seja necess&aacute;rio para efetuar uma encomenda no mesmo ou para utilizar o site como uma fonte para a compra.<br />\r\n&nbsp;<br />\r\nO utilizador/cliente n&atilde;o est&aacute; autorizado a fazer qualquer outro uso do site ou das informa&ccedil;&otilde;es e materiais nele contido, incluindo reprodu&ccedil;&atilde;o para fins diferentes dos acima mencionados, modifica&ccedil;&atilde;o, distribui&ccedil;&atilde;o ou republica&ccedil;&atilde;o.<br />\r\n&nbsp;<br />\r\nCaso o utilizador/cliente pretenda utilizar informa&ccedil;&otilde;es ou materiais do site necessita de obter uma autoriza&ccedil;&atilde;o pr&eacute;via por escrito e concedida pela Natura Online.<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;',	'',	'',	'',	'',	NULL,	1,	0,	0,	0,	2,	NULL,	'https://www.netgocio.pt/pt/',	'_blank',	'botão teste',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(51,	11,	0,	1,	NULL,	NULL,	1,	1,	0,	1,	NULL,	1,	NULL,	'new new',	'new',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	7,	1,	0,	0,	0,	1,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	1,	99),
(52,	12,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'teste',	'Guia dos pais',	'',	'teste etbnnvjfnvkjnvk.xjnv.kcxfv',	'',	'',	'',	'',	NULL,	1,	0,	0,	0,	0,	NULL,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(53,	13,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Bloco Texto Imagem Video',	'Bloco Texto Imagem Video',	'',	'kj hasdlkjf hajlskdfh alsjhdflkj ahsdkjf hasjdf asdf a<br />\r\nsdf<br />\r\n&nbsp;as<br />\r\ndf<br />\r\nasd<br />\r\nfa<br />\r\ns',	'',	'',	'',	'',	NULL,	1,	0,	0,	0,	0,	NULL,	'http://www.google.com.br',	'_blank',	'Google!',	'',	'',	'',	'',	'',	'',	NULL,	1,	99),
(54,	13,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	1,	0,	'Texto',	'Texto',	'Titulo 1',	'cvbncvbncvbncvbncvbncvn dfgh&nbsp;<br />\r\nfdgh<br />\r\nd fg<br />\r\nhd f<br />\r\ngh<br />\r\nd f<br />\r\ngh dfgh',	'Titulo 2',	'd fgh dfghnsl dkfghadfh gsdfhgl skjdhfglkjsdhf jkgshdlkg',	'',	'',	NULL,	2,	0,	0,	NULL,	NULL,	2,	'www.google.com.br',	'_parent',	'botao 1',	'www.google.com.br',	'_blank',	'botao 2',	'',	'',	'',	NULL,	1,	99),
(55,	13,	0,	1,	NULL,	NULL,	1,	0,	NULL,	1,	NULL,	0,	NULL,	'2 Colunas com Imagem / Vídeo',	'2 Colunas com Imagem / Vídeo',	'Titulo 1',	'ajskdf hlakjsdhf lkjashdfjk haslkjdfha kjsdfhas<br />\r\ndfa&nbsp;<br />\r\nsdf<br />\r\na sd<br />\r\nf<br />\r\n&nbsp;',	'Titulo 2',	'&ccedil;lsdjfh asdfh alsjdhf ajhsdlfkj ahlskjdf hlakjsdhfasdf<br />\r\na sd<br />\r\nfa<br />\r\nsdf<br />\r\na sd<br />\r\nf',	'',	'',	NULL,	3,	0,	0,	NULL,	NULL,	2,	'',	'',	'',	'',	'',	'',	'',	'',	'',	NULL,	1,	99);

DROP TABLE IF EXISTS `paginas_blocos_timeline_en`;
CREATE TABLE `paginas_blocos_timeline_en` (
  `id` int(11) NOT NULL,
  `bloco` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `texto` text,
  `imagem1` varchar(255) DEFAULT NULL,
  `ano` varchar(255) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `paginas_blocos_timeline_pt`;
CREATE TABLE `paginas_blocos_timeline_pt` (
  `id` int(11) NOT NULL,
  `bloco` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `texto` text,
  `imagem1` varchar(255) DEFAULT NULL,
  `ano` varchar(255) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `paginas_en`;
CREATE TABLE `paginas_en` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `mostrar_menu` tinyint(4) DEFAULT '0',
  `mostrar_topo` tinyint(4) DEFAULT '1',
  `esp_blocos` int(11) DEFAULT '120',
  `esp_blocos_mob` int(11) DEFAULT '60',
  `mostra_titulo` tinyint(4) DEFAULT '1' COMMENT '1 - Mostra título no topo da página; 0 - não mostra',
  `cor_titulo` varchar(20) DEFAULT '#000000',
  `tem_fundo` tinyint(4) DEFAULT '1' COMMENT '1 - Possui cor ou imagem de fundo; 0 - não tem',
  `tipo_fundo` tinyint(4) DEFAULT '1' COMMENT '1 - Cor; 2 - Imagem',
  `cor_fundo` varchar(50) DEFAULT '#e2e2e2',
  `fixo` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`mostrar_topo`,`mostra_titulo`,`tem_fundo`,`tipo_fundo`,`fixo`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `paginas_en` (`id`, `menu`, `nome`, `titulo`, `imagem1`, `mostrar_menu`, `mostrar_topo`, `esp_blocos`, `esp_blocos_mob`, `mostra_titulo`, `cor_titulo`, `tem_fundo`, `tipo_fundo`, `cor_fundo`, `fixo`, `ordem`, `visivel`, `url`, `title`, `description`, `keywords`) VALUES
(6,	NULL,	'Termos e Condições',	'Termos e Condições',	NULL,	0,	1,	120,	60,	0,	'#000000',	1,	1,	'#E2E2E2',	1,	99,	1,	'termos-e-condicoes',	'Termos e Condições',	NULL,	NULL),
(7,	NULL,	'Métodos de Pagamento',	'Métodos de Pagamento',	NULL,	0,	1,	120,	60,	0,	'#000000',	1,	1,	'#E2E2E2',	1,	99,	1,	'metodos-de-pagamento',	'Métodos de Pagamento',	NULL,	NULL),
(8,	NULL,	'Refund Policy',	'Refund Policy',	NULL,	0,	1,	120,	60,	0,	'#2FAD49',	1,	1,	'#FCC91E',	1,	99,	1,	'entregas-e-devolucoes',	'Refund Policy',	NULL,	NULL),
(9,	NULL,	'Why Choose Us',	'Franchise Opportunities with Bismillah Bakery',	NULL,	0,	1,	120,	60,	1,	'#000000',	1,	1,	'#E2E2E2',	0,	99,	1,	'quem-somos',	'Why Choose Us',	NULL,	NULL),
(10,	NULL,	'Teste 1',	'teste 1',	NULL,	0,	1,	120,	60,	1,	'#000000',	1,	1,	'#e2e2e2',	1,	99,	1,	'teste-1',	'Teste 1',	NULL,	NULL),
(13,	NULL,	'What We Look For',	'What We Look For',	NULL,	0,	1,	120,	60,	1,	'#000000',	1,	1,	'#E2E2E2',	0,	99,	1,	'teste4',	'What We Look For',	NULL,	NULL);

DROP TABLE IF EXISTS `paginas_pt`;
CREATE TABLE `paginas_pt` (
  `id` int(11) NOT NULL,
  `menu` int(11) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `imagem1` varchar(255) DEFAULT NULL,
  `mostrar_menu` tinyint(4) DEFAULT '0',
  `mostrar_topo` tinyint(4) DEFAULT '1',
  `esp_blocos` int(11) DEFAULT '120',
  `esp_blocos_mob` int(11) DEFAULT '60',
  `mostra_titulo` tinyint(4) DEFAULT '1' COMMENT '1 - Mostra título no topo da página; 0 - não mostra',
  `cor_titulo` varchar(20) DEFAULT '#000000',
  `tem_fundo` tinyint(4) DEFAULT '1' COMMENT '1 - Possui cor ou imagem de fundo; 0 - não tem',
  `tipo_fundo` tinyint(4) DEFAULT '1' COMMENT '1 - Cor; 2 - Imagem',
  `cor_fundo` varchar(50) DEFAULT '#e2e2e2',
  `fixo` tinyint(4) DEFAULT '0',
  `ordem` int(11) DEFAULT '99',
  `visivel` tinyint(4) DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` text,
  PRIMARY KEY (`id`),
  KEY `indice` (`mostrar_topo`,`mostra_titulo`,`tem_fundo`,`tipo_fundo`,`fixo`,`ordem`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `paginas_pt` (`id`, `menu`, `nome`, `titulo`, `imagem1`, `mostrar_menu`, `mostrar_topo`, `esp_blocos`, `esp_blocos_mob`, `mostra_titulo`, `cor_titulo`, `tem_fundo`, `tipo_fundo`, `cor_fundo`, `fixo`, `ordem`, `visivel`, `url`, `title`, `description`, `keywords`) VALUES
(2,	NULL,	'Política de Cookies',	'Política de Cookies',	NULL,	0,	1,	120,	60,	0,	'#000000',	1,	1,	'#E2E2E2',	1,	99,	1,	'politica-cookies',	'Política de Cookies',	NULL,	NULL),
(3,	NULL,	'Política de Privacidade',	'Política de Privacidade',	NULL,	0,	1,	120,	60,	0,	'#FFFFFF',	1,	1,	'',	1,	99,	1,	'politica-de-privacidade',	'Política de Privacidade',	NULL,	NULL),
(4,	NULL,	'RAL e RLL',	'RAL e RLL',	NULL,	0,	1,	120,	60,	0,	'#000000',	1,	1,	'#E2E2E2',	1,	99,	1,	'ral-e-rll',	'RAL e RLL',	NULL,	NULL),
(6,	NULL,	'Termos e Condições',	'Termos e Condições',	NULL,	0,	1,	120,	60,	0,	'#000000',	1,	1,	'#E2E2E2',	1,	99,	1,	'termos-e-condicoes',	'Termos e Condições',	NULL,	NULL),
(7,	NULL,	'Métodos de Pagamento',	'Métodos de Pagamento',	NULL,	0,	1,	120,	60,	0,	'#000000',	1,	1,	'#E2E2E2',	1,	99,	1,	'metodos-de-pagamento',	'Métodos de Pagamento',	NULL,	NULL),
(8,	NULL,	'Entregas e Devoluções',	'Entregas e Devoluções',	'103403_1_4640_vinyl-pvc-banners-1.jpg',	0,	1,	120,	60,	0,	'#2FAD49',	1,	1,	'#FCC91E',	1,	99,	1,	'entregas-e-devolucoes',	'Entregas e Devoluções',	NULL,	NULL),
(9,	NULL,	'Quem Somos',	'Quem Somos',	NULL,	0,	1,	120,	60,	1,	'#000000',	1,	1,	'#E2E2E2',	0,	99,	1,	'quem-somos',	'Quem Somos',	NULL,	NULL),
(10,	NULL,	'Teste 1',	'teste 1',	NULL,	0,	1,	120,	60,	1,	'#000000',	1,	1,	'#e2e2e2',	1,	99,	1,	'teste-1',	'Teste 1',	NULL,	NULL),
(11,	NULL,	'testnew',	'testnew',	NULL,	0,	1,	120,	60,	1,	'#000000',	1,	1,	'#E2E2E2',	0,	99,	1,	'testnew',	'testnew',	NULL,	NULL),
(12,	NULL,	'teste3',	'teste3',	'033030_1_302_gd_produto_1.png',	0,	1,	120,	60,	0,	'#2FAD49',	1,	1,	'#FAF5E4',	0,	99,	1,	'teste3',	'teste3',	NULL,	NULL),
(13,	NULL,	'teste4',	'Teste 4',	NULL,	0,	1,	120,	60,	1,	'#000000',	1,	1,	'#e2e2e2',	0,	99,	1,	'teste4',	'teste4',	NULL,	NULL);

DROP TABLE IF EXISTS `paises`;
CREATE TABLE `paises` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `abreviatura` varchar(20) DEFAULT NULL,
  `codigo` varchar(5) DEFAULT NULL,
  `zona` int(11) DEFAULT '1',
  `idioma` int(11) DEFAULT '1',
  `moeda` int(11) DEFAULT '1',
  `visivel` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`abreviatura`,`codigo`,`zona`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `paises` (`id`, `nome`, `abreviatura`, `codigo`, `zona`, `idioma`, `moeda`, `visivel`) VALUES
(2,	'Belgium',	'BEL',	'BE',	4,	1,	1,	1),
(11,	'Afghanistan',	'AFG',	'AF',	1,	1,	1,	0),
(12,	'Akrotiri',	NULL,	NULL,	1,	1,	1,	0),
(13,	'Albania',	'ALB',	'AL',	1,	1,	1,	0),
(14,	'Algeria',	'DZA',	'DZ',	1,	1,	1,	0),
(15,	'American Samoa',	'ASM',	'AS',	1,	1,	1,	0),
(16,	'Andorra',	'AND',	'AD',	1,	1,	1,	0),
(17,	'Angola',	'AGO',	'AO',	1,	1,	1,	0),
(18,	'Anguilla',	'AIA',	'AI',	1,	1,	1,	0),
(19,	'Antarctica',	NULL,	'AQ',	1,	1,	1,	0),
(20,	'Antigua and Barbuda',	'ATG',	'AG',	1,	1,	1,	0),
(21,	'Argentina',	'ARG',	'AR',	1,	1,	1,	0),
(22,	'Armenia',	'ARM',	'AM',	1,	1,	1,	0),
(23,	'Aruba',	'ABW',	'AW',	1,	1,	1,	0),
(24,	'Ashmore and Cartier Islands',	NULL,	NULL,	1,	1,	1,	0),
(25,	'Australia',	'AUS',	'AU',	1,	1,	1,	0),
(26,	'Austria',	'AUT',	'AT',	4,	1,	1,	1),
(27,	'Azerbaijan',	'AZE',	'AZ',	1,	1,	1,	0),
(28,	'Bahamas, The',	'BHS',	'BS',	1,	1,	1,	0),
(29,	'Bahrain',	'BHR',	'BH',	1,	1,	1,	0),
(30,	'Bangladesh',	'BGD',	'BD',	1,	1,	1,	0),
(31,	'Barbados',	'BRB',	'BB',	1,	1,	1,	0),
(32,	'Bassas da India',	NULL,	NULL,	1,	1,	1,	0),
(33,	'Belarus',	'BLR',	'BY',	1,	1,	1,	0),
(34,	'Bolivia',	'BOL',	'BO',	1,	1,	1,	0),
(35,	'Belize',	'BLZ',	'BZ',	1,	1,	1,	0),
(36,	'Benin',	'BEN',	'BJ',	1,	1,	1,	0),
(37,	'Bermuda',	'BMU',	'BM',	1,	1,	1,	0),
(38,	'Bhutan',	'BTN',	'BT',	1,	1,	1,	0),
(39,	'Bosnia and Herzegovina',	'BIH',	'BA',	1,	1,	1,	0),
(40,	'Botswana',	'BWA',	'BW',	1,	1,	1,	0),
(41,	'Bouvet Island',	NULL,	'BV',	1,	1,	1,	0),
(42,	'Brazil',	'BRA',	'BR',	1,	1,	1,	0),
(43,	'British Indian Ocean Territory',	NULL,	'IO',	1,	1,	1,	0),
(44,	'British Virgin Islands',	NULL,	NULL,	1,	1,	1,	0),
(45,	'Brunei',	'BRN',	'BN',	1,	1,	1,	0),
(46,	'Bulgaria',	'BGR',	'BG',	1,	1,	1,	0),
(47,	'Burkina Faso',	'BFA',	'BF',	1,	1,	1,	0),
(48,	'Burma',	NULL,	NULL,	1,	1,	1,	0),
(49,	'Burundi',	'BDI',	'BI',	1,	1,	1,	0),
(50,	'Cambodia',	'KHM',	'KH',	1,	1,	1,	0),
(51,	'Cameroon',	'CMR',	'CM',	1,	1,	1,	0),
(52,	'Canada',	'CAN',	'CA',	1,	1,	1,	0),
(53,	'Cape Verde',	'CPV',	'CV',	1,	1,	1,	0),
(54,	'Cayman Islands',	'CYM',	'KY',	1,	1,	1,	0),
(55,	'Central African Republic',	'CAF',	'CF',	1,	1,	1,	0),
(56,	'Chad',	'TCD',	'TD',	1,	1,	1,	0),
(57,	'Chile',	'CHL',	'CL',	1,	1,	1,	0),
(58,	'China',	'CHN',	'CN',	1,	1,	1,	0),
(59,	'Christmas Island',	NULL,	'CX',	1,	1,	1,	0),
(60,	'Clipperton Island',	NULL,	NULL,	1,	1,	1,	0),
(61,	'Cocos (Keeling) Islands',	NULL,	'CC',	1,	1,	1,	0),
(62,	'Colombia',	'COL',	'CO',	1,	1,	1,	0),
(63,	'Comoros',	'COM',	'KM',	1,	1,	1,	0),
(64,	'Congo, Democratic Republic of the',	'COD',	'CD',	1,	1,	1,	0),
(65,	'Congo, Republic of the',	'COG',	'CG',	1,	1,	1,	0),
(66,	'Cook Islands',	'COK',	'CK',	1,	1,	1,	0),
(67,	'Coral Sea Islands',	NULL,	NULL,	1,	1,	1,	0),
(68,	'Costa Rica',	'CRI',	'CR',	1,	1,	1,	0),
(69,	'Cote d\'Ivoire',	'CIV',	'CI',	1,	1,	1,	0),
(70,	'Croatia',	'HRV',	'HR',	1,	1,	1,	0),
(71,	'Cuba',	'CUB',	'CU',	1,	1,	1,	0),
(72,	'Cyprus',	'CYP',	'CY',	1,	1,	1,	0),
(73,	'Czech Republic',	'CZE',	'CZ',	4,	1,	1,	1),
(74,	'Denmark',	'DNK',	'DK',	5,	1,	1,	1),
(75,	'Dhekelia',	NULL,	NULL,	1,	1,	1,	0),
(76,	'Djibouti',	'DJI',	'DJ',	1,	1,	1,	0),
(77,	'Dominica',	'DMA',	'DM',	1,	1,	1,	0),
(78,	'Dominican Republic',	'DOM',	'DO',	1,	1,	1,	0),
(79,	'Ecuador',	'ECU',	'EC',	1,	1,	1,	0),
(80,	'Egypt',	'EGY',	'EG',	1,	1,	1,	0),
(81,	'El Salvador',	'SLV',	'SV',	1,	1,	1,	0),
(82,	'Equatorial Guinea',	'GNQ',	'GQ',	1,	1,	1,	0),
(83,	'Eritrea',	'ERI',	'ER',	1,	1,	1,	0),
(84,	'Estonia',	'EST',	'EE',	5,	1,	1,	1),
(85,	'Ethiopia',	'ETH',	'ET',	1,	1,	1,	0),
(86,	'Europa Island',	NULL,	NULL,	1,	1,	1,	0),
(87,	'Falkland Islands (Islas Malvinas)',	NULL,	NULL,	1,	1,	1,	0),
(88,	'Faroe Islands',	'FRO',	'FO',	1,	1,	1,	0),
(89,	'Fiji\r\n',	'FJI',	'FJ',	1,	1,	1,	0),
(90,	'Finland',	'FIN',	'FI',	5,	1,	1,	1),
(91,	'France',	'FRA',	'FR',	4,	1,	1,	1),
(92,	'French Guiana',	'GUF',	'GF',	1,	1,	1,	0),
(93,	'French Polynesia',	'PYF',	'PF',	1,	1,	1,	0),
(94,	'French Southern and Antarctic Lands',	NULL,	'TF',	1,	1,	1,	0),
(95,	'Gabon',	'GAB',	'GA',	1,	1,	1,	0),
(96,	'Gambia, The',	'GMB',	'GM',	1,	1,	1,	0),
(97,	'Gaza Strip',	NULL,	NULL,	1,	1,	1,	0),
(98,	'Georgia',	'GEO',	'GE',	1,	1,	1,	0),
(99,	'Germany',	'DEU',	'DE',	4,	1,	1,	1),
(100,	'Ghana',	'GHA',	'GH',	1,	1,	1,	0),
(101,	'Gibraltar',	'GIB',	'GI',	1,	1,	1,	0),
(102,	'Glorioso Islands',	NULL,	NULL,	1,	1,	1,	0),
(103,	'Greece',	'GRC',	'GR',	4,	1,	1,	1),
(105,	'Grenada',	'GRD',	'GD',	1,	1,	1,	0),
(106,	'Guadeloupe',	'GLP',	'GP',	1,	1,	1,	0),
(107,	'Guam',	'GUM',	'GU',	1,	1,	1,	0),
(108,	'Guatemala',	'GTM',	'GT',	1,	1,	1,	0),
(109,	'Guernsey',	NULL,	NULL,	1,	1,	1,	0),
(110,	'Guinea',	'GIN',	'GN',	1,	1,	1,	0),
(111,	'Guinea-Bissau',	'GNB',	'GW',	1,	1,	1,	0),
(112,	'Guyana',	'GUY',	'GY',	1,	1,	1,	0),
(113,	'Haiti',	'HTI',	'HT',	1,	1,	1,	0),
(114,	'Heard Island and Mcdonald Islands',	NULL,	'HM',	1,	1,	1,	0),
(115,	'Holy See (Vatican City State)',	'VAT',	'VA',	1,	1,	1,	0),
(116,	'Honduras',	'HND',	'HN',	1,	1,	1,	0),
(117,	'Hong Kong',	'HKG',	'HK',	1,	1,	1,	0),
(118,	'Hungary',	'HUN',	'HU',	4,	1,	1,	1),
(119,	'Iceland',	'ISL',	'IS',	5,	1,	1,	1),
(120,	'India',	'IND',	'IN',	1,	1,	1,	0),
(121,	'Indonesia',	'IDN',	'ID',	1,	1,	1,	0),
(122,	'Iran, Islamic Republic of',	'IRN',	'IR',	1,	1,	1,	0),
(123,	'Iraq',	'IRQ',	'IQ',	1,	1,	1,	0),
(124,	'Ireland',	'IRL',	'IE',	4,	1,	1,	1),
(125,	'Isle of Man',	NULL,	NULL,	1,	1,	1,	0),
(126,	'Israel',	'ISR',	'IL',	1,	1,	1,	0),
(127,	'Italy',	'ITA',	'IT',	4,	1,	1,	1),
(128,	'Jamaica',	'JAM',	'JM',	1,	1,	1,	0),
(129,	'Jan Mayen',	NULL,	NULL,	1,	1,	1,	0),
(130,	'Japan',	'JPN',	'JP',	1,	1,	1,	0),
(131,	'Jersey',	NULL,	NULL,	1,	1,	1,	0),
(132,	'Jordan',	'JOR',	'JO',	1,	1,	1,	0),
(133,	'Juan de Nova Island',	NULL,	NULL,	1,	1,	1,	0),
(134,	'Kazakhstan',	'KAZ',	'KZ',	1,	1,	1,	0),
(135,	'Kenya',	'KEN',	'KE',	1,	1,	1,	0),
(136,	'Kiribati',	'KIR',	'KI',	1,	1,	1,	0),
(137,	'Korea, North',	'PRK',	'KP',	1,	1,	1,	0),
(138,	'Korea, South',	'KOR',	'KR',	1,	1,	1,	0),
(139,	'Kuwait',	'KWT',	'KW',	1,	1,	1,	0),
(140,	'Kyrgyzstan',	'KGZ',	'KG',	1,	1,	1,	0),
(141,	'Laos',	'LAO',	'LA',	1,	1,	1,	0),
(142,	'Latvia',	'LVA',	'LV',	5,	1,	1,	1),
(143,	'Lebanon',	'LBN',	'LB',	1,	1,	1,	0),
(144,	'Lesotho',	'LSO',	'LS',	1,	1,	1,	0),
(145,	'Liberia',	'LBR',	'LR',	1,	1,	1,	0),
(146,	'Libya',	'LBY',	'LY',	1,	1,	1,	0),
(147,	'Liechtenstein',	'LIE',	'LI',	1,	1,	1,	0),
(148,	'Lithuania',	'LTU',	'LT',	5,	1,	1,	1),
(149,	'Luxembourg',	'LUX',	'LU',	4,	1,	1,	1),
(150,	'Macao',	'MAC',	'MO',	1,	1,	1,	0),
(151,	'Macedonia',	'MKD',	'MK',	1,	1,	1,	0),
(152,	'Madagascar',	'MDG',	'MG',	1,	1,	1,	0),
(153,	'Malawi',	'MWI',	'MW',	1,	1,	1,	0),
(154,	'Malaysia',	'MYS',	'MY',	1,	1,	1,	0),
(155,	'Maldives',	'MDV',	'MV',	1,	1,	1,	0),
(156,	'Mali',	'MLI',	'ML',	1,	1,	1,	0),
(157,	'Malta',	'MLT',	'MT',	5,	1,	1,	1),
(158,	'Marshall Islands',	'MHL',	'MH',	1,	1,	1,	0),
(159,	'Martinique',	'MTQ',	'MQ',	1,	1,	1,	0),
(160,	'Mauritania',	'MRT',	'MR',	1,	1,	1,	0),
(161,	'Mauritius',	'MUS',	'MU',	1,	1,	1,	0),
(162,	'Mayotte',	NULL,	'YT',	1,	1,	1,	0),
(163,	'Mexico',	'MEX',	'MX',	1,	1,	1,	0),
(164,	'Micronesia, Federated States of',	'FSM',	'FM',	1,	1,	1,	0),
(165,	'Moldova',	'MDA',	'MD',	1,	1,	1,	0),
(166,	'Monaco',	'MCO',	'MC',	1,	1,	1,	0),
(167,	'Mongolia',	'MNG',	'MN',	1,	1,	1,	0),
(168,	'Montserrat',	'MSR',	'MS',	1,	1,	1,	0),
(169,	'Morocco',	'MAR',	'MA',	1,	1,	1,	0),
(170,	'Mozambique',	'MOZ',	'MZ',	1,	1,	1,	0),
(171,	'Namibia',	'NAM',	'NA',	1,	1,	1,	0),
(172,	'Nauru',	'NRU',	'NR',	1,	1,	1,	0),
(173,	'Navassa Island',	NULL,	NULL,	1,	1,	1,	0),
(174,	'Nepal',	'NPL',	'NP',	1,	1,	1,	0),
(175,	'Netherlands',	'NLD',	'NL',	4,	1,	1,	1),
(176,	'Netherlands Antilles',	'ANT',	'AN',	1,	1,	1,	0),
(177,	'New Caledonia',	'NCL',	'NC',	1,	1,	1,	0),
(178,	'New Zealand',	'NZL',	'NZ',	1,	1,	1,	0),
(179,	'Nicaragua',	'NIC',	'NI',	1,	1,	1,	0),
(180,	'Niger',	'NER',	'NE',	1,	1,	1,	0),
(181,	'Nigeria',	'NGA',	'NG',	1,	1,	1,	0),
(182,	'Niue',	'NIU',	'NU',	1,	1,	1,	0),
(183,	'Norfolk Island',	'NFK',	'NF',	1,	1,	1,	0),
(184,	'Northern Mariana Islands',	'MNP',	'MP',	1,	1,	1,	0),
(185,	'Norway',	'NOR',	'NO',	5,	1,	1,	1),
(186,	'Oman',	'OMN',	'OM',	1,	1,	1,	0),
(187,	'Pakistan',	'PAK',	'PK',	1,	1,	1,	0),
(188,	'Palau',	'PLW',	'PW',	1,	1,	1,	0),
(189,	'Panama',	'PAN',	'PA',	1,	1,	1,	0),
(190,	'Papua New Guinea',	'PNG',	'PG',	1,	1,	1,	0),
(191,	'Paracel Islands',	NULL,	NULL,	1,	1,	1,	0),
(192,	'Paraguay',	'PRY',	'PY',	1,	1,	1,	0),
(193,	'Peru',	'PER',	'PE',	1,	1,	1,	0),
(194,	'Philippines',	'PHL',	'PH',	1,	1,	1,	0),
(195,	'Pitcairn Islands',	'PCN',	'PN',	1,	1,	1,	0),
(196,	'Poland',	'POL',	'PL',	5,	1,	1,	1),
(197,	'Portugal Continental',	'PRT',	'PT',	1,	1,	1,	1),
(198,	'Puerto Rico',	'PRI',	'PR',	1,	1,	1,	0),
(199,	'Qatar',	'QAT',	'QA',	1,	1,	1,	0),
(200,	'Reunion',	'REU',	'RE',	1,	1,	1,	0),
(201,	'Romania',	'ROM',	'RO',	1,	1,	1,	0),
(202,	'Russia',	'RUS',	'RU',	1,	1,	1,	0),
(203,	'Rwanda',	'RWA',	'RW',	1,	1,	1,	0),
(204,	'Saint Helena',	'SHN',	'SH',	1,	1,	1,	0),
(205,	'Saint Kitts and Nevis',	'KNA',	'KN',	1,	1,	1,	0),
(206,	'Saint Lucia',	'LCA',	'LC',	1,	1,	1,	0),
(207,	'Saint Pierre and Miquelon',	'SPM',	'PM',	1,	1,	1,	0),
(208,	'Saint Vincent and the Grenadines',	'VCT',	'VC',	1,	1,	1,	0),
(209,	'Samoa',	'WSM',	'WS',	1,	1,	1,	0),
(210,	'San Marino',	'SMR',	'SM',	1,	1,	1,	0),
(211,	'Sao Tome and Principe',	'STP',	'ST',	1,	1,	1,	0),
(212,	'Saudi Arabia',	'SAU',	'SA',	1,	1,	1,	0),
(213,	'Senegal',	'SEN',	'SN',	1,	1,	1,	0),
(214,	'Serbia and Montenegro',	NULL,	'RS',	1,	1,	1,	0),
(215,	'Seychelles',	'SYC',	'SC',	1,	1,	1,	0),
(216,	'Sierra Leone',	'SLE',	'SL',	1,	1,	1,	0),
(217,	'Singapore',	'SGP',	'SG',	1,	1,	1,	0),
(218,	'Slovakia',	'SVK',	'SK',	4,	1,	1,	1),
(219,	'Slovenia',	'SVN',	'SI',	4,	1,	1,	1),
(220,	'Solomon Islands',	'SLB',	'SB',	1,	1,	1,	0),
(221,	'Somalia',	'SOM',	'SO',	1,	1,	1,	0),
(222,	'South Africa',	'ZAF',	'ZA',	1,	1,	1,	0),
(223,	'South Georgia and the South Sandwich Islands',	NULL,	'GS',	1,	1,	1,	0),
(224,	'España Peninsular',	'ESP',	'ES',	6,	1,	1,	1),
(225,	'Spratly Islands',	NULL,	NULL,	1,	1,	1,	0),
(226,	'Sri Lanka',	'LKA',	'LK',	1,	1,	1,	0),
(227,	'Sudan',	'SDN',	'SD',	1,	1,	1,	0),
(228,	'Suriname',	'SUR',	'SR',	1,	1,	1,	0),
(229,	'Svalbard',	NULL,	NULL,	1,	1,	1,	0),
(230,	'Swaziland',	'SWZ',	'SZ',	1,	1,	1,	0),
(231,	'Sweden',	'SWE',	'SE',	5,	1,	1,	1),
(232,	'Switzerland',	'CHE',	'CH',	4,	1,	1,	1),
(233,	'Syria',	'SYR',	'SY',	1,	1,	1,	0),
(234,	'Taiwan',	'TWN',	'TW',	1,	1,	1,	0),
(235,	'Tajikistan',	'TJK',	'TJ',	1,	1,	1,	0),
(236,	'Tanzania',	'TZA',	'TZ',	1,	1,	1,	0),
(237,	'Thailand',	'THA',	'TH',	1,	1,	1,	0),
(238,	'Timor-Leste',	NULL,	'TL',	1,	1,	1,	0),
(239,	'Togo',	'TGO',	'TG',	1,	1,	1,	0),
(240,	'Tokelau',	'TKL',	'TK',	1,	1,	1,	0),
(241,	'Tonga',	'TON',	'TO',	1,	1,	1,	0),
(242,	'Trinidad and Tobago',	'TTO',	'TT',	1,	1,	1,	0),
(243,	'Tromelin Island',	NULL,	NULL,	1,	1,	1,	0),
(244,	'Tunisia',	'TUN',	'TN',	1,	1,	1,	0),
(245,	'Turkey',	'TUR',	'TR',	1,	1,	1,	0),
(246,	'Turkmenistan',	'TKM',	'TM',	1,	1,	1,	0),
(247,	'Turks and Caicos Islands',	'TCA',	'TC',	1,	1,	1,	0),
(248,	'Tuvalu',	'TUV',	'TV',	1,	1,	1,	0),
(249,	'Uganda',	'UGA',	'UG',	1,	1,	1,	0),
(250,	'Ukraine',	'UKR',	'UA',	1,	1,	1,	0),
(251,	'United Arab Emirates',	'ARE',	'AE',	1,	1,	1,	0),
(252,	'United Kingdom',	'GBR',	'GB',	4,	1,	1,	1),
(253,	'United States',	'USA',	'US',	1,	1,	1,	0),
(254,	'Uruguay',	'URY',	'UY',	1,	1,	1,	0),
(255,	'Uzbekistan',	'UZB',	'UZ',	1,	1,	1,	0),
(256,	'Vanuatu',	'VUT',	'VU',	1,	1,	1,	0),
(257,	'Venezuela',	'VEN',	'VE',	1,	1,	1,	0),
(258,	'Vietnam',	'VNM',	'VN',	1,	1,	1,	0),
(259,	'Virgin Islands, British',	'VGB',	'VG',	1,	1,	1,	0),
(260,	'Wake Island',	NULL,	NULL,	1,	1,	1,	0),
(261,	'Wallis and Futuna',	'WLF',	'WF',	1,	1,	1,	0),
(262,	'West Bank',	NULL,	NULL,	1,	1,	1,	0),
(263,	'Western Sahara',	'ESH',	'EH',	1,	1,	1,	0),
(264,	'Yemen',	'YEM',	'YE',	1,	1,	1,	0),
(265,	'Zambia',	'ZMB',	'ZM',	1,	1,	1,	0),
(266,	'Zimbabwe',	'ZWE',	'ZW',	1,	1,	1,	0),
(267,	'Portugal Ilhas',	'PRT',	'PT',	3,	1,	1,	1),
(268,	'España Baleares',	'ESP',	'ES',	1,	1,	1,	0),
(269,	'España Canarias',	'ESP',	'ES',	1,	1,	1,	0);

DROP TABLE IF EXISTS `pickup`;
CREATE TABLE `pickup` (
  `id` int(3) NOT NULL,
  `discount` int(3) DEFAULT NULL,
  `texto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pickup` (`id`, `discount`, `texto`) VALUES
(1,	50,	'discount appliesrss');

DROP TABLE IF EXISTS `portes_gratis`;
CREATE TABLE `portes_gratis` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `datai` datetime DEFAULT NULL,
  `dataf` datetime DEFAULT NULL,
  `min_encomenda` decimal(10,2) DEFAULT NULL,
  `peso_max` decimal(10,3) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`datai`,`dataf`,`visivel`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `portes_gratis` (`id`, `nome`, `datai`, `dataf`, `min_encomenda`, `peso_max`, `visivel`) VALUES
(1,	'nova campanha',	'2020-07-10 12:25:00',	'2021-01-12 12:30:00',	10.00,	0.000,	1);

DROP TABLE IF EXISTS `portes_gratis_categorias`;
CREATE TABLE `portes_gratis_categorias` (
  `portes_gratis` int(11) DEFAULT NULL,
  `categoria` int(11) DEFAULT NULL,
  KEY `indice` (`portes_gratis`,`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `portes_gratis_categorias` (`portes_gratis`, `categoria`) VALUES
(1,	1),
(1,	2),
(1,	3),
(1,	4),
(1,	5),
(1,	6),
(1,	7),
(1,	8),
(1,	9),
(1,	10),
(1,	11),
(1,	12),
(1,	13);

DROP TABLE IF EXISTS `portes_gratis_marcas`;
CREATE TABLE `portes_gratis_marcas` (
  `portes_gratis` int(11) DEFAULT NULL,
  `marca` int(11) DEFAULT NULL,
  KEY `indice` (`portes_gratis`,`marca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `portes_gratis_zonas`;
CREATE TABLE `portes_gratis_zonas` (
  `portes_gratis` int(11) DEFAULT NULL,
  `zona` int(11) DEFAULT NULL,
  KEY `indice` (`portes_gratis`,`zona`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `portes_gratis_zonas` (`portes_gratis`, `zona`) VALUES
(1,	1),
(1,	2),
(1,	3),
(1,	6),
(1,	7),
(1,	8),
(1,	9),
(1,	10),
(1,	11);

DROP TABLE IF EXISTS `product_role`;
CREATE TABLE `product_role` (
  `id` int(20) NOT NULL,
  `product_id` int(20) NOT NULL,
  `roleid` int(20) DEFAULT NULL,
  `regularprice` int(20) NOT NULL,
  `sellingprice` int(20) NOT NULL,
  `productqtn` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `redes_sociais`;
CREATE TABLE `redes_sociais` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `link` text,
  `mostra_topo` tinyint(4) DEFAULT '0',
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`visivel`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `redes_sociais` (`id`, `nome`, `link`, `mostra_topo`, `visivel`, `ordem`) VALUES
(1,	'Facebook',	'https://www.facebook.com',	0,	1,	10),
(2,	'Vimeo',	'',	0,	0,	40),
(3,	'Twitter',	'',	0,	0,	60),
(4,	'Pinterest',	'',	0,	0,	50),
(5,	'Instagram',	'https://www.instagram.com/',	0,	1,	20),
(6,	'Google',	'',	0,	0,	30),
(7,	'Blogger',	'',	0,	0,	70),
(8,	'Linkedin',	'',	0,	0,	80),
(9,	'Youtube',	'',	0,	0,	90);

DROP TABLE IF EXISTS `redirects_301`;
CREATE TABLE `redirects_301` (
  `id` int(11) NOT NULL,
  `url_old` varchar(500) NOT NULL,
  `url_new` varchar(500) NOT NULL,
  `lang` varchar(20) DEFAULT 'pt',
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `redirects_301` (`id`, `url_old`, `url_new`, `lang`, `data`) VALUES
(1,	'suplementos',	'suplementos-suplementacao',	'pt',	'2020-07-27 17:57:56'),
(2,	'suplementos-creme',	'suplementos-suplementacao-creme',	'pt',	'2020-07-27 17:57:56'),
(3,	'suplementos-suplementos-desportivos',	'suplementos-suplementacao-suplementos-suplementacao-desportivos',	'pt',	'2020-07-27 17:57:56'),
(4,	'suplementos-suplementos-alimentares',	'suplementos-suplementacao-suplementos-suplementacao-alimentares',	'pt',	'2020-07-27 17:57:56'),
(5,	'suplementos-suplementos-alimentares-neu-control-60-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-neu-control-60-capsulas',	'pt',	'2020-07-27 17:57:56'),
(6,	'suplementos-suplementos-alimentares-optimag-60-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-optimag-60-capsulas',	'pt',	'2020-07-27 17:57:56'),
(7,	'suplementos-suplementos-alimentares-oroseda-forte-60-caps.',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-oroseda-forte-60-caps.',	'pt',	'2020-07-27 17:57:56'),
(8,	'suplementos-suplementos-alimentares-h-dyn-500-ml',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-h-dyn-500-ml',	'pt',	'2020-07-27 17:57:56'),
(9,	'suplementos-suplementos-alimentares-h-dyn-30-comprimidos',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-h-dyn-30-comprimidos',	'pt',	'2020-07-27 17:57:56'),
(10,	'suplementos-suplementos-alimentares-artiprot-30-saquetas-30-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-artiprot-30-saquetas-30-capsulas',	'pt',	'2020-07-27 17:57:56'),
(11,	'suplementos-suplementos-alimentares-drenohepar-30-ampolas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-drenohepar-30-ampolas',	'pt',	'2020-07-27 17:57:56'),
(12,	'suplementos-suplementos-alimentares-alinalgesic-20-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-alinalgesic-20-capsulas',	'pt',	'2020-07-27 17:57:56'),
(13,	'suplementos-suplementos-alimentares-neorotrans-b-60-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-neorotrans-b-60-capsulas',	'pt',	'2020-07-27 17:57:57'),
(14,	'suplementos-suplementos-alimentares-neorotrans-a-60-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-neorotrans-a-60-capsulas',	'pt',	'2020-07-27 17:57:57'),
(15,	'suplementos-suplementos-alimentares-regen-dol-14-shots',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-regen-dol-14-shots',	'pt',	'2020-07-27 17:57:57'),
(16,	'suplementos-suplementos-alimentares-regen-dol-flexi-14-saquetas-',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-regen-dol-flexi-14-saquetas-',	'pt',	'2020-07-27 17:57:57'),
(17,	'suplementos-suplementos-alimentares-regen-dol-60-comprimidos',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-regen-dol-60-comprimidos',	'pt',	'2020-07-27 17:57:57'),
(18,	'suplementos-suplementos-alimentares-memochoc-forte-30-ampolas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-memochoc-forte-30-ampolas',	'pt',	'2020-07-27 17:57:57'),
(19,	'suplementos-suplementos-alimentares-memo-force-30-ampolas-30-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-memo-force-30-ampolas-30-capsulas',	'pt',	'2020-07-27 17:57:57'),
(20,	'suplementos-suplementos-alimentares-digestplex-gotas-75ml',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-digestplex-gotas-75ml',	'pt',	'2020-07-27 17:57:57'),
(21,	'suplementos-suplementos-alimentares-hepaplex-gotas-75ml',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-hepaplex-gotas-75ml',	'pt',	'2020-07-27 17:57:57'),
(22,	'suplementos-suplementos-alimentares-100-cerebro-para-mulher-30-ampolas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-100-cerebro-para-mulher-30-ampolas',	'pt',	'2020-07-27 17:57:57'),
(23,	'suplementos-suplementos-alimentares-creme-adelgacante-detoxlim-150ml',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-creme-adelgacante-detoxlim-150ml',	'pt',	'2020-07-27 17:57:57'),
(24,	'suplementos-suplementos-alimentares-slim-shaper-drena-sport-500ml',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-slim-shaper-drena-sport-500ml',	'pt',	'2020-07-27 17:57:57'),
(25,	'suplementos-suplementos-alimentares-slim-shaper-drena-boost-500ml',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-slim-shaper-drena-boost-500ml',	'pt',	'2020-07-27 17:57:57'),
(26,	'suplementos-suplementos-alimentares-slim-shaper-drena-pur-500ml',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-slim-shaper-drena-pur-500ml',	'pt',	'2020-07-27 17:57:57'),
(27,	'suplementos-suplementos-alimentares-biobalance-colestermin-30-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-biobalance-colestermin-30-capsulas',	'pt',	'2020-07-27 17:57:57'),
(28,	'suplementos-suplementos-alimentares-biobalance-colestermin-60-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-biobalance-colestermin-60-capsulas',	'pt',	'2020-07-27 17:57:57'),
(29,	'suplementos-suplementos-alimentares-nutricerebro-active-30-ampolas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-nutricerebro-active-30-ampolas',	'pt',	'2020-07-27 17:57:57'),
(30,	'suplementos-suplementos-alimentares-levezza-stop-gordura-50-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-levezza-stop-gordura-50-capsulas',	'pt',	'2020-07-27 17:57:57'),
(31,	'suplementos-suplementos-alimentares-levezza-corta-apetite-40-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-levezza-corta-apetite-40-capsulas',	'pt',	'2020-07-27 17:57:57'),
(32,	'suplementos-suplementos-alimentares-levezza-fast-burn-40-capsulas',	'suplementos-suplementacao-suplementos-suplementacao-alimentares-levezza-fast-burn-40-capsulas',	'pt',	'2020-07-27 17:57:57'),
(33,	'suplementos',	'suplementacao',	'en',	'2020-07-27 17:58:06'),
(34,	'desporto',	'desporto-desporto',	'pt',	'2020-07-29 17:48:09'),
(35,	'aromaterapia-physalis-sensual-bio',	'aromaterapia-sinergia-de-oleos-essenciais-bio-estimulante-sexual',	'pt',	'2020-08-05 09:32:05'),
(36,	'aromaterapia-physalis-sensual-bio',	'aromaterapia-sinergia-de-oleos-essenciais-bio-estimulante-sexual',	'en',	'2020-08-05 09:32:29'),
(37,	'higiene-e-cosmetica-higiene-bebe',	'maternidade-higiene-bebe',	'pt',	'2020-08-13 18:48:29'),
(38,	'higiene-e-cosmetica-higiene-bebe-oleo-de-calendula-200ml',	'maternidade-higiene-bebe-oleo-de-calendula-200ml',	'pt',	'2020-08-13 18:48:29'),
(39,	'higiene-e-cosmetica-higiene-bebe-champo-gel-duche-de-calendula-200ml',	'maternidade-higiene-bebe-champo-gel-duche-de-calendula-200ml',	'pt',	'2020-08-13 18:48:29'),
(40,	'higiene-e-cosmetica-higiene-bebe-creme-muda-de-fraldas-de-calendula-75ml',	'maternidade-higiene-bebe-creme-muda-de-fraldas-de-calendula-75ml',	'en',	'2020-08-13 18:48:29'),
(41,	'higiene-e-cosmetica-higiene-bebe',	'maternidade-higiene-bebe',	'en',	'2020-08-13 18:48:43'),
(42,	'higiene-e-cosmetica-higiene-bebe-oleo-de-calendula-200ml',	'maternidade-higiene-bebe-oleo-de-calendula-200ml',	'en',	'2020-08-13 18:48:43'),
(43,	'higiene-e-cosmetica-higiene-bebe-champo-gel-duche-de-calendula-200ml',	'maternidade-higiene-bebe-champo-gel-duche-de-calendula-200ml',	'en',	'2020-08-13 18:48:43'),
(44,	'higiene-e-cosmetica-higiene-bebe-creme-muda-de-fraldas-de-calendula-75ml',	'maternidade-higiene-bebe-creme-muda-de-fraldas-de-calendula-75ml',	'en',	'2020-08-13 18:48:43'),
(45,	'suplementacao-alimentar-pele-cabelo-e-unhas',	'suplementacao-alimentar-homeopaticos-pele-cabelo-e-unhas-267',	'pt',	'2020-08-13 19:25:47'),
(46,	'alimentacao-oleo-alimentar-biologico-oleo-primeira-pressao-a-frio-oleo-de-girassol-bio-garrafa-de 500-ml',	'oleo-de-girassol-biogarrafa-de 500-ml',	'pt',	'2020-09-10 16:49:24'),
(47,	'test-new',	'testnew',	'pt',	'2020-10-01 11:14:06'),
(48,	'higiene-e-cosmetica-pele-cabelo-e-unhas-power-shine-hair-skin-nails-60-comprimidos',	'power-shine-hair-skin-nails-60-comprimidos',	'pt',	'2020-10-06 21:55:24'),
(49,	'higiene-e-cosmetica-pele-cabelo-e-unhas-colastic-citric-fruits-hydrolized-collagen-20-saquetas',	'colastic-citric-fruits-hydrolized-collagen-20-saquetas',	'en',	'2020-10-06 21:57:03'),
(50,	'contactos.php',	'store-locater.php',	'en',	'2020-11-07 11:07:08');

DROP TABLE IF EXISTS `roll`;
CREATE TABLE `roll` (
  `id` int(13) NOT NULL,
  `roll_name` varchar(200) NOT NULL,
  `cod_price` int(5) NOT NULL,
  `ordem` varchar(15) NOT NULL,
  `visivel` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `roll` (`id`, `roll_name`, `cod_price`, `ordem`, `visivel`) VALUES
(7,	'franchise',	0,	'',	'1'),
(8,	'customer',	210,	'',	'1');

DROP TABLE IF EXISTS `share_logs`;
CREATE TABLE `share_logs` (
  `id` int(11) NOT NULL,
  `data_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `facebook` int(11) DEFAULT NULL,
  `twitter` int(11) DEFAULT NULL,
  `id_prod` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sitemap`;
CREATE TABLE `sitemap` (
  `id` int(11) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `links` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sitemap_estatico`;
CREATE TABLE `sitemap_estatico` (
  `id` int(11) NOT NULL,
  `url` text,
  `prioridade` decimal(5,1) DEFAULT '1.0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `sitemap_tabelas`;
CREATE TABLE `sitemap_tabelas` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `prefixo_url` varchar(250) DEFAULT NULL,
  `linguas` tinyint(4) DEFAULT '1',
  `imagem` varchar(250) DEFAULT NULL,
  `pasta` varchar(250) DEFAULT NULL,
  `prioridade` decimal(5,1) DEFAULT NULL,
  `blog` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `store_locater`;
CREATE TABLE `store_locater` (
  `id` int(10) NOT NULL,
  `b_name` varchar(255) DEFAULT NULL,
  `Sreet` text,
  `lat` varchar(50) NOT NULL,
  `lng` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(30) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `start_time` text,
  `close` text,
  `door` varchar(5) DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `store_locater` (`id`, `b_name`, `Sreet`, `lat`, `lng`, `city`, `country`, `pincode`, `phone`, `email`, `start_time`, `close`, `door`, `ordem`, `visivel`) VALUES
(2,	'WASHWOOD HEATH BRANCH',	'618 Washwood Heath Road Birmingham&nbsp;',	'52.49385359419111',	'-1.8337273644182142',	'Birmingham',	'United Kingdom',	'B8 2HB',	'01213267500',	'washwoodheath@bbakery.co.uk',	'a:21:{s:10:\"set_monday\";s:6:\"Monday\";s:6:\"monday\";s:5:\"08:58\";s:10:\"monday_end\";s:5:\"14:27\";s:11:\"set_tuesday\";s:7:\"Tuesday\";s:7:\"tuesday\";s:5:\"04:05\";s:11:\"tuesday_end\";s:5:\"21:13\";s:13:\"set_wednesday\";s:9:\"Wednesday\";s:9:\"wednesday\";s:5:\"10:03\";s:13:\"wednesday_end\";s:5:\"23:02\";s:12:\"set_thursday\";s:8:\"Thursday\";s:8:\"thursday\";s:5:\"01:11\";s:12:\"thursday_end\";s:5:\"21:13\";s:10:\"set_friday\";s:6:\"Friday\";s:6:\"friday\";s:5:\"12:10\";s:10:\"friday_end\";s:5:\"00:10\";s:12:\"set_saturday\";s:8:\"Saturday\";s:8:\"saturday\";s:5:\"04:07\";s:12:\"saturday_end\";s:5:\"04:11\";s:10:\"set_sunday\";s:6:\"Sunday\";s:6:\"sunday\";s:5:\"00:00\";s:10:\"sunday_end\";s:5:\"17:14\";}',	'a:7:{s:12:\"monday_close\";N;s:13:\"tuesday_close\";N;s:15:\"wednesday_close\";N;s:14:\"thursday_close\";N;s:12:\"friday_close\";N;s:14:\"saturday_close\";N;s:12:\"sunday_close\";s:1:\"1\";}',	'1',	NULL,	1),
(4,	'SPARK HILL BRANCH',	'1 Dora Road Birmingham',	'52.446594',	'-1.860262',	'Birmingham',	'United Kingdom',	'B11 4AE',	'01217782555',	'sparkhill@bbakery.co.uk',	'a:21:{s:10:\"set_monday\";s:6:\"Monday\";s:6:\"monday\";s:5:\"10:30\";s:10:\"monday_end\";s:5:\"19:30\";s:11:\"set_tuesday\";s:7:\"Tuesday\";s:7:\"tuesday\";s:5:\"10:30\";s:11:\"tuesday_end\";s:5:\"19:30\";s:13:\"set_wednesday\";s:9:\"Wednesday\";s:9:\"wednesday\";s:5:\"10:30\";s:13:\"wednesday_end\";s:5:\"19:30\";s:12:\"set_thursday\";s:8:\"Thursday\";s:8:\"thursday\";s:5:\"10:30\";s:12:\"thursday_end\";s:5:\"19:30\";s:10:\"set_friday\";s:6:\"Friday\";s:6:\"friday\";s:5:\"10:30\";s:10:\"friday_end\";s:5:\"19:30\";s:12:\"set_saturday\";s:8:\"Saturday\";s:8:\"saturday\";s:5:\"10:30\";s:12:\"saturday_end\";s:5:\"19:30\";s:10:\"set_sunday\";s:6:\"Sunday\";s:6:\"sunday\";s:0:\"\";s:10:\"sunday_end\";s:0:\"\";}',	'a:7:{s:12:\"monday_close\";N;s:13:\"tuesday_close\";N;s:15:\"wednesday_close\";N;s:14:\"thursday_close\";N;s:12:\"friday_close\";N;s:14:\"saturday_close\";N;s:12:\"sunday_close\";s:1:\"1\";}',	'1',	NULL,	1),
(6,	'SMALL HEATH BRANCH',	'1 Dora Road',	'52.467251',	'-1.850625',	'Birmingham',	'United Kingdom',	'B10 0SH',	'01217710000',	'smallheath@bbakery.co.uk',	'a:21:{s:10:\"set_monday\";s:6:\"Monday\";s:6:\"monday\";s:5:\"10:30\";s:10:\"monday_end\";s:5:\"19:30\";s:11:\"set_tuesday\";s:7:\"Tuesday\";s:7:\"tuesday\";s:5:\"10:30\";s:11:\"tuesday_end\";s:5:\"19:30\";s:13:\"set_wednesday\";s:9:\"Wednesday\";s:9:\"wednesday\";s:5:\"10:30\";s:13:\"wednesday_end\";s:5:\"19:30\";s:12:\"set_thursday\";s:8:\"Thursday\";s:8:\"thursday\";s:5:\"10:30\";s:12:\"thursday_end\";s:5:\"19:30\";s:10:\"set_friday\";s:6:\"Friday\";s:6:\"friday\";s:5:\"10:30\";s:10:\"friday_end\";s:5:\"19:30\";s:12:\"set_saturday\";s:8:\"Saturday\";s:8:\"saturday\";s:5:\"10:30\";s:12:\"saturday_end\";s:5:\"19:30\";s:10:\"set_sunday\";s:6:\"Sunday\";s:6:\"sunday\";s:0:\"\";s:10:\"sunday_end\";s:0:\"\";}',	'a:7:{s:12:\"monday_close\";N;s:13:\"tuesday_close\";N;s:15:\"wednesday_close\";N;s:14:\"thursday_close\";N;s:12:\"friday_close\";N;s:14:\"saturday_close\";N;s:12:\"sunday_close\";s:1:\"1\";}',	'1',	NULL,	1),
(7,	'LOZELLS BRANCH',	'91 Lozells Road&nbsp;\r\n<p>&nbsp;</p>\r\n',	'52.502984',	'-1.905712',	'Birmingham',	'Armenia',	'B19 2TR',	'01215239886',	'lozells@bbakery.co.uk',	'a:21:{s:10:\"set_monday\";s:6:\"Monday\";s:6:\"monday\";s:5:\"10:30\";s:10:\"monday_end\";s:5:\"19:30\";s:11:\"set_tuesday\";s:7:\"Tuesday\";s:7:\"tuesday\";s:5:\"10:30\";s:11:\"tuesday_end\";s:5:\"19:30\";s:13:\"set_wednesday\";s:9:\"Wednesday\";s:9:\"wednesday\";s:5:\"10:30\";s:13:\"wednesday_end\";s:5:\"19:30\";s:12:\"set_thursday\";s:8:\"Thursday\";s:8:\"thursday\";s:5:\"10:30\";s:12:\"thursday_end\";s:5:\"19:30\";s:10:\"set_friday\";s:6:\"Friday\";s:6:\"friday\";s:5:\"10:30\";s:10:\"friday_end\";s:5:\"19:30\";s:12:\"set_saturday\";s:8:\"Saturday\";s:8:\"saturday\";s:5:\"10:30\";s:12:\"saturday_end\";s:5:\"19:30\";s:10:\"set_sunday\";s:6:\"Sunday\";s:6:\"sunday\";s:0:\"\";s:10:\"sunday_end\";s:0:\"\";}',	'a:7:{s:12:\"monday_close\";N;s:13:\"tuesday_close\";N;s:15:\"wednesday_close\";N;s:14:\"thursday_close\";N;s:12:\"friday_close\";N;s:14:\"saturday_close\";N;s:12:\"sunday_close\";s:1:\"1\";}',	'1',	NULL,	1),
(8,	'DUDLEY ROAD BRANCH',	'155 Dudley Road\r\n<p>&nbsp;</p>\r\n',	'52.486936',	'-1.935627',	'Birmingham',	'Virgin Islands, British',	'B18 7QH',	'01214550909',	'winsongreen@bbakery.co.uk',	'a:14:{s:6:\"monday\";s:5:\"10:30\";s:10:\"monday_end\";s:5:\"19:30\";s:7:\"tuesday\";s:5:\"10:30\";s:11:\"tuesday_end\";s:5:\"19:30\";s:9:\"wednesday\";s:5:\"10:30\";s:13:\"wednesday_end\";s:5:\"19:30\";s:8:\"thursday\";s:5:\"10:30\";s:12:\"thursday_end\";s:5:\"19:30\";s:6:\"friday\";s:5:\"10:30\";s:10:\"friday_end\";s:5:\"19:30\";s:8:\"saturday\";s:5:\"10:30\";s:12:\"saturday_end\";s:5:\"19:30\";s:6:\"sunday\";s:5:\"10:30\";s:10:\"sunday_end\";s:5:\"19:30\";}',	'0',	'0',	NULL,	1),
(9,	'ALUM ROCK BRANCH',	'8 College Road',	'52.488679',	'-1.850380',	'Birmingham',	'United Kingdom',	'B8 1JB',	'01213282235',	'alumrock@bbakery.co.uk',	'a:21:{s:10:\"set_monday\";s:6:\"Monday\";s:6:\"monday\";s:5:\"10:30\";s:10:\"monday_end\";s:5:\"07:30\";s:11:\"set_tuesday\";s:7:\"Tuesday\";s:7:\"tuesday\";s:5:\"10:30\";s:11:\"tuesday_end\";s:5:\"19:30\";s:13:\"set_wednesday\";s:9:\"Wednesday\";s:9:\"wednesday\";s:5:\"10:30\";s:13:\"wednesday_end\";s:5:\"19:30\";s:12:\"set_thursday\";s:8:\"Thursday\";s:8:\"thursday\";s:5:\"10:30\";s:12:\"thursday_end\";s:5:\"19:30\";s:10:\"set_friday\";s:6:\"Friday\";s:6:\"friday\";s:5:\"10:30\";s:10:\"friday_end\";s:5:\"19:30\";s:12:\"set_saturday\";s:8:\"Saturday\";s:8:\"saturday\";s:5:\"10:30\";s:12:\"saturday_end\";s:5:\"19:30\";s:10:\"set_sunday\";s:6:\"Sunday\";s:6:\"sunday\";s:0:\"\";s:10:\"sunday_end\";s:0:\"\";}',	'a:7:{s:12:\"monday_close\";N;s:13:\"tuesday_close\";N;s:15:\"wednesday_close\";N;s:14:\"thursday_close\";N;s:12:\"friday_close\";N;s:14:\"saturday_close\";N;s:12:\"sunday_close\";s:1:\"1\";}',	'1',	NULL,	1);

DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `title` text,
  `data` varchar(50) DEFAULT NULL,
  `product_order_number` varchar(50) DEFAULT NULL,
  `product_account_number` varchar(50) DEFAULT NULL,
  `s_email_id` varchar(50) DEFAULT NULL,
  `s_address` text,
  `ordem` varchar(12) NOT NULL,
  `visivel` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `supplier` (`id`, `title`, `data`, `product_order_number`, `product_account_number`, `s_email_id`, `s_address`, `ordem`, `visivel`) VALUES
(7,	'vishal',	'2020-09-28',	'7878878999',	'87978',	'prajapativishal@gmail.com',	'Culpitt Ltd&nbsp;<br />\r\nJubilee Industrial Estate<br />\r\nAshington<br />\r\nNorthumberland<br />\r\nNE63 8UQ',	'',	'1'),
(9,	'hiteshprajapati',	'2020-10-06',	'7896351',	'452151515156',	'hitesh@gmail.comm',	'Culpitt Ltd&nbsp;<br />\r\nJubilee Industrial Estate<br />\r\nAshington<br />\r\nNorthumberland<br />\r\nNE63 8UQ',	'',	'1'),
(10,	'kisan',	'2020-10-06',	'1115515191851851',	'14154145145144',	'kisan@gmail.com',	'Culpitt Ltd&nbsp;<br />\r\nJubilee Industrial Estate<br />\r\nAshington<br />\r\nNorthumberland<br />\r\nNE63 8UQ',	'',	'1');

DROP TABLE IF EXISTS `taxa_iva`;
CREATE TABLE `taxa_iva` (
  `id` int(11) NOT NULL,
  `iva` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `taxa_iva` (`id`, `iva`) VALUES
(1,	23.00);

DROP TABLE IF EXISTS `termos_pesquisa`;
CREATE TABLE `termos_pesquisa` (
  `id` int(11) NOT NULL,
  `termo` varchar(255) DEFAULT NULL,
  `counter` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`counter`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `textos_notificacoes_en`;
CREATE TABLE `textos_notificacoes_en` (
  `id` int(11) NOT NULL,
  `estado` int(11) DEFAULT '0',
  `nome` varchar(250) DEFAULT NULL,
  `assunto` varchar(250) DEFAULT NULL,
  `texto` text,
  `dias` int(11) DEFAULT '0',
  `horas` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `textos_notificacoes_pt`;
CREATE TABLE `textos_notificacoes_pt` (
  `id` int(11) NOT NULL,
  `estado` int(11) DEFAULT '0',
  `nome` varchar(250) DEFAULT NULL,
  `assunto` varchar(250) DEFAULT NULL,
  `texto` text,
  `dias` int(11) DEFAULT '0',
  `horas` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `textos_notificacoes_pt` (`id`, `estado`, `nome`, `assunto`, `texto`, `dias`, `horas`) VALUES
(1,	1,	'Nova Encomenda',	'A sua encomenda ##enc# foi registada',	'Exmo. Sr(a). <strong>#nome#</strong>,<br />\r\n<br />\r\nA sua encomenda est&aacute; a ser processada pelos nossos servi&ccedil;os. Poder&aacute; consultar o estado da encomenda no nosso website clique <a href=\"http://www.teste.pt/area-reservada.php\"><strong>aqui</strong></a>.<br />\r\n<br />\r\nObrigado pela prefer&ecirc;ncia!',	0,	0),
(2,	2,	'Estado da encomenda: Em processamento',	'A sua encomenda ##enc# encontra-se em processamento - www.teste.pt',	'Exmo. Sr(a). <strong>#nome#</strong>,<br />\r\n<br />\r\nA sua encomenda encontra-se em processamento.<br />\r\n<br />\r\nObrigado pela prefer&ecirc;ncia!',	0,	0),
(3,	3,	'Estado da encomenda: Enviada',	'A sua encomenda ##enc# foi enviada - www.teste.pt',	'Exmo. Sr(a). <strong>#nome#</strong>,<br />\r\n<br />\r\nA sua encomenda foi enviada.<br />\r\n<br />\r\nObrigado pela prefer&ecirc;ncia!',	0,	0),
(4,	5,	'Estado da encomenda: Anulada',	'A sua encomenda ##enc# foi anulada',	'Exmo. Sr(a). <strong>#nome#</strong>,<br />\r\n<br />\r\nA sua encomenda foi anulada.<br />\r\n<br />\r\nObrigado pela prefer&ecirc;ncia!\r\n',	0,	0),
(5,	0,	'Encomenda por pagar há X dias',	'Notificação sobre encomenda - www.teste.pt',	'Exmo(a) Senhor(a) #nome#,<br />\r\n<br />\r\n<span style=\"color:#222222; font-size:12px\">Verificamos que tem uma encomenda por pagar h&aacute; #dias# ou mais dias.</span><br />\r\n<span style=\"color:#222222; font-size:12px\">N&ordm; Encomenda: #enc#</span><br />\r\n<span style=\"color:#222222; font-size:12px\">Total: #preco#</span>',	1,	0),
(6,	0,	'Encomenda anulada por falta de pagamento há X dias',	'Notificação sobre encomenda - www.teste.pt',	'Exmo(a) Senhor(a) #nome#,<br />\r\n<br />\r\nA sua encomenda #enc# foi anulada por se encontrar com falta de pagamento h&aacute; #dias# dias.',	5,	0),
(7,	0,	'Recuperar carrinho',	'Recupere o seu carrinho - www.teste.pt',	'Exmo(a) Senhor(a) #nome#,<br />\r\n<br />\r\nClique no bot&atilde;o &quot;Finalizar Encomenda&quot; e recupere o seu carrinho.',	0,	6),
(8,	0,	'Estado da encomenda: Pronta para Levantamento',	'A sua encomenda ##enc# encontra-se pronta para levantamento - www.teste.pt',	'Exmo. Sr(a). <strong>#nome#</strong>,<br />\r\n<br />\r\nA sua encomenda encontra-se pronta para levantamento.<br />\r\n<br />\r\nObrigado pela prefer&ecirc;ncia!',	0,	0);

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `id_pai` int(11) DEFAULT '0',
  `id_cliente` int(11) DEFAULT NULL,
  `tipo` int(11) DEFAULT '0',
  `remetente` varchar(255) DEFAULT NULL,
  `assunto` varchar(255) DEFAULT NULL,
  `descricao` text,
  `anexos` text,
  `data` datetime DEFAULT NULL,
  `estado` tinyint(4) DEFAULT '1',
  `visto` tinyint(4) DEFAULT '0',
  `lingua` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_pai`,`id_cliente`,`tipo`,`data`,`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tickets_tipos_en`;
CREATE TABLE `tickets_tipos_en` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`visivel`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `tickets_tipos_pt`;
CREATE TABLE `tickets_tipos_pt` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `visivel` tinyint(4) DEFAULT '1',
  `ordem` int(11) DEFAULT '99',
  PRIMARY KEY (`id`),
  KEY `indice` (`visivel`,`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `tickets_tipos_pt` (`id`, `nome`, `visivel`, `ordem`) VALUES
(1,	'Pedido de informações',	1,	1),
(2,	'Trocas e devoluções',	1,	2),
(3,	'Outro assunto',	1,	3);

DROP TABLE IF EXISTS `transportadoras`;
CREATE TABLE `transportadoras` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `kg` decimal(10,3) DEFAULT '0.000',
  `preco` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `transportadoras` (`id`, `nome`, `kg`, `preco`) VALUES
(1,	'MRW Europa',	1.000,	1.74),
(2,	'CTT - 19 M Continente',	0.000,	0.00),
(3,	'CTT - Prá Amanhã - Continente',	1.000,	0.22),
(4,	'CTT - Economy Zona 2  (Alemanha, Austria, Bélgica, Eslováquia, Eslovénia, frança, Grécia, Países baixos, Hungria, Irlanda, Itália, Luxemburgo, Reino Unido, rep. Checa, Suiça)',	1.000,	1.74),
(5,	'CTT - Economy Zona 3 (Dinamarca, Estónia, Finlandia, Islândia, Letónia, Lituania, malta, noruega, Polonia, Suecia)',	1.000,	2.10),
(6,	'MRW - Amanhã 19H Nacional',	1.000,	1.04),
(7,	'MRW - Amanhã 13/14H Nacional',	1.000,	1.35),
(8,	'MRW - Amanhã 19H - Espanha Peninsular',	1.000,	1.66),
(9,	'MRW - Amanhã 13/14H - Espanha Peninsular',	1.000,	1.86),
(10,	'CTT',	0.000,	0.00),
(11,	'MRW',	0.000,	0.00),
(12,	'Store Pickup',	0.000,	0.00),
(13,	'DPD ',	0.000,	0.00),
(14,	'Royal Mail / DPD Tracked ',	0.000,	0.00),
(15,	'Royal Mail / DPD',	0.000,	0.00),
(16,	'Home Delivery',	0.000,	0.00);

DROP TABLE IF EXISTS `transp_valores`;
CREATE TABLE `transp_valores` (
  `id` int(11) NOT NULL,
  `id_transp` int(11) DEFAULT NULL,
  `min` decimal(10,3) DEFAULT NULL,
  `max` decimal(10,3) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indice` (`id_transp`,`min`,`max`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `transp_valores` (`id`, `id_transp`, `min`, `max`, `preco`) VALUES
(1,	1,	0.000,	0.500,	16.44),
(2,	1,	0.000,	1.000,	17.00),
(3,	1,	1.001,	2.000,	20.74),
(4,	1,	2.001,	3.000,	23.83),
(5,	1,	3.001,	4.000,	26.94),
(9,	1,	4.001,	5.000,	30.03),
(12,	1,	5.001,	10.000,	46.61),
(15,	1,	10.001,	15.000,	61.13),
(16,	1,	15.001,	20.000,	76.66),
(17,	1,	20.001,	25.000,	92.19),
(18,	1,	25.001,	30.000,	107.72),
(19,	2,	0.000,	2.000,	4.01),
(20,	2,	2.001,	5.000,	4.53),
(21,	2,	5.001,	10.000,	5.06),
(22,	2,	10.001,	20.000,	5.78),
(23,	2,	20.001,	30.000,	6.51),
(24,	3,	0.000,	2.000,	4.20),
(25,	3,	2.001,	5.000,	4.75),
(26,	3,	5.001,	10.000,	5.33),
(27,	3,	10.001,	15.000,	5.81),
(28,	3,	15.001,	20.000,	6.09),
(29,	3,	20.001,	25.000,	6.73),
(30,	3,	25.001,	30.000,	7.24),
(31,	4,	0.000,	0.500,	16.44),
(32,	4,	0.501,	1.000,	17.61),
(33,	4,	1.001,	2.000,	20.74),
(34,	4,	2.001,	3.000,	23.83),
(35,	4,	3.001,	4.000,	26.94),
(36,	4,	4.001,	5.000,	30.03),
(37,	4,	5.001,	10.000,	45.61),
(38,	4,	10.001,	15.000,	61.13),
(39,	4,	15.001,	20.000,	76.66),
(40,	4,	20.001,	25.000,	92.19),
(41,	4,	25.001,	30.000,	107.72),
(42,	5,	0.000,	0.500,	20.26),
(43,	5,	0.501,	1.000,	22.07),
(44,	5,	1.001,	2.000,	26.52),
(45,	5,	2.001,	3.000,	30.98),
(46,	5,	3.001,	4.000,	35.42),
(47,	5,	4.001,	5.000,	39.90),
(48,	5,	0.000,	0.000,	0.00),
(49,	5,	5.001,	10.000,	62.19),
(50,	5,	10.001,	15.000,	84.51),
(51,	5,	15.001,	20.000,	106.80),
(52,	5,	20.001,	25.000,	129.06),
(53,	5,	25.001,	30.000,	151.39),
(54,	6,	0.000,	2.000,	4.00),
(55,	6,	2.001,	5.000,	4.20),
(56,	6,	5.001,	10.000,	5.80),
(57,	7,	0.000,	5.000,	5.40),
(58,	7,	5.001,	10.000,	8.10),
(59,	8,	0.000,	2.000,	4.30),
(60,	8,	2.001,	5.000,	4.90),
(61,	8,	5.001,	10.000,	6.10),
(62,	9,	0.000,	5.000,	5.60),
(63,	9,	5.001,	10.000,	8.30);

DROP TABLE IF EXISTS `world_map`;
CREATE TABLE `world_map` (
  `id` int(11) NOT NULL,
  `id_pais` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `d` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `world_map` (`id`, `id_pais`, `title`, `d`) VALUES
(1,	'AE',	'United Arab Emirates',	'M619.87,393.72L620.37,393.57L620.48,394.41L622.67,393.93L624.99,394.01L626.68,394.1L628.6,392.03L630.7,390.05L632.47,388.15L633,389.2L633.38,391.64L631.95,391.65L631.72,393.65L632.22,394.07L630.95,394.67L630.94,395.92L630.12,397.18L630.05,398.39L629.48,399.03L621.06,397.51L619.98,394.43z'),
(2,	'AF',	'Afghanistan',	'M646.88,356.9L649.74,358.2L651.85,357.74L652.44,356.19L654.65,355.67L656.23,354.62L656.79,351.83L659.15,351.15L659.59,349.9L660.92,350.84L661.76,350.95L663.32,350.98L665.44,351.72L666.29,352.14L668.32,351.02L669.27,351.69L670.17,350.09L671.85,350.16L672.28,349.64L672.58,348.21L673.79,346.98L675.3,347.78L675,348.87L675.85,349.04L675.58,351.99L676.69,353.14L677.67,352.4L678.92,352.06L680.66,350.49L682.59,350.75L685.49,350.75L685.99,351.76L684.35,352.15L682.93,352.8L679.71,353.2L676.7,353.93L675.06,355.44L675.72,356.9L676.05,358.6L674.65,360.03L674.77,361.33L674,362.55L671.33,362.44L672.43,364.66L670.65,365.51L669.46,367.51L669.61,369.49L668.51,370.41L667.48,370.11L665.33,370.54L665.03,371.45L662.94,371.45L661.38,373.29L661.28,376.04L657.63,377.37L655.68,377.09L655.11,377.79L653.44,377.39L650.63,377.87L645.94,376.23L648.48,373.3L648.25,371.2L646.13,370.65L645.91,368.56L644.99,365.92L646.19,364.09L644.97,363.6L645.74,361.15z'),
(3,	'AL',	'Albania',	'M532.98,334.66L532.63,335.93L533.03,337.52L534.19,338.42L534.13,339.39L533.22,339.93L533.05,341.12L531.75,342.88L531.27,342.63L531.22,341.83L529.66,340.6L529.42,338.85L529.66,336.32L530.04,335.16L529.57,334.57L529.38,333.38L530.6,331.51L530.77,332.23L531.53,331.89L532.13,332.91L532.8,333.29z'),
(4,	'AM',	'Armenia',	'M597.45,337.5L601.35,336.92L601.93,337.9L603,338.54L602.43,339.46L603.93,340.72L603.14,341.88L604.33,342.87L605.59,343.46L605.65,345.96L604.63,346.06L603.49,343.98L603.5,343.43L602.26,343.44L601.43,342.46L600.85,342.56L599.74,341.5L597.66,340.59L597.93,338.8z'),
(5,	'AO',	'Angola',	'M521.03,479.78l0.69,2.09l0.8,1.68l0.64,0.91l1.07,1.47l1.85,-0.23l0.93,-0.4l1.55,0.4l0.42,-0.7l0.7,-1.64l1.74,-0.11l0.15,-0.49l1.43,-0.01l-0.24,1.01l3.4,-0.02l0.05,1.77l0.57,1.09l-0.41,1.7l0.21,1.74l0.94,1.05l-0.15,3.37l0.69,-0.26l1.22,0.07l1.74,-0.42l1.28,0.17l0.3,0.88l-0.32,1.38l0.49,1.34l-0.42,1.07l0.24,0.99l-5.84,-0.04l-0.13,9.16l1.89,2.38l1.83,1.82l-5.15,1.19l-6.79,-0.41l-1.94,-1.4l-11.37,0.13l-0.42,0.21l-1.67,-1.32l-1.82,-0.09l-1.68,0.5l-1.35,0.56l-0.26,-1.83l0.39,-2.55l0.97,-2.65l0.15,-1.24l0.91,-2.59l0.67,-1.17l1.61,-1.87l0.9,-1.27l0.29,-2.11l-0.15,-1.61l-0.84,-1.01l-0.75,-1.72l-0.69,-1.69l0.15,-0.59l0.86,-1.12l-0.85,-2.72l-0.57,-1.88l-1.4,-1.77l0.27,-0.54l1.16,-0.38l0.81,0.05l0.98,-0.34L521.03,479.78zM510.12,479.24l-0.71,0.3l-0.75,-2.1l1.13,-1.21l0.85,-0.47l1.05,0.96l-1.02,0.59l-0.46,0.72L510.12,479.24z'),
(6,	'AR',	'Argentina',	'M291.6,648.91l-2.66,0.25l-1.43,-1.73l-1.69,-0.13l-3,0l0,-10.57l1.08,2.15l1.4,3.53l3.65,2.87l3.93,1.21L291.6,648.91zM293.1,526.47l1.65,2.18l1.09,-2.43l3.2,0.12l0.45,0.64l5.15,4.94l2.29,0.46l3.43,2.26l2.89,1.2l0.4,1.36l-2.76,4.73l2.83,0.85l3.15,0.48l2.22,-0.5l2.54,-2.4l0.46,-2.74l1.39,-0.59l1.41,1.79l-0.06,2.49l-2.36,1.73l-1.88,1.28l-3.16,3.08l-3.74,4.37l-0.7,2.59l-0.75,3.37l0.03,3.3l-0.61,0.74l-0.22,2.17l-0.19,1.76l3.56,2.91l-0.38,2.37l1.75,1.51l-0.14,1.7l-2.69,4.52l-4.16,1.91l-5.62,0.75l-3.08,-0.36l0.59,2.15l-0.57,2.72l0.52,1.85l-1.68,1.3l-2.87,0.51l-2.7,-1.35l-1.08,0.97l0.39,3.71l1.89,1.14l1.54,-1.19l0.84,1.96l-2.58,1.18l-2.25,2.38l-0.41,3.91l-0.66,2.11l-2.65,0.01l-2.2,2.04l-0.8,3.01l2.76,2.98l2.68,0.83l-0.96,3.73l-3.31,2.38l-1.82,5.03l-2.56,1.72l-1.15,2.06l0.91,4.64l1.87,2.63l-1.18,-0.23l-2.6,-0.71l-6.78,-0.61l-1.16,-2.63l0.05,-3.33l-1.87,0.28l-0.99,-1.6l-0.25,-4.6l2.15,-1.88l0.89,-2.68l-0.33,-2.11l1.49,-3.52l1.02,-5.35l-0.3,-2.33l1.22,-0.75l-0.3,-1.48l-1.3,-0.78l0.92,-1.63l-1.27,-1.46l-0.65,-4.4l1.13,-0.77l-0.47,-4.54l0.66,-3.75l0.75,-3.22l1.68,-1.3l-0.85,-3.46l-0.01,-3.22l2.12,-2.26l-0.06,-2.87l1.6,-3.31l0.01,-3.09l-0.73,-0.61l-1.29,-5.69l1.73,-3.34l-0.27,-3.11l1,-2.9l1.84,-2.96l1.98,-1.95l-0.84,-1.23l0.59,-1l-0.09,-5.14l3.05,-1.51l0.96,-3.16l-0.34,-0.76l2.34,-2.72L293.1,526.47z'),
(7,	'AT',	'Austria',	'M522.86,309.85L522.65,311.56L521.07,311.57L521.61,312.46L520.68,315.11L520.15,315.8L517.7,315.9L516.28,316.82L513.96,316.51L509.95,315.46L509.33,314.03L506.56,314.75L506.23,315.52L504.53,314.94L503.1,314.83L501.83,314.09L502.26,313.08L502.15,312.34L503,312.12L504.42,313.26L504.82,312.17L507.29,312.35L509.3,311.61L510.64,311.73L511.51,312.58L511.78,311.88L511.38,309.16L512.39,308.62L513.37,306.67L515.46,308.04L517.03,306.3L518.02,305.98L520.2,307.28L521.51,307.06L522.81,307.86L522.58,308.4z'),
(8,	'AU',	'Australia',	'M882.93,588.16l2.71,1.28l1.53,-0.51l2.19,-0.71l1.68,0.25l0.2,4.43l-0.96,1.3l-0.29,3.06l-0.98,-1.05l-1.95,2.67l-0.58,-0.21l-1.72,-0.12l-1.73,-3.28l-0.38,-2.5l-1.62,-3.25l0.07,-1.7L882.93,588.16zM877.78,502.1l1.01,2.25l1.8,-1.08l0.93,1.22l1.35,1.13l-0.29,1.28l0.6,2.48l0.43,1.45l0.71,0.35l0.76,2.5l-0.27,1.52l0.91,1.99l3.04,1.54l1.98,1.41l1.88,1.29l-0.37,0.72l1.6,1.87l1.09,3.25l1.12,-0.66l1.14,1.31l0.69,-0.46l0.48,3.21l1.99,1.87l1.3,1.17l2.19,2.49l0.79,2.49l0.07,1.77l-0.19,1.94l1.34,2.68l-0.16,2.81l-0.49,1.48l-0.76,2.87l0.06,1.86l-0.55,2.34l-1.24,3l-2.08,1.63l-1.02,2.59l-0.94,1.67l-0.83,2.93l-1.08,1.71l-0.71,2.58l-0.36,2.4l0.14,1.11l-1.61,1.22l-3.14,0.13l-2.59,1.45l-1.29,1.38l-1.69,1.54l-2.32,-1.58l-1.72,-0.63l0.44,-1.85l-1.53,0.67l-2.46,2.58l-2.42,-0.97l-1.59,-0.56l-1.6,-0.25l-2.71,-1.03l-1.81,-2.18l-0.52,-2.66l-0.65,-1.75l-1.38,-1.4l-2.7,-0.41l0.92,-1.66l-0.68,-2.52l-1.37,2.35l-2.5,0.63l1.47,-1.88l0.42,-1.95l1.08,-1.65l-0.22,-2.47l-2.28,2.85l-1.75,1.15l-1.07,2.69l-2.19,-1.4l0.09,-1.79l-1.75,-2.43l-1.48,-1.25l0.53,-0.77l-3.6,-2l-1.97,-0.09l-2.7,-1.6l-5.02,0.31l-3.63,1.18l-3.19,1.1l-2.68,-0.22l-2.97,1.7l-2.43,0.77l-0.54,1.75l-1.04,1.36l-2.38,0.08l-1.76,0.3l-2.48,-0.61l-2.02,0.37l-1.92,0.15l-1.67,1.8l-0.82,-0.15l-1.41,0.96l-1.35,1.08l-2.05,-0.13l-1.88,0l-2.97,-2.17l-1.51,-0.64l0.06,-1.93l1.39,-0.46l0.48,-0.76l-0.1,-1.2l0.34,-2.3l-0.31,-1.95l-1.48,-3.29l-0.46,-1.85l0.12,-1.83l-1.12,-2.08l-0.07,-0.93l-1.24,-1.26l-0.35,-2.47l-1.6,-2.48l-0.39,-1.33l1.23,1.35l-0.95,-2.88l1.39,0.9l0.83,1.2l-0.05,-1.59l-1.39,-2.43l-0.27,-0.97l-0.65,-0.92l0.3,-1.77l0.57,-0.75l0.38,-1.52l-0.3,-1.77l1.16,-2.17l0.21,2.29l1.18,-2.07l2.28,-1l1.37,-1.28l2.14,-1.1l1.27,-0.23l0.77,0.37l2.21,-1.11l1.7,-0.33l0.42,-0.65l0.74,-0.27l1.55,0.07l2.95,-0.87l1.52,-1.31l0.72,-1.58l1.64,-1.49l0.13,-1.17l0.07,-1.59l1.96,-2.47l1.18,2.51l1.19,-0.58l-1,-1.38l0.88,-1.41l1.24,0.63l0.34,-2.21l1.53,-1.42l0.68,-1.14l1.41,-0.49l0.04,-0.8l1.23,0.34l0.05,-0.72l1.23,-0.41l1.36,-0.39l2.07,1.32l1.56,1.71l1.75,0.02l1.78,0.27l-0.59,-1.58l1.34,-2.3l1.26,-0.75l-0.44,-0.71l1.22,-1.63l1.7,-1.01l1.43,0.34l2.36,-0.54l-0.05,-1.45l-2.05,-0.94l1.49,-0.41l1.86,0.7l1.49,1.17l2.36,0.73l0.8,-0.29l1.74,0.88l1.64,-0.82l1.05,0.25l0.66,-0.55l1.29,1.41l-0.75,1.53l-1.06,1.16l-0.96,0.1l0.33,1.15l-0.82,1.43l-1,1.41l0.2,0.81l2.23,1.6l2.16,0.93l1.44,1l2.03,1.72l0.79,0l1.47,0.75l0.43,0.9l2.68,0.99l1.85,-1l0.55,-1.57l0.57,-1.29l0.35,-1.59l0.85,-2.3l-0.39,-1.39l0.2,-0.84l-0.32,-1.64l0.37,-2.16l0.54,-0.58l-0.44,-0.95l0.68,-1.51l0.53,-1.56l0.07,-0.81l1.04,-1.06l0.79,1.39l0.19,1.78l0.7,0.34l0.12,1.2l1.02,1.45l0.21,1.62L877.78,502.1z'),
(9,	'AZ',	'Azerbaijan',	'M601.43,342.46l0.83,0.97l1.24,-0.01l-0.01,0.56l1.14,2.08l-1.92,-0.48l-1.42,-1.66l-0.44,-1.37L601.43,342.46zM608.08,337.03l1.24,0.25l0.48,-0.95l1.67,-1.51l1.47,1.97l1.43,2.62l1.31,0.17l0.86,0.99l-2.31,0.29l-0.49,2.82l-0.48,1.26l-1.03,0.84l0.08,1.77l-0.7,0.18l-1.75,-1.87l0.97,-1.78l-0.83,-1.06l-1.05,0.27l-3.31,2.66l-0.06,-2.5l-1.26,-0.59l-1.19,-0.99l0.79,-1.16l-1.49,-1.26l0.56,-0.92l-1.07,-0.64l-0.58,-0.97l0.69,-0.61l2.09,1.07l1.51,0.22l0.38,-0.43l-1.38,-2.02l0.73,-0.52l0.79,0.13L608.08,337.03z'),
(10,	'BA',	'Bosnia and Herzegovina',	'M528.54,323.11L529.56,323.1L528.86,324.82L530.21,326.32L529.8,328.14L529.14,328.31L528.61,328.67L527.7,329.56L527.29,331.66L524.81,330.22L523.75,328.61L522.68,327.76L521.39,326.31L520.79,325.1L519.41,323.27L520,321.63L521.01,322.54L521.61,321.72L522.92,321.63L525.33,322.29L527.27,322.23z'),
(11,	'BD',	'Bangladesh',	'M735.09,400.41L735.04,402.56L734.06,402.1L734.24,404.51L733.44,402.95L733.28,401.43L732.74,399.98L731.57,398.22L728.99,398.1L729.25,399.35L728.37,401.02L727.17,400.41L726.76,400.96L725.97,400.63L724.89,400.36L724.45,397.88L723.48,395.6L723.95,393.76L722.23,392.94L722.85,391.82L724.6,390.67L722.58,389.04L723.57,386.93L725.79,388.27L727.13,388.43L727.38,390.58L730.04,391L732.65,390.95L734.26,391.48L732.97,394.07L731.71,394.25L730.85,395.98L732.38,397.56L732.84,395.62L733.62,395.61z'),
(12,	'BE',	'Belgium',	'M484.55,295.91L486.6,296.26L489.2,295.33L490.97,297.28L492.52,298.32L492.2,301.29L491.47,301.45L491.16,303.88L488.71,301.91L487.27,302.25L485.31,300.19L484.01,298.42L482.71,298.35L482.3,296.79z'),
(13,	'BF',	'Burkina Faso',	'M467.33,436.4L465.41,435.67L464.09,435.78L463.11,436.49L461.85,435.89L461.36,434.96L460.1,434.34L459.91,432.7L460.68,431.49L460.61,430.53L462.84,428.17L463.25,426.21L464.02,425.51L465.38,425.89L466.55,425.31L466.93,424.57L469.11,423.29L469.64,422.39L472.26,421.19L473.81,420.78L474.51,421.33L476.3,421.32L476.08,422.72L476.46,424.03L478.04,425.9L478.12,427.28L481.36,427.93L481.29,429.88L480.68,430.74L479.31,431L478.74,432.24L477.78,432.56L475.32,432.5L474.02,432.28L473.12,432.74L471.88,432.53L467.01,432.66L466.94,434.27z'),
(14,	'BG',	'Bulgaria',	'M538.78,325.56L539.59,327.16L540.67,326.87L542.83,327.48L546.95,327.68L548.34,326.69L551.64,325.79L553.68,327.2L555.33,327.61L553.87,329.2L552.85,331.93L553.75,334.09L551.34,333.58L548.48,334.76L548.45,336.62L545.9,336.97L543.93,335.67L541.68,336.7L539.61,336.59L539.41,334.12L538,332.91L538.47,332.37L538.16,331.92L538.63,330.71L539.7,329.52L538.34,327.86L538.09,326.44z'),
(15,	'BI',	'Burundi',	'M557.52,475.93L557.34,472.56L556.63,471.3L558.34,471.52L559.2,469.93L560.69,470.11L560.85,471.21L561.45,471.84L561.48,472.75L560.79,473.33L559.69,474.79L558.68,475.8z'),
(16,	'BJ',	'Benin',	'M482.8,445.92L480.48,446.25L479.79,444.31L479.92,437.85L479.35,437.27L479.25,435.88L478.27,434.89L477.42,434.06L477.78,432.56L478.74,432.24L479.31,431L480.68,430.74L481.29,429.88L482.23,429.05L483.24,429.04L485.38,430.68L485.27,431.63L485.9,433.31L485.35,434.45L485.64,435.21L484.28,436.96L483.42,437.83L482.89,439.6L482.96,441.39z'),
(17,	'BN',	'Brunei Darussalam',	'M795.46,450.77L796.57,449.72L798.96,448.19L798.83,449.57L798.67,451.35L797.33,451.26L796.74,452.21z'),
(18,	'BO',	'Bolivia',	'M299.04,526.35L295.84,526.22L294.75,528.65L293.1,526.47L289.43,525.74L287.1,528.46L285.07,528.87L283.97,524.72L282.47,521.38L283.35,518.51L281.88,517.26L281.51,515.14L280.13,513.14L281.9,510L280.69,507.56L281.34,506.59L280.83,505.52L281.93,504.08L281.99,501.64L282.12,499.62L282.73,498.66L280.3,494.08L282.39,494.32L283.83,494.25L284.46,493.4L286.91,492.25L288.38,491.19L292.05,490.71L291.76,492.83L292.1,493.92L291.87,495.82L294.92,498.37L298.06,498.84L299.16,499.91L301.06,500.48L302.22,501.31L303.98,501.28L305.61,502.13L305.73,503.79L306.28,504.63L306.32,505.88L305.5,505.92L306.58,509.29L311.95,509.41L311.54,511.09L311.84,512.24L313.37,513.06L314.04,514.88L313.54,517.2L312.77,518.49L313.04,520.18L312.16,520.79L312.12,519.88L309.5,518.37L306.9,518.32L302.01,519.18L300.67,521.8L300.6,523.4L299.49,526.99z'),
(19,	'BR',	'Brazil',	'M313.68,551.79L317.42,547.42L320.59,544.34L322.47,543.06L324.83,541.33L324.89,538.84L323.48,537.05L322.09,537.64L322.64,535.86L323.02,534.04L323.02,532.36L322.01,531.81L320.96,532.3L319.92,532.17L319.59,530.99L319.33,528.22L318.8,527.32L316.91,526.5L315.77,527.09L312.81,526.51L312.99,522.45L312.16,520.79L313.04,520.18L312.77,518.49L313.54,517.2L314.04,514.88L313.37,513.06L311.84,512.24L311.54,511.09L311.95,509.41L306.58,509.29L305.5,505.92L306.32,505.88L306.28,504.63L305.73,503.79L305.61,502.13L303.98,501.28L302.22,501.31L301.06,500.48L299.16,499.91L298.06,498.84L294.92,498.37L291.87,495.82L292.1,493.92L291.76,492.83L292.05,490.71L288.38,491.19L286.91,492.25L284.46,493.4L283.83,494.25L282.39,494.32L280.3,494.08L278.72,494.57L277.44,494.24L277.63,489.94L275.33,491.6L272.86,491.53L271.8,490.02L269.94,489.86L270.53,488.65L268.97,486.93L267.8,484.4L268.54,483.89L268.54,482.7L270.24,481.89L269.96,480.38L270.67,479.4L270.88,478.1L274.08,476.19L276.38,475.66L276.75,475.24L279.28,475.37L280.54,467.72L280.61,466.51L280.17,464.92L278.93,463.9L278.94,461.88L280.52,461.42L281.08,461.71L281.17,460.64L279.53,460.35L279.5,458.61L284.96,458.67L285.89,457.71L286.67,458.59L287.21,460.24L287.74,459.89L289.29,461.37L291.47,461.19L292.01,460.33L294.09,459.68L295.25,459.23L295.57,458.05L297.58,457.25L297.42,456.67L295.05,456.43L294.66,454.67L294.77,452.8L293.52,452.08L294.04,451.82L296.12,452.18L298.35,452.88L299.16,452.22L301.17,451.78L304.31,450.74L305.34,449.67L304.96,448.88L306.42,448.76L307.08,449.4L306.71,450.63L307.67,451.05L308.32,452.35L307.54,453.33L307.09,455.71L307.81,457.12L308.01,458.41L309.74,459.71L311.12,459.85L311.43,459.31L312.31,459.19L313.58,458.7L314.49,457.96L316.04,458.19L316.72,458.09L318.25,458.32L318.5,457.75L318.03,457.2L318.31,456.39L319.44,456.64L320.77,456.35L322.37,456.94L323.6,457.52L324.47,456.76L325.09,456.88L325.48,457.67L326.82,457.47L327.89,456.41L328.75,454.35L330.41,451.8L331.37,451.67L332.06,453.21L333.63,458.09L335.13,458.55L335.21,460.47L333.1,462.76L333.97,463.6L338.93,464.04L339.03,466.83L341.16,465L344.69,466.01L349.34,467.71L350.71,469.34L350.25,470.88L353.51,470.02L358.97,471.5L363.16,471.39L367.3,473.7L370.88,476.83L373.04,477.63L375.44,477.75L376.46,478.63L377.41,482.2L377.88,483.89L376.76,488.55L375.33,490.39L371.38,494.33L369.59,497.54L367.52,500.02L366.82,500.08L366.03,502.18L366.23,507.58L365.45,512.06L365.15,513.99L364.27,515.14L363.77,519.08L360.93,522.96L360.45,526.05L358.18,527.36L357.52,529.17L354.48,529.16L350.07,530.33L348.09,531.68L344.95,532.57L341.65,535.01L339.28,538.07L338.87,540.39L339.34,542.12L338.81,545.3L338.18,546.85L336.22,548.6L333.11,554.28L330.64,556.87L328.73,558.41L327.46,561.57L325.6,563.48L324.82,561.58L326.06,560.01L324.44,557.76L322.24,555.94L319.35,553.86L318.31,553.95L315.5,551.45z'),
(20,	'BS',	'Bahamas',	'M257.86,395.2l-0.69,0.15l-0.71,-1.76l-1.05,-0.89l0.61,-1.95l0.84,0.12l0.98,2.55L257.86,395.2zM257.06,386.51l-3.06,0.5l-0.2,-1.15l1.32,-0.25l1.85,0.09L257.06,386.51zM259.36,386.48l-0.48,2.21l-0.52,-0.4l0.05,-1.63l-1.26,-1.23l-0.01,-0.36L259.36,386.48z'),
(21,	'BT',	'Bhutan',	'M732.36,382.78L733.5,383.78L733.3,385.71L731.01,385.8L728.65,385.59L726.88,386.08L724.33,384.89L724.28,384.26L726.13,381.92L727.64,381.12L729.65,381.85L731.13,381.93z'),
(22,	'BW',	'Botswana',	'M547.17,515.95L547.73,516.47L548.62,518.18L551.79,521.43L552.99,521.75L553,522.8L553.82,524.7L555.99,525.16L557.78,526.52L553.81,528.74L551.29,531L550.36,533.03L549.52,534.18L547.99,534.43L547.5,535.9L547.21,536.86L545.42,537.58L543.14,537.43L541.8,536.57L540.62,536.19L539.25,536.91L538.56,538.39L537.23,539.32L535.83,540.71L533.82,541.03L533.2,539.94L533.46,538.04L531.79,535.11L531.04,534.65L531.04,525.79L533.8,525.68L533.88,515.11L535.97,515.02L540.29,513.99L541.37,515.2L543.15,514.05L544.01,514.04L545.59,513.38L546.09,513.6z'),
(23,	'BY',	'Belarus',	'M541.1,284.07L543.81,284.11L546.85,282.31L547.5,279.59L549.8,278.02L549.54,275.82L551.24,274.98L554.26,273.05L557.21,274.31L557.61,275.54L559.08,274.95L561.82,276.13L562.09,278.44L561.49,279.76L563.25,282.91L564.39,283.78L564.22,284.64L566.11,285.47L566.92,286.72L565.83,287.74L563.57,287.58L563.03,288.02L563.69,289.56L564.38,292.49L561.97,292.76L561.11,293.76L560.92,296.02L559.81,295.59L557.28,295.81L556.54,294.76L555.49,295.54L554.44,294.89L552.23,294.8L549.1,293.72L546.27,293.36L544.1,293.46L542.56,294.69L541.22,294.86L541.17,292.85L540.3,290.73L541.98,289.79L542,287.94L541.22,286.16z'),
(24,	'BZ',	'Belize',	'M225.31,412.96L225.29,412.53L225.63,412.39L226.14,412.74L227.14,410.97L227.67,410.93L227.68,411.36L228.21,411.37L228.17,412.17L227.71,413.44L227.96,413.89L227.67,414.94L227.84,415.21L227.52,416.68L226.97,417.46L226.46,417.55L225.9,418.55L225.07,418.55L225.29,415.27z'),
(25,	'CA',	'Canada',	'M198.93,96.23l-0.22,-5.9l3.63,0.58l1.63,0.96l3.35,4.92l-0.76,4.97l-4.15,2.77l-2.28,-3.12L198.93,96.23zM212.14,108.88l0.33,-1.49l-1.97,-2.45l-5.65,-0.19l0.75,3.68l5.25,0.83L212.14,108.88zM248.49,155.83l3.08,5.1l0.81,0.57l3.07,-1.27l3.02,0.2l2.98,0.28l-0.25,-2.64l-4.84,-5.38l-6.42,-1.08l-1.35,0.67L248.49,155.83zM183.06,93.13l-2.71,4.19l6.24,0.52l4.61,4.44l4.58,1.5l-1.09,-5.68l-2.14,-6.73l-7.58,-5.35l-5.5,-2.04l0.2,5.69L183.06,93.13zM208.96,82.89l5.13,-0.12l-2.22,4l-0.04,5.3l3.01,5.76l5.81,1.77l4.96,-0.99l5.18,-10.73l3.85,-4.45l-3.38,-4.97l-2.21,-10.65l-4.6,-3.19l-4.72,-3.68l-3.58,-9.56l-6.52,0.94l1.23,4.15l-2.87,1.25l-1.94,5.32l-1.94,7.46l1.78,7.26L208.96,82.89zM145.21,136.27l3.92,1.95l12.67,-1.3l-5.82,4.77l0.36,3.43l4.26,-0.24l7.07,-4.58l9.5,-1.67l1.71,-5.22l-0.49,-5.57l-2.94,-0.5l-2.5,1.93l-1.1,-4.13l-0.95,-5.7l-2.9,-1.42l-2.57,4.41l4.01,11.05l-4.9,-0.85l-4.98,-6.79l-7.89,-4l-2.64,3.32L145.21,136.27zM167.77,94.21l-3.65,-2.9l-1.5,-0.66l-2.88,4.28l-0.05,2l4.66,0.01L167.77,94.21zM166.31,106.56l0.93,-3.99l-3.95,-2.12l-4.09,1.39l-2.27,4.26l4.16,4.21L166.31,106.56zM195.4,139.8l4.62,-1.11l1.28,-8.25l-0.09,-5.95l-2.14,-5.56l-0.22,1.6l-3.94,-0.7l-4.22,4.09l-3.02,-0.37l0.18,8.92l4.6,-0.87l-0.06,6.47L195.4,139.8zM192.12,185.41l-5.06,-3.93l-4.71,-4.21l-0.87,-6.18l-1.76,-8.92l-3.14,-3.84l-2.79,-1.55l-2.47,1.42l1.99,9.59l-1.41,3.73l-2.29,-8.98l-2.56,-3.11l-3.17,4.81l-3.9,-4.76l-6.24,2.87l1.4,-4.46l-2.87,-1.87l-7.51,5.84l-1.95,3.71l-2.35,6.77l4.9,2.32l4.33,-0.12l-6.5,3.46l1.48,3.13l3.98,0.17l5.99,-0.67l5.42,1.96l-3.66,1.44l-3.95,-0.37l-4.33,1.41l-1.87,0.87l3.45,6.35l2.49,-0.88l3.83,2.15l1.52,3.65l4.99,-0.73l7.1,-1.16l5.26,-2.65l3.26,-0.48l4.82,2.12l5.07,1.22l0.94,-2.86l-1.79,-3.05l4.6,-0.64L192.12,185.41zM199.86,184.43l-1.96,3.54l-2.47,2.49l3.83,3.54l2.28,-0.85l3.78,2.36l1.74,-2.73l-1.71,-3.03l-0.84,-1.53l-1.68,-1.46L199.86,184.43zM182.25,154.98l-2.13,-2.17l-3.76,0.4l-0.95,1.38l4.37,6.75L182.25,154.98zM210.94,168.15l3.01,-6.93l3.34,-1.85l4.19,-8.74l-5.36,-2.47l-5.84,-0.36l-2.78,2.77l-1.47,4.23l-0.04,4.82l1.75,8.19L210.94,168.15zM228.09,145.15l5.76,-0.18l8.04,-1.61l3.59,1.28l4.18,-2.26l1.75,-2.84l-0.63,-4.52l-3,-4.23l-4.56,-0.8l-5.71,0.97l-4.46,2.44l-4.09,-0.94l-3.78,-0.5l-1.78,-2.7l-3.22,-2.61l0.64,-4.43l-2.42,-3.98l-5.52,0.03l-3.11,-3.99l-5.78,-0.8l-1.06,5.1l3.25,3.74l5.8,1.45l2.81,5.09l0.34,5.6l0.97,5.99l7.45,3.42L228.09,145.15zM139.07,126.88l5.21,-5.05l2.62,-0.59l2.16,-4.23l0.38,-9.77l-3.85,1.91l-4.3,-0.18l-5.76,8.19l-4.76,8.98l3.8,2.51L139.07,126.88zM211.25,143.05l1.53,-4.14l-1.02,-3.46l-2.45,-3.92l-4.03,3.02l-1.49,4.92l3.4,2.79L211.25,143.05zM202.94,154.49l-0.73,-2.88l-5,1.26l-3.34,-2.11l-3.32,4.8l3.09,6.28l-5.72,-1.17l-0.06,3.01l6.97,7.05l1.94,3.38l2.7,0.73l4.6,-3.41l0.5,-8.21l-4.24,-4.07L202.94,154.49zM128.95,308.23l-1.16,-2.34l-2.8,-1.77l-1.39,-2.05l-0.95,-1.5l-2.64,-0.46l-1.72,-0.67l-2.94,-0.96l-0.24,1.02l1.08,2.38l2.89,0.78l0.5,1.23l2.51,1.5l0.84,1.51l4.6,1.92L128.95,308.23zM250.65,230.6l-2,-2.11l-2.06,0.5l-0.25,-3.06l-3.21,-2.04l-3.07,-2.27l-1.63,-1.75l-1.43,1.03l-0.52,-2.96l-2.03,-0.55l-0.96,6.13l-0.36,5.11l-2.44,3.14l3.8,-0.6l0.96,3.65l3.99,-3.23l2.78,-3.38l1.57,2.86l4.36,1.51L250.65,230.6zM130.12,178.05l7.38,-4.18V170l3.48,-6.41l6.88,-6.69l3.52,-2.47l-3.01,-4.2l-2.72,-2.95l-7.16,-0.57l-4,-2.16l-9.48,1.63l2.74,6.23l-2.43,6.43l-1.94,6.87l-1.2,3.86l6.47,4.69L130.12,178.05zM264.36,205.36l0.32,-1.01l-0.03,-3.17l-2.19,-2.08l-2.57,1.05l-1.19,4.17l0.7,3.56l3.14,-0.36L264.36,205.36zM288.18,212.9l4.41,6.6l3.45,2.85l4.92,-7.87l0.87,-4.93l-4.41,-0.47l-4.03,-6.7l-4.45,-1.64l-6.6,-4.97l5.15,-3.63l-2.65,-7.54l-2.44,-3.35l-6.77,-3.35l-2.92,-5.55l-5.21,1.99l-0.36,-3.86l-3.86,-4.32l-6.22,-4.71l-2.65,3.71l-5.55,2.66l0.42,-6.06l-4.81,-10.05l-7.11,4.06l-2.59,7.7l-2.21,-5.92l2.06,-6.37l-7.24,2.65l-2.88,3.99l-2.15,8.42l0.89,9.05l3.98,0.04l-2.93,3.92l2.33,2.96l4.55,1.25l5.93,2.42l10.2,1.82l5.08,-1.04l1.5,-2.42l2.21,2.79l2.47,0.46l2.97,4.96l-1.8,1.98l5.68,2.63l4.29,3.68l1.08,2.55l0.77,3.24l-3.63,6.93l-0.98,3.44l0.94,2.42l-5.77,0.86l-5.27,0.12l-1.85,4.87l2.37,2.23l8.11,-1.03l-0.04,-1.89l4.08,3.15l4.18,3.28l-0.98,1.77l3.4,3.02l6.02,3.53l7.6,2.39l-0.46,-2.09l-2.92,-3.67l-3.96,-5.37l7.03,5l3.54,1.66l0.97,-4.44l-1.82,-6.3l-1.16,-1.73l-3.81,-3.03l-2.95,-3.91l0.35,-3.94L288.18,212.9zM222.35,51.34l2.34,7.29l4.96,5.88l9.81,-1.09l6.31,1.97l-4.38,6.05l-2.21,-1.78l-7.66,-0.71l1.19,8.31l3.96,6.04l-0.8,5.2l-4.97,3.46l-2.27,5.47l4.55,2.65l3.82,8.55l-7.5,-5.7l-1.71,0.94l1.38,9.38l-5.18,2.83l0.35,5.85l5.3,0.63l4.17,1.44l8.24,-1.84l7.33,3.27l7.49,-7.19l-0.06,-3.02l-4.79,0.48l-0.39,-2.84l3.92,-3.83l1.33,-5.15l4.33,-3.83l2.66,-4.76l-2.32,-7.1l1.94,-2.65l-3.86,-1.89l8.49,-1.63l1.79,-3.15l5.78,-2.6l4.8,-13.47l4.57,-4.94l6.62,-11.12l-6.1,0.1l2.54,-4.3l6.78,-3.99l6.84,-8.9l0.12,-5.73l-5.13,-6.04l-6.02,-2.93l-7.49,-1.82l-6.07,-1.49l-6.07,-1.5l-8.1,3.98l-1.49,-2.53l-8.57,0.98l-5.03,2.57l-3.7,3.65l-2.13,11.74L239,24.52l-3.48,-1.14l-4.12,7.97l-5.5,3.35l-3.27,0.66l-4.17,3.84l0.61,6.65L222.35,51.34zM296.75,316.34l-0.98,-1.98l-1.06,1.26l0.7,1.36l3.56,1.71l1.04,-0.26l1.38,-1.66l-2.6,0.11L296.75,316.34zM239.75,238.48l0.61,1.63l1.98,0.14l3.28,-3.34l0.06,-1.19l-3.85,-0.06L239.75,238.48zM301.88,304.92l-2.87,-1.8l-3.69,-1.09l-0.97,0.37l2.61,2.04l3.63,1.34l1.36,-0.08L301.88,304.92zM326.76,309.71l-0.36,-2.24l-1.96,0.72l0.87,-3.11l-2.8,-1.32l-1.29,1.05l-2.49,-1.18l0.98,-1.51l-1.88,-0.93l-1.83,1.47l1.86,-3.82l1.5,-2.8l0.54,-1.22l-1.3,-0.2l-2.43,1.55l-1.74,2.53l-2.9,6.92l-2.35,2.56l1.22,1.14l-1.75,1.47l0.43,1.23l5.44,0.13l3.01,-0.25l2.69,1.01l-1.98,1.93l1.67,0.14l3.25,-3.58l0.78,0.53l-0.61,3.37l1.84,0.77l1.27,-0.15l1.18,-3.61L326.76,309.71zM305.57,314.47l-2.81,4.56l-4.63,0.58l-3.64,-2.01l-0.92,-3.07l-0.89,-4.46l2.65,-2.83l-2.48,-2.09l-4.19,0.43l-5.88,3.53l-4.5,5.45l-2.38,0.67l3.23,-3.8l4.04,-5.57l3.57,-1.9l2.35,-3.11l2.9,-0.3l4.21,0.03l6,0.92l4.74,-0.71l3.53,-3.62l4.62,-1.59l2.01,-1.58l2.04,-1.71l-0.2,-5.19l-1.13,-1.77l-2.18,-0.63l-1.11,-4.05l-1.8,-1.55l-4.47,-1.26l-2.52,-2.82l-3.73,-2.83l1.13,-3.2l-3.1,-6.26l-3.65,-6.89l-2.18,-4.98l-1.86,2.61l-2.68,6.05l-4.06,2.97l-2.03,-3.16l-2.56,-0.85l-0.93,-6.99l0.08,-4.8l-5,-0.44l-0.85,-2.27l-3.45,-3.44l-2.61,-2.04l-2.32,1.58l-2.88,-0.58l-4.81,-1.65l-1.95,1.4l0.94,9.18l1.22,5.12l-3.31,5.75l3.41,4.02l1.9,4.44l0.23,3.42l-1.55,3.5l-3.18,3.46l-4.49,2.28l1.98,2.53l1.46,7.4l-1.52,4.68l-2.16,1.46l-4.17,-4.28l-2.03,-5.17l-0.87,-4.76l0.46,-4.19l-3.05,-0.47l-4.63,-0.28l-2.97,-2.08l-3.51,-1.37l-2.01,-2.38l-2.8,-1.94l-5.21,-2.23l-3.92,1.02l-1.31,-3.95l-1.26,-4.99l-4.12,-0.9l0.15,-6.41l1.09,-4.48l3.04,-6.6l3.43,-4.9l3.26,-0.77l0.19,-4.05l2.21,-2.68l4.01,-0.42l3.25,-4.39l0.82,-2.9l2.7,-5.73l0.84,-3.5l2.9,2.11l3.9,-1.08l5.49,-4.96l0.36,-3.54l-1.98,-3.98l2.09,-4.06l-0.17,-3.87l-3.76,-3.95l-4.14,-1.19l-3.98,-0.62l-0.15,8.71l-2.04,6.56l-2.93,5.3l-2.71,-4.95l0.84,-5.61l-3.35,-5.02l-3.75,6.09l0.01,-7.99l-5.21,-1.63l2.49,-4.01l-3.81,-9.59l-2.84,-3.91l-3.7,-1.44l-3.32,6.43l-0.22,9.34l3.27,3.29l3,4.91l-1.27,7.71l-2.26,-0.2l-1.78,5.88l0.02,-7l-4.34,-2.58l-2.49,1.33l0.32,4.67l-4.09,-0.18l-4.35,1.17l-4.95,-3.35l-3.13,0.6l-2.82,-4.11l-2.26,-1.84l-2.24,0.77l-3.41,0.35l-1.81,2.61l2.86,3.19l-3.05,3.72l-2.99,-4.42l-2.39,1.3l-7.57,0.87l-5.07,-1.59l3.94,-3.74l-3.78,-3.9l-2.75,0.5l-3.86,-1.32l-6.56,-2.89l-4.29,-3.37l-3.4,-0.47l-1.06,2.36l-3.44,1.31l-0.38,-6.15l-3.73,5.5l-4.74,-7.32l-1.94,-0.89l-0.63,3.91l-2.09,1.9l-1.93,-3.39l-4.59,2.05l-4.2,3.55l-4.17,-0.98l-3.4,2.5l-2.46,3.28l-2.92,-0.72l-4.41,-3.8l-5.23,-1.94l-0.02,27.65l-0.01,35.43l2.76,0.17l2.73,1.56l1.96,2.44l2.49,3.6l2.73,-3.05l2.81,-1.79l1.49,2.85l1.89,2.23l2.57,2.42l1.75,3.79l2.87,5.88l4.77,3.2l0.08,3.12l-1.56,2.35l0.06,2.48l3.39,3.45l0.49,3.76l3.59,1.96l-0.4,2.79l1.56,3.96l5.08,1.82l2,1.89l5.43,4.23l0.38,0.01h7.96h8.32h2.76h8.55h8.27h8.41l8.42,0l9.53,0l9.59,0l5.8,0l0.01,-1.64l0.95,-0.02l0.5,2.35l0.87,0.72l1.96,0.26l2.86,0.67l2.72,1.3l2.27,-0.55l3.45,1.09l1.14,-1.66l1.59,-0.66l0.62,-1.03l0.63,-0.55l2.61,0.86l1.93,0.1l0.67,0.57l0.94,2.38l3.15,0.63l-0.49,1.18l1.11,1.21l-0.48,1.56l1.18,0.51l-0.59,1.37l0.75,0.13l0.53,-0.6l0.55,0.9l2.1,0.5l2.13,0.04l2.27,0.41l2.51,0.78l0.91,1.26l1.82,3.04l-0.9,1.3l-2.28,-0.54l-1.42,-2.44l0.36,2.49l-1.34,2.17l0.15,1.84l-0.23,1.07l-1.81,1.27l-1.32,2.09l-0.62,1.32l1.54,0.24l2.08,-1.2l1.23,-1.06l0.83,-0.17l1.54,0.38l0.75,-0.59l1.37,-0.48l2.44,-0.47v0l0,0l-0.25,-1.15l-0.13,0.04l-0.86,0.2l-1.12,-0.36l0.84,-1.32l0.85,-0.46l1.98,-0.56l2.37,-0.53l1.24,0.73l0.78,-0.85l0.89,-0.54l0.6,0.29l0.03,0.06l2.87,-2.73l1.27,-0.73l4.26,-0.03l5.17,0l0.28,-0.98l0.9,-0.2l1.19,-0.62l1,-1.82l0.86,-3.15l2.14,-3.1l0.93,1.08l1.88,-0.7l1.25,1.19l0,5.52l1.83,2.25l3.12,-0.48l4.49,-0.13l-4.87,3.26l0.11,3.29l2.13,0.28l3.13,-2.79l2.78,-1.58l6.21,-2.35l3.47,-2.62l-1.81,-1.46L305.57,314.47zM251.91,243.37l1.1,-3.12l-0.71,-1.23l-1.15,-0.13l-1.08,1.8l-0.13,0.41l0.74,1.77L251.91,243.37zM109.25,279.8L109.25,279.8l1.56,-2.35L109.25,279.8zM105.85,283.09l-2.69,0.38l-1.32,-0.62l-0.17,1.52l0.52,2.07l1.42,1.46l1.04,2.13l1.69,2.1l1.12,0.01l-2.44,-3.7L105.85,283.09z'),
(26,	'CD',	'Democratic Republic of Congo',	'M561.71,453.61L561.54,456.87L562.66,457.24L561.76,458.23L560.68,458.97L559.61,460.43L559.02,461.72L558.86,463.96L558.21,465.02L558.19,467.12L557.38,467.9L557.28,469.56L556.89,469.77L556.63,471.3L557.34,472.56L557.52,475.93L558.02,478.5L557.74,479.96L558.3,481.58L559.93,483.15L561.44,486.7L560.34,486.41L556.57,486.89L555.82,487.22L555.02,489.02L555.65,490.27L555.15,493.62L554.8,496.47L555.56,496.98L557.52,498.08L558.29,497.57L558.53,500.65L556.38,500.62L555.23,499.05L554.2,497.83L552.05,497.43L551.42,495.94L549.7,496.84L547.46,496.44L546.52,495.15L544.74,494.89L543.43,494.96L543.27,494.08L542.3,494.01L541.02,493.84L539.29,494.26L538.07,494.19L537.37,494.45L537.52,491.08L536.59,490.03L536.38,488.3L536.79,486.6L536.23,485.51L536.18,483.75L532.77,483.77L533.02,482.76L531.59,482.77L531.44,483.26L529.7,483.37L528.99,485L528.57,485.71L527.02,485.31L526.1,485.71L524.24,485.93L523.17,484.46L522.53,483.55L521.72,481.87L521.03,479.78L512.76,479.75L511.77,480.08L510.96,480.03L509.8,480.41L509.41,479.54L510.12,479.24L510.21,478.02L510.67,477.3L511.69,476.72L512.43,477L513.39,475.93L514.91,475.96L515.09,476.75L516.14,477.25L517.79,475.49L519.42,474.13L520.13,473.24L520.04,470.94L521.26,468.23L522.54,466.8L524.39,465.46L524.71,464.57L524.78,463.55L525.24,462.58L525.09,461L525.44,458.53L525.99,456.79L526.83,455.3L526.99,453.62L527.24,451.67L528.34,450.25L529.84,449.35L532.15,450.3L533.93,451.33L535.98,451.61L538.07,452.15L538.91,450.47L539.3,450.25L540.57,450.53L543.7,449.14L544.8,449.73L545.71,449.65L546.13,448.97L547.17,448.73L549.28,449.02L551.08,449.08L552.01,448.79L553.7,451.1L554.96,451.43L555.71,450.96L557.01,451.15L558.57,450.56L559.24,451.75z'),
(27,	'CF',	'Central African Republic',	'M518.09,442.66L520.41,442.44L520.93,441.72L521.39,441.78L522.09,442.41L525.62,441.34L526.81,440.24L528.28,439.25L528,438.26L528.79,438L531.5,438.18L534.14,436.87L536.16,433.78L537.59,432.64L539.36,432.15L539.68,433.37L541.3,435.14L541.3,436.29L540.85,437.47L541.03,438.34L542,439.15L544.14,440.39L545.67,441.52L545.7,442.44L547.58,443.9L548.75,445.11L549.46,446.79L551.56,447.9L552.01,448.79L551.08,449.08L549.28,449.02L547.17,448.73L546.13,448.97L545.71,449.65L544.8,449.73L543.7,449.14L540.57,450.53L539.3,450.25L538.91,450.47L538.07,452.15L535.98,451.61L533.93,451.33L532.15,450.3L529.84,449.35L528.34,450.25L527.24,451.67L526.99,453.62L525.19,453.46L523.29,452.99L521.62,454.47L520.15,457.07L519.85,456.26L519.73,454.99L518.45,454.09L517.41,452.65L517.17,451.65L515.85,450.19L516.07,449.36L515.79,448.18L516.01,446.01L516.68,445.5z'),
(28,	'CG',	'Republic of Congo',	'M511.69,476.72L510.64,475.76L509.79,476.23L508.66,477.43L506.36,474.48L508.49,472.94L507.44,471.09L508.4,470.39L510.29,470.05L510.51,468.81L512.01,470.15L514.49,470.27L515.35,468.95L515.7,467.1L515.39,464.92L514.07,463.28L515.28,460.05L514.58,459.5L512.5,459.72L511.71,458.29L511.92,457.07L515.45,457.18L517.72,457.91L519.95,458.57L520.15,457.07L521.62,454.47L523.29,452.99L525.19,453.46L526.99,453.62L526.83,455.3L525.99,456.79L525.44,458.53L525.09,461L525.24,462.58L524.78,463.55L524.71,464.57L524.39,465.46L522.54,466.8L521.26,468.23L520.04,470.94L520.13,473.24L519.42,474.13L517.79,475.49L516.14,477.25L515.09,476.75L514.91,475.96L513.39,475.93L512.43,477z'),
(29,	'CH',	'Switzerland',	'M502.15,312.34L502.26,313.08L501.83,314.09L503.1,314.83L504.53,314.94L504.31,316.61L503.08,317.3L501,316.79L500.39,318.42L499.06,318.55L498.57,317.91L497,319.27L495.65,319.46L494.44,318.6L493.48,316.83L492.14,317.47L492.18,315.63L494.23,313.32L494.14,312.27L495.42,312.66L496.19,311.95L498.57,311.98L499.15,311.08z'),
(30,	'CI',	'Côte d\'Ivoire',	'M467.24,449.46L465.97,449.49L464.01,448.94L462.22,448.97L458.89,449.46L456.95,450.27L454.17,451.29L453.63,451.22L453.84,448.92L454.11,448.57L454.03,447.46L452.84,446.29L451.95,446.1L451.13,445.33L451.74,444.09L451.46,442.73L451.59,441.91L452.04,441.91L452.2,440.68L451.98,440.14L452.25,439.75L453.29,439.41L452.6,437.15L451.95,435.99L452.18,435.02L452.74,434.81L453.1,434.55L453.88,434.97L456.04,435L456.56,434.17L457.04,434.23L457.85,433.91L458.29,435.12L458.94,434.76L460.1,434.34L461.36,434.96L461.85,435.89L463.11,436.49L464.09,435.78L465.41,435.67L467.33,436.4L468.07,440.41L466.89,442.77L466.16,445.94L467.37,448.35z'),
(31,	'CL',	'Chile',	'M282.81,636.73l0,10.57l3,0l1.69,0.13l-0.93,1.98l-2.4,1.53l-1.38,-0.16l-1.66,-0.4l-2.04,-1.48l-2.94,-0.71l-3.53,-2.71l-2.86,-2.57l-3.86,-5.25l2.31,0.97l3.94,3.13l3.72,1.7l1.45,-2.17l0.91,-3.2l2.58,-1.91L282.81,636.73zM283.97,524.72l1.1,4.15l2.02,-0.41l0.34,0.76l-0.96,3.16l-3.05,1.51l0.09,5.14l-0.59,1l0.84,1.23l-1.98,1.95l-1.84,2.96l-1,2.9l0.27,3.11l-1.73,3.34l1.29,5.69l0.73,0.61l-0.01,3.09l-1.6,3.31l0.06,2.87l-2.12,2.26l0.01,3.22l0.85,3.46l-1.68,1.3l-0.75,3.22l-0.66,3.75l0.47,4.54l-1.13,0.77l0.65,4.4l1.27,1.46l-0.92,1.63l1.3,0.78l0.3,1.48l-1.22,0.75l0.3,2.33l-1.02,5.35l-1.49,3.52l0.33,2.11l-0.89,2.68l-2.15,1.88l0.25,4.6l0.99,1.6l1.87,-0.28l-0.05,3.33l1.16,2.63l6.78,0.61l2.6,0.71l-2.49,-0.03l-1.35,1.13l-2.53,1.67l-0.45,4.38l-1.19,0.11l-3.16,-1.54l-3.21,-3.25l0,0l-3.49,-2.63l-0.88,-2.87l0.79,-2.62l-1.41,-2.94l-0.36,-7.34l1.19,-4.03l2.96,-3.19l-4.26,-1.19l2.67,-3.57l0.95,-6.56l3.12,1.37l1.46,-7.97l-1.88,-1l-0.88,4.75l-1.77,-0.54l0.88,-5.42l0.96,-6.84l1.29,-2.48l-0.81,-3.5l-0.23,-3.98l1.18,-0.11l1.72,-5.6l1.94,-5.43l1.19,-4.97l-0.65,-4.91l0.84,-2.67l-0.34,-3.96l1.64,-3.87l0.51,-6.04l0.9,-6.37l0.88,-6.75l-0.21,-4.87l-0.58,-4.15l1.44,-0.75l0.75,-1.5l1.37,1.99l0.37,2.12l1.47,1.25l-0.88,2.87L283.97,524.72z'),
(32,	'CM',	'Cameroon',	'M511.92,457.07L511.57,456.92L509.91,457.28L508.2,456.9L506.87,457.09L502.31,457.02L502.72,454.82L501.62,452.98L500.34,452.5L499.77,451.25L499.05,450.85L499.09,450.08L499.81,448.1L501.14,445.4L501.95,445.37L503.62,443.73L504.69,443.69L506.26,444.84L508.19,443.89L508.45,442.73L509.08,441.59L509.51,440.17L511.01,439.01L511.58,437.04L512.17,436.41L512.57,434.94L513.31,433.13L515.67,430.93L515.82,429.98L516.13,429.47L515.02,428.33L515.11,427.43L515.9,427.26L517.01,429.09L517.2,430.98L517.1,432.87L518.62,435.44L517.06,435.41L516.27,435.61L514.99,435.33L514.38,436.66L516.03,438.31L517.25,438.79L517.65,439.96L518.53,441.89L518.09,442.66L516.68,445.5L516.01,446.01L515.79,448.18L516.07,449.36L515.85,450.19L517.17,451.65L517.41,452.65L518.45,454.09L519.73,454.99L519.85,456.26L520.15,457.07L519.95,458.57L517.72,457.91L515.45,457.18z'),
(33,	'CN',	'China',	'M784.63,410.41l-2.42,1.41l-2.3,-0.91l-0.08,-2.53l1.38,-1.34l3.06,-0.83l1.61,0.07l0.63,1.13l-1.23,1.3L784.63,410.41zM833.19,302.89l4.88,1.38l3.32,3.03l1.13,3.95l4.26,0l2.43,-1.65l4.63,-1.24l-1.47,3.76l-1.09,1.51l-0.96,4.46l-1.89,3.89l-3.4,-0.7l-2.41,1.4l0.74,3.36l-0.4,4.55l-1.43,0.1l0.02,1.93l-1.81,-2.24l-1.11,2.13l-4.33,1.62l0.44,1.97l-2.42,-0.14l-1.33,-1.17l-1.93,2.64l-3.09,1.98l-2.28,2.35l-3.92,1.06l-2.06,1.69l-3.02,0.98l1.49,-1.67l-0.59,-1.41l2.22,-2.45l-1.48,-1.93l-2.44,1.3l-3.17,2.54l-1.73,2.34l-2.75,0.17l-1.43,1.68l1.48,2.41l2.29,0.58l0.09,1.58l2.22,1.02l3.14,-2.51l2.49,1.37l1.81,0.09l0.46,1.84l-3.97,0.97l-1.31,1.87l-2.73,1.73l-1.44,2.39l3.02,1.86l1.1,3.31l1.71,3.05l1.9,2.53l-0.05,2.43l-1.76,0.89l0.67,1.73l1.65,1l-0.43,2.61l-0.71,2.52l-1.57,0.28l-2.05,3.41l-2.27,4.09l-2.6,3.68l-3.86,2.82l-3.9,2.55l-3.16,0.35l-1.71,1.34l-0.97,-0.98l-1.59,1.5l-3.92,1.5l-2.97,0.46l-0.96,3.15l-1.55,0.17l-0.74,-2.16l0.66,-1.16l-3.76,-0.96l-1.33,0.49l-2.82,-0.78l-1.33,-1.22l0.44,-1.74l-2.56,-0.55l-1.35,-1.14l-2.39,1.62l-2.73,0.35l-2.24,-0.02l-1.5,0.74l-1.45,0.44l0.42,3.43l-1.5,-0.08l-0.25,-0.7l-0.08,-1.24l-2.06,0.87l-1.21,-0.55l-2.08,-1.13l0.82,-2.51l-1.78,-0.59l-0.67,-2.8l-2.96,0.51l0.34,-3.63l2.66,-2.58l0.11,-2.57l-0.08,-2.4l-1.22,-0.75l-0.94,-1.86l-1.64,0.24l-3.02,-0.47l0.95,-1.33l-1.31,-1.99l-2,1.35L740.4,378l-3.23,2.03l-2.55,2.36l-2.26,0.39l-1.23,-0.85l-1.48,-0.08l-2,-0.73l-1.51,0.8l-1.85,2.34l-0.24,-2.48l-1.71,0.66l-3.27,-0.31l-3.17,-0.73l-2.28,-1.39l-2.18,-0.63l-0.94,-1.53l-1.58,-0.46l-2.83,-2.09l-2.25,-0.99l-1.16,0.77l-3.9,-2.26l-2.75,-2.07l-0.79,-3.63l2.01,0.44l0.09,-1.69l-1.12,-1.71l0.28,-2.74l-3.01,-3.99l-4.61,-1.39l-0.83,-2.66l-2.07,-1.63l-0.5,-1.01l-0.42,-2.01l0.1,-1.38l-1.7,-0.81l-0.92,0.36l-0.71,-3.32l0.8,-0.83l-0.39,-0.85l2.68,-1.73l1.94,-0.72l2.97,0.49l1.06,-2.35l3.6,-0.44l1,-1.48l4.42,-2.03l0.39,-0.85l-0.22,-2.17l1.92,-1l-2.52,-6.75l5.55,-1.58l1.44,-0.89l2.02,-7.26l5.56,1.35l1.56,-1.86l0.13,-4.19l2.33,-0.39l2.13,-2.83l1.1,-0.35l0.74,2.97l2.36,2.23l4,1.57l1.93,3.32l-1.08,4.73l1.01,1.73l3.33,0.68l3.78,0.55l3.39,2.45l1.73,0.43l1.28,3.57l1.65,2.27l3.09,-0.09l5.79,0.85l3.73,-0.53l2.77,0.57l4.15,2.29l3.39,0l1.24,1.16l3.26,-2.01l4.53,-1.31l4.2,-0.14l3.28,-1.34l2.01,-2.05l1.96,-1.3l-0.45,-1.28l-0.9,-1.5l1.47,-2.54l1.58,0.36l2.88,0.8l2.79,-2.1l4.28,-1.55l2.05,-2.66l1.97,-1.16l4.07,-0.54l2.21,0.46l0.31,-1.45l-2.54,-2.89l-2.25,-1.33l-2.16,1.54l-2.77,-0.65l-1.59,0.53l-0.72,-1.71l1.98,-4.23l1.37,-3.25l3.37,1.63l3.95,-2.74l-0.03,-1.93l2.53,-4.73l1.56,-1.45l-0.04,-2.52l-1.54,-1.1l2.32,-2.31l3.48,-0.84l3.72,-0.13l4.2,1.39l2.46,1.71l1.73,4.61l1.05,1.94l0.98,2.73L833.19,302.89z'),
(34,	'CO',	'Colombia',	'M263.92,463.81L262.72,463.15L261.34,462.23L260.54,462.67L258.16,462.28L257.48,461.08L256.96,461.13L254.15,459.54L253.77,458.67L254.82,458.46L254.7,457.07L255.35,456.06L256.74,455.87L257.93,454.12L259,452.66L257.96,451.99L258.49,450.37L257.86,447.81L258.46,447.08L258.02,444.71L256.88,443.21L257.24,441.85L258.15,442.05L258.68,441.21L258.03,439.56L258.37,439.14L259.81,439.23L261.92,437.26L263.07,436.96L263.1,436.03L263.62,433.64L265.23,432.32L266.99,432.27L267.21,431.68L269.41,431.91L271.62,430.48L272.71,429.84L274.06,428.47L275.06,428.64L275.79,429.39L275.25,430.35L273.45,430.83L272.74,432.25L271.65,433.06L270.84,434.12L270.49,436.13L269.72,437.79L271.16,437.97L271.52,439.27L272.14,439.89L272.36,441.02L272.03,442.06L272.13,442.65L272.82,442.88L273.49,443.86L277.09,443.59L278.72,443.95L280.7,446.36L281.83,446.06L283.85,446.21L285.45,445.89L286.44,446.38L285.93,447.88L285.31,448.82L285.09,450.83L285.65,452.68L286.45,453.51L286.54,454.14L285.12,455.53L286.14,456.14L286.89,457.12L287.74,459.89L287.21,460.24L286.67,458.59L285.89,457.71L284.96,458.67L279.5,458.61L279.53,460.35L281.17,460.64L281.08,461.71L280.52,461.42L278.94,461.88L278.93,463.9L280.17,464.92L280.61,466.51L280.54,467.72L279.28,475.37L277.88,473.88L277.04,473.82L278.85,470.98L276.7,469.67L275.02,469.91L274.01,469.43L272.46,470.17L270.37,469.82L268.72,466.9L267.42,466.18L266.53,464.86L264.67,463.54z'),
(35,	'CR',	'Costa Rica',	'M242.63,440.4L241.11,439.77L240.54,439.18L240.86,438.69L240.76,438.07L239.98,437.39L238.88,436.84L237.91,436.48L237.73,435.65L236.99,435.14L237.17,435.97L236.61,436.64L235.97,435.86L235.07,435.58L234.69,435.01L234.71,434.15L235.08,433.25L234.29,432.85L234.93,432.31L235.35,431.94L237.2,432.69L237.84,432.32L238.73,432.56L239.2,433.14L240.02,433.33L240.69,432.73L241.41,434.27L242.49,435.41L243.81,436.62L242.72,436.87L242.74,438L243.32,438.42L242.9,438.76L243.01,439.27L242.78,439.84z'),
(36,	'CU',	'Cuba',	'M244.58,396.94L247.01,397.16L249.21,397.19L251.84,398.22L252.96,399.33L255.58,398.99L256.57,399.69L258.95,401.56L260.69,402.91L261.61,402.87L263.29,403.48L263.08,404.32L265.15,404.44L267.27,405.66L266.94,406.35L265.07,406.73L263.18,406.88L261.25,406.64L257.24,406.93L259.12,405.27L257.98,404.5L256.17,404.3L255.2,403.44L254.53,401.74L252.95,401.85L250.33,401.05L249.49,400.42L245.84,399.95L244.86,399.36L245.91,398.61L243.16,398.46L241.15,400.02L239.98,400.06L239.58,400.8L238.2,401.13L237,400.84L238.48,399.91L239.08,398.82L240.35,398.15L241.78,397.56L243.91,397.27z'),
(37,	'CY',	'Cyprus',	'M570.31,358.29L572.2,356.83L569.65,357.85L567.63,357.8L567.23,358.63L567.03,358.65L565.7,358.77L566.35,360.14L567.72,360.58L570.6,359.2L570.51,358.93z'),
(38,	'CZ',	'Czech Republic',	'M522.81,307.86L521.51,307.06L520.2,307.28L518.02,305.98L517.03,306.3L515.46,308.04L513.37,306.67L511.79,304.84L510.36,303.8L510.06,301.98L509.57,300.68L511.61,299.73L512.65,298.63L514.66,297.77L515.37,296.93L516.11,297.44L517.36,296.97L518.69,298.4L520.78,298.79L520.61,300L522.13,300.9L522.55,299.77L524.47,300.26L524.74,301.63L526.82,301.89L528.11,304.02L527.28,304.03L526.84,304.8L526.2,304.99L526.02,305.96L525.48,306.17L525.4,306.56L524.45,307L523.2,306.93z'),
(39,	'DE',	'Germany',	'M503.07,278.92L503.12,280.8L505.96,281.92L505.93,283.62L508.78,282.72L510.35,281.41L513.52,283.3L514.84,284.81L515.5,287.2L514.72,288.45L515.73,290.1L516.43,292.55L516.21,294.11L517.36,296.97L516.11,297.44L515.37,296.93L514.66,297.77L512.65,298.63L511.61,299.73L509.57,300.68L510.06,301.98L510.36,303.8L511.79,304.84L513.37,306.67L512.39,308.62L511.38,309.16L511.78,311.88L511.51,312.58L510.64,311.73L509.3,311.61L507.29,312.35L504.82,312.17L504.42,313.26L503,312.12L502.15,312.34L499.15,311.08L498.57,311.98L496.19,311.95L496.54,308.97L497.96,306.07L493.92,305.29L492.6,304.16L492.76,302.27L492.2,301.29L492.52,298.32L492.04,293.63L493.73,293.63L494.44,291.92L495.14,287.69L494.61,286.11L495.16,285.11L497.5,284.85L498.02,285.89L499.93,283.56L499.29,281.77L499.16,279.02L501.28,279.66z'),
(40,	'DJ',	'Djibouti',	'M596.05,427.72L596.71,428.6L596.62,429.79L595.02,430.47L596.23,431.24L595.19,432.76L594.57,432.26L593.9,432.46L592.33,432.41L592.28,431.55L592.07,430.76L593.01,429.43L594,428.17L595.2,428.42z'),
(41,	'DK',	'Denmark',	'M510.83,275.84l-1.68,3.97l-2.93,-2.76l-0.39,-2.05l4.11,-1.66L510.83,275.84zM505.85,271.59l-0.69,1.9l-0.83,-0.55l-2.02,3.59l0.76,2.39l-1.79,0.74l-2.12,-0.64l-1.14,-2.72l-0.08,-5.12l0.47,-1.38l0.8,-1.54l2.47,-0.32l0.98,-1.43l2.26,-1.47l-0.1,2.68l-0.83,1.68l0.34,1.43L505.85,271.59z'),
(42,	'DO',	'Dominican Republic',	'M274.18,407.35L274.53,406.84L276.72,406.86L278.38,407.62L279.12,407.54L279.63,408.59L281.16,408.53L281.07,409.41L282.32,409.52L283.7,410.6L282.66,411.8L281.32,411.16L280.04,411.28L279.12,411.14L278.61,411.68L277.53,411.86L277.11,411.14L276.18,411.57L275.06,413.57L274.34,413.11L274.19,412.27L274.25,411.47L273.53,410.59L274.21,410.09L274.43,408.96z'),
(43,	'DZ',	'Algeria',	'M508.9,396.08L499.29,401.83L491.17,407.68L487.22,409L484.11,409.29L484.08,407.41L482.78,406.93L481.03,406.08L480.37,404.69L470.91,398.14L461.45,391.49L450.9,383.96L450.96,383.35L450.96,383.14L450.93,379.39L455.46,377.03L458.26,376.54L460.55,375.68L461.63,374.06L464.91,372.77L465.03,370.36L466.65,370.07L467.92,368.86L471.59,368.3L472.1,367.02L471.36,366.31L470.39,362.78L470.23,360.73L469.17,358.55L471.86,356.68L474.9,356.08L476.67,354.65L479.37,353.6L484.12,352.98L488.76,352.69L490.17,353.21L492.81,351.84L495.81,351.81L496.95,352.62L498.86,352.41L498.29,354.2L498.74,357.48L498.08,360.3L496.35,362.18L496.6,364.71L498.89,366.69L498.92,367.5L500.64,368.83L501.84,374.69L502.75,377.53L502.9,379.01L502.41,381.6L502.61,383.04L502.25,384.76L502.5,386.73L501.38,388.02L503.04,390.28L503.15,391.6L504.14,393.31L505.45,392.75L507.67,394.17z'),
(44,	'EC',	'Ecuador',	'M250.1,472.87L251.59,470.79L250.98,469.57L249.91,470.87L248.23,469.64L248.8,468.86L248.33,466.33L249.31,465.91L249.83,464.18L250.89,462.38L250.69,461.25L252.23,460.65L254.15,459.54L256.96,461.13L257.48,461.08L258.16,462.28L260.54,462.67L261.34,462.23L262.72,463.15L263.92,463.81L264.31,465.92L263.44,467.73L260.38,470.65L257.01,471.75L255.29,474.18L254.76,476.06L253.17,477.21L252,475.8L250.86,475.5L249.7,475.72L249.63,474.7L250.43,474.04z'),
(45,	'EE',	'Estonia',	'M543.42,264.71L543.75,261.59L542.72,262.26L540.94,260.36L540.69,257.25L544.24,255.72L547.77,254.91L550.81,255.83L553.71,255.66L554.13,256.62L552.14,259.76L552.97,264.72L551.77,266.38L549.45,266.37L547.04,264.43L545.81,263.78z'),
(46,	'EG',	'Egypt',	'M573.17,377.28L572.38,378.57L571.78,380.97L571.02,382.61L570.36,383.17L569.43,382.15L568.16,380.73L566.16,376.16L565.88,376.45L567.04,379.82L568.76,383L570.88,387.88L571.91,389.56L572.81,391.3L575.33,394.7L574.77,395.23L574.86,397.2L578.13,399.91L578.62,400.53L567.5,400.53L556.62,400.53L545.35,400.53L545.35,389.3L545.35,378.12L544.51,375.54L545.23,373.54L544.8,372.15L545.81,370.58L549.54,370.53L552.24,371.39L555.02,372.36L556.32,372.86L558.48,371.83L559.63,370.9L562.11,370.63L564.1,371.04L564.87,372.66L565.52,371.59L567.76,372.36L569.95,372.55L571.33,371.73z'),
(47,	'EH',	'Western Sahara',	'M438.57,383.06L442.19,383.07L450.94,383.1L450.94,383.1L450.94,383.1L442.19,383.07L438.57,383.06L438.46,383.15L438.41,383.19L436.63,386.39L434.77,387.53L433.75,389.44L433.69,391.09L432.94,392.88L432,393.37L430.44,395.31L429.48,397.46L429.66,398.48L428.74,400.05L427.66,400.87L427.53,402.26L427.41,403.53L428.02,402.53L439,402.55L438.47,398.2L439.16,396.65L441.78,396.38L441.69,388.52L450.9,388.69L450.9,383.96L450.96,383.35L450.96,383.14z'),
(48,	'ER',	'Eritrea',	'M594,428.17L593.04,427.24L591.89,425.57L590.65,424.65L589.92,423.65L587.48,422.5L585.56,422.47L584.88,421.86L583.24,422.54L581.54,421.23L580.66,423.38L577.4,422.78L577.1,421.63L578.31,417.38L578.58,415.45L579.46,414.55L581.53,414.07L582.95,412.4L584.58,415.78L585.35,418.45L586.89,419.86L590.71,422.58L592.27,424.22L593.79,425.88L594.67,426.86L596.05,427.72L595.2,428.42z'),
(49,	'ES',	'Spain',	'M449.92,334.56L450.06,331.88L448.92,330.22L452.88,327.45L456.31,328.15L460.08,328.12L463.06,328.78L465.39,328.58L469.92,328.7L471.04,330.19L476.2,331.92L477.22,331.1L480.38,332.82L483.63,332.33L483.78,334.52L481.12,337.01L477.53,337.79L477.28,339.03L475.55,341.06L474.47,344.02L475.56,346.07L473.94,347.67L473.34,349.97L471.22,350.67L469.23,353.36L465.68,353.41L463,353.35L461.25,354.57L460.18,355.88L458.8,355.59L457.77,354.42L456.97,352.42L454.35,351.88L454.12,350.72L455.16,349.4L455.54,348.44L454.58,347.38L455.35,345.03L454.23,342.86L455.44,342.56L455.55,340.84L456.01,340.31L456.04,337.43L457.34,336.43L456.56,334.55L454.92,334.42L454.44,334.89L452.79,334.9L452.08,333.06L450.94,333.61z'),
(50,	'ET',	'Ethiopia',	'M581.54,421.23L583.24,422.54L584.88,421.86L585.56,422.47L587.48,422.5L589.92,423.65L590.65,424.65L591.89,425.57L593.04,427.24L594,428.17L593.01,429.43L592.07,430.76L592.28,431.55L592.33,432.41L593.9,432.46L594.57,432.26L595.19,432.76L594.58,433.77L595.62,435.33L596.65,436.69L597.72,437.7L606.89,441.04L609.25,441.02L601.32,449.44L597.67,449.56L595.17,451.53L593.38,451.58L592.61,452.46L590.69,452.46L589.56,451.52L587,452.69L586.17,453.85L584.3,453.63L583.68,453.31L583.02,453.38L582.14,453.36L578.59,450.98L576.64,450.98L575.68,450.07L575.68,448.5L574.22,448.03L572.57,444.98L571.29,444.33L570.79,443.21L569.37,441.84L567.65,441.64L568.61,440.03L570.09,439.96L570.51,439.1L570.48,436.57L571.31,433.61L572.63,432.81L572.92,431.65L574.12,429.48L575.81,428.06L576.95,425.25L577.4,422.78L580.66,423.38z'),
(51,	'FK',	'Falkland Islands',	'M303.66,633.13L307.02,630.44L309.41,631.56L311.09,629.77L313.33,631.78L312.49,633.36L308.7,634.72L307.44,633.13L305.06,635.18z'),
(52,	'FI',	'Finland',	'M555.42,193.1L555.01,198.5L559.31,203.49L556.72,208.97L559.98,216.93L558.09,222.69L560.62,227.55L559.47,231.69L563.62,235.95L562.56,239.05L559.96,242.5L553.96,249.91L548.87,250.36L543.94,252.43L539.38,253.61L537.75,250.54L535.04,248.67L535.66,242.95L534.3,237.54L535.64,233.96L538.18,230.02L544.59,223L546.47,221.61L546.17,218.77L542.27,215.55L541.33,212.85L541.25,201.73L536.88,196.58L533.14,192.77L534.82,190.69L537.94,194.84L541.6,194.45L544.61,196.32L547.28,192.88L548.66,187.03L553.01,184.25L556.61,187.51z'),
(53,	'FJ',	'Fiji',	'M980.53,508.61l-0.35,1.4l-0.23,0.16l-1.78,0.72l-1.79,0.61l-0.36,-1.09l1.4,-0.6l0.89,-0.16l1.64,-0.91L980.53,508.61zM974.69,512.92l-1.27,-0.36l-1.08,1l0.27,1.29l1.55,0.36l1.74,-0.4l0.46,-1.53l-0.96,-0.84L974.69,512.92z'),
(54,	'FR',	'France',	'M502.06,333.54l-0.93,2.89l-1.27,-0.76l-0.65,-2.53l0.57,-1.41l1.81,-1.45L502.06,333.54zM485.31,300.19l1.96,2.06l1.44,-0.34l2.45,1.97l0.63,0.37l0.81,-0.09l1.32,1.12l4.04,0.79l-1.42,2.9l-0.36,2.98l-0.77,0.71l-1.28,-0.38l0.09,1.05l-2.05,2.3l-0.04,1.84l1.34,-0.63l0.96,1.77l-0.12,1.13l0.83,1.5l-0.97,1.21l0.72,3.04l1.52,0.49l-0.32,1.68l-2.54,2.17l-5.53,-1.04l-4.08,1.24l-0.32,2.29l-3.25,0.49l-3.15,-1.72l-1.02,0.82l-5.16,-1.73l-1.12,-1.49l1.45,-2.32l0.53,-7.88l-2.89,-4.26l-2.07,-2.09l-4.29,-1.6l-0.28,-3.07l3.64,-0.92l4.71,1.09l-0.89,-4.84l2.65,1.85l6.53,-3.37l0.84,-3.61l2.45,-0.9l0.41,1.56l1.3,0.07L485.31,300.19z'),
(55,	'GA',	'Gabon',	'M506.36,474.48L503.48,471.66L501.62,469.36L499.92,466.48L500.01,465.56L500.62,464.66L501.3,462.64L501.87,460.57L502.82,460.41L506.89,460.44L506.87,457.09L508.2,456.9L509.91,457.28L511.57,456.92L511.92,457.07L511.71,458.29L512.5,459.72L514.58,459.5L515.28,460.05L514.07,463.28L515.39,464.92L515.7,467.1L515.35,468.95L514.49,470.27L512.01,470.15L510.51,468.81L510.29,470.05L508.4,470.39L507.44,471.09L508.49,472.94z'),
(56,	'GB',	'United Kingdom',	'M459.38,281l-1.5,3.29l-2.12,-0.98l-1.73,0.07l0.58,-2.57l-0.58,-2.6l2.35,-0.2L459.38,281zM466.83,260.24l-3,5.73l2.86,-0.72l3.07,0.03l-0.73,4.22l-2.52,4.53l2.9,0.32l0.22,0.52l2.5,5.79l1.92,0.77l1.73,5.41l0.8,1.84l3.4,0.88l-0.34,2.93l-1.43,1.33l1.12,2.33l-2.52,2.33l-3.75,-0.04l-4.77,1.21l-1.31,-0.87l-1.85,2.06l-2.59,-0.5l-1.97,1.67l-1.49,-0.87l4.11,-4.64l2.51,-0.97l-0.02,0l-4.38,-0.75l-0.79,-1.8l2.93,-1.41l-1.54,-2.48l0.53,-3.06l4.17,0.42l0,0l0.41,-2.74l-1.88,-2.95l-0.04,-0.07l-3.4,-0.85l-0.67,-1.32l1.02,-2.2l-0.92,-1.37l-1.51,2.34l-0.16,-4.8l-1.42,-2.59l1.02,-5.36l2.18,-4.31l2.24,0.42L466.83,260.24z'),
(57,	'GE',	'Georgia',	'M591.76,335.85L592.18,334.25L591.48,331.68L589.86,330.27L588.31,329.83L587.28,328.66L587.62,328.2L589.99,328.86L594.12,329.48L597.94,331.31L598.43,332.02L600.13,331.42L602.75,332.22L603.6,333.77L605.37,334.64L604.64,335.15L606.02,337.17L605.64,337.6L604.13,337.38L602.04,336.32L601.35,336.92L597.45,337.5L594.75,335.68z'),
(58,	'GF',	'French Guiana',	'M327.89,456.41l-1.07,1.06l-1.34,0.2l-0.38,-0.78l-0.63,-0.12l-0.87,0.76l-1.22,-0.57l0.71,-1.19l0.24,-1.27l0.48,-1.2l-1.09,-1.65l-0.22,-1.91l1.46,-2.41l0.95,0.31l2.06,0.66l2.97,2.36l0.46,1.14l-1.66,2.55L327.89,456.41z'),
(59,	'GH',	'Ghana',	'M478.23,446.84L473.83,448.48L472.27,449.44L469.74,450.25L467.24,449.46L467.37,448.35L466.16,445.94L466.89,442.77L468.07,440.41L467.33,436.4L466.94,434.27L467.01,432.66L471.88,432.53L473.12,432.74L474.02,432.28L475.32,432.5L475.11,433.39L476.28,434.85L476.28,436.9L476.55,439.12L477.25,440.15L476.63,442.68L476.85,444.08L477.6,445.86z'),
(60,	'GL',	'Greenland',	'M344.13,23.91L353.55,10.3L363.39,11.37L366.96,2.42L376.87,0L399.27,3.15L416.81,21.74L411.63,30.04L400.9,30.97L385.81,33L387.22,36.64L397.15,34.4L405.59,41.31L411.04,35.19L413.37,42.34L410.29,53.31L417.43,46.38L431.04,38.83L439.45,42.64L441.02,50.76L429.59,63.42L428.01,67.32L419.05,70.18L425.54,70.97L422.26,82.48L420,92.07L420.09,107.33L423.46,115.67L419.08,116.18L414.47,120.06L419.64,126.36L420.3,135.98L417.3,137L420.93,146.15L414.71,146.9L417.96,151.04L417.04,154.55L413.09,156.06L409.18,156.09L412.69,162.57L412.73,166.7L407.18,162.87L405.74,165.36L409.52,167.65L413.2,173.13L414.26,180.08L409.26,181.7L407.1,178.44L403.63,173.46L404.59,179.33L401.34,183.74L408.72,184.09L412.59,184.54L405.07,191.57L397.45,197.7L389.25,200.31L386.16,200.35L383.26,203.22L379.36,210.85L373.33,215.74L371.39,216.03L367.65,217.7L363.63,219.29L361.22,223.41L361.18,227.97L359.77,232.13L355.19,237.08L356.32,241.79L355.06,246.64L353.63,252.2L349.68,252.54L345.54,247.91L339.93,247.88L337.21,244.7L335.34,238.9L330.48,231.22L329.06,227.07L328.68,221.18L324.79,214.91L325.8,209.74L323.93,207.21L326.7,198.56L330.92,195.71L332.03,192.45L332.62,186.19L329.41,189.05L327.89,190.24L325.37,191.38L321.93,188.77L321.74,183.22L322.84,178.74L325.44,178.62L331.16,180.87L326.34,175.44L323.83,172.43L321.04,173.67L318.7,171.48L321.83,162.98L320.13,159.45L317.9,152.71L314.53,141.8L310.96,137.63L310.99,133L303.46,126.31L297.51,125.46L290.02,125.93L283.18,126.79L279.92,123.04L275.05,115.38L282.41,111.41L288.06,110.73L276.06,107.37L269.74,101.93L270.13,96.59L280.74,89.72L291.01,82.56L292.09,76.92L284.53,71.16L286.97,64.52L296.68,52.19L300.76,50.21L299.59,41.64L306.23,36.4L314.85,33.19L323.47,33.01L326.53,39.31L333.97,27.99L340.66,35.77L344.59,37.36L350.42,43.77L343.75,33z'),
(61,	'GM',	'Gambia',	'M428.03,426.43L428.39,425.16L431.44,425.07L432.08,424.4L432.97,424.35L434.07,425.06L434.94,425.07L435.87,424.59L436.43,425.41L435.22,426.06L434,426.01L432.8,425.4L431.76,426.06L431.26,426.09L430.58,426.49z'),
(62,	'GN',	'Guinea',	'M451.59,441.91L450.8,441.84L450.23,442.97L449.43,442.96L448.89,442.36L449.07,441.23L447.9,439.51L447.17,439.82L446.57,439.89L445.8,440.05L445.83,439.02L445.38,438.28L445.47,437.46L444.86,436.27L444.08,435.26L441.84,435.26L441.19,435.79L440.41,435.85L439.93,436.46L439.61,437.25L438.11,438.49L436.88,436.82L435.79,435.71L435.07,435.35L434.37,434.78L434.06,433.53L433.65,432.91L432.83,432.44L434.08,431.06L434.93,431.11L435.66,430.63L436.28,430.63L436.72,430.25L436.48,429.31L436.79,429.01L436.84,428.04L438.19,428.07L440.21,428.77L440.83,428.7L441.04,428.39L442.56,428.61L442.97,428.45L443.13,429.5L443.58,429.49L444.31,429.11L444.77,429.21L445.55,429.93L446.75,430.16L447.52,429.54L448.43,429.16L449.1,428.76L449.66,428.84L450.28,429.46L450.62,430.25L451.77,431.44L451.19,432.17L451.08,433.09L451.68,432.81L452.03,433.15L451.88,433.99L452.74,434.81L452.18,435.02L451.95,435.99L452.6,437.15L453.29,439.41L452.25,439.75L451.98,440.14L452.2,440.68L452.04,441.91z'),
(63,	'GQ',	'Equatorial Guinea',	'M501.87,460.57L501.34,460.15L502.31,457.02L506.87,457.09L506.89,460.44L502.82,460.41z'),
(64,	'GR',	'Greece',	'M541.7,356.71l1.53,1.16l2.18,-0.2l2.09,0.24l-0.07,0.59l1.53,-0.41l-0.35,1.01l-4.04,0.29l0.03,-0.56l-3.42,-0.67L541.7,356.71zM549.85,335.75l-0.87,2.33l-0.67,0.41l-1.71,-0.1l-1.46,-0.35l-3.4,0.96l1.94,2.06l-1.42,0.59l-1.56,0l-1.48,-1.88l-0.53,0.8l0.63,2.18l1.4,1.7l-1.06,0.79l1.56,1.65l1.39,1.03l0.04,2l-2.59,-0.94l0.83,1.8l-1.78,0.37l1.06,3.08l-1.86,0.04l-2.3,-1.51l-1.05,-2.81l-0.49,-2.36l-1.09,-1.64l-1.44,-2.05l-0.19,-1.03l1.3,-1.76l0.17,-1.19l0.91,-0.53l0.06,-0.97l1.83,-0.33l1.07,-0.81l1.52,0.07l0.46,-0.65l0.53,-0.12l2.07,0.11l2.24,-1.02l1.98,1.3l2.55,-0.35l0.03,-1.86L549.85,335.75z'),
(65,	'GT',	'Guatemala',	'M222.64,424.75L221.2,424.25L219.45,424.2L218.17,423.63L216.66,422.45L216.73,421.61L217.05,420.93L216.66,420.39L218.01,418.03L221.6,418.02L221.68,417.04L221.22,416.86L220.91,416.23L219.87,415.56L218.83,414.58L220.1,414.58L220.1,412.93L222.72,412.93L225.31,412.96L225.29,415.27L225.07,418.55L225.9,418.55L226.82,419.08L227.06,418.64L227.88,419.01L226.61,420.12L225.28,420.93L225.08,421.48L225.3,422.04L224.72,422.78L224.06,422.95L224.21,423.29L223.69,423.61L222.73,424.33z'),
(66,	'GW',	'Guinea-Bissau',	'M432.83,432.44L431.33,431.25L430.15,431.07L429.51,430.26L429.52,429.83L428.67,429.23L428.49,428.62L429.98,428.15L430.91,428.24L431.66,427.92L436.84,428.04L436.79,429.01L436.48,429.31L436.72,430.25L436.28,430.63L435.66,430.63L434.93,431.11L434.08,431.06z'),
(67,	'GY',	'Guyana',	'M307.7,440L309.54,441.03L311.28,442.86L311.35,444.31L312.41,444.38L313.91,445.74L315.02,446.72L314.57,449.24L312.87,449.97L313.02,450.62L312.5,452.07L313.75,454.09L314.64,454.1L315.01,455.67L316.72,458.09L316.04,458.19L314.49,457.96L313.58,458.7L312.31,459.19L311.43,459.31L311.12,459.85L309.74,459.71L308.01,458.41L307.81,457.12L307.09,455.71L307.54,453.33L308.32,452.35L307.67,451.05L306.71,450.63L307.08,449.4L306.42,448.76L304.96,448.88L303.07,446.76L303.83,445.99L303.77,444.69L305.5,444.24L306.19,443.72L305.23,442.68L305.48,441.65z'),
(68,	'HN',	'Honduras',	'M230.43,426.9L229.95,426.01L229.09,425.76L229.29,424.61L228.91,424.3L228.33,424.1L227.1,424.44L227,424.05L226.15,423.59L225.55,423.02L224.72,422.78L225.3,422.04L225.08,421.48L225.28,420.93L226.61,420.12L227.88,419.01L228.17,419.13L228.79,418.62L229.59,418.58L229.85,418.81L230.29,418.67L231.59,418.93L232.89,418.85L233.79,418.53L234.12,418.21L235.01,418.36L235.68,418.56L236.41,418.49L236.97,418.24L238.25,418.64L238.7,418.7L239.55,419.24L240.36,419.89L241.38,420.33L242.12,421.13L241.16,421.07L240.77,421.46L239.8,421.84L239.09,421.84L238.47,422.21L237.91,422.08L237.43,421.64L237.14,421.72L236.78,422.41L236.51,422.38L236.46,422.98L235.48,423.77L234.97,424.11L234.68,424.47L233.85,423.89L233.25,424.65L232.66,424.63L232,424.7L232.06,426.11L231.65,426.13L231.3,426.79z'),
(69,	'HR',	'Croatia',	'M528.05,318.93L528.73,320.48L529.62,321.62L528.54,323.11L527.27,322.23L525.33,322.29L522.92,321.63L521.61,321.72L521.01,322.54L520,321.63L519.41,323.27L520.79,325.1L521.39,326.31L522.68,327.76L523.75,328.61L524.81,330.22L527.29,331.66L526.98,332.3L524.35,330.9L522.72,329.52L520.16,328.38L517.8,325.53L518.37,325.23L517.09,323.59L517.03,322.25L515.23,321.63L514.37,323.34L513.54,322.01L513.61,320.63L513.71,320.57L515.66,320.71L516.18,320.03L517.13,320.68L518.23,320.76L518.22,319.64L519.19,319.23L519.47,317.61L521.7,316.53L522.59,317.03L524.69,318.76L527,319.53z'),
(70,	'HT',	'Haiti',	'M270.04,406.75L271.75,406.88L274.18,407.35L274.43,408.96L274.21,410.09L273.53,410.59L274.25,411.47L274.19,412.27L272.33,411.77L271.01,411.97L269.3,411.76L267.99,412.31L266.48,411.39L266.73,410.44L269.31,410.85L271.43,411.09L272.44,410.43L271.16,409.16L271.18,408.03L269.41,407.57z'),
(71,	'HU',	'Hungary',	'M520.68,315.11L521.61,312.46L521.07,311.57L522.65,311.56L522.86,309.85L524.29,310.92L525.32,311.38L527.68,310.87L527.9,310.03L529.02,309.9L530.38,309.25L530.68,309.52L532,309L532.66,308L533.58,307.75L536.58,309.03L537.18,308.6L538.73,309.74L538.93,310.86L537.22,311.73L535.89,314.53L534.2,317.29L531.95,318.05L530.2,317.88L528.05,318.93L527,319.53L524.69,318.76L522.59,317.03L521.7,316.53L521.15,315.16z'),
(72,	'ID',	'Indonesia',	'M813.72,492.06l-1.18,0.05l-3.72,-1.98l2.61,-0.56l1.47,0.86l0.98,0.86L813.72,492.06zM824.15,491.78l-2.4,0.62l-0.34,-0.34l0.25,-0.96l1.21,-1.72l2.77,-1.12l0.28,0.56l0.05,0.86L824.15,491.78zM805.83,486.01l1.01,0.75l1.73,-0.23l0.7,1.2l-3.24,0.57l-1.94,0.38l-1.51,-0.02l0.96,-1.62l1.54,-0.02L805.83,486.01zM819.86,486l-0.41,1.56l-4.21,0.8l-3.73,-0.35l-0.01,-1.03l2.23,-0.59l1.76,0.84l1.87,-0.21L819.86,486zM779.82,482.31l5.37,0.28l0.62,-1.16l5.2,1.35l1.02,1.82l4.21,0.51l3.44,1.67l-3.2,1.07l-3.08,-1.13l-2.54,0.08l-2.91,-0.21l-2.62,-0.51l-3.25,-1.07l-2.06,-0.28l-1.17,0.35l-5.11,-1.16l-0.49,-1.21l-2.57,-0.21l1.92,-2.68l3.4,0.17l2.26,1.09l1.16,0.21L779.82,482.31zM853,480.73l-1.44,1.91l-0.27,-2.11l0.5,-1.01l0.59,-0.95l0.64,0.82L853,480.73zM832.04,473.02l-1.05,0.93l-1.94,-0.51l-0.55,-1.2l2.84,-0.13L832.04,473.02zM841.08,472.01l1.02,2.13l-2.37,-1.15l-2.34,-0.23l-1.58,0.18l-1.94,-0.1l0.67,-1.53l3.46,-0.12L841.08,472.01zM851.37,466.59l0.78,4.51l2.9,1.67l2.34,-2.96l3.22,-1.68l2.49,0l2.4,0.97l2.08,1l3.01,0.53l0.05,9.1l0.05,9.16l-2.5,-2.31l-2.85,-0.57l-0.69,0.8l-3.55,0.09l1.19,-2.29l1.77,-0.78l-0.73,-3.05l-1.35,-2.35l-5.44,-2.37l-2.31,-0.23l-4.21,-2.58l-0.83,1.36l-1.08,0.25l-0.64,-1.02l-0.01,-1.21l-2.14,-1.37l3.02,-1l2,0.05l-0.24,-0.74l-4.1,-0.01l-1.11,-1.66l-2.5,-0.51l-1.19,-1.38l3.78,-0.67l1.44,-0.91l4.5,1.14L851.37,466.59zM826.41,459.43l-2.25,2.76l-2.11,0.54l-2.7,-0.54l-4.67,0.14l-2.45,0.4l-0.4,2.11l2.51,2.48l1.51,-1.26l5.23,-0.95l-0.23,1.28l-1.22,-0.4l-1.22,1.63l-2.47,1.08l2.65,3.57l-0.51,0.96l2.52,3.22l-0.02,1.84l-1.5,0.82l-1.1,-0.98l1.36,-2.29l-2.75,1.08l-0.7,-0.77l0.36,-1.08l-2.02,-1.64l0.21,-2.72l-1.87,0.85l0.24,3.25l0.11,4l-1.78,0.41l-1.2,-0.82l0.8,-2.57l-0.43,-2.69l-1.18,-0.02l-0.87,-1.91l1.16,-1.83l0.4,-2.21l1.41,-4.2l0.59,-1.15l2.38,-2.07l2.19,0.82l3.54,0.39l3.22,-0.12l2.77,-2.02L826.41,459.43zM836.08,460.23l-0.15,2.43l-1.45,-0.27l-0.43,1.69l1.16,1.47l-0.79,0.33l-1.13,-1.76l-0.83,-3.56l0.56,-2.23l0.93,-1.01l0.2,1.52l1.66,0.24L836.08,460.23zM805.76,458.29l3.14,2.58l-3.32,0.33l-0.94,1.9l0.12,2.52l-2.7,1.91L802,470.3l-1.08,4.27l-0.41,-0.99l-3.19,1.26l-1.11,-1.71l-2,-0.16l-1.4,-0.89l-3.33,1l-1.02,-1.35l-1.84,0.15l-2.31,-0.32l-0.43,-3.74l-1.4,-0.77l-1.35,-2.38l-0.39,-2.44l0.33,-2.58l1.67,-1.85l0.47,1.86l1.92,1.57l1.81,-0.57l1.79,0.2l1.63,-1.41l1.34,-0.24l2.65,0.78l2.29,-0.59l1.44,-3.88l1.08,-0.97l0.97,-3.17l3.22,0l2.43,0.47l-1.59,2.52l2.06,2.64L805.76,458.29zM771.95,479.71l-3.1,0.06l-2.36,-2.34l-3.6,-2.28l-1.2,-1.69l-2.12,-2.27l-1.39,-2.09l-2.13,-3.9l-2.46,-2.32l-0.82,-2.39l-1.03,-2.17l-2.53,-1.75l-1.47,-2.39l-2.11,-1.56l-2.92,-3.08l-0.25,-1.42l1.81,0.11l4.34,0.54l2.48,2.73l2.17,1.89l1.55,1.16l2.66,3l2.85,0.04l2.36,1.91l1.62,2.33l2.13,1.27l-1.12,2.27l1.61,0.97l1.01,0.07l0.48,1.94l0.98,1.56l2.06,0.25l1.36,1.76l-0.7,3.47L771.95,479.71z'),
(73,	'IE',	'Ireland',	'M457.88,284.29L458.34,287.65L456.22,291.77L451.25,294.45L447.28,293.77L449.55,288.99L448.09,284.22L451.9,280.47L454.02,278.2L454.6,280.8L454.02,283.37L455.76,283.31z'),
(74,	'IL',	'Israel',	'M575.41,366.82L574.92,367.87L573.9,367.41L573.32,369.61L574.02,369.97L573.31,370.43L573.18,371.29L574.5,370.84L574.57,372.11L573.17,377.28L571.33,371.73L572.14,370.65L571.95,370.46L572.69,368.93L573.26,366.43L573.66,365.59L573.74,365.56L574.68,365.56L574.94,364.98L575.69,364.93L575.73,366.3L575.35,366.8z'),
(75,	'IN',	'India',	'M693.5,357.44L696.51,361.43L696.23,364.17L697.34,365.88L697.25,367.57L695.24,367.13L696.03,370.76L698.78,372.82L702.68,375.09L700.9,376.55L699.81,379.54L702.53,380.74L705.17,382.29L708.83,384.06L712.67,384.47L714.29,386.06L716.45,386.35L719.83,387.08L722.16,387.03L722.48,385.79L722.11,383.8L722.33,382.45L724.04,381.78L724.28,384.26L724.33,384.89L726.88,386.08L728.65,385.59L731.01,385.8L733.3,385.71L733.5,383.78L732.36,382.78L734.62,382.38L737.17,380.03L740.4,378L742.75,378.78L744.75,377.44L746.07,379.42L745.12,380.76L748.14,381.23L748.36,382.43L747.37,383.01L747.6,384.94L745.6,384.37L741.97,386.53L742.05,388.31L740.51,390.91L740.36,392.41L739.11,394.93L736.92,394.23L736.81,397.38L736.18,398.41L736.48,399.69L735.09,400.41L733.62,395.61L732.84,395.62L732.38,397.56L730.85,395.98L731.71,394.25L732.97,394.07L734.26,391.48L732.65,390.95L730.04,391L727.38,390.58L727.13,388.43L725.79,388.27L723.57,386.93L722.58,389.04L724.6,390.67L722.85,391.82L722.23,392.94L723.95,393.76L723.48,395.6L724.45,397.88L724.89,400.36L724.48,401.46L722.58,401.42L719.12,402.04L719.28,404.29L717.78,406.05L713.75,408.05L710.61,411.51L708.5,413.36L705.71,415.27L705.71,416.61L704.31,417.33L701.78,418.36L700.47,418.52L699.63,420.72L700.21,424.47L700.36,426.84L699.18,429.55L699.16,434.38L697.71,434.52L696.44,436.67L697.29,437.6L694.73,438.4L693.79,440.32L692.66,441.13L690.01,438.5L688.71,434.54L687.63,431.68L686.65,430.34L685.16,427.6L684.47,424.02L683.98,422.22L681.43,418.25L680.27,412.61L679.43,408.84L679.44,405.26L678.9,402.46L674.82,404.25L672.84,403.89L669.18,400.26L670.53,399.17L669.7,397.99L666.41,395.41L668.28,393.37L674.45,393.38L673.89,390.74L672.32,389.18L672,386.79L670.16,385.39L673.25,382.09L676.51,382.33L679.44,379.01L681.2,375.75L683.92,372.51L683.88,370.18L686.27,368.27L684,366.64L683.03,364.39L682.04,361.44L683.41,359.98L687.67,360.81L690.79,360.3z'),
(76,	'IQ',	'Iraq',	'M602.61,355.77L604.44,356.81L604.66,358.81L603.24,359.98L602.59,362.62L604.54,365.8L607.97,367.62L609.42,370.12L608.96,372.49L609.85,372.49L609.88,374.22L611.43,375.91L609.77,375.76L607.88,375.49L605.82,378.57L600.61,378.31L592.71,371.82L588.53,369.53L585.15,368.64L584.02,364.6L590.23,361.1L591.29,356.98L591.02,354.46L592.56,353.6L594,351.42L595.2,350.87L598.46,351.33L599.45,352.22L600.79,351.63z'),
(77,	'IR',	'Iran',	'M626.44,351.53L628.91,350.85L630.9,348.83L632.77,348.93L634,348.27L636,348.6L639.1,350.39L641.34,350.78L644.54,353.87L646.63,353.99L646.88,356.9L645.74,361.15L644.97,363.6L646.19,364.09L644.99,365.92L645.91,368.56L646.13,370.65L648.25,371.2L648.48,373.3L645.94,376.23L647.32,377.91L648.45,379.84L651.13,381.24L651.21,384.01L652.55,384.52L652.78,385.96L648.74,387.57L647.68,391.17L642.41,390.24L639.35,389.53L636.19,389.12L634.99,385.31L633.65,384.75L631.49,385.31L628.67,386.82L625.24,385.79L622.41,383.38L619.71,382.48L617.84,379.47L615.77,375.2L614.26,375.72L612.48,374.65L611.43,375.91L609.88,374.22L609.85,372.49L608.96,372.49L609.42,370.12L607.97,367.62L604.54,365.8L602.59,362.62L603.24,359.98L604.66,358.81L604.44,356.81L602.61,355.77L600.79,351.63L599.26,348.8L599.8,347.71L598.93,343.59L600.85,342.56L601.29,343.93L602.71,345.59L604.63,346.06L605.65,345.96L608.96,343.3L610.01,343.03L610.83,344.1L609.87,345.88L611.62,347.74L612.31,347.57L613.2,350.18L615.86,350.91L617.81,352.67L621.79,353.27L626.17,352.35z'),
(78,	'IS',	'Iceland',	'M434.57,212.43L433.93,216.91L437.09,221.51L433.45,226.52L425.36,230.9L422.94,232.05L419.25,231.12L411.43,229.11L414.19,226.27L408.09,223.07L413.05,221.79L412.93,219.82L407.05,218.25L408.94,213.78L413.19,212.75L417.56,217.43L421.82,213.68L425.35,215.64L429.92,211.93z'),
(79,	'IT',	'Italy',	'M518.77,347.88l-1.01,2.78l0.42,1.09l-0.59,1.79l-2.14,-1.31l-1.43,-0.38l-3.91,-1.79l0.39,-1.82l3.28,0.32l2.86,-0.39L518.77,347.88zM501.08,337.06l1.68,2.62l-0.39,4.81l-1.27,-0.23l-1.14,1.2l-1.06,-0.95l-0.11,-4.38l-0.64,-2.1l1.54,0.19L501.08,337.06zM509.95,315.46l4.01,1.05l-0.3,1.99l0.67,1.71l-2.23,-0.58l-2.28,1.42l0.16,1.97l-0.34,1.12l0.92,1.99l2.63,1.95l1.41,3.17l3.12,3.05l2.2,-0.02l0.68,0.83l-0.79,0.74l2.51,1.35l2.06,1.12l2.4,1.92l0.29,0.68l-0.52,1.31l-1.56,-1.7l-2.44,-0.6l-1.18,2.36l2.03,1.34l-0.33,1.88l-1.17,0.21l-1.5,3.06l-1.17,0.27l0.01,-1.08l0.57,-1.91l0.61,-0.77l-1.09,-2.09l-0.86,-1.83l-1.16,-0.46l-0.83,-1.58l-1.8,-0.67l-1.21,-1.49l-2.07,-0.24l-2.19,-1.68l-2.56,-2.45l-1.91,-2.19l-0.87,-3.8l-1.4,-0.45l-2.28,-1.29l-1.29,0.53l-1.62,1.8l-1.17,0.28l0.32,-1.68l-1.52,-0.49l-0.72,-3.04l0.97,-1.21l-0.83,-1.5l0.12,-1.13l1.21,0.86l1.35,-0.19l1.57,-1.36l0.49,0.64l1.34,-0.13l0.61,-1.63l2.07,0.51l1.24,-0.68l0.22,-1.67l1.7,0.58l0.33,-0.78l2.77,-0.71L509.95,315.46z'),
(80,	'JM',	'Jamaica',	'M257.76,410.96L259.65,411.22L261.14,411.93L261.6,412.73L259.63,412.78L258.78,413.27L257.21,412.8L255.61,411.73L255.94,411.06L257.12,410.86z'),
(81,	'JO',	'Jordan',	'M574.92,367.87L575.41,366.82L578.53,368.14L584.02,364.6L585.15,368.64L584.62,369.13L579,370.78L581.8,374.04L580.87,374.58L580.41,375.67L578.27,376.11L577.6,377.27L576.38,378.25L573.26,377.74L573.17,377.28L574.57,372.11L574.5,370.84L574.92,369.88z'),
(82,	'JP',	'Japan',	'M852.76,362.01l0.36,1.15l-1.58,2.03l-1.15,-1.07l-1.44,0.78l-0.74,1.95l-1.83,-0.95l0.02,-1.58l1.55,-2l1.59,0.39l1.15,-1.42L852.76,362.01zM870.53,351.73l-1.06,2.78l0.49,1.73l-1.46,2.42l-3.58,1.6l-4.93,0.21l-4,3.84l-1.88,-1.29L854,360.5l-4.88,0.75l-3.32,1.59l-3.28,0.06l2.84,2.46l-1.87,5.61l-1.81,1.37l-1.36,-1.27l0.69,-2.96l-1.77,-0.96l-1.14,-2.28l2.65,-1.03l1.47,-2.11l2.82,-1.75l2.06,-2.33l5.58,-1.02l3,0.7l2.93,-6.17l1.87,1.67l4.11,-3.51l1.59,-1.38l1.76,-4.38l-0.48,-4.1l1.18,-2.33l2.98,-0.68l1.53,5.11l-0.08,2.94l-2.59,3.6L870.53,351.73zM878.76,325.8l1.97,0.83l1.98,-1.65l0.62,4.35l-4.16,1.05l-2.46,3.76l-4.41,-2.58l-1.53,4.12l-3.12,0.06l-0.39,-3.74l1.39,-2.94l3,-0.21l0.82,-5.38l0.83,-3.09l3.29,4.12L878.76,325.8z'),
(83,	'KE',	'Kenya',	'M590.19,465.78L591.85,468.07L589.89,469.19L589.2,470.35L588.14,470.55L587.75,472.52L586.85,473.64L586.3,475.5L585.17,476.42L581.15,473.63L580.95,472.01L570.79,466.34L570.31,466.03L570.29,463.08L571.09,461.95L572.47,460.11L573.49,458.08L572.26,454.88L571.93,453.48L570.6,451.54L572.32,449.87L574.22,448.03L575.68,448.5L575.68,450.07L576.64,450.98L578.59,450.98L582.14,453.36L583.02,453.38L583.68,453.31L584.3,453.63L586.17,453.85L587,452.69L589.56,451.52L590.69,452.46L592.61,452.46L590.16,455.63z'),
(84,	'KG',	'Kyrgyzstan',	'M674.22,333.11L674.85,331.45L676.69,330.91L681.31,332.22L681.74,329.98L683.33,329.18L687.33,330.79L688.35,330.37L693,330.47L697.16,330.87L698.56,332.24L700.29,332.79L699.9,333.65L695.48,335.68L694.48,337.16L690.88,337.6L689.82,339.95L686.85,339.46L684.92,340.18L682.24,341.9L682.63,342.75L681.83,343.58L676.53,344.13L673.06,342.96L670.02,343.24L670.29,341.14L673.34,341.75L674.37,340.62L676.5,340.98L680.09,338.34L676.77,336.38L674.77,337.31L672.7,335.91L675.05,333.48z'),
(85,	'KH',	'Cambodia',	'M765.44,433.6L764.3,432.12L762.89,429.18L762.22,425.73L764.02,423.35L767.64,422.8L770.27,423.21L772.58,424.34L773.85,422.35L776.34,423.41L776.99,425.33L776.64,428.75L771.93,430.94L773.16,432.67L770.22,432.87L767.79,434.01z'),
(86,	'KP',	'North Korea',	'M841.55,332.62L841.94,333.29L840.88,333.06L839.66,334.33L838.82,335.61L838.93,338.28L837.48,339.09L836.98,339.74L835.92,340.82L834.05,341.42L832.84,342.4L832.75,343.97L832.42,344.37L833.54,344.95L835.13,346.53L834.72,347.39L833.53,347.62L831.55,347.79L830.46,349.39L829.2,349.27L829.03,349.59L827.67,348.92L827.33,349.58L826.51,349.87L826.41,349.21L825.68,348.89L824.93,348.32L825.7,346.75L826.36,346.33L826.11,345.68L826.82,343.74L826.63,343.15L825,342.75L823.68,341.78L825.96,339.43L829.05,337.45L830.98,334.8L832.31,335.97L834.73,336.11L834.29,334.14L838.62,332.51L839.74,330.38z'),
(87,	'KR',	'South Korea',	'M835.13,346.53L837.55,350.71L838.24,352.98L838.26,356.96L837.21,358.84L834.67,359.5L832.43,360.91L829.9,361.2L829.59,359.35L830.11,356.78L828.87,353.18L830.95,352.59L829.03,349.59L829.2,349.27L830.46,349.39L831.55,347.79L833.53,347.62L834.72,347.39z'),
(88,	'XK',	'Kosovo',	'M533.47,333.92L533.34,334.69L532.98,334.66L532.8,333.29L532.13,332.91L531.53,331.89L532.05,331.04L532.72,330.76L533.11,329.5L533.61,329.28L534.01,329.82L534.54,330.06L534.9,330.67L535.36,330.85L535.91,331.55L536.31,331.53L535.99,332.46L535.66,332.91L535.75,333.19L535.12,333.33z'),
(89,	'KW',	'Kuwait',	'M609.77,375.76L610.35,377.17L610.1,377.9L611,380.31L609.02,380.39L608.32,378.88L605.82,378.57L607.88,375.49z'),
(90,	'KZ',	'Kazakhstan',	'M674.22,333.11L672.61,333.81L668.92,336.42L667.69,339.07L666.64,339.09L665.88,337.34L662.31,337.22L661.74,334.16L660.37,334.13L660.58,330.33L657.23,327.53L652.42,327.83L649.13,328.39L646.45,324.89L644.16,323.41L639.81,320.57L639.29,320.22L632.07,322.57L632.18,336.7L630.74,336.88L628.78,333.95L626.88,332.89L623.7,333.68L622.46,334.93L622.3,334.01L622.99,332.44L622.46,331.12L619.21,329.82L617.94,326.35L616.4,325.37L616.3,324.09L619.03,324.46L619.14,321.58L621.52,320.94L623.97,321.53L624.48,317.62L623.98,315.11L621.17,315.31L618.79,314.31L615.54,316.1L612.93,316.96L611.5,316.3L611.79,314.2L610,311.44L607.92,311.55L605.54,308.72L607.16,305.5L606.34,304.63L608.57,299.86L611.46,302.39L611.81,299.2L617.59,294.35L621.97,294.23L628.16,297.33L631.47,299.12L634.45,297.25L638.89,297.17L642.48,299.46L643.3,298.15L647.23,298.34L647.94,296.23L643.39,293.14L646.08,290.91L645.56,289.66L648.25,288.45L646.23,285.25L647.51,283.63L658,281.97L659.37,280.78L666.39,278.99L668.91,276.95L673.95,278.01L674.83,283.02L677.76,281.86L681.36,283.49L681.13,286.07L683.82,285.8L690.84,281.31L689.82,282.81L693.4,286.47L699.66,298.05L701.16,295.72L705.02,298.28L709.05,297.14L710.59,297.94L711.94,300.49L713.9,301.33L715.1,303.18L718.71,302.6L720.2,305.23L718.06,308.06L715.73,308.46L715.6,312.64L714.04,314.5L708.48,313.15L706.46,320.41L705.02,321.3L699.47,322.88L701.99,329.63L700.07,330.63L700.29,332.79L698.56,332.24L697.16,330.87L693,330.47L688.35,330.37L687.33,330.79L683.33,329.18L681.74,329.98L681.31,332.22L676.69,330.91L674.85,331.45z'),
(91,	'LA',	'Lao People\'s Democratic Republic',	'M770.27,423.21L771.18,421.91L771.31,419.47L769.04,416.94L768.86,414.07L766.73,411.69L764.61,411.49L764.05,412.51L762.4,412.59L761.56,412.08L758.61,413.82L758.54,411.2L759.23,408.09L757.34,407.96L757.18,406.18L755.96,405.26L756.56,404.16L758.95,402.22L759.2,402.92L760.69,403L760.27,399.57L761.72,399.13L763.36,401.5L764.62,404.22L768.07,404.25L769.16,406.84L767.37,407.61L766.56,408.68L769.92,410.44L772.25,413.9L774.02,416.47L776.14,418.49L776.85,420.53L776.34,423.41L773.85,422.35L772.58,424.34z'),
(92,	'LB',	'Lebanon',	'M575.69,364.93L574.94,364.98L574.68,365.56L573.74,365.56L574.74,362.83L576.13,360.45L576.19,360.33L577.45,360.51L577.91,361.83L576.38,363.1z'),
(93,	'LK',	'Sri Lanka',	'M704.57,442.37L704.15,445.29L702.98,446.09L700.54,446.73L699.2,444.5L698.71,440.47L699.98,435.89L701.91,437.46L703.22,439.44z'),
(94,	'LR',	'Liberia',	'M453.63,451.22L452.89,451.24L450,449.91L447.46,447.78L445.07,446.25L443.18,444.44L443.85,443.54L444,442.73L445.26,441.2L446.57,439.89L447.17,439.82L447.9,439.51L449.07,441.23L448.89,442.36L449.43,442.96L450.23,442.97L450.8,441.84L451.59,441.91L451.46,442.73L451.74,444.09L451.13,445.33L451.95,446.1L452.84,446.29L454.03,447.46L454.11,448.57L453.84,448.92z'),
(95,	'LS',	'Lesotho',	'M556.5,547.75L557.48,548.71L556.62,550.27L556.14,551.32L554.58,551.82L554.06,552.86L553.06,553.18L550.96,550.69L552.45,548.66L553.97,547.41L555.28,546.77z'),
(96,	'LT',	'Lithuania',	'M538.99,282.09L538.76,280.87L539.06,279.54L537.82,278.77L534.89,277.91L534.29,273.75L537.5,272.2L542.2,272.53L544.96,272.03L545.35,273.08L546.84,273.4L549.54,275.82L549.8,278.02L547.5,279.59L546.85,282.31L543.81,284.11L541.1,284.07L540.43,282.61z'),
(97,	'LU',	'Luxembourg',	'M492.2,301.29L492.76,302.27L492.6,304.16L491.79,304.26L491.16,303.88L491.47,301.45z'),
(98,	'LV',	'Latvia',	'M534.29,273.75L534.39,269.94L535.77,266.7L538.41,264.92L540.63,268.8L542.88,268.7L543.42,264.71L545.81,263.78L547.04,264.43L549.45,266.37L551.77,266.38L553.12,267.57L553.35,270.06L554.26,273.05L551.24,274.98L549.54,275.82L546.84,273.4L545.35,273.08L544.96,272.03L542.2,272.53L537.5,272.2z'),
(99,	'LY',	'Libya',	'M516.89,397.93L514.91,399.05L513.33,397.39L508.9,396.08L507.67,394.17L505.45,392.75L504.14,393.31L503.15,391.6L503.04,390.28L501.38,388.02L502.5,386.73L502.25,384.76L502.61,383.04L502.41,381.6L502.9,379.01L502.75,377.53L501.84,374.69L503.21,373.94L503.45,372.56L503.15,371.21L505.08,369.95L505.94,368.9L507.31,367.95L507.47,365.4L510.76,366.55L511.94,366.26L514.28,366.82L518,368.29L519.31,371.21L521.83,371.85L525.78,373.21L528.77,374.82L530.14,373.98L531.48,372.49L530.83,369.98L531.71,368.38L533.73,366.83L535.66,366.38L539.45,367.06L540.41,368.54L541.45,368.55L542.34,369.11L545.13,369.5L545.81,370.58L544.8,372.15L545.23,373.54L544.51,375.54L545.35,378.12L545.35,389.3L545.35,400.53L545.35,406.49L542.13,406.5L542.09,407.74L530.91,402.04L519.72,396.27z'),
(100,	'MA',	'Morocco',	'M450.96,383.14L450.93,379.39L455.46,377.03L458.26,376.54L460.55,375.68L461.63,374.06L464.91,372.77L465.03,370.36L466.65,370.07L467.92,368.86L471.59,368.3L472.1,367.02L471.36,366.31L470.39,362.78L470.23,360.73L469.17,358.55L467.95,358.51L465.05,357.76L462.38,358L460.69,356.54L458.63,356.52L457.74,358.63L455.87,362.14L453.79,363.53L450.98,365.06L449.18,367.3L448.8,369.04L447.73,371.86L448.43,375.89L446.09,378.57L444.69,379.42L442.48,381.59L439.87,381.94L438.57,383.06L442.19,383.07L450.94,383.1L450.94,383.1L450.94,383.1L442.19,383.07L438.57,383.06z'),
(101,	'MD',	'Moldova',	'M549.89,309.45L550.56,308.83L552.42,308.41L554.49,309.72L555.64,309.88L556.91,311L556.71,312.41L557.73,313.08L558.13,314.8L559.11,315.84L558.92,316.44L559.44,316.86L558.7,317.15L557.04,317.04L556.77,316.47L556.18,316.8L556.38,317.52L555.61,318.81L555.12,320.18L554.42,320.62L553.91,318.79L554.21,317.07L554.12,315.28L552.5,312.84L551.61,311.09L550.74,309.85z'),
(102,	'ME',	'Montenegro',	'M530.77,332.23L530.6,331.51L529.38,333.38L529.57,334.57L528.98,334.28L528.2,333.05L526.98,332.3L527.29,331.66L527.7,329.56L528.61,328.67L529.14,328.31L529.88,328.97L530.29,329.51L531.21,329.92L532.28,330.71L532.05,331.04L531.53,331.89z'),
(103,	'MG',	'Madagascar',	'M614.17,498.4L614.91,499.61L615.6,501.5L616.06,504.96L616.78,506.31L616.5,507.69L616.01,508.55L615.05,506.85L614.53,507.71L615.06,509.85L614.81,511.09L614.04,511.76L613.86,514.24L612.76,517.66L611.38,521.75L609.64,527.42L608.57,531.63L607.3,535.18L605.02,535.91L602.57,537.22L600.96,536.43L598.73,535.33L597.96,533.71L597.77,531L596.79,528.58L596.53,526.41L597.03,524.25L598.32,523.73L598.33,522.74L599.67,520.48L599.92,518.6L599.27,517.2L598.74,515.35L598.52,512.65L599.5,511.02L599.87,509.17L601.27,509.07L602.84,508.47L603.87,507.95L605.11,507.91L606.7,506.26L609.01,504.48L609.85,503.04L609.47,501.81L610.66,502.16L612.21,500.17L612.26,498.45L613.19,497.17z'),
(104,	'MK',	'Macedonia',	'M532.98,334.66L533.34,334.69L533.47,333.92L535.12,333.33L535.75,333.19L536.71,332.97L538,332.91L539.41,334.12L539.61,336.59L539.07,336.71L538.61,337.36L537.09,337.29L536.02,338.1L534.19,338.42L533.03,337.52L532.63,335.93z'),
(105,	'ML',	'Mali',	'M441.13,422.22L442.07,421.7L442.54,420L443.43,419.93L445.39,420.73L446.97,420.16L448.05,420.35L448.48,419.71L459.73,419.67L460.35,417.64L459.86,417.28L458.51,404.6L457.16,391.54L461.45,391.49L470.91,398.14L480.37,404.69L481.03,406.08L482.78,406.93L484.08,407.41L484.11,409.29L487.22,409L487.23,415.75L485.69,417.69L485.45,419.48L482.96,419.93L479.14,420.18L478.1,421.21L476.3,421.32L474.51,421.33L473.81,420.78L472.26,421.19L469.64,422.39L469.11,423.29L466.93,424.57L466.55,425.31L465.38,425.89L464.02,425.51L463.25,426.21L462.84,428.17L460.61,430.53L460.68,431.49L459.91,432.7L460.1,434.34L458.94,434.76L458.29,435.12L457.85,433.91L457.04,434.23L456.56,434.17L456.04,435L453.88,434.97L453.1,434.55L452.74,434.81L451.88,433.99L452.03,433.15L451.68,432.81L451.08,433.09L451.19,432.17L451.77,431.44L450.62,430.25L450.28,429.46L449.66,428.84L449.1,428.76L448.43,429.16L447.52,429.54L446.75,430.16L445.55,429.93L444.77,429.21L444.31,429.11L443.58,429.49L443.13,429.5L442.97,428.45L443.1,427.56L442.86,426.46L441.81,425.65L441.26,424.01z'),
(106,	'MM',	'Myanmar',	'M754.36,405.95L752.72,407.23L750.74,407.37L749.46,410.56L748.28,411.09L749.64,413.66L751.42,415.79L752.56,417.71L751.54,420.23L750.57,420.76L751.24,422.21L753.11,424.49L753.43,426.09L753.38,427.42L754.48,430.02L752.94,432.67L751.58,435.58L751.31,433.48L752.17,431.3L751.23,429.62L751.46,426.51L750.32,425.03L749.41,421.59L748.9,417.93L747.69,415.53L745.84,416.99L742.65,419.05L741.08,418.79L739.34,418.12L740.31,414.51L739.73,411.77L737.53,408.38L737.87,407.31L736.23,406.93L734.24,404.51L734.06,402.1L735.04,402.56L735.09,400.41L736.48,399.69L736.18,398.41L736.81,397.38L736.92,394.23L739.11,394.93L740.36,392.41L740.51,390.91L742.05,388.31L741.97,386.53L745.6,384.37L747.6,384.94L747.37,383.01L748.36,382.43L748.14,381.23L749.78,380.99L750.72,382.85L751.94,383.6L752.03,386L751.91,388.57L749.26,391.15L748.92,394.78L751.88,394.28L752.55,397.08L754.33,397.67L753.51,400.17L755.59,401.3L756.81,401.85L758.86,400.98L758.95,402.22L756.56,404.16L755.96,405.26z'),
(107,	'MN',	'Mongolia',	'M721.29,304.88L724.25,304.14L729.6,300.4L733.87,298.33L736.3,299.68L739.23,299.74L741.1,301.79L743.9,301.94L747.96,303.03L750.68,300L749.54,297.4L752.45,292.74L755.59,294.61L758.13,295.14L761.43,296.29L761.96,299.61L765.95,301.45L768.6,300.64L772.14,300.07L774.95,300.65L777.7,302.74L779.4,304.94L782,304.9L785.53,305.59L788.11,304.53L791.8,303.82L795.91,300.76L797.59,301.23L799.06,302.69L802.4,302.33L801.04,305.58L799.06,309.8L799.78,311.51L801.37,310.98L804.13,311.63L806.29,310.09L808.54,311.42L811.08,314.31L810.77,315.76L808.56,315.3L804.49,315.84L802.51,317L800.46,319.66L796.18,321.21L793.39,323.31L790.51,322.51L788.93,322.15L787.46,324.69L788.35,326.19L788.81,327.47L786.84,328.77L784.83,330.82L781.56,332.15L777.35,332.3L772.82,333.61L769.56,335.62L768.32,334.46L764.93,334.46L760.78,332.17L758.01,331.6L754.28,332.13L748.49,331.28L745.4,331.37L743.76,329.1L742.48,325.53L740.75,325.1L737.36,322.65L733.58,322.1L730.25,321.42L729.24,319.69L730.32,314.96L728.39,311.65L724.39,310.08L722.03,307.85z'),
(108,	'MR',	'Mauritania',	'M441.13,422.22L439.28,420.24L437.58,418.11L435.72,417.34L434.38,416.49L432.81,416.52L431.45,417.15L430.05,416.9L429.09,417.83L428.85,416.27L429.63,414.83L429.98,412.08L429.67,409.17L429.33,407.7L429.61,406.23L428.89,404.81L427.41,403.53L428.02,402.53L439,402.55L438.47,398.2L439.16,396.65L441.78,396.38L441.69,388.52L450.9,388.69L450.9,383.96L461.45,391.49L457.16,391.54L458.51,404.6L459.86,417.28L460.35,417.64L459.73,419.67L448.48,419.71L448.05,420.35L446.97,420.16L445.39,420.73L443.43,419.93L442.54,420L442.07,421.7z'),
(109,	'MW',	'Malawi',	'M572.15,495.69L571.37,497.85L572.15,501.57L573.13,501.53L574.14,502.45L575.31,504.53L575.55,508.25L574.34,508.86L573.48,510.87L571.65,509.08L571.45,507.04L572.04,505.69L571.87,504.54L570.77,503.81L569.99,504.07L568.38,502.69L566.91,501.95L567.76,499.29L568.64,498.3L568.1,495.94L568.66,493.64L569.14,492.87L568.43,490.47L567.11,489.21L569.85,489.73L570.42,490.51L571.37,491.83z'),
(110,	'MX',	'Mexico',	'M202.89,388.72L201.8,391.43L201.31,393.64L201.1,397.72L200.83,399.19L201.32,400.83L202.19,402.3L202.75,404.61L204.61,406.82L205.26,408.51L206.36,409.96L209.34,410.75L210.5,411.97L212.96,411.15L215.09,410.86L217.19,410.33L218.96,409.82L220.74,408.62L221.41,406.89L221.64,404.4L222.13,403.53L224.02,402.74L226.99,402.05L229.47,402.15L231.17,401.9L231.84,402.53L231.75,403.97L230.24,405.74L229.58,407.55L230.09,408.06L229.67,409.34L228.97,411.63L228.26,410.88L227.67,410.93L227.14,410.97L226.14,412.74L225.63,412.39L225.29,412.53L225.31,412.96L222.72,412.93L220.1,412.93L220.1,414.58L218.83,414.58L219.87,415.56L220.91,416.23L221.22,416.86L221.68,417.04L221.6,418.02L218.01,418.03L216.66,420.39L217.05,420.93L216.73,421.61L216.66,422.45L213.49,419.34L212.04,418.4L209.75,417.64L208.19,417.85L205.93,418.94L204.52,419.23L202.54,418.47L200.44,417.91L197.82,416.58L195.72,416.17L192.54,414.82L190.2,413.42L189.49,412.64L187.92,412.47L185.05,411.54L183.88,410.2L180.87,408.53L179.47,406.66L178.8,405.21L179.73,404.92L179.44,404.07L180.09,403.3L180.1,402.26L179.16,400.92L178.9,399.72L177.96,398.2L175.49,395.18L172.67,392.79L171.31,390.88L168.9,389.62L168.39,388.86L168.82,386.94L167.39,386.21L165.73,384.69L165.03,382.5L163.52,382.24L161.9,380.58L160.58,379.03L160.46,378.03L158.95,375.61L157.96,373.13L158,371.88L155.97,370.59L155.04,370.73L153.44,369.83L152.99,371.16L153.45,372.72L153.72,375.15L154.69,376.48L156.77,378.69L157.23,379.44L157.66,379.66L158.02,380.76L158.52,380.71L159.09,382.75L159.94,383.55L160.53,384.66L162.3,386.26L163.23,389.15L164.06,390.5L164.84,391.94L164.99,393.56L166.34,393.66L167.47,395.05L168.49,396.41L168.42,396.95L167.24,398.06L166.74,398.05L166,396.2L164.17,394.47L162.15,392.99L160.71,392.21L160.8,389.96L160.38,388.28L159.04,387.32L157.11,385.93L156.74,386.33L156.04,385.51L154.31,384.76L152.66,382.93L152.86,382.69L154.01,382.87L155.05,381.69L155.16,380.26L153,377.99L151.36,377.1L150.32,375.09L149.28,372.97L147.98,370.36L146.84,367.4L150.03,367.15L153.59,366.79L153.33,367.43L157.56,369.04L163.96,371.35L169.54,371.32L171.76,371.32L171.76,369.97L176.62,369.97L177.64,371.14L179.08,372.17L180.74,373.6L181.67,375.29L182.37,377.05L183.82,378.02L186.15,378.98L187.91,376.45L190.21,376.39L192.18,377.67L193.59,379.85L194.56,381.71L196.21,383.51L196.83,385.7L197.62,387.17L199.8,388.13L201.79,388.81z'),
(111,	'MY',	'Malaysia',	'M758.65,446.07l0.22,1.44l1.85,-0.33l0.92,-1.15l0.64,0.26l1.66,1.69l1.18,1.87l0.16,1.88l-0.3,1.27l0.27,0.96l0.21,1.65l0.99,0.77l1.1,2.46l-0.05,0.94l-1.99,0.19l-2.65,-2.06l-3.32,-2.21l-0.33,-1.42l-1.62,-1.87l-0.39,-2.31l-1.01,-1.52l0.31,-2.04l-0.62,-1.19l0.49,-0.5L758.65,446.07zM807.84,450.9l-2.06,0.95l-2.43,-0.47l-3.22,0l-0.97,3.17l-1.08,0.97l-1.44,3.88l-2.29,0.59l-2.65,-0.78l-1.34,0.24l-1.63,1.41l-1.79,-0.2l-1.81,0.57l-1.92,-1.57l-0.47,-1.86l2.05,0.96l2.17,-0.52l0.56,-2.36l1.2,-0.53l3.36,-0.6l2.01,-2.21l1.38,-1.77l1.28,1.45l0.59,-0.95l1.34,0.09l0.16,-1.78l0.13,-1.38l2.16,-1.95l1.41,-2.19l1.13,-0.01l1.44,1.42l0.13,1.22l1.85,0.78l2.34,0.84l-0.2,1.1l-1.88,0.14L807.84,450.9z'),
(112,	'MZ',	'Mozambique',	'M572.15,495.69L574.26,495.46L577.63,496.26L578.37,495.9L580.32,495.83L581.32,494.98L583,495.02L586.06,493.92L588.29,492.28L588.75,493.55L588.63,496.38L588.98,498.88L589.09,503.36L589.58,504.76L588.75,506.83L587.66,508.84L585.87,510.64L583.31,511.75L580.15,513.16L576.98,516.31L575.9,516.85L573.94,518.94L572.79,519.63L572.55,521.75L573.88,524L574.43,525.76L574.47,526.66L574.96,526.51L574.88,529.47L574.43,530.88L575.09,531.4L574.67,532.67L573.5,533.76L571.19,534.8L567.82,536.46L566.59,537.61L566.83,538.91L567.54,539.12L567.3,540.76L565.18,540.74L564.94,539.36L564.52,537.97L564.28,536.86L564.78,533.43L564.05,531.26L562.71,527L565.66,523.59L566.4,521.44L566.83,521.17L567.14,519.43L566.69,518.55L566.81,516.35L567.36,514.31L567.35,510.62L565.9,509.68L564.56,509.47L563.96,508.75L562.66,508.14L560.32,508.2L560.14,507.12L559.87,505.07L568.38,502.69L569.99,504.07L570.77,503.81L571.87,504.54L572.04,505.69L571.45,507.04L571.65,509.08L573.48,510.87L574.34,508.86L575.55,508.25L575.31,504.53L574.14,502.45L573.13,501.53L572.15,501.57L571.37,497.85z'),
(113,	'NA',	'Namibia',	'M521.08,546.54L519,544.15L517.9,541.85L517.28,538.82L516.59,536.57L515.65,531.85L515.59,528.22L515.23,526.58L514.14,525.34L512.69,522.87L511.22,519.3L510.61,517.45L508.32,514.58L508.15,512.33L509.5,511.78L511.18,511.28L513,511.37L514.67,512.69L515.09,512.48L526.46,512.36L528.4,513.76L535.19,514.17L540.34,512.98L542.64,512.31L544.46,512.48L545.56,513.14L545.59,513.38L544.01,514.04L543.15,514.05L541.37,515.2L540.29,513.99L535.97,515.02L533.88,515.11L533.8,525.68L531.04,525.79L531.04,534.65L531.03,546.17L528.53,547.8L527.03,548.03L525.26,547.43L524,547.2L523.53,545.84L522.42,544.97z'),
(114,	'NC',	'New Caledonia',	'M940.08,523.48L942.38,525.34L943.83,526.72L942.77,527.45L941.22,526.63L939.22,525.28L937.41,523.69L935.56,521.59L935.17,520.58L936.37,520.63L937.95,521.64L939.18,522.65z'),
(115,	'NE',	'Niger',	'M481.29,429.88L481.36,427.93L478.12,427.28L478.04,425.9L476.46,424.03L476.08,422.72L476.3,421.32L478.1,421.21L479.14,420.18L482.96,419.93L485.45,419.48L485.69,417.69L487.23,415.75L487.22,409L491.17,407.68L499.29,401.83L508.9,396.08L513.33,397.39L514.91,399.05L516.89,397.93L517.58,402.6L518.63,403.38L518.68,404.33L519.84,405.35L519.23,406.63L518.15,412.61L518.01,416.4L514.43,419.14L513.22,422.94L514.39,424L514.38,425.85L516.18,425.92L515.9,427.26L515.11,427.43L515.02,428.33L514.49,428.4L512.6,425.27L511.94,425.15L509.75,426.75L507.58,425.92L506.07,425.75L505.26,426.15L503.61,426.07L501.96,427.29L500.53,427.36L497.14,425.88L495.81,426.58L494.38,426.53L493.33,425.45L490.51,424.38L487.5,424.72L486.77,425.34L486.38,426.99L485.57,428.14L485.38,430.68L483.24,429.04L482.23,429.05z'),
(116,	'NG',	'Nigeria',	'M499.09,450.08L496.18,451.08L495.11,450.94L494.03,451.56L491.79,451.5L490.29,449.75L489.37,447.73L487.38,445.89L485.27,445.92L482.8,445.92L482.96,441.39L482.89,439.6L483.42,437.83L484.28,436.96L485.64,435.21L485.35,434.45L485.9,433.31L485.27,431.63L485.38,430.68L485.57,428.14L486.38,426.99L486.77,425.34L487.5,424.72L490.51,424.38L493.33,425.45L494.38,426.53L495.81,426.58L497.14,425.88L500.53,427.36L501.96,427.29L503.61,426.07L505.26,426.15L506.07,425.75L507.58,425.92L509.75,426.75L511.94,425.15L512.6,425.27L514.49,428.4L515.02,428.33L516.13,429.47L515.82,429.98L515.67,430.93L513.31,433.13L512.57,434.94L512.17,436.41L511.58,437.04L511.01,439.01L509.51,440.17L509.08,441.59L508.45,442.73L508.19,443.89L506.26,444.84L504.69,443.69L503.62,443.73L501.95,445.37L501.14,445.4L499.81,448.1z'),
(117,	'NI',	'Nicaragua',	'M234.93,432.31L233.96,431.41L232.65,430.26L232.03,429.3L230.85,428.41L229.44,427.12L229.75,426.68L230.22,427.11L230.43,426.9L231.3,426.79L231.65,426.13L232.06,426.11L232,424.7L232.66,424.63L233.25,424.65L233.85,423.89L234.68,424.47L234.97,424.11L235.48,423.77L236.46,422.98L236.51,422.38L236.78,422.41L237.14,421.72L237.43,421.64L237.91,422.08L238.47,422.21L239.09,421.84L239.8,421.84L240.77,421.46L241.16,421.07L242.12,421.13L241.88,421.41L241.74,422.05L242.02,423.1L241.38,424.08L241.08,425.23L240.98,426.5L241.14,427.23L241.21,428.52L240.78,428.8L240.52,430.02L240.71,430.77L240.13,431.5L240.27,432.26L240.69,432.73L240.02,433.33L239.2,433.14L238.73,432.56L237.84,432.32L237.2,432.69L235.35,431.94z'),
(118,	'NL',	'Netherlands',	'M492.28,285.98L494.61,286.11L495.14,287.69L494.44,291.92L493.73,293.63L492.04,293.63L492.52,298.32L490.97,297.28L489.2,295.33L486.6,296.26L484.55,295.91L485.99,294.67L488.45,287.93z'),
(119,	'NO',	'Norway',	'M554.23,175.61l8.77,6.24l-3.61,2.23l3.07,5.11l-4.77,3.19l-2.26,0.72l1.19,-5.59l-3.6,-3.25l-4.35,2.78l-1.38,5.85l-2.67,3.44l-3.01,-1.87l-3.66,0.38l-3.12,-4.15l-1.68,2.09l-1.74,0.32l-0.41,5.08l-5.28,-1.22l-0.74,4.22l-2.69,-0.03l-1.85,5.24l-2.8,7.87l-4.35,9.5l1.02,2.23l-0.98,2.55l-2.78,-0.11l-1.82,5.91l0.17,8.04l1.79,2.98l-0.93,6.73l-2.33,3.81l-1.24,3.15l-1.88,-3.35l-5.54,6.27l-3.74,1.24l-3.88,-2.71l-1,-5.86l-0.89,-13.26l2.58,-3.88l7.4,-5.18l5.54,-6.59l5.13,-9.3l6.74,-13.76l4.7,-5.67l7.71,-9.89l6.15,-3.59l4.61,0.44l4.27,-6.99l5.11,0.38L554.23,175.61z'),
(120,	'NP',	'Nepal',	'M722.33,382.45L722.11,383.8L722.48,385.79L722.16,387.03L719.83,387.08L716.45,386.35L714.29,386.06L712.67,384.47L708.83,384.06L705.17,382.29L702.53,380.74L699.81,379.54L700.9,376.55L702.68,375.09L703.84,374.31L706.09,375.31L708.92,377.4L710.49,377.86L711.43,379.39L713.61,380.02L715.89,381.41L719.06,382.14z'),
(121,	'NZ',	'New Zealand',	'M960.38,588.63l0.64,1.53l1.99,-1.5l0.81,1.57l0,1.57l-1.04,1.74l-1.83,2.8l-1.43,1.54l1.03,1.86l-2.16,0.05l-2.4,1.46l-0.75,2.57l-1.59,4.03l-2.2,1.8l-1.4,1.16l-2.58,-0.09l-1.82,-1.34l-3.05,-0.28l-0.47,-1.48l1.51,-2.96l3.53,-3.87l1.81,-0.73l2.01,-1.47l2.4,-2.01l1.68,-1.98l1.25,-2.81l1.06,-0.95l0.42,-2.07l1.97,-1.7L960.38,588.63zM964.84,571.61l2.03,3.67l0.06,-2.38l1.27,0.95l0.42,2.65l2.26,1.15l1.89,0.28l1.6,-1.35l1.42,0.41l-0.68,3.15l-0.85,2.09l-2.14,-0.07l-0.75,1.1l0.26,1.56l-0.41,0.68l-1.06,1.97l-1.39,2.53l-2.17,1.49l-0.48,-0.98l-1.17,-0.54l1.62,-3.04l-0.92,-2.01l-3.02,-1.45l0.08,-1.31l2.03,-1.25l0.47,-2.74l-0.13,-2.28l-1.14,-2.34l0.08,-0.61l-1.34,-1.43l-2.21,-3.04l-1.17,-2.41l1.04,-0.27l1.53,1.89l2.18,0.89L964.84,571.61z'),
(122,	'OM',	'Oman',	'M640.29,403.18l-1.05,2.04l-1.27,-0.16l-0.58,0.71l-0.45,1.5l0.34,1.98l-0.26,0.36l-1.29,-0.01l-1.75,1.1l-0.27,1.43l-0.64,0.62l-1.74,-0.02l-1.1,0.74l0.01,1.18l-1.36,0.81l-1.55,-0.27l-1.88,0.98l-1.3,0.16l-0.92,-2.04l-2.19,-4.84l8.41,-2.96l1.87,-5.97l-1.29,-2.14l0.07,-1.22l0.82,-1.26l0.01,-1.25l1.27,-0.6l-0.5,-0.42l0.23,-2l1.43,-0.01l1.26,2.09l1.57,1.11l2.06,0.4l1.66,0.55l1.27,1.74l0.76,1l1,0.38l-0.01,0.67l-1.02,1.79l-0.45,0.84L640.29,403.18zM633.37,388.64L633,389.2l-0.53,-1.06l0.82,-1.06l0.35,0.27L633.37,388.64z'),
(123,	'PA',	'Panama',	'M256.88,443.21L255.95,442.4L255.35,440.88L256.04,440.13L255.33,439.94L254.81,439.01L253.41,438.23L252.18,438.41L251.62,439.39L250.48,440.09L249.87,440.19L249.6,440.78L250.93,442.3L250.17,442.66L249.76,443.08L248.46,443.22L247.97,441.54L247.61,442.02L246.68,441.86L246.12,440.72L244.97,440.54L244.24,440.21L243.04,440.21L242.95,440.82L242.63,440.4L242.78,439.84L243.01,439.27L242.9,438.76L243.32,438.42L242.74,438L242.72,436.87L243.81,436.62L244.81,437.63L244.75,438.23L245.87,438.35L246.14,438.12L246.91,438.82L248.29,438.61L249.48,437.9L251.18,437.33L252.14,436.49L253.69,436.65L253.58,436.93L255.15,437.03L256.4,437.52L257.31,438.36L258.37,439.14L258.03,439.56L258.68,441.21L258.15,442.05L257.24,441.85z'),
(124,	'PE',	'Peru',	'M280.13,513.14L279.38,514.65L277.94,515.39L275.13,513.71L274.88,512.51L269.33,509.59L264.3,506.42L262.13,504.64L260.97,502.27L261.43,501.44L259.06,497.69L256.29,492.45L253.65,486.83L252.5,485.54L251.62,483.48L249.44,481.64L247.44,480.51L248.35,479.26L246.99,476.59L247.86,474.64L250.1,472.87L250.43,474.04L249.63,474.7L249.7,475.72L250.86,475.5L252,475.8L253.17,477.21L254.76,476.06L255.29,474.18L257.01,471.75L260.38,470.65L263.44,467.73L264.31,465.92L263.92,463.81L264.67,463.54L266.53,464.86L267.42,466.18L268.72,466.9L270.37,469.82L272.46,470.17L274.01,469.43L275.02,469.91L276.7,469.67L278.85,470.98L277.04,473.82L277.88,473.88L279.28,475.37L276.75,475.24L276.38,475.66L274.08,476.19L270.88,478.1L270.67,479.4L269.96,480.38L270.24,481.89L268.54,482.7L268.54,483.89L267.8,484.4L268.97,486.93L270.53,488.65L269.94,489.86L271.8,490.02L272.86,491.53L275.33,491.6L277.63,489.94L277.44,494.24L278.72,494.57L280.3,494.08L282.73,498.66L282.12,499.62L281.99,501.64L281.93,504.08L280.83,505.52L281.34,506.59L280.69,507.56L281.9,510z'),
(125,	'PG',	'Papua New Guinea',	'M912.32,482.42l-0.79,0.28l-1.21,-1.08l-1.23,-1.78l-0.6,-2.13l0.39,-0.27l0.3,0.83l0.85,0.63l1.36,1.77l1.32,0.95L912.32,482.42zM901.39,478.67l-1.47,0.23l-0.44,0.79l-1.53,0.68l-1.44,0.66l-1.49,0l-2.3,-0.81l-1.6,-0.78l0.23,-0.87l2.51,0.41l1.53,-0.22l0.42,-1.34l0.4,-0.07l0.27,1.49l1.6,-0.21l0.79,-0.96l1.57,-1l-0.31,-1.65l1.68,-0.05l0.57,0.46l-0.06,1.55L901.39,478.67zM887.96,484.02l2.5,1.84l1.82,2.99l1.61,-0.09l-0.11,1.25l2.17,0.48l-0.84,0.53l2.98,1.19l-0.31,0.82l-1.86,0.2l-0.69,-0.73l-2.41,-0.32l-2.83,-0.43l-2.18,-1.8l-1.59,-1.55l-1.46,-2.46l-3.66,-1.23l-2.38,0.8l-1.71,0.93l0.36,2.08l-2.2,0.97l-1.57,-0.47l-2.9,-0.12l-0.05,-9.16l-0.05,-9.1l4.87,1.92l5.18,1.6l1.93,1.43l1.56,1.41l0.43,1.65l4.67,1.73l0.68,1.49l-2.58,0.3L887.96,484.02zM904.63,475.93l-0.88,0.74l-0.53,-1.65l-0.65,-1.08l-1.27,-0.91l-1.6,-1.19l-2.02,-0.82l0.78,-0.67l1.51,0.78l0.95,0.61l1.18,0.67l1.12,1.17l1.07,0.89L904.63,475.93z'),
(126,	'PH',	'Philippines',	'M829.59,439.86l0.29,1.87l0.17,1.58l-0.96,2.57l-1.02,-2.86l-1.31,1.42l0.9,2.06l-0.8,1.31l-3.3,-1.63l-0.79,-2.03l0.86,-1.33l-1.78,-1.33l-0.88,1.17l-1.32,-0.11l-2.08,1.57l-0.46,-0.82l1.1,-2.37l1.77,-0.79l1.53,-1.06l0.99,1.27l2.13,-0.77l0.46,-1.26l1.98,-0.08l-0.17,-2.18l2.27,1.34l0.24,1.42L829.59,439.86zM822.88,434.6l-1.01,0.93l-0.88,1.79l-0.88,0.84l-1.73,-1.95l0.58,-0.76l0.7,-0.79l0.31,-1.76l1.55,-0.17l-0.45,1.91l2.08,-2.74L822.88,434.6zM807.52,437.32l-3.73,2.67l1.38,-1.97l2.03,-1.74l1.68,-1.96l1.47,-2.82l0.5,2.31l-1.85,1.56L807.52,437.32zM817,430.02l1.68,0.88l1.78,0l-0.05,1.19l-1.3,1.2l-1.78,0.85l-0.1,-1.32l0.2,-1.45L817,430.02zM827.14,429.25l0.79,3.18l-2.16,-0.75l0.06,0.95l0.69,1.75l-1.33,0.63l-0.12,-1.99l-0.84,-0.15l-0.44,-1.72l1.65,0.23l-0.04,-1.08l-1.71,-2.18l2.69,0.06L827.14,429.25zM816,426.66l-0.74,2.47l-1.2,-1.42l-1.43,-2.18l2.4,0.1L816,426.66zM815.42,410.92l1.73,0.84l0.86,-0.76l0.25,0.75l-0.46,1.22l0.96,2.09l-0.74,2.42l-1.65,0.96l-0.44,2.33l0.63,2.29l1.49,0.32l1.24,-0.34l3.5,1.59l-0.27,1.56l0.92,0.69l-0.29,1.32l-2.18,-1.4l-1.04,-1.5l-0.72,1.05l-1.79,-1.72l-2.55,0.42l-1.4,-0.63l0.14,-1.19l0.88,-0.73l-0.84,-0.67l-0.36,1.04l-1.38,-1.65l-0.42,-1.26l-0.1,-2.77l1.13,0.96l0.29,-4.55l0.91,-2.66L815.42,410.92z'),
(127,	'PL',	'Poland',	'M517.36,296.97L516.21,294.11L516.43,292.55L515.73,290.1L514.72,288.45L515.5,287.2L514.84,284.81L516.76,283.42L521.13,281.2L524.67,279.56L527.46,280.38L527.67,281.56L530.38,281.62L533.83,282.17L538.99,282.09L540.43,282.61L541.1,284.07L541.22,286.16L542,287.94L541.98,289.79L540.3,290.73L541.17,292.85L541.22,294.86L542.63,298.75L542.33,299.99L540.94,300.5L538.39,304.11L539.11,306.03L538.5,305.78L535.84,304.14L533.82,304.74L532.5,304.3L530.84,305.22L529.43,303.7L528.27,304.28L528.11,304.02L526.82,301.89L524.74,301.63L524.47,300.26L522.55,299.77L522.13,300.9L520.61,300L520.78,298.79L518.69,298.4z'),
(128,	'PK',	'Pakistan',	'M685.99,351.76L688.06,353.39L688.89,356.05L693.5,357.44L690.79,360.3L687.67,360.81L683.41,359.98L682.04,361.44L683.03,364.39L684,366.64L686.27,368.27L683.88,370.18L683.92,372.51L681.2,375.75L679.44,379.01L676.51,382.33L673.25,382.09L670.16,385.39L672,386.79L672.32,389.18L673.89,390.74L674.45,393.38L668.28,393.37L666.41,395.41L664.36,394.64L663.52,392.44L661.35,390.1L656.19,390.68L651.63,390.73L647.68,391.17L648.74,387.57L652.78,385.96L652.55,384.52L651.21,384.01L651.13,381.24L648.45,379.84L647.32,377.91L645.94,376.23L650.63,377.87L653.44,377.39L655.11,377.79L655.68,377.09L657.63,377.37L661.28,376.04L661.38,373.29L662.94,371.45L665.03,371.45L665.33,370.54L667.48,370.11L668.51,370.41L669.61,369.49L669.46,367.51L670.65,365.51L672.43,364.66L671.33,362.44L674,362.55L674.77,361.33L674.65,360.03L676.05,358.6L675.72,356.9L675.06,355.44L676.7,353.93L679.71,353.2L682.93,352.8L684.35,352.15z'),
(129,	'PR',	'Puerto Rico',	'M289.41,410.89L290.84,411.15L291.35,411.73L290.63,412.47L288.52,412.45L286.88,412.55L286.72,411.3L287.11,410.87z'),
(130,	'PS',	'Palestinian Territories',	'M574.92,367.87L574.92,369.88L574.5,370.84L573.18,371.29L573.31,370.43L574.02,369.97L573.32,369.61L573.9,367.41z'),
(131,	'PT',	'Portugal',	'M449.92,334.56L450.94,333.61L452.08,333.06L452.79,334.9L454.44,334.89L454.92,334.42L456.56,334.55L457.34,336.43L456.04,337.43L456.01,340.31L455.55,340.84L455.44,342.56L454.23,342.86L455.35,345.03L454.58,347.38L455.54,348.44L455.16,349.4L454.12,350.72L454.35,351.88L453.23,352.79L451.75,352.3L450.3,352.68L450.73,349.94L450.47,347.76L449.21,347.43L448.54,346.08L448.77,343.72L449.88,342.41L450.08,340.94L450.67,338.73L450.6,337.16L450.04,335.82z'),
(132,	'PY',	'Paraguay',	'M299.49,526.99L300.6,523.4L300.67,521.8L302.01,519.18L306.9,518.32L309.5,518.37L312.12,519.88L312.16,520.79L312.99,522.45L312.81,526.51L315.77,527.09L316.91,526.5L318.8,527.32L319.33,528.22L319.59,530.99L319.92,532.17L320.96,532.3L322.01,531.81L323.02,532.36L323.02,534.04L322.64,535.86L322.09,537.64L321.63,540.39L319.09,542.79L316.87,543.29L313.72,542.81L310.9,541.96L313.66,537.23L313.25,535.86L310.37,534.66L306.94,532.4L304.65,531.94z'),
(133,	'QA',	'Qatar',	'M617.72,392.16L617.53,389.92L618.29,388.3L619.05,387.96L619.9,388.93L619.95,390.74L619.34,392.55L618.56,392.77z'),
(134,	'RO',	'Romania',	'M538.93,310.86L540.14,309.97L541.88,310.43L543.67,310.45L544.97,311.46L545.93,310.82L548,310.42L548.71,309.44L549.89,309.45L550.74,309.85L551.61,311.09L552.5,312.84L554.12,315.28L554.21,317.07L553.91,318.79L554.42,320.62L555.67,321.35L556.98,320.71L558.26,321.39L558.32,322.42L556.96,323.26L556.11,322.9L555.33,327.61L553.68,327.2L551.64,325.79L548.34,326.69L546.95,327.68L542.83,327.48L540.67,326.87L539.59,327.16L538.78,325.56L538.27,324.88L538.92,324.22L538.22,323.73L537.34,324.61L535.71,323.47L535.49,321.84L533.78,320.9L533.47,319.63L531.95,318.05L534.2,317.29L535.89,314.53L537.22,311.73z'),
(135,	'RS',	'Serbia',	'M533.78,320.9L535.49,321.84L535.71,323.47L537.34,324.61L538.22,323.73L538.92,324.22L538.27,324.88L538.78,325.56L538.09,326.44L538.34,327.86L539.7,329.52L538.63,330.71L538.16,331.92L538.47,332.37L538,332.91L536.71,332.97L535.75,333.19L535.66,332.91L535.99,332.46L536.31,331.53L535.91,331.55L535.36,330.85L534.9,330.67L534.54,330.06L534.01,329.82L533.61,329.28L533.11,329.5L532.72,330.76L532.05,331.04L532.28,330.71L531.21,329.92L530.29,329.51L529.88,328.97L529.14,328.31L529.8,328.14L530.21,326.32L528.86,324.82L529.56,323.1L528.54,323.11L529.62,321.62L528.73,320.48L528.05,318.93L530.2,317.88L531.95,318.05L533.47,319.63z'),
(136,	'RU',	'Russia',	'M1008.27,215.75l-2.78,2.97l-4.6,0.7l-0.07,6.46l-1.12,1.35l-2.63,-0.19l-2.14,-2.26l-3.73,-1.92l-0.63,-2.89l-2.85,-1.1l-3.19,0.87l-1.52,-2.37l0.61,-2.55l-3.36,1.64l1.26,3.19l-1.59,2.83l-0.02,0.04l-3.6,2.89l-3.63,-0.48l2.53,3.44l1.67,5.2l1.29,1.67l0.33,2.53l-0.72,1.6l-5.23,-1.32l-7.84,4.51l-2.49,0.69l-4.29,4.1l-4.07,3.5l-1.03,2.55l-4.01,-3.9l-7.31,4.42l-1.28,-2.08l-2.7,2.39l-3.75,-0.76l-0.9,3.63l-3.36,5.22l0.1,2.14l3.19,1.17l-0.38,7.46l-2.6,0.19l-1.2,4.15l1.17,2.1l-4.9,2.47l-0.97,5.4l-4.18,1.14l-0.84,4.66l-4.04,4.18l-1.04,-3.08l-1.2,-6.69l-1.56,-10.65l1.35,-6.95l2.37,-3.07l0.15,-2.44l4.36,-1.18l5.01,-6.78l4.83,-5.73l5.04,-4.57l2.25,-8.37l-3.41,0.51l-1.68,4.92l-7.11,6.36l-2.3,-7.14l-7.24,2l-7.02,9.56l2.32,3.38l-6.26,1.42l-4.33,0.56l0.2,-3.95l-4.36,-0.84l-3.47,2.7l-8.57,-0.94l-9.22,1.62l-9.08,10.33l-10.75,11.78l4.42,0.61l1.38,3l2.72,1.05l1.79,-2.38l3.08,0.31l4.05,5.19l0.09,3.92l-2.19,4.51l-0.24,5.27l-1.26,6.85l-4.23,6.01l-0.94,2.82l-3.81,4.66l-3.78,4.53l-1.81,2.28l-3.74,2.25l-1.77,0.05l-1.76,-1.86l-3.76,2.79l-0.44,1.26l-0.39,-0.66l-0.02,-1.93l1.43,-0.1l0.4,-4.55l-0.74,-3.36l2.41,-1.4l3.4,0.7l1.89,-3.89l0.96,-4.46l1.09,-1.51l1.47,-3.76l-4.63,1.24l-2.43,1.65l-4.26,0l-1.13,-3.95l-3.32,-3.03l-4.88,-1.38l-1.04,-4.28l-0.98,-2.73l-1.05,-1.94l-1.73,-4.61l-2.46,-1.71l-4.2,-1.39l-3.72,0.13l-3.48,0.84l-2.32,2.31l1.54,1.1l0.04,2.52l-1.56,1.45l-2.53,4.72l0.03,1.93l-3.95,2.74l-3.37,-1.63l-3.35,0.36l-1.47,-1.46l-1.68,-0.47l-4.11,3.06l-3.69,0.71l-2.58,1.06l-3.53,-0.7l-2.6,0.04l-1.7,-2.2l-2.75,-2.09l-2.81,-0.58l-3.55,0.57l-2.65,0.81l-3.98,-1.84l-0.53,-3.32l-3.3,-1.15l-2.54,-0.53l-3.14,-1.87l-2.9,4.66l1.14,2.6l-2.73,3.03l-4.05,-1.09l-2.8,-0.16l-1.87,-2.04l-2.92,-0.06l-2.44,-1.35l-4.26,2.07l-5.35,3.74l-2.96,0.74l-1.1,0.35l-1.49,-2.63l-3.61,0.58l-1.19,-1.84l-1.96,-0.85l-1.35,-2.55l-1.55,-0.8l-4.03,1.14l-3.86,-2.57l-1.49,2.33l-6.27,-11.58l-3.58,-3.66l1.03,-1.5l-7.03,4.49l-2.69,0.27l0.23,-2.58l-3.6,-1.63l-2.93,1.17l-0.88,-5.01l-5.04,-1.06l-2.52,2.03l-7.02,1.79l-1.37,1.19l-10.49,1.66l-1.29,1.62l2.02,3.21l-2.69,1.2l0.53,1.25l-2.69,2.22l4.54,3.1l-0.7,2.11l-3.94,-0.19l-0.81,1.31l-3.59,-2.29l-4.45,0.09l-2.98,1.87l-3.32,-1.79l-6.18,-3.1l-4.38,0.12l-5.79,4.85l-0.35,3.19l-2.88,-2.53l-2.24,4.77l0.82,0.87l-1.62,3.21l2.38,2.84l2.08,-0.12l1.79,2.76l-0.28,2.1l1.42,0.66l-1.28,2.39l-2.72,0.66l-2.79,4.09l2.55,3.7l-0.28,2.59l3.06,4.46l-1.67,1.51l-0.48,0.95l-1.24,-0.25l-1.93,-2.27l-0.79,-0.13l-1.76,-0.87l-0.86,-1.55l-2.62,-0.79l-1.7,0.6l-0.49,-0.71l-3.82,-1.83l-4.13,-0.62l-2.37,-0.66l-0.34,0.45l-3.57,-3.27l-3.2,-1.48l-2.42,-2.32l2.04,-0.64l2.33,-3.35l-1.57,-1.6l4.13,-1.67l-0.07,-0.9l-2.52,0.66l0.09,-1.83l1.45,-1.16l2.71,-0.31l0.44,-1.4l-0.62,-2.33l1.14,-2.23l-0.03,-1.26l-4.13,-1.41l-1.64,0.05l-1.73,-2.04l-2.15,0.69l-3.56,-1.54l0.06,-0.87l-1,-1.93l-2.24,-0.22l-0.23,-1.39l0.7,-0.91l-1.79,-2.58l-2.91,0.44l-0.85,-0.23l-0.71,1.04l-1.05,-0.18l-0.69,-2.94l-0.66,-1.54l0.54,-0.44l2.26,0.16l1.09,-1.02l-0.81,-1.25l-1.89,-0.83l0.17,-0.86l-1.14,-0.87l-1.76,-3.15l0.6,-1.31l-0.27,-2.31l-2.74,-1.18l-1.47,0.59l-0.4,-1.24l-2.95,-1.26l-0.9,-2.99l-0.24,-2.49l-1.35,-1.19l1.2,-1.66l-0.83,-4.96l2,-3.13l-0.42,-0.96l3.19,-3.07l-2.94,-2.68l6,-7.41l2.6,-3.45l1.05,-3.1l-4.15,-4.26l1.15,-4.15l-2.52,-4.85l1.89,-5.76l-3.26,-7.96l2.59,-5.48l-4.29,-4.99l0.41,-5.4l2.26,-0.72l4.77,-3.19l2.89,-2.81l4.61,4.86l7.68,1.88l10.59,8.65l2.15,3.51l0.19,4.8l-3.11,3.69l-4.58,1.85l-12.52,-5.31l-2.06,0.9l4.57,5.1l0.18,3.15l0.18,6.75l3.61,1.97l2.19,1.66l0.36,-3.11l-1.69,-2.8l1.78,-2.51l6.78,4.1l2.36,-1.59l-1.89,-4.88l6.53,-6.74l2.59,0.4l2.62,2.43l1.63,-4.81l-2.34,-4.28l1.37,-4.41l-2.06,-4.69l7.84,2.44l1.6,4.18l-3.55,0.91l0.02,4.04l2.21,2.44l4.33,-1.54l0.69,-4.61l5.86,-3.52l9.79,-6.54l2.11,0.38l-2.76,4.64l3.48,0.78l2.01,-2.58l5.25,-0.21l4.16,-3.19l3.2,4.62l3.19,-5.09l-2.94,-4.58l1.46,-2.66l8.28,2.44l3.88,2.49l10.16,8.8l1.88,-3.97l-2.85,-4.11l-0.08,-1.68l-3.38,-0.78l0.92,-3.83l-1.5,-6.49l-0.08,-2.74l5.17,-7.99l1.84,-8.42l2.08,-1.88l7.42,2.51l0.58,5.18l-2.66,7.28l1.74,2.78l0.9,5.94l-0.64,11.07l3.09,4.73l-1.2,5.01l-5.49,10.2l3.21,1.02l1.12,-2.51l3.08,-1.82l0.74,-3.55l2.43,-3.49l-1.63,-4.26l1.31,-5.08l-3.07,-0.64l-0.67,-4.42l2.24,-8.28l-3.64,-7.03l5.02,-6.04l-0.65,-6.62l1.4,-0.22l1.47,5.19l-1.11,8.67l3,1.59l-1.28,-6.37l4.69,-3.58l5.82,-0.49l5.18,5.18l-2.49,-7.62l-0.28,-10.28l4.88,-2.02l6.74,0.44l6.08,-1.32l-2.28,-5.38l3.25,-7.02l3.22,-0.3l5.45,-5.51l7.4,-1.51l0.94,-3.15l7.36,-1.08l2.29,2.61l6.29,-6.24l5.15,0.2l0.77,-5.24l2.68,-5.33l6.62,-5.31l4.81,4.21l-3.82,3.13l6.35,1.92l0.76,6.03l2.56,-2.94l8.2,0.16l6.32,5.84l2.25,4.35l-0.7,5.85l-3.1,3.24l-7.37,5.92l-2.11,3.08l3.48,1.43l4.15,2.55l2.52,-1.91l1.43,6.39l1.23,-2.56l4.48,-1.57l9,1.65l0.68,4.58l11.72,1.43l0.16,-7.47l5.95,1.74l4.48,-0.05l4.53,5.14l1.29,6.04l-1.66,3.84l3.52,6.98l4.41,3.49l2.71,-9.18l4.5,4l4.78,-2.38l5.43,2.72l2.07,-2.47l4.59,1.24l-2.02,-8.4l3.7,-4.07l25.32,6.06l2.39,5.35l7.34,6.65l11.32,-1.62l5.58,1.41l2.33,3.5l-0.34,6.02l3.45,2.29l3.75,-1.64l4.97,-0.21l5.29,1.57l5.31,-0.89l4.88,6.99l3.47,-2.48l-2.27,-5.07l1.25,-3.62l8.95,2.29l5.83,-0.49l8.06,3.84l3.92,3.44l6.87,5.86l7.35,7.34l-0.24,4.44l1.89,1.74l-0.65,-5.15l7.61,1.07L1008.27,215.75zM880.84,306.25l-2.82,-7.68l-1.16,-4.51l0.07,-4.5l-0.97,-4.5l-0.73,-3.15l-1.25,0.67l1.11,2.21l-2.59,2.17l-0.25,6.3l1.64,4.41l-0.12,5.85l-0.65,3.24l0.32,4.54l-0.31,4.01l0.52,3.4l1.84,-3.13l2.13,2.44l0.08,-2.84l-2.73,-4.23l1.72,-6.11L880.84,306.25zM537.82,278.77l-2.94,-0.86l-3.87,1.58l-0.64,2.13l3.45,0.55l5.16,-0.07l-0.22,-1.23l0.3,-1.33L537.82,278.77zM979.95,178.65l3.66,-0.52l2.89,-2.06l0.24,-1.19l-4.06,-2.51l-2.38,-0.02l-0.36,0.37l-3.57,3.64l0.5,2.73L979.95,178.65zM870.07,151.56l-2.66,3.92l0.49,0.52l5.75,1.08l4.25,-0.07l-0.34,-2.57l-3.98,-3.81L870.07,151.56zM894.64,142.03l3.24,-4.25l-7.04,-2.88l-5.23,-1.68l-0.67,3.59l5.21,4.27L894.64,142.03zM869.51,140.34l10.33,0.3l2.21,-8.14l-10.13,-6.07l-7.4,-0.51l-3.7,2.18l-1.51,7.75l5.55,7.01L869.51,140.34zM622.39,166.28l-2.87,1.96l0.41,4.83l5.08,2.35l0.74,3.82l9.16,1.1l1.66,-0.74l-5.36,-7.11l-0.57,-7.52l4.39,-9.14l4.18,-9.82l8.71,-10.17l8.56,-5.34l9.93,-5.74l1.88,-3.71l-1.95,-4.83l-5.46,1.6l-4.8,4.49l-9.33,2.22l-9.26,7.41l-6.27,5.85l0.76,4.87l-6.71,9.03l2.58,1.22l-5.56,8.27L622.39,166.28zM769.87,98.34l0.83,-5.72l-7.11,-8.34l-2.11,-0.98l-2.3,1.7l-5.12,18.6L769.87,98.34zM605.64,69.03l3.04,3.88l3.28,-2.69l0.39,-2.72l2.52,-1.27l3.76,-2.23l1.08,-2.62l-4.16,-3.85l-2.64,2.9l-1.61,4.12l-0.57,-4.65l-4.26,0.21L601,63.25l6.24,0.52L605.64,69.03zM736.89,82.07l4.65,5.73l7.81,4.2l6.12,-1.8l0.69,-13.62l-6.46,-16.04l-5.45,-9.02l-6.07,4.11l-7.28,11.83l3.83,3.27L736.89,82.07z'),
(137,	'RW',	'Rwanda',	'M560.54,466.55L561.66,468.12L561.49,469.76L560.69,470.11L559.2,469.93L558.34,471.52L556.63,471.3L556.89,469.77L557.28,469.56L557.38,467.9L558.19,467.12L558.87,467.41z'),
(138,	'SA',	'Saudi Arabia',	'M595.2,417.22L594.84,415.98L593.99,415.1L593.77,413.93L592.33,412.89L590.83,410.43L590.04,408.02L588.1,405.98L586.85,405.5L584.99,402.65L584.67,400.57L584.79,398.78L583.18,395.42L581.87,394.23L580.35,393.6L579.43,391.84L579.58,391.15L578.8,389.55L577.98,388.86L576.89,386.54L575.18,384.02L573.75,381.86L572.36,381.87L572.79,380.13L572.92,379.02L573.26,377.74L576.38,378.25L577.6,377.27L578.27,376.11L580.41,375.67L580.87,374.58L581.8,374.04L579,370.78L584.62,369.13L585.15,368.64L588.53,369.53L592.71,371.82L600.61,378.31L605.82,378.57L608.32,378.88L609.02,380.39L611,380.31L612.1,383.04L613.48,383.75L613.96,384.86L615.87,386.17L616.04,387.46L615.76,388.49L616.12,389.53L616.92,390.4L617.3,391.41L617.72,392.16L618.56,392.77L619.34,392.55L619.87,393.72L619.98,394.43L621.06,397.51L629.48,399.03L630.05,398.39L631.33,400.53L629.46,406.5L621.05,409.46L612.97,410.59L610.35,411.91L608.34,414.98L607.03,415.46L606.33,414.49L605.26,414.64L602.55,414.35L602.03,414.05L598.8,414.12L598.04,414.39L596.89,413.63L596.14,415.06L596.43,416.29z'),
(139,	'SB',	'Solomon Islands',	'M929.81,492.75l0.78,0.97l-1.96,-0.02l-1.07,-1.74l1.67,0.69L929.81,492.75zM926.26,491.02l-1.09,0.06l-1.72,-0.29l-0.59,-0.44l0.18,-1.12l1.85,0.44l0.91,0.59L926.26,491.02zM928.58,490.25l-0.42,0.52l-2.08,-2.45l-0.58,-1.68h0.95l1.01,2.25L928.58,490.25zM923.52,486.69l0.12,0.57l-2.2,-1.19l-1.54,-1.01l-1.05,-0.94l0.42,-0.29l1.29,0.67l2.3,1.29L923.52,486.69zM916.97,483.91l-0.56,0.16l-1.23,-0.64l-1.15,-1.15l0.14,-0.47l1.67,1.18L916.97,483.91z'),
(140,	'SD',	'Sudan',	'M570.48,436.9L570.09,436.85L570.14,435.44L569.8,434.47L568.36,433.35L568.02,431.3L568.36,429.2L567.06,429.01L566.87,429.64L565.18,429.79L565.86,430.62L566.1,432.33L564.56,433.89L563.16,435.93L561.72,436.22L559.36,434.57L558.3,435.15L558.01,435.98L556.57,436.51L556.47,437.09L553.68,437.09L553.29,436.51L551.27,436.41L550.26,436.9L549.49,436.65L548.05,435L547.57,434.23L545.54,434.62L544.77,435.93L544.05,438.45L543.09,438.98L542.23,439.29L542,439.15L541.03,438.34L540.85,437.47L541.3,436.29L541.3,435.14L539.68,433.37L539.36,432.15L539.39,431.46L538.36,430.63L538.33,428.97L537.75,427.87L536.76,428.04L537.04,426.99L537.77,425.79L537.45,424.61L538.37,423.73L537.79,423.06L538.53,421.28L539.81,419.15L542.23,419.35L542.09,407.74L542.13,406.5L545.35,406.49L545.35,400.53L556.62,400.53L567.5,400.53L578.62,400.53L579.52,403.47L578.91,404.01L579.32,407.07L580.35,410.59L581.41,411.32L582.95,412.4L581.53,414.07L579.46,414.55L578.58,415.45L578.31,417.38L577.1,421.63L577.4,422.78L576.95,425.25L575.81,428.06L574.12,429.48L572.92,431.65L572.63,432.81L571.31,433.61L570.48,436.57z'),
(141,	'SE',	'Sweden',	'M537.45,217.49L534.73,222.18L535.17,226.2L530.71,231.33L525.3,236.67L523.25,245.08L525.25,249.15L527.93,252.29L525.36,258.52L522.44,259.78L521.37,268.62L519.78,273.38L516.38,272.89L514.79,276.84L511.54,277.07L510.65,272.36L508.3,266.55L506.17,259.05L507.41,255.9L509.74,252.09L510.67,245.36L508.88,242.38L508.7,234.34L510.53,228.43L513.31,228.54L514.28,225.99L513.26,223.76L517.61,214.26L520.42,206.39L522.27,201.15L524.96,201.17L525.71,196.96L530.99,198.18L531.4,193.1L533.14,192.77L536.88,196.58L541.25,201.73L541.33,212.85L542.27,215.55z'),
(142,	'SI',	'Slovenia',	'M513.96,316.51L516.28,316.82L517.7,315.9L520.15,315.8L520.68,315.11L521.15,315.16L521.7,316.53L519.47,317.61L519.19,319.23L518.22,319.64L518.23,320.76L517.13,320.68L516.18,320.03L515.66,320.71L513.71,320.57L514.33,320.21L513.66,318.5z'),
(143,	'SJ',	'Svalbard and Jan Mayen',	'M544.58,104.49l-6.26,5.36l-4.95,-3.02l1.94,-3.42l-1.69,-4.34l5.81,-2.78l1.11,5.18L544.58,104.49zM526.43,77.81l9.23,11.29l-7.06,5.66l-1.56,10.09l-2.46,2.49l-1.33,10.51l-3.38,0.48l-6.03,-7.64l2.54,-4.62l-4.2,-3.86l-5.46,-11.82l-2.18,-11.79l7.64,-5.69l1.54,5.56l3.99,-0.22l1.06,-5.43l4.12,-0.56L526.43,77.81zM546.6,66.35l5.5,5.8l-4.16,8.52l-8.13,1.81l-8.27,-2.56l-0.5,-4.32l-4.02,-0.28l-3.07,-7.48l8.66,-4.72l4.07,4.08l2.84,-5.09L546.6,66.35z'),
(144,	'SK',	'Slovakia',	'M528.11,304.02L528.27,304.28L529.43,303.7L530.84,305.22L532.5,304.3L533.82,304.74L535.84,304.14L538.5,305.78L537.73,306.89L537.18,308.6L536.58,309.03L533.58,307.75L532.66,308L532,309L530.68,309.52L530.38,309.25L529.02,309.9L527.9,310.03L527.68,310.87L525.32,311.38L524.29,310.92L522.86,309.85L522.58,308.4L522.81,307.86L523.2,306.93L524.45,307L525.4,306.56L525.48,306.17L526.02,305.96L526.2,304.99L526.84,304.8L527.28,304.03z'),
(145,	'SL',	'Sierra Leone',	'M443.18,444.44L442.42,444.23L440.41,443.1L438.95,441.6L438.46,440.57L438.11,438.49L439.61,437.25L439.93,436.46L440.41,435.85L441.19,435.79L441.84,435.26L444.08,435.26L444.86,436.27L445.47,437.46L445.38,438.28L445.83,439.02L445.8,440.05L446.57,439.89L445.26,441.2L444,442.73L443.85,443.54z'),
(146,	'SN',	'Senegal',	'M428.39,425.16L427.23,422.92L425.83,421.9L427.07,421.35L428.43,419.32L429.09,417.83L430.05,416.9L431.45,417.15L432.81,416.52L434.38,416.49L435.72,417.34L437.58,418.11L439.28,420.24L441.13,422.22L441.26,424.01L441.81,425.65L442.86,426.46L443.1,427.56L442.97,428.45L442.56,428.61L441.04,428.39L440.83,428.7L440.21,428.77L438.19,428.07L436.84,428.04L431.66,427.92L430.91,428.24L429.98,428.15L428.49,428.62L428.03,426.43L430.58,426.49L431.26,426.09L431.76,426.06L432.8,425.4L434,426.01L435.22,426.06L436.43,425.41L435.87,424.59L434.94,425.07L434.07,425.06L432.97,424.35L432.08,424.4L431.44,425.07z'),
(147,	'SO',	'Somalia',	'M618.63,430.43L618.56,429.64L617.5,429.65L616.17,430.63L614.68,430.91L613.39,431.33L612.5,431.39L610.9,431.49L609.9,432.01L608.51,432.2L606.04,433.08L602.99,433.41L600.34,434.14L598.95,434.13L597.69,432.94L597.14,431.77L596.23,431.24L595.19,432.76L594.58,433.77L595.62,435.33L596.65,436.69L597.72,437.7L606.89,441.04L609.25,441.02L601.32,449.44L597.67,449.56L595.17,451.53L593.38,451.58L592.61,452.46L590.16,455.63L590.19,465.78L591.85,468.07L592.48,467.41L593.13,465.95L596.2,462.57L598.81,460.45L603.01,457.69L605.81,455.43L609.11,451.62L611.5,448.49L613.91,444.39L615.64,440.8L616.99,437.65L617.78,434.6L618.38,433.58L618.37,432.08z'),
(148,	'SR',	'Suriname',	'M315.02,446.72L318.38,447.28L318.68,446.77L320.95,446.57L323.96,447.33L322.5,449.73L322.72,451.64L323.83,453.3L323.34,454.5L323.09,455.77L322.37,456.94L320.77,456.35L319.44,456.64L318.31,456.39L318.03,457.2L318.5,457.75L318.25,458.32L316.72,458.09L315.01,455.67L314.64,454.1L313.75,454.09L312.5,452.07L313.02,450.62L312.87,449.97L314.57,449.24z'),
(149,	'SS',	'South Sudan',	'M570.48,436.9L570.51,439.1L570.09,439.96L568.61,440.03L567.65,441.64L569.37,441.84L570.79,443.21L571.29,444.33L572.57,444.98L574.22,448.03L572.32,449.87L570.6,451.54L568.87,452.82L566.9,452.82L564.64,453.47L562.86,452.84L561.71,453.61L559.24,451.75L558.57,450.56L557.01,451.15L555.71,450.96L554.96,451.43L553.7,451.1L552.01,448.79L551.56,447.9L549.46,446.79L548.75,445.11L547.58,443.9L545.7,442.44L545.67,441.52L544.14,440.39L542.23,439.29L543.09,438.98L544.05,438.45L544.77,435.93L545.54,434.62L547.57,434.23L548.05,435L549.49,436.65L550.26,436.9L551.27,436.41L553.29,436.51L553.68,437.09L556.47,437.09L556.57,436.51L558.01,435.98L558.3,435.15L559.36,434.57L561.72,436.22L563.16,435.93L564.56,433.89L566.1,432.33L565.86,430.62L565.18,429.79L566.87,429.64L567.06,429.01L568.36,429.2L568.02,431.3L568.36,433.35L569.8,434.47L570.14,435.44L570.09,436.85z'),
(150,	'SV',	'El Salvador',	'M229.09,425.76L228.78,426.43L227.16,426.39L226.15,426.12L224.99,425.55L223.43,425.37L222.64,424.75L222.73,424.33L223.69,423.61L224.21,423.29L224.06,422.95L224.72,422.78L225.55,423.02L226.15,423.59L227,424.05L227.1,424.44L228.33,424.1L228.91,424.3L229.29,424.61z'),
(151,	'SY',	'Syria',	'M584.02,364.6L578.53,368.14L575.41,366.82L575.35,366.8L575.73,366.3L575.69,364.93L576.38,363.1L577.91,361.83L577.45,360.51L576.19,360.33L575.93,357.72L576.61,356.31L577.36,355.56L578.11,354.8L578.27,352.86L579.18,353.54L582.27,352.57L583.76,353.22L586.07,353.21L589.29,351.9L590.81,351.96L594,351.42L592.56,353.6L591.02,354.46L591.29,356.98L590.23,361.1z'),
(152,	'SZ',	'Swaziland',	'M565.18,540.74L564.61,542.13L562.97,542.46L561.29,540.77L561.27,539.69L562.03,538.52L562.3,537.62L563.11,537.4L564.52,537.97L564.94,539.36z'),
(153,	'TD',	'Chad',	'M515.9,427.26L516.18,425.92L514.38,425.85L514.39,424L513.22,422.94L514.43,419.14L518.01,416.4L518.15,412.61L519.23,406.63L519.84,405.35L518.68,404.33L518.63,403.38L517.58,402.6L516.89,397.93L519.72,396.27L530.91,402.04L542.09,407.74L542.23,419.35L539.81,419.15L538.53,421.28L537.79,423.06L538.37,423.73L537.45,424.61L537.77,425.79L537.04,426.99L536.76,428.04L537.75,427.87L538.33,428.97L538.36,430.63L539.39,431.46L539.36,432.15L537.59,432.64L536.16,433.78L534.14,436.87L531.5,438.18L528.79,438L528,438.26L528.28,439.25L526.81,440.24L525.62,441.34L522.09,442.41L521.39,441.78L520.93,441.72L520.41,442.44L518.09,442.66L518.53,441.89L517.65,439.96L517.25,438.79L516.03,438.31L514.38,436.66L514.99,435.33L516.27,435.61L517.06,435.41L518.62,435.44L517.1,432.87L517.2,430.98L517.01,429.09z'),
(154,	'TF',	'French Southern and Antarctic Lands',	'M668.54,619.03L670.34,620.36L672.99,620.9L673.09,621.71L672.31,623.67L668,623.95L667.93,621.66L668.35,619.9z'),
(155,	'TG',	'Togo',	'M480.48,446.25L478.23,446.84L477.6,445.86L476.85,444.08L476.63,442.68L477.25,440.15L476.55,439.12L476.28,436.9L476.28,434.85L475.11,433.39L475.32,432.5L477.78,432.56L477.42,434.06L478.27,434.89L479.25,435.88L479.35,437.27L479.92,437.85L479.79,444.31z'),
(156,	'TH',	'Thailand',	'M762.89,429.18L760.37,427.87L757.97,427.93L758.38,425.68L755.91,425.7L755.69,428.84L754.18,432.99L753.27,435.49L753.46,437.54L755.28,437.63L756.42,440.2L756.93,442.63L758.49,444.24L760.19,444.57L761.64,446.02L760.73,447.17L758.87,447.51L758.65,446.07L756.37,444.84L755.88,445.34L754.77,444.27L754.29,442.88L752.8,441.29L751.44,439.96L750.98,441.61L750.45,440.05L750.76,438.29L751.58,435.58L752.94,432.67L754.48,430.02L753.38,427.42L753.43,426.09L753.11,424.49L751.24,422.21L750.57,420.76L751.54,420.23L752.56,417.71L751.42,415.79L749.64,413.66L748.28,411.09L749.46,410.56L750.74,407.37L752.72,407.23L754.36,405.95L755.96,405.26L757.18,406.18L757.34,407.96L759.23,408.09L758.54,411.2L758.61,413.82L761.56,412.08L762.4,412.59L764.05,412.51L764.61,411.49L766.73,411.69L768.86,414.07L769.04,416.94L771.31,419.47L771.18,421.91L770.27,423.21L767.64,422.8L764.02,423.35L762.22,425.73z'),
(157,	'TJ',	'Tajikistan',	'M674.37,340.62L673.34,341.75L670.29,341.14L670.02,343.24L673.06,342.96L676.53,344.13L681.83,343.58L682.54,346.91L683.46,346.55L685.16,347.36L685.07,348.74L685.49,350.75L682.59,350.75L680.66,350.49L678.92,352.06L677.67,352.4L676.69,353.14L675.58,351.99L675.85,349.04L675,348.87L675.3,347.78L673.79,346.98L672.58,348.21L672.28,349.64L671.85,350.16L670.17,350.09L669.27,351.69L668.32,351.02L666.29,352.14L665.44,351.72L667.01,348.15L666.41,345.49L664.35,344.63L665.08,343.04L667.42,343.21L668.75,341.2L669.64,338.85L673.39,337.99L672.81,339.7L673.21,340.72z'),
(158,	'TL',	'Timor-Leste',	'M825.65,488.25L825.98,487.59L828.39,486.96L830.35,486.86L831.22,486.51L832.28,486.86L831.25,487.62L828.33,488.85L825.98,489.67L825.93,488.81z'),
(159,	'TM',	'Turkmenistan',	'M646.88,356.9L646.63,353.99L644.54,353.87L641.34,350.78L639.1,350.39L636,348.6L634,348.27L632.77,348.93L630.9,348.83L628.91,350.85L626.44,351.53L625.92,349.04L626.33,345.31L624.14,344.09L624.86,341.61L623,341.39L623.62,338.3L626.26,339.21L628.73,338.02L626.68,335.79L625.88,333.65L623.62,334.61L623.34,337.34L622.46,334.93L623.7,333.68L626.88,332.89L628.78,333.95L630.74,336.88L632.18,336.7L635.34,336.65L634.88,334.77L637.28,333.47L639.64,331.27L643.42,333.27L643.72,336.26L644.79,337.03L647.82,336.86L648.76,337.53L650.14,341.32L653.35,343.83L655.18,345.52L658.11,347.27L661.84,348.79L661.76,350.95L660.92,350.84L659.59,349.9L659.15,351.15L656.79,351.83L656.23,354.62L654.65,355.67L652.44,356.19L651.85,357.74L649.74,358.2z'),
(160,	'TN',	'Tunisia',	'M501.84,374.69L500.64,368.83L498.92,367.5L498.89,366.69L496.6,364.71L496.35,362.18L498.08,360.3L498.74,357.48L498.29,354.2L498.86,352.41L501.92,351L503.88,351.42L503.8,353.19L506.18,351.9L506.38,352.57L504.97,354.28L504.96,355.88L505.93,356.73L505.56,359.69L503.71,361.4L504.24,363.23L505.69,363.29L506.4,364.88L507.47,365.4L507.31,367.95L505.94,368.9L505.08,369.95L503.15,371.21L503.45,372.56L503.21,373.94z'),
(161,	'TR',	'Turkey',	'M578.75,336.6l4.02,1.43l3.27,-0.57l2.41,0.33l3.31,-1.94l2.99,-0.18l2.7,1.83l0.48,1.3l-0.27,1.79l2.08,0.91l1.1,1.06l-1.92,1.03l0.88,4.11l-0.55,1.1l1.53,2.82l-1.34,0.59l-0.98,-0.89l-3.26,-0.45l-1.2,0.55l-3.19,0.54l-1.51,-0.06l-3.23,1.31l-2.31,0.01l-1.49,-0.66l-3.09,0.97l-0.92,-0.68l-0.15,1.94l-0.75,0.76l-0.75,0.76l-1.03,-1.57l1.06,-1.3l-1.71,0.3l-2.35,-0.8l-1.93,2l-4.26,0.39l-2.27,-1.86l-3.02,-0.12l-0.65,1.44l-1.94,0.41l-2.71,-1.85l-3.06,0.06l-1.66,-3.48l-2.05,-1.96l1.36,-2.78l-1.78,-1.72l3.11,-3.48l4.32,-0.15l1.18,-2.81l5.34,0.49l3.37,-2.42l3.27,-1.06l4.64,-0.08L578.75,336.6zM551.5,338.99l-2.34,1.98l-0.88,-1.71l0.04,-0.76l0.67,-0.41l0.87,-2.33l-1.37,-0.99l2.86,-1.18l2.41,0.5l0.33,1.44l2.45,1.2l-0.51,0.91l-3.33,0.2L551.5,338.99z'),
(162,	'TT',	'Trinidad and Tobago',	'M302.31,433.24L303.92,432.87L304.51,432.97L304.4,435.08L302.06,435.39L301.55,435.14L302.37,434.36z'),
(163,	'TW',	'Taiwan',	'M816.7,393.27L815.01,398.14L813.81,400.62L812.33,398.07L812.01,395.82L813.66,392.82L815.91,390.5L817.19,391.41z'),
(164,	'TZ',	'Tanzania',	'M570.31,466.03L570.79,466.34L580.95,472.01L581.15,473.63L585.17,476.42L583.88,479.87L584.04,481.46L585.84,482.48L585.92,483.21L585.15,484.91L585.31,485.76L585.13,487.11L586.11,488.87L587.27,491.66L588.29,492.28L586.06,493.92L583,495.02L581.32,494.98L580.32,495.83L578.37,495.9L577.63,496.26L574.26,495.46L572.15,495.69L571.37,491.83L570.42,490.51L569.85,489.73L567.11,489.21L565.51,488.36L563.73,487.89L562.61,487.41L561.44,486.7L559.93,483.15L558.3,481.58L557.74,479.96L558.02,478.5L557.52,475.93L558.68,475.8L559.69,474.79L560.79,473.33L561.48,472.75L561.45,471.84L560.85,471.21L560.69,470.11L561.49,469.76L561.66,468.12L560.54,466.55L561.53,466.21L564.6,466.25z'),
(165,	'UA',	'Ukraine',	'M564.38,292.49L565.42,292.68L566.13,291.64L566.98,291.87L569.89,291.43L571.68,294L570.98,294.92L571.21,296.31L573.45,296.52L574.45,298.45L574.39,299.32L577.95,300.86L580.1,300.17L581.83,302.21L583.47,302.16L587.6,303.57L587.63,304.84L586.5,307.07L587.11,309.4L586.67,310.79L583.96,311.1L582.51,312.26L582.43,314.09L580.19,314.42L578.32,315.74L575.7,315.95L573.28,317.47L573.45,319.97L574.82,320.93L577.68,320.69L577.13,322.11L574.06,322.79L570.25,325.06L568.7,324.27L569.31,322.42L566.25,321.26L566.75,320.49L569.43,319.16L568.62,318.24L564.26,317.22L564.07,315.71L561.47,316.21L560.43,318.44L558.26,321.39L556.98,320.71L555.67,321.35L554.42,320.62L555.12,320.18L555.61,318.81L556.38,317.52L556.18,316.8L556.77,316.47L557.04,317.04L558.7,317.15L559.44,316.86L558.92,316.44L559.11,315.84L558.13,314.8L557.73,313.08L556.71,312.41L556.91,311L555.64,309.88L554.49,309.72L552.42,308.41L550.56,308.83L549.89,309.45L548.71,309.44L548,310.42L545.93,310.82L544.97,311.46L543.67,310.45L541.88,310.43L540.14,309.97L538.93,310.86L538.73,309.74L537.18,308.6L537.73,306.89L538.5,305.78L539.11,306.03L538.39,304.11L540.94,300.5L542.33,299.99L542.63,298.75L541.22,294.86L542.56,294.69L544.1,293.46L546.27,293.36L549.1,293.72L552.23,294.8L554.44,294.89L555.49,295.54L556.54,294.76L557.28,295.81L559.81,295.59L560.92,296.02L561.11,293.76L561.97,292.76z'),
(166,	'UG',	'Uganda',	'M564.6,466.25L561.53,466.21L560.54,466.55L558.87,467.41L558.19,467.12L558.21,465.02L558.86,463.96L559.02,461.72L559.61,460.43L560.68,458.97L561.76,458.23L562.66,457.24L561.54,456.87L561.71,453.61L562.86,452.84L564.64,453.47L566.9,452.82L568.87,452.82L570.6,451.54L571.93,453.48L572.26,454.88L573.49,458.08L572.47,460.11L571.09,461.95L570.29,463.08L570.31,466.03z'),
(167,	'US',	'United States',	'M109.25,279.8L109.25,279.8l-1.54,-1.83l-2.47,-1.57l-0.79,-4.36l-3.61,-4.13l-1.51,-4.94l-2.69,-0.34l-4.46,-0.13l-3.29,-1.54l-5.8,-5.64l-2.68,-1.05l-4.9,-1.99l-3.88,0.48l-5.51,-2.59l-3.33,-2.43l-3.11,1.21l0.58,3.93l-1.55,0.36l-3.24,1.16l-2.47,1.86l-3.11,1.16l-0.4,-3.24l1.26,-5.53l2.98,-1.77l-0.77,-1.46l-3.57,3.22l-1.91,3.77l-4.04,3.95l2.05,2.65l-2.65,3.85l-3.01,2.21l-2.81,1.59l-0.69,2.29l-4.38,2.63l-0.89,2.36l-3.28,2.13l-1.92,-0.38l-2.62,1.38l-2.85,1.67l-2.33,1.63l-4.81,1.38l-0.44,-0.81l3.07,-2.27l2.74,-1.51l2.99,-2.71l3.48,-0.56l1.38,-2.06l3.89,-3.05l0.63,-1.03l2.07,-1.83l0.48,-4l1.43,-3.17l-3.23,1.64l-0.9,-0.93l-1.52,1.95l-1.83,-2.73l-0.76,1.94l-1.05,-2.7l-2.8,2.17l-1.72,0l-0.24,-3.23l0.51,-2.02l-1.81,-1.98l-3.65,1.07l-2.37,-2.63l-1.92,-1.36l-0.01,-3.25l-2.16,-2.48l1.08,-3.41l2.29,-3.37l1,-3.15l2.27,-0.45l1.92,0.99l2.26,-3.01l2.04,0.54l2.14,-1.96l-0.52,-2.92l-1.57,-1.16l2.08,-2.52l-1.72,0.07l-2.98,1.43l-0.85,1.43l-2.21,-1.43l-3.97,0.73l-4.11,-1.56l-1.18,-2.65l-3.55,-3.91l3.94,-2.87l6.25,-3.41h2.31l-0.38,3.48l5.92,-0.27l-2.28,-4.34l-3.45,-2.72l-1.99,-3.64l-2.69,-3.17l-3.85,-2.38l1.57,-4.03l4.97,-0.25l3.54,-3.58l0.67,-3.92l2.86,-3.91l2.73,-0.95l5.31,-3.76l2.58,0.57l4.31,-4.61l4.24,1.83l2.03,3.87l1.25,-1.65l4.74,0.51l-0.17,1.95l4.29,1.43l2.86,-0.84l5.91,2.64l5.39,0.78l2.16,1.07l3.73,-1.34l4.25,2.46l3.05,1.13l-0.02,27.65l-0.01,35.43l2.76,0.17l2.73,1.56l1.96,2.44l2.49,3.6l2.73,-3.05l2.81,-1.79l1.49,2.85l1.89,2.23l2.57,2.42l1.75,3.79l2.87,5.88l4.77,3.2l0.08,3.12L109.25,279.8zM285.18,314.23l-1.25,-1.19l-1.88,0.7l-0.93,-1.08l-2.14,3.1l-0.86,3.15l-1,1.82l-1.19,0.62l-0.9,0.2l-0.28,0.98l-5.17,0l-4.26,0.03l-1.27,0.73l-2.87,2.73l0.29,0.54l0.17,1.51l-2.1,1.27l-2.3,-0.32l-2.2,-0.14l-1.33,0.44l0.25,1.15l0,0l0.05,0.37l-2.42,2.27l-2.11,1.09l-1.44,0.51l-1.66,1.03l-2.03,0.5l-1.4,-0.19l-1.73,-0.77l0.96,-1.45l0.62,-1.32l1.32,-2.09l-0.14,-1.57l-0.5,-2.24l-1.04,-0.39l-1.74,1.7l-0.56,-0.03l-0.14,-0.97l1.54,-1.56l0.26,-1.79l-0.23,-1.79l-2.08,-1.55l-2.38,-0.8l-0.39,1.52l-0.62,0.4l-0.5,1.95l-0.26,-1.33l-1.12,0.95l-0.7,1.32l-0.73,1.92l-0.14,1.64l0.93,2.38l-0.08,2.51l-1.14,1.84l-0.57,0.52l-0.76,0.41l-0.95,0.02l-0.26,-0.25l-0.76,-1.98l-0.02,-0.98l0.08,-0.94l-0.35,-1.87l0.53,-2.18l0.63,-2.71l1.46,-3.03l-0.42,0.01l-2.06,2.54l-0.38,-0.46l1.1,-1.42l1.67,-2.57l1.91,-0.36l2.19,-0.8l2.21,0.42l0.09,0.02l2.47,-0.36l-1.4,-1.61l-0.75,-0.13l-0.86,-0.16l-0.59,-1.14l-2.75,0.36l-2.49,0.9l-1.97,-1.55l-1.59,-0.52l0.9,-2.17l-2.48,1.37l-2.25,1.33l-2.16,1.04l-1.72,-1.4l-2.81,0.85l0.01,-0.6l1.9,-1.73l1.99,-1.65l2.86,-1.37l-3.45,-1.09l-2.27,0.55l-2.72,-1.3l-2.86,-0.67l-1.96,-0.26l-0.87,-0.72l-0.5,-2.35l-0.95,0.02l-0.01,1.64l-5.8,0l-9.59,0l-9.53,0l-8.42,0h-8.41h-8.27h-8.55h-2.76h-8.32h-7.96l0.95,3.47l0.45,3.41l-0.69,1.09l-1.49,-3.91l-4.05,-1.42l-0.34,0.82l0.82,1.94l0.89,3.53l0.51,5.42l-0.34,3.59l-0.34,3.54l-1.1,3.61l0.9,2.9l0.1,3.2l-0.61,3.05l1.49,1.99l0.39,2.95l2.17,2.99l1.24,1.17l-0.1,0.82l2.34,4.85l2.72,3.45l0.34,1.87l0.71,0.55l2.6,0.33l1,0.91l1.57,0.17l0.31,0.96l1.31,0.4l1.82,1.92l0.47,1.7l3.19,-0.25l3.56,-0.36l-0.26,0.65l4.23,1.6l6.4,2.31l5.58,-0.02l2.22,0l0.01,-1.35l4.86,0l1.02,1.16l1.43,1.03l1.67,1.43l0.93,1.69l0.7,1.77l1.45,0.97l2.33,0.96l1.77,-2.53l2.29,-0.06l1.98,1.28l1.41,2.18l0.97,1.86l1.65,1.8l0.62,2.19l0.79,1.47l2.19,0.96l1.99,0.68l1.09,-0.09l-0.53,-1.06l-0.14,-1.5l0.03,-2.16l0.65,-1.42l1.53,-1.51l2.79,-1.37l2.55,-2.37l2.36,-0.75l1.74,-0.23l2.04,0.74l2.45,-0.4l2.09,1.69l2.03,0.1l1.05,-0.61l1.04,0.47l0.53,-0.42l-0.6,-0.63l0.05,-1.3l-0.5,-0.86l1.16,-0.5l2.14,-0.22l2.49,0.36l3.17,-0.41l1.76,0.8l1.36,1.5l0.5,0.16l2.83,-1.46l1.09,0.49l2.19,2.68l0.79,1.75l-0.58,2.1l0.42,1.23l1.3,2.4l1.49,2.68l1.07,0.71l0.44,1.35l1.38,0.37l0.84,-0.39l0.7,-1.89l0.12,-1.21l0.09,-2.1l-1.33,-3.65l-0.02,-1.37l-1.25,-2.25l-0.94,-2.75l-0.5,-2.25l0.43,-2.31l1.32,-1.94l1.58,-1.57l3.08,-2.16l0.4,-1.12l1.42,-1.23l1.4,-0.22l1.84,-1.98l2.9,-1.01l1.78,-2.53l-0.39,-3.46l-0.29,-1.21l-0.8,-0.24l-0.12,-3.35l-1.93,-1.14l1.85,0.56l-0.6,-2.26l0.54,-1.55l0.33,2.97l1.43,1.36l-0.87,2.4l0.26,0.14l1.58,-2.81l0.9,-1.38l-0.04,-1.35l-0.7,-0.64l-0.58,-1.94l0.92,0.9l0.62,0.19l0.21,0.92l2.04,-2.78l0.61,-2.62l-0.83,-0.17l0.85,-1.02l-0.08,0.45l1.79,-0.01l3.93,-1.11l-0.83,-0.7l-4.12,0.7l2.34,-1.07l1.63,-0.18l1.22,-0.19l2.07,-0.65l1.35,0.07l1.89,-0.61l0.22,-1.07l-0.84,-0.84l0.29,1.37l-1.16,-0.09l-0.93,-1.99l0.03,-2.01l0.48,-0.86l1.48,-2.28l2.96,-1.15l2.88,-1.34l2.99,-1.9l-0.48,-1.29l-1.83,-2.25L285.18,314.23zM45.62,263.79l-1.5,0.8l-2.55,1.86l0.43,2.42l1.43,1.32l2.8,-1.95l2.43,-2.47l-1.19,-1.63L45.62,263.79zM0,235.22l2.04,-1.26l0.23,-0.68L0,232.61V235.22zM8.5,250.59l-2.77,0.97l1.7,1.52l1.84,1.04l1.72,-0.87l-0.27,-2.15L8.5,250.59zM105.85,283.09l-2.69,0.38l-1.32,-0.62l-0.17,1.52l0.52,2.07l1.42,1.46l1.04,2.13l1.69,2.1l1.12,0.01l-2.44,-3.7L105.85,283.09zM37.13,403.77l-1,-0.28l-0.27,0.26l0.02,0.19l0.32,0.24l0.48,0.63l0.94,-0.21l0.23,-0.36L37.13,403.77zM34.14,403.23l1.5,0.09l0.09,-0.32l-1.38,-0.13L34.14,403.23zM40.03,406.52l-0.5,-0.26l-1.07,-0.5l-0.21,-0.06l-0.16,0.28l0.19,0.58l-0.49,0.48l-0.14,0.33l0.46,1.08l-0.08,0.83l0.7,0.42l0.41,-0.49l0.9,-0.46l1.1,-0.63l0.07,-0.16l-0.71,-1.04L40.03,406.52zM32.17,401.38l-0.75,0.41l0.11,0.12l0.36,0.68l0.98,0.11l0.2,0.04l0.15,-0.17l-0.81,-0.99L32.17,401.38zM27.77,399.82l-0.43,0.3l-0.15,0.22l0.94,0.55l0.33,-0.3l-0.06,-0.7L27.77,399.82z'),
(168,	'UY',	'Uruguay',	'M313.68,551.79L315.5,551.45L318.31,553.95L319.35,553.86L322.24,555.94L324.44,557.76L326.06,560.01L324.82,561.58L325.6,563.48L324.39,565.6L321.22,567.48L319.15,566.8L317.63,567.17L315.04,565.71L313.14,565.82L311.43,563.95L311.65,561.79L312.26,561.05L312.23,557.75L312.98,554.38z'),
(169,	'UZ',	'Uzbekistan',	'M661.76,350.95L661.84,348.79L658.11,347.27L655.18,345.52L653.35,343.83L650.14,341.32L648.76,337.53L647.82,336.86L644.79,337.03L643.72,336.26L643.42,333.27L639.64,331.27L637.28,333.47L634.88,334.77L635.34,336.65L632.18,336.7L632.07,322.57L639.29,320.22L639.81,320.57L644.16,323.41L646.45,324.89L649.13,328.39L652.42,327.83L657.23,327.53L660.58,330.33L660.37,334.13L661.74,334.16L662.31,337.22L665.88,337.34L666.64,339.09L667.69,339.07L668.92,336.42L672.61,333.81L674.22,333.11L675.05,333.48L672.7,335.91L674.77,337.31L676.77,336.38L680.09,338.34L676.5,340.98L674.37,340.62L673.21,340.72L672.81,339.7L673.39,337.99L669.64,338.85L668.75,341.2L667.42,343.21L665.08,343.04L664.35,344.63L666.41,345.49L667.01,348.15L665.44,351.72L663.32,350.98z'),
(170,	'VE',	'Venezuela',	'M275.25,430.35L275.17,431.02L273.52,431.35L274.44,432.64L274.4,434.13L273.17,435.77L274.23,438.01L275.44,437.83L276.07,435.79L275.2,434.79L275.06,432.65L278.55,431.49L278.16,430.15L279.14,429.25L280.15,431.25L282.12,431.3L283.94,432.88L284.05,433.82L286.56,433.84L289.56,433.55L291.17,434.82L293.31,435.17L294.88,434.29L294.91,433.57L298.39,433.4L301.75,433.36L299.37,434.2L300.32,435.54L302.57,435.75L304.69,437.14L305.14,439.4L306.6,439.33L307.7,440L305.48,441.65L305.23,442.68L306.19,443.72L305.5,444.24L303.77,444.69L303.83,445.99L303.07,446.76L304.96,448.88L305.34,449.67L304.31,450.74L301.17,451.78L299.16,452.22L298.35,452.88L296.12,452.18L294.04,451.82L293.52,452.08L294.77,452.8L294.66,454.67L295.05,456.43L297.42,456.67L297.58,457.25L295.57,458.05L295.25,459.23L294.09,459.68L292.01,460.33L291.47,461.19L289.29,461.37L287.74,459.89L286.89,457.12L286.14,456.14L285.12,455.53L286.54,454.14L286.45,453.51L285.65,452.68L285.09,450.83L285.31,448.82L285.93,447.88L286.44,446.38L285.45,445.89L283.85,446.21L281.83,446.06L280.7,446.36L278.72,443.95L277.09,443.59L273.49,443.86L272.82,442.88L272.13,442.65L272.03,442.06L272.36,441.02L272.14,439.89L271.52,439.27L271.16,437.97L269.72,437.79L270.49,436.13L270.84,434.12L271.65,433.06L272.74,432.25L273.45,430.83z'),
(171,	'VN',	'Vietnam',	'M778.21,401.87L774.47,404.43L772.13,407.24L771.51,409.29L773.66,412.38L776.28,416.2L778.82,417.99L780.53,420.32L781.81,425.64L781.43,430.66L779.1,432.53L775.88,434.36L773.6,436.72L770.1,439.34L769.08,437.53L769.87,435.62L767.79,434.01L770.22,432.87L773.16,432.67L771.93,430.94L776.64,428.75L776.99,425.33L776.34,423.41L776.85,420.53L776.14,418.49L774.02,416.47L772.25,413.9L769.92,410.44L766.56,408.68L767.37,407.61L769.16,406.84L768.07,404.25L764.62,404.22L763.36,401.5L761.72,399.13L763.23,398.39L765.46,398.41L768.19,398.06L770.58,396.44L771.93,397.58L774.5,398.13L774.05,399.87L775.39,401.09z'),
(172,	'VU',	'Vanuatu',	'M945.87,509.9l-0.92,0.38l-0.94,-1.27l0.1,-0.78L945.87,509.9zM943.8,505.46l0.46,2.33l-0.75,-0.36l-0.58,0.16l-0.4,-0.8l-0.06,-2.21L943.8,505.46z'),
(173,	'YE',	'Yemen',	'M624.16,416.33L622.13,417.12L621.59,418.4L621.52,419.39L618.73,420.61L614.25,421.96L611.74,423.99L610.51,424.14L609.67,423.97L608.03,425.17L606.24,425.72L603.89,425.87L603.18,426.03L602.57,426.78L601.83,426.99L601.4,427.72L600.01,427.66L599.11,428.04L597.17,427.9L596.44,426.23L596.52,424.66L596.07,423.81L595.52,421.69L594.71,420.5L595.27,420.36L594.98,419.04L595.32,418.48L595.2,417.22L596.43,416.29L596.14,415.06L596.89,413.63L598.04,414.39L598.8,414.12L602.03,414.05L602.55,414.35L605.26,414.64L606.33,414.49L607.03,415.46L608.34,414.98L610.35,411.91L612.97,410.59L621.05,409.46L623.25,414.3z'),
(174,	'ZA',	'South Africa',	'M563.63,548.71l-0.55,0.46l-1.19,1.63l-0.78,1.66l-1.59,2.33l-3.17,3.38l-1.98,1.98l-2.12,1.51l-2.93,1.3l-1.43,0.17l-0.36,0.93l-1.7,-0.5l-1.39,0.64l-3.04,-0.65l-1.7,0.41l-1.16,-0.18l-2.89,1.33l-2.39,0.54l-1.73,1.28l-1.28,0.08l-1.19,-1.21l-0.95,-0.06l-1.21,-1.51l-0.13,0.47l-0.37,-0.91l0.02,-1.96l-0.91,-2.23l0.9,-0.6l-0.07,-2.53l-1.84,-3.05l-1.41,-2.74l0,-0.01l-2.01,-4.15l1.34,-1.57l1.11,0.87l0.47,1.36l1.26,0.23l1.76,0.6l1.51,-0.23l2.5,-1.63l0,-11.52l0.76,0.46l1.66,2.93l-0.26,1.89l0.63,1.1l2.01,-0.32l1.4,-1.39l1.33,-0.93l0.69,-1.48l1.37,-0.72l1.18,0.38l1.34,0.87l2.28,0.15l1.79,-0.72l0.28,-0.96l0.49,-1.47l1.53,-0.25l0.84,-1.15l0.93,-2.03l2.52,-2.26l3.97,-2.22l1.14,0.03l1.36,0.51l0.94,-0.36l1.49,0.3l1.34,4.26l0.73,2.17l-0.5,3.43l0.24,1.11l-1.42,-0.57l-0.81,0.22l-0.26,0.9l-0.77,1.17l0.03,1.08l1.67,1.7l1.64,-0.34l0.57,-1.39l2.13,0.03l-0.7,2.28l-0.33,2.62l-0.73,1.43L563.63,548.71zM556.5,547.75l-1.22,-0.98l-1.31,0.65l-1.52,1.25l-1.5,2.03l2.1,2.48l1,-0.32l0.52,-1.03l1.56,-0.5l0.48,-1.05l0.86,-1.56L556.5,547.75z'),
(175,	'ZM',	'Zambia',	'M567.11,489.21L568.43,490.47L569.14,492.87L568.66,493.64L568.1,495.94L568.64,498.3L567.76,499.29L566.91,501.95L568.38,502.69L559.87,505.07L560.14,507.12L558.01,507.52L556.42,508.67L556.08,509.68L555.07,509.9L552.63,512.3L551.08,514.19L550.13,514.26L549.22,513.92L546.09,513.6L545.59,513.38L545.56,513.14L544.46,512.48L542.64,512.31L540.34,512.98L538.51,511.16L536.62,508.78L536.75,499.62L542.59,499.66L542.35,498.67L542.77,497.6L542.28,496.27L542.6,494.89L542.3,494.01L543.27,494.08L543.43,494.96L544.74,494.89L546.52,495.15L547.46,496.44L549.7,496.84L551.42,495.94L552.05,497.43L554.2,497.83L555.23,499.05L556.38,500.62L558.53,500.65L558.29,497.57L557.52,498.08L555.56,496.98L554.8,496.47L555.15,493.62L555.65,490.27L555.02,489.02L555.82,487.22L556.57,486.89L560.34,486.41L561.44,486.7L562.61,487.41L563.73,487.89L565.51,488.36z'),
(176,	'ZW',	'Zimbabwe',	'M562.71,527L561.22,526.7L560.27,527.06L558.92,526.55L557.78,526.52L555.99,525.16L553.82,524.7L553,522.8L552.99,521.75L551.79,521.43L548.62,518.18L547.73,516.47L547.17,515.95L546.09,513.6L549.22,513.92L550.13,514.26L551.08,514.19L552.63,512.3L555.07,509.9L556.08,509.68L556.42,508.67L558.01,507.52L560.14,507.12L560.32,508.2L562.66,508.14L563.96,508.75L564.56,509.47L565.9,509.68L567.35,510.62L567.36,514.31L566.81,516.35L566.69,518.55L567.14,519.43L566.83,521.17L566.4,521.44L565.66');

DROP TABLE IF EXISTS `zonas`;
CREATE TABLE `zonas` (
  `id` int(11) NOT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `portes_gratis1` decimal(10,2) DEFAULT NULL,
  `portes_gratis2` decimal(10,2) DEFAULT NULL,
  `portes_gratis3` decimal(10,2) DEFAULT NULL,
  `portes_gratis4` decimal(10,2) DEFAULT NULL,
  `portes_gratis5` decimal(10,2) DEFAULT NULL,
  `portes_gratis6` decimal(10,2) DEFAULT NULL,
  `peso_max` decimal(10,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `zonas` (`id`, `nome`, `portes_gratis1`, `portes_gratis2`, `portes_gratis3`, `portes_gratis4`, `portes_gratis5`, `portes_gratis6`, `peso_max`) VALUES
(1,	'Resto do Mundo',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(2,	'Portugal Continental',	20.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(3,	'Portugal Ilhas',	50.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(4,	'CTT Zona 2 (Alemanha, Austria, Bélgica, Eslováquia, Eslovénia, frança, Grécia, Países baixos, Hungria, Irlanda, Itália, Luxemburgo, Reino Unido, rep. Checa, Suiça)',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(5,	'CTT - Zona 3 (Dinamarca, estonia, finlandia, islandia, letonia, lituania, malta, noruega, polonia, suecia)',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(6,	'Espanha Peninsular',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(7,	'Portugal Continental MRW',	20.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(8,	'Store Pickup',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(9,	'DPD',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(10,	'Royal Mail / DPD Tracked',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(11,	'Royal Mail / DPD ',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000),
(12,	'Home Delivery',	0.00,	NULL,	NULL,	NULL,	NULL,	NULL,	0.000);

DROP TABLE IF EXISTS `zonas_met_envio`;
CREATE TABLE `zonas_met_envio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_zona` int(11) DEFAULT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `portes` decimal(10,0) DEFAULT '0',
  `custo` decimal(10,2) DEFAULT '0.00',
  `tipo` tinyint(4) DEFAULT '1',
  `tabela` int(11) DEFAULT '0',
  `permite_pgratis` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_zona`,`id_metodo`,`tipo`,`tabela`,`permite_pgratis`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `zonas_met_envio` (`id`, `id_zona`, `id_metodo`, `portes`, `custo`, `tipo`, `tabela`, `permite_pgratis`) VALUES
(66,	2,	9,	0,	4.00,	0,	0,	0),
(67,	3,	9,	0,	9.00,	0,	0,	0),
(69,	5,	9,	0,	17.00,	0,	0,	0),
(70,	4,	9,	0,	17.00,	0,	0,	0),
(71,	6,	9,	0,	17.00,	0,	0,	0),
(72,	7,	10,	0,	4.00,	0,	11,	0),
(73,	2,	10,	0,	4.00,	0,	0,	0),
(74,	8,	8,	0,	0.00,	0,	0,	0),
(75,	9,	11,	0,	6.99,	0,	0,	0),
(77,	10,	12,	0,	4.00,	0,	0,	0),
(78,	10,	13,	0,	0.00,	0,	0,	0),
(80,	5,	10,	0,	0.00,	0,	0,	0),
(82,	9,	12,	0,	4.00,	0,	0,	0),
(83,	9,	13,	0,	3.00,	0,	0,	0),
(84,	9,	8,	0,	0.00,	0,	0,	0),
(85,	5,	7,	0,	0.00,	0,	0,	0),
(86,	5,	13,	0,	0.00,	0,	0,	0),
(87,	11,	9,	0,	0.00,	0,	0,	0),
(90,	12,	8,	0,	0.00,	0,	0,	0),
(92,	12,	11,	0,	6.99,	0,	0,	0),
(93,	5,	8,	0,	20.00,	0,	0,	0),
(95,	9,	14,	0,	0.00,	0,	16,	0),
(97,	12,	14,	10,	23.00,	NULL,	0,	0),
(98,	11,	8,	0,	0.00,	0,	0,	0),
(99,	11,	11,	0,	0.00,	0,	0,	0),
(100,	11,	12,	0,	0.00,	0,	0,	0),
(101,	11,	13,	0,	0.00,	0,	0,	0),
(102,	11,	14,	0,	0.00,	0,	0,	0),
(103,	5,	14,	0,	0.00,	0,	0,	0),
(105,	8,	11,	0,	6.99,	0,	0,	0),
(106,	8,	12,	0,	0.00,	0,	0,	0),
(107,	8,	13,	0,	0.00,	0,	0,	0),
(108,	8,	14,	0,	22.00,	0,	0,	0),
(109,	12,	12,	0,	3.99,	0,	0,	0),
(110,	12,	13,	0,	2.99,	0,	0,	0),
(114,	1,	8,	0,	0.00,	0,	0,	0),
(115,	1,	14,	1,	2.00,	NULL,	0,	0),
(116,	1,	13,	0,	0.00,	0,	0,	0),
(117,	1,	11,	0,	0.00,	0,	0,	0),
(118,	1,	12,	0,	0.00,	0,	0,	0);

DROP TABLE IF EXISTS `zonas_met_pagamento`;
CREATE TABLE `zonas_met_pagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_zona` int(11) DEFAULT NULL,
  `id_metodo` int(11) DEFAULT NULL,
  `portes` decimal(10,2) DEFAULT '0.00',
  `tipo` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indice` (`id_zona`,`id_metodo`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `zonas_met_pagamento` (`id`, `id_zona`, `id_metodo`, `portes`, `tipo`) VALUES
(1,	9,	1,	0.00,	1),
(2,	9,	10,	0.00,	NULL),
(4,	12,	10,	0.00,	NULL),
(5,	1,	10,	0.00,	NULL),
(9,	8,	10,	0.00,	NULL);

-- 2021-03-23 05:31:54
