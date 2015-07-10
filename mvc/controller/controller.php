<?php
include 'mvc/view/view.php';

class controller extends view{    
  
    public $site_name = 'Automatic OPC HDA Client';
    public $file;
    public $tmp_file;
    public $public_key = '6LczgQgTAAAAAIsXQFNzsfKGjQ3GcJQN8X_MZn8S';
    public $privat_key = '6LczgQgTAAAAAEFe9jBM5mcHIdi68NoSDiwXSZtx';

    public function connect_mysql(){
      $link = mysql_connect('localhost', 'h76_admin', 'aXZm1mRP');
      mysql_select_db('h76_automatic', $link);
      if (!$link) {
        die('Connection Error: ' . mysql_error());
      }
        return $link;
    }

    public function routing(){

      $this->maintenance(true);
      $this->insertToHistory();
      
      if (isset($_GET['page']) && $_GET['page']=='user') { $this->Auth(); } else
      if (isset($_GET['page']) && $_GET['page']=='registration') { $this->registrationUser(); } else
      if (isset($_GET['page']) && $_GET['page']=='contact-action') { $this->addFeddback(); } else
      if (isset($_GET['page']) && $_GET['page']=='bug-action') { $this->addBug(); } else
      if (isset($_GET['page']) && $_GET['page']=='bug-list') { $this->bugs(); } else
      if (isset($_GET['page']) && $_GET['page']=='bug-item') { $this->bugItem(); } else 
      if (isset($_GET['page']) && $_GET['page']=='contact') { $this->Contact__(); } else
      if (isset($_GET['page']) && $_GET['page']=='reg-form-action') { $this->RegFormAction(); } else
      if (isset($_GET['page']) && $_GET['page']=='logout') { $this->logout(); } else
      if (isset($_GET['page']) && $_GET['page']=='auth-form-action') { $this->checkUser(); } else
      if (isset($_GET['page']) && $_GET['page']=='admin-panel') { $this->adminPanelcontroll(); } else
      if (isset($_GET['page']) && $_GET['page']=='feedbackitem') { $this->feedbackItem(); } else
      if (isset($_GET['page']) && $_GET['page']=='item-bug-action') { $this->EditBugItem(); } else
      if (isset($_GET['page']) && $_GET['page']=='product') { $this->product(); } else 
      if (isset($_GET['page']) && $_GET['page']=='download') { $this->downloadFile(); } else
      if (isset($_GET['page']) && $_GET['page']=='payment') { $this->payment(); } else
      if (isset($_GET['page']) && $_GET['page']=='comment-action') { $this->commentAction(); } else
      if (isset($_GET['page']) && $_GET['page']=='systems') { $this->systems(); } else
      if (isset($_GET['page']) && $_GET['page']=='send-mail') { $this->sendMail(); } else
      if (isset($_GET['page']) && $_GET['page']=='add-license') { $this->addLicense(); } else
      if (isset($_GET['page']) && $_GET['page']=='enter-authcode') { $this->enterAuthcode(); } else
      if (isset($_GET['page']) && $_GET['page']=='buy-license') { $this->buyLicense(); } else
      if (isset($_GET['page']) && $_GET['page']=='authcode-action') { $this->authcodeAction(); } else
      if (isset($_GET['page']) && $_GET['page']=='re-count') { $this->reCount(); } else
      if (isset($_GET['page']) && $_GET['page']=='blog') { $this->pageBlog(); } else
      if (isset($_GET['page']) && $_GET['page']=='admin-add-blog') { $this->pageAddBlogItem(); } else
      if (isset($_GET['page']) && $_GET['page']=='action-blog-post') { $this->actionBlogItem(); } else
      if (isset($_GET['page']) && $_GET['page']=='single-post') { $this->singleBlogItem(); } else
      if (isset($_GET['page']) && $_GET['page']=='post-category') { $this->postCategory(); } else
      if (isset($_GET['page']) && $_GET['page']=='edit-post') { $this->editPost(); } else
      if (isset($_GET['page']) && $_GET['page']=='action-blog-post-edit') { $this->actionPostUpdate(); } else
      if (isset($_GET['page']) && $_GET['page']=='add-file-post') { $this->actionAddPostFiles(); } else
      if (isset($_GET['page']) && $_GET['page']=='action-delete-file-from-post') { $this->deleteFilePost(); }
      else if (isset($_GET['page'])){
         $page = $_GET['page'];        
         view::content_view($page);
      } else {
        $page = 'home';
        view::content_view($page);
      }  

    }

