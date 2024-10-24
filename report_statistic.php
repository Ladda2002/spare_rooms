<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<?php 
$allApartment = getAllApartmentUser($_SESSION['id']);
if(isset($_POST["search"])){
  $allDataDetailChart = getAllDataDetailChart($_POST["apartment"]);
  $dataPoints = getAllDataChart($_POST["apartment"]);
}
?>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
  window.onload = function() {


    var chart = new CanvasJS.Chart("chartContainer", {
      animationEnabled: true,
      title: {
        text: "ข้อมูล"
      },
      subtitles: [{
        text: "สถิติ"
      }],
      data: [{
        type: "pie",
        yValueFormatString: "#,##0\" ครั้ง\"",
        indexLabel: "{label} ({y})",
        dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
      }]
    });
    chart.render();

  }
</script>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <div class="row">
      <div class="col-xs-12">
        <div class="section-container-spacer">
          <h1>ดูรายงานการจอง</h1>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" class="form-control" id="users_id" name="users_id" value="<?php echo $_SESSION["id"];?>">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>วันที่เริ่มต้น</label>
                  <select name="apartment" class="form-control" id="apartment" required>
                    <option value="">-- โปรดเลือก --</option>
                    <?php foreach($allApartment as $data){ ?>
                      <?php $selected = "";
                      if($currentRoom['apartment'] == $data['id']){
                        $selected = " selected";

                      }
                      ?>
                      <option value="<?php echo $data['id']?>" <?php echo $selected;?>><?php echo $data['apart_name']?></option>
                    <?php } ?>
                  </select>

                  
                </div>
                <div align="right">
                  <button type="submit" name="search" class="btn btn-primary btn-lg">แสดงรายงาน</button>
                </div>

                <table class="table" >
                    <thead>
                      <tr>
                        <td>ห้อง</td>
                        <td  style="text-align: center;">จำนวนการจอง</td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(empty($allDataDetailChart)){ ?>
                      <?php }else{?>
                        <?php $i=1;?>
                        <?php foreach($allDataDetailChart as $data){ ?>
                          <tr>
                            <td><?php echo $data["room_name"];?></td>
                            <td style="text-align: center;"><?php echo $data["numCount"];?></td>
                          </tr>
                        <?php } ?>
                      <?php } ?>
                    </tbody>
                  </table>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                  <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <script>

    $('#start_date').datetimepicker({
      lang:'th',
      timepicker:false,
      format:'d/m/Y'
    });

    $('#end_date').datetimepicker({
      lang:'th',
      timepicker:false,
      format:'d/m/Y'
    });

  </script>
  <?php
  require_once("footer.php");
  ?>
</body>

</html>