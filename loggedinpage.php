<?php

session_start();
$diaryContent = "";

if(array_key_exists("id", $_COOKIE) && $_COOKIE['id']){
    $_SESSION['id'] = $_COOKIE['id'];

}
if(array_key_exists("id", $_SESSION) && $_SESSION['id']){
   
   include "./includes/db.php";
   $query = "SELECT diary FROM user WHERE id= " .mysqli_real_escape_string($link,$_SESSION['id'])." LIMIT 1";
   $result= mysqli_query($link, $query);
   $row = mysqli_fetch_array($result);
   $diaryContent = $row['diary'];
}else{
    header("Location:index.php");
}

include("header.php") ?>


<nav class="navbar navbar-expand-lg navbar-faded navbar-fixed-top bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Secret Diary</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
      <form class="d-flex">
      <a href="index.php?logout=1" class="btn btn-primary btn  role="button" aria-disabled="true">Log Out</a>

    </div>
  </div>
</nav>

<div class="container-fluid" id="containerLoggedInPage">
    <textarea name="" class="form-control" id="diary" cols="30" rows="10"><?php echo $diaryContent ?></textarea>
</div>

<?php include("footer.php")?>