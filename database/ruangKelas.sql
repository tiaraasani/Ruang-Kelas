-- Ruang Kelas - database schema
-- Generated from the live schema with mysqldump (structure only).
--
-- Import:
--   mysql -u root -p < database/ruangKelas.sql
--
-- Recommended: create a least-privilege application user (never use root):
--   CREATE USER 'ruangkelas_app'@'localhost' IDENTIFIED BY 'your-strong-password';
--   GRANT SELECT, INSERT, UPDATE, DELETE ON ruangkelas.* TO 'ruangkelas_app'@'localhost';
--   FLUSH PRIVILEGES;

CREATE DATABASE IF NOT EXISTS `ruangkelas` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ruangkelas`;

SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daftar_kelas` (
  `id_daftar` int NOT NULL AUTO_INCREMENT,
  `id_kelas` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_daftar`),
  UNIQUE KEY `uq_daftar` (`id_kelas`,`username`),
  KEY `fk_daftar_user` (`username`),
  CONSTRAINT `fk_daftar_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  CONSTRAINT `fk_daftar_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id_kelas` int NOT NULL AUTO_INCREMENT,
  `namakelas` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `mapel` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kodeKelas` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_kelas`),
  UNIQUE KEY `kodeKelas` (`kodeKelas`),
  KEY `fk_kelas_user` (`username`),
  CONSTRAINT `fk_kelas_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `komentar` (
  `id_komentar` int NOT NULL AUTO_INCREMENT,
  `isi_komentar` text COLLATE utf8mb4_general_ci NOT NULL,
  `id_tugas` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_komentar`),
  KEY `fk_komentar_tugas` (`id_tugas`),
  KEY `fk_komentar_user` (`username`),
  CONSTRAINT `fk_komentar_tugas` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`) ON DELETE CASCADE,
  CONSTRAINT `fk_komentar_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_attempts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `attempted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_login_attempts_username` (`username`,`attempted_at`),
  KEY `idx_login_attempts_ip` (`ip_address`,`attempted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materi` (
  `id_materi` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_kelas` int NOT NULL,
  `judul_materi` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `file` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_materi`),
  KEY `fk_materi_kelas` (`id_kelas`),
  KEY `fk_materi_user` (`username`),
  CONSTRAINT `fk_materi_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  CONSTRAINT `fk_materi_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai` (
  `id_nilai` int NOT NULL AUTO_INCREMENT,
  `id_kumpul` int NOT NULL,
  `angka_nilai` int DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  UNIQUE KEY `uq_nilai` (`id_kumpul`),
  CONSTRAINT `fk_nilai_kumpul` FOREIGN KEY (`id_kumpul`) REFERENCES `pengumpulan` (`id_kumpul`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `nilai_siswa` AS SELECT 
 1 AS `id_kumpul`,
 1 AS `id_tugas`,
 1 AS `nama_siswa`,
 1 AS `username`,
 1 AS `judul_tugas`,
 1 AS `tanggal_kumpul`,
 1 AS `file_kumpul`,
 1 AS `angka_nilai`*/;
SET character_set_client = @saved_cs_client;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumpulan` (
  `id_kumpul` int NOT NULL AUTO_INCREMENT,
  `id_tugas` int NOT NULL,
  `file_kumpul` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_kumpul` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_kumpul`),
  UNIQUE KEY `uq_kumpul` (`id_tugas`,`username`),
  KEY `fk_kumpul_user` (`username`),
  CONSTRAINT `fk_kumpul_tugas` FOREIGN KEY (`id_tugas`) REFERENCES `tugas` (`id_tugas`) ON DELETE CASCADE,
  CONSTRAINT `fk_kumpul_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumuman` (
  `id_pengumuman` int NOT NULL AUTO_INCREMENT,
  `isi_pengumuman` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_pengumuman` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_kelas` int DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_pengumuman`),
  KEY `fk_pengumuman_kelas` (`id_kelas`),
  KEY `fk_pengumuman_user` (`username`),
  CONSTRAINT `fk_pengumuman_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  CONSTRAINT `fk_pengumuman_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `id_role` int NOT NULL,
  `nama_role` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tugas` (
  `id_tugas` int NOT NULL AUTO_INCREMENT,
  `judul_tugas` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi_tugas` text COLLATE utf8mb4_general_ci,
  `id_kelas` int NOT NULL,
  `deadline` datetime DEFAULT NULL,
  `file_tugas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_upload` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_tugas`),
  KEY `fk_tugas_kelas` (`id_kelas`),
  KEY `fk_tugas_user` (`username`),
  CONSTRAINT `fk_tugas_kelas` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  CONSTRAINT `fk_tugas_user` FOREIGN KEY (`username`) REFERENCES `user` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `id_role` int NOT NULL,
  PRIMARY KEY (`username`),
  KEY `fk_user_role` (`id_role`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP VIEW IF EXISTS `nilai_siswa`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `nilai_siswa` AS select `p`.`id_kumpul` AS `id_kumpul`,`p`.`id_tugas` AS `id_tugas`,`u`.`nama` AS `nama_siswa`,`p`.`username` AS `username`,`t`.`judul_tugas` AS `judul_tugas`,`p`.`tanggal_kumpul` AS `tanggal_kumpul`,`p`.`file_kumpul` AS `file_kumpul`,`n`.`angka_nilai` AS `angka_nilai` from (((`pengumpulan` `p` join `user` `u` on((`u`.`username` = `p`.`username`))) join `tugas` `t` on((`t`.`id_tugas` = `p`.`id_tugas`))) left join `nilai` `n` on((`n`.`id_kumpul` = `p`.`id_kumpul`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

SET FOREIGN_KEY_CHECKS = 1;

-- Reference data: roles
INSERT INTO `role` VALUES (1,'guru'),(2,'siswa');

-- Demo accounts (bcrypt hashes). Plaintext: guru1=guru123, siswa1=siswa123, siswa2=siswa123.
-- Change or remove these before production.
INSERT INTO `user` (`username`, `nama`, `password`, `id_role`) VALUES ('guru1','Budi Guru','$2y$10$Y2i0pVd6VRwSYW1ayV0QOuvkWrVjxzqRnSXlJsltQ1cCgfpTUtqWK',1),('siswa1','Ani Siswa','$2y$10$QRtb.4wZ8aoAfKuBBxOGleQrsDTQSxOmDVtGJHp11U/J8ppcgjzzS',2),('siswa2','Cahyo Siswa','$2y$10$2g74ev0vb8NaYsoHzEGe8uJsIUHVhwAPZViL3fvOoiFPzNpLK4/lG',2);
