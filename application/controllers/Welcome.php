<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->helper('url');
		$this->load->library('session');
		
		//$this->session->set_userdata('token', '121');
		$loginPage= site_url('welcome/loginIn');
		$dashBoard= site_url('welcome/dashBoard');
		$validate_token= site_url('restapi/validate_token');
		
		if(isset($_SESSION['token'])){
			//Check if the session is valid or not expired			
			$token=$_SESSION['token'];			 			
			 
			$data = array('token' => $token);			 
			
			$curl = curl_init($validate_token);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$json = curl_exec($curl);
			curl_close($curl);
			
			 
			if ($json === FALSE) { 
				redirect($loginPage."/InvalidToken", 'refresh');
			}
			else{
			
				$json=json_decode($json,true);
				 
				if($json['loggedIn']){
					redirect($dashBoard, 'refresh');
				}
				else{
					
					redirect($loginPage."/".($json['message']), 'refresh');
				}
			} 
		}
		else{
			redirect($loginPage."/InvalidToken", 'refresh');
		}
		
		$this->load->view('welcome_message');
		
	}
	
	
	
	public function loginIn($message=''){
		//echo "Login page";
		
		
		$this->load->helper('url'); 
		$validate_token= site_url('restapi/login_check');
		$loginPage= site_url('welcome/loginIn');
		$dashBoard= site_url('welcome/dashBoard');
		$welcome= site_url('welcome/');
		if(isset($_POST['submit'])){
			 
			  
			$username= $this->input->post('username');
			$password= md5($this->input->post('password'));
			
			//send both parameter in login_check
			 
			$data = array('username' => $username,'password' => $password);			
			
			$curl = curl_init($validate_token);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$json = curl_exec($curl);
			curl_close($curl);
			 
			 
			if ($json === FALSE) { 
				redirect($loginPage.'/'.$json['message'], 'refresh');
			}
			else{
				
				$json=json_decode($json,true);
				 
				if($json['loggedIn']){
					//Logged in
					 
					
					$this->load->library('session');		
					$this->session->set_userdata('token', $json['token']);
					redirect($welcome, 'refresh');
				}
				else{
					 
					redirect($loginPage.'/'.$json['message'], 'refresh');
				}
				 
			}
			
			
		}
		 
		$data=['message'=>$message];
		$this->load->view('login',$data);
	}
	
	public function logOut(){
		$this->load->library('session');
		$token=$_SESSION['token'];
		
		$this->db->where("token",$token);
		
		$this->db->update('admin',array('token'=>''));
		
		$welcome= site_url('welcome/');
		redirect($welcome,'refresh');
	}
	
	public function dashBoard(){
		
		$this->logoutIfSessionExpired();
		
		$token=$_SESSION['token'];
		$this->db->where("token",$token);
		$data=$this->db->get('admin',0,1)->result();
		
		$data=$data[0];
		$data->pageName="Home";
		
		$chefCount=$this->db->get('chefs')->num_rows();
		$data->chefCount=$chefCount;
		
		$this->load->view('dashboard',$data);
	}
	
	public function posts(){
		$this->logoutIfSessionExpired();
		$token=$_SESSION['token'];
		$this->db->where("token",$token);
		$data=$this->db->get('admin',0,1)->result();
		$data=$data[0];
		$data->pageName="Posts Hierarchy";
		$data->baseUrl=base_url();
		
		$rows=$this->db->get("jobpost")->result();
		//$rows=$rows[0];
		
		$data->jobposts=$rows;
		
		$this->load->view('posts',$data);
	}
	
	
	public function associates_filtered($filter_name,$filter_value){
		$this->logoutIfSessionExpired();
		$token=$_SESSION['token'];
		$this->db->where("token",$token);
		$data=$this->db->get('admin',0,1)->result();
		$data=$data[0];
		$data->pageName="Associates Filtered";
		
		//Get the all the chefs
		$data->baseUrl=base_url();
		$data->filter_name=$filter_name;
		$data->filter_value=$filter_value;
		//$data->chefs=$this->db->get("chefs")->result();
		
		$this->load->view('associates_filtered',$data);
	}
	
	public function clients(){
		$this->logoutIfSessionExpired();
		$token=$_SESSION['token'];
		$this->db->where("token",$token);
		$data=$this->db->get('admin',0,1)->result();
		$data=$data[0];
		$data->pageName="Clients";
		
		 
		$data->baseUrl=base_url();
		 
		
		$this->load->view('clients',$data);
	}
	
	
	public function chefs(){
		$this->logoutIfSessionExpired();
		$token=$_SESSION['token'];
		$this->db->where("token",$token);
		$data=$this->db->get('admin',0,1)->result();
		$data=$data[0];
		$data->pageName="Associates";
		
		//Get the all the chefs
		$data->baseUrl=base_url();
		$data->chefs=$this->db->get("chefs")->result();
		
		$this->load->view('chefs',$data);
	}
	
	public function disciplines(){
		$this->logoutIfSessionExpired();
		$token=$_SESSION['token'];
		$this->db->where("token",$token);
		$data=$this->db->get('admin',0,1)->result();
		$data=$data[0];
		$data->pageName="Disciplines";
		
		//Get the all the chefs
		$data->baseUrl=base_url();
		$data->disciplines=$this->db->get("disciplines")->result();
		
		$this->load->view('disciplines',$data);
	}
	
	
	public function chef_edit($id){
		$this->logoutIfSessionExpired();
		$token=$_SESSION['token'];
		$this->db->where("token",$token);
		$data=$this->db->get('admin',0,1)->result();
		$data=$data[0];
		$data->pageName="Edit Associate";
		$data->positions=$this->db->get("jobpost")->result();
		 
		//Get the all the chefs
		$data->baseUrl=base_url();
		$this->db->where('id',$id);
		$chef=$this->db->get("chefs")->result();
		$chef=$chef[0];
		$data->chef=$chef;
		//Get the all the deciplenes
		$data->disciplines=$this->db->get("disciplines")->result();
		
		$this->load->view('chef_edit',$data);
	}
	
	public function logoutIfSessionExpired(){
		$token=$_SESSION['token'];			 			
			 
		$data = array('token' => $token);	
		$loginPage= site_url('welcome/loginIn');
		$validate_token= site_url('restapi/validate_token');
		$curl = curl_init($validate_token);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($curl);
		curl_close($curl);
		
		if ($json === FALSE) { 
				redirect($loginPage."/InvalidToken", 'refresh');
			}
			else{
				$json=json_decode($json,true);
				if(!$json['loggedIn']){redirect($loginPage."/".($json['message']), 'refresh');}
			} 
	}
}
