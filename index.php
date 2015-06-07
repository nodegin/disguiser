<? /**********/ require_once('Disguiser.php') ; ob_start('Disguiser::disguise') /**********/ ?>

<html>
  <head>
    <title>hey world!</title>
    <style>
    body{
      color: white;
      text-align: center;
    }
    .my-extremely-unbelievably-very-long-class-name{
      background: red;
    }
    .X_compare_with_this-long_long_longer-my-extremely-unbelievably-very-long-class-name{
      background: blue;
    }
    </style>
  </head>
  <body>
    <div class="my-extremely-unbelievably-very-long-class-name">foo</div>
    <div class="X_compare_with_this-long_long_longer-my-extremely-unbelievably-very-long-class-name">bar</div>
  </body>
</html>

<? /**********************************/ ob_end_flush() /**********************************/  ?>
