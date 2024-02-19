<ul>
    <li><a href="../pages/home.php">home</a></li>
    <!--  create-->
    <li>
      <?php
      if($_SESSION["username"] == "admin"){
        echo "<a href='../pages/create.php'>create</a>";
        }
      ?>
      </li> 
    <li><a href="../pages/logout.php">logout</a></li>
    <?php 
          $products = json_decode(file_get_contents("../pages/cart.json"),true);
          if(filesize("../pages/cart.json") == 0){
            $counttt = 0;
          }else{
            $counttt=count($products);
          }

    //  var_export($counttt);
    ?>
    <li><a href="../pages/my_cart.php"><i class="fa-solid fa-cart-shopping "></i><span><sup><?php echo $counttt ?></sup></span></a></li>
</ul>
