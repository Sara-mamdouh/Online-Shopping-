<?php 
  require_once '../config.php';
  authenticated();

// session_start();
// if(!isset($_SESSION["logedin"])){
//   header("location:../login.php");
// }

// if(!isset("add")){
//   header("location:login.php");
// }

$idProducts=array();
if(isset($_GET['add-btn'])){
  $id=$_GET['id-product'];
  $file = fopen("../products.json" , "r+");
      $products = json_decode(file_get_contents("../products.json"),true);
      // var_export($products);
    $productsCart = array_filter($products, function($product)use($id){
      return $product["id"] == $id ;
    });
    // echo "<pre>";
    // var_export($productsCart);
  fclose($file);
  $productInCart = [...$productsCart];
    if(filesize("cart.json") == 0){
        $data_to_save =$productInCart;
      //  print_r($data_to_save);
    }else{
      // echo "is not empty";
       $old_records = json_decode(file_get_contents("cart.json"),true);
        $checkCart = array_filter($old_records, function($product)use($productInCart){
          return $product["id"] == $productInCart[0]["id"];
        });
        if(!$checkCart){
          array_push($old_records,...$productsCart);
          $data_to_save = $old_records;
        }else{
          $data_to_save = $old_records;
        } 
    }
    $encoded_data = json_encode($data_to_save, JSON_PRETTY_PRINT);
    file_put_contents("cart.json", $encoded_data);
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
  <?php 
      $productsInput=["type","name","description","price","sold","stock","available"];
      // $products=[
      //   ["type" =>"cars" , "name"=>"TEPO" ,"stock"=>10,"sold"=>1000,"price"=>2000,"available"=>"NO"],
      //   ["type" =>"cars" , "name"=>"BMW" ,"stock"=>50,"sold"=>2,"price"=>45000,"available"=>"YES"],
      //   ["type" =>"cars" , "name"=>"volvo" ,"stock"=>12,"sold"=>20,"price"=>25000,"available"=>"YES"],
      //   ["type" =>"phone" , "name"=>"iphone" ,"stock"=>3,"sold"=>123,"price"=>1289,"available"=>"NO"],
      //   ["type" =>"phone" , "name"=>"samsung" ,"stock"=>39,"sold"=>234,"price"=>11879,"available"=>"YES"],
      //   ["type" =>"cars" , "name"=>"BWG" ,"stock"=>8,"sold"=>29,"price"=>78000,"available"=>"NO"],
      // ];
      $file = fopen("../products.json" , "r+");
      $products = json_decode(file_get_contents("../products.json"),true);
      // var_export($old_records);
      fclose($file);

      function productInfo($product){
        if(!$_POST["search"])
        {
          if(!$_POST["lprice"] && !$_POST["hprice"])
          {
            return $product;

          }elseif($_POST["hprice"]&&$_POST["lprice"])
          {
            if( $product["pprice"]< (int)$_POST["hprice"] && $product["pprice"] > (int)$_POST["lprice"]){
            return $product;
            }
          }elseif($_POST["hprice"])
          {     
            if($product["pprice"]< (int)$_POST["hprice"]){
            return $product;
            }
          }elseif($_POST["lprice"]){
            if($product["pprice"] > (int)$_POST["lprice"]){
              return $product;
            }
          }
        }else{
          if(!$_POST["lprice"] && !$_POST["hprice"])
          { 
            if($product["ptype"] == $_POST["search"]){
            return $product;
            }
          }elseif($_POST["lprice"]&& !$_POST["hprice"]){
            if($product["ptype"] == $_POST["search"] && $product["pprice"] > (int)$_POST["lprice"]){
              return $product;
            }
          }elseif($_POST["hprice"] &&!$_POST["lprice"]){
            if($product["ptype"] == $_POST["search"] && $product["pprice"] < (int)$_POST["hprice"]){
              return $product;
            }
          }elseif($_POST["hprice"] && $_POST["lprice"]){
            if($product["pprice"] > (int)$_POST["lprice"] && $product["pprice"] < (int)$_POST["hprice"] && $product["ptype"] == $_POST["search"]){
              return $product;
            }
          }
        }        
      };
      if(isset($_POST["search-btn"])&&!filesize("../products.json") == 0){
        $productsFilter = array_filter($products,"productInfo");
        $productsDisplay =[...$productsFilter];
      }else{
        $productsDisplay =$products;
        // var_export($productsDisplay);

      }
   

      ?>
  <main>
    <div class="main-search">
      <div >
        <form action="home.php" method="post" class="search-form" >
          <div class="input-field"> 
            <label for="search">search :</label>
            <input type="text" id="search" name="search" >
          </div>
          <div class="input-field"> 
            <label for="hprice">highest price:</label>
            <input type="text" id="hprice" name="hprice" >
          </div>
          <div class="input-field"> 
            <label for="lprice">lowest price:</label>
            <input type="text" id="lprice" name="lprice" >
          </div>     
          <div class="btn-submit">       
            <input type="submit" value="search" name="search-btn">
        </div>   
        </form>
      </div>
      <!-- (((((((table))))))) -->
      <?php if(filesize("../products.json") == 0): ?>
            <div class="mess-available">
            <p>no products available</p>
          </div>
      <?php else: ?>
        <div>
          <Table>
          <thead>
          <?php if($productsDisplay): ?>
            <tr>
              <?php foreach($productsInput as $input): ?>
                <th><?php echo $input ?></th>
                <?php endforeach; ?>
                <th>add to shopping cart</th>
            </tr>
           <?php else: ?>
            <div class="notav">
            <p>no products available</p>
          </div>
          <?php endif; ?>

            

          </thead>
            <tbody>
                <tr>
                  <?php foreach($productsDisplay as $product): ?>
                    <tr>
                      <?php                    
                       $count = count($product);?>

                      <?php foreach( $product as $input): ?>
                        <?php if (--$count <= 1){
                          break;}
                        ?>
                        <td><?php echo $input ?></td>
                      <?php endforeach; ?> 
                      <td>
                        <form action="./home.php" method="get">
                         <input type="submit" value="add" name="add-btn" class="btn"> 
                         <input type="hidden" name="id-product" value="<?php echo $product['id'] ?>">
                        </form>
                      </td>
                     

                    </tr>
                  <?php endforeach; ?>  
                </tr>  
              </tbody> 
        </Table>
    </div>
           <?php endif;?>
  </main>

</body>
</html>

    
