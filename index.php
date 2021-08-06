<?php include "./includes/db.php"?>
<?php session_start();

if(!$link){

    echo "not connected to db";
}

if (array_key_exists("logout", $_GET)){
    unset($_SESSION);
    setcookie("id","",time()-(60*60));
    $_COOKIE['id'] = "";

}else if (array_key_exists("id",$_SESSION) OR (array_key_exists("id", $_COOKIE))){
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


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div id="error"><?php echo $error.$success; ?></div>
                <div id="success"></div>
            </div>
        </div>

    </div>
    
    </div>
    <div class="container  mb-3">
        <div class="row">
            <div class="col-lg-12 col-md-12">
            <form method="POST">
                <div class="mb-3">
                    <label for="userEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" aria-describedby="usernameHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="userPassword" class="form-label">Password</label>
                    <input type="password" name="userPassword" class="form-control" id="userPassword">
                </div>
                <div class="mb-3 form-check">
                    <input type="hidden" name="signUp" value="1">
                    <input type="checkbox" name="stayLoggedIn" value="1"  class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" name="checkboxLogin" for="exampleCheck1">Stay Logged in</label>
                </div>
                <button type="submit" name="submit" value="Submit!" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
            <form method="POST">
                <div class="mb-3">
                    <label for="userEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="userEmail" name="userEmail" aria-describedby="usernameHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="userPassword" class="form-label">Password</label>
                    <input type="password" name="userPassword" class="form-control" id="userPassword">
                </div>
                <div class="mb-3 form-check">
                    <input type="hidden" name="signUp" value="0">
                    <input type="checkbox" name="stayLoggedIn" value="1"  class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" name="checkboxLogin" for="exampleCheck1">Stay Logged in</label>
                </div>
                <button type="submit" name="submit" value="LogIn" class="btn btn-primary">Log In</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>