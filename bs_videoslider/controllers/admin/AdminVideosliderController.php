<?php

/**
 * کنترلر مدیریت برای لیست، اضافه و ویرایش اسلایدرها
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'bs_videoslider/classes/BsVideosliderModel.php';

class AdminVideosliderController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'bs_videoslider';
        $this->className = 'BsVideosliderModel';
        $this->identifier = 'id_bs_videoslider';
        $this->_defaultOrderBy = 'date_add';
        $this->_defaultOrderWay = 'ASC';

        parent::__construct();

        $this->fields_list = [
            'id_bs_videoslider' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'title' => [
                'title' => $this->l('Slider Title'),
                'align' => 'left'
            ],
            'position' => [
                'title' => $this->l('Position'),
                'align' => 'center'
            ],
            'active' => [
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-sm'
            ]
        ];

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            ]
        ];
    }

    public function initToolbar()
    {
        parent::initToolbar();

        $this->toolbar_btn['new'] = [
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->l('Add New Slider'),
            'icon' => 'process-icon-new'
        ];
    }

    public function renderForm()
    {
        $hooks = array_map(function ($hook) {
            return ['id' => $hook['name'], 'name' => $hook['name']];
        }, Hook::getHooks());

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Video Slider'),
                'icon' => 'icon-film'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Slider Title'),
                    'name' => 'title',
                    'required' => true
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Position'),
                    'name' => 'position',
                    'required' => true,
                    'options' => [
                        'query' => $hooks,
                        'id' => 'id',
                        'name' => 'name'
                    ]
                ],
                [
                    'type' => 'html',
                    'name' => 'videos_html',
                    'html_content' => $this->generateVideosHtml()
                ],
                [
                    'type' => 'select',
                    'label' => $this->l('Status'),
                    'name' => 'active',
                    'required' => true,
                    'options' => [
                        'query' => [
                            ['id' => 1, 'name' => $this->l('Enabled')],
                            ['id' => 0, 'name' => $this->l('Disabled')]
                        ],
                        'id' => 'id',
                        'name' => 'name'
                    ]
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ],
            'buttons' => [
                [
                    'href' => $this->context->link->getAdminLink('AdminVideoslider'),
                    'title' => $this->l('Back'),
                    'icon' => 'process-icon-back'
                ]
            ]
        ];

        // مقدار اولیه برای videos و پاس دادن به Smarty
        $videos = [];
        if ($id_slider = (int)Tools::getValue('id_bs_videoslider')) {
            $slider = new BsVideosliderModel($id_slider);
            if ($slider->videos) {
                $videos = json_decode($slider->videos, true);
            }
        }
        $this->context->smarty->assign('videos', $videos);

        return parent::renderForm();
    }

    protected function generateVideosHtml()
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'bs_videoslider/views/templates/admin/videoslider_form.tpl');
    }

    public function processSave()
    {
        $object = parent::processSave();
        if ($object) {
            $videos_json = Tools::getValue('videos', false);
            if (!empty($videos_json) && Validate::isJson($videos_json)) {
                $object->videos = $videos_json;
            } else {
                $this->errors[] = $this->l('Invalid or empty video data', 'bs_videoslider');
                return false;
            }

            $object->date_upd = date('Y-m-d H:i:s');
            if (!$object->id) {
                $object->date_add = date('Y-m-d H:i:s');
            }

            return $object->save();
        }
        return false;
    }

    public function ajaxProcessUpdateStatus()
    {
        if (!$id_bs_videoslider = (int)Tools::getValue('id_bs_videoslider')) {
            die(json_encode([
                'success' => false,
                'error' => true,
                'text' => $this->l('Failed to update the status', 'bs_videoslider')
            ]));
        }

        $slider = new BsVideosliderModel($id_bs_videoslider);
        if (Validate::isLoadedObject($slider)) {
            $slider->active = !(bool)$slider->active;
            $slider->date_upd = date('Y-m-d H:i:s');

            if ($slider->save()) {
                die(json_encode([
                    'success' => true,
                    'text' => $this->l('The status has been updated successfully', 'bs_videoslider')
                ]));
            }
        }

        die(json_encode([
            'success' => false,
            'error' => true,
            'text' => $this->l('Failed to update the status', 'bs_videoslider')
        ]));
    }
}