    public function insertToHistory(){
      $link = $this->connect_mysql();
      $ip = $_SERVER['REMOTE_ADDR'];
      if (isset($_GET['page'])) { $page = $_GET['page']; } else { $page = 'home';};
      $grant = $this->admin();
      $country = $this->ip_info($ip, "country");
      $city = $this->ip_info($ip, "sity");
      if (!$grant){
        model::addHistorySite($link, $ip, $page, $country, $city);
      }
    } 

    public function sendMail(){
      //mail('tarasdj@rambler.ru', 'Subject', 'Test');
      //view::successMessage('Email sent');
      $this->smtpmail('tarasj@hotmail.com', 'test subject', 'Test content');
      view::successMessage('Email sent smtpmail test');
    }

    public function smtpmail($to, $subject, $content, $attach=false)
    {
      require_once('/files/phpmailer/config.php'); 
      require_once('/files/phpmailer/class.phpmailer.php');
      $mail = new PHPMailer(true);
       
      $mail->IsSMTP();
      try {
        $mail->Host       = $__smtp['host'];
        $mail->SMTPDebug  = $__smtp['debug'];
        $mail->SMTPAuth   = $__smtp['auth'];
        $mail->Port       = $__smtp['port'];
        $mail->Username   = $__smtp['username'];
        $mail->Password   = $__smtp['password'];
        $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
        $mail->AddAddress($to);                
        $mail->SetFrom($__smtp['addreply'], $__smtp['username']); 
        $mail->AddReplyTo($__smtp['addreply'], $__smtp['username']);
        $mail->Subject = htmlspecialchars($subject);
        $mail->MsgHTML($content);
        if($attach)  $mail->AddAttachment($attach);
        $mail->Send();
        echo "Message sent Ok!</p>\n";
      } catch (phpmailerException $e) {
        echo $e->errorMessage();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }

    public function Contact__(){
      view::contact($this->public_key);
    }

    public function product(){
      $link = $this->connect_mysql();
      $result = model::getCountDownloads($link, 8);
      while($row = mysql_fetch_assoc($result)){
        $cd = $row['cd'];
      } 
      view::mainWrapperOpen();     
      view::viewProduct($cd);
      $this->comment(1);
      view::mainWrapperClose();
    }

    public function comment($comment_group){
      $link = $this->connect_mysql();
      $result = model::getComment($link, $comment_group);
      view::commentList($result);
      if (isset($_COOKIE['uid']) && isset($_COOKIE['hash'])):
        view::commentForm($comment_group);
      else:
        view::warningMessage('You must be registered and logined for add comments!');
        $this->auth();
      endif;  
    }

    function commentAction(){      
      $link = $this->connect_mysql();
      if (isset($_GET['gid'])):
        $group_id = $_GET['gid'];
        if (isset($_POST['subject']) && isset($_POST['comment'])):
          $comment = urlencode($_POST['comment']);
          $subject = urlencode($_POST['subject']);
          $this->verifyUser();
          $uid = $_COOKIE['uid'];
          model::insertComment($link, $uid, $group_id, $subject, $comment); 
          view::successMessage('Your comment successfully added!');
        else:
          view::errorMessage('Data Error!');
          die();
        endif;  
      endif; 
      view::redirect('home'); 
    }

    public function header(){
      if (isset($_GET['page'])) { 
        $page = $_GET['page'];
        $link = $this->connect_mysql();
        if ($page == 'single-post'){$page = $_GET['post'];} 
        $result = model::getPage($link, $page);
        while($row = mysql_fetch_assoc($result)){
          $description = $row['page_description'];
          $title = $row['page_title'];
        }
        $page = $_GET['page'];        
        view::header_view($title, $description);
      } else {
        $description = 'official site Automatic OPC HDA Client';
        view::header_view('Home'.' - '.$this->site_name, $description);
      } 
    }

    public function Auth(){
      if (!$_COOKIE["hash"]){
        view::authform();
      } else {
        $this->verifyUser();
        $this->dashboard();
      }      
    }

    public function dashboard(){
        $hash = $_COOKIE["hash"];
        $uid = $_COOKIE["uid"];
        $this->verifyUser();
        $link = $this->connect_mysql();
        $result = model::getUser($link, $hash);
        view::userInfoLeft($result); 
        $result = model::getLicensed($link, $uid);
        $lcount = mysql_num_rows($result);        
        view::titleYourLicense();
        view::rightSideOpen();
        if ($lcount == 0){
          view::emptyLicense();
          view::addLicenseButton();
        } else{
          while($row = mysql_fetch_assoc($result)){
            $lid = $row['id'];
            $authcode = $row['authcode'];
            $regcode = $row['registercode'];
            $pay_class = $row['payment'];
            $reg = $row['reg'];           
            if ($pay_class == 0){
              $pay_class = 'demo';
              view::demo_license($lid, $authcode, $regcode, $pay_class, array('Buy License', 'buy-license'));
            } else {
              if ($reg == 0) { 
                $pay_class = 'demo';    
                view::demo_license($lid, $authcode, $regcode, $pay_class, array('Enter my authcode (paid)', 'enter-authcode&lid='.$lid)); 
              } else {
                $pay_class = 'buy';
                view::paid_up_license($lid, $authcode, $regcode, $pay_class);
              } 
            }
        } 
        view::addLicenseButton(); 
        view::mainWrapperClose();  
      }
    }

    public function registrationUser(){
      if ($_COOKIE["hash"]) {
          $this->verifyUser();
          $this->dashboard();
      } else {
          view::regForm($this->public_key);
      }
    }

    public function logout(){
      setcookie("hash", "", time() - 3600*24*30*12, "/"); 
      setcookie("uid", "", time() - 3600*24*30*12, "/"); 
      view::successMessage('You are logout!');
      header("Location: index.php");   
    }

    public function checkUser(){
      if (isset($_POST['login']) && isset($_POST['password'])):
        $login = $_POST['login'];
        $pass = $_POST['password'];
        $link = $this->connect_mysql();
        $result = model::getUserLogin($link, $login);
          while($row = mysql_fetch_assoc($result)){
            $data_login = $row['login'];
            $data_password = $row['password'];
            $data_id = $row['id'];
          }
          if (md5($pass) ==  $data_password):   //User is in database
            $remember_token = md5($this->generateCode(10)); 
            setcookie("hash", $remember_token, time()+60*60*24*30);  
            setcookie("uid", $data_id, time()+60*60*24*30);
            $ip = $_SERVER['REMOTE_ADDR']; 
            model::AddUserHash($remember_token, $ip, $data_id, $link);
            model::updateUserHash($link, $remember_token, $data_id);
            $result = model::getUser($link, $remember_token);
            header("Location: index.php");            
          else:
            view::errorMessage('Password or login is incorrect!');
            $this->Auth();
          endif;  
      else:
        view::errorMessage('Authentication data is incorrect!');
      endif; 
    }

    public function getAdminPanel(){
      $this->verifyUser();
      $grant = $this->admin();      
      if ($grant): view::adminPanel(); endif;
    }

    public function admin(){
      $uid = $_COOKIE["uid"];
      $link = $this->connect_mysql();
      $g = model::isAdmin($link, $uid);      
      if ($g): 
        return TRUE;
      else: FALSE; endif;
    }

    function adminPanelcontroll(){
      $link = $this->connect_mysql();
      $grant = $this->admin();      
      if ($grant): 
        $result = model::getHistory($link);
        view::view_history($result);
        $result = model::getUsers($link);
        view::view_users($result);
        $result = model::getFeedbackList($link);
        view::view_feedbackList($result);
        $result = model::getBugList($link);
        view::view_bugList($result); 
      else:
        view::errorMessage('You not have permission for access this page!');
      endif;  
    }

    public function User(){
      if (!$_COOKIE["hash"]){
        view::userName('Login');
      } else {
        $hash = $_COOKIE["hash"];
        $link = $this->connect_mysql();
        $result = model::getUser($link, $hash);
        while($row = mysql_fetch_assoc($result)){
          $data_login = $row['login'];
        }
        $uid = $_COOKIE['uid'];
        $this->verifyUser();
        $result = model::getLicensed($link, $uid);  
        while($row = mysql_fetch_assoc($result)){
          $data_payment = $row['payment'];
        }
        if ($data_payment == '0'){$license = 'demo license';} else {$license = '';}        
        view::userName($data_login, $license);
      }            
    }

    public function RegFormAction(){
      if (isset($_POST['login']) && isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['password_confirm'])):
        //Check Captcha**************
        $recaptcha = $_POST['g-recaptcha-response'];
        if (!empty($recaptcha)):

          include("files/getCurlData.php");
          $google_url="https://www.google.com/recaptcha/api/siteverify";
          $ip=$_SERVER['REMOTE_ADDR'];
          $url=$google_url."?secret=".$this->privat_key."&response=".$recaptcha."&remoteip=".$ip;
          $res=getCurlData($url);
          $res= json_decode($res, true);

            $remember_token = md5($this->generateCode(10));
            $login = $_POST['login'];  
            $email = $_POST['mail'];
            $password = $_POST['password'];  
            $link = $this->connect_mysql();
            //********* is user existing ************************
            $result = model::getUserLogin($link, $login);
            $count = mysql_num_rows($result);
            if ($count > 0): 
              view::errorMessage("User " . $login . " already exist!"); 
              view::regForm($this->public_key);
            else:
            //***************************************************
              if (strlen($password) < 8): 
                view::errorMessage('Password must be more 8 symbols! Please try again!');
                view::regForm($this->public_key); else:
                if ($password == $_POST['password_confirm']):                 
                  model::CreateUser($login, $email, md5($password), $remember_token, $link);
                  view::successMessage('User successfully created! Hash=' . $remember_token);
                  view::regForm($this->public_key);
                else:
                  view::errorMessage("Password incorrect!"); 
                  view::regForm($this->public_key);
                endif;
              endif;   
            endif; 
          //ss}
        else:
          view::errorMessage("You are robot! Please entered captcha!");    
          view::regForm($this->public_key); 
        endif;  
        //******* End check capcha ********************
      else:
        view::errorMessage('Data is not send. Sorry, for error! Please try again!');
        view::regForm($this->public_key);     
      endif;  
    }

