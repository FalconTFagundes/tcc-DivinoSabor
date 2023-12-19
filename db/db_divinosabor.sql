-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/12/2023 às 14:32
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
  `ativo` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`idclientes`, `nome`, `endereco`, `complemento`, `cidade`, `estado`, `cep`, `telefone`, `cadastro`, `alteracao`, `ativo`) VALUES
(8, 'Rafael Fagundes', 'Endereco1', 'Complemento1', 'Cidade1', 'Estado1', 'CEP1', 'Telefone1', '2023-12-19 09:49:26', '2023-12-19 12:50:24', 'D'),
(9, 'Geísa Martins', 'Endereco2', 'Complemento2', 'Cidade2', 'Estado2', 'CEP2', 'Telefone2', '2023-12-19 09:49:26', '2023-12-19 12:49:26', 'A'),
(10, 'Widerson Alves', 'Endereco3', 'Complemento3', 'Cidade3', 'Estado3', 'CEP3', 'Telefone3', '2023-12-19 09:49:26', '2023-12-19 12:50:26', 'D'),
(11, 'Fernando Rodrigues', 'Endereco4', 'Complemento4', 'Cidade4', 'Estado4', 'CEP4', 'Telefone4', '2023-12-19 09:49:26', '2023-12-19 12:49:26', 'A'),
(12, 'Cliente5', 'Endereco5', 'Complemento5', 'Cidade5', 'Estado5', 'CEP5', 'Telefone5', '2023-12-19 09:49:26', '2023-12-19 13:28:05', 'D'),
(13, 'Cliente6', 'Endereco6', 'Complemento6', 'Cidade6', 'Estado6', 'CEP6', 'Telefone6', '2023-12-19 09:49:26', '2023-12-19 12:49:26', 'A'),
(14, 'Cliente7', 'Endereco7', 'Complemento7', 'Cidade7', 'Estado7', 'CEP7', 'Telefone7', '2023-12-19 09:49:26', '2023-12-19 12:49:26', 'A'),
(15, 'Cliente8', 'Endereco8', 'Complemento8', 'Cidade8', 'Estado8', 'CEP8', 'Telefone8', '2023-12-19 09:49:26', '2023-12-19 13:28:09', 'D'),
(16, 'Cliente9', 'Endereco9', 'Complemento9', 'Cidade9', 'Estado9', 'CEP9', 'Telefone9', '2023-12-19 09:49:26', '2023-12-19 12:49:26', 'A'),
(17, 'Cliente10', 'Endereco10', 'Complemento10', 'Cidade10', 'Estado10', 'CEP10', 'Telefone10', '2023-12-19 09:49:26', '2023-12-19 12:49:26', 'A'),
(18, 'Aparecido Das Graças', 'Rua das Graças, 102, Santa Helena', 'Apto 01', 'Governador Valadares', 'Minas Gerais', '35069-110', '(33) 9 9141-4767', '2023-12-19 10:31:36', '2023-12-19 13:31:36', 'A');

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
  `codigo` varchar(255) DEFAULT NULL,
  `cadastro` datetime NOT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ingredientes`
--

INSERT INTO `ingredientes` (`idingredientes`, `nomeIngred`, `img`, `quantIngred`, `pesoUnit`, `precoUnit`, `dataComp`, `dataValidad`, `codigo`, `cadastro`, `alteracao`, `ativo`) VALUES
(21, 'Leite Condensado Piracanjuba', '1702991221.png', 1, 3.95, 7.00, '2023-12-19', '2024-08-28', '7898215152002', '2023-12-19 10:07:01', '2023-12-19 13:13:43', 'A'),
(22, 'Leite em pó Integral Piracanjuba', '1702991564.png', 1, 0.40, 6.00, '2023-12-19', '2024-11-02', '7898215152347', '2023-12-19 10:12:44', '2023-12-19 13:12:44', 'A');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacote`
--

