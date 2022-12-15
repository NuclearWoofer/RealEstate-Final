<?php
session_start(); 
include 'API_Functions.php';
$APIAgentFetchURL = 'https://localhost:5001/api/Agent';
$APIPropertyListingPostURL = "https://localhost:5001/api/PropertyListing"; //check this is correct?
$GET_requestPropertyData = GET_CurlAPIRequest($APIPropertyListingPostURL);
if (isset($_POST['edit'])) {
    $itemId = filter_input(INPUT_POST, 'itemId');
    $_SESSION['tempId'] = $itemId;
    header("Location: PostPropertyListingForm.php?edit=true");
}else if (isset($_POST['delete'])){
  $itemId = filter_input(INPUT_POST, 'itemId');
  $results = DELETE_ONE_CurlAPIRequest($APIPropertyListingPostURL, $itemId);
  header("Location: GetAllProperties.php");
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
    <title>Get All Agents Page</title>
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
      <h4>Get All Property Listings</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6><br>
    </center>
    <?php foreach ($GET_requestPropertyData as $oneProperty):?> 

      <?php 

        $dirtyImage = $oneProperty["image"];
        $cleanImagePath = explode("\\RealEstateAPI\\", $dirtyImage)[1];
        $APIImage = "http://localhost/RealEstateFakeHost/".$cleanImagePath 
      ;?>

      <div class="row" style="margin:50px;">
        <div class="card col-6" >
          <img class="card-img-top" src="<?= $APIImage;?>" alt="Card image cap">
        
        </div>
        <div class="card col-6" >
          <div class="card-body center">
            <div class="text-center">
              <p class="card-title "><b><?= $oneProperty["address"];?><br> <?= $oneProperty["city"];?>, <?= $oneProperty["state"];?>  <?= $oneProperty["zip"];?></b></p><br>
            </div>
            <div class="row" > 
              <h6 class="col-6">Beds: <?= $oneProperty["beds"];?></h6>
              <h6 class="col-6">Baths: <?= $oneProperty["baths"];?></h6>
            </div>
            <div class="row" > 
              <h6 class="col-6">sqft: <?= $oneProperty["sqft"];?></h6>
              <h6 class="col-6">Price: <?= $oneProperty["price"];?></h6>
            </div>
            <br>
            <div class="row" >
              <?php
                $datetime = new DateTime($oneProperty["listingDate"]);
              ;?>
              <h6 class="col-12">Listing Date:<br> <?= $datetime->format('l  F jS Y');?></h6>
            </div>

            <br>

            <div class="row" > 
              <h6 class="col-12">Type: <?= $oneProperty["type"];?></h6>
            </div>

            <br>

            <div class="row" > 
              <?php $oneAgentData = GET_ONE_CurlAPIRequest($APIAgentFetchURL, $oneProperty["agent_ID"]) ;?>
              <h6 class="col-12">Agent: <?= $oneAgentData["firstname"] ;?> <?= $oneAgentData["lastname"] ;?></h6>
            </div>

            <br>

            <div class="row" > 
              <p class="col-12">Description: <?= $oneProperty["description"];?></p>
            </div>

            <form class="" method="post" action = "" enctype="multipart/form-data">

              <input type="text" name="itemId" value="<?php echo $oneProperty["id"];?>" readonly style="display:none">

              <div class="row">

                <div class="col-6">
                  <button  name="edit" type="submit" class="btn btn-success btn-sm rounded-0" data-toggle="tooltip" data-placement="top" title="Edit">EDIT</button>
                </div>

                <div class="col-6">
                  <button  name="delete" type="submit" class="btn btn-danger btn-sm rounded-0"data-toggle="tooltip" data-placement="top" title="Delete">DELETE</button>
                </div>

              </div>
            </form>

          </div>
        </div>
      </div>
    <?php endforeach;?>
  </div>
</body>


</html>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js">