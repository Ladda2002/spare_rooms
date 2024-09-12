<!DOCTYPE html>
<html lang="en">

<?php
require_once("header.php");
?>
<body>

  <?php
  require_once("nav.php");
  ?>


  <main class="" id="main-collapse">

    <div class="row">
      <div class="col-xs-12">
        <div class="section-container-spacer">
          <h1>รายงานการขายสัญญาเช่า</h1>
        </div>
        <div class="section-container-spacer">
          <form class="reveal-content" action="pdf_contract.php" method="post" enctype="multipart/form-data" target="_blank">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>วันที่เริ่มต้น</label>
                  <input type="text" class="form-control" id="start_date" name="start_date" placeholder="00/00/0000">
                </div>
                <div class="form-group">
                  <label>วันที่สิ้นสุด</label>
                  <input type="text" class="form-control" id="end_date" name="end_date" placeholder="00/00/0000">
                </div>
                <div align="right">
                  <button type="submit" name="submit" class="btn btn-primary btn-lg">แสดงรายงาน</button>
                </div>
              </div>
              <div class="col-md-6" >
                <div class="col-xs-12 col-md-12 section-container-spacer" align="center">
                  <img class="img-responsive" alt="" src="images/printer_ico.png" id="blah">
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