<!-- Code by Brave Coder - https://youtube.com/BraveCoder -->

<?php
error_reporting(0);
include "config/config.php";
$media = mysqli_query($conn, "SELECT * FROM other");
$do = mysqli_fetch_array($media);

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

function create_random($length)
{
    $data = 'ABCDEFGHIJKLMNOPQRSTU1234567890';
    $string = '';
    for($i = 0; $i < $length; $i++) {
        $pos = rand(0, strlen($data)-1);
        $string .= $data{$pos};
    }
    return $string;
}

    session_start();


    //Load Composer's autoloader
    require 'mailer/autoload.php';

    include 'config/config.php';
    $msg = "";
    $pass ="";

    if (isset($_POST['submit'])) {
    	$level = mysqli_real_escape_string($conn, $_POST['level']);
	$noreg = mysqli_real_escape_string($conn, create_random(10));
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $date_created = mysqli_real_escape_string($conn, $_POST['date_created']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, ($_POST['password']));
        $confirm_password = mysqli_real_escape_string($conn, ($_POST['confirm-password']));
        $code = mysqli_real_escape_string($conn, md5(rand()));

        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE email='{$email}'")) > 0) {
            $msg = "<div class='alert alert-danger'>{$email} - Email Ini Telah terdaftar.</div>";
        } else {
            if ($password === $confirm_password) {
            
                $sql = "INSERT INTO user (nama, email, password, level, noreg, date_created, code) VALUES ('{$nama}', '{$email}', '{$password}', '{$level}', '{$noreg}','{$date_created}','{$code}')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "<div style='display: none;'>";
                    //Create an instance; passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'rozaquf@gmail.com';                     //SMTP username
                        $mail->Password   = 'muhammad2021';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('rozaquf@gmail.com', 'Sandrive');
                        $mail->addAddress($email);

                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Aktivasi Akun';
                        // $mail->Body    = 'Hai '.$nama.' Pendaftaran Kamu Berhasil. No Registrasi kamu '.$noreg.' Silahkan Klik link ini untuk verifikasi <b><a href="http://localhost/sandrivepro/login?verification='.$code.'">Aktivasi Sekarang!</a></b>';
                         $mail->Body    = "
                         <!DOCTYPE html>
<html>
<head>
    <title></title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3' crossorigin='anonymous'>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js' integrity='sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p' crossorigin='anonymous'></script>
</head>
<body>
<div class='card text-center'>
  <div class='card-body'>
    <h5 class='card-title'>Hai, $nama</h5>
    <p class='card-text'>Pendaftaran kamu Berhasil, Silahkan lakukan aktivasi akun terlebih dahulu. <a href='http://localhost/sandrivepro/login?verification=$code'>Aktivasi Sekarang!</a></p>
  </div>
  <div class='card-footer text-muted'>
    <i>-Sandrive Developer</i>
  </div>
</div>
</body>
</html>
";

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Pesan Gagal Terkirim. Mailer Error: {$mail->ErrorInfo}";
                    }
                    echo "</div>";
                    $msg = "<div class='alert alert-info'>Kami Telah Mengirim link Verifikasi ke email $email </div>";
                    
                } else {
                    $msg = "<div class='alert alert-danger'>Ada Yang Salah.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password & Konfirmasi Password Tidak Sesuai.</div>";
            }

        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from themes.3rdwavemedia.com/coderpro/bs4/signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 16 Dec 2021 03:18:16 GMT -->

<head>
	<title>signup <?= $do['nama_sistem']?> | Cloud Storage</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<script src="http://code.jquery.com/jquery-2.2.1.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Bootstrap 4 Template For Software Startups">
	<meta name="author" content="Xiaoying Riley at 3rd Wave Media">
	<link rel="shortcut icon" href="media/logo.png">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&amp;display=swap" rel="stylesheet">

	<!-- FontAwesome JS-->
	<script defer src="assets/fontawesome/js/all.min.js"></script>

	<!-- Theme CSS -->
	<link id="theme-style" rel="stylesheet" href="assets/css/theme.css">

</head>

		<style type="text/css">
