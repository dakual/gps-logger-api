CREATE TABLE `locations` (
  `id` int NOT NULL,
  `device` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(10,8) NOT NULL,
  `accuracy` decimal(10,2) NOT NULL,
  `altitude` decimal(10,2) NOT NULL,
  `speed` decimal(10,2) NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `locations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;