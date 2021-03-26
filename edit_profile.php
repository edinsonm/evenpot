<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_activity.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('model_tickets.php'); ?>
<?php include_once('model_levels.php'); ?>
<?php include_once('time_stamp.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();
if(!isset($_SESSION['Id_user']))
{ 
$_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	header('Location: login.php');
}
else {
	$Id_user=$_SESSION['Id_user'];	
	$errmsg="";
// verificamos si se han enviado ya las variables necesarias.
if (isset($_POST["MM_insert2"]) && ($_POST["MM_insert2"] == "form2")) 
	{
	if ($_POST["Nick"]=="")
    {
	$errmsg="Debe escribir un Nick";
	}
	if ($_POST["Nombre"]=="")
    {
	$errmsg="Debe escribir un Nombre";
	}
	if ($_POST["Apellidos"]=="")
    {
	$errmsg="Debe escribir un Apellido";
	}
	if ($_POST["Gender"]==0)
    {
	$errmsg="Debe escoger un Genero";
	}
	
	if($_FILES['Foto']['name']){
		    $Foto = $_FILES['Foto']['name'];
			$ruta = "images/profile/".$Foto;
			$directorio="images/profile/";
			$tmp_imagen01 = $_FILES['Foto']['tmp_name'];		
			
			if($Foto!=""){
			move_uploaded_file($tmp_imagen01,$directorio.$Foto);
			}
			
			//$file=$tmp_imagen01;
			$file=$ruta;
			//$file_info = getimagesize($ruta);
			//$newwidth = 102;
			//$newheight = 102;
			
			$ext = explode(".", $Foto);
			
			$ext = strtolower($ext[count($ext) - 1]);
			if ($ext == "jpeg") $ext = "jpg";
		switch ($ext) {
        case "jpg":
                $img = imagecreatefromjpeg($file);
        break;
        case "png":
                $img = imagecreatefrompng($file);
        break;
        case "gif":
                $img = imagecreatefromgif($file);
        break;
}
	
	  $displ=0.5;
	  $width=300;
	  $height=300;
	  
	  $origw = imagesx($img);
	  $origh = imagesy($img);

	  $ratiow = $width / $origw;
	  $ratioh = $height / $origh;
	  $ratio = max($ratioh, $ratiow); /* This time we want the bigger image */

	  $neww = $origw * $ratio;
	  $newh = $origh * $ratio;

	  $cropw = $neww-$width;
	  $croph = $newh-$height;


	  $imgresize = imageCreateTrueColor($width, $height);

	  imagecopyresampled($imgresize, $img, -$cropw*$displ, -$croph*$displ, 0, 0, $width+$cropw, $height+$croph, $origw, $origh);
	  
	//$imgresize = imagecreatetruecolor($newwidth, $newheight);
	//imagecopyresampled($imgresize, $img, 0, 0, 0, 0, $newwidth, $newheight, $file_info[0], $file_info[1]);
	$fotonorm=$directorio.mt_rand().$Foto;
	imagejpeg($imgresize, $fotonorm, 80);	
	
	$newwidth=70;
	$newheight=70;
	
	$ratiow2 = $newwidth / $origw;
	$ratioh2 = $newheight / $origh;
	$ratio2 = max($ratioh2, $ratiow2); /* This time we want the bigger image */

	$neww2 = $origw * $ratio2;
	$newh2 = $origh * $ratio2;

	$cropw2 = $neww2-$newwidth;
	$croph2 = $newh2-$newheight;
	  
	$imgresize2 = imagecreatetruecolor($newwidth, $newheight);
//	imagecopyresampled($imgresize, $img, 0, 0, 0, 0, $newwidth, $newheight, $file_info[0], $file_info[1]);
	imagecopyresampled($imgresize2, $img, -$cropw2*$displ, -$croph2*$displ, 0, 0, $newwidth+$cropw2, $newheight+$croph2, $origw, $origh);
	$File = explode("/", $fotonorm);
	$File = strtolower($File[count($File) - 1]);
	$fotoruta=$directorio."thumb".$File;

	imagejpeg($imgresize2, $fotoruta, 100);	
		
	unlink($directorio.$Foto);		
		}
		else $fotonorm=$_POST["Imgprinc"];
	
	if (!$errmsg) {
	$Nick = $_POST["Nick"];
	$Nombre = $_POST["Nombre"];
	$Apellidos = $_POST["Apellidos"];
	$Bio = $_POST["Bio"];
	$Edad = $_POST["Fec_ini"];
	$Edad = str_replace("/", "-", $Edad);
	$Edad= date("Y-m-d", strtotime($Edad));
	$Gender = $_POST["Gender"];
	$Profesion = $_POST["Profesion"];
	$Institution = $_POST["Institution"];
	$Perfil = $_POST["Perfil"];
	$Location = $_POST["Location"];
	
	$query = "SELECT * FROM user WHERE Nick='".$_POST["Nick"]."' AND Id_user!='".$Id_user."'";
  //$user_count = $db_handle->numRows($query);
  $resultq = mysql_query($query);
  $user_count = mysql_num_rows($resultq);
  if($user_count>0) {
    $resultsb='<div class="alert alert-danger">Nick no disponible</div>';
	}
	else{
      $query = "UPDATE user set Nombre='$Nombre', Apellidos='$Apellidos', Nick='$Nick', Photo_user='$fotonorm', Bio='$Bio', Gender='$Gender', Birthday='$Edad', Location='$Location', Profesion='$Profesion', Institution='$Institution', Type_user='$Perfil' where Id_user='$Id_user'";
				mysql_query($query) or die(mysql_error());
				$resultsb='<div class="alert alert-success">El perfil ha sido actualizado.</div>';
		}       
	}
	else{
			$resultsb='<div class="alert alert-danger">'.$errmsg.'</div>';
		}
}

if (!$errmsg) {
$TablaUs = consultar_user($Id_user);
$row_TablaUsp = mysql_fetch_assoc($TablaUs);
if ($row_TablaUsp['Birthday']=='0000-00-00') {
	$Birth= date("Y-m-d"); 
	}
	else $Birth = str_replace("-", "/",date("d-m-Y", strtotime($row_TablaUsp['Birthday'])));
	}
else	{
	$row_TablaUsp['Photo_user']=$_POST["Imgprinc"];
	$row_TablaUsp['Nick']=$_POST['Nick'];
	$row_TablaUsp['Nombre']=$_POST['Nombre'];
	$row_TablaUsp['Apellidos']=$_POST['Apellidos'];
	$row_TablaUsp['Bio']=$_POST['Bio'];
	$row_TablaUsp['Location']=$_POST['Location'];
	$Birth=$_POST['Fec_ini'];
	$row_TablaUsp['Gender']=$_POST['Gender'];
	$row_TablaUsp['Profesion']=$_POST['Profesion'];
	$row_TablaUsp['Institution']=$_POST['Institution'];
	$row_TablaUsp['Type_user']=$_POST['Perfil'];
	}
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD-->
<head>
  <meta charset="utf-8">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Editar Perfil | evenpot </title>
  	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
  
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="account/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="account/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="account/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="account/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="account/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="account/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
  <link rel="stylesheet" href="account/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="account/plugins/iCheck/all.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="account/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="account/assets/css/bootstrap-fileupload.min.css" />
    <!-- daterange picker -->
  <link rel="stylesheet" href="account/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="account/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">	
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel='stylesheet' href='css/jAlert.css'>
	
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-50639346-2"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-50639346-2');
	</script>
	
		<!-- LinkedIn - Campaign -->
	<script type="text/javascript">
	_linkedin_partner_id = "638202";
	window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
	window._linkedin_data_partner_ids.push(_linkedin_partner_id);
	</script><script type="text/javascript">
	(function(){var s = document.getElementsByTagName("script")[0];
	var b = document.createElement("script");
	b.type = "text/javascript";b.async = true;
	b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
	s.parentNode.insertBefore(b, s);})();
	</script>
	<noscript>
	<img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid=638202&fmt=gif" />
	</noscript>
</head>

<body class="layout-top-nav skin-green">
<div class="wrapper">
    <!-- Mobile menu overlay mask -->
	
  <header class="main-header">
	<?php include ('navbar.php'); ?>  
  </header>
  <!-- Left side column. contains the logo and sidebar -->
<!-- /header container-->

  <div class="content-wrapper">
      <section class="content">
	<div class="row">
       <?php include ('leftedit.php'); ?> 
     <div class="col-md-6">

<? if ($resultsb) { ?>
  <p><? echo $resultsb; ?></p>
<? } ?>

 <div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title"><b>Editar Perfil</b></h3>
  </div>
    <div class="panel-body">
                <div id="collapseOne" class="accordion-body collapse in body">
                    <form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>"  class="form-horizontal" id="popup-validation">
                        <div class="form-group">                         
							<div class="form-group">
								<div class="col-lg-12" align="center">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"> 
										 <? if($row_TablaUsp['Photo_user']) {?>
										<img class="img-circle" src="<?php echo $row_TablaUsp['Photo_user']; ?>" >	
											  <? }   
											  else { ?>
											<img src="images/blank_boy_m.png" width="152" height="152">
											<? } ?>
										</div>
										<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 240px; max-height: 150px; line-height: 20px;">                                
										</div>
										<div>
											 <span class="btn btn-file btn-primary"><span class="fileupload-new">Buscar Imagen</span>
											 <span class="fileupload-exists">Cambiar</span>
											  <input name="Foto" type="file" id="Foto" /></span>
											 <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Quitar</a>
										</div>
									</div>
								</div>
							</div>                       
						</div>
				  
				   <div class="form-group">
                  <label class="control-label col-lg-3">Correo:</label>
					<div class="col-lg-6">
					<?php echo $row_TablaUsp['Correo'];?>
					 </div>
                   </div>
                                        <div class="form-group">
                                          <label class="control-label col-lg-3">Nombre de usuario:</label>

                                            <div class="col-lg-3">
                                                <input name="Nick" type="text" id="nick" value="<?php echo $row_TablaUsp['Nick']; ?>" class="form-control" maxlength="50" onBlur="comprobarUsuario()"/>
											<span id="resultado"></span> 
											<p><img src="images/ajax-loader.gif" id="loaderIcon" style="display:none" /></p>
											</div>
                                        </div>


<div class="form-group">
                  <label class="control-label col-lg-3">Nombre:</label>

<div class="col-lg-3">
<input name="Nombre" type="text" id="Nombre" value="<?php echo $row_TablaUsp['Nombre']; ?>" class="form-control" maxlength="50"/>
            </div>
                  <label class="control-label col-lg-2">Apellidos:</label>

<div class="col-lg-3">
<input name="Apellidos" type="text" id="Apellidos" value="<?php echo $row_TablaUsp['Apellidos'];?>" class="form-control" maxlength="50"/>
 </div>
                                  </div>

		<div class="form-group">
								  <label class="control-label col-lg-3">Acerca de mi:</label>

				<div class="col-lg-8">
					   
				<textarea id="wysihtml5" name="Bio" rows="5" class="validate[required] form-control"><?php echo $row_TablaUsp['Bio'];?></textarea>
					</div>
			  </div>
			   
			   <div class="form-group">
                  <label class="control-label col-lg-3">Ciudad:</label>

<div class="col-lg-6">
<input name="Location" type="text" id="Location" value="<?php echo $row_TablaUsp['Location'];?>" class="form-control" maxlength="50"/>
 </div>
                                      </div>

<div class="form-group">
                  <label class="control-label col-lg-3">Edad:</label>

<div class="col-lg-3">
					 <div class="input-group date">
					  <div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					  </div>
					  <input type="text" class="form-control pull-right" id="datepicker" name="Fec_ini"  value="<?php echo $Birth;?>" data-date-format="dd/mm/yyyy">
					</div>
 </div>
                                      
                  <label class="control-label col-lg-2">Genero:</label>

<div class="col-lg-3">
<select name="Gender" id="select2" class="form-control">
      <option value="0">Escoja su genero</option>
      <option value="1" <?php if ($row_TablaUsp['Gender']==1) echo "selected"; ?> >Masculino</option>
      <option value="2" <?php if ($row_TablaUsp['Gender']==2) echo "selected"; ?> >Femenino</option>
    </select>
 </div>
 </div>
 <div class="form-group">
                  <label class="control-label col-lg-3">Profesion/Empleo:</label>

<div class="col-lg-3">
<input name="Profesion" type="text" id="Profesion" value="<?php echo $row_TablaUsp['Profesion'];?>" class="form-control" maxlength="50"/>
 </div>
                                 
                  <label class="control-label col-lg-2">Entidad/Instit.:</label>

<div class="col-lg-3">
<input name="Institution" type="text" id="Institution" value="<?php echo $row_TablaUsp['Institution'];?>" class="form-control" maxlength="50"/>
 </div>
                                      </div> 
									  
									  <div class="form-group">
                  <label class="control-label col-lg-3">Perfil:</label>

<div class="col-lg-6">
<div class="field_container">
    <input name="Perfil" type="radio" id="Perfil" value="0" class="flat-red" checked="checked" />
    <label for="Perfil"></label>
  <strong>Publico</strong> </div>
  <div class="field_container">
    <input type="radio" name="Perfil" id="Perfil" value="1" class="flat-red" <?php if ($row_TablaUsp['Type_user']==1){echo "checked='checked'";}?>/>
    <label for="Perfil"></label>
  <strong>Privado</strong></div>
 </div>
								 
                                      </div>                                                                                                                 
                                      <div class="form-actions no-margin-bottom" style="text-align:center;">
                                          <input type="submit" value="Actualizar" class="btn btn-primary btn-lg " />
										 <input type="hidden" name="Imgprinc" value="<?php echo $row_TablaUsp['Photo_user']; ?>">                                         
										<input type="hidden" name="MM_insert2" value="form2"> 
                                      </div>

                                    </form>
                                </div>
                            </div>
                        </div>
            
        </div>
           

     <div class="col-md-3">
              <!-- USERS LIST -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Nuevos miembros</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
				  	<?
					$query = "SELECT *
					FROM user u
					WHERE u.Id_user!='$Id_user' AND Photo_user!=''
					ORDER BY u.Fecha DESC
					LIMIT 8";
					$result = mysql_query($query);
					while ($row_NewMem = mysql_fetch_assoc($result)) { ?>                   
				    <a href="/user.php?Id_user=<?php echo $row_NewMem['Id_user'];?>"/>
					<li>
                      <img src="<? echo  $row_NewMem['Photo_user']; ?>" alt="User Image">
                      <a class="users-list-name" href="#"><? echo  $row_NewMem['Nombre'].' '.$row_NewMem['Apellidos']; ?></a>
                    </li>
					</a>
					 <? } ?>
                   </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-center">
                  <a href="/find_friends" class="uppercase">Ver Todos</a>
                </div>
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
            </div>
            <!-- /.col -->
	</div>
</section>
</div>

   <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2019 <a href="https://evenpot.com">Evenpot</a>.</strong> All rights
    reserved.
  </footer>
  
 <!-- jQuery 3 -->
<script src="account/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="account/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- InputMask -->
<script src="account/plugins/input-mask/jquery.inputmask.js"></script>
<script src="account/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="account/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- Morris.js charts -->
<script src="account/bower_components/raphael/raphael.min.js"></script>
<script src="account/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="account/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="account/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="account/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob -->
<script src="account/bower_components/jquery-knob/js/jquery.knob.js"></script>
<!-- Sparkline -->
<script src="account/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- date-range-picker -->
<script src="account/bower_components/moment/min/moment.min.js"></script>
<script src="account/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="account/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="account/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- FastClick -->
<script src="account/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="account/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="account/dist/js/demo.js"></script>
<!-- SlimScroll -->
<script src="account/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="account/plugins/iCheck/icheck.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="account/dist/js/demo.js"></script>
<script src="account/assets/plugins/jasny/js/bootstrap-fileupload.js"></script>

<script>
  $(function () {
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })
  })
