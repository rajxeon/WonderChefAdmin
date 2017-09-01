<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class RestApi extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

	
	public function validate_token_post(){
		$token =trim( $this->post('token'));	 	
		
		$this->db->where("token",$token);
		$result=$this->db->get("admin",0,1)->result();
		if(empty($result)){
			
			$data =['id' => null, 'name' => 'null', 'token' => $token,'loggedIn'=>false,"message"=>"Invalid Token"]      ;
		}
		else{
			$from_time =strtotime( $result[0]->lastLoggedin);
			$to_time=strtotime(date("Y-m-d H:i:s"));
			if((int) round(abs($to_time - $from_time)/60 ,0)>60){
				//Session expired				
				$data =['id' => $result[0]->id, 'name' => $result[0]->name, 'loggedIn' => $token,'loggedIn'=>false,"message"=>"Session expired"];
			}
			else{
				//Login is valid				
				$data =['id' => $result[0]->id, 'name' => $result[0]->name, 'loggedIn' => $token,'loggedIn'=>true,"message"=>"Successfully logged in"];			 
			}
		}
		
		 
		$this->set_response($data, REST_Controller::HTTP_OK);
	}
	
	public function login_check_post(){
		$username =( $this->post('username'));
		$password =( $this->post('password'));
		
		$this->db->where(array('username'=>$username,'password'=>$password));
		$result=$this->db->get("admin",0,1)->result();
		 
		
		if(empty($result)){
			$data =['loggedIn'=>false,"message"=>"Invalid cedentials",'token'=>''];
		}
		else{
			$id=$result[0]->id;			
			$new_token=sha1(md5(time().md5($id)));			
			
			//Update new_token and logged in time
			$lastLoggedin=(date("Y-m-d H:i:s"));
			$token=$new_token;
			
			$this->db->where("id",$id);
			$this->db->update('admin',array("lastLoggedin"=>$lastLoggedin,"token"=>$token));
			
			$data =['loggedIn'=>true,"message"=>"Success",'token'=>$new_token];
		}
		
		$this->set_response($data, REST_Controller::HTTP_OK);
	}
	
	public function getchefs_get(){
		$result=$this->db->get('chefs')->result();		 
		$this->set_response($result, REST_Controller::HTTP_OK);
	}
	
	public function addchefs_post(){
		
		$chef_name=$this->post('chef_name');
		$chef_phone=$this->post('chef_phone');
		$chef_address=$this->post('chef_address');
		$chef_experience=$this->post('chef_experience');
		
		$data = array(
		   'name' => $chef_name,
		   'phone' => $chef_phone ,
		   'address' => $chef_address, 
		   'experience' => $chef_experience
		);
		
		$id=$this->db->insert('chefs',$data);
		 
		
		$this->set_response($id, REST_Controller::HTTP_OK);
	}
	
	public function addposition_post(){		
		$position_name=$this->post('position_name');
		$parent_id=$this->post('parent_id'); 
		
		$data = array(
		   'name' => $position_name,
		   'parent' => $parent_id  
		);
		
		$id=$this->db->insert('jobpost',$data);
		 
		
		$this->set_response($id, REST_Controller::HTTP_OK);
	}
	
	public function updatePositionDetails_post(){
		$id=$this->post('id');		
		$name=$this->post('name');		
		$description=$this->post('description');	
		$parent=$this->post('parent');	
		
	 
		
		if(empty($name)){
			$name="No name";
		}
		$data = array(
		   'name' => $name,
		   'description' => $description,
		   'parent' => $parent  
		);
		
		$this->db->where('id',$id);
		if($this->db->update('jobpost',$data)){
			$this->set_response('true', REST_Controller::HTTP_OK);
		}
		else{
			$this->set_response('false', REST_Controller::HTTP_OK);
		}
		
	}
	
	public function deletePostWithChild_post(){
		//Delete a jobpost and its child
		$id=$this->post('id');		
		
		
		
		$this->db->where('id',$id);
		if($this->db->delete('jobpost')){
			$this->db->where('parent',$id);	
			if($this->db->delete('jobpost')){
				$this->set_response("true", REST_Controller::HTTP_OK);
				//Delete the orphans
				//Need to modify code
				$sql='select id from jobpost where (id not in(SELECT DISTINCT  `parent` FROM `jobpost`) and parent<>0) and (id not in (select id from jobpost where parent in (select id from jobpost where id in(SELECT DISTINCT  `parent` FROM `jobpost`))))';
				$result=$this->db->query($sql)->result();
				
				$str='(';
				foreach($result as $r){
					//$str.=$r;
					$str.=( $r->id).',';
				}
				$str = rtrim($str, ',');
				$str.=')';
				if(strlen($str)>2){
					$sql_delete="DELETE from jobpost where id in ".$str;
					$this->set_response("true", REST_Controller::HTTP_OK);
					$this->db->query($sql);	
				}
				
			}
			else{
				$this->set_response("false", REST_Controller::HTTP_OK);
			}
				
		}
		else{
			$this->set_response("false", REST_Controller::HTTP_OK);
		}
		
		
		
		
		
	}
	
	public function updateChef_post(){
		$id=$this->post('id');		
		$this->db->where('id',$id);
		
		$name=$this->post('name');
		$address=$this->post('address');		
		$phone=$this->post('phone');		
		$active=$this->post('active');		
		$position=$this->post('position');		
		
		$data = array(
               'name' => $name,
               'address' => $address,
               'phone' => $phone,
               'position' => $position,
               'active' => $active
			   
            );
		if($this->db->update('chefs', $data)){
			$this->set_response("true", REST_Controller::HTTP_OK);
		}
		else{
			$this->set_response("false", REST_Controller::HTTP_OK);
		}
	}
	
	public function getDisciplines_post(){
		$id=$this->post('id');
		$this->db->where('id',$id);
		$data=$this->db->get("chefs")->result();
		$data=$data[0];
		$disciplines=$data->disciplines;
		$this->set_response($disciplines, REST_Controller::HTTP_OK);
	}
	
	
	public function addDisciplines_post(){
		$id=$this->post('id');		
		$discipline=$this->post('discipline');		
		
		//Get all the discipline from chef
		$this->db->where('id',$id);
		$data=$this->db->get("chefs")->result();
		$data=$data[0];
		
		$p_disciplines=$data->disciplines;
		$p_disciplines=explode("-", $p_disciplines);
		$p_disciplines[]=$discipline;
		
		$p_disciplines=(array_unique($p_disciplines));
		
		$discipline=implode('-',$p_disciplines).'-';
		
		$this->db->where('id',$id);
		$data=array(
		'disciplines'=>$discipline
		);
		
		if($this->db->update('chefs', $data)){
			$this->set_response("true", REST_Controller::HTTP_OK);
		}
		else{
			$this->set_response("false", REST_Controller::HTTP_OK);
		}
	}
	
	
	public function getPositionDetails_post(){
		$id=$this->post('id');		
		$this->db->where('id',$id);
		$data=$this->db->get('jobpost')->result(); 
		
		$temp=array();
		$temp[]=$data[0];
		
		//Also get the number of associates that comes under the node
		$this->db->where('position',$id);
		$num_rows = $this->db->count_all_results('chefs');
		$temp[]=$num_rows;
		
		$this->set_response($temp, REST_Controller::HTTP_OK);
	}
	
	
	public function deletechefs_post(){
		$id=$this->post('id');
		
		$this->db->where('id',$id);
		$data=$this->db->delete('chefs'); 
		$this->set_response($data, REST_Controller::HTTP_OK);
	}
	
	public function toggleChefsActive_post(){
		$id=$this->post('id');
		$active=$this->post('active');
		
		if($active=='true'){
			$active=1;
		}
		else{
			$active=0;
		}
		
		$this->db->where("id",$id);
		
		$data = array(
               'active' => $active
            );
			
		if($this->db->update('chefs', $data)){
			$this->set_response("true", REST_Controller::HTTP_OK);
		}
		else{
			$this->set_response("false", REST_Controller::HTTP_OK);
		}
		
		
	}
	
	public function getPostTree_post(){
		 $query = $this->db->query("call GenerateTreeView()");
		 $this->set_response($query->result(), REST_Controller::HTTP_OK);
         
	}
	
    public function users_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'fact' => 'Loves coding'],
            ['id' => 2, 'name' => 'Jim', 'email' => 'jim@example.com', 'fact' => 'Developed on CodeIgniter'],
            ['id' => 3, 'name' => 'Jane', 'email' => 'jane@example.com', 'fact' => 'Lives in the USA', ['hobbies' => ['guitar', 'cycling']]],
        ];

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retrieval.
        // Usually a model is to be used for this.

        $user = NULL;

        if (!empty($users))
        {
            foreach ($users as $key => $value)
            {
                if (isset($value['id']) && $value['id'] === $id)
                {
                    $user = $value;
                }
            }
        }

        if (!empty($user))
        {
            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
