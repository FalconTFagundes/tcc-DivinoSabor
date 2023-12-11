-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/12/2023 às 03:17
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

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
(1, 'Teste Roxo', '#9E77F1', '2023-12-10 03:00:00', '2023-12-11 03:00:00'),
(2, 'Teste Red', '#FF0831', '2023-12-15 03:00:00', '2023-12-16 03:00:00'),
(3, 'Teste Blue', '#297BFF', '2023-12-19 03:00:00', '2023-12-20 03:00:00'),
(5, 'teste amarelão', '#D4C200', '2023-12-22 03:00:00', '2023-12-23 03:00:00'),
(6, 'Teste', '#FF0831', '2023-12-26 23:00:00', '2023-12-27 03:00:00'),
(7, 'Festa', '#00BD3f', '2023-12-07 03:00:00', '2023-12-08 03:00:00');

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
  `ativo` char(1) NOT NULL DEFAULT 'D',
  `dataEntrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idpedidos`, `nome`, `pedido`, `detalhes`, `cadastro`, `alteracao`, `ativo`, `dataEntrega`) VALUES
(1, 'Rafael', 'Festa de Aniverário', '200 salgados ao total', '2023-12-10 20:33:08', '2023-12-11 00:01:57', 'D', '2024-08-17'),
(2, 'teste', 'teste', 'teste', '2023-12-10 22:24:10', '2023-12-11 01:24:43', 'D', '0111-11-11'),
(3, 'Teste', 'teste', 'teste', '2023-12-10 22:50:03', '2023-12-11 01:50:03', 'D', '2025-11-11'),
(4, 'teste', 'tagasg', 'gasgag', '2023-12-10 22:50:15', '2023-12-11 01:50:15', 'D', '2024-11-11'),
(5, 'teste', 'teste', 'teste', '2023-12-10 22:50:30', '2023-12-11 01:50:30', 'D', '2023-12-11'),
(6, 'test', 't', 't', '2023-12-10 22:50:38', '2023-12-11 01:50:38', 'D', '1111-11-11'),
(7, 'test', 't', 't', '2023-12-10 22:50:45', '2023-12-11 01:50:45', 'D', '1111-11-11'),
(8, 't', 't', 't', '2023-12-10 22:50:55', '2023-12-11 01:50:55', 'D', '0000-00-00'),
(9, 'test', 't', 't', '2023-12-10 22:51:01', '2023-12-11 01:51:01', 'D', '1111-11-11');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedidos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