</script>
<script>
  $(function () {
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })
  })	
</script>	
<script>
$(document).ready(function(){
                         
      var nick;
             
      //hacemos focus
      $("#nick").focus();
                                                 
      //comprobamos si se pulsa una tecla
      $("#nick").keyup(function(e){
             //obtenemos el texto introducido en el campo
             nick = $("#nick").val();
			let contenido = e.target.value;
			e.target.value = contenido.replace(" ", "");
             //hace la búsqueda
             $("#resultado").delay(100).queue(function(n) {      
                                           
                  $("#resultado").html('<img src="images/ajax-loader.gif" />');
                                           
                        $.ajax({
                              type: "POST",
                              url: "checknick.php",
                              data: {nick: nick, id:<? echo $Id_user; ?>},
                              dataType: "html",
                              error: function(){
                                    alert("Recargar pagina (F12)");
                              },
                              success: function(data){                                                      
                                    $("#resultado").html(data);
                                    n();
                              }
                  });
                                           
             });
                                
      });
                          
});

 $(function () {
    /* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
    /* END JQUERY KNOB */

    //INITIALIZE SPARKLINE CHARTS
    $(".sparkline").each(function () {
      var $this = $(this);
      $this.sparkline('html', $this.data());
    });

    /* SPARKLINE DOCUMENTATION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
  });
    // We could use setInterval instead, but I prefer to do it this way
    setTimeout(mdraw, mrefreshinterval);
</script>	
</body>