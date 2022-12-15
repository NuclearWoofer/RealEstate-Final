<?php
session_start(); 
include 'API_Functions.php';

$APIAgentFetchURL = 'https://localhost:5001/api/Agent';
$APIPropertyListingPostURL = "https://localhost:5001/api/PropertyListing"; //check this is correct?


$isEditing = false;

if (isset($_GET["edit"])){

  $isEditing = htmlspecialchars($_GET["edit"]);

  if($isEditing){

    $tempId = $_SESSION['tempId'];

    $tempEdits = GET_ONE_CurlAPIRequest($APIPropertyListingPostURL, $tempId);

  }
}

$GET_requestAgentData = GET_CurlAPIRequest($APIAgentFetchURL);

//POST Button request
if(isset($_POST["submitPropertyListing"])){

  if ($isEditing) {

    $filename = $_FILES["file"]["name"];
    $tempname = $_FILES["file"]["tmp_name"];

    if($filename == ""){

      $tempFileLocationPathToUpload = $tempEdits["image"];

    }else{
        
      $filename = $_FILES["file"]["name"];
      $tempname = $_FILES["file"]["tmp_name"];
    
      $folder = "Resources/tempFiles/".$filename;

      // Now let's move the uploaded image into the folder: image
      if (move_uploaded_file($tempname, $folder))  {
    
        $msg = "Image uploaded successfully";
    
        $currentServerLocation = realpath(__DIR__);
    
        $tempFileLocationPathToUpload = $currentServerLocation . "\\Resources\\tempFiles\\".$filename;

      }else{
    
        $msg = "Failed to upload image";
      }
    }
    
  }
  else{

    $filename = $_FILES["file"]["name"];
    $tempname = $_FILES["file"]["tmp_name"];
  
    $folder = "Resources/tempFiles/".$filename;

    // Now let's move the uploaded image into the folder: image
    if (move_uploaded_file($tempname, $folder))  {
  
      $msg = "Image uploaded successfully";
  
      $currentServerLocation = realpath(__DIR__);
  
      $tempFileLocationPathToUpload = $currentServerLocation . "\\Resources\\tempFiles\\".$filename;
    
    }else{
  
      $msg = "Failed to upload image";
  
    }
  
  }

  
    //Retreive Data from form to post
    $address = filter_input(INPUT_POST, "address"); //Agent last name
    $city = filter_input(INPUT_POST, "city"); //Agent First name
    $state = filter_input(INPUT_POST, "state"); // Agent Email
    $zip = filter_input(INPUT_POST, "zip"); //Agent Phone Number
    $beds = filter_input(INPUT_POST, "beds"); //Agent Real Estate License
    $price = filter_input(INPUT_POST, "price"); //Agent Real Estate License
    $baths = filter_input(INPUT_POST, "baths"); //Agent Real Estate License
    $sqft = filter_input(INPUT_POST, "sqft"); //Agent Real Estate License
    $description = filter_input(INPUT_POST, "description"); //Agent Real Estate License
    $active = filter_input(INPUT_POST, "active"); //Agent Real Estate License
    $type = filter_input(INPUT_POST, "type"); //Agent Real Estate License
    $listingDate = filter_input(INPUT_POST, "listingDate"); //Agent Real Estate License
   
    $agent_ID = filter_input(INPUT_POST, "agent_ID"); //Agent Real Estate License
  

    $d=strtotime("tomorrow");
    $dataToPost = [
      "id" => $isEditing ? $tempId : 0,
      "address" => $address,
      "city" => $city,
      "state"=>  $state,
      "zip"=>  $zip,
      "beds"=>  $beds,
      "price" => $price,
      "baths" => $baths,
      "sqft"=>  $sqft,
      "description"=>  $description,
      "active"=>  True,
      "type"=>  $type,
      "listingDate"=>  "2019-01-06T17:16:40",
      "image"=>  $tempFileLocationPathToUpload,
      "agent_ID"=>  $agent_ID
    ];


  if ($isEditing) {


    $postResults = PUT_CurlAPIRequest($APIPropertyListingPostURL, $dataToPost);

    if(!($filename == "")){

      unlink($tempFileLocationPathToUpload);
      
    }else{
     
      //do nothing because no new file was added
    }

  }
  else{

    $postResults = POST_CurlAPIRequest($APIPropertyListingPostURL, $dataToPost);

    unlink($tempFileLocationPathToUpload);

  }


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
    <title>POST Property FORM </title>
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
      <h4>Post Property Form</h4>
      <h6>By Michael Lopez & Saimer Nieves</h6>
    </center>

    <form  class="contact-form style-2" method="post" action = "" enctype="multipart/form-data" style="margin:10px;">


     <!--Enter Order Name Field -->
     <div class="form-group">
        <label for="">Address : </label><label class="error-label">  </label>
        <input type="text"  name="address" class="form-control formInput" id="" placeholder="" required value="<?php echo (($isEditing)) ?  $tempEdits["address"] : "";?>">
      </div>

     <div class="row" > 

      <div class="form-group col-4">
          <label for="">City : </label><label class="error-label">  </label>
          <input type="text"  name="city" class="form-control formInput" id="" placeholder=""  required value="<?php echo (($isEditing)) ?  $tempEdits["city"] : "";?>">
        </div>

        <div class="form-group col-4">
          <label for="">State : </label><label class="error-label">  </label>
         
          <select class="form-control formInput stateDropDown" name="state" id=""  required >

            <?php echo (($isEditing)) ?  " <option   value='editState'>".$tempEdits["state"]."</option>"  : " <option selected disabled value='State'>Select a State</option>";?>
          </select>
        </div>

        <div class="form-group col-4">
          <label for="">Zip : </label><label class="error-label">  </label>
          <input type="text"  name="zip" class="form-control formInput" id="" placeholder=""  required  value="<?php echo (($isEditing)) ?  $tempEdits["zip"] : "";?>">
        </div>

     </div>

     <div class="row" > 

      <div class="form-group col-3">
          <label for="">Beds : </label><label class="error-label">  </label>
          <input type="number" min="0" name="beds" class="form-control formInput" id="" placeholder="" value="<?php echo (($isEditing)) ?  $tempEdits["beds"] : "";?>">
        </div>

        <div class="form-group col-3">
          <label for="">Baths : </label><label class="error-label">  </label>
          <input type="number"  name="baths" class="form-control formInput" id="" placeholder="" step="0.01" min="0" max="500000000" value="<?php echo (($isEditing)) ?  $tempEdits["baths"] : "";?>">
        </div>

        <div class="form-group col-3">
          <label for="">Area in Square feet: </label><label class="error-label">  </label>
          <input type="text"  name="sqft" class="form-control formInput" id="" placeholder="" step="0.01" min="0" max="500000000" value="<?php echo (($isEditing)) ?  $tempEdits["sqft"] : "";?>">
        </div>

        <div class="form-group col-3">
          <label for="">Price: </label><label class="error-label">  </label>
          <input type="number"  name="price" class="form-control formInput" id="" placeholder="$0.00" step="0.01" min="0" max="500000000" value="<?php echo (($isEditing)) ?  $tempEdits["price"] : "";?>">
        </div>

      </div>


      <div class="form-group">
        <label for="">Description : </label><label class="error-label">  </label>
        <textarea  name="description" class="form-control formInput" rows="4" cols="50"> <?php echo (($isEditing)) ?  trim($tempEdits["description"]) : "";?></textarea>
      </div>

      <div class="form-group">
        <label for="">Type : </label><label class="error-label">  </label>
         
        <select class="form-control formInput typeDropDown" name="type" id="">
           
            <?php echo (($isEditing)) ?  " <option  value=".$tempEdits["type"].">".$tempEdits["type"]."</option>"  : " <option selected disabled value='type'>Select a Type</option>";?>

          </select>
      </div>
      

      <div class="form-group">
        <label for="">Agent : </label><label class="error-label">  </label>
         
        <select class="form-control formInput agentDropDown" name="agent_ID" id="">
           
            <?php echo (($isEditing)) ?  " <option  value=".$tempEdits["agent_ID"].">".$tempEdits["agent_ID"]."</option>"  : " <option selected disabled value='agent'>Select a Agent</option>";?>
           
            <?php foreach($GET_requestAgentData as $oneAgent): ?>

              <option  value="<?= $oneAgent["id"] ;?>"> <?= $oneAgent["firstname"];?> <?= $oneAgent["lastname"];?></option>
            <?php endforeach; ?>

          </select>
      </div>



      <div class="row">
        <div class="form-group col-6" >
          <label for="">Image : </label><label class="error-label">  </label>
          <input type="file" name="file" class="form-control formInput" id="propertyImageInput" >
        
        </div>
        <div class="form-group col-6" >

          <img id="propertyImageDisplay" width="100"  src=" <?php echo (($isEditing)) ?   "http://localhost/RealEstateFakeHost/" . (explode("\\RealEstateAPI\\", $tempEdits["image"])[1])  : "";?>"/>
        
        
        </div>

      </div>


      <button name="submitPropertyListing" type="submit" class="btn bg-dark text-white submitPropertybtn" style="width:100%;">Submit</button>

    </form>
  </div>
</body>


</html>


<script src="jquery-3.4.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>

  $('#propertyImageInput').change( function(event) {
    console.log("Gere")
      $("#propertyImageDisplay").css("animation", "fadeIn 5s");
      // $("img").css("animation", "fadeIn 5s");
      $("#propertyImageDisplay").attr('src',URL.createObjectURL(event.target.files[0]))

      let propertyImageDisplayURL = document.querySelector("#propertyImageDisplayURL")
      console.log(URL.createObjectURL(event.target.files[0]))
      propertyImageDisplayURL.value = (URL.createObjectURL(event.target.files[0]))
  });
  let allErrorTags = document.querySelectorAll(".error-label") //all error tags
  let allFormInput = document.querySelectorAll(".formInput") //all form inputs 
  let submitPropertybtn = document.querySelector(".submitPropertybtn") //Submit button
<<<<<<< HEAD
=======


  console.log(allFormInput)
>>>>>>> da9427694a7417ae256eaf4823fc0ae5de00df66
  //When Submit button is pressed
  submitPropertybtn.addEventListener("click", (event)=>{
    let doesFormErrorsExist = false; //clear all errors
    allFormInput.forEach((oneInput)=>{
      console.log(Array.from(allFormInput).indexOf(oneInput))
      let errorTagOfInput = allErrorTags[Array.from(allFormInput).indexOf(oneInput)]
      errorTagOfInput.innerHTML = ""
      if( (Array.from(allFormInput).indexOf(oneInput) != 11) && ((oneInput.value.length > 0) == false) ){
        errorTagOfInput.innerHTML = "* This field is required"
        doesFormErrorsExist = true;
      }

      if( (Array.from(allFormInput).indexOf(oneInput) == 2) && ((oneInput.value == "State" ) ) ){

      errorTagOfInput.innerHTML = "* This field is required"
      doesFormErrorsExist = true;
      }

      if( (Array.from(allFormInput).indexOf(oneInput) == 9) && ((oneInput.value == "type" ) ) ){

      errorTagOfInput.innerHTML = "* This field is required"
      doesFormErrorsExist = true;
      }

      if( (Array.from(allFormInput).indexOf(oneInput) == 10) && ((oneInput.value == "agent" ) ) ){

      errorTagOfInput.innerHTML = "* This field is required"
      doesFormErrorsExist = true;
      }

      if( (Array.from(allFormInput).indexOf(oneInput) == 8) && ((oneInput.value == 0 ) ) ){

      errorTagOfInput.innerHTML = "* This field is required"
      doesFormErrorsExist = true;
      }

      <?php if (!$isEditing) :?>
        if( (Array.from(allFormInput).indexOf(oneInput) == 11) && ((oneInput.value == "" ) ) ){

        errorTagOfInput.innerHTML = "* This field is required"
        doesFormErrorsExist = true;
        }

      <?php endif;?>

    });
<<<<<<< HEAD
    let agentPhone = allFormInput[0]
    if(agentPhone.value.length > 20){
      allErrorTags[0].innerHTML = "* Cannot Exceed 20 Chars"
      doesFormErrorsExist = true;
    }
=======


    let inputData = allFormInput[0]

    if(inputData.value.length > 60){

      allErrorTags[0].innerHTML = "* Cannot Exceed 60 Chars"
      doesFormErrorsExist = true;
    }

    let inputData1 = allFormInput[1]

    if(inputData1.value.length > 30){

      allErrorTags[1].innerHTML = "* Cannot Exceed 30 Chars"
      doesFormErrorsExist = true;
    }

    let inputData3 = allFormInput[3]

if(inputData3.value.length > 5){

  allErrorTags[3].innerHTML = "* Cannot Exceed 5 Chars"
  doesFormErrorsExist = true;
}


let inputData4 = allFormInput[4]

if(inputData4.value > 100){

  allErrorTags[4].innerHTML = "* Cannot Exceed 100 Beds"
  doesFormErrorsExist = true;
}


let inputData5 = allFormInput[5]

if(inputData5.value > 100){

  allErrorTags[5].innerHTML = "* Cannot Exceed 100 Baths"
  doesFormErrorsExist = true;
}



let inputData6 = allFormInput[6]

if(inputData6.value > 50000000){

  allErrorTags[6].innerHTML = "* Cannot Exceed 50000000 SQFT"
  doesFormErrorsExist = true;
}


let inputData7 = allFormInput[7]

if(inputData7.value > 500000000){

  allErrorTags[7].innerHTML = "* Cannot Exceed 500,000,000 Dollars"
  doesFormErrorsExist = true;
}

let inputData8 = allFormInput[8]

if(inputData8.value.length > 300){

  allErrorTags[8].innerHTML = "* Cannot Exceed 300 characters "
  doesFormErrorsExist = true;
}


>>>>>>> da9427694a7417ae256eaf4823fc0ae5de00df66
    //Prevent Post event if errors are found
    if(doesFormErrorsExist){
      event.preventDefault();
    }
  })
</script>
<script>
  let stateDropDown = document.querySelector(".stateDropDown")
  let allStates = ['AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY']
  allStates.forEach((oneState) => {
    let oneOption = document.createElement('option');
    oneOption.innerHTML = oneState;
    oneOption.setAttribute("value", oneState);
    stateDropDown.appendChild(oneOption)
  })
  <?php if ($isEditing) :?>
    for (var i=0; i<stateDropDown.length; i++) {
      if (stateDropDown.options[0].value != 'state'){
        console.log(stateDropDown.options[0].text)
        if(stateDropDown.options[0].text == stateDropDown.options[i].value){
          let valueToSelect = stateDropDown.options[i].value
          stateDropDown.options[0].remove();
          stateDropDown.value = valueToSelect;
          break;
        }
      }
    }
    <?php endif;?>
  let typeDropDown = document.querySelector(".typeDropDown")
  let allPropertyTypes = ['Single-Family','Multi-Family', 'Appartments', 'Single-Appartment','Condo', 'Ranch','Cabin','Farm','Mansion']
  allPropertyTypes.forEach((oneProperty) => {
    let oneOption = document.createElement('option');
    oneOption.innerHTML = oneProperty;
    oneOption.setAttribute("value", oneProperty);
    typeDropDown.appendChild(oneOption)
  })
  <?php if ($isEditing) :?>
for (var i=0; i<typeDropDown.length; i++) {
  if (typeDropDown.options[0].value != 'type'){
    console.log(typeDropDown.options[0].text)
    if(typeDropDown.options[0].text == typeDropDown.options[i].value){
      let valueToSelect = typeDropDown.options[i].value
      typeDropDown.options[0].remove();
      typeDropDown.value = valueToSelect;
      break;
    }
  }
}
<?php endif;?>
<?php if ($isEditing) :?>
  let agentDropDown = document.querySelector(".agentDropDown")
for (var i=0; i<agentDropDown.length; i++) {
if (agentDropDown.options[0].value != 'type'){
  console.log(agentDropDown.options[0].text)
  if(agentDropDown.options[0].value == agentDropDown.options[i].value){
    let valueToSelect = agentDropDown.options[i].value
    agentDropDown.options[0].remove();
    agentDropDown.value = valueToSelect;
    break;
  }
}
}
<?php endif;?>
</script>
