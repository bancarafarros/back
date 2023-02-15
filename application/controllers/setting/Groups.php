    <?php
    if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Groups extends BaseController
    {

    public $loginBehavior = true;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_group', 'group');
    }

    public function index()
    {
        $this->data['title'] = 'User Group';
        $crud = new Grid();
        $crud->setSkin('bootstrap-v4');
        $crud->unsetJquery();
        $crud->unsetPrint();
        $crud->unsetFilters();
        $crud->unsetExport();
        $crud->setSubject('User Group');

        $crud->setTable('user_group');

        $crud->columns([
        'nama_group',
        'keterangan',
        'is_active',
        ]);

        $crud->displayAs([
        'nama_group' => 'Nama Grup',
        'keterangan' => 'Keterangan',
        'is_active' => 'Aktif'
        ]);

        $crud->addFields([
        'nama_group',
        'is_active',
        'dbusername',
        'table_name',
        'keterangan',
        ]);

        $crud->editFields([
        'nama_group',
        'is_active',
        'dbusername',
        'table_name',
        'keterangan',
        ]);

        $crud->requiredFields([
        'nama_group',
        ]);

        $crud->fieldType('is_active', 'dropdown', [
        '0' => 'Tidak Aktif',
        '1' => 'Aktif'
        ]);

        $crud->fieldType('id_group', 'hidden');

        $crud->setActionButton('Hak Akses', 'fas fa-users-cog', function ($row) {
        return site_url("setting/groups/access/$row->id_group");
        }, false);

        $output = $crud->render();
        $this->setOutput($output,'index');
    }


    public function access($group_id)
    {
        
        if(getSessionRole() == 1){
        $userGroup = $this->group->getUserGroup(intval($group_id));
        $groupAccess = $this->group->getUserGroupAccess(intval($group_id));

        $this->data['userGroup'] = $userGroup;
        $this->data['groupAccess'] = $groupAccess;
        $this->data['title'] = strtoupper($userGroup->nama_group);
        $this->data['scripts'] = ['groups/js/index.js'];
        $this->render('access/index');
        }else{
            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Akses ditolak</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('dashboard');
        }
    }

    public function assign($group_id)
    {
        $this->group->updateGroupAccess($group_id, $this->input->post('access'));

        redirect(site_url("setting/groups/access/$group_id"));
    }
    }
