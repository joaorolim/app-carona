-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 21-Nov-2017 às 20:07
-- Versão do servidor: 5.7.14-log
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carona_db`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `carona`
--

CREATE TABLE `carona` (
  `id_carona` int(10) UNSIGNED NOT NULL,
  `id_rota` int(10) UNSIGNED NOT NULL,
  `data` datetime NOT NULL,
  `id_carro` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `carona`
--

INSERT INTO `carona` (`id_carona`, `id_rota`, `data`, `id_carro`) VALUES
(1, 3, '2017-10-02 00:00:00', 2),
(2, 3, '2017-10-03 00:00:00', 1),
(3, 1, '2017-10-04 00:00:00', 1),
(4, 2, '2017-10-05 00:00:00', 2),
(5, 3, '2017-10-06 00:00:00', 2),
(9, 3, '2017-11-20 00:00:00', 3),
(10, 1, '2017-11-21 00:00:00', 1),
(11, 3, '2017-11-22 00:00:00', 1),
(12, 3, '2017-11-23 00:00:00', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `carona_pessoa`
--

CREATE TABLE `carona_pessoa` (
  `id_carona` int(10) UNSIGNED NOT NULL,
  `id_pessoa` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `carona_pessoa`
--

INSERT INTO `carona_pessoa` (`id_carona`, `id_pessoa`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 3),
(4, 2),
(4, 3),
(5, 1),
(5, 2),
(9, 7),
(9, 1),
(9, 2),
(9, 13),
(10, 1),
(10, 2),
(10, 4),
(10, 8),
(12, 2),
(12, 1),
(11, 1),
(11, 2),
(11, 3),
(11, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `carro`
--

CREATE TABLE `carro` (
  `id_carro` int(10) UNSIGNED NOT NULL,
  `id_pessoa` int(10) UNSIGNED NOT NULL,
  `descricao` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `carro`
--

INSERT INTO `carro` (`id_carro`, `id_pessoa`, `descricao`) VALUES
(1, 1, 'Bugatti Chiron'),
(2, 2, 'Lamborghini Aventador S Roadster'),
(3, 7, 'Ferrari Portofino');

-- --------------------------------------------------------

--
-- Estrutura da tabela `carro_fechamento`
--

CREATE TABLE `carro_fechamento` (
  `id_fechamento` int(10) UNSIGNED NOT NULL,
  `id_carro` int(10) UNSIGNED NOT NULL,
  `media_comb` decimal(10,5) NOT NULL,
  `media_km_litro` decimal(10,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `carro_fechamento`
--

INSERT INTO `carro_fechamento` (`id_fechamento`, `id_carro`, `media_comb`, `media_km_litro`) VALUES
(1, 1, '3.57849', '12.32678'),
(1, 2, '3.69735', '13.58257');

-- --------------------------------------------------------

--
-- Estrutura da tabela `fechamento`
--

CREATE TABLE `fechamento` (
  `id_fechamento` int(10) UNSIGNED NOT NULL,
  `mes` int(2) UNSIGNED NOT NULL,
  `ano` int(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `fechamento`
--

INSERT INTO `fechamento` (`id_fechamento`, `mes`, `ano`) VALUES
(1, 10, 2017);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa`
--

