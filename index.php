<?php include "./includes/db.php"?>
<?php session_start();

if(!$link){

    echo "not connected to db";
}

if (array_key_exists("logout", $_GET)){
    session_unset();
    setcookie("id","",time()-(60*60));
    $_COOKIE['id'] = "";

}else if ((array_key_exists("id",$_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])){
    header("Location:loggedinpage.php");
}

if (array_key_exists("submit", $_POST)){
    //print_r($_POST);
}

    $error = "";
    $success = "";

if(isset($_POST['userEmail']) && isset($_POST['userPassword'])){
    $email= ($_POST['userEmail']);
    $password= ($_POST['userPassword']);
 
    

    if(!$_POST["userEmail"]){

        $error .= "Please do not leave email blank.</br>";
     }

    if(!$_POST["userPassword"]){

       $error .= "Please do not leave password blank.</br>";
    }
    if ($_POST["userEmail"] && (filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)==false)) {
       $error .= "Please use a valid email format. </br>";
   }
   if($error !=""){
      $error = '<div class="alert alert-danger alert-dismissable fade show " role="alert"><p><strong>There were errors in your form:</strong></p>'. $error . '<button type="button" class="btn-close .alert-dismissible" data-bs-dismiss="alert" aria-label="Close"></button></div>';
   }else{
        if($_POST['signUp']==1){
            if($email != "") {

                $email = mysqli_real_escape_string($link,$email);
                $query= "SELECT * FROM user where email='".$email."'";
                $result= mysqli_query($link, $query);
                $num_rows = mysqli_num_rows($result);
                if($num_rows >= 1){
                    $error = '<div class="alert alert-danger alert-dismissable fade show " role="alert"><p><strong>Your email is already in the database</strong></p><button type="button" class="btn-close .alert-dismissible" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                }else{

                    $email = mysqli_real_escape_string($link,$email);
                    $user_password = mysqli_real_escape_string($link,$password );

                    $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));



                    $query = "INSERT into user(email,password) VALUE('$email', '$user_password')";
                    $create_user_query = mysqli_query($link, $query);
                    $_SESSION['email'] = $email;
                    $_SESSION['id'] = mysqli_insert_id($link);
                    if($_POST['stayLoggedIn']==1){
                        setcookie("id",mysqli_insert_id($link), time()+(60*60*24*365) ); 
                    }
                    header("location:loggedinpage.php");
                
                    
                    $success .= '<div class="alert alert-primary alert-dismissable fade show " role="alert"><p><strong>Registration Successful</strong></p><button type="button" class="btn-close .alert-dismissible" data-bs-dismiss="alert" aria-label="Close"></button></div>';

                }
            }
        }  else {
            
           
            $email = mysqli_real_escape_string($link,$email);
            $user_password = mysqli_real_escape_string($link,$password );
         
            $query= "SELECT * FROM user where email='".$email."'";
            $result= mysqli_query($link, $query);
            $row =mysqli_fetch_array($result);
            if(password_verify($user_password, $row["password"])){
                
                echo "log in"; 
                $_SESSION['id'] == $row['id'];

                if($_POST['stayLoggedIn']==1){
                    setcookie("id", $row['id'], time()+(60*60*24*365) ); 
                }
                header("location:loggedinpage.php");
            }else{
                $error = '<div class="alert alert-danger alert-dismissable fade show " role="alert"><p><strong>Your email or password was incorrect</strong></p><button type="button" class="btn-close .alert-dismissible" data-bs-dismiss="alert" aria-label="Close"></button></div>';
               
            }
        } 
   }
}



?>

<?php include("header.php") ?>

  <body>
    
        
     
    <div class="container mb-3" id="homePageContainer">
    
        <h1>Secret Diary</h1>
            
                 <p><strong>Store your thoughts securely</strong></p>
                <div id="error"><?php echo $error.$success; ?></div>
               
           
       
        <div class="row">
            
            <form method="POST" id="signUpForm">
                <p>Interested?  Sign Up Now?</p>
                <div class="mb-3">
                    
                    <input type="email" class="form-control" id="userEmail" name="userEmail" aria-describedby="usernameHelp" placeholder="Your Email">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    
                    <input type="password" name="userPassword" class="form-control" id="userPassword" placeholder="Password">
                </div>
                <div class="mb-3 form-check">
                    <input type="hidden" name="signUp" value="1">
                    <input type="checkbox" name="stayLoggedIn" value="1"  class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" name="checkboxLogin" for="exampleCheck1">Stay Logged in</label>
                </div>
                <button type="submit" name="submit" value="Submit!" class="btn btn-success">Submit</button>
                <p><a href="#" class="toggleForms">Log In Bitches</a></p>
                </form>
            
      
           
            <form method="POST" id="logInForm">
            <p>Have an account?  Sign in!</p>
                <div class="mb-3">
                    
                    <input type="email" class="form-control" id="userEmail" name="userEmail" aria-describedby="usernameHelp" placeholder="Your Email">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                   
                    <input type="password" name="userPassword" class="form-control" id="userPassword" placeholder="Password">
                </div>
                <div class="mb-3 form-check">
                    <input type="hidden" name="signUp" value="0">
                    <input type="checkbox" name="stayLoggedIn" value="1"  class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" name="checkboxLogin" for="exampleCheck1">Stay Logged in</label>
                </div>
                <button type="submit" name="submit" value="LogIn" class="btn btn-primary">Log In</button>
                <p><a href="#" class="toggleForms">Sign Up Bitches</a></p>
            </form>
            
        </div>
    </div>

    <?php include("footer.php") ?>