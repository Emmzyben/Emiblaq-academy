<?php
$servername = 'localhost';
$username = "grafordc_graford";
$password = "Gratia12345";
$database = "grafordc_graford";

// Create a database connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Modify the SQL query to retrieve posts sorted by the latest post
$sql = "SELECT * FROM blogposts ORDER BY created_at DESC";


// Execute the query
$result = mysqli_query($connection, $sql);

// Check if there are results
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Rest of your code to display the posts...
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News and Blog</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
<style>
    .post-container {
            display: flex;
            flex-direction: column;
            align-items: left;
            justify-content: center;
         margin-left:-20px;
        }
        #cool{
          padding: 10px;
          padding-right:20px;
          color: black;
        }
        #cooler{
          margin-bottom: 30px;
        }
        #color{
          color:#0f2765;
        }
 #img {
           width: 500px;
           height: 300px;
            max-width: 500px;
            max-height: 300px;
            margin-top:10px;
        }
        ul li{
  list-style-type: none;
}
#topper{
  margin-top: 20px;
  background-color:  #0f2765;
  color: white;
  padding: 10px;
  font-weight: bold;
  font-size:17px;
   margin-left:40px;
   margin-right:40px;
    text-align:center;
}
        .newIMG{
             width:70px;
                margin-top:-40px;
                margin-left:10px;
                }
@media screen and (max-width:800px) {
  #img{
    width:100%;
    margin-left:-10px;
  }
        #topper{
margin-left:0;
   margin-right:0;
   width:auto;
}
  
}
</style>
</head>
<body>
<header id="cover">
        <div style="background-color: #ffffff;">
            
               <div style="display: flex;flex-direction: row;" id="name">
                   <span id="side"><img src="images/logo.jpg" alt="logo"  ></span>
                    <span id="center">
                      <h1>EMI<span style="color: #2154cf;">BLAQ</span> TECH SO<span style="color: #2154cf;">LUT</span>IONS</h1>
                      <p><i class="fa fa-phone-square"></i> +234 (0) 9056897432 <span id="sp"><i class="fa fa-phone-square"></i> +234 (0) 7085082556 </span><span id="sp"><a style="text-decoration: none;color: rgb(98, 108, 132);" href="mailto:support@emiblaqtech.com"><i class="fa fa-share-square-o"></i> support@emiblaqtech.com</a></span></p>
                   </span>
                   <span id="side" style="text-align:right;">
                       <ul>
                       <li><a href="register.php">REGISTER NOW!</a></li>
                       <li><a href="contact.html">CONTACT US</a></li>
                       </ul>
                   </span>
               </div>
    
              </div>
    </header> 

        
             <div id="bg">
                 <ul>
                     <li><a href="index.html">HOME</a></li>
                         <li><a href="about.html">ABOUT US</a></li> 
                         <li class="nav-container">
                            <span id="hoverer">SERVICES</span> 
                             <ul id="dropdown">
                              <li><a href="#">Web Design</a></li> 
                              <li><a href="#">Web development</a></li> 
                              <li><a href="#">Website maintenance</a></li>
                              <li><a href="#">Graphic design</a></li>
                             </ul>
                           </li>
                           <li class="nav-container">
                            <span id="hoverer">ACADEMY COURSES</span> 
                            <ul id="dropdown">
                              <li><a href="academy.html">Web development<br>(Html,Css,Javascript)</a></li> 
                              <li><a href="academy.html">Full-stack development<br>(Front-end,Back-end)</a></li> 
                              <li><a href="academy.html">Graphic design</a></li>
                             </ul>
                           </li>
                          <li><a href="portfolio.html">PORTFOLIO</a></li>
                          <li><a href="packages.html">OUR PACKAGES</a></li>
                          <li><a href="student.php">STUDENT PORTAL</a></li> 
                           <li><a href="blog.php">NEWS/BLOG</a></li>  
                 </ul>
             </div>
     

    
    <aside style="z-index: 1;">
        <div style="width: 20%;"><img src="images/logo.jpg" alt="logo" ></div>
       <div onclick="openNav()" >
          <div class="container" onclick="myFunction(this)" id="sideNav">
              <div class="bar1"></div>
              <div class="bar2"></div>
              <div class="bar3"></div>
            </div>
          </div>
    </aside>

      



