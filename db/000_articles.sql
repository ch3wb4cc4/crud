SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `000_articles` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL DEFAULT '0',
  `edited` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edited_by` tinytext COLLATE utf8mb4_unicode_ci,
  `published` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` tinytext COLLATE utf8mb4_unicode_ci,
  `headline` tinytext COLLATE utf8mb4_unicode_ci,
  `teaser` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `000_articles`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `000_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
