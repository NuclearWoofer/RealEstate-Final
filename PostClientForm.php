<?php
session_start(); 
include 'API_Functions.php';

$APIClientFetchURL = 'https://localhost:5001/api/Client';

$isEditing = false;

if (isset($_GET["edit"])){

  $isEditing = htmlspecialchars($_GET["edit"]);

  if($isEditing){

    $tempId = $_SESSION['tempIdClient'];
    $tempEdits = GET_ONE_CurlAPIRequest($APIClientFetchURL, $tempId);
  }
}


//POST Button request
if(isset($_POST["submitClient"])){

  //Retreive Data from form to post
  $input_lastname = filter_input(INPUT_POST, "lastname"); //Client last name
  $input_firstname = filter_input(INPUT_POST, "firstname"); //Client First name
  $input_email = filter_input(INPUT_POST, "email"); // Client Email
  $input_phone = filter_input(INPUT_POST, "phone"); //Client Phone Number

  $dataToPost = [
    "id" => $isEditing ? $tempId : 0,
    "lastname" => $input_lastname,
    "firstname" => $input_firstname,
    "email"=>  $input_email,
    "phone"=>  $input_phone
  ];

  
  if ($isEditing) {

    $postResults = PUT_CurlAPIRequest($APIClientFetchURL, $dataToPost);

  }
  else{

    $postResults = POST_CurlAPIRequest($APIClientFetchURL, $dataToPost);

  }

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
    <title>Post Client Form </title>
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
      <h4>Post Client Form</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6>
    </center>




<!--*****************************************-->
<!-- THIS FORM BELOW NEEDS TO GET REWORKED. -->
<!--*****************************************-->
    <form  class="contact-form style-2" method="post" action = "" enctype="multipart/form-data">


     <!--Enter Order Name Field -->

     <div class="form-group">
        <label for="firstname">Enter Client First Name: </label><label class="error-label">  </label>
        <input type="text"  name="firstname" class="form-control formInput" id="firstname" placeholder="Client's First Name" value="<?php echo (($isEditing)) ?  $tempEdits["firstname"] : "";?>">
      </div>

      <div class="form-group">
        <label for="lastname">Enter Client Last Name: </label><label class="error-label">  </label>
        <input type="text"  name="lastname" class="form-control formInput" id="lastname" placeholder="Client's Last Name" value="<?php echo (($isEditing)) ?  $tempEdits["lastname"] : "";?>">
      </div>

      <div class="form-group">
        <label for="phone">Enter Phone Number: </label><label class="error-label">  </label>
        <input type="text"  name="phone" class="form-control formInput" id="phone" placeholder="Client's Phone Number" value="<?php echo (($isEditing)) ?  $tempEdits["phone"] : "";?>">
      </div>

      <div class="form-group">
        <label for="email">Enter E-Mail: </label><label class="error-label">  </label>
        <input type="text"  name="email" class="form-control formInput" id="email" placeholder="Client's email" value="<?php echo (($isEditing)) ?  $tempEdits["email"] : "";?>">
      </div>

      <button name="submitClient" type="submit" class="btn bg-dark text-white submitClientbtn" style="width:100%;">Submit</button>

    </form>
  </div>
</body>


</html>

<script>

        
  let allErrorTags = document.querySelectorAll(".error-label") //all error tags
  let allFormInput = document.querySelectorAll(".formInput") //all form inputs 
  let submitClientbtn = document.querySelector(".submitClientbtn") //Submit button


  //When Submit button is pressed
  submitClientbtn.addEventListener("click", (event)=>{

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
    let ClientPhone = allFormInput[0]

    if(ClientPhone.value.length > 20){

      allErrorTags[0].innerHTML = "* Cannot Exceed 20 Chars"
      doesFormErrorsExist = true;
    }

    let inputDat1= allFormInput[1]

    if(inputDat1.value.length > 20){

      allErrorTags[1].innerHTML = "* Cannot Exceed 20 Chars"
      doesFormErrorsExist = true;
    }

    let inputData2= allFormInput[2]

    if(inputData2.value.length > 12){

      allErrorTags[2].innerHTML = "* Cannot Exceed 12 Chars"
      doesFormErrorsExist = true;
    }

    let inputData3= allFormInput[3]

    if(inputData3.value.length > 60){

      allErrorTags[3].innerHTML = "* Cannot Exceed 60 Chars"
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