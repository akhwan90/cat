ALTER TABLE `m_soal` ADD `media_a` VARCHAR(300) NOT NULL AFTER `opsi_e`, ADD `media_b` VARCHAR(300) NOT NULL AFTER `media_a`, ADD `media_c` VARCHAR(300) NOT NULL AFTER `media_b`, ADD `media_d` VARCHAR(300) NOT NULL AFTER `media_c`, ADD `media_e` VARCHAR(300) NOT NULL AFTER `media_d`;
CREATE TABLE `db_cat_tiga`.`instansi` ( `id` INT(1) NOT NULL ,  `nama` VARCHAR(100) NOT NULL ,  `header_1` VARCHAR(100) NOT NULL ,  `header_2` VARCHAR(100) NOT NULL ,  `logo` VARCHAR(100) NOT NULL ) ENGINE = InnoDB;
CREATE TABLE `tr_guru_tes_soal` (
  `id` int(10) NOT NULL,
  `id_guru_tes` int(10) NOT NULL,
  `id_soal` int(10) NOT NULL,
  `urutan` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `tr_guru_tes_soal`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `tr_guru_tes_soal`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;
