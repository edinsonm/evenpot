<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('mail_register.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();

if ($_GET['Id_user']){
$Id_user=$_GET['Id_user'];
$query_us = "SELECT * FROM user WHERE Id_user='$Id_user'";
$row_dataus = mysql_fetch_assoc(mysql_query($query_us));
$_POST['Nombre'] = $row_dataus['Nombre'];
$_POST["Apellido"]= $row_dataus['Apellidos'];
$_POST["Email"]= $row_dataus['Correo'];
}
      
// verificamos si se han enviado ya las variables necesarias.
if ($_POST["MM_insert2"] == "form2"){
	$validate=0;
if ($_POST["t_and_c"]!=1){
	$msg = "*Debe Aceptar los terminos y condiciones"."<br>".$msg;
	$validate=1;}
if (trim($_POST["Clave"]) == ""){
	$msg = "*Debe ingresar una Clave"."<br>".$msg;
	$validate=1;}
if(trim($_POST["Email"]) == ""){
	$msg = "*Debe ingresar un Email"."<br>".$msg;
	$validate=1;}	
if (trim($_POST["Apellido"]) == ""){
	$msg = "*Debe ingresar un Apellido"."<br>".$msg;
	$validate=1;}
if (trim($_POST["Nombre"]) == ""){
	$msg = "*Debe ingresar un Nombre"."<br>".$msg;
	$validate=1;}

	if ($validate==0){
    	
	$Email = $_POST["Email"];
    $Clave = $_POST["Clave"];
    $Retype = $_POST["Retype"];
    $Nombre = $_POST["Nombre"];
	$Apellido = $_POST["Apellido"];
	$Nick = $_POST["Nick"];
    // ¿Coinciden las contraseñas?
        if($Clave!=$Retype) {
            $msg = "Las claves no coinciden \n".$msg;
        }else{
            // Comprobamos si el nombre de usuario o la cuenta de correo ya existían
			$checkuser = mysql_query('SELECT Correo FROM user WHERE Correo=\''.$Email.'\'');
            $username_exist = mysql_num_rows($checkuser);
            
            if ($username_exist>0) {
                $msg = "La cuenta de correo esta ya en uso".$msg;
              
            }else{
				$Creado=date("Y-n-j").date(" H:i:s");
                $query = 'INSERT INTO user (Nombre, Apellidos, Correo, Clave, Points, Username, Fecha)
                VALUES (\''.$Nombre.'\',\''.$Apellido.'\',\''.$Email.'\',\''.$Clave.'\',5, \''.$Email.'\', \''.$Creado.'\')';
                mysql_query($query) or die(mysql_error());
            
			//notifica_register($Nombre, $Email, $Clave);
			$_SESSION["k_name"] = $Nombre;
			$_SESSION["k_apel"] = $Apellido;
			$checkuser = mysql_query('SELECT Id_user FROM user WHERE Correo=\''.$Email.'\'');
            $username_exist = mysql_fetch_assoc($checkuser);
            $_SESSION["Id_user"] = $username_exist['Id_user'];
			unset($_SESSION['verifica']);
			$redirect = "congrats.php";
			unset($_SESSION['redirect_to']); 
			header('Location: '.$redirect);
			}
        }
    }
    //formRegistro();
}

if((isset($_SESSION['Id_user']))&&(isset($_SESSION['redirect_to']))) { 
unset($_SESSION['verifica']); 
$redirect=$_SESSION['redirect_to'];
unset($_SESSION['redirect_to']); 
header('Location: '.$redirect);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registro | evenpot</title>
<?php include ('meta.php'); ?>
	<!-- CSS -->
    <link href="/css2/base.css" rel="stylesheet">
    
    <!-- CSS -->
	<link href="/css2/date_time_picker.css" rel="stylesheet">
	<link rel="stylesheet" href="/css/blueimp-gallery.css">
	<link rel="stylesheet" href="/css/blueimp-gallery-indicator.css">
	<link rel="stylesheet" href="admin/assets/css/bootstrap-fileupload.min.css" />
	<link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" rel="stylesheet">
		
     <!-- Google web fonts -->
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Gochi+Hand' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
    
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

<link href="/css/star-rating.css" rel="stylesheet">
<link rel='stylesheet' href='/css/jAlert.css'>
</head>
<body background='https://www.evenpot.com/img/img01.jpeg'>	
    <div class="layer"></div>
    <!-- Mobile menu overlay mask -->
	
    <header class="sticky"> 
    <?php include ('head_menuU2.php'); ?>
	</header>
	
<div class="container margin_60">

<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
<? if ($msg!=""){ ?>
<div class="alert alert-dismissible alert-warning">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <p><? echo $msg; ?></p>
</div>
<? } ?>
	<form method="post" action="<?php echo $editFormAction; ?>">
			<h3 class="heading-desc">Registrarse con Facebook</h3>
			<div class="social-box">			
             <div class="row mg-btm">
             <div class="col-md-12">

                <a href="#" class="btn btn-primary btn-block btn-lg" onclick="fb_login();">
                  <i class="icon-facebook"></i>    Registrarse con Facebook
                </a>
			</div>
			</div>
            </div>
			 <hr class="colorgraph">
			<h3 class="heading-desc">Registrarse con tu correo</h3>             
			 <div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
                        <input type="text" name="Nombre" id="first_name" class="form-control input-lg" value="<?php echo $_POST['Nombre'];?>" placeholder="Nombres" tabindex="1">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="text" name="Apellido" id="last_name" class="form-control input-lg" value="<?php echo $_POST['Apellido'];?>" placeholder="Apellidos" tabindex="2">
					</div>
				</div>
			</div>
			<div class="form-group">
				<input type="email" name="Email" id="email" class="form-control input-lg"  value="<?php echo $_POST['Email'];?>" placeholder="Email" tabindex="4">
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="Clave" id="password" class="form-control input-lg" value="<?php echo $_POST['Clave'];?>" placeholder="Asignar Clave" tabindex="5">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<input type="password" name="Retype" id="password_confirmation" value="<?php echo $_POST['Retype'];?>" class="form-control input-lg" placeholder="Confirmar Clave" tabindex="6">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-3 col-md-3">
					<span class="button-checkbox">
						<button type="button" class="btn" data-color="info" tabindex="7">De acuerdo</button>
                        <input type="checkbox" name="t_and_c" id="t_and_c" class="hidden" value="1">
					</span>
				</div>
				<div class="col-xs-8 col-sm-9 col-md-9">
					 Al dar clic en <strong class="label label-primary">Registrarse</strong>, usted esta de acuerdo con los <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terminos & Condiciones</a> establecidos por este sitio, incluidas nuestras Cookies de uso.
				</div>
			</div>
			
			<hr class="colorgraph">
			<div class="row">
			 <div class="col-xs-12 col-md-12"><input type="submit" value="Registrarse" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
			</div>
			<input type="hidden" name="MM_insert2" value="form2">  
		</form>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">Terminos & Condiciones</h4>
			</div>
			<div class="modal-body">
				<p><strong>Declaración de derechos y responsabilidades</strong><br />
  Esta Declaración de derechos y responsabilidades (&quot;Declaración&quot;,  &quot;Condiciones&quot; o &quot;DDR&quot;) tiene su origen en los&nbsp;<a href="https://www.facebook.com/principles.php">Principios de Evenpot</a>&nbsp;y  contiene las condiciones del servicio que rigen nuestra relación con los  usuarios y con todos aquellos que interactúan con Evenpot, así como las marcas,  los productos y los servicios de Evenpot, que reciben el nombre de&nbsp;<a href="https://www.facebook.com/help/1561485474074139">&quot;Servicios de Evenpot&quot; o  &quot;Servicios&quot;</a>. Al usar los Servicios de Evenpot o al acceder  a ellos, muestras tu conformidad con esta Declaración, que se actualiza  periódicamente según se estipula en la sección&nbsp;13 más adelante. Al final  de este documento también encontrarás otros recursos que te ayudarán a  comprender cómo funciona Evenpot.<br />
  Dado que Evenpot proporciona una amplia variedad de&nbsp;<a href="https://www.facebook.com/help/1561485474074139">Servicios</a>, es posible que  te pidamos que consultes y aceptes condiciones complementarias que se apliquen  a tu interacción con una aplicación, un producto o un servicio específico. En  caso de discrepancias entre dichas condiciones complementarias y esta DDR,  prevalecerán las condiciones complementarias asociadas a la aplicación, el  producto o el servicio respecto del uso que hagas de ellos y en la medida en  que exista una discrepancia.</p>
<ol>
  <li><strong>Privacidad</strong><br />
    <br />
    Tu privacidad es muy importante para nosotros. Diseñamos nuestra&nbsp;<a href="https://www.facebook.com/about/privacy/">Política de datos</a>&nbsp;para  ayudarte a comprender cómo puedes usar Evenpot para compartir información con  otras personas, y cómo recopilamos y usamos tu contenido e información. Te  recomendamos que leas nuestra&nbsp;<a href="https://www.facebook.com/about/privacy/">Política de datos</a>&nbsp;y  que la utilices para poder tomar decisiones fundamentadas.&nbsp;<br />
    &nbsp;</li>
  <li><strong>Compartir el  contenido y la información</strong><br />
    <br />
    Eres el propietario de todo el contenido y la información que publicas en Evenpot  y puedes controlar cómo se comparte a través de la configuración de la&nbsp;<a href="https://www.facebook.com/settings/?tab=privacy">privacidad</a>&nbsp;y de las&nbsp;<a href="https://www.facebook.com/settings/?tab=applications">aplicaciones</a>.  Asimismo:</li>
  <ol>
    <li>En el caso de  contenido protegido por derechos de propiedad intelectual, como fotos y videos  (&quot;contenido de PI&quot;), nos concedes específicamente el siguiente  permiso, de acuerdo con la configuración de la&nbsp;<a href="https://www.facebook.com/privacy/">privacidad</a>&nbsp;y de las&nbsp;<a href="https://www.facebook.com/settings/?tab=applications">aplicaciones</a>:  nos concedes una licencia no exclusiva, transferible, con derechos de  sublicencia, libre de regalías y aplicable en todo el mundo para utilizar  cualquier contenido de PI que publiques en Evenpot o en conexión con Evenpot  (&quot;licencia de PI&quot;). Esta licencia de PI finaliza cuando eliminas tu  contenido de PI o tu cuenta, salvo si el contenido se compartió con terceros y  estos no lo eliminaron.</li>
    <li>Cuando eliminas  contenido de PI, este se borra de forma similar a cuando vacías la papelera de  reciclaje de tu computadora. No obstante, entiendes que es posible que el  contenido eliminado permanezca en copias de seguridad durante un plazo de  tiempo razonable (si bien no estará disponible para terceros).</li>
    <li>Cuando utilizas una  aplicación, esta puede solicitarte permiso para acceder a tu contenido e  información, y al contenido y a la información que otros compartieron  contigo.&nbsp; Exigimos que las aplicaciones respeten tu privacidad, y tu  acuerdo con la aplicación controlará el modo en el que esta use, almacene y  transfiera dicho contenido e información.&nbsp; (Para obtener más información  sobre la plataforma, incluido el modo de controlar la información que otras  personas pueden compartir con las aplicaciones, lee nuestra&nbsp;<a href="https://www.facebook.com/about/privacy/">Política de datos</a>&nbsp;y  la&nbsp;<a href="http://developers.facebook.com/docs/">página de la plataforma</a>).</li>
    <li>Cuando publicas  contenido o información con la configuración &quot;Público&quot;, significa que  permites que todos, incluidas las personas que son ajenas a Evenpot, accedan a  dicha información, la utilicen y la asocien a ti (es decir, a tu nombre y foto  del perfil).</li>
    <li>Siempre valoramos  tus comentarios o sugerencias acerca de Evenpot, pero debes entender que  podríamos utilizarlos sin obligación de compensarte por ellos (del mismo modo  que tú no tienes obligación de proporcionarlos).<br />
      &nbsp;</li>
  </ol>
  <li><strong>Seguridad</strong><br />
    <br />
    Hacemos todo lo posible para que Evenpot sea un sitio seguro, pero no podemos  garantizarlo. Necesitamos tu ayuda para que así sea, lo que implica los  siguientes compromisos de tu parte:</li>
  <ol>
    <li>No publicarás  comunicaciones comerciales no autorizadas (como spam) en Evenpot.</li>
    <li>No recopilarás  información o contenido de otros usuarios ni accederás a Evenpot utilizando  medios automáticos (como bots de recolección, robots, spiders o scrapers) sin  nuestro permiso previo.</li>
    <li>No participarás en  marketing multinivel ilegal, como el de tipo piramidal, en Evenpot.</li>
    <li>No subirás virus ni  código malicioso de ningún tipo.</li>
    <li>No solicitarás  información de inicio de sesión ni accederás a una cuenta perteneciente a otro  usuario.</li>
    <li>No molestarás,  intimidarás ni acosarás a ningún usuario.</li>
    <li>No publicarás  contenido que contenga lenguaje que incite al odio, resulte intimidatorio, sea  pornográfico, incite a la violencia o contenga desnudos o violencia gráfica o  injustificada.</li>
    <li>No desarrollarás ni  pondrás en funcionamiento aplicaciones de terceros que incluyan contenido  relacionado con el consumo de alcohol o las citas, o bien dirigido a público  adulto (incluidos los anuncios) sin las restricciones de edad apropiadas.</li>
    <li>No utilizarás Evenpot  para actos ilícitos, engañosos, malintencionados o discriminatorios.</li>
    <li>No realizarás  ninguna acción que pudiera inhabilitar, sobrecargar o afectar al funcionamiento  correcto de Evenpot o a su aspecto, como un ataque de denegación de servicio o  la alteración de la presentación de páginas u otras funciones de Evenpot.</li>
    <li>No facilitarás ni  fomentarás el incumplimiento de esta Declaración ni de nuestras políticas.<br />
      &nbsp;</li>
  </ol>
  <li><strong>Seguridad de la  cuenta y registro</strong><br />
    <br />
    Los usuarios de Evenpot proporcionan sus nombres y datos reales, y necesitamos  tu colaboración para que siga siendo así. Estos son algunos de los compromisos  que aceptas en relación con el registro y el mantenimiento de la seguridad de  tu cuenta:</li>
  <ol>
    <li>No proporcionarás  información personal falsa en Evenpot, ni crearás una cuenta para otras  personas sin su autorización.</li>
    <li>No crearás más de  una cuenta personal.</li>
    <li>Si inhabilitamos tu  cuenta, no crearás otra sin nuestro permiso.</li>
    <li>No utilizarás tu  biografía personal para tu propio beneficio comercial, sino que para ello te  servirás de una página de Evenpot.</li>
    <li>No utilizarás Evenpot  si eres menor de 13 años.</li>
    <li>No utilizarás Evenpot  si fuiste declarado culpable de un delito sexual.</li>
    <li>Mantendrás la  información de contacto exacta y actualizada.</li>
    <li>No compartirás tu  contraseña (o, en el caso de los desarrolladores, tu clave secreta), no dejarás  que otra persona acceda a tu cuenta, ni harás nada que pueda poner en peligro  la seguridad de tu cuenta.</li>
    <li>No transferirás la  cuenta (incluida cualquier página o aplicación que administres) a nadie sin  nuestro consentimiento previo por escrito.</li>
    <li>Si seleccionas un  nombre de usuario o identificador similar para tu cuenta o página, nos  reservamos el derecho de eliminarlo o reclamarlo si lo consideramos oportuno  (por ejemplo, si el propietario de una marca comercial se queja por un nombre  de usuario que no esté estrechamente relacionado con el nombre real del  usuario).<br />
      &nbsp;</li>
  </ol>
  <li><strong>Protección de los  derechos de otras personas</strong><br />
    <br />
    Respetamos los derechos de otras personas y esperamos que tú hagas lo mismo.</li>
  <ol>
    <li>No publicarás  contenido ni realizarás ninguna acción en Evenpot que infrinja o vulnere los  derechos de terceros o que vulnere la ley de algún modo.</li>
    <li>Podemos retirar  cualquier contenido o información que publiques en Evenpot si consideramos que  infringe esta Declaración o nuestras políticas.</li>
    <li>Te proporcionamos  las herramientas necesarias para ayudarte a proteger tus derechos de propiedad  intelectual. Para obtener más información, visita nuestra página<a href="https://www.facebook.com/help/399224883474207">Cómo reportar vulneraciones de  derechos de propiedad intelectual</a>.</li>
    <li>Si retiramos tu  contenido debido a una infracción de los derechos de autor de otra persona y  consideras que cometimos un error, tendrás la posibilidad de apelar la  decisión.</li>
    <li>Si infringes  repetidamente los derechos de propiedad intelectual de otras personas, inhabilitaremos  tu cuenta cuando lo estimemos oportuno.</li>
    <li>No utilizarás  nuestros derechos de autor, nuestras marcas comerciales ni ninguna marca que se  parezca a las nuestras, excepto si lo permiten nuestras Normas de uso de las  marcas de forma expresa o si recibes un consentimiento previo por escrito de Evenpot.</li>
    <li>Si obtienes  información de los usuarios, deberás obtener su consentimiento previo, dejar  claro que eres tú (y no Evenpot) quien recopila la información y publicar una  política de privacidad que explique qué datos recopilas y cómo los usarás.</li>
    <li>No publicarás los  documentos de identidad ni la información financiera confidencial de nadie en Evenpot.</li>
    <li>No etiquetarás a  los usuarios ni enviarás invitaciones por correo electrónico a quienes no sean  usuarios sin su consentimiento. Evenpot ofrece herramientas de reporte social  para que los usuarios puedan hacernos llegar sus opiniones sobre el etiquetado.<br />
      &nbsp;</li>
  </ol>
  <li><strong>Dispositivos  móviles y de otros tipos</strong></li>
  <ol>
    <li>Actualmente  ofrecemos nuestros servicios para dispositivos móviles de forma gratuita, pero  ten en cuenta que se aplicarán las tarifas normales de tu operador, por  ejemplo, para mensajes de texto y datos.</li>
    <li>En caso de que  cambies o desactives tu número de teléfono celular, actualizarás la información  de tu cuenta en Evenpot en un plazo de 48&nbsp;horas para garantizar que los  mensajes no se envíen por error a la persona que pudiera adquirir tu número  antiguo.</li>
    <li>Proporcionas tu  consentimiento y todos los derechos necesarios para permitir que los usuarios  sincronicen (incluso a través de una aplicación) sus dispositivos con cualquier  información que puedan ver en Evenpot.<br />
      &nbsp;</li>
  </ol>
  <li><strong>Pagos</strong><br />
    <br />
    Si realizas un pago en Evenpot, aceptas nuestras&nbsp;<a href="https://www.facebook.com/payments_terms">Condiciones de pago</a>, a  menos que se indique que se aplican otras condiciones.<br />
    &nbsp;</li>
  <li><strong>Disposiciones  especiales aplicables a desarrolladores u operadores de aplicaciones y sitios  web&nbsp;</strong><br />
    <br />
    Si eres desarrollador u operador de una aplicación o de un sitio web de la  plataforma o si usas plug-ins sociales, debes cumplir con las&nbsp;<a href="https://developers.facebook.com/policy">Normas de la plataforma de Evenpot</a>.</li>
  <li><strong>Acerca de los  anuncios u otro contenido comercial publicado u optimizado por Evenpot</strong><br />
    <br />
    Nuestro objetivo es publicar anuncios y otro contenido comercial o patrocinado  que sea valioso para nuestros usuarios y anunciantes. Para ayudarnos a  lograrlo, aceptas lo siguiente:</li>
  <ol>
    <li>Nos concedes permiso  para usar tu nombre, foto del perfil, contenido e información en relación con  contenido comercial, patrocinado o asociado (como una marca que te guste) que  publiquemos u optimicemos. Esto significa, por ejemplo, que permites que una  empresa u otra entidad nos pague por mostrar tu nombre y/o foto del perfil con  tu contenido o información sin que recibas ninguna compensación por ello. Si  seleccionaste un público específico para tu contenido o información,  respetaremos tu elección cuando lo usemos.</li>
    <li>No proporcionamos  tu contenido o información a anunciantes sin tu consentimiento.</li>
    <li>Entiendes que es  posible que no siempre identifiquemos las comunicaciones y los servicios de  pago como tales.<br />
      &nbsp;</li>
  </ol>
  <li><strong>Disposiciones  especiales aplicables a anunciantes&nbsp;</strong><br />
    <br />
    Si utilizas nuestras interfaces de creación de anuncios de autoservicio para  crear, presentar y/o entregar anuncios u otra actividad o contenido de carácter  comercial o patrocinado (conjuntamente, &quot;Interfaces de publicidad de  autoservicio&quot;), aceptas nuestras&nbsp;<a href="https://www.facebook.com/legal/self_service_ads_terms">Condiciones de  publicidad de autoservicio</a>. Asimismo, dichos anuncios u otra  actividad o contenido de carácter comercial o patrocinado publicados en Evenpot  o en nuestra red de editores deben cumplir nuestras&nbsp;<a href="https://www.facebook.com/ad_guidelines.php">Políticas de publicidad</a>.</li>
  <li><strong>Disposiciones  especiales aplicables a páginas</strong><br />
    <br />
    Si creas o administras una página de Evenpot, organizas una promoción o pones  en circulación una oferta desde tu página, aceptas nuestras&nbsp;<a href="https://www.facebook.com/page_guidelines.php">Condiciones de las páginas</a>.<br />
    &nbsp;</li>
  <li><strong>Disposiciones  especiales aplicables al software</strong></li>
  <ol>
    <li>Si descargas o  utilizas nuestro software, como un producto de software independiente, una  aplicación o un plug-in para el navegador, aceptas que, periódicamente, pueden  descargarse e instalarse mejoras, actualizaciones y funciones adicionales con  el fin de mejorar, optimizar y desarrollar el software.</li>
    <li>No modificarás  nuestro código fuente ni llevarás a cabo con él trabajos derivados, como  descompilar o intentar de algún otro modo extraer dicho código fuente, excepto  en los casos permitidos expresamente por una licencia de código abierto o si te  damos nuestro consentimiento expreso por escrito.</li>
  </ol>
</ol>
<ol>
  <li><strong>Enmiendas</strong></li>
  <ol>
    <li>Te notificaremos  antes de realizar cambios en estas condiciones y te daremos la oportunidad de  revisar y comentar las condiciones modificadas antes de seguir usando nuestros  Servicios.</li>
    <li>Si realizamos  cambios en las políticas, normas u otras condiciones a las que hace referencia  esta Declaración o que están incorporadas en ella, podremos indicarlo en la  página &quot;Evenpot Site Governance&quot;.</li>
    <li>Tu uso continuado  de los Servicios de Evenpot después de recibir la notificación sobre los  cambios en nuestras condiciones, políticas o normas supone la aceptación de las  enmiendas.<br />
      &nbsp;</li>
  </ol>
  <li><strong>Terminación</strong><br />
    <br />
    Si infringes la esencia o el espíritu de esta Declaración, creas riesgos de  cualquier tipo para Evenpot o nos expones a posibles responsabilidades  jurídicas, podríamos impedirte el acceso a Evenpot total o parcialmente. Te  notificaremos por correo electrónico o la próxima vez que intentes acceder a tu  cuenta. También puedes eliminar tu cuenta o desactivar tu aplicación en  cualquier momento. En tales casos, esta Declaración cesará, pero las siguientes  disposiciones continuarán vigentes: 2.2, 2.4, 3-5, 9.3 y 14-18.&nbsp;<br />
    &nbsp;</li>
  <li><strong>Conflictos</strong></li>
  <ol>
    <li>Resolverás  cualquier demanda, causa de acción o conflicto (colectivamente,  &quot;demanda&quot;) que tengas con nosotros surgida de la presente Declaración  o de Evenpot, o relacionada con estos, únicamente en el Tribunal de Distrito de  los Estados Unidos para el Distrito del Norte de California o en un tribunal  estatal del condado de San Mateo y aceptas que sean dichos tribunales los  competentes a la hora de resolver los litigios de dichas demandas. Las leyes  del estado de California rigen esta Declaración, así como cualquier demanda que  pudiera surgir entre tú y nosotros, independientemente de las disposiciones  sobre conflictos de leyes.</li>
    <li>Si alguien interpone  una demanda contra nosotros relacionada con tus acciones, tu contenido o tu  información en Evenpot, nos indemnizarás y nos librarás de la responsabilidad  por todos los posibles daños, pérdidas y gastos de cualquier tipo (incluidos  los costos y honorarios judiciales razonables) relacionados con dicha demanda.  Aunque proporcionamos normas para la conducta de los usuarios, no controlamos  ni dirigimos sus acciones en Evenpot y no somos responsables del contenido o de  la información que los usuarios transmitan o compartan en Evenpot. No somos  responsables de ningún contenido que se considere ofensivo, inapropiado,  obsceno, ilegal o inaceptable que puedas encontrar en Evenpot. No somos  responsables de la conducta de ningún usuario de Evenpot, tanto dentro como  fuera de Evenpot.</li>
    <li>INTENTAMOS MANTENER  EVENPOT EN FUNCIONAMIENTO, SIN ERRORES Y SEGURO, PERO LO UTILIZAS BAJO TU  PROPIA RESPONSABILIDAD. PROPORCIONAMOS EVENPOT TAL CUAL, SIN GARANTÍA ALGUNA  EXPRESA O IMPLÍCITA, INCLUIDAS, ENTRE OTRAS, LAS GARANTÍAS DE COMERCIABILIDAD,  ADECUACIÓN A UN FIN PARTICULAR Y NO INCUMPLIMIENTO. NO GARANTIZAMOS QUE EVENPOT  SEA SIEMPRE SEGURO O ESTÉ LIBRE DE ERRORES, NI QUE FUNCIONE SIEMPRE SIN  INTERRUPCIONES, RETRASOS O IMPERFECCIONES. EVENPOT NO SE RESPONSABILIZA DE LAS  ACCIONES, EL CONTENIDO, LA INFORMACIÓN O LOS DATOS DE TERCEROS, Y POR LA  PRESENTE NOS DISPENSAS A NOSOTROS, NUESTROS DIRECTIVOS, EMPLEADOS Y AGENTES DE  CUALQUIER DEMANDA O DAÑOS, CONOCIDOS O DESCONOCIDOS, DERIVADOS DE CUALQUIER  DEMANDA QUE TENGAS INTERPUESTA CONTRA TALES TERCEROS O DE ALGÚN MODO  RELACIONADOS CON ESTA. SI ERES RESIDENTE DE CALIFORNIA, RENUNCIAS A LOS  DERECHOS DE LA SECCIÓN&nbsp;1542 DEL CÓDIGO CIVIL DE CALIFORNIA, QUE ESTIPULA  LO SIGUIENTE: UNA RENUNCIA GENERAL NO INCLUYE LAS DEMANDAS QUE EL ACREEDOR  DESCONOCE O NO SOSPECHA QUE EXISTEN EN SU FAVOR EN EL MOMENTO DE LA EJECUCIÓN  DE LA RENUNCIA, LA CUAL, SI FUERA CONOCIDA POR ÉL, DEBERÁ HABER AFECTADO  MATERIALMENTE A SU RELACIÓN CON EL DEUDOR. NO SEREMOS RESPONSABLES DE NINGUNA  PÉRDIDA DE BENEFICIOS, ASÍ COMO DE OTROS DAÑOS RESULTANTES, ESPECIALES,  INDIRECTOS O INCIDENTALES DERIVADOS DE ESTA DECLARACIÓN O DE EVENPOT O  RELACIONADOS CON ESTOS, INCLUSO EN EL CASO DE QUE SE HAYA AVISADO DE LA  POSIBILIDAD DE QUE SE PRODUZCAN DICHOS DAÑOS. NUESTRA RESPONSABILIDAD CONJUNTA  DERIVADA DE LA PRESENTE DECLARACIÓN O DE EVENPOT NO PODRÁ SOBREPASAR EL VALOR  DE CIEN DÓLARES (100&nbsp;USD) O EL IMPORTE QUE NOS HAYAS PAGADO EN LOS ÚLTIMOS  DOCE MESES, LO QUE SEA MÁS ALTO. LAS LEYES APLICABLES PODRÍAN NO PERMITIR LA  LIMITACIÓN O EXCLUSIÓN DE LA RESPONSABILIDAD POR DAÑOS INCIDENTALES O  DERIVADOS, POR LO QUE LA LIMITACIÓN O EXCLUSIÓN ANTERIOR PODRÍA NO SER  APLICABLE EN TU CASO. EN TALES CASOS, LA RESPONSABILIDAD DE EVENPOT SE LIMITARÁ  AL GRADO MÁXIMO PERMITIDO POR LA LEY APLICABLE.<br />
      &nbsp;</li>
  </ol>
  <li><strong>Disposiciones especiales  aplicables a usuarios que no residen en los Estados Unidos</strong><br />
    <br />
    Nos esforzamos por crear una comunidad mundial con normas coherentes para  todos, pero también por respetar la legislación local. Las siguientes  disposiciones se aplicarán a los usuarios y a las personas que no sean usuarios  de Evenpot que se encuentran fuera de los Estados Unidos:</li>
  <ol>
    <li>Das tu  consentimiento para que tus datos personales se transfieran y se procesen en  los Estados Unidos.</li>
    <li>Si te encuentras en  un país bajo el embargo de los Estados Unidos o que forme parte de la lista SDN  (Specially Designated Nationals, ciudadanos norteamericanos especialmente  designados) del Departamento del Tesoro de los Estados Unidos, no participarás  en actividades comerciales en Evenpot (como publicidad o pagos) ni utilizarás  una aplicación o un sitio web de la plataforma. No utilizarás Evenpot si se te  prohibió recibir productos, servicios o software procedente de los Estados  Unidos.</li>
    <li>Las condiciones  aplicables específicamente a los usuarios de Evenpot en Alemania están  disponibles&nbsp;<a href="https://www.facebook.com/terms/provisions/german/index.php">aquí</a>.</li>
  </ol>
  <li><strong>Definiciones</strong></li>
  <ol>
    <li>Las expresiones  &quot;Evenpot&quot; o &quot;Servicios de Evenpot&quot; se refieren a las  funciones y los servicios que proporcionamos, incluidos los que se ofrecen a  través de (a) nuestro sitio web en www.evenpot.com y cualquier otro sitio web  con marca o marca compartida de Evenpot (incluidos los subdominios, las  versiones internacionales, los widgets y las versiones para dispositivos  móviles); (b) nuestra plataforma; (c) plug-ins sociales, como el botón &quot;Me  gusta&quot;, el botón &quot;Compartir&quot; y otros elementos similares y (d)  otros medios, marcas, productos, servicios, software (como una barra de  herramientas), dispositivos o redes ya existentes o desarrollados con  posterioridad. Evenpot se reserva el derecho de determinar, según su propio  criterio, que ciertas marcas, productos o servicios de la empresa se rigen por  condiciones independientes y no por esta DDR.</li>
    <li>El término  &quot;plataforma&quot; se refiere al conjunto de API y servicios (como el  contenido) que permiten que otras personas, incluidos los desarrolladores de  aplicaciones y los operadores de sitios web, obtengan datos de Evenpot o nos  los proporcionen a nosotros.</li>
    <li>El término  &quot;información&quot; se refiere a los hechos y a otra información sobre ti,  incluidas las acciones que realizan los usuarios y las personas que, sin ser  usuarios, interactúan con Evenpot.</li>
    <li>El término  &quot;contenido&quot; se refiere a cualquier elemento que tú u otros usuarios  publican, proporcionan o comparten por medio de los Servicios de Evenpot.</li>
    <li>Las expresiones  &quot;datos&quot;, &quot;datos de usuario&quot; o &quot;datos del usuario&quot;  se refieren a los datos, incluidos el contenido o la información de un usuario,  que otros pueden obtener de Evenpot o proporcionar a Evenpot a través de la  plataforma.</li>
    <li>El término  &quot;publicar&quot; significa publicar en Evenpot o proporcionar contenido de  otro modo mediante Evenpot.</li>
    <li>Por  &quot;usar&quot; se entiende utilizar, ejecutar, copiar, reproducir o mostrar  públicamente, distribuir, modificar, traducir y crear obras derivadas.</li>
    <li>El término  &quot;aplicación&quot; significa cualquier aplicación o sitio web que usa la  plataforma o accede a ella, así como cualquier otro componente que recibe o  recibió datos de nosotros.&nbsp; Si ya no accedes a la plataforma, pero no  eliminaste todos los datos que te proporcionamos, el término  &quot;aplicación&quot; continuará siendo válido hasta que los elimines.</li>
    <li>La expresión  &quot;marcas comerciales&quot; se refiere a la lista de marcas comerciales que  se incluye&nbsp;<a href="https://www.facebook.com/l.php?u=https%3A%2F%2Fwww.facebookbrand.com%2Ftrademarks%2F&amp;h=lAQGecPVu&amp;s=1" target="_blank">aquí</a>.&nbsp;<br />
      &nbsp;</li>
  </ol>
  <li><strong>Otras disposiciones</strong></li>
  <ol>
    <li>Si resides o tienes  tu sede de actividad comercial principal en los Estados Unidos o en Canadá, esta  Declaración constituye el acuerdo entre Evenpot, Inc. y tú. De lo contrario,  esta Declaración constituye el acuerdo entre Evenpot Ireland Limited y  tú.&nbsp; Las menciones a &quot;nosotros&quot;, &quot;nos&quot; y  &quot;nuestro&quot; se refieren a Evenpot, Inc. o a Evenpot Ireland Limited,  según corresponda.</li>
    <li>Esta Declaración  constituye el acuerdo completo entre las partes en relación con Evenpot y  sustituye cualquier acuerdo previo.</li>
    <li>Si alguna parte de  esta Declaración no puede hacerse cumplir, la parte restante seguirá teniendo  plenos efecto y validez.</li>
    <li>Si no cumpliéramos  alguna parte de esta Declaración, no se considerará una exención.</li>
    <li>Cualquier enmienda  a esta Declaración o exención de esta deberá hacerse por escrito y estar  firmada por nosotros.</li>
    <li>No transferirás  ninguno de tus derechos u obligaciones en virtud de esta Declaración a ningún  tercero sin nuestro consentimiento.</li>
    <li>Todos nuestros  derechos y obligaciones según esta Declaración son asignables libremente por  nosotros en relación con una fusión, adquisición o venta de activos, o por  efecto de ley, o de algún otro modo.</li>
    <li>Nada de lo  dispuesto en esta Declaración nos impedirá cumplir la ley.</li>
    <li>Esta Declaración no  otorga derechos de beneficiario a ningún tercero.</li>
    <li>Nos reservamos  todos los derechos que no te hayamos concedido de forma expresa.</li>
    <li>Cuando accedas a Evenpot  o lo uses deberás cumplir todas las leyes aplicables.</li>
  </ol>
</ol>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">De acuerdo</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<br>

<?php include ('footer.php'); ?>    
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
<script src="js/register.js"></script>
</body>
</html>