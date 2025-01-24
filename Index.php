<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Task.css">
</head>
<body>
   <div class="container">
        <div class="sidebar1">
          <h4>USED TOKENS:</h4>
          <ul>
              <?php
              $usedTokenFile = 'used_tokens.json';
              if (file_exists($usedTokenFile)) {
                  $usedTokens = json_decode(file_get_contents($usedTokenFile), true);
                  if (!empty($usedTokens['tokens'])) {
                      foreach ($usedTokens['tokens'] as $usedToken) {
                          echo "<li>{$usedToken}</li>";
                      }
                  } else {
                      echo "<li>No tokens used yet</li>";
                  }
              } else {
                  echo "<li>Error: Used token file not found</li>";
              }
              ?>
          </ul>
        </div>
        
        <div class="mainbox">
        <h1 class="center-title">Book Borrow System</h1>
            <!--Add Books-->
            <div class="box1">
            <form action="add_book_process.php" method="POST">
            <h1 class="bcenter-title">Add Books</h1>

            <label for="bname">Book name:</label>
            <input type="text" id="bname" name="bname" placeholder="Enter Book name">

            <label for="aname">Author name:</label>
            <input type="text" id="aname" name="aname" placeholder="Enter Author name">

            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" placeholder="Enter ISBN">

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" placeholder="Enter price">

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" placeholder="Enter Quantity">


            <input type="submit" value="Submit">
            </form>
            </div>

            <!--Search Book-->
            <div class="box2">
                <form action="search_book.php" method="POST">
                    <h1 class="scenter-title">Search Book</h1><br>
              
                    <label for="bname">Enter Book name:</label>
                    <input type="text" id="bname" name="bname" placeholder="Enter Book name">
                    
                    <input type="submit" value="Submit">
                </form>
            </div>
            
            <!--Show available Books-->
            <div class="box3">
              <form action="show_books.php" method="POST">
                <h1 class="acenter-title">All Books</h1><br>
                
                <button class="btn success"> Show available Books</button>
              </form>
            </div>

            <!--Book Image-->
            <div class="box4">
                <div class="minibox1">
                <img src="book1.jpg" alt="Book 1 Image">
                </div>
                <div class="minibox2">
                <img src="book1.jpg" alt="Book 1 Image">
                </div>
                <div class="minibox3">
                <img src="book1.jpg" alt="Book 1 Image">
                </div>
            </div>

            <div class="box5">
                <!--Borrow Book-->
                <div class="bottombox1">

                  <form action="Process.php" method="post" >
                    <h2>Borrow a Book</h2>
                    
                    <label for="fname">Name:</label>
                    <input type="text" id="fname" name="fname" placeholder="Enter your full name">
                    
                    <label for="id">ID:</label>
                    <input type="text" id="id" name="id" placeholder="Enter your student id">
                    
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email">

                    <label for="btitle">Book title:</label>
                    <select id="btitle" name="btitle">
                        <option value="Web Technologies">Web technologies</option>
                        <option value="Programming">Programming</option>
                        <option value="Design Patterns">Design Patterns</option>
                        <option value="Machine Learning">Machine Learning</option>
                        <option value="Data Science">Data Science</option>
                        <option value="Computer Vision">Computer vision</option>
                        <option value="Database">Database</option>
                        <option value="Software">Software</option>
                        <option value="Compiler">Compiler</option>
                        <option value="Human Science">Human science</option>
                    </select>
                    
                    <label for="bdate">Borrow Date:</label>
                    <input type="date" id="bdate" name="bdate">
                    
                    <label for="rdate">Return date:</label>
                    <input type="date" id="rdate" name="rdate">
                    
                    <label for="token">Token:</label>
                    <input type="text" id="token" name="token" placeholder="Enter token">
                    
                    <label for="fees">Fees:</label>
                    <input type="text" id="fees" name="fees" placeholder="Enter fees"> 
                    
                    <input type="submit" value="Submit">
                  </form> 
                </div>

                <!-- Available Tokens -->
                <div class="bottombox2">
                  <label>AVAILABLE TOKENS:</label>
                  <ul>
                      <?php
                      $tokenFile = 'token.json';
                      if (file_exists($tokenFile)) {
                          $tokens = json_decode(file_get_contents($tokenFile), true);
                          if (!empty($tokens['tokens'])) {
                              foreach ($tokens['tokens'] as $token) {
                                  echo "<li>{$token}</li>";
                              }
                          } else {
                              echo "<li>No tokens available</li>";
                          }
                      } else {
                          echo "<li>Error: Token file not found</li>";
                      }
                      ?>
                  </ul>
              </div>
            </div>
          </div>
        <div class="sidebar2">
        <h3>ID: 22-46356-1</h3>
        </div>
   </div>
   
</body>
</html>