CREATE TABLE `pessoa` (
  `id_pessoa` int(10) UNSIGNED NOT NULL,
  `nome` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `is_driver` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `pessoa`
--

INSERT INTO `pessoa` (`id_pessoa`, `nome`, `is_driver`) VALUES
(1, 'Bernardo Simão', 1),
(2, 'Jaime Leonardo', 1),
(3, 'Ana Paula', 0),
(4, 'Maria Bonita', 0),
(6, 'Luiz Paulo', 0),
(7, 'Tatiana Dezan', 1),
(8, 'Clayton Calixto', 0),
(9, 'João Paulo', 0),
(13, 'Bia Antunes', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa_fechamento`
--

CREATE TABLE `pessoa_fechamento` (
  `id_pessoa` int(10) UNSIGNED NOT NULL,
  `id_fechamento` int(10) UNSIGNED NOT NULL,
  `pagou` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `pessoa_fechamento`
--

INSERT INTO `pessoa_fechamento` (`id_pessoa`, `id_fechamento`, `pagou`) VALUES
(1, 1, 1),
(2, 1, 0),
(3, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `rota`
--

CREATE TABLE `rota` (
  `id_rota` int(10) UNSIGNED NOT NULL,
  `descricao` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `km` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `rota`
--

INSERT INTO `rota` (`id_rota`, `descricao`, `km`) VALUES
(1, 'SJC x FATEC TBT - (casa Bernardo)', '70.00'),
(2, 'SJC x FATEC TBT - (casa do Jaime)', '80.00'),
(3, 'SJC x FATEC TBT - (Parque Tec.)', '60.00'),
(4, 'SJC x FATEC PINDA', '120.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carona`
--
ALTER TABLE `carona`
  ADD PRIMARY KEY (`id_carona`),
  ADD KEY `id_rota` (`id_rota`),
  ADD KEY `id_carro` (`id_carro`);

--
-- Indexes for table `carona_pessoa`
--
ALTER TABLE `carona_pessoa`
  ADD KEY `id_carona` (`id_carona`),
  ADD KEY `id_pessoa` (`id_pessoa`);

--
-- Indexes for table `carro`
--
ALTER TABLE `carro`
  ADD PRIMARY KEY (`id_carro`),
  ADD KEY `id_pessoa` (`id_pessoa`);

--
-- Indexes for table `carro_fechamento`
--
ALTER TABLE `carro_fechamento`
  ADD KEY `id_carro` (`id_carro`),
  ADD KEY `id_fechamento` (`id_fechamento`);

--
-- Indexes for table `fechamento`
--
ALTER TABLE `fechamento`
  ADD PRIMARY KEY (`id_fechamento`);

--
-- Indexes for table `pessoa`
--
ALTER TABLE `pessoa`
  ADD PRIMARY KEY (`id_pessoa`);

--
-- Indexes for table `pessoa_fechamento`
--
ALTER TABLE `pessoa_fechamento`
  ADD KEY `id_pessoa` (`id_pessoa`),
  ADD KEY `id_fechamento` (`id_fechamento`);

--
-- Indexes for table `rota`
--
ALTER TABLE `rota`
  ADD PRIMARY KEY (`id_rota`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carona`
--
ALTER TABLE `carona`
  MODIFY `id_carona` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `carro`
--
ALTER TABLE `carro`
  MODIFY `id_carro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `fechamento`
--
ALTER TABLE `fechamento`
  MODIFY `id_fechamento` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pessoa`
--
ALTER TABLE `pessoa`
  MODIFY `id_pessoa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `rota`
--
ALTER TABLE `rota`
  MODIFY `id_rota` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `carona`
--
ALTER TABLE `carona`
  ADD CONSTRAINT `carona_ibfk_1` FOREIGN KEY (`id_rota`) REFERENCES `rota` (`id_rota`),
  ADD CONSTRAINT `carona_ibfk_2` FOREIGN KEY (`id_carro`) REFERENCES `carro` (`id_carro`);

--
-- Limitadores para a tabela `carona_pessoa`
--
ALTER TABLE `carona_pessoa`
  ADD CONSTRAINT `carona_pessoa_ibfk_1` FOREIGN KEY (`id_carona`) REFERENCES `carona` (`id_carona`),
  ADD CONSTRAINT `carona_pessoa_ibfk_2` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`);

--
-- Limitadores para a tabela `carro`
--
ALTER TABLE `carro`
  ADD CONSTRAINT `carro_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`);

--
-- Limitadores para a tabela `carro_fechamento`
--
ALTER TABLE `carro_fechamento`
  ADD CONSTRAINT `carro_fechamento_ibfk_1` FOREIGN KEY (`id_carro`) REFERENCES `carro` (`id_carro`),
  ADD CONSTRAINT `carro_fechamento_ibfk_2` FOREIGN KEY (`id_fechamento`) REFERENCES `fechamento` (`id_fechamento`);

--
-- Limitadores para a tabela `pessoa_fechamento`
--
ALTER TABLE `pessoa_fechamento`
  ADD CONSTRAINT `pessoa_fechamento_ibfk_1` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id_pessoa`),
  ADD CONSTRAINT `pessoa_fechamento_ibfk_2` FOREIGN KEY (`id_fechamento`) REFERENCES `fechamento` (`id_fechamento`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
