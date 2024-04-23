<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="about.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
<!-- Included Flutterwave JavaScript Library -->
<script src="https://checkout.flutterwave.com/v3.js"></script>
<script>
  function makePayment() {
    FlutterwaveCheckout({
      public_key: publicKey,
      tx_ref: transactionRef,
      amount: 25000,
      currency: "NGN",
      payment_options: "card, banktransfer, ussd",
      meta: {
        source: "docs-inline-test",
        consumer_mac: "92a3-912ba-1192a",
      },
      customer: {
        email: customerEmail,
        phone_number: phoneNumber,
        name: customerName,
      },
      customizations: {
        title: "Graford college",
        description: "One-time registration fee",
        logo: "https://checkout.flutterwave.com/assets/img/rave-logo.png",
      },
      callback: function(response) {
        // Check if payment is successful
        if (response.status === "successful") {
          // Call notify.php upon successful payment
          var notifyXhr = new XMLHttpRequest();
          notifyXhr.open("POST", "notify.php", true);
          notifyXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          notifyXhr.onreadystatechange = function() {
            if (notifyXhr.readyState === 4 && notifyXhr.status === 200) {
              alert("Registration Successful");
            }
          };
          notifyXhr.send("email=" + encodeURIComponent(customerEmail));
        } else {
          // Payment unsuccessful, call delete.php
          var deleteXhr = new XMLHttpRequest();
          deleteXhr.open("POST", "delete.php", true);
          deleteXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          deleteXhr.onreadystatechange = function() {
            if (deleteXhr.readyState === 4 && deleteXhr.status === 200) {
              alert("Registration Failed");
            }
          };
          deleteXhr.send("email=" + encodeURIComponent(customerEmail));
        }
      },
      onclose: function(incomplete) {
        if (incomplete === true) {
          // Payment incomplete, call delete.php
          var deleteXhr = new XMLHttpRequest();
          deleteXhr.open("POST", "delete.php", true);
          deleteXhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          deleteXhr.onreadystatechange = function() {
            if (deleteXhr.readyState === 4 && deleteXhr.status === 200) {
              alert("Registration Failed");
            }
          };
          deleteXhr.send("email=" + encodeURIComponent(customerEmail));
        }
      }
    });
  }
</script>


<style>
  .course-info {
    display: none;
    color: red;
  }
