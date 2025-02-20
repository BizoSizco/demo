<?php

/**
 * مدل دیتابیس برای مدیریت اسلایدرهای ویدیویی
 */

class BsVideosliderModel extends ObjectModel
{
    public $id_bs_videoslider;
    public $title;
    public $position;
    public $videos;
    public $active;
    public $date_add;
    public $date_upd;

    /**
     * تعریف ساختار جدول دیتابیس
     */
    public static $definition = [
        'table' => 'bs_videoslider',
        'primary' => 'id_bs_videoslider',
        'fields' => [
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255],
            'position' => ['type' => self::TYPE_STRING, 'validate' => 'isHookName', 'required' => true, 'size' => 255],
            'videos' => ['type' => self::TYPE_STRING, 'validate' => 'isJson', 'required' => true],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate']
        ]
    ];

    /**
     * دریافت اسلایدرهای فعال بر اساس هوک
     * @param string $hook_name نام هوک
     * @return array
     */
    public static function getActiveByHook($hook_name)
    {
        $sql = 'SELECT *
                FROM `' . _DB_PREFIX_ . 'bs_videoslider`
                WHERE `active` = 1 AND `position` = "' . pSQL($hook_name) . '"
                ORDER BY `date_add` ASC';
        return Db::getInstance()->executeS($sql);
    }
}