CREATE TABLE `pacote` (
  `idpacote` int(10) UNSIGNED NOT NULL,
  `idclientes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `pacote` varchar(245) NOT NULL DEFAULT '',
  `qtdPessoas` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cadastro` datetime DEFAULT NULL,
  `alteracao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ativo` char(1) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacote`
--

INSERT INTO `pacote` (`idpacote`, `idclientes`, `pacote`, `qtdPessoas`, `cadastro`, `alteracao`, `ativo`) VALUES
(45, 11, 'Ouro', 30, '2023-11-12 22:20:00', '2023-12-19 13:03:06', 'A'),
(46, 12, 'Diamante', 50, '2023-11-23 23:25:00', '2023-12-19 13:03:06', 'A'),
(47, 10, 'Premium', 90, '2023-12-15 09:20:00', '2023-12-19 13:03:06', 'A'),
(48, 9, 'Halloween', 25, '2023-12-17 21:10:27', '2023-12-19 13:30:20', 'D'),
(49, 13, 'Clássico', 20, '2023-12-18 20:01:27', '2023-12-19 13:30:20', 'A'),
(50, 17, 'Aniversário Infantil', 35, '2023-12-19 10:01:27', '2023-12-19 13:23:11', 'D');

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
(101, 45, 11, '2023-12-19 10:21:18', '2023-12-19 13:21:19', 'A', 68.00, 'Pacote ideal para festas pequenas', 10),
(102, 45, 12, '2023-12-19 10:21:19', '2023-12-19 13:21:19', 'A', 68.00, 'Pacote ideal para festas pequenas', 20),
(103, 45, 15, '2023-12-19 10:21:19', '2023-12-19 13:21:19', 'A', 68.00, 'Pacote ideal para festas pequenas', 10),
(104, 49, 6, '2023-12-19 10:24:16', '2023-12-19 13:24:16', 'A', 80.00, 'Porçãozinha de peixes', 20),
(105, 46, 14, '2023-12-19 10:24:42', '2023-12-19 13:24:42', 'A', 88.00, 'Mini-pizza combina com mini-coxinhas', 20),
(106, 46, 12, '2023-12-19 10:24:42', '2023-12-19 13:24:42', 'A', 88.00, 'Mini-pizza combina com mini-coxinhas', 20),
(107, 47, 13, '2023-12-19 10:25:25', '2023-12-19 13:25:25', 'A', 122.00, 'Pacote exclusivo para o nosso cliente', 20),
(108, 47, 11, '2023-12-19 10:25:25', '2023-12-19 13:25:25', 'A', 122.00, 'Pacote exclusivo para o nosso cliente', 10),
(109, 47, 15, '2023-12-19 10:25:25', '2023-12-19 13:25:25', 'A', 122.00, 'Pacote exclusivo para o nosso cliente', 4),
(110, 47, 14, '2023-12-19 10:25:25', '2023-12-19 13:25:25', 'A', 122.00, 'Pacote exclusivo para o nosso cliente', 15);

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
  `dataEntrega` date DEFAULT NULL,
  `cor_pedidos` varchar(60) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(6, 'peixeFrito.jpg', 'Peixe Frito', 4.00, '2023-12-18 04:50:00', '2023-12-19 13:19:08', 'A'),
(11, 'pasteizinhos.jpg', 'Pasteizinhos', 3.00, '2023-12-18 04:50:00', '2023-12-19 13:20:37', 'A'),
(12, 'miniCoxinha.jpg', 'Mini-Coxinha', 0.40, '2023-12-18 04:50:00', '2023-12-19 13:19:09', 'A'),
(13, 'doceCoco.jpg', 'Doce de Coco', 1.00, '2023-12-18 04:50:00', '2023-12-19 13:19:09', 'A'),
(14, 'miniPizza.jpg', 'Mini-Pizza', 4.00, '2023-12-18 04:50:00', '2023-12-19 13:19:09', 'A'),
(15, 'quibe.jpg', 'Quibe Grande', 3.00, '2023-12-18 04:50:00', '2023-12-19 13:19:09', 'A');

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
  ADD PRIMARY KEY (`idpacote`,`idclientes`),
  ADD KEY `fk_pacote_idclientes` (`idclientes`);

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
  MODIFY `idclientes` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `idingredientes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `pacote`
--
ALTER TABLE `pacote`
  MODIFY `idpacote` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de tabela `pacotecadastro`
--
ALTER TABLE `pacotecadastro`
  MODIFY `idpacotecadastro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idpedidos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idprodutos` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pacote`
--
ALTER TABLE `pacote`
  ADD CONSTRAINT `fk_pacote_idclientes` FOREIGN KEY (`idclientes`) REFERENCES `clientes` (`idclientes`) ON DELETE CASCADE ON UPDATE CASCADE;

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
