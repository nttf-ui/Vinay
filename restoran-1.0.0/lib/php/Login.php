<html>
<head>
    <title>email verification</title>
    <link rel="style" type="text" href="style5.css">
</head>
<body>
    <div class="wrapper" id="login-side">
        <div class="left-side">
            <h2>login</h2>
            <hr>
            <from action="" method="post">
                <div class="form-group">
                    <label>Email</label>
                    </input type ="email" name="email" placeholder="Email" autocomplete="off"  required>
                </div>
                <div class="form-group">
                    <label>password</label>
                    </input type ="password" name="password" placeholder="password" autocomplete="off"  required>
                </div>
                <div class="form-group">
                    <label></label>
                    </input type ="submit" name="login" value="login">
                </div>
            </from>
        </div>
        <div class="container"></div>
        <div class="rigth-side" id="signup-text-side">
            <h2>Register</h2>
            <hr>
            <div class="right-side-text">
            <p>don't have an account ?</p>
            <p>please click to signup button for register</p>
            <a href="javascript:void(0);" id="signup-button">Signup</a>
</div>
</div>
</div>
<div class="wrapper display" id="signup-side">
<div class="left-side signUp">
    <h2>signup</h2>
    <hr>
    <from action="" method="POST">
        <input type="hidden" name="otp" value="<?php echo $otp; ?>">
        <input type="hidden" name="activation_code" value="<?php echo $activation_code; ?>"> 
        <div class="form-group">
            <label>Name</label>
            </input type ="text" name="name" placeholder="your Name" autocomplete="off"  required>
        </div>
        <div class="form-group">
            <label>Email</label>
             </input type ="email" name="email" placeholder="Email" autocomplete="off"  required>
        </div>
        <div class="form-group">
            <label>password</label>
            </input type ="password" name="password" placeholder="password" autocomplete="off"  required>
        </div>    
        <div class="form-group">
            <label></label> 
            </input type ="submit" name="register" value="Signup">
        </div>
    </form>
    <div class="container"></div>
        <div class="rigth-side" id="signup-text-side">
                <h2>login</h2>
                <hr>
            <div class="right-side-text">
                <p>don't have an account ?</p>
                <p>please click to signup button for register</p>
                <a href="javascript:void(0);" id="login-button">login</a>
            </div>
        </div>  
</div>
</body>
<script type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('#login-side').click(fu)