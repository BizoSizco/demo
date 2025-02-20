<?php

/**
 * 2007-2025 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @author    BizoSizco <contact@bizosizco.com>
 * @copyright 2007-2025 PrestaShop SA and Contributors
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Bs_Videoslider extends Module
{
    public function __construct()
    {
        $this->name = 'bs_videoslider';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'BizoSizco';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Video Slider');
        $this->description = $this->l('Display video sliders in your shop based on selected hooks.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
    }

    /**
     * نصب ماژول و ایجاد جداول و تب مدیریت
     * @return bool
     */
    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayHome') && // هوک پیش‌فرض
            $this->installTab();
    }

    /**
     * حذف ماژول و پاک‌سازی دیتابیس و تب
     * @return bool
     */
    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');
        return $this->uninstallTab() && parent::uninstall();
    }

    /**
     * ایجاد تب مدیریت در زیرمجموعه کاتالوگ
     * @return bool
     */
    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminVideoslider';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Video Sliders');
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminCatalog');
        $tab->module = $this->name;
        return $tab->add();
    }

    /**
     * حذف تب مدیریت
     * @return bool
     */
    private function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminVideoslider');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    /**
     * اضافه کردن فایل‌های CSS و JS به هدر
     */
    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
    }

    /**
     * نمایش اسلایدر در هوک‌های انتخاب‌شده
     * @param string $hook_name نام هوک
     * @return string
     */
    protected function displaySlider($hook_name)
    {
        if (!class_exists('BsVideosliderModel')) {
            require_once _PS_MODULE_DIR_ . $this->name . '/classes/BsVideosliderModel.php';
        }

        $sliders = BsVideosliderModel::getActiveByHook($hook_name);
        if (empty($sliders)) {
            return '';
        }

        $this->context->smarty->assign([
            'sliders' => $sliders,
            'module_dir' => $this->_path
        ]);

        return $this->display(__FILE__, 'views/templates/hook/slider.tpl');
    }

    /**
     * هوک displayHome (پیش‌فرض)
     */
    public function hookDisplayHome($params)
    {
        return $this->displaySlider('displayHome');
    }

    /**
     * هدایت به صفحه مدیریت
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminVideoslider'));
    }

    /**
     * پشتیبانی از هوک‌های داینامیک
     */
    public function __call($method, $args)
    {
        if (preg_match('/^hook/', $method)) {
            $hook_name = str_replace('hook', '', $method);
            return $this->displaySlider($hook_name);
        }
        return parent::__call($method, $args);
    }
}
