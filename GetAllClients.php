<?php
session_start(); 
include 'API_Functions.php';

$APIClientFetchURL = 'https://localhost:5001/api/Client';

$GET_requestAgentData = GET_CurlAPIRequest($APIClientFetchURL);


if (isset($_POST['edit'])) {

  $itemId = filter_input(INPUT_POST, 'itemId');

  $_SESSION['tempIdClient'] = $itemId;

  header("Location: PostClientForm.php?edit=true");


}else if (isset($_POST['delete'])){

  $itemId = filter_input(INPUT_POST, 'itemId');

  $results = DELETE_ONE_CurlAPIRequest($APIClientFetchURL, $itemId);

  header("Location: GetAllClients.php");

}

;?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Get All Clients Page</title>
    <style>
      .form-div{
        position: relative;
        top: 10%;
        left: 30%;
        margin-top: -50px;
        margin-left: -50px;
        width: 40%;
        height: 100px;
      }

      .error-label{
        color:red;
      }

    </style>
</head>
<body>


<?php include 'Resources/includes/header.php';?>

  <div class="form-div" >


  
    <!--Title Section of Post Food Form -->
    <center>
      <h4>Get All Clients Table</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6><br>
    </center>




<!--*****************************************-->
<!-- THIS FORM BELOW NEEDS TO GET REWORKED. -->
<!--*****************************************-->


<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Client #</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Phone</th>
      <th scope="col">Email</th>
      <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>

    <?php foreach ($GET_requestAgentData as $oneAgent):?> 

      <tr>
        <th scope="row"><?= $oneAgent["id"];?></th>
        <td><?= $oneAgent["firstname"];?></td>
        <td><?= $oneAgent["lastname"];?></td>
        <td><?= $oneAgent["phone"];?></td>
        <td><?= $oneAgent["email"];?></td>
        <form class="" method="post" action = "" enctype="multipart/form-data">

          <td>
            <input type="text" name="itemId" value="<?php echo $oneAgent["id"];?>" readonly style="display:none">
            <button  name="edit" type="submit" class="btn btn-success btn-sm rounded-0" data-toggle="tooltip" data-placement="top" title="Edit">EDIT</button>
          </td>

          <td>
            <input type="text" name="itemId" value="<?php echo $oneAgent["id"];?>" readonly style="display:none">
            <button  name="delete" type="submit" class="btn btn-danger btn-sm rounded-0"data-toggle="tooltip" data-placement="top" title="Delete">DELETE</button>
          </td>

        </form>
      </tr>

    <?php endforeach;?>
    
 
  </tbody>
</table>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>