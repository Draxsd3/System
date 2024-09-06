-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/09/2024 às 23:09
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `relatorios_voos`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `contas_a_pagar`
--

CREATE TABLE `contas_a_pagar` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `data_vencimento` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `status` enum('Pendente','Pago') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contas_a_pagar`
--

INSERT INTO `contas_a_pagar` (`id`, `descricao`, `data_vencimento`, `valor`, `status`) VALUES
(13, 'Conta de Agua ', '2024-10-05', 150.00, 'Pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `controle_bomba`
--

CREATE TABLE `controle_bomba` (
  `id` int(11) NOT NULL,
  `tipo_operacao` enum('entrada','saida') NOT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  `data_operacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `operador` varchar(255) NOT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `controle_bomba`
--

INSERT INTO `controle_bomba` (`id`, `tipo_operacao`, `quantidade`, `data_operacao`, `data_hora`, `operador`, `observacoes`) VALUES
(1, 'entrada', 100.00, '2024-09-01 23:57:52', '2024-09-02 00:12:54', '', NULL),
(2, 'saida', 30.00, '2024-09-01 23:58:01', '2024-09-02 00:12:54', '', NULL),
(3, 'entrada', 300.00, '2024-09-02 00:06:44', '2024-09-02 00:12:54', '', NULL),
(4, 'saida', 100.00, '2024-09-02 00:14:42', '2024-09-02 00:14:42', '', NULL),
(5, 'entrada', 100.00, '2024-09-02 00:41:18', '2024-09-02 00:41:18', '', NULL),
(6, 'saida', 500.00, '2024-09-02 00:41:28', '2024-09-02 00:41:28', '', NULL),
(7, 'saida', 100.00, '2024-09-02 02:57:47', '2024-09-02 02:57:47', '', NULL),
(8, 'saida', 12.00, '2024-09-02 16:46:04', '2024-09-02 16:46:04', '1', '1231231'),
(9, 'saida', 12.00, '2024-09-02 16:46:36', '2024-09-02 16:46:36', '1', '1231231'),
(10, 'saida', 12.00, '2024-09-02 16:46:58', '2024-09-02 16:46:58', '1', '1231231'),
(11, 'saida', 1.00, '2024-09-02 17:01:01', '2024-09-02 17:01:01', '1', '1'),
(12, 'saida', 1.00, '2024-09-02 17:01:10', '2024-09-02 17:01:10', '1', '1'),
(13, 'saida', 1.00, '2024-09-02 17:09:38', '2024-09-02 17:09:38', '1', '1'),
(14, 'saida', 10.00, '2024-09-02 17:19:28', '2024-09-02 17:19:28', '5', '123'),
(15, 'entrada', 12.00, '2024-09-02 17:29:33', '2024-09-02 17:29:33', '12', '12'),
(16, 'entrada', 12.00, '2024-09-02 17:30:15', '2024-09-02 17:30:15', '12', '12'),
(17, 'entrada', 12.00, '2024-09-02 17:30:30', '2024-09-02 17:30:30', '12', '12'),
(18, 'saida', 1.00, '2024-09-02 17:30:37', '2024-09-02 17:30:37', '1', '1'),
(19, 'saida', 1.00, '2024-09-02 17:31:13', '2024-09-02 17:31:13', '1', '1'),
(20, 'saida', 1.00, '2024-09-02 17:31:35', '2024-09-02 17:31:35', '1', '1'),
(21, 'saida', 1.00, '2024-09-02 17:31:49', '2024-09-02 17:31:49', '1', '1'),
(22, 'saida', 1.00, '2024-09-02 17:31:56', '2024-09-02 17:31:56', '1', '1'),
(23, 'saida', 1.00, '2024-09-02 17:33:23', '2024-09-02 17:33:23', '1', '1'),
(24, 'entrada', 12.00, '2024-09-02 20:11:14', '2024-09-02 20:11:14', '12', '12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `item` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `finalizados`
--

CREATE TABLE `finalizados` (
  `id` int(11) NOT NULL,
  `pre_voo_id` int(11) DEFAULT NULL,
  `observacoes` text NOT NULL,
  `data_finalizacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pre_voos`
--

CREATE TABLE `pre_voos` (
  `id` int(11) NOT NULL,
  `piloto` varchar(100) NOT NULL,
  `tecnico` varchar(100) NOT NULL,
  `data_voo` date NOT NULL,
  `local` varchar(255) NOT NULL,
  `detalhes` text NOT NULL,
  `finalizado` tinyint(1) DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pre_voos`
--

INSERT INTO `pre_voos` (`id`, `piloto`, `tecnico`, `data_voo`, `local`, `detalhes`, `finalizado`, `criado_em`) VALUES
(63, 'Piloto', 'tecnico', '2024-09-02', 'registro', '1\r\n', 0, '2024-09-02 20:01:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `quantidade`, `preco`) VALUES
(30, 'Caneta ', 10, 20.00),
(31, '12', 12, 12.00),
(32, '12', 12, 12.00),
(33, '13', 13, 13.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `contas_a_pagar`
--
ALTER TABLE `contas_a_pagar`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `controle_bomba`
--
ALTER TABLE `controle_bomba`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `finalizados`
--
ALTER TABLE `finalizados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_voo_id` (`pre_voo_id`);

--
-- Índices de tabela `pre_voos`
--
ALTER TABLE `pre_voos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `contas_a_pagar`
--
ALTER TABLE `contas_a_pagar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `controle_bomba`
--
ALTER TABLE `controle_bomba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `finalizados`
--
ALTER TABLE `finalizados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `pre_voos`
--
ALTER TABLE `pre_voos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `finalizados`
--
ALTER TABLE `finalizados`
  ADD CONSTRAINT `finalizados_ibfk_1` FOREIGN KEY (`pre_voo_id`) REFERENCES `pre_voos` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
