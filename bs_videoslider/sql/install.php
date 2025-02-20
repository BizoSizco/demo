<?php

/**
 * اسکریپت نصب برای ایجاد جدول دیتابیس
 */

$sql = [
    "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "bs_videoslider` (
        `id_bs_videoslider` INT(11) NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL,
        `position` VARCHAR(255) NOT NULL,
        `videos` TEXT NOT NULL,
        `active` TINYINT(1) NOT NULL DEFAULT 0,
        `date_add` DATETIME NOT NULL,
        `date_upd` DATETIME NOT NULL,
        PRIMARY KEY (`id_bs_videoslider`)
    ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;"
];

foreach ($sql as $query) {
    if (!Db::getInstance()->execute($query)) {
        return false;
    }
}
