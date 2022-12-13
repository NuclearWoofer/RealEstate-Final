<?php

include 'API_Functions.php';

$APIAgentFetchURL = 'https://localhost:5001/api/Agent';
$APIBookingPostURL = "https://localhost:5001/api/Booking";
$APIPropertyListingPostURL = "https://localhost:5001/api/PropertyListing"; //check this is correct?
$APIClientPostURL = "https://localhost:5001/api/Client";



$GET_requestAgentData = GET_CurlAPIRequest($APIAgentFetchURL);

//POST Button request
if(isset($_POST["submitOrder"])){

  //Retreive Data from form to post
  $input_lastname = filter_input(INPUT_POST, "lastname"); //Agent last name
  $input_firstname = filter_input(INPUT_POST, "firstname"); //Agent First name
  $input_email = filter_input(INPUT_POST, "email"); // Agent Email
  $input_phone = filter_input(INPUT_POST, "phone"); //Agent Phone Number
  $input_relicence = filter_input(INPUT_POST, "relicence"); //Agent Real Estate License


  //Display to ensure form is working
  // echo "input_orderName: ". $input_orderName ."<br>";
  // echo "input_menuItem: ". $input_menuItem ."<br>";
  // echo "input_quantity: ". $input_quantity ."<br>";

  $dataToPost = [
    "lastname" => $input_lastname,
    "firstname" => $input_firstname,
    "email"=>  $input_email,
    "phone"=>  $input_phone,
    "relicence"=>  $input_relicence
  ];


  $postResults = POST_CurlAPIRequest($APIAgentPostURL, $dataToPost);
  // var_dump($postResults);

}

;?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>POST AGENT FORM </title>
    <style>
      .form-div{
        position: absolute;
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

  <div class="form-div" >

  
    <!--Title Section of Post Food Form -->
    <center>
      <h4>Post Agent Form</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6>
    </center>


    

<!--*****************************************-->
<!-- THIS FORM BELOW NEEDS TO GET REWORKED. -->
<!--*****************************************-->
    <form  class="contact-form style-2" method="post" action = "" enctype="multipart/form-data">


     <!--Enter Order Name Field -->
      <div class="form-group">
        <label for="orderName">Enter Order Name: </label><label class="error-label">  </label>
        <input type="text"  name="orderName" class="form-control formInput" id="orderName" placeholder="Order Name">
      </div>


        <!--Enter Food Item Field -->
      <div class="form-group">
        <label for="menuItem">Menu Item: </label><label class="error-label">  </label>
        <select class="form-control formInput" id="menuItem"  name="menuItem">

        <option value="" disabled selected>Select an Option below</option>

        <!-- Populate Select Tag from GET REQUEST with Food Items -->
        <?php foreach ($GET_requestFoodData as $oneFoodItem):?>

          <option value="<?=$oneFoodItem["id"];?>" > <?=$oneFoodItem["title"];?> </option>

        <?php endforeach;?>

        </select>
      </div>


      <!-- Enter numeric quantity field -->
      <div class="form-group">
        <label for="quantity">Quantity:  </label> <label class="error-label">  </label>
        
        <input type="number"   name="quantity" class="form-control formInput" id="quantity" placeholder="Quantity">
      </div>


      <!-- submit form button -->

      <button name="submitOrder" type="submit" class="btn bg-dark text-white submitOrderBtn" style="width:100%;">Submit Order</button>

    </form>
  </div>
</body>


</html>

<script>

        
  let allErrorTags = document.querySelectorAll(".error-label") //all error tags
  let allFormInput = document.querySelectorAll(".formInput") //all form inputs 
  let submitOrderBtn = document.querySelector(".submitOrderBtn") //Submit button


  //When Submit button is pressed
  submitOrderBtn.addEventListener("click", (event)=>{

    let doesFormErrorsExist = false; //clear all errors

    //Clear all errors and check all for emptyness
    allFormInput.forEach((oneInput)=>{

      let errorTagOfInput = allErrorTags[Array.from(allFormInput).indexOf(oneInput)]
      errorTagOfInput.innerHTML = ""

      if((oneInput.value.length > 0) == false){

        errorTagOfInput.innerHTML = "* This field is required"
        doesFormErrorsExist = true;
      }
    });




    //Order Name Validation
    let orderNameInput = allFormInput[0]

    if(orderNameInput.value.length > 20){

      allErrorTags[0].innerHTML = "* Cannot Exceed 20 Chars"
      doesFormErrorsExist = true;
    }



    //Quantity  Validation
    let quantityInput = allFormInput[2]

    if((parseInt(quantityInput.value) > 100) || (parseInt(quantityInput.value) <= 0)){

      let errorTagOfInput = allErrorTags[Array.from(allFormInput).indexOf(quantityInput)]
      errorTagOfInput.innerHTML = "*Must be a number between 1 - 100"

      doesFormErrorsExist = true;
    }


    //Prevent Post event if errors are found
    if(doesFormErrorsExist){

      event.preventDefault();
    }
  })

  


  

</script>