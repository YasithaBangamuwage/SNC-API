<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_access extends CI_Controller {

	public function __construct(){
		parent::__construct();
        // To use base_url and redirect on this controller.
        $this->load->helper('url');
        $this->load->library('facebook'); // Automatically picks appId and secret from config
        $this->load->model('user_model');
	}

    /*if user already logged into the facebook then update profile if not do login and access profile*/
	public function login(){

		$user = $this->facebook->getUser();
        if ($user) {
            try {
               redirect(base_url('facebook_access/user'), 'location');
            } catch (FacebookApiException $e) {
                $user = null;
            }
        }else {
            //$this->facebook->destroySession();
            $user = null;
            $loginUrl = $this->facebook->getLoginUrl(array(
                                                            'redirect_uri' => base_url('facebook_access/user'), 
                                                            'scope' => array(
                                                                            "email,offline_access,
                                                                             read_stream,user_location,
                                                                             user_activities, user_checkins,
                                                                             user_events,user_groups,
                                                                             user_status,user_interests,
                                                                             user_likes
                                                                             ")));
            redirect($loginUrl);
        }
	}

    /* access logged in user's facebook profile */
    public function user(){

        $user = $this->facebook->getUser();
        if ($user) {
            try {

               // echo $accessToken = $this->facebook->getAccessToken();// store for use
                $_COOKIE['fbs_appId'] = $this->facebook->setAccessToken($this->facebook->getAccessToken());

                $user_id = 10;//(should have to pass user id client request)
                $socialnetwork_id = 1;
                $user_socialnetwork_id = $user;
                $userData = $this->user_model->checkUser($user_id);
                //check user already registerd 
                if(empty($userData)){//new user
                  echo 'new user </br>';
                  $feedsArray = $this->getProfileFeeds();
                  $this->readFeeds($feedsArray, $user_id, $socialnetwork_id, $user_socialnetwork_id);
                   echo 'profile feeds added (limit 500)</br>';
                  $feedsArray = $this->getHomeFeeds();
                  $this->readFeeds($feedsArray, $user_id, $socialnetwork_id, $user_socialnetwork_id);
                   echo 'home feeds added (limit 500) </br>';
                }else{// exsiting user
                  echo 'exsiting user';
                }


                $since= strtotime('2014-01-01 14:35:08' );
                $until = strtotime(date('Y-m-d H:i:s'));

             //  $feedsArray = $this->getProfileFeeds();
              // $this->readFeeds($feedsArray, $user_id, $socialnetwork_id, $user_socialnetwork_id);

  
        
            } catch (FacebookApiException $e) {
                $user = null;
            }
        }else{
            echo 'SNC API error !!';
        }
    }

  


  /*read one feed and pass data to user model*/
  private function readFeeds($feedsArray, $user_id, $socialnetwork_id, $user_socialnetwork_id){

    foreach ($feedsArray['data'] as $feed) {
     // echo "fffff ".$user_id.$socialnetwork_id.$user_socialnetwork_id;
       $feedData = array(); 
       $feedData['user_id'] = $user_id;
       $feedData['socialnetwork_id'] = $socialnetwork_id;
       $feedData['user_socialnetwork_id'] = $user_socialnetwork_id;

      if(array_key_exists('name', $feed)){
        $feedData['name'] = $feed['name'];
      }else{
        $feedData['name'] = null;
      }
      if(array_key_exists('description', $feed)){
        $feedData['description'] = $feed['description'];
      }else{
        $feedData['description'] = null;
      }
      if(array_key_exists('story', $feed)){
        $feedData['story'] = $feed['story'];
      }else{
        $feedData['story'] = null;
      }
      if(array_key_exists('message', $feed)){
        $feedData['message'] = $feed['message'];
      }else{
        $feedData['message'] = null;
      }
      if(array_key_exists('comments', $feed)){

        $commentsArray = $this->getComments($feed['id']);
        $commentSet = null;
        foreach ($commentsArray['data'] as $comment) {
          $commentSet = $commentSet.'/'.$comment['message'];
        }
        $feedData['comments'] = $commentSet;
      }else{
        $feedData['comments'] = null;
      }
      $feedData['last_update'] = date('Y-m-d H:i:s');
      $this->user_model->insertFeed($feedData);
    }
  }

  public function revokingLogin(){
    $user = $this->facebook->getUser();
    if ($user) {
      try {
          $ret = $this->facebook->api('/me/permissions', 'DELETE');
          echo $ret;
          echo 'revoke login';
          }
            catch (FacebookApiException $e) {
              $user = null;
            }
    }else{
          echo 'no user !';
      }
  }
 
  public function logout(){
        // Logs off session from website
    $this->facebook->destroySession();
    echo 'logout true';
  }

  private function getMe(){
    $response = $this->facebook->api('/me');
    echo  var_dump($response);
  }

  private function getInterests(){
    $response = $this->facebook->api('/me/interests');
    echo  var_dump($response);
  }

  private function getProfileFeeds(){
    $response = $this->facebook->api('/me/feed?limit=500', array('fields' => 'id, name, story, description, created_time, message,comments'));
    //echo  var_dump($response);
    return  $response;
  }


  private function getHomeFeeds(){
    $response = $this->facebook->api('/me/home?limit=500', array('fields' => 'id, name, story, description, created_time, message,comments'));
    return $response;
  }

  private function getLatestProfileFeeds($since, $until){
    $response = $this->facebook->api('/me/feed?since='.$since.'&until='.$until, array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
  }

  private function getLatestHomeFeeds($since, $until){
    $response = $this->facebook->api('/me/feed?since='.$since.'&until='.$until, array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
  }

  private function getAllGroups(){
    $response = $this->facebook->api('/me/groups');
    echo  var_dump($response);
  }

  private function getGroupFeeds($gruopID, $since, $until){
    $response = $this->facebook->api('/186929031465556/feed?since='.$since.'&until='.$until, array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
  }

  private function getLatestGroupFeeds($gruopID){
    $response = $this->facebook->api('/186929031465556/feed?limit=200', array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
  }

  

  private function getTagged(){
    $response = $this->facebook->api('/me/tagged?limit=200',array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
  }

  private function getLatestTagged($since, $until){
    $response = $this->facebook->api('/me/tagged?since='.$since.'&until='.$until, array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
  }

  private function getAllLikes($user){
    $response = $this->facebook->api('/'.$user.'/likes', array('fields' => 'category, name'));
    echo  var_dump($response);
  }

  private function getLatestLikes(){
    $response = $this->facebook->api('/me/likes?since='.$since.'&until='.$until, array('fields' => 'category, name'));
    echo  var_dump($response);
  }

  private function getComments($id){
    $response = $this->facebook->api('/'.$id.'/comments');
    return $response;
  }

    /*public function getlinks(){
        $response = $this->facebook->api('/'.$user.'/links');
        echo  var_dump($response);
    }
      */


      public function demoGroupData(){
      $this->getLatestGroupFeeds(333);
    }

     public function demoProfileData(){
      $this->demogetProfileFeeds();
    }

     public function demoHomeData(){
      $this->demogetHomeFeeds();
    }


     public function demoMeData(){
      $this->getMe();
    }

     public function demogetAllGroupsData(){
      $this->getAllGroups();
    }

      private function demogetProfileFeeds(){
    $response = $this->facebook->api('/me/feed?limit=500', array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
    //return  $response;
  }

   private function demogetHomeFeeds(){
    $response = $this->facebook->api('/me/home?limit=500', array('fields' => 'id, name, story, description, created_time, message,comments'));
    echo  var_dump($response);
  }
}

