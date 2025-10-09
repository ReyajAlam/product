<?php include('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Order</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background: #e6e7df;
      font-family: Arial, sans-serif;
    }

    /* Header */
    .top-header {
      background: #000;
      color: #fff;
      padding: 10px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .top-header .left-menu a {
      color: #fff;
      text-decoration: none;
      margin-right: 18px;
      font-size: 13.5px;
      text-transform: uppercase;
    }
    .top-header .left-menu a:first-child {
      font-weight: 700;
      font-size: 16px;
    }
    .top-header .left-menu a:hover {
      text-decoration: underline;
    }
    .logout {
      color: #fff;
      font-weight: bold;
      text-decoration: none;
      font-size: 14px;
    }

    /* Page Heading */
    h2.page-title {
      text-align: center;
      font-size: 18px;
      font-weight: 700;
      color: #000;
      margin-top: 25px;
      text-transform: uppercase;
    }

    /* Search Box */
    .search-box {
  border: 1px solid #000;      /* Black rounded border all sides */
  border-radius: 50px;         /* Keep corners rounded */
  background: #fff;
  width: 97%;
  margin: 20px auto;
  box-shadow: 0 2px 8px rgba(0,0,0,0.25);
  padding: 10px 25px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  position: relative;
}


    .search-options {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
    }
    .search-options span {
      font-weight: 600;
      margin-right: 5px;
    }
    .search-options label {
      margin-right: 10px;
      font-size: 14px;
      font-weight: normal;
    }

    .search-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .search-input {
      border: none;
      outline: none;
      border-radius: 30px;
      padding: 10px 18px;
      width: 517px;
      background: #f3f3f3;
      font-size: 14px;
    }

    .search-btn {
      background: linear-gradient(to right, #00b09b, #96c93d);
      border: none;
      border-radius: 30px;
      color: #fff;
      padding: 10px 25px;
      font-weight: 600;
      font-size: 14px;
      transition: 0.3s;
    }
    .search-btn:hover {
      opacity: 0.9;
    }

    /* Result Section */
    .result-box {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 2px 5px rgba(0,0,0,0.25);
      padding: 20px 25px;
      margin: 25px auto;
      width: 88%;
    }


    .search-title {
  position: absolute;
  top: -11px;
  left: 30px;
  font-size: 14px;
  font-weight: 600;
  color: #222;
  background: #fff;   /* Fixes label overlapping border */
  padding: 0 5px;     /* Space left/right of text */
}

.search-here{
    
    margin: 2px auto;
    border-radius: 141px;
    /* align-items: center; */
    justify-content: center;
    padding: 1px;
    width: 68%;
    background: white;
    /* border-radius: 1012px; */
    /* width: 90%; */
}


  </style>
</head>
<body>

  <!-- HEADER -->
  <div class="top-header">
    <div class="left-menu">
      <a href="#">VPLAK</a>
      <a href="#">PRODUCT</a>
      <a href="#">BRAND</a>
      <a href="#">CATEGORY</a>
      <a href="#">BRAND CATEGORY</a>
      <a href="#">ORDER'S PANEL</a>
      <a href="#">BAR CHART</a>
      <a href="#">BUYING GUIDE</a>
      <a href="#">EXCEL</a>
      <a href="#">SEO TEXT</a>
    </div>
    <a href="#" class="logout">LOGOUT</a>
  </div>

  <!-- PAGE HEADING -->
  <h2 class="page-title">SEARCH ORDER</h2>

  <!-- SEARCH BOX -->
  <div class="search-here">
    <div class="search-box">
    <span class="search-title">Search Order</span>
    <div class="search-options">
      <span>By</span>
      <label><input type="radio" name="searchBy" value="order_id" checked> Order Id</label>
      <label><input type="radio" name="searchBy" value="mobile"> Mobile</label>
      <label><input type="radio" name="searchBy" value="name"> Name</label>
      <label><input type="radio" name="searchBy" value="email"> Email</label>
    </div>
    <div class="search-right">
      <input type="text" id="searchValue" class="search-input" placeholder="Enter value...">
      <button class="search-btn" id="searchBtn">SEARCH ORDER</button>
    </div>
  </div>
  </div>

  <!-- RESULT AREA -->
  <div id="resultArea" class="container"></div>

  <script>
    $(document).ready(function(){
      $("#searchBtn").click(function(){
        var searchBy = $("input[name='searchBy']:checked").val();
        var value = $("#searchValue").val();
        if(value.trim() === ''){
          alert("Please enter a value to search");
          return;
        }
        $.ajax({
          url: "search.php",
          type: "POST",
          data: { searchBy: searchBy, value: value },
          success: function(data){
            $("#resultArea").html(data);
          }
        });
      });
    });
  </script>

</body>
</html>
