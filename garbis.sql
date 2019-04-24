CREATE TABLE m_kategori_user (
  ID_KATEGORI_USER int NOT NULL,
  NAMA_KATEGORI_USER varchar(50) DEFAULT NULL,
  KETERANGAN varchar(150) DEFAULT NULL
)
INSERT INTO m_kategori_user (ID_KATEGORI_USER, NAMA_KATEGORI_USER, KETERANGAN) VALUES
(1, 'Developer', 'Untuk Developer'),
(2, 'Administrator', 'Untuk Admin Aplikasi'),
(3, 'Admin BKD', 'Untuk Admin BKD'),
(4, 'Admin SKPD', 'Untuk Admin SKPD'),
(5, 'Eksekutif', 'Untuk Kepala Dinas');

CREATE TABLE m_menu (
  ID_MENU int NOT NULL,
  ID_PARENT int DEFAULT NULL,
  NAMA_MENU varchar(100) DEFAULT NULL,
  JUDUL_MENU varchar(250) DEFAULT NULL,
  LINK_MENU varchar(35) DEFAULT NULL,
  ICON_MENU varchar(25) DEFAULT NULL,
  AKTIF_MENU varchar(1) DEFAULT NULL,
  TINGKAT_MENU int DEFAULT NULL,
  URUTAN_MENU int DEFAULT NULL,
  ADD_BUTTON varchar(1) DEFAULT NULL,
  EDIT_BUTTON varchar(1) DEFAULT NULL,
  DELETE_BUTTON varchar(1) DEFAULT NULL
)
INSERT INTO m_menu (ID_MENU, ID_PARENT, NAMA_MENU, JUDUL_MENU, LINK_MENU, ICON_MENU, AKTIF_MENU, TINGKAT_MENU, URUTAN_MENU, ADD_BUTTON, EDIT_BUTTON, DELETE_BUTTON) VALUES
(1, 0, 'Setting', NULL, NULL, 'cog', 'Y', 1, 4, 'N', 'N', 'N'),
(2, 1, 'Basis Data', 'Basis Data', NULL, NULL, 'Y', 2, 1, 'N', 'N', 'N'),
(3, 1, 'System', 'System', NULL, NULL, 'Y', 2, 2, 'N', 'N', 'N'),
(4, 3, 'Setting User', 'Setting User', 'setting_user', NULL, 'Y', 3, 1, 'Y', 'Y', 'Y'),
(5, 3, 'Setting Role', 'Setting Role', 'setting_role', NULL, 'Y', 3, 2, 'Y', 'Y', 'Y'),
(6, 0, 'Dashboard', 'dashboard', 'dashboard', 'dashboard', 'Y', 1, 1, 'N', 'N', 'N'),
(7, 2, 'Backup', 'Backup', 'backup', NULL, 'Y', 3, 1, 'N', 'N', 'N'),
(8, 0, 'Utilitas Menu (Developer)', 'Utilitas Menu (Developer)', 'menu', 'cog', 'Y', 1, 99, 'Y', 'Y', 'Y'),
(9, 0, 'Data Master', 'Data Master', '', 'wrench', 'Y', 1, 2, 'N', 'N', 'N'),
(10, 9, 'Kepegawaian', 'Kepegawaian', '', '', 'Y', 2, 1, 'N', 'N', 'N'),
(11, 10, 'Master Pegawai', 'Master Pegawai', 'mater_pegawai', '', 'Y', 3, 1, 'Y', 'Y', 'Y'),
(12, 10, 'Master Unit Organisasi Kerja', 'Master Unit Organisasi Kerja', 'master_unor_kerja', '', 'Y', 3, 2, 'Y', 'Y', 'Y'),
(13, 10, 'Master Instansi', 'Master Instansi', 'master_instansi', '', 'Y', 3, 3, 'Y', 'Y', 'Y'),
(14, 10, 'Master Status Pegawai', 'Master Status Pegawai', 'master_status_pegawai', '', 'Y', 3, 4, 'Y', 'Y', 'Y'),
(15, 10, 'Master Golongan', 'Master Golongan', 'master_golongan', '', 'Y', 3, 5, 'Y', 'Y', 'Y'),
(16, 10, 'Master Eselon', 'Master Eselon', 'master_eselon', '', 'Y', 3, 6, 'Y', 'Y', 'Y'),
(17, 10, 'Master Jenis Kelamin', 'Master Jenis Kelamin', 'master_jenis_kelamin', '', 'Y', 3, 7, 'Y', 'Y', 'Y'),
(18, 10, 'Master Rumpun Jabatan', 'Master Rumpun Jabatan', 'master_rumpun_jabatan', '', 'Y', 3, 8, 'Y', 'Y', 'Y'),
(19, 9, 'Master Absensi', '', '', '', 'Y', 2, 2, 'N', 'N', 'N'),
(20, 19, 'Master Agama', 'Master Agama', 'master_agama', '', 'Y', 3, 1, 'Y', 'Y', 'Y');


CREATE TABLE t_hak_akses (
  ID_MENU int NOT NULL,
  ID_KATEGORI_USER int NOT NULL,
  ADD_BUTTON varchar(1) DEFAULT NULL,
  EDIT_BUTTON varchar(1) DEFAULT NULL,
  DELETE_BUTTON varchar(1) DEFAULT NULL
)

INSERT INTO t_hak_akses (ID_MENU, ID_KATEGORI_USER, ADD_BUTTON, EDIT_BUTTON, DELETE_BUTTON) VALUES
(1, 1, '', '', ''),
(2, 1, '', '', ''),
(3, 1, '', '', ''),
(4, 1, 'Y', 'Y', 'Y'),
(5, 1, 'Y', 'Y', 'Y'),
(6, 1, '', '', ''),
(7, 1, '', '', ''),
(8, 1, 'Y', 'Y', 'Y'),
(9, 1, '', '', ''),
(10, 1, '', '', ''),
(11, 1, 'Y', 'Y', 'Y'),
(12, 1, 'Y', 'Y', 'Y'),
(13, 1, 'Y', 'Y', 'Y'),
(14, 1, 'Y', 'Y', 'Y'),
(15, 1, 'Y', 'Y', 'Y'),
(16, 1, 'Y', 'Y', 'Y'),
(17, 1, 'Y', 'Y', 'Y'),
(18, 1, 'Y', 'Y', 'Y'),
(19, 1, '', '', ''),
(20, 1, 'Y', 'Y', 'Y');
