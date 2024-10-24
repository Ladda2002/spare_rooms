<?php

// เรียกใช้ไลบรารี TCPDF สำหรับสร้าง PDF
require_once('assets/tcpdf/tcpdf.php'); 
session_start(); 
require("function/function.php"); 

$start_date = $_POST["start_date"]; 
$end_date = $_POST["end_date"]; 

$reportContract = getReportContract($start_date,$end_date); 
$buy_map = array( 0=>'<label style="color:red">ยกเลิก</label>',1=>'<label style="color:blue">รอยืนยัน</label>',2=>'<label style="color:green">ยืนยัน</label>'); 

// แปลงปีปัจจุบันเป็น พ.ศ.
$yThai = date("Y")+543; 
$dateNow = date("d/m/").$yThai;

// สร้างเอกสาร PDF ใหม่
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// ตั้งค่าข้อมูลของเอกสาร
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni'); 
$pdf->SetTitle('Spare Room'); 
$pdf->SetSubject('TCPDF Tutorial'); 
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// ลบ header/footer ที่เป็นค่าเริ่มต้น
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// ตั้งค่าฟอนต์ monospaced ที่ใช้
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// ตั้งค่าระยะห่าง
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

// ตั้งค่า auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// ตั้งค่าอัตราส่วนการปรับขนาดภาพ
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ตั้งค่าภาษา
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// ตั้งค่าฟอนต์
//$fontname = $pdf->addTTFfont('fonts/Browa.ttf', 'TrueTypeUnicode', '', 32);
$pdf->SetFont('cordiaupc', '', 12, '', true); // ตั้งค่าฟอนต์ CordiaUPC

$line_html="";
//PAGE 3 >> PAGE 1
$pdf->AddPage(); 
// ตั้งค่าหน้าเป็นแนวนอน
$pdf->setPageOrientation ('L', $autopagebreak='', $bottommargin=''); 
// รับระยะห่างของ page break ปัจจุบัน
$bMargin = $pdf->getBreakMargin();
// รับค่า auto-page-break ปัจจุบัน
$auto_page_break = $pdf->getAutoPageBreak();
// ปิด auto-page-break
$pdf->SetAutoPageBreak(true, 0); // เปิดใช้งาน auto-page-break
// ตั้งค่าที่จะพิมพ์

$total_price = 0; // ค่าเริ่มต้นสำหรับราคารวม
$total_weight = 0; // ค่าเริ่มต้นสำหรับน้ำหนักรวม

$x = 0;
$y = 0;
$z = 0;

foreach($reportContract as $data){
    $a++; // นับลำดับ
    $cDate = formatDateFull($data["buy_date"]); // แปลงรูปแบบวันที่ซื้อ
    $cStat = $buy_map[$data["buy_status"]]; // ดึงสถานะการซื้อจาก map
    if($data["buy_status"] == 0){
        $x++;
    }else if($data["buy_status"] == 1){
        $y++;
    }else{
        $z++;
    }
$line_html  .= <<<EOD
                <tr>
                    <td align="center" style="border-right-width:0.1px;">{$a}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$cDate}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['buy_name']}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['buy_phone']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['buy_email']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['apartment']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['room_name']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$cStat}</td>
                </tr>

EOD;
}

$header_html  .= <<<EOD
<div style="text-align:center;margin:0;"><b style="font-size:30px;">รายงานการขายประกัน</b></div>
<div style="text-align:center;margin:0;">
<b style="font-size:20px;">วันที่ {$start_date} ถึง {$end_date}</b>
</div>
EOD;

// สร้างเนื้อหาในส่วน body
$body_html  .= <<<EOD
<table style="width:100%;" border="1">
    <tr>
        <td style="text-align:center;"><b>ลำดับ</b></td>
        <td style="text-align:center;"><b>วันที่</b></td>
        <td style="text-align:center;"><b>ชื่อ-นามสกุล</b></td>
        <td style="text-align:center;"><b>โทรศัพท์</b></td>
        <td style="text-align:center;"><b>อีเมล</b></td>
        <td style="text-align:center;"><b>ชื่อหอพัก</b></td>
        <td style="text-align:center;"><b>ห้อง</b></td>
        <td style="text-align:center;"><b>สถานะ</b></td>
    </tr>
    {$line_html}
</table>
<div align="right" >
    <label style="color:red">รวมยกเลิก {$x} รายการ</label><br/>
    <label style="color:blue">รวมรอยืนยัน {$y} รายการ</label><br/>
    <label style="color:green">รวมยืนยัน {$z} รายการ</label>
</div>
<div align="right">
    รวม {$a} รายงาน
</div>
EOD;

$html = <<<EOD
{$header_html}
{$body_html}
EOD;

// พิมพ์ข้อความโดยใช้ writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// ปิดและสร้างไฟล์ PDF
ob_end_clean(); // ล้าง buffer ของ output
$pdf->Output('รายงาน.pdf', 'I'); // แสดงไฟล์ PDF ในเบราว์เซอร์

?>

<?php die();
?>