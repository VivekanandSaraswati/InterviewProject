<?php 
//if (isset($csv_data)){
//    echo "<pre>";print_r($csv_data);
//}
//if (isset($dup_data)){
//    //echo "<pre>";print_r($dup_data);
//    $dup_data=(array)$dup_data[0];
//    echo "<pre>";print_r($dup_data);
//}
$dup_data=(array)$dup_data[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CI Import Excel CSV to MySQL</title>
	<meta name="description" content="The tiny framework with powerful features">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
	<style>
	  .container {
		max-width: 500px;
	  }
	</style>
</head>
<body>
    <div><form action="<?php echo base_url('data/insert');?>" method="post" id="last-data">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">sapid</th>
      <th scope="col">hostname</th>
      <th scope="col">loopback</th>
      <th scope="col">mac_address</th>
      <th scope="col">creation_date</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php for($i=1;$i<=count((array)$csv_data);$i++){
        $tr_class = "";
        if(in_array($i,$dup_data)){
           $tr_class = "table-secondary";
        }
        ?>
    <tr class="<?php echo $tr_class;?>">
        <th scope="row"><?php echo $i;?><input type="hidden" name="id[]" value="<?php echo $i;?>" readonly></th>
        <td><?php echo $csv_data->$i['sapid'];?><input type="text" maxlength="18" class="hide" name="sapid[]" value="<?php echo $csv_data->$i['sapid'];?>"></td>
        <td><?php echo $csv_data->$i['hostname'];?><input type="text" maxlength="14"  class="hide" name="hostname[]" value="<?php echo $csv_data->$i['hostname'];?>"></td>
        <td><?php echo $csv_data->$i['loopback'];?><input type="text" maxlength="100" class="hide" name="loopback[]" value="<?php echo $csv_data->$i['loopback'];?>"></td>
        <td><?php echo $csv_data->$i['mac_address'];?><input type="text" maxlength="50" class="hide" name="mac_address[]" value="<?php echo $csv_data->$i['mac_address'];?>"></td>
        <td><?php echo $csv_data->$i['creation_date'];?><input type="text" class="hide" name="creation_date[]" value="<?php echo $csv_data->$i['creation_date'];?>" readonly></td>
        <td><div class="edit"><?php if(!empty($tr_class)){?><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
</svg><?php } ?>Edit</div><div class="remove">X</div></td>
    </tr>
    <?php } ?>    
  </tbody>
</table>
    <div class="text-center"><button class="btn-dark" id="form_sub">Sumbit</button></div>
        </form>
    <style>
        .hide{display: none;}
        tr.show > td > input.hide{display: block;border: 2px solid #f00;}
        .edit {cursor: pointer;display: inline-block;}
				svg.bi.bi-info-circle {color: #f00;}
				.remove {
						display: inline-block;
						margin-left: 10px;
						border-radius: 50%;
						border: 2px solid #f00;
						padding: 2px 8px;
						background: #f00;
						color: #fff;
						cursor: pointer;
				}
    </style>
</div>
<script>
jQuery(function($){
    $('#form_sub').on('click',function(e){
        e.preventDefault();
        var total = $('input[name="id[]"]').length;
        var v_sapid = document.getElementsByName('sapid[]');
        var v_hostname = document.getElementsByName('hostname[]');
        var v_loopback = document.getElementsByName('loopback[]');
        var v_mac_address = document.getElementsByName('mac_address[]');
        var v_creation_date = document.getElementsByName('creation_date[]');
        for(var i=0;i<total;i++){
            for(var j=i+1;j<total;j++){
                if(v_sapid[i].value+v_hostname[i].value+v_loopback[i].value+v_mac_address[i].value  == v_sapid[j].value+v_hostname[j].value+v_loopback[j].value+v_mac_address[j].value){
                    alert("Duplicate value exists");
                    return;
                }
            }
        }
        var form = document.getElementById("last-data");
        form.submit();
    });
    $('.edit').on('click',function(){$($(this).closest('tr')).addClass('show');});
    $(".remove").on('click',function(){if(confirm("Do you want to remove") == true){$(this).closest('tr').remove();}});
});
    
</script>
</body>
</html>
