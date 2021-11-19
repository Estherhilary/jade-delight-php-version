<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="background-color:#E6E6FA;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>

<title>Jade Delight</title>
</head>

<body>

<script language="javascript">
function MenuItem(name, cost)
{
	this.name = name;
	this.cost=cost;
}

menuItems = new Array(
	new MenuItem("Chicken Chop Suey", 4.5),
	new MenuItem("Sweet and Sour Pork", 6.25),
	new MenuItem("Shrimp Lo Mein", 5.25),
	new MenuItem("Moo Shi Chicken", 6.5),
	new MenuItem("Fried Rice", 2.35)
);
</script>

<div style="background-color: #EE82EE; width:14%; text-align:center; border-radius:10px;"><h1>Jade Delight</h1></div>
<div class="theForm" style="background-color:#EE82EE; width:40%; padding:8px; border-radius:10px;">
<form name="myForm" action="newpage.php" method="get" onsubmit="return validatestuff()" target = "_blank">

<p>First Name*: <input type="text"  name='fname' /></p>
<p>Last Name*:  <input type="text"  name='lname' /></p>
<p>Street: <input type="text"  name='street' /></p>
<p>City: <input type="text"  name='city' /></p>
<p>Phone*: <input type="text"  name='phone' /></p>
<p>Email adress: <input type="text"  name='email' /></p>
<p> 
	<input type="radio"  name="p_or_d" value = "pickup" checked="checked"/>Pickup  
	<input type="radio"  name='p_or_d' value = 'delivery'/>
	Delivery
</p>
<table border="0" cellpadding="3">
  <tr>
    <th>Select Item</th>
    <th>Item Name</th>
    <th>Cost Each</th>
    <th>Total Cost</th>
  </tr>
  
<?php
function makeSelect($name, $minRange, $maxRange)
{
	echo "";
	echo "<select name='".$name."' size='1'>";
	for ($j=$minRange; $j<=$maxRange; $j++)
	   echo "<option>". $j. "</option>";
	echo "</select>"; 
}

$server = "sql113.epizy.com";
$userid = "epiz_29722738";
$pw = "jEukevvvXnI01O";
$db= "epiz_29722738_jade_delight_menu";

//create connection
$connection = new mysqli($server, $userid, $pw);

//check connection
if($connection->connect_error){
    die("Connection failed: ".$connection->connect_error);
}
echo("Connected successfully")."<br>"."<br>";

//select db
$connection->select_db($db);

 //run a query
 $sql = "SELECT * FROM menu_items";
 $result = $connection->query($sql);

 //get results
 if($result->num_rows > 0){
     $i = 0;
    while($row = $result->fetch_assoc()){
        makeSelect("quan".$i++,0,10);
        echo $row["itemName"]." ".$row["itemPrice"]."<br>";

     }
 }
 else{
     echo("No results");
 }

 //close the connection
$connection->close();

mail($email,"Thank you for your order!",$msg);


?>
</table>


<p>Subtotal:
   $<input type="text"  name='subtotal' id="subtotal" />
</p>
<p>Mass tax 6.25%:
  $ <input type="text"  name='tax' id="tax" />
</p>
<p>Total: $ <input type="text"  name='total' id="total" />
</p>

<input type = "submit" value = "Submit Order"/>

</form>
</div>

<script language="javascript">
  function showOrHideStreetAndCity(value){
   
   if(value == "pickup") {

     $(`input[name="street"]`).parent().hide();
     $(`input[name="city"]`).parent().hide();
   }
   else{
     
     $(`input[name="street"]`).parent().show();
     $(`input[name="city"]`).parent().show();
    }
  }
  

  showOrHideStreetAndCity($(`input[name="p_or_d"]`).val());

  $(`input[name="p_or_d"]`).on('change', function(event){
    showOrHideStreetAndCity(event.target.value);
  })

  for (let i=0; i < menuItems.length; i++) {
    const quantitySelect = $(`select`)[i];

    $(quantitySelect).on("change", function(event){
      const newSelectValue = Number(event.target.value) || 0;
      const costInput = $(`input[name="cost"]`)[i];

      $(costInput).val((newSelectValue * menuItems[i].cost).toFixed(2));
    })

          
  }

  $('select').on("change", function (){
      var total = 0;

      for (let i=0; i < menuItems.length; i++) {
        const quantitySelect = $(`select`)[i];
        const quantity = Number($(quantitySelect).val()) || 0;

        const itemTotal = quantity * menuItems[i].cost;

        total = total + itemTotal;
      }
      
      const tax = total * 0.0625;

      const subtotalInput = $(`#subtotal`);
      $(subtotalInput).val(total.toFixed(2));

      const taxInput = $(`#tax`);
      $(taxInput).val(tax.toFixed(2));
      
      const overall = total + tax;

      const totalInput = $(`#total`);
      $(totalInput).val(overall.toFixed(2));
    });
  
  function validatestuff() {
    let fname = $(`input[name="fname"]`).val();
    let lname = $(`input[name="lname"]`).val();
    let phone = $(`input[name="phone"]`).val();
    let city = $(`input[name="city"]`).val();
    let street = $(`input[name="street"]`).val();
            
    if(fname == "" || lname == ""){
        alert("Both First and Last Name must be filled out");
        return false;
    }
    //if phone z is less than 10 chars
    if(phone.length < 10 || isNaN(phone)){
        alert("Phone number must be valid");
        return false;
    }

    if($(`input[name="street"]`).val() == "" && $(`input[name="city"]`).val() == ""){
      alert("Both city and street must be filled out");
      return false;

    if($(`input[name="total"]`).val() == "") {
      alert("At least one item must be ordered");
      return false;
    }

  }
  
   
      const p_or_d = $(`input[name="p_or_d"]`).val();
      var time = new Date();
      var minutes = time.getMinutes();
      var hours = time.getHours();
      var timeNow = hours +" : "+ minutes;
    

    if(p_or_d == "pickup"){
      
      if(minutes == 45){
        var pickupTime = (hours+1) + " : 00";
      }
      else if(minutes < 45){
        var deliveryTime = hours + " : " + (minutes+15);
      }
      
      else {
        newMins = minutes + 15 - 60; 
        newHr = hours + 1;
        var deliveryTime = hours + " : " + newMins;
      }
      return deliveryTime;
    }
    
    else if(p_or_d == "delivery"){
      if(minutes == 30){
        var pickupTime = (hours+1) + " : 00";
      }
      else if(minutes < 30){
        var pickupTime = hours + " : " + (minutes+30);
      }
      
      else{
        newMins = minutes + 30 - 60; 
        newHr = hours + 1;
        var pickupTime = hours + " : " + newMins;
      }
      return pickupTime;
    }
  }
</script>
</body>
</html>