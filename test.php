<html>
   
   <head>
      <title>Tìm giá trị nhỏ nhất trong mảng PHP</title>
   </head>
   <body>
   
       <?php
            function tim_gia_tri_nho_nhat(Array $values)   
            {  
                return min(array_diff(array_map('intval', $values), array(0)));  
            }

            print_r(tim_gia_tri_nho_nhat(array(-1,0,1,12,-100,1))."<br>");
       ?>
       
   </body>
</html>