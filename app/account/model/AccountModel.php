<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\account\model;

use think\Db;
use think\Model;

class AccountModel extends Model
{
	
  
  public function __construct() {
  
    /*导入phpExcel核心类 SPAPP_PATH为存放phpexcel路径的定义，在入口文件index.php定义*/
  require_once VENDOR_PATH.'/phpoffice/PHPExcel/Classes/PHPExcel.php';
  require_once VENDOR_PATH.'phpoffice/PHPExcel/Classes/PHPExcel/Writer/Excel5.php';     // 用于其他低版本xls 
  require_once VENDOR_PATH.'phpoffice/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php'; // 用于 excel-2007 格式  
  }
	//导入excel内容转换成数组，import方法要用到 
public function readexecl($filePath){
  $this->__construct();
  $PHPExcel = new \PHPExcel();//实例化，一定要注意命名空间的问题加\ 

  /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/ 
  $PHPReader = new \PHPExcel_Reader_Excel2007(); 
    if(!$PHPReader->canRead($filePath)){ 
      $PHPReader = new \PHPExcel_Reader_Excel5(); 
      if(!$PHPReader->canRead($filePath)){ 
        echo 'no Excel'; 
        return; 
      } 
    } 
 
  $PHPExcel = $PHPReader->load($filePath); 
  $currentSheet = $PHPExcel->getSheet(0);  //读取excel文件中的第一个工作表
  $allColumn = $currentSheet->getHighestColumn(); //取得最大的列号
  $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
  
  /**从第二行开始输出，因为excel表中第一行为列名*/ 
  for($currentRow = 2;$currentRow <= $allRow;$currentRow++){ 
  
      /**从第A列开始输出*/ 
    for($currentColumn= 'A';$currentColumn<= $allColumn; $currentColumn++){ 
   //这部分注释不要，取出的数据不便于我们处理
    //$val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/
    // if($val!=''){
    // $erp_orders_id[] = $val;
    // }
   //数据坐标 
           $address = $currentColumn . $currentRow; 
            //读取到的数据，保存到数组$arr中 
          $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
		  //echo "<br>";
    /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/ 
   //  iconv('utf-8','gb2312', $val)."\t"; 
    
  }  
  }
  
  return $data;
}
      
}