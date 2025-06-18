<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('datalib');
    }

    public function index()
    {
        $this->load->view('user_view');
    }

    public function get_users()
    {
        $output = $this->datalib
            ->from('users') // Automatically selects all columns
            ->where('status', 'active')
            ->add_column('action', '<a href="edit/$1">Edit</a>', 'id')
            ->generate('UTF-8', 'keybased');

        $this->output
            ->set_content_type('application/json')
            ->set_output($output);
    }
}
