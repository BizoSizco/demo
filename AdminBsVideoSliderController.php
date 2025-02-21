<?php
/**
 * @author    BizoSizco <info@bizosiz.com>
 * @copyright 2025 BizoSizco
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminBsVideoSliderController extends ModuleAdminController
{
    protected $position_identifier = 'id_slider';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'bs_videoslider';
        $this->identifier = 'id_slider';
        $this->className = 'BsVideoSlider';
        $this->lang = false;
        
        parent::__construct();

        $this->fields_list = [
            'id_slider' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'align' => 'center',
                'class' => 'fixed-width-xs'
            ],
            'title' => [
                'title' => $this->trans('Title', [], 'Admin.Global'),
                'align' => 'left',
            ],
            'position' => [
                'title' => $this->trans('Hook Position', [], 'Admin.Global'),
                'align' => 'center',
                'type' => 'select',
                'list' => [
                    'displayHome' => $this->trans('Home', [], 'Admin.Global'),
                    'displayLeftColumn' => $this->trans('Left Column', [], 'Admin.Global'),
                    'displayRightColumn' => $this->trans('Right Column', [], 'Admin.Global'),
                    'displayFooter' => $this->trans('Footer', [], 'Admin.Global')
                ],
                'filter_key' => 'a!position',
                'callback' => 'getPositionName'
            ],
            'active' => [
                'title' => $this->trans('Status', [], 'Admin.Global'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-sm',
                'orderby' => false
            ],
            'date_add' => [
                'title' => $this->trans('Created', [], 'Admin.Global'),
                'align' => 'center',
                'type' => 'datetime'
            ],
            'date_upd' => [
                'title' => $this->trans('Modified', [], 'Admin.Global'),
                'align' => 'center',
                'type' => 'datetime'
            ]
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Actions'),
                'icon' => 'icon-trash',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Warning')
            ]
        ];

        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function getPositionName($position)
    {
        $positions = [
            'displayHome' => $this->trans('Home', [], 'Admin.Global'),
            'displayLeftColumn' => $this->trans('Left Column', [], 'Admin.Global'),
            'displayRightColumn' => $this->trans('Right Column', [], 'Admin.Global'),
            'displayFooter' => $this->trans('Footer', [], 'Admin.Global')
        ];

        return isset($positions[$position]) ? $positions[$position] : $position;
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_slider'] = [
                'href' => self::$currentIndex.'&addbs_videoslider&token='.$this->token,
                'desc' => $this->trans('Add New Slider', [], 'Admin.Actions'),
                'icon' => 'process-icon-new'
            ];
        }

        parent::initPageHeaderToolbar();
    }

    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        $this->fields_form = [
            'legend' => [
                'title' => $this->trans('Video Slider', [], 'Admin.Global'),
                'icon' => 'icon-film'
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'required' => true,
                    'class' => 'fixed-width-xl',
                    'hint' => $this->trans('Enter the slider title.', [], 'Admin.Global')
                ],
                [
                    'type' => 'select',
                    'label' => $this->trans('Position', [], 'Admin.Global'),
                    'name' => 'position',
                    'required' => true,
                    'options' => [
                        'query' => [
                            ['id' => 'displayHome', 'name' => $this->trans('Home', [], 'Admin.Global')],
                            ['id' => 'displayLeftColumn', 'name' => $this->trans('Left Column', [], 'Admin.Global')],
                            ['id' => 'displayRightColumn', 'name' => $this->trans('Right Column', [], 'Admin.Global')],
                            ['id' => 'displayFooter', 'name' => $this->trans('Footer', [], 'Admin.Global')]
                        ],
                        'id' => 'id',
                        'name' => 'name'
                    ]
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Status', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global')
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global')
                        ]
                    ]
                ],
                [
                    'type' => 'free',
                    'label' => $this->trans('Videos', [], 'Admin.Global'),
                    'name' => 'video_list'
                ]
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions')
            ],
            'buttons' => [
                'save-and-stay' => [
                    'type' => 'submit',
                    'title' => $this->trans('Save and Stay', [], 'Admin.Actions'),
                    'icon' => 'process-icon-save',
                    'class' => 'btn btn-default pull-right',
                    'name' => 'submitAdd'.$this->table.'AndStay'
                ],
                'back' => [
                    'href' => self::$currentIndex . '&token=' . $this->token,
                    'title' => $this->trans('Back to List', [], 'Admin.Actions'),
                    'icon' => 'process-icon-back',
                    'class' => 'btn btn-default'
                ]
            ]
        ];

        // Get current videos if editing
        $videos = [];
        if ($obj->id) {
            $videos = BsVideoSlider::getVideos($obj->id, false);
        }

        $this->context->smarty->assign([
            'videos' => $videos,
            'id_slider' => $obj->id,
            'secure_key' => $this->module->secure_key,
            'ps_version' => _PS_VERSION_,
            'datetime_now' => date('Y-m-d H:i:s'),
            'current_user' => $this->context->employee->firstname.' '.$this->context->employee->lastname,
            'module_dir' => _PS_MODULE_DIR_.$this->module->name.'/'
        ]);

        // Load video form template
        $tpl = $this->context->smarty->createTemplate(
            _PS_MODULE_DIR_.'bs_videoslider/views/templates/admin/video_form.tpl'
        );
        $tpl->assign($this->context->smarty->getTemplateVars());
        
        $this->fields_value = [
            'active' => $obj->id ? $obj->active : 1,
            'video_list' => $tpl->fetch()
        ];

        return parent::renderForm();
    }

    public function processSave()
    {
        if (Tools::isSubmit('submitAdd'.$this->table) || Tools::isSubmit('submitAdd'.$this->table.'AndStay')) {
            $videoContent = Tools::getValue('video');
            if (isset($videoContent['content'])) {
                foreach ($videoContent['content'] as &$content) {
                    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
                }
                $_POST['video']['content'] = $videoContent['content'];
            }
        }

        $object = parent::processSave();
        
        if ($object) {
            // Handle videos
            $videoTitles = Tools::getValue('video');
            if (is_array($videoTitles) && isset($videoTitles['title'])) {
                // First delete existing videos if editing
                if ($object->id) {
                    // Delete old images first
                    $old_videos = Db::getInstance()->executeS('
                        SELECT image FROM `'._DB_PREFIX_.'bs_videoslider_videos`
                        WHERE `id_slider` = '.(int)$object->id
                    );
                    
                    if ($old_videos) {
                        foreach ($old_videos as $old_video) {
                            if ($old_video['image']) {
                                $old_image_path = _PS_MODULE_DIR_.'bs_videoslider/'.$old_video['image'];
                                if (file_exists($old_image_path)) {
                                    @unlink($old_image_path);
                                }
                            }
                        }
                    }

                    // Delete old records
                    Db::getInstance()->execute('
                        DELETE FROM `'._DB_PREFIX_.'bs_videoslider_videos`
                        WHERE `id_slider` = '.(int)$object->id
                    );
                }

                // Add new videos
                foreach ($videoTitles['title'] as $key => $title) {
                    if (empty($title) || !isset($videoTitles['content'][$key])) {
                        continue;
                    }

                    $image = '';
                    if (isset($_FILES['video']['name']['image'][$key]) 
                        && !empty($_FILES['video']['name']['image'][$key])) {
                        
                        // Create img directory if it doesn't exist
                        $img_dir = _PS_MODULE_DIR_.'bs_videoslider/views/img/';
                        if (!file_exists($img_dir)) {
                            mkdir($img_dir, 0777, true);
                        }

                        $ext = pathinfo($_FILES['video']['name']['image'][$key], PATHINFO_EXTENSION);
                        $image_name = 'video_'.$object->id.'_'.md5(uniqid()).'.'.$ext;
                        $image_path = $img_dir.$image_name;
                        
                        if (@move_uploaded_file(
                            $_FILES['video']['tmp_name']['image'][$key],
                            $image_path
                        )) {
                            // Set correct permissions
                            @chmod($image_path, 0644);
                            $image = 'views/img/'.$image_name;
                        }
                    }

                    Db::getInstance()->insert('bs_videoslider_videos', [
                        'id_slider' => (int)$object->id,
                        'title' => pSQL($title),
                        'image' => pSQL($image),
                        'video' => pSQL($videoTitles['content'][$key], true),
                        'position' => (int)$key,
                        'active' => 1,
                        'date_add' => date('Y-m-d H:i:s'),
                        'date_upd' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        return $object;
    }

    public function processDelete()
    {
        $object = $this->loadObject();
        if ($object) {
            // Delete all associated videos first
            $videos = Db::getInstance()->executeS('
                SELECT * FROM `'._DB_PREFIX_.'bs_videoslider_videos`
                WHERE `id_slider` = '.(int)$object->id
            );

            if ($videos) {
                foreach ($videos as $video) {
                    if ($video['image']) {
                        $image_path = _PS_MODULE_DIR_.'bs_videoslider/'.$video['image'];
                        if (file_exists($image_path)) {
                            @unlink($image_path);
                        }
                    }
                }
            }

            Db::getInstance()->execute('
                DELETE FROM `'._DB_PREFIX_.'bs_videoslider_videos`
                WHERE `id_slider` = '.(int)$object->id
            );
        }

        return parent::processDelete();
    }

    public function ajaxProcessUpdatePositions()
    {
        $positions = Tools::getValue('positions');
        
        if (is_array($positions)) {
            foreach ($positions as $position => $id_video) {
                Db::getInstance()->update(
                    'bs_videoslider_videos',
                    ['position' => (int)$position],
                    'id_video = '.(int)$id_video
                );
            }
        }
        
        die(json_encode(['success' => true]));
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        
        $this->addJqueryUi('ui.sortable');
        $this->addJS([
            _PS_MODULE_DIR_.$this->module->name.'/views/js/admin.js',
            _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
            _PS_JS_DIR_.'admin/tinymce.inc.js',
        ]);
        $this->addCSS(_PS_MODULE_DIR_.$this->module->name.'/views/css/admin.css');
    }

    protected function afterImageUpload()
    {
        parent::afterImageUpload();

        if (($id_slider = (int)Tools::getValue('id_slider')) &&
            isset($_FILES) &&
            count($_FILES) &&
            file_exists(_PS_MODULE_DIR_ . 'bs_videoslider/views/img/')
        ) {
            $authorized_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            foreach ($_FILES as $file) {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $authorized_extensions)) {
                    $name = 'video_' . $id_slider . '_' . md5(uniqid()) . '.' . $extension;
                    move_uploaded_file(
                        $file['tmp_name'],
                        _PS_MODULE_DIR_ . 'bs_videoslider/views/img/' . $name
                    );
                    @chmod(_PS_MODULE_DIR_ . 'bs_videoslider/views/img/' . $name, 0644);
                }
            }
        }
        return true;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitAdd' . $this->table) || Tools::isSubmit('submitAdd' . $this->table . 'AndStay')) {
            $_POST[$this->identifier] = (int)Tools::getValue($this->identifier);
            $_POST['active'] = (int)Tools::getValue('active');

            // Set dates if not provided
            if (!Tools::getValue('date_add')) {
                $_POST['date_add'] = date('Y-m-d H:i:s');
            }
            $_POST['date_upd'] = date('Y-m-d H:i:s');
        }

        return parent::postProcess();
    }

    public function ajaxProcessUpdateVideoStatus()
    {
        $id_video = (int)Tools::getValue('id_video');
        $status = (int)Tools::getValue('status');

        $result = Db::getInstance()->update(
            'bs_videoslider_videos',
            ['active' => $status],
            'id_video = ' . (int)$id_video
        );

        die(json_encode([
            'success' => $result,
            'status' => $status
        ]));
    }

    public function ajaxProcessDeleteVideo()
    {
        $id_video = (int)Tools::getValue('id_video');
        $video = Db::getInstance()->getRow(
            '
            SELECT * FROM `' . _DB_PREFIX_ . 'bs_videoslider_videos`
            WHERE `id_video` = ' . (int)$id_video
        );

        if ($video && $video['image']) {
            $image_path = _PS_MODULE_DIR_ . 'bs_videoslider/' . $video['image'];
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
        }

        $result = Db::getInstance()->delete(
            'bs_videoslider_videos',
            'id_video = ' . (int)$id_video
        );

        die(json_encode(['success' => $result]));
    }

    public function ajaxProcessUpdateVideoPositions()
    {
        $positions = Tools::getValue('positions');
        $success = true;

        if (is_array($positions)) {
            foreach ($positions as $position => $id) {
                $success &= Db::getInstance()->update(
                    'bs_videoslider_videos',
                    ['position' => (int)$position],
                    'id_video = ' . (int)$id
                );
            }
        }

        die(json_encode(['success' => $success]));
    }

    protected function getBulkActions()
    {
        return [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Actions'),
                'icon' => 'icon-trash',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Warning')
            ]
        ];
    }

    protected function filterToField($key, $filter)
    {
        if ($key == 'position') {
            return [
                'type' => 'select',
                'list' => [
                    'displayHome' => $this->trans('Home', [], 'Admin.Global'),
                    'displayLeftColumn' => $this->trans('Left Column', [], 'Admin.Global'),
                    'displayRightColumn' => $this->trans('Right Column', [], 'Admin.Global'),
                    'displayFooter' => $this->trans('Footer', [], 'Admin.Global')
                ],
                'filter_key' => 'a!position'
            ];
        }
        return parent::filterToField($key, $filter);
    }
}