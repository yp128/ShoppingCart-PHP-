<?php
    session_start();
    
    require 'mysqli_connect.php';

    $userNameErr=$userAddressErr=$userContactErr=$userPayErr="";
    $userName=$userAddress=$userContact=$userPay="";
    if(isset($_POST['checkout'])){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            
            $userName=$_POST['name'];
            $userAddress=$_POST['address'];
            $userContact=$_POST['contact'];
            $userPay=$_POST['payment'];
           
          

            if($userName=''|| empty($userName)){

                $userNameErr="*This filed is mendatory";
    
            }else if(!preg_match("/^[A-Za-z ]+$/", $_POST['name'])){
    
                $userNameErr="*Please enter appropriate name";
    
            }else if($userAddress='' || empty($userAddress)){
                
                $userAddressErr="*This filed is mendatory";
    
            }else if($userContact='' || empty($userContact)){
                
                $userContactErr="*This filed is mendatory";
    
            }else if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}/",$_POST['contact'])){
                $userContactErr="*Please enter appropriate Number";
            }else if(empty($_POST['payment'])){
                $userPayErr="*This filed is mendatory";
            }else{
                $userName=filter_var($_POST['name'], FILTER_SANITIZE_STRING);
                $userAddress=filter_var($_POST['address'], FILTER_SANITIZE_STRING);
                $userContact=filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
                $userPay=$_POST['payment'];
    
              
                $today=date("Y/m/d");
                $setCustID=$setOrdID="";
//-- ----------------------------------------------------------------------------------------------------------------------
                $custId="select custID from customers where custName='".$userName."' and custAddress='".$userAddress."'";
                $resCustID=mysqli_query($dbc,$custId);
                if(mysqli_num_rows($resCustID)==0){
                    $custInsert="insert into customers values (null,'".$userName."','".$userAddress."','".$userContact."')";
                    mysqli_query($dbc,$custInsert);
                    $setCustID=mysqli_insert_id($dbc);
                    echo $setCustID;
                }else{
                    while($r=mysqli_fetch_array($resCustID)){
                        $setCustID=$r['custID'];
                    }
                    echo $setCustID;
                }
//-------------------------------------------------------------------------------------------------------------------------
                if(isset($setCustID)){
                    $ordInsert="insert into orders values(null,'".$setCustID."','".$userPay."','".$today."')";
                    mysqli_query($dbc,$ordInsert);
                    $setOrdID=mysqli_insert_id($dbc);
                    echo $setOrdID;
                }
                if(isset($setOrdID)){


                    $getItem="select * from products";
                    $resultProducts=mysqli_query($dbc,$getItem);

                    $itemsInCart=array_column($_SESSION['cart'],'product');

                    while($rProd=mysqli_fetch_array($resultProducts)){
                        foreach($itemsInCart as $item){
                            if($rProd['prodID']==$item){
                                $id=$setOrdID;
                                $prod=$rProd['prodID'];
                                $prodprice=$rProd['prodPrice'];
                                $prodquantity=$_SESSION['new'][$item][$rProd['prodID']];
                                $total=$prodprice*$prodquantity;
                                
                                $ordDetailInsert="insert into orderDetails values('".$id."','".$prod."','".$prodprice."','".$prodquantity."','".$total."')";
                                mysqli_query($dbc,$ordDetailInsert);

                                $newQuantity=$rProd['prodQuantity']-$prodquantity;

                                $updateProd="update products set prodQuantity=".$newQuantity." where prodID=".$rProd['prodID']."";
                                mysqli_query($dbc,$updateProd);                                    
                            }
                        }
                    }
                    echo "success";
                }
//-------------------------------------------------------------------------------------------------------------------------
                echo "
                <script>alert('Thank You \n Math.floor(Math.random() * 10000) is your') </script>";
                session_destroy();
                //header('Location: index.php');
    
        }
    }
}
   

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar nav-dark bg-dark">
            <div class="container">
                <p class="navbar-brand text-light ">Hello</p>
                <div class="nav">
                    <a class="nav-item nav-link" href='cart.php'>Cart</a>
                    <a class="nav-item nav-link" href='display.php'>Store</a>
                </div>
            </div>
        </nav>
        <div class="container mt-3">
            <div class="row">
            <div class="col-4 mx-auto border border-success rounded p-3">
                    <form class="form-container" action="checkout.php" method="post" novalidate>
                        <div class="form-group">
                            <label for="name" class="form-control-label m-0">Name:</label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Enter Name:" required>
                            <div class="text-danger ml-2"><?php echo $userNameErr;?></div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-control-group m-0">Address:</label>
                            <input type="text" class="form-control form-control-sm" id="address" name="address" placeholder="Enter Address:" required>
                            <div class="text-danger ml-2"><?php echo $userAddressErr;?></div>
                        </div>
                        <div class="form-group">
                            <label for="contact" class="form-control-group m-0">Conatct No.:</label>
                            <input type="text" class="form-control form-control-sm " id="contact" name="contact" placeholder="Enter Contact:" required>
                            <div class="text-danger ml-2"><?php echo$userContactErr;?></div>
                        </div>
                        <div class="form-group">
                            <h5 class="my-1" >Choose payment method: </h3>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" id="payment1" value="creditCard" checked=true>
                                <label class="form-check-label" for="payment1">Credit Card</label>
                            </div>
                            <div class="form-check ">
                                <input class="form-check-input" type="radio" name="payment" id="payment2" value="debitCard">
                                <label class="form-check-label" for="payment2">Debit Card</label>
                            </div>
                            <div class="form-check ">
                                <input class="form-check-input" type="radio" name="payment" id="payment3" value="COD">
                                <label class="form-check-label" for="payment3">COD (Cash On Delivery)</label>
                            </div>
                            <div class="text-danger ml-2"><?php echo $userPayErr;?></div>
                        </div>
                        <div class="form-group text-center pt-2">
                            <button type="submit" class="btn btn-primary btn-block" name="checkout">Place Order</button>
                        </div>
                        <label class="from-control-label">*All fields are Mendatory</label>
                    </form>
                </div> 
                <div class="col-6">
                    <div class="card border-success mb-3">
                        <div class="card-header bg-transparent border-success">
                            <div class="row">
                                <div class="col-4">
                                    Item Name
                                </div>
                                <div class="col-4">
                                    Quantity
                                </div>
                                <div class="col-4">
                                    Price
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-success">
                        <?php
                        $sum=$count=0;
                        if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
                            $items=array_column($_SESSION['cart'],'product');
                            $query="select * from products";
                            $result_total=mysqli_query($dbc,$query);
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
                </div>
            </div>
        </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>