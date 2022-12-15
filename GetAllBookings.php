<?php
session_start(); 
include 'API_Functions.php';

$APIAgentFetchURL = 'https://localhost:5001/api/Agent';
$APIBookingPostURL = "https://localhost:5001/api/Booking";
$APIPropertyListingPostURL = "https://localhost:5001/api/PropertyListing"; 
$APIClientPostURL = "https://localhost:5001/api/Client";


$GET_requestBookingData = GET_CurlAPIRequest($APIBookingPostURL);


if (isset($_POST['edit'])) {

  $itemId = filter_input(INPUT_POST, 'itemId');

  $_SESSION['tempIdBooking'] = $itemId;

  header("Location: PostBookingForm.php?edit=true");



}else if (isset($_POST['delete'])){

  $itemId = filter_input(INPUT_POST, 'itemId');

  $results = DELETE_ONE_CurlAPIRequest($APIBookingPostURL, $itemId);

  header("Location: GetAllBookings.php");


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
    <title>Get All Bookings Page</title>
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
      <h4>Get All Bookings Table</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6><br>
    </center>




<!--*****************************************-->
<!-- THIS FORM BELOW NEEDS TO GET REWORKED. -->
<!--*****************************************-->


<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Booking #</th>
      <th scope="col">Property</th>
      <th scope="col">Date Time</th>
      <th scope="col">Client</th>
      <th scope="col"></th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>

    <?php foreach ($GET_requestBookingData as $oneBooking):?> 

      <?php

        $onePropertyInformation = GET_ONE_CurlAPIRequest($APIPropertyListingPostURL, $oneBooking["propertyListing_ID"]);
        $oneBookingDatetime = new DateTime($oneBooking["booking_DateTime"]);
        $oneClientInformation = GET_ONE_CurlAPIRequest($APIClientPostURL, $oneBooking["client_ID"]);
      ;?>

      <tr>
        <th scope="row"><?= $oneBooking["id"];?></th>
        <td> <?= $onePropertyInformation["address"];?>, <?= $onePropertyInformation["city"];?>, <?= $onePropertyInformation["state"];?>,  <?= $onePropertyInformation["zip"];?> |  $<?= $onePropertyInformation["price"];?></td>
        <td> <?= $oneBookingDatetime->format('l  F jS Y h:ia');?></td>
        <td><?= $oneClientInformation["firstname"];?> <?= $oneClientInformation["lastname"];?></td>
        <form class="" method="post" action = "" enctype="multipart/form-data">
          
          <td>
            <input type="text" name="itemId" value="<?php echo $oneBooking["id"];?>" readonly style="display:none">
            <button  name="edit" type="submit" class="btn btn-success btn-sm rounded-0" data-toggle="tooltip" data-placement="top" title="Edit">EDIT</button>
          </td>

          <td>
            <input type="text" name="itemId" value="<?php echo $oneBooking["id"];?>" readonly style="display:none">
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