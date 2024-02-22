SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Estrutura da tabela `companies`
--
CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `status` varchar(256) NOT NULL DEFAULT 'ACTIVE',
  `corporateName` varchar(2048) NOT NULL,
  `name` varchar(2048) NOT NULL,
  `document` varchar(256) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabela `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document` (`document`);

--
-- Extraindo dados da tabela `companies`
--
INSERT INTO `companies` (`id`, `status`, `corporateName`, `name`, `document`, `createdAt`, `updatedAt`) VALUES
(1, 'ACTIVE', 'IMPETUS FRAMEWORK', 'IMPETUS', '11.111.111/0001-11', '2024-01-21 10:05:33', NULL),
(3, 'ACTIVE', 'IMPETUS FRAMEWORK TESTE', 'Teste2', '111111', '2024-01-21 21:39:04', NULL);

--
-- AUTO_INCREMENT de tabela `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Estrutura da tabela `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `status` varchar(256) NOT NULL DEFAULT 'ACTIVE',
  `name` varchar(1024) NOT NULL,
  `email` varchar(1024) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `permission` enum('admin','user') NOT NULL,
  `isConfirmedEmail` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Extraindo dados da tabela `users`
--
INSERT INTO `users` (`id`, `status`, `name`, `email`, `username`, `password`, `permission`, `isConfirmedEmail`, `createdAt`, `updatedAt`) VALUES
(2, 'ACTIVE', 'IMPETUS', 'impetus@impetus.com', 'admin', '$2y$10$9TcxA/gjQkW0okJfA.LmTugY1rJx4U6yJKn3Xo76jp13MTaX4FAjG', 'admin', 0, '2024-01-19 21:19:05', NULL),
(7, 'INACTIVE', 'Teste', 'avante@teste.com', 'gustavosoares1', '$2y$10$JM/d4gZxizLRzNEP8Dn0BO1M00.SNEvwnafnpi3jpaLjBlOU9EwpS', 'user', NULL, '2024-01-21 20:43:35', '2024-01-21 17:49:02');

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


COMMIT;