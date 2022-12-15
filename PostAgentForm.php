<?php
session_start(); 
include 'API_Functions.php';


$APIAgentFetchURL = 'https://localhost:5001/api/Agent';

$isEditing = false;

if (isset($_GET["edit"])){

  $isEditing = htmlspecialchars($_GET["edit"]);

  if($isEditing){

    $tempId = $_SESSION['tempIdAgent'];

    $tempEdits = GET_ONE_CurlAPIRequest($APIAgentFetchURL, $tempId);
  }
}


if(isset($_POST["submitAgent"])){

  //Retreive Data from form to post
  $input_lastname = filter_input(INPUT_POST, "lastname"); //Agent last name
  $input_firstname = filter_input(INPUT_POST, "firstname"); //Agent First name
  $input_email = filter_input(INPUT_POST, "email"); // Agent Email
  $input_phone = filter_input(INPUT_POST, "phone"); //Agent Phone Number
  $input_relicence = filter_input(INPUT_POST, "relicence"); //Agent Real Estate License


  // exit();

  $dataToPost = [
    "id" => $isEditing ? $tempId : 0,
    "lastname" => $input_lastname,
    "firstname" => $input_firstname,
    "email"=>  $input_email,
    "phone"=>  $input_phone,
    "reLicense"=>  $input_relicence
  ];

  
  if ($isEditing) {

    $postResults = PUT_CurlAPIRequest($APIAgentFetchURL, $dataToPost);

  }
  else{

    $postResults = POST_CurlAPIRequest($APIAgentFetchURL, $dataToPost);

  }

  header("Location: GetAllAgents.php");

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
    <title>POST AGENT FORM </title>
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
      <h4>Post Agent Form</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6>
    </center>




<!--*****************************************-->
<!-- THIS FORM BELOW NEEDS TO GET REWORKED. -->
<!--*****************************************-->
    <form  class="contact-form style-2" method="post" action = "" enctype="multipart/form-data">


     <!--Enter Order Name Field -->

      <div class="form-group">
        <label for="firstname">Enter Agent First Name: </label><label class="error-label">  </label>
        <input maxlength="75" type="text"  name="firstname" class="form-control formInput" id="firstname" placeholder="Agent's First Name" value="<?php echo (($isEditing)) ?  $tempEdits["firstname"] : "";?>">
      </div>
      <div class="form-group">
        <label for="lastname">Enter Agent Last Name: </label><label class="error-label">  </label>
        <input maxlength="75" type="text"  name="lastname" class="form-control formInput" id="lastname" placeholder="Agent's Last Name" value="<?php echo (($isEditing)) ?  $tempEdits["lastname"] : "";?>">
      </div>
      <div class="form-group">
        <label for="phone">Enter Phone Number: </label><label class="error-label">  </label>
        <input type="text"  name="phone" class="form-control formInput" id="phone" placeholder="Agent's Phone Number" value="<?php echo (($isEditing)) ?  $tempEdits["phone"] : "";?>">
      </div>
      <div class="form-group">
        <label for="email">Enter E-Mail: </label><label class="error-label">  </label>
        <input maxlength="200" type="text"  name="email" class="form-control formInput" id="email" placeholder="Agent's email" value="<?php echo (($isEditing)) ?  $tempEdits["email"] : "";?>">
      </div>
      <div class="form-group">
        <label for="relicense">Enter Real Estate License Number: </label><label class="error-label">  </label>
        <input type="text"  name="relicence" class="form-control formInput" id="relicense" placeholder="Agent's Real Estate License #" value="<?php echo (($isEditing)) ?  $tempEdits["reLicense"] : "";?>">
      </div>


      <button name="submitAgent" type="submit" class="btn bg-dark text-white submitAgentbtn" style="width:100%;">Submit</button>

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
    });




    //Order Name Validation
    let inputData = allFormInput[0]

    if(inputData.value.length > 20){

      allErrorTags[0].innerHTML = "* Cannot Exceed 20 Chars"
      doesFormErrorsExist = true;
    }

    let inputData1 = allFormInput[1]

    if(inputData1.value.length > 20){

      allErrorTags[1].innerHTML = "* Cannot Exceed 20 Chars"
      doesFormErrorsExist = true;
    }


    let inputData2 = allFormInput[2]

    if(inputData2.value.length > 15){

      allErrorTags[2].innerHTML = "* Cannot Exceed 15 Chars"
      doesFormErrorsExist = true;
    }


    
    let inputData3 = allFormInput[3]

    if(inputData3.value.length > 60){

      allErrorTags[3].innerHTML = "* Cannot Exceed 60 Chars"
      doesFormErrorsExist = true;
    }

        
    let inputData4 = allFormInput[4]

    if(inputData4.value.length > 8){

      allErrorTags[4].innerHTML = "* Cannot Exceed 8 Chars"
      doesFormErrorsExist = true;
    }



    //Prevent Post event if errors are found
    if(doesFormErrorsExist){

      event.preventDefault();
    }
  })

  


  

</script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>