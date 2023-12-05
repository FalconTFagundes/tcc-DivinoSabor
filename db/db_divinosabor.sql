-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/12/2023 às 02:11
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
-- Estrutura para tabela `calendario`
--

CREATE TABLE `calendario` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(220) NOT NULL,
  `color` varchar(45) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `calendario`
--

INSERT INTO `calendario` (`id`, `title`, `color`, `start`, `end`) VALUES
(1, 'Confraternização', '#9E77F1', '2023-12-12', '2023-12-13'),
(2, 'Confraternização 2', '#9E75F3', '2023-12-25', '2023-12-28'),
(3, 'Confraternização 3', '#9E77F6', '2023-12-17', '2023-12-19');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idpedidos` int(10) UNSIGNED NOT NULL,
  `nome` varchar(60) NOT NULL,
  `status` varchar(45) NOT NULL,
  `detalhes` varchar(70) NOT NULL,
  `cadastro` datetime NOT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idpedidos`, `nome`, `status`, `detalhes`, `cadastro`, `alteracao`, `ativo`) VALUES
(1, 'Widerson', 'Ativo', 'ps5 da nasa tunado SEM SER SLIN', '2023-12-04 22:05:42', '2023-12-05 01:05:42', 'A'),
(2, 'Fernando', 'Ativo', '200 esfirras de frango com muito cheddar e calabresa grande em rodelas', '2023-12-04 22:06:10', '2023-12-05 01:06:10', 'A'),
(3, 'Glaydmar', 'Ativo', 'Coxinha Gigante de 6kg', '2023-12-04 22:09:59', '2023-12-05 01:09:59', 'A');

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
-- Índices de tabela `calendario`
--
ALTER TABLE `calendario`
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
-- AUTO_INCREMENT de tabela `calendario`
--
ALTER TABLE `calendario`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedidos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
