<?php
session_start(); 
include 'API_Functions.php';



$APIAgentFetchURL = 'https://localhost:5001/api/Agent';
$APIBookingPostURL = "https://localhost:5001/api/Booking";
$APIPropertyListingPostURL = "https://localhost:5001/api/PropertyListing"; //check this is correct?
$APIClientPostURL = "https://localhost:5001/api/Client";


$isEditing = false;

if (isset($_GET["edit"])){

  $isEditing = htmlspecialchars($_GET["edit"]);

  if($isEditing){

    $tempId = $_SESSION['tempIdBooking'];
    $tempEdits = GET_ONE_CurlAPIRequest($APIBookingPostURL, $tempId);
    $onePropertyToEdit = GET_ONE_CurlAPIRequest($APIPropertyListingPostURL, $tempEdits["propertyListing_ID"]);
    $oneClientToEdit = GET_ONE_CurlAPIRequest($APIClientPostURL, $tempEdits["client_ID"]);
  }
}

$GET_requestAgentData = GET_CurlAPIRequest($APIAgentFetchURL);
$GET_requestPropertyData = GET_CurlAPIRequest($APIPropertyListingPostURL);
$GET_requestClientData = GET_CurlAPIRequest($APIClientPostURL);

//POST Button request
if(isset($_POST["submitBooking"])){

  //Retreive Data from form to post
  $agent_ID = filter_input(INPUT_POST, "agent_ID"); // last name
  $property_ID = filter_input(INPUT_POST, "property_ID"); // First name
  $datetime = filter_input(INPUT_POST, "datetime"); //  Email
  $client_ID = filter_input(INPUT_POST, "client_ID"); // Phone Number


  $dataToPost = [
    "id" => $isEditing ? $tempId : 0,
    "active" => True,
    "propertyListing_ID" => $property_ID,
    "booking_DateTime"=>  $datetime,
    "client_ID"=>  $client_ID
  ];

  if ($isEditing) {
    $postResults = PUT_CurlAPIRequest($APIBookingPostURL, $dataToPost);

  }
  else{
    $postResults = POST_CurlAPIRequest($APIBookingPostURL, $dataToPost);
  }

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
    <title>Post Booking Form</title>
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
      <h4>Post Booking Form</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6>
    </center>




<!--*****************************************-->
<!-- THIS FORM BELOW NEEDS TO GET REWORKED. -->
<!--*****************************************-->
    <form  class="contact-form style-2" method="post" action = "" enctype="multipart/form-data">


     <!--Enter Order Name Field -->
    
      <div class="form-group">
        <label for="">Property : </label><label class="error-label">  </label>
         
        <select class="form-control formInput propertyDropDown" name="property_ID" id="">
           
            <?php echo (($isEditing)) ?  " <option  value=". $tempEdits["propertyListing_ID"].">".$onePropertyToEdit["address"].','. $onePropertyToEdit["city"].','. $onePropertyToEdit["state"].','. $onePropertyToEdit["zip"].'|'.  $onePropertyToEdit["price"]."</option>"  : " <option selected disabled value='property'>Select a property</option>";?>
           
            <?php foreach($GET_requestPropertyData as $oneProperty): ?>

              <option  value="<?= $oneProperty["id"] ;?>"> <?= $oneProperty["address"];?>, <?= $oneProperty["city"];?>, <?= $oneProperty["state"];?>,  <?= $oneProperty["zip"];?> |  $<?= $oneProperty["price"];?></option>
            <?php endforeach; ?>

          </select>
      </div>

      <div class="form-group">
        <label for="datetime">Date Time </label><label class="error-label">  </label>
        <input  type="datetime-local"  name="datetime" class="form-control formInput" id="datetime" min="2022-12-14T00:00" value="<?php echo (($isEditing)) ?  $tempEdits["booking_DateTime"] : "";?>">
      </div>

      <div class="form-group">
        <label for="">Client : </label><label class="error-label">  </label>
         
        <select class="form-control formInput clientDropDown" name="client_ID" id="">
           
            <?php echo (($isEditing)) ?  " <option  value=".$tempEdits["client_ID"].">".$oneClientToEdit["firstname"]." " .$oneClientToEdit["lastname"]."</option>"  : " <option selected disabled value='client'>Select a client</option>";?>
           
            <?php foreach($GET_requestClientData as $oneClient): ?>

              <option  value="<?= $oneClient["id"] ;?>"> <?= $oneClient["firstname"];?> <?= $oneClient["lastname"];?></option>
            <?php endforeach; ?>

          </select>
      </div>

      <button name="submitBooking" type="submit" class="btn bg-dark text-white submitAgentbtn" style="width:100%;">Submit</button>

    </form>
  </div>
</body>


</html>

<script>

        
  let allErrorTags = document.querySelectorAll(".error-label") //all error tags
  let allFormInput = document.querySelectorAll(".formInput") //all form inputs 
  let submitAgentbtn = document.querySelector(".submitAgentbtn") //Submit button


  //When Submit button is pressed
  submitAgentbtn.addEventListener("click", (event)=>{

    let doesFormErrorsExist = false; //clear all errors

    //Clear all errors and check all for emptyness
    allFormInput.forEach((oneInput)=>{

      let errorTagOfInput = allErrorTags[Array.from(allFormInput).indexOf(oneInput)]
      errorTagOfInput.innerHTML = ""

      if((oneInput.value.length > 0) == false){

        errorTagOfInput.innerHTML = "* This field is required"
        doesFormErrorsExist = true;
      }


      if( (Array.from(allFormInput).indexOf(oneInput) == 0) && ((oneInput.value == "property" ) ) ){

      errorTagOfInput.innerHTML = "* This field is required"
      doesFormErrorsExist = true;
      }

      if( (Array.from(allFormInput).indexOf(oneInput) == 2) && ((oneInput.value == "client" ) ) ){

      errorTagOfInput.innerHTML = "* This field is required"
      doesFormErrorsExist = true;
      }
    });




    //Order Name Validation
    let agentPhone = allFormInput[0]

    if(agentPhone.value.length > 20){

      allErrorTags[0].innerHTML = "* Cannot Exceed 20 Chars"
      doesFormErrorsExist = true;
    }


    //Prevent Post event if errors are found
    if(doesFormErrorsExist){

      event.preventDefault();
    }
  })

  
  

  <?php if ($isEditing) :?>

  let propertyDropDown = document.querySelector(".propertyDropDown")

  for (var i=0; i<propertyDropDown.length; i++) {


  if (propertyDropDown.options[0].value != 'property'){


  console.log(propertyDropDown.options[0])
  if(propertyDropDown.options[0].value == propertyDropDown.options[i].value){

    let valueToSelect = propertyDropDown.options[i].value
    propertyDropDown.options[0].remove();
    
    propertyDropDown.value = valueToSelect;

    break;
  }
  }
  }

  let clientDropDown = document.querySelector(".clientDropDown")

  for (var i=0; i<clientDropDown.length; i++) {


  if (clientDropDown.options[0].value != 'client'){


  console.log(clientDropDown.options[0])
  if(clientDropDown.options[0].value == clientDropDown.options[i].value){

    let valueToSelect = clientDropDown.options[i].value
    clientDropDown.options[0].remove();
    
    clientDropDown.value = valueToSelect;

    break;
  }
  }
  }

<?php endif;?>

</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>