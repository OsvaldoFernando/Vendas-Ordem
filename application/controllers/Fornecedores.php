<?php
defined('BASEPATH') or exit('Ação não permitida');

class Fornecedores extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//*******Verificar se o cliente esta logado
		if (!$this->ion_auth->logged_in()) {
			//**** Mensagem de sessão
			$this->session->set_flashdata('info', 'Sua sessão expirou');
			redirect('login');
		}
	}

	public function index()
	{
		//*** Variável data do tipo array (informação da documentação do plugin --- Usuário --
		$data = array(

			//*** Título da página de usuários
			'titulo' => 'Fornecedores cadastrados',

			//*** Chave para pesquisar dinamicamente os usuários(Bootstrap css)
			'styles' => array(
				'vendor/datatables/dataTables.bootstrap4.min.css',
			),
			'scripts' => array(
				'vendor/datatables/jquery.dataTables.min.js',
				'vendor/datatables/dataTables.bootstrap4.min.js',
				'vendor/datatables/app.js'
			),
			//*** Fim, Chave para pesquisar dinamicamente os usuários

			//****** Trazer toda informação da tabela
			'fornecedores' => $this->core_model->get_all('fornecedores'),

		);

//		echo '<pre>';
//		print_r($data['fornecedores']);
//		exit();


		//*******Carregar a minha view
		//*** Carregar a Views
		$this->load->view('layout/header', $data);
		$this->load->view('fornecedores/index');
		$this->load->view('layout/footer');
	}

	public function edit($fornecedor_id = NULL)
	{
//		Se ele foi passado
		if (!$fornecedor_id || !$this->core_model->get_by_id('fornecedores', array('fornecedor_id' => $fornecedor_id))) {
			$this->session->set_flashdata('error', 'Fornecedor não encontrado');
			redirect('fornecedores');

		} else {
//			|min_length[5]
//			$this->form_validation->set_rules('fornecedor_razao', 'Razão social', 'trim|required|max_length[200]|callback_check_razao_social');
//			$this->form_validation->set_rules('fornecedor_nome_fantasia', 'Nome fantasia', 'trim|required|max_length[145]|callback_check_nome_fantasia');
//			$this->form_validation->set_rules('fornecedor_telefone', 'Telefone', 'trim|max_length[20]|callback_check_telefone');
//			$this->form_validation->set_rules('fornecedor_email', 'E-mail', 'trim|required|valid_email|max_length[100]|callback_check_email');
			$this->form_validation->set_rules('fornecedor_endereco', 'Endereço', 'trim|required|max_length[145]');
			$this->form_validation->set_rules('fornecedor_bairro', 'Bairro', 'trim|required|max_length[45]');
			$this->form_validation->set_rules('fornecedor_complemento', 'Complemento', 'trim|required|max_length[45]');
			$this->form_validation->set_rules('fornecedor_cidade', 'Cidade', 'trim|required|max_length[45]');
			$this->form_validation->set_rules('fornecedor_ativo', 'Fornecedor ativo', 'trim|required|exact_length[2]');
			$this->form_validation->set_rules('fornecedor_obs', '', 'max_length[500]');

			if ($this->form_validation->run()) {
				exit('Validado');
			} else {
				//Erro de validação

				$data = array(
					'titulo' => 'Atualizar fornecedor',
					'scripts' => array(
						'vendor/datatables/jquery.dataTables.min.js',
						'vendor/datatables/dataTables.bootstrap4.min.js',
						'vendor/datatables/app.js'
					),
					'fornecedor' => $this->core_model->get_by_id('fornecedores', array('fornecedor_id' => $fornecedor_id)),
				);

//			echo '<pre>';
//			print_r($data['fornecedor']);
//			exit();

				//*** Carregar a Views
				$this->load->view('layout/header', $data);
				$this->load->view('fornecedores/edit');
				$this->load->view('layout/footer');
			}
		}
	}
}

/********** Metódo cheque fornecedor**/
function check_razao_social($fornecedor_razao)
{
	/** Recuperar o id do cliente */
	$fornecedor_id = $this->input->post('fornecedor_id');

	if ($this->core_model->get_by_id('fornecedores', array('fornecedor_razao' => $fornecedor_razao, 'fornecedor_id !=' => $fornecedor_id))) {
		$this->form_validation->set_message('check_razao_social', 'Esta razão social já existe');
		return FALSE;
	} else {
		return TRUE;
	}
}

/********** Metódo cheque email**/
function check_email($fornecedor_email)
{
	/** Recuperar o id do cliente */
	$fornecedor_id = $this->input->post('fornecedor_id');

	if ($this->core_model->get_by_id('fornecedores', array('fornecedor_email' => $fornecedor_email, 'fornecedor_id !=' => $fornecedor_id))) {
		$this->form_validation->set_message('check_email', 'Esse e-mail ja existe');
		return FALSE;
	} else {
		return TRUE;
	}
}

/********** Metódo cliente telefone**/
function check_telefone($fornecedor_telefone)
{
	/** Recuperar o id do cliente */
	$fornecedor_id = $this->input->post('fornecedor_id');

	if ($this->core_model->get_by_id('fornecedores', array('fornecedor_telefone' => $fornecedor_telefone, 'fornecedor_id !=' => $fornecedor_id))) {
		$this->form_validation->set_message('check_telefone', 'Esse telefone ja existe');
		return FALSE;
	} else {
		return TRUE;
	}

}

