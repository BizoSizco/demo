<?php

/**
 * اسکریپت حذف برای پاک‌سازی دیتابیس
 */

$sql = [
    "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "bs_videoslider`"
];

foreach ($sql as $query) {
    if (!Db::getInstance()->execute($query)) {
        return false;
    }
}
