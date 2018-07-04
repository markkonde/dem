
<?php
  $get_filename = $get_name = $get_tmp_name = array();
  $get_filename_st = $get_name_st = $get_tmp_name_st = '';
  function return_appropriate_string($value)
  {
    $count_anything = 0;
    $string_return = '';
    foreach ($value as $key) {
      if ($count_anything == 0) {
        $string_return .= $key;
      }
      else{
        $string_return .= '$//$'.$key;
      }
      $count_anything++;
    }
    return $string_return;
  }
  function name_and_move_uploaded_file($tmp_name, $foldername, $filename)
  {
    $get_tmp_name_st='';
    $tmp_name_count = 0;
    foreach ($tmp_name as $value) {
      if($value != ''){
        if($tmp_name_count==0){
          $get_tmp_name_st .= $tmp_name_count.$filename.'.jpeg';
          move_uploaded_file($value, $foldername.'/'.$tmp_name_count.$filename.'.jpeg');
        }
        else{
          $get_tmp_name_st .= '$//$'.$tmp_name_count.$filename.'.jpeg';
          move_uploaded_file($value, $foldername.'/'.$tmp_name_count.$filename.'.jpeg');
        }
      }
      else{
        if ($tmp_name_count != 0) {
          $get_tmp_name_st.='$//$';
        }
      }
        $tmp_name_count++;
    }
    return $get_tmp_name_st;
  }
 if(isset($_POST['submit'])){
  require_once 'database.php';
  $name = $_POST['name'];
  $file_tmp = $_FILES['filename']['tmp_name'];
  $phone = $_POST['phone'];
  $get_file_name_st = name_and_move_uploaded_file($file_tmp, 'passports', '_'.time());
  $get_name_st = return_appropriate_string($name);
  $get_phone_st = return_appropriate_string($phone);

  $name = explode("$//$", $get_name_st);
  $phone = explode("$//$", $get_phone_st);
  $file = explode("$//$", $get_file_name_st);
  for ($num = 0; $num < 3; $num++){
    $query = "INSERT INTO `info_table`(`name`, `phone`, `passport`) VALUES ('$name[$num]','$phone[$num]','$file[$num]')";
    $result = mysqli_query($conn, $query);
  }
 }
?>
<!DOCTYPE html>

<html>
 <head>
  <title>add new staff record</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <header>
   <h2 align="center">Read from <b>text input</b>, <b>file input</b> with the same <b>attribute name</b> in a form of array</h2>
   <h3 align="center">Auto-replace correct un-acceptable character in a field using javascript</h3>
   <h4 align="center">check <b>file size</b> before uploading using jquery</h4>
   <h5 align="center">Inserting the information into phpmyadmin database</h5>
 </header>
 <body>
  <div class="container" id="text_field" style="padding: 5%;">
    <br/>
    <form method="post" enctype="multipart/form-data">
      <div class="row">
      <?php for ($num = 0; $num < 3; $num++){ ?>
        <div class="col-xs-4">
          <fieldset>
            <legend>Person <?php echo($num+1) ?></legend>
            <div class="form-group">
                <label>name</label>
                <input type="text" name="name[]" id="name" onkeyup="getcorrect_name(this)" class="form-control">
            </div>
            <div class="form-group">
                <label>phone number</label>
                <input type="text" name="phone[]" id="phone" onkeyup="getcorrect_number(this)" class="form-control">
            </div>
            <div class="form-group">
                <label>passport</label>
                <input type="file" name="filename[]" class="form-control filename" accept="image/png,image/jpeg,image/jpg">
            </div>
          </fieldset>
        </div>
      <?php } ?>
      </div>
      <label class="text-danger filedanger"></label>
      <div class="form-group">
        <h3 align="center"><input type="submit" name="submit" value="submit" id="submit" class="btn btn-success"></h3>
      </div>
    </form>
  </div>
</body>
<script type="text/javascript">
  function getcorrect_number(input){
    var accepted = /[^0-9 +]/gi;
    input.value = input.value.replace(accepted, '');
  }
  function getcorrect_name(input){
    var accepted = /[^A-Z -.]/gi;
    input.value = input.value.replace(accepted, '');
  }
  $(document).ready(function(){
    $(document).on('change', '.filename', function(){
      var filesize = $(this)[0].files[0].size;
      //if file size is greater than 10KB, send an error message
      if (filesize > 10000) {
        $('.filedanger').html("file size is bigger than 10KB, it's " + (filesize/1000) + "KB");
        $(this).css('background-color', 'red');
      }
    });
    $(document).on('focusout', '.filename', function(){
      var filesize = $(this)[0].files[0].size;
      //if file size is greater than 10KB, send an error message
      if (filesize > 10000) {
        $(this).val('');
        $(this).css('background-color', 'white');
        $('.filedanger').html("");
      }
    });
  });
</script>
</html>
