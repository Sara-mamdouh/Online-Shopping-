<?php 
  require_once '../config.php';
  authenticated();

    if(isset($_POST['submit'])){
      $originalPostCount = count($_POST);
      if (count(array_filter($_POST)) === $originalPostCount){
        $Available = $_POST['pstock'] > 10? "Yes":"No";
        $new_message = array(
          "ptype" => $_POST['ptype'],
          "pname" => $_POST['pname'],
          "pdescription" => $_POST['pdescription'],
          "pprice" =>(int)$_POST['pprice'],
          "psold" => (int)$_POST['psold'],
          "pstock" => (int)$_POST['pstock'], 
          "available" =>$Available ,
          "id" =>time(),
          "quantity" =>1,

       );
       if(filesize("../products.json") == 0){
         // echo "is empty";
          $first_record = array($new_message);
          $data_to_save = $first_record;
       }else{
         // echo "is not empty";
          $old_records = json_decode(file_get_contents("../products.json"));

          array_push($old_records ,$new_message);
          $data_to_save = $old_records;
       }
       $encoded_data = json_encode($data_to_save, JSON_PRETTY_PRINT);
       file_put_contents("../products.json", $encoded_data, LOCK_EX);
      //  if(!file_put_contents("products.json", $encoded_data, LOCK_EX)){
      //     echo $error = "Error storing message, please try again";
      //  }else{
      //    echo $success =  "Message is stored successfully";
      //  }
      $dis_message ="product inserted successfuly";

      }else{
        $dis_message ="please make sure u insert your product with all details";

      }
    }
      
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/fontawesome.min.css"/>
  <link rel="stylesheet" href="../css/style.css">
  <title>Document</title>
</head>
<body>
  <header>
    <?php 
      include_once("../common/head.php");
    ?>
  </header>
  <main>
    <div class="product-form">
      <form action="create.php" method="post">
        <h1>create new product</h1>
        <div class="input-field"> 
          <label for="ptype">product type:</label>
          <input type="text" id="ptype" name="ptype" >
        </div>

        <div class="input-field"> 
          <label for="pname">product name:</label>
          <input type="text" id="pname" name="pname" >
        </div>

        
        <div class="input-field"> 
        <label for="pdescription">product description:</label>
        <input type="text" id="pdescription" name="pdescription" >
        </div>

        
        <div class="input-field">
        <label for="pprice">product price:</label>
        <input type="text" id="pprice" name="pprice" >
        </div>

        
        <div class="input-field"> 
        <label for="psold">product sold:</label>
        <input type="text" id="psold" name="psold" >
        </div>

       
        <div class="input-field"> 
        <label for="pstock">product stock:</label>
        <input type="text" id="pstock" name="pstock" >
        </div>
        <div class="btn-submit">       
            <input type="submit" value="create" name="submit">
        </div>
        <?php
        if(isset($dis_message)){
          echo "<div class='message'>
             <p>{$dis_message}</p>
             </div>";
        }
        ?>
        
      </form>
    </div>
    
   
  </main>

</body>
</html>