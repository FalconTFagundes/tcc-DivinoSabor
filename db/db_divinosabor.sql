-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/12/2023 às 09:33
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
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `idclientes` int(10) UNSIGNED NOT NULL,
  `nome` varchar(65) NOT NULL DEFAULT '',
  `endereco` varchar(120) NOT NULL DEFAULT '',
  `complemento` varchar(45) NOT NULL DEFAULT '',
  `cidade` varchar(100) NOT NULL DEFAULT '',
  `estado` varchar(100) NOT NULL DEFAULT '',
  `cep` varchar(45) NOT NULL DEFAULT '',
  `telefone` varchar(55) NOT NULL DEFAULT '',
  `cadastro` datetime DEFAULT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT 'A',
  `img` varchar(150) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`idclientes`, `nome`, `endereco`, `complemento`, `cidade`, `estado`, `cep`, `telefone`, `cadastro`, `alteracao`, `ativo`, `img`) VALUES
(3, 'Rafael', 'teste', 'teste', 'teste', 'teste', '124524', '333333', '2023-12-15 00:23:29', '2023-12-15 04:42:43', 'D', 'rafael.jpg'),
(4, 'Bulim', 'test', 'test', 'teste', 'teste', '24242', 'teste', '2023-12-15 00:29:17', '2023-12-15 04:35:44', 'D', 'bulim.jpg'),
(5, 'Geísa', 'test', 'test', 'teste', 'teste', '24242', 'teste', '2023-12-15 00:29:17', '2023-12-15 04:35:44', 'A', 'geisa.jpg'),
(6, 'Widerson', 'teste', 'teste', 'teste', 'teste', '35059-110', '245252', '2023-12-15 00:40:45', '2023-12-15 04:35:44', 'A', 'widerson.jpg'),
(7, 'Glaydmar', 'teste', 'teste', 'teste', 'teste', '252525', '25252525', '2023-12-15 00:40:45', '2023-12-15 04:35:44', 'A', 'glaydmar.jpg');

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
-- Estrutura para tabela `ingredientes`
--

CREATE TABLE `ingredientes` (
  `idingredientes` int(11) NOT NULL,
  `nomeIngred` varchar(45) NOT NULL,
  `img` varchar(45) NOT NULL,
  `quantIngred` int(11) NOT NULL,
  `pesoUnit` decimal(10,2) NOT NULL,
  `pesoTotal` decimal(10,2) GENERATED ALWAYS AS (`quantIngred` * `pesoUnit`) STORED,
  `precoUnit` decimal(10,2) NOT NULL,
  `precoTotal` decimal(10,2) GENERATED ALWAYS AS (`quantIngred` * `precoUnit`) STORED,
  `dataComp` date NOT NULL,
  `dataValidad` date DEFAULT NULL,
  `codigo` int(11) NOT NULL,
  `cadastro` datetime NOT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ingredientes`
--

INSERT INTO `ingredientes` (`idingredientes`, `nomeIngred`, `img`, `quantIngred`, `pesoUnit`, `precoUnit`, `dataComp`, `dataValidad`, `codigo`, `cadastro`, `alteracao`, `ativo`) VALUES
(12, 'Fubá Mimoso Ranziza 51', 'fubaMimoso.jpg', 14, 22.00, 2.00, '2222-02-22', '2222-02-22', 2222, '2023-12-18 00:28:50', '2023-12-18 06:58:25', 'A');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacote`
--

CREATE TABLE `pacote` (
  `idpacote` int(10) UNSIGNED NOT NULL,
  `pacote` varchar(245) NOT NULL DEFAULT '',
  `qtdPessoas` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cadastro` datetime DEFAULT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacote`
--

INSERT INTO `pacote` (`idpacote`, `pacote`, `qtdPessoas`, `cadastro`, `alteracao`) VALUES
(36, 'test qtd', 2, '2023-12-16 15:06:54', '2023-12-16 18:06:54'),
(41, 'team viewer', 50, '2023-12-17 11:36:00', '2023-12-17 14:36:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacotecadastro`
--

CREATE TABLE `pacotecadastro` (
  `idpacotecadastro` int(10) UNSIGNED NOT NULL,
  `idpacote` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `idproduto` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cadastro` datetime DEFAULT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT 'A',
  `valorPacote` decimal(5,2) NOT NULL,
  `detalhes` varchar(150) NOT NULL DEFAULT '',
  `quantidade` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacotecadastro`
--

INSERT INTO `pacotecadastro` (`idpacotecadastro`, `idpacote`, `idproduto`, `cadastro`, `alteracao`, `ativo`, `valorPacote`, `detalhes`, `quantidade`) VALUES
(87, 41, 7, '2023-12-17 11:36:20', '2023-12-17 14:36:20', 'A', 700.00, 'frango, franguinho', 90),
(88, 41, 7, '2023-12-17 11:36:20', '2023-12-17 14:36:20', 'A', 700.00, 'frango, franguinho', 50);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idpedidos` int(10) UNSIGNED NOT NULL,
  `idclientes` int(10) UNSIGNED NOT NULL,
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

INSERT INTO `pedidos` (`idpedidos`, `idclientes`, `pedido`, `detalhes`, `cadastro`, `alteracao`, `ativo`, `dataEntrega`) VALUES
(1, 5, 'teste', 'teste', '2023-12-15 02:18:44', '2023-12-15 05:18:44', 'A', '1111-11-11'),
(2, 4, 'teste', 'teste', '2023-12-15 02:24:33', '2023-12-15 05:24:33', 'A', '1111-11-11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idprodutos` int(10) UNSIGNED NOT NULL,
  `img` varchar(145) DEFAULT NULL,
  `produto` varchar(145) NOT NULL DEFAULT '',
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cadastro` datetime DEFAULT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idprodutos`, `img`, `produto`, `valor`, `cadastro`, `alteracao`, `ativo`) VALUES
(6, 'peixeFrito.jpg', 'peixe', 5.00, '2023-12-18 04:50:00', '2023-12-18 08:06:23', 'A'),
(7, 'frangoFrito.jpg', 'Franguinho', 5.00, '2023-12-18 04:50:00', '2023-12-18 07:56:47', 'A');

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
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idclientes`);

--
-- Índices de tabela `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`idingredientes`);

--
-- Índices de tabela `pacote`
--
ALTER TABLE `pacote`
  ADD PRIMARY KEY (`idpacote`);

--
-- Índices de tabela `pacotecadastro`
--
ALTER TABLE `pacotecadastro`
  ADD PRIMARY KEY (`idpacotecadastro`,`idpacote`,`idproduto`),
  ADD KEY `FK_pacotecadastro_pacote` (`idpacote`),
  ADD KEY `FK_pacotecadastro_produto` (`idproduto`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idpedidos`,`idclientes`),
  ADD KEY `FK_idclientes` (`idclientes`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idprodutos`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idclientes` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `idingredientes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `pacote`
--
ALTER TABLE `pacote`
  MODIFY `idpacote` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `pacotecadastro`
--
ALTER TABLE `pacotecadastro`
  MODIFY `idpacotecadastro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedidos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idprodutos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pacotecadastro`
--
ALTER TABLE `pacotecadastro`
  ADD CONSTRAINT `FK_pacotecadastro_pacote` FOREIGN KEY (`idpacote`) REFERENCES `pacote` (`idpacote`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_pacotecadastro_produto` FOREIGN KEY (`idproduto`) REFERENCES `produtos` (`idprodutos`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `FK_idclientes` FOREIGN KEY (`idclientes`) REFERENCES `clientes` (`idclientes`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