.preloader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background-color: #fff;
}
.preloader .loading {
  position: absolute;
  left: 55%;
  top: 50%;
  transform: translate(-50%,-50%);
  font: 14px arial;
}
</style>
<div class="preloader">
  <div class="loading">
    <img src="media/cloud_load.gif" width="80%">
  </div>
</div>
<script>$(document).ready(function(){
$(".preloader").delay(1000).fadeOut(500);
}) </script>
</script>

<body class="p-0 theme-bg-light">

	<section class="auth-section signup-section text-center py-5">
		<div class="container">
			<div class="site-logo mb-4"><a class="navbar-brand mr-0" href="<?= $baseurl ?>"><img class="logo-icon mr-2" width="40px" src="<?= $logo ?>" alt="logo" ><span class="logo-text"><?= $do['nama_sistem'] ?></a></div>

			<div class="auth-wrapper mx-auto shadow p-5 rounded">

				<h2 class="auth-heading text-center mb-3">Daftar Gratis Sekarang!</h2>
				<?php error_reporting(0);
				echo $success ?>
				<!--//social-auth-->

				<div class="divider my-5">
					<!-- <span class="or-text">OR</span> -->
				</div>
				<!--//divider-->
   <?php echo $msg; ?>
				<div class="auth-form-container text-left mx-auto">
					<form class="auth-form auth-signup-form" action="" method="POST">
						<div class="form-group email">
							<label class="sr-only" for="signup-email">Nama Lengkap</label>
							<input id="signup-email" name="nama" type="text" class="form-control signup-email" placeholder="Nama Lengkap" value="<?php if (isset($_POST['submit'])) { echo $nama; } ?>" required=" required">
						</div>
						<div class="form-group email">
							<label class="sr-only" for="signup-email">Alamat Email</label>
							<input type="hidden" name="date_created" value="<?= time() ?>">
							<input type="hidden" name="level" value="user">
							<input id="signup-email" name="email" type="email" class="form-control signup-email" placeholder="Alamat Email" required="required" value="<?php if (isset($_POST['submit'])) { echo $email; } ?>">
						</div>
						<!--//form-group-->
						<div class="form-group password">
							<label class="sr-only" for="signup-password">Password</label>
							<input id="signup-password" name="password" type="password" class="form-control signup-password" placeholder="Buat sebuah password" required="required">
						</div>
						<div class="form-group password">
							<label class="sr-only" for="signup-password">Konfirmasi Password</label>
							<input id="signup-password" name="confirm-password" type="password" class="form-control signup-password" placeholder="Konfirmasi password" required="required">
						</div>
						<!--//form-group-->
						<div class="extra mb-4 text-center">Dengan Mendaftar, Anda Menyetujui<a href="#" class="theme-link">persyaratan Layanan</a> dan <a href="#" class="theme-link">Kebijakan Privasi</a> dari kami.</div>
						<div class="text-center">
							<button type="submit" name="submit" class="btn btn-primary btn-block theme-btn mx-auto">Buat Akun</button>
						</div>
					</form>
					<!--//auth-form-->

					<div class="auth-option text-center pt-5">Sudah memiliki Akun? <a href="login">Gabung</a></div>
				</div>
				<!--//auth-form-container-->

			</div>
			<!--//auth-wrapper-->
		</div>
		<!--//container-->
	</section>
	<!--//auth-section-->

	<!-- <footer class="footer auth-footer py-5">
		<div class="footer-bottom text-center">
			<small class="copyright">Template Copyright &copy; <a href="https://themes.3rdwavemedia.com/" target="_blank">3rd Wave Media</a></small>
		</div>
		//footer-bottom-->
	</footer> -->
	<!--//footer-->

	<!-- Javascript -->
	<script src="assets/plugins/jquery-3.4.1.min.js"></script>
	<script src="assets/plugins/popper.min.js"></script>
	<script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <footer class="footer auth-footer py-5">
        <div class="footer-bottom text-center">
            <small class="copyright">All Rights Reserved ❤️‍ <a href="./" target="_blank"><?= $do['nama_sistem']  ?>_Developer.</a></small>
        </div>
        <!--//footer-bottom-->
    </footer>


</body>

<!-- Mirrored from themes.3rdwavemedia.com/coderpro/bs4/signup.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 16 Dec 2021 03:18:16 GMT -->

</html>