SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `000_users` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `user` tinytext COLLATE utf8mb4_unicode_ci,
  `email` tinytext COLLATE utf8mb4_unicode_ci,
  `token` tinytext COLLATE utf8mb4_unicode_ci,
  `sent` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edited` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edited_by` tinytext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `000_users`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `000_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
