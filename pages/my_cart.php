<?php
  require_once '../config.php';
  authenticated();

   $productsInput=["type","name","description","price","update quantity","quantity","total price","action"];

  $productsDisplay = json_decode(file_get_contents("./cart.json"),true);
        // var_export($old_records);

   $products = json_decode(file_get_contents('../pages/cart.json'), true);


  if(isset($_POST['update-btn'])){
    $id=$_POST['id-product-cart'];
    $quantity=$_POST['quantity'];

/////////
    foreach ($products as $key => $value){
      if ($value['id'] == $id && (int)$quantity != 0) {

        $products[$key]['quantity'] = (int)$quantity;
      }
    }
    file_put_contents('../pages/cart.json', json_encode($products, JSON_PRETTY_PRINT));
      
  }

  if(isset($_POST["remove-btn"])){
    $id_pro=$_POST['id-product-remove'];
  // get array index to delete
  $arr_index = array();
  foreach ($products as $key => $product)
  {
      if ($product['id'] == $id_pro)
      {
          $arr_index[] = $key;
      }
  }
  //  var_dump($arr_index);


  foreach ($arr_index as $i)
  {
      unset($products[$i]);
  }
  // echo "<pre>";
  // var_export($products);
  // echo "</pre>";
  $products = [...$products];

  // echo "<pre>";
  // var_export($products);
  // echo "</pre>";

  file_put_contents('../pages/cart.json', json_encode($products, JSON_PRETTY_PRINT));

  }

  

$total_price=0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/fontawesome.min.css"/>
  <link rel="stylesheet" href="../css/style.css">
  <title>my_cart</title>
</head>
<body>
  <header>
    <?php 
      include_once("../common/head.php");
    ?>
  </header>
  <section class="my-cart">
      <?php if(filesize("./cart.json") == 0): ?>
          <div class="mess-available">
            <p>no products</p>
          </div>
      <?php else: ?>
        <div>
          <h1>my card</h1>
          <Table>
          <thead>
          <?php if($productsDisplay): ?>
            <tr>
              <?php foreach($productsInput as $input): ?>
                <th><?php echo $input ?></th>
                <?php endforeach; ?>
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
                  <?php $count = count($product);?>
                  <?php foreach( $product as $input): ?>
                    <?php if (--$count <= 4){
                      break;}
                    ?>
                    <td><?php echo $input ?></td>
                  <?php endforeach; ?> 
                  <td>
                    <form action="my_cart.php" method="post" >
                      <div class="quantity-field ">
                          <input type="hidden" name="id-product-cart" value="<?php echo $product['id'] ?>">
                          <input type="number" name="quantity" min= "1" class="quantity" >
                          <input type="submit"  name="update-btn" value="update" class="btn"> 
                      </div>
                    </form>
                  </td>
                  <td>
                  <?php echo $product["quantity"] ?>
                  </td>
                  <td>
                  <?php
                        echo ($product["quantity"]*$product["pprice"]) ;
                      ?>
                  </td>
                  <td>
                    <form action="my_cart.php" method="post">
                      <input type="hidden" name="id-product-remove" value="<?php echo $product['id'] ?>">
                      <input type="submit" name="remove-btn" value="remove" class="btn">
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>  
            </tr> 
            <tr class="total-price">
              <td colspan="6">total price</td>
              <td colspan="2">
                <?php foreach($productsDisplay as $product): ?>
                <?php $total_price +=$product["quantity"]*$product["pprice"];?>
                <?php endforeach; ?>  
                 <?php echo $total_price ?>
              </td>
            </tr>
           </tbody> 
        </Table>
    </div>
      <?php endif;?>
  </section>

</body>
</html>