    // function for generated hash code for anonymus user 
    public function generateCode($length=6) 
    {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
      $code = "";
      $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
          $code .= $chars[mt_rand(0,$clen)];
        }
    return $code;
    }

    public function addFeddback() {
      if (isset($_POST['name']) && isset($_POST['subject']) && isset($_POST['email']) && isset($_POST['message'])):
        $name = urlencode($_POST['name']);
        $subject = urlencode($_POST['subject']);
        $email = urlencode($_POST['email']);
        $message = urlencode($_POST['message']);
        $link = $this->connect_mysql();

        //Check Captcha**************
        $recaptcha = $_POST['g-recaptcha-response'];
        if (!empty($recaptcha)):

          include("files/getCurlData.php");
          $google_url = "https://www.google.com/recaptcha/api/siteverify";
          $ip  = $_SERVER['REMOTE_ADDR'];
          $url = $google_url."?secret=".$this->privat_key."&response=".$recaptcha."&remoteip=".$ip;
          $res = getCurlData($url);
          $res = json_decode($res, true);

            if ($_COOKIE["uid"]): $uid = $_COOKIE["uid"]; else: $uid = 0;  endif; 
            $this->verifyUser();       
            model::AddContactItem($subject, $email, $name, $message, $link, $uid, $ip);
            view::successMessage('Your message is successfully send to admnistrator! We contact you about 1 day!');
            $this->Contact__();

        else:
          view::errorMessage("You are robot! Please entered captcha!");    
          $this->Contact__(); 
        endif;  
        //******* End check capcha ********************

      else:
        view::errorMessage('Data is not send. Sorry, for error! Please try again!');
        $this->Contact__();
      endif;  
    }

    public function addBug() {
      if (isset($_POST['title']) && isset($_POST['bug_description']) && isset($_FILES['uploadfile'])):
        $title = urlencode($_POST['title']);
        $descr = urlencode($_POST['bug_description']);
        $this->file = $_FILES['uploadfile']['name'];
        $this->tmp_file = $_FILES['uploadfile']['tmp_name'];
        $this->uploadFile('upload');
        $link = $this->connect_mysql();
        model::AddBugItem($title, $descr, $this->file, $link);
        view::successMessage('Succesfully added to database bugs! Be visible in site after administrator verification');
        $this->bugs();
      else:
        view::errorMessage('Data is not send. Sorry, for error! Please try again!');
        $this->bugs();
      endif;  
    }

    public function bugs(){
      $link = $this->connect_mysql();      
      $result = model::getBugs($link);
      $field_name = array();      
      $logg = $this->isUserLogined();
      if ($logg):
        view::bugsLeftpanel();
      else: 
        view::warningMessage('You must be registered and logined for add bugitems!');       
        $this->auth();        
      endif;  
        view::bugsRightpanel($result);
    }

    public function bugItem() {      
      if (isset($_GET['item']) && isset($_GET['page'])):
        $bid = $_GET['item'];
        $link = $this->connect_mysql();
        $result = model::getItemBug($link, $bid);
        view::bugItemView($result);
        $result = model::getItemBug($link, $bid);
        while($row = mysql_fetch_assoc($result)){
          $admin_comment = $row['comment'];
          $allowed = $row['allowed'];
          $status = $row['status'];
        }
        if ($allowed == '1'): $allowed = 'checked'; else: $allowed = ''; endif; 
        if ($status == '1'): $status = 'checked'; else: $status = ''; endif; 
        $grant = $this->admin();      
        if ($grant): 
          view::bugItemAdminPart($bid, $admin_comment, $allowed, $status);
        endif;
        $this->comment($bid);
      else: 
        view::errorMessage('Data Error!');
      endif;
    }

    public function feedbackItem(){      
      if(isset($_GET['item'])):
        $cid = $_GET['item'];
        $link = $this->connect_mysql();
        $result = model::getFeedbackItem($link, $cid);
        view::viewFeedbackItem($result);
      else:
        view::errorMessage('Data Error!');
      endif; 
    }

    public function EditBugItem(){
      if (isset($_GET['page']) && isset($_GET['item'])):
        $link = $this->connect_mysql();
        $grant = $this->admin();
        if ($grant):
            $bid = $_GET['item'];
            $comment = $_POST['admin_comment'];
            if (isset($_POST['allowed'])): $allow = 1; else: $allow = 0; endif; 
            if (isset($_POST['done'])): $status = 1; else: $status = 0; endif;
            model::UpdateCommentsStatusAllowinBugItem($link, $comment, $allow, $status, $bid);
            view::successMessage('Item successfully updated!');
        else:
          view::errorMessage('You not have permission for access this page!');
        endif;  
      else:
        view::errorMessage('Data Error!');
      endif;
    }

    public function maintenance($st_m){
      if ($st_m){view::viewMaintenance();}      
    }

    public function downloadFile(){
        $link = $this->connect_mysql();
        $file = $_GET['fname'];
        $result = model::getFileIdFromFilename($link, $file);
        $row = mysql_fetch_assoc($result);
        $file_id = $row['id'];
        if (file_exists('files/download/'.$file)) {
            if (ob_get_level()) {
              ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename = ' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize('files/download/'.$file));
            readfile('files/download/'.$file);                   
            $result = model::getDownloadIPfromFile($link, $file_id);
            $count_ip = mysql_num_rows($result);
            $ip = $_SERVER['REMOTE_ADDR'];
            if ($count_ip == 0):               
              $result = model::getCountDownloads($link, $file_id);
              while($row = mysql_fetch_assoc($result)){
                $cd = $row['cd'];
              }
              $cd = $cd + 1;
              model::updateDownload($link, $cd, $file_id);             
            endif;
            model::insertDownloadIP($link, $ip, $file_id);
        }
}

    public function verifyUser(){
      $link = $this->connect_mysql();
      $uid = $_COOKIE['uid'];
      $hash = $_COOKIE['hash'];
      $result = model::getUser($link, $hash);
      while($row = mysql_fetch_assoc($result)){
        $data_uid = $row['id'];
      }
      if ($data_uid == $uid){return TRUE;} else{
          view::errorMessage('Something wrong! Data bad! Sory!');
          die();
      }
    }

    public function isUserLogined(){
      if($_COOKIE['hash'] && $_COOKIE['uid']){
        $this->verifyUser();
        return TRUE;
      } else {return FALSE;}
    }

    public function systems(){
      view::content_view('systems');
      $this->comment(3);
    }

    //*************LICENSE***********************
    public function payment(){
      view::paymentform();
    }

    public function addLicense(){
      $this->verifyUser();
      $uid = $_COOKIE['uid'];
      $link = $this->connect_mysql();
      $ip = $_SERVER['REMOTE_ADDR'];
      $result = model::getDemoLicensed($link, $uid);
      $lcount = mysql_num_rows($result);  
      if ( $lcount < 2 ){
        model::insertDemoLicense($link, $ip, $uid);        
      }
      header("Location: ?page=user");
    }

    public function enterAuthcode(){
      $lid = $_GET['lid'];
      view::formEnterAuthcode($lid);
    }

    public function buyLicense(){
      view::paymentform();
    }

    public function authcodeAction() {
      if (isset($_GET['lid'])){
        $lid = $_GET['lid'];
        $this->verifyUser();
        $link = $this->connect_mysql();
        if (isset($_POST['authcode'])){
          $ac = $_POST['authcode'];
          model::updateAuthCode($link, $ac, $lid);
        }  
      }
    }
    //*******************************************

  public function reCount(){
    view::content_view('re_count');
    $this->comment(5);
  }

  //***********************BLOG******************
  public function pageBlog(){
    $link = $this->connect_mysql();
    $result = model::getBlogList($link);
    view::mainWrapperOpen();
    view::blogHeader();
    while ($row = mysql_fetch_assoc($result)) {
      $bid = $row['id'];
      $body = model::getBlogItem($link, $bid);
      $categories = model::getCategoryBlogItem($link, $bid);
      view::view_blog_item($body, $categories);      
    }
    view::mainWrapperClose();
  }

  public function pageAddBlogItem(){
    $link = $this->connect_mysql();
    $category_dataset = model::getBlogCategories($link);
    view::formAddBlog($category_dataset, $pid);
  }

  public function actionBlogItem() {
    if (isset($_POST['title']) && isset($_POST['teaser']) && isset($_POST['blogcontent']) && isset($_POST['option'])):
      if ($this->admin()):
        $this->verifyUser();
        $title = urlencode($_POST['title']);
        $teaser = urlencode($_POST['teaser']);
        $text = urlencode($_POST['blogcontent']);
        $view_count = 0;
        $link = $this->connect_mysql();
        $bid = model::insertBlogItem($link, $title, $teaser, $text, $view_count); 
        model::insertPages($link, $_POST['title'], $_POST['teaser'], $bid);       
        foreach($_POST['option'] as $check) {
          model::insertBlogCategoryStructure($link, $bid, $check); 
        }
        view::successMessage('Blog item successfully added!');
      else:
        view::errorMessage('Validation error!');
      endif;  
    else:
      view::errorMessage('Data error. Somthing wrong!');
    endif;
    view::redirect('admin-add-blog'); 
  }

  public function singleBlogItem(){
    if (isset($_GET['post'])):
      $bid = $_GET['post'];
      $this->incCountView($bid);
      $link = $this->connect_mysql();
      $result = model::getBlogItem($link, $bid);
      $categories = model::getCategoryBlogItem($link, $bid);
      $download = model::getDownloadList($link, $bid);
      view::singlePostItem($result, $categories, $download);
      $grant = $this->admin();
      $this->verifyUser();
      if ($grant):
        view::link('?page=edit-post&post='.$bid, 'Edit Post');
      endif;
      $this->comment($bid);
    else:
      view::errorMessage('Data error. Somthing wrong!');
      view::redirect('blog');
    endif;  
  }

  public function postCategory() {
    if (isset($_GET['category'])):
      $category = $_GET['category'];
      $link = $this->connect_mysql();
      $result = model::getPostCategory($link, $category);
      view::mainWrapperOpen();
      view::blogHeader();
      while ($row = mysql_fetch_assoc($result)) {
        $bid = $row['id'];
        $body = model::getBlogItem($link, $bid);
        $categories = model::getCategoryBlogItem($link, $bid);
        view::view_blog_item($body, $categories);      
      }
      view::mainWrapperClose();
    else:
      view::errorMessage('Data error. Somthing wrong!');
      view::redirect('blog');
    endif;   
  }

  public function incCountView($post_id){
    $link = $this->connect_mysql();
    $result = model::getBlogItem($link, $post_id);
    while ($row = mysql_fetch_assoc($result)) {
      $count = $row['view_count'];      
    }
    $count = $count + 1;
    model::updateCountView($link, $post_id, $count);  
  }

  public function editPost(){
    if (isset($_GET['post'])):
      $link = $this->connect_mysql();
      $post_id = $_GET['post'];
      $result = model::getBlogItem($link, $post_id); 
      while ($row = mysql_fetch_assoc($result)) {
        $title = urldecode($row['title']);
        $teaser = urldecode($row['teaser']); 
        $blog_text = urldecode($row['blog_text']);  
      }  
      view::formUpdatePost($title, $teaser, $blog_text, $post_id);
      view::formAddFilesToPost($post_id); 
      $download = model::getDownloadList($link, $post_id);   
      view::downloadFilesInPost($download);
      view::ajaxImgGif();    
    endif;
  }

  public function actionPostUpdate() {
    if (isset($_GET['post'])):
      $post_id = $_GET['post'];
      if (isset($_POST['title']) && isset($_POST['teaser']) && isset($_POST['body'])):
        $title = urlencode($_POST['title']);
        $teaser = urlencode($_POST['teaser']);
        $body = urlencode($_POST['body']);
        $link = $this->connect_mysql();
        model::UpdatePost($link, $title, $teaser, $body, $post_id);
        view::successMessage('Post successfully updated!');
        view::redirect('blog&post='.$post_id);
      else: 
        view::errorMessage('Data update error! Something wrong!');
        view::redirect('blog');
      endif; 
    else:
        view::errorMessage('Item data error! Something wrong!');
        view::redirect('blog');
    endif; 
  }

  public function actionAddPostFiles() {
    if (isset($_POST['filetitle']) && isset($_FILES['uploadfile'])):
      $post_id = $_GET['post'];
      $file_title = $_POST['filetitle'];
      $this->file = $_FILES['uploadfile']['name'];
      $this->tmp_file = $_FILES['uploadfile']['tmp_name'];
      $this->uploadFile('download');
      $link = $this->connect_mysql();    
      model::insertDownload($link, $this->file, $file_title, $post_id);
      header("Location: /?page=edit-post&post=".$post_id);
    endif;  
  }

  public function deleteFilePost(){
    $file_id = $_GET['file'];
    $post_id = $_GET['post'];
    $link = $this->connect_mysql();
    model::deletePostFile($link, $file_id); 
    header("Location: /?page=edit-post&post=".$post_id);
  }

  //********************************************

  function uploadFile($folder)
  {
    //*****************Загрузка файла***********************
    $uploaddir = 'files/'.$folder.'/';
    $file = $uploaddir . basename($this->file);
    move_uploaded_file($this->tmp_file, $file);
    //******************************************************  
  }

  function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
      $output = NULL;
      if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
          $ip = $_SERVER["REMOTE_ADDR"];
          if ($deep_detect) {
              if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
              if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                  $ip = $_SERVER['HTTP_CLIENT_IP'];
          }
      }
      $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
      $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
      $continents = array(
          "AF" => "Africa",
          "AN" => "Antarctica",
          "AS" => "Asia",
          "EU" => "Europe",
          "OC" => "Australia (Oceania)",
          "NA" => "North America",
          "SA" => "South America"
      );
      if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
          $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
          if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
              switch ($purpose) {
                  case "location":
                      $output = array(
                          "city"           => @$ipdat->geoplugin_city,
                          "state"          => @$ipdat->geoplugin_regionName,
                          "country"        => @$ipdat->geoplugin_countryName,
                          "country_code"   => @$ipdat->geoplugin_countryCode,
                          "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                          "continent_code" => @$ipdat->geoplugin_continentCode
                      );
                      break;
                  case "address":
                      $address = array($ipdat->geoplugin_countryName);
                      if (@strlen($ipdat->geoplugin_regionName) >= 1)
                          $address[] = $ipdat->geoplugin_regionName;
                      if (@strlen($ipdat->geoplugin_city) >= 1)
                          $address[] = $ipdat->geoplugin_city;
                      $output = implode(", ", array_reverse($address));
                      break;
                  case "city":
                      $output = @$ipdat->geoplugin_city;
                      break;
                  case "state":
                      $output = @$ipdat->geoplugin_regionName;
                      break;
                  case "region":
                      $output = @$ipdat->geoplugin_regionName;
                      break;
                  case "country":
                      $output = @$ipdat->geoplugin_countryName;
                      break;
                  case "countrycode":
                      $output = @$ipdat->geoplugin_countryCode;
                      break;
              }
          }
      }
      return $output;
  }

}
?>