<nav>
    <div id="mySidenav" class="sidenav">

      <img src="images/logo.jpg" alt="" id="img"><hr>
      <a href="index.html">Home</a>
      <a href="about.html">About Us</a>
      <a class="dropdown-item" onclick="toggleDropdown()" >
       Services +
          <div class="sub-menu1" style="display: none;transition: 0.5s;background-color: #d3e4ee;
          color: #fff;">
         <a href="#">Web Design</a>
          <a href="#">Web development</a>
          <a href="#">Website maintenance</a>
          <a href="#">Graphic design</a>
          </div>
        </a>
     
        <script>
          function toggleDropdown() {
            const subMenu = document.querySelector('.sub-menu1');
            subMenu.style.display = (subMenu.style.display === 'none' || subMenu.style.display === '') ? 'block' : 'none';
          }
        </script>
   <a class="dropdown-item" onclick="toggleDropdown2()">
   Academy Courses +
   <div class="sub-menu2" style="display: none;transition: 0.5s;background-color: #d3e4ee;
       color: #fff;">
   <a href="academy.html">Web development<br>(Html,Css,Javascript)</a>
   <a href="academy.html">Full-stack development<br>(Front-end,Back-end)</a>
  <a href="academy.html">Graphic design</a>
       </div>
     </a>
  
     <script>
       function toggleDropdown2() {
         const subMenu2 = document.querySelector('.sub-menu2');
         subMenu2.style.display = (subMenu2.style.display === 'none' || subMenu2.style.display === '') ? 'block' : 'none';
       }
     </script>

                  <a href="portfolio.html">Portfolio</a>
                        <a href="packages.html">Our packages</a>
                        <a href="student.php">Student portal</a>
                        <a href="blog.php">News/Blog</a>
                       <a href="contact.html"> Contact Us</a>
    </div>
    <script>
      
function myFunction(x) {
    x.classList.toggle("change");
  }

  var open = false;

function openNav() {
    var sideNav = document.getElementById("mySidenav");
    
    if (sideNav.style.width === "0px" || sideNav.style.width === "") {
        sideNav.style.width = "250px";
        open = true;
    } else {
        sideNav.style.width = "0";
        open = false;
    }
}
    </script>
</nav>

<div id="bg1">
  <div class="bgcover" style="text-align:center;display: flex;align-items: center;justify-content: center;">
    <div id="slidingDiv" class="hidden"><h2>News/Blog</h2></div>
    </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
  var slidingDiv = document.getElementById("slidingDiv");

  // Add a class to display the div and initiate the slide-out animation
  slidingDiv.classList.remove("hidden");

  // After a short delay, reset the left property to its final position
  setTimeout(function() {
      slidingDiv.style.left = "0";
  }, 10);
});
</script>



<div class="post-container"> 
  <ul>
    <?php
while ($row = mysqli_fetch_assoc($result)) {
    echo "<li id='cool'>";
    echo "<div id='cooler'>";
    echo "<h1 id='color'><b>" . $row['title'] . "</b></h1>";
    
    // Use nl2br to handle newlines in the content
    echo "<p>" . nl2br($row['content']) . "</p>";
    
    echo "</div>";
    
    if (!empty($row['image_path'])) {
        echo "<img src='" . $row['image_path'] . "' alt='Post Image' id='img'>";
    }
    
    echo "<p>Posted on: " . $row['created_at'] . "</p>";
    echo "</li>";
}
?>

  </ul>
</div>

<?php
// Close the database connection
mysqli_close($connection);
?>






<div id="overfoot">
  <footer>
<div style="display: flex;flex-direction: row;">
  <div><img src="images/logo.jpg" alt="logo" width="80px"></div>
<div><h3 style="padding: 10px;color: white;">EMIBLAQ TECH SOLUTIONS</h3></div>
</div>
<hr>
<div id="foot">
<div>
<h3>ABOUT</h3>
<p><a href="about.html">About Us</a></p>
<h3>ACADEMY COURSES</h3>
<p><a href="#">Web development<br>(Html,Css,Javascript)</a></p> 
<p><a href="#">Full-stack development<br>(Front-end,Back-end)</a></p> 
<p><a href="#">Graphic design</a></p>
</div>

 <div>
            <h3>SERVICES</h3>
            <P><a href="#">Web Design</a></P> 
            <P><a href="#">Web development</a></P> 
            <P><a href="#">Website maintenance</a></P>
            <P><a href="#">Graphic design</a></P>
          </div>

<div>
<h3>INFORMATION CENTER</h3>
<p><a href="blog.php">News and Blog</a></p>
<p><a href="packages.html">Our packages</a></p>
<p><a href="portfolio.html">Our Portfolio</a></p>

</div>

<div>
  <h3>CONTACT</h3>
  <p><a style="color: white;text-decoration: none;" href="mailto:support@emiblaqtech.com"><i class="fa fa-share-square-o"></i> support@emiblaqtech.com</a></p>
  <p><i class="fa fa-phone-square" ></i> +234 (0) 9056897432</p>
  <p><i class="fa fa-phone-square" ></i> +234 (0) 7085082556</p>
</div>
</div>
<hr>
<div style="text-align: center;">
  <p>©2023  Emiblaq Tech- All rights reserved</p>
</div>
</footer>
</div>
    <script src="javascript.js"></script>
</body>
</html>