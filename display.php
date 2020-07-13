<?php

session_start();

require 'mysqli_connect.php';


$prodName=$prodPrice="";
$getItem="select * from products";
$result=mysqli_query($dbc,$getItem);


if(isset($_POST['add'])){
    if(isset($_SESSION['cart'])){
        $checkItem=array_column($_SESSION['cart'],"product");
        if(in_array($_POST['prod_id'], $checkItem)){
            echo "<script>alert('Item is already added')</script>";
            echo "<script>window.location='index.php'</script>";
        }
        else{
            $items=array(
                'product'=>$_POST['prod_id']
            );
            $count=count($_SESSION['cart']);
            $_SESSION['cart'][$count]=$items;
            print_r($_SESSION['cart']);
        }
    }else{
        $items=array(
            'product'=>$_POST['prod_id']
        );

        $_SESSION['cart'][0]=$items;
        print_r($_SESSION['cart']);
    }
    if(isset($_POST['link'])){
        echo "hello";
    }
}
$countCartItem="";
if(isset($_SESSION['cart'])){
    $countCartItem=count($_SESSION['cart']);
}else{
    $countCartItem=0;
}
?>


<html>
    <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <title>Item Display</title>
    </head>
    <body>
<!--header start-->        
    <nav class="navbar nav-dark bg-dark">
        <div class="container">
            <p class="navbar-brand text-light ">Hello</p>
            <form class="form-inline m-0" method="post" action="index.php">
                    <input type="text"  name="searchBar" class="form-control mx-2" placeholder="Search here">
                    
                    <button name="search" class="btn btn-outline-light">Go</button>
            </form>
            <div class="nav">
                <a class="nav-item nav-link" href='index.php'>Home</a>
                <a class="nav-item nav-link" href='cart.php'>Cart<span class="badge badge-secondary badge-pill ml-1" ><?php echo $countCartItem;?></span></a>
            </div>
        </div>
    </nav>
<!--header end-->        


<!--Main body start-->
    <div class="container my-3">
        <div class="row justify-content-center row-cols-1 row-cols-lg-4 row-cols-md-3 row-cols-sm-2">
            <?php
                $searchRegex="";
                if(isset($_POST['search'])){
                    
                    $searchRegex=$_POST['searchBar'];
                    $searchQue="SELECT * from products where prodName like '%".$searchRegex."%'";
                    $searchResult=mysqli_query($dbc,$searchQue);
                    
                    if(mysqli_num_rows($searchResult)>0){
                        while($r=mysqli_fetch_array($searchResult)){
                            if($r['prodQuantity']>0){
                                if($r['prodQuantity']>0){
                                 //   card($r['prodName'],$r['prodImage'],$r['prodDiscription'],$r['prodPrice'],$r['prodID']);
            ?>
                        <div class="col mb-3">
                            <form action"index.php" method="post">
                                <div class="card bg-light h-100 aligh-items-stretch">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $r['prodName'];?></h5>
                                        <img class="card-img" src="<?php echo $r['prodImage'];?>" alt="Product image">
                                        <p class="card-text my-2 text-truncate"><?php echo $r['prodDiscription'];?></p>
                                        <p class="card-text my-2"><?php echo $r['prodPrice'];?></p>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-block btn-sm btn-primary" name="add">Add To Cart</button>
                                        </div>
                                        <input type="hidden" name="prod_id" value="<?php echo $r['prodID'];?>">
                                    </div>
                                </div>
                            </form>
                        </div>

            <?php
                                }
                            }
                        }
                    }
                    else{
                        echo "sorry, we don't have that product<br>";
                       
                    }
                    
                }else{
                    while($r=mysqli_fetch_array($result)){
                        if($r['prodQuantity']>0){
                            ?>
                            <div class="col mb-3">
                                <form action"index.php" method="post">
                                    <div class="card bg-light h-100 aligh-items-stretch">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $r['prodName'];?></h5>
                                            <img class="card-img" src="<?php echo $r['prodImage'];?>" alt="Product image">
                                            <p class="card-text my-2 text-truncate"><?php echo $r['prodDiscription'];?></p>
                                            <p class="card-text my-2"><?php echo $r['prodPrice'];?></p>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-block btn-sm btn-primary" name="add">Add To Cart</button>
                                            </div>
                                            <input type="hidden" name="prod_id" value="<?php echo $r['prodID'];?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
    
                <?php
                        }
                    }
                }
            ?>
        </div>
    </div>
<!--Main body ends-->        

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>