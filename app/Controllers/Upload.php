<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\BookModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Upload extends BaseController
{
    public function index()
    {
        return view('upload_page');
    }
    public function insertData(){
        //echo "<pre>";print_r($_POST);
        $total = count($_POST['sapid']);
        for($i=0;$i<$total;$i++){
            $books = new BookModel();
            $bookdata['sapid'] = $_POST['sapid'][$i];
            $bookdata['hostname'] = $_POST['hostname'][$i];
            $bookdata['loopback'] = $_POST['loopback'][$i];
            $bookdata['mac_address'] = $_POST['mac_address'][$i];
            $bookdata['creation_date'] = $_POST['creation_date'][$i];
            $books->insert($bookdata);
        }
        session()->setFlashdata('message', $total.' rows successfully added.');
        session()->setFlashdata('alert-class', 'alert-success');
        return redirect()->route('/');
    }
    public function importData(){
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv,xlsx],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
            session()->setFlashdata('message', 'No file uploaded or wrong format file. Upload only csv and excel file');
            session()->setFlashdata('alert-class', 'alert-danger');
            return view('upload_page', $data); 
        }else{
            if($file = $this->request->getFile('file')) {
                $csvArr = array();
                
                if ($file->isValid() && ! $file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move('../public/csvfile', $newName);
                    $file = fopen("../public/csvfile/".$newName,"r");
                    
                    $file_a = explode('.', $newName);
                    $extension = end($file_a);
                    if(!in_array($extension, ['xlsx','csv'])){
                        session()->setFlashdata('message', 'Wrong file format');
                        session()->setFlashdata('alert-class', 'alert-danger');
                    }
                    if($extension == 'xlsx'){
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet 	= $reader->load('../public/csvfile/'.$newName);
			$sheet_data 	= $spreadsheet->getActiveSheet()->toArray();
                        
                        $i=0;
                        foreach($sheet_data as $filedata){
                            if($i==0){$i++; continue;}
                            $csvArr[$i]['sapid'] = $filedata[0];
                            $csvArr[$i]['hostname'] = $filedata[1];
                            $csvArr[$i]['loopback'] = $filedata[2];
                            $csvArr[$i]['mac_address'] = $filedata[3];
                            $csvArr[$i]['creation_date'] = $filedata[4];
                            
                            if(strlen($csvArr[$i]['sapid']) > 18 || empty($csvArr[$i]['sapid'])){
                                session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                session()->setFlashdata('alert-class', 'alert-danger');
                                return redirect()->route('/');
                            }
                            if(strlen($csvArr[$i]['hostname']) > 14 || empty($csvArr[$i]['hostname'])){
                                session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                session()->setFlashdata('alert-class', 'alert-danger');
                                return redirect()->route('/');
                            }
                            if(strlen($csvArr[$i]['loopback']) > 100 || empty($csvArr[$i]['hostname'])){
                                session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                session()->setFlashdata('alert-class', 'alert-danger');
                                return redirect()->route('/');
                            }
                            if(strlen($csvArr[$i]['mac_address']) > 50 || empty($csvArr[$i]['hostname'])){
                                session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                session()->setFlashdata('alert-class', 'alert-danger');
                                return redirect()->route('/');
                            }
                            $i++;
                        }
                    }
                    else{
                        
                        $i = 0;
                        $numberOfFields = 5;
                        

                        while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {

                            $num = count($filedata);
                            if($i > 0 && $num == $numberOfFields){
                                $csvArr[$i]['sapid'] = $filedata[0];
                                $csvArr[$i]['hostname'] = $filedata[1];
                                $csvArr[$i]['loopback'] = $filedata[2];
                                $csvArr[$i]['mac_address'] = $filedata[3];
                                $csvArr[$i]['creation_date'] = $filedata[4];
                                if(strlen($csvArr[$i]['sapid']) > 18 || empty($csvArr[$i]['sapid'])){
                                    session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->route('/');
                                }
                                if(strlen($csvArr[$i]['hostname']) > 14 || empty($csvArr[$i]['hostname'])){
                                    session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->route('/');
                                }
                                if(strlen($csvArr[$i]['loopback']) > 100 || empty($csvArr[$i]['hostname'])){
                                    session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->route('/');
                                }
                                if(strlen($csvArr[$i]['mac_address']) > 50 || empty($csvArr[$i]['hostname'])){
                                    session()->setFlashdata('message', 'row no '.$i.' is invalid');
                                    session()->setFlashdata('alert-class', 'alert-danger');
                                    return redirect()->route('/');
                                }

                            }
                            $i++;
                        }
                    }    
                        
                        fclose($file);
                        $count = 0;
                        $dup = array();
                        for($i=1;$i<=count($csvArr);$i++){
                            for($j=$i+1;$j<=count($csvArr);$j++){
                                if($csvArr[$i]['sapid'].$csvArr[$i]['hostname'].$csvArr[$i]['loopback'].$csvArr[$i]['mac_address'].$csvArr[$i]['creation_date'] 
                                        == 
                                     $csvArr[$j]['sapid'].$csvArr[$j]['hostname'].$csvArr[$j]['loopback'].$csvArr[$j]['mac_address'].$csvArr[$j]['creation_date']
                                        ){
                                    $dup[] = $j;
                                }
                            }
                        }
                        $data['csv_data'] = (object)$csvArr;
                        $data['dup_data'][] = (object)$dup;
                        return view('parse_data',$data);
                    
                }
                else{
                    session()->setFlashdata('message', 'CSV file coud not be imported.');
                    session()->setFlashdata('alert-class', 'alert-danger');
                }
            }else{
                session()->setFlashdata('message', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('/');
        //return view('upload_page');
    }
}
