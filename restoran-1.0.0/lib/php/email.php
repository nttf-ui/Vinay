<?php
include "configure.php"; // This file must set up $conn as the mysqli connection

session_start();

// Generate OTP and activation code
function generate_otp($length = 5) {
    return substr(str_shuffle("1234567890"), 0, $length);
}
function generate_activation_code() {
    return str_shuffle("abcdefghijklmno" . rand(100000, 10000000));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $password_raw = $_POST['password'];
        $password = mysqli_real_escape_string($conn, md5($password_raw));

        if (empty($name) || empty($email) || empty($password_raw)) {
            echo "<script>alert('Please fill all signup fields.')</script>";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.')</script>";
        } else {
            // Check if email exists
            $sql_check = "SELECT * FROM user WHERE email='$email'";
            $res_check = mysqli_query($conn, $sql_check);
            if (!$res_check) {
                die("Database error: " . mysqli_error($conn));
            }
            $otp = generate_otp();
            $activation_code = generate_activation_code();

            if (mysqli_num_rows($res_check) > 0) {
                // Email exists
                $row = mysqli_fetch_assoc($res_check);
                if ($row['status'] === 'active') {
                    echo "<script>alert('Email already registered and active. Please login.')</script>";
                } else {
                    // Update user record with new info and OTP
                    $sql_update = "UPDATE user SET name='$name', password='$password', otp='$otp', activation_code='$activation_code' WHERE email='$email'";
                    $res_update = mysqli_query($conn, $sql_update);
                    if (!$res_update) {
                        die("Update error: " . mysqli_error($conn));
                    }
                    // Send OTP email
                    require 'class/class.phpmailer.php';
                    $mail = new PHPMailer;
                    $mail->IsSMTP();
                    $mail->Host = 'smtp.sendgrid.net';
                    $mail->Port = '587';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'your_account_username'; // update
                    $mail->Password = 'your_password_or_API_key'; // update
                    $mail->SMTPSecure = 'tls';
                    $mail->From = 'your_email@example.com'; // update
                    $mail->FromName = 'Your Site Name'; // update
                    $mail->AddAddress($email);
                    $mail->IsHTML(true);
                    $mail->Subject = 'Your OTP Verification Code';
                    $mail->Body = "<p>Hello $name,</p><p>Your OTP is: <b>$otp</b></p><p>Please use this OTP to verify your account.</p>";

                    if ($mail->Send()) {
                        header("Location: email_verify.php?code=$activation_code");
                        exit;
                    } else {
                        echo "<script>alert('Failed to send OTP email. Mailer Error: " . $mail->ErrorInfo . "');</script>";
                    }
                }
            } else {
                // Insert new user
                $sql_insert = "INSERT INTO user (name, email, password, otp, activation_code, status) VALUES ('$name', '$email', '$password', '$otp', '$activation_code', 'inactive')";
                $res_insert = mysqli_query($conn, $sql_insert);
                if (!$res_insert) {
                    die("Insert error: " . mysqli_error($conn));
                }
                // Send OTP email
                require 'class/class.phpmailer.php';
                $mail = new PHPMailer;
                $mail->IsSMTP();
                $mail->Host = 'smtp.sendgrid.net';
                $mail->Port = '587';
                $mail->SMTPAuth = true;
                $mail->Username = 'your_account_username'; // update
                $mail->Password = 'your_password_or_API_key'; // update
                $mail->SMTPSecure = 'tls';
                $mail->From = 'your_email@example.com'; // update
                $mail->FromName = 'Your Site Name'; // update
                $mail->AddAddress($email);
                $mail->IsHTML(true);
                $mail->Subject = 'Your OTP Verification Code';
                $mail->Body = "<p>Hello $name,</p><p>Your OTP is: <b>$otp</b></p><p>Please use this OTP to verify your account.</p>";

                if ($mail->Send()) {
                    header("Location: email_verify.php?code=$activation_code");
                    exit;
                } else {
                    echo "<script>alert('Failed to send OTP email. Mailer Error: " . $mail->ErrorInfo . "');</script>";
                }
            }
        }
    }
    if (isset($_POST['login'])) {
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $password_raw = $_POST['password'];
        $password = mysqli_real_escape_string($conn, md5($password_raw));

        if (empty($email) || empty($password_raw)) {
            echo "<script>alert('Please enter email and password.')</script>";
        } else {
            $sql_login = "SELECT * FROM user WHERE email='$email' AND password='$password'";
            $res_login = mysqli_query($conn, $sql_login);
            if (!$res_login) {
                die("Database error: " . mysqli_error($conn));
            }
            if (mysqli_num_rows($res_login) == 1) {
                $user = mysqli_fetch_assoc($res_login);
                if ($user['status'] === 'active') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    echo "<script>alert('Login successful. Welcome, " . htmlspecialchars($user['name']) . "!');</script>";
                    // Redirect or show logged-in content here
                } else {
                    echo "<script>alert('Account not verified. Please check your email for OTP verification.');</script>";
                }
            } else {
                echo "<script>alert('Invalid email or password.');</script>";
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

 	<head>
 		<!-- Meta Tags -->
		<meta charset="UTF-8">
		<meta name="author" content="Kamran Mubarik">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Site Title -->
 		<title>Create a Login and Signup Page || PSD to HTML</title>
 		<!-- External Style Sheet -->
		<link rel="stylesheet" type="text/css" href="css/style.css" />

 	</head>
<body>
	
	<div class="wrapper" id="login-side">
		<div class="left-side">
			<h2>Login</h2>
			<hr>
			<form>
				<div class="form-group">
					<label>Email</label>
					<input type="email" name="email" placeholder="Registered Email">
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" placeholder="Password">
				</div>
				<div class="form-group">
					<label></label>
					<input type="submit" name="login" value="Login">
				</div>
			</form>
		</div>
		<div class="container"></div>
		<div class="right-side">
			<h2>Registered</h2>
			<hr>
			<p>Don't have an Account?</p>
			<p>Please signup to register</p>
			<a href="#" id="signup-button">Signup</a>
		</div>
	</div>
	<!-- End of Wrapper -->

	<div class="wrapper display" id="singup-side">
		<div class="left-side signUp">
			<h2>Signup</h2>
			<hr>
			<from action="" method="POST">
        		<input type="hidden" name="otp" value="<?php echo $otp; ?>">
        		<input type="hidden" name="activation_code" value="<?php echo $activation_code; ?>"> 
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="name" placeholder="Your Name">
				</div>
				<div class="form-group">
					<label>Email</label>
					<input type="email" name="email" placeholder="Registered Email">
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" placeholder="Password">
				</div>
				<div class="form-group">
					<label></label>
					<input type="submit" name="signup" value="Signup">
				</div>
			</form>
		</div>
		<div class="container"></div>
		<div class="right-side">
			<h2>Login</h2>
			<hr>
			<p>Already have an Account?</p>
			<p>Please click to Login button for login</p>
			<a href="#" id="login-button">Login</a>
		</div>
	</div>

</body>

<script type="text/javascript" src="js/jquery.min.3-4-1.js"></script>
<script>
	$(document).ready(function(){

		$('#signup-button').click(function(){
			$('#login-side').addClass('display').fadeOut();
			$('#singup-side').removeClass('display').fadeIn();
		});
		$('#login-button').click(function(){
			$('#singup-side').addClass('display').fadeOut();
			$('#login-side').removeClass('display').fadeIn();
		});
	});
</script>

</html>