</style>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'grafordc_graford';
$DATABASE_PASS = 'Gratia12345';
$DATABASE_NAME = 'grafordc_graford';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$errorMessage = ''; // Variable to store error messages
$successMessage = ''; // Variable to store success message
$publicKey = 'FLWPUBK_TEST-f92e874839fb45102e9c7e53e3d84695-X';

 // Function to generate a random string
        function generateRandomString($length = 8)
        {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = [
        'fullName',
        'state',
        'nationality',
        'dateOfbirth',
        'email',
        'school',
        'course',
        'phone',
        'address',
        'MaritalStatus',
        'religion',
        'qualification'
    ];

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errorMessage .= "Field '$field' is missing or empty. ";
        }
    }

    // Function to validate and move an uploaded image
    function validateAndMoveImage($fileInputName, $allowedExtensions, $maxFileSize, $destinationDirectory)
    {
        $errorMsg = '';

        if (!isset($_FILES[$fileInputName]['error']) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'Failed to upload ' . $fileInputName . '. Please try again.';
        } else {
            $tempFilePath = $_FILES[$fileInputName]['tmp_name'];
            $fileExtension = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
            $fileSize = $_FILES[$fileInputName]['size'];

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $errorMsg = 'Invalid file format for ' . $fileInputName . '. Allowed formats: JPG, JPEG, PNG, GIF.';
            } elseif ($fileSize > $maxFileSize) {
                $errorMsg = 'File size exceeds the allowed limit (2MB) for ' . $fileInputName . '.';
            } elseif (!move_uploaded_file($tempFilePath, $destinationDirectory . $_FILES[$fileInputName]['name'])) {
                $errorMsg = 'Failed to move uploaded ' . $fileInputName . ' to the directory.';
            }
        }

        return $errorMsg;
    }

    $checkExistingQuery = $con->prepare('SELECT COUNT(*) FROM accounts WHERE fullName = ? AND email = ?');
    $checkExistingQuery->bind_param('ss', $_POST['fullName'], $_POST['email']);
    $checkExistingQuery->execute();
    $checkExistingQuery->bind_result($existingAccountsCount);
    $checkExistingQuery->fetch();
    $checkExistingQuery->close();

    if ($existingAccountsCount > 0) {
        $errorMessage .= "An account with the same name and email already exists.";
    }

    // Image upload validation for passport
    $passportErrorMsg = validateAndMoveImage('passport', ['jpg', 'jpeg', 'png', 'gif'], 2 * 1024 * 1024, 'uploads/');

    if ($passportErrorMsg !== '') {
        $errorMessage .= $passportErrorMsg;
    }

   
    if ($errorMessage === '') {
        // Generate matriculation number: U + present year + random number
        $accreditationNumber = 'U' . date('Y') . '/' . mt_rand(1000, 9999);

        $randomPassword = mt_rand(10000000, 99999999); // Generate 8-digit random number

   
        $stmt = $con->prepare('INSERT INTO accounts (fullName, state, nationality, dateOfbirth, email, course, phone, address, MaritalStatus, religion, qualification, password, passport_image_path, identification_image_path, accreditationNumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        // Assign file paths to variables
        $passportImagePath = 'uploads/' . $_FILES['passport']['name'];

        // Bind parameters by reference
        $bindResult = $stmt->bind_param(
            'ssssssssssssssss',
            $_POST['fullName'],
            $_POST['state'],
            $_POST['nationality'],
            $_POST['dateOfbirth'],
            $_POST['email'],
            $_POST['course'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['MaritalStatus'],
            $_POST['religion'],
            $_POST['qualification'],
            $randomPassword,
            $passportImagePath,
            $accreditationNumber,
        );

       

        if ($stmt->execute()) {
            $transactionRef = 'txref-' . generateRandomString() . '-' . time();
            // Escape special characters to prevent potential issues in JavaScript
            $customerName = htmlspecialchars($_POST['fullName'], ENT_QUOTES, 'UTF-8');
            $customerEmail = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $phoneNumber = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
            $publicKey = htmlspecialchars($publicKey, ENT_QUOTES, 'UTF-8');

          
           echo '<script>
            var customerName = "' . $customerName . '";
            var customerEmail = "' . $customerEmail . '";
            var phoneNumber = "' . $phoneNumber . '";
            var publicKey = "' . $publicKey . '";
            var transactionRef = "' . $transactionRef . '";
            if (typeof makePayment === "function") {
                makePayment();
            } else {
                console.error("makePayment function is not defined");
            }
          </script>';
} else {
    $errorMessage .= 'Registration failed, please try again';
}

        $stmt->close();
    }
}
?>

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



<div style="background-image: url(images/);background-position: center;background-repeat: no-repeat;background-size: cover;margin-top: -20px;">
 <div style="background-color: #2155cf;">
  <h3 style="text-align: center;color: rgb(24, 23, 23);padding-top: 20px ;">Fill the form fields to register</h3>
  <?php
        if ($errorMessage !== '') {
            echo '<div style="color: white;text-align:center">' . $errorMessage . '</div>';
        }

        if ($successMessage !== '') {
            echo '<div style="color: white;text-align:center">' . $successMessage . '</div>';
        }
    ?>
  <form action="" method="post" enctype="multipart/form-data">
   
  <div style="height: auto;" id="reg">
 
  <div> 
    <input type="text" name="fullName" placeholder="Full Name"><br>
    <input type="text" name="state" placeholder="State Of Origin"><br>
    <input type="text" name="nationality" placeholder="Nationality"><br>
    <label for="">Date Of Birth</label><br>
    <input type="date" name="dateOfbirth" placeholder="Date Of Birth"><br>
    <input type="email" name="email" placeholder="Email"><br>
    <label for="">What Course are you applying for</label><br>
    <select name="course" id="course">
      <option value="">Select Course</option>
      <option value="Web Design(Front-end)">Web Design(Front-end)</option>
      <option value="web development(Back-end)">Web development(Back-end)</option>
      <option value="Graphic design">Graphic design</option>
    </select>
    
    <br>

</div>

<div>

<input type="number" name="phone" id="" placeholder="Phone Number"><br>
<input type="text" name="address" placeholder="Home/Residential Address"><br>
<label >Marital Status:</label><br>
<select id="MaritalStatus" name="MaritalStatus"><br>
<option value="">Select status</option>
    <option value="single">Single</option>
    <option value="married">Married</option>
 </select><br>
<label >Religion:</label><br>
<select id="religion" name="religion"><br>
    <option value="christianity">Christianity</option>
    <option value="islam">Islam</option>
    <option value="hinduism">Hinduism</option>
    <option value="buddhism">Buddhism</option>
    <option value="judaism">Judaism</option>
    <option value="sikhism">Sikhism</option>
    <option value="other">Other</option>
</select><br>
<label for="">Educational Qualification</label><br>
<select name="qualification" id="">
  <option value="High School">High School</option>
  <option value="Bachelors">Bachelor's Degree</option>
  <option value="PHD">PHD</option>
  <option value="others">Others</option>
</select><br>
<label for="">Upload a Recent Passport Photograph</label><br>
<input type="file" name="passport" id=""><br>

<button type="submits" id="pay">Proceed to payment</button>
<p>
By proceeding you agree to pay a non-refundable fee of #25,000 only for Registration
</p>
</div>
</div>
</form>
</div></div>


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


<script src="select.js"></script>
    <script src="javascript.js"></script>
</body>
</html>