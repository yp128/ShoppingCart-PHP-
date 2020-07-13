<?php
    session_start();
    
    require "mysqli_connect.php";

    $getItem="select * from products";
    $result=mysqli_query($dbc,$getItem);
    $result_total=mysqli_query($dbc,$getItem);
    

    if(isset($_POST['remove'])){
        //print_r($_GET['id']);
        if($_GET['action']=='remove'){
            foreach($_SESSION['cart'] as $key=>$value){
                if($value['product']==$_GET['id']){
                    unset($_SESSION['cart'][$key]);
                    echo "<script>alert('product removed')</script>";
                    echo "<script>window.location='cart.php'</script>";
                }
            }
        }
    }
    
    $items=array_column($_SESSION['cart'],'product');
    foreach($items as $item){
        if(isset($_SESSION['new'][$item])){
        
            if(isset($_POST['add']) && isset($_POST['quantity'][$_GET['id']])){ 
                if($item==$_GET['id']){
                    $amount=array(
                        "$item"=>$_POST['quantity'][$_GET['id']]+1
                    );
                    $_SESSION['new'][$item]=$amount;
                } 
            }
            else if(isset($_POST['reduce']) && isset($_POST['quantity'][$_GET['id']])){ 
                    if($item==$_GET['id']){
                        if($_POST['quantity'][$_GET['id']]>1){
                            $amount=array(
                                "$item"=>$_POST['quantity'][$_GET['id']]-1
                            );
                            $_SESSION['new'][$item]=$amount;
                        }
                        else{
                            echo "<script>document.getElementById('reduce').disabled=true;</script>";
                        }
                    }
                }
        }
        else{
            $amount=array(
                "$item"=>1
            );
            $_SESSION['new'][$item]=$amount;       
        }        
    }
   
?>

<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <title>User Cart</title>
    </head>
    <body>
    <nav class="navbar nav-dark bg-dark">
        <div class="container">
            <p class="navbar-brand text-light ">Hello</p>
            <div class="nav">
                <a class="nav-item nav-link" href='index.php'>Home</a>
                <a class="nav-item nav-link" href='display.php'>Store</a>
            </div>
        </div>
    </nav>



    <div class="container mt-2">
        <div class="row">
            
        <!--Product card start-->
            <div class="col-7 float-left">
                <?php
                if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
                    $items=array_column($_SESSION['cart'],'product');
                    while($r=mysqli_fetch_array($result)){
                        foreach($items as $item){
                            if($r['prodID']==$item){
                ?>
                <div class="row justify-content-center mb-2">
                    <div class="card bg-light h-100 aligh-items-stretch">
                        <div class="row no-gutters">
                            <div class="col-4 ">
                                <img src="<?php echo $r['prodImage'];?>" class="card-img" >
                            </div>
                            <div class="col-8 ">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <div class="row">
                                            <div class="col-7 float-left">
                                            <?php echo $r['prodName'];?>
                                            </div>
                                            <div class="col-5 float-right text-right">
                                            <?php echo $r['prodPrice'];?>
                                            </div>
                                        </div>
                                    </h5>
                                    <p class="card-text text-truncate"><?php echo $r['prodDiscription'];?></p>
                                    <div class="row justify-content-between">
                                        <form action="cart.php?action=remove&id=<?php echo $r['prodID'];?>" method="post">
                                            <div class="col-4">
                                                <button type="submit" name="remove"  class="btn-sm btn-primary">Remove</button>
                                            </div>
                                        </form>
                                        <div class="col-8 mx-auto">
                                            <form class="form-inline"  action="cart.php?action=add&id=<?php echo $r['prodID'];?>" method="post">
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <button type="submit" class="btn btn-sm btn-prepend btn-secondary" id="reduce" name="reduce">-</button>
                                                    </div>
                                                    <input typr="number" name="quantity[<?php echo $r['prodID'];?>]" class="form-control col-4 text-center" value="<?php echo $_SESSION['new'][$item][$r['prodID']];?>">
                                                    <div class="input-group-prepend">
                                                        <button type="submit" class="btn btn-sm btn-append btn-secondary" name="add">+</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                            }
                        }
                    }
                }else{
                    echo "cart is empty!!!";
                }
                ?>
            </div>
        <!--Product card ends-->
        
        
        <!--Product total start-->
            <div class="col-5 float-right justify-content-center">
                <div class="card border-success mb-3">
                    <div class="card-header bg-transparent border-success">
                        <div class="row">
                            <div class="col-6">
                                Item Name
                            </div>
                            <div class="col-6">
                                Price
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-success">
                        <?php
                        $sum=$count=0;
                        if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
                            $items=array_column($_SESSION['cart'],'product');
                            
                            while($r=mysqli_fetch_array($result_total)){
                                foreach($items as $item){
                                    if($r['prodID']==$item){
                            ?>

                            <p class="card-text m-0">
                                    <div class="row pb-1">
                                        <div class="col-4">
                                            <?php echo $r['prodName'];?>
                                        </div>
                                        <div class="col-4">
                                        <?php echo $_SESSION['new'][$item][$r['prodID']];?>
                                        </div>
                                        <div class="col-4">
                                        <?php echo $r['prodPrice']*$_SESSION['new'][$item][$r['prodID']];?>
                                        </div>
                                    </div>
                                </p>

                            <?php
                            $count=$count + $_SESSION['new'][$item][$r['prodID']];
                            $sum=$sum + ($r['prodPrice']*$_SESSION['new'][$item][$r['prodID']]);
                                    }
                                }
                            }
                        }else{
                            echo "cart is empty!!!";
                        }
                        ?>
                    </div>
                    <div class="card-footer bg-transparent border-success">
                        <div class="row">
                            <div class="col-4">
                                Total:
                            </div>
                            <div class="col-4">
                                <?php echo $count;?>
                            </div>
                            <div class="col-4">
                                <?php echo "$".$sum;?>
                            </div>
                        </div>
                    </div>
                    
                    </div>
                    <form action="checkout.php" method="post" class="text-center">
                        <button class="btn btn-primary" type="submit">Checkout</button>
                    </form> 
                </div>
            </div>
            
            <!--Product card ends-->
        </div>
    </div>
        



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>
