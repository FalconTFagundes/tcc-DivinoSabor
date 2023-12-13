-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12/12/2023 às 23:36
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_divinosabor`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(220) NOT NULL,
  `color` varchar(45) NOT NULL DEFAULT '#9E77F1',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `events`
--

INSERT INTO `events` (`id`, `title`, `color`, `start`, `end`) VALUES
(8, 'Teste Mask', '#00BD3f', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'teste', '#D4C200', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 'Teste Mask', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'Teste Mask', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'teste', '#D4C200', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'a', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(17, 'a', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 't', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 't', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 't', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 't', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 't', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 't', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 't', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'teste', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'teste', '#9E77F1', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 'Festa de Aniversário', '#297BFF', '2023-12-16 00:00:00', '2023-12-17 12:00:00'),
(32, 'Festa no Saae', '#9E77F1', '2023-12-07 03:00:00', '2023-12-08 03:00:00'),
(35, 'Aegea', '#D4C200', '2023-12-12 03:00:00', '2023-12-13 03:00:00'),
(36, 'Formatura', '#FF0831', '2023-12-29 03:00:00', '2023-12-30 03:00:00'),
(37, 'Aniversário Fernando', '#D4C200', '2023-12-05 03:00:00', '2023-12-06 03:00:00'),
(38, 'Senai lab', '#00BD3f', '2024-01-01 03:00:00', '2024-01-02 03:00:00'),
(39, 'teste', '#00BD3f', '2023-12-22 03:00:00', '2023-12-23 03:00:00'),
(40, 'teste', '#9E77F1', '2023-12-14 03:00:00', '2023-12-15 03:00:00'),
(41, 'teste', '#9E77F1', '2023-12-21 03:00:00', '2023-12-22 03:00:00'),
(42, 'teste', '#9E77F1', '2023-12-05 03:00:00', '2023-12-06 03:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idpedidos` int(10) UNSIGNED NOT NULL,
  `nome` varchar(60) NOT NULL,
  `pedido` varchar(45) NOT NULL,
  `detalhes` varchar(70) NOT NULL,
  `cadastro` datetime NOT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT 'A',
  `dataEntrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idpedidos`, `nome`, `pedido`, `detalhes`, `cadastro`, `alteracao`, `ativo`, `dataEntrega`) VALUES
(14, 'Fernando Rodrigues', 'Festa de Hallowen', 'Salgados diversos e bolo', '2023-12-11 19:41:24', '2023-12-12 22:31:07', 'A', '2023-12-18'),
(15, 'Widerson', 'Festa de Aniversário', '500 salgados diversos', '2023-12-11 19:41:57', '2023-12-12 22:31:07', 'A', '2025-12-01'),
(16, 'Rafael', 'Festa', 'Muitos salgados com foco em carne', '2023-12-11 19:42:34', '2023-12-12 22:31:07', 'A', '2023-11-25'),
(17, 't', 't', 't', '2023-12-12 19:30:39', '2023-12-12 22:31:07', 'A', '1111-11-11'),
(18, 'T', 'T', 'T', '2023-12-12 19:31:28', '2023-12-12 22:31:28', 'A', '1111-11-11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(10) UNSIGNED NOT NULL,
  `nome` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `senha` varchar(45) NOT NULL,
  `ativo` char(1) NOT NULL DEFAULT 'A',
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cadastro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nome`, `email`, `senha`, `ativo`, `alteracao`, `cadastro`) VALUES
(1, 'g', 'g@gmail.com', '123', 'A', '2023-12-03 22:46:47', '0000-00-00 00:00:00'),
(2, 'Rafael', 'rafael@gmail.com', '123', 'A', '2023-12-04 22:12:36', '2023-12-04 19:00:00'),
(3, 'Glaydmar', 'glayglay@gmail.com', '123', 'A', '2023-12-05 01:06:46', '2023-12-04 22:06:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idpedidos`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedidos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
