<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeIgniter User Registration Form Demo</title>
    <link href="<?php echo base_url("bootstrap/css/bootstrap.css"); ?>" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?php echo $this->session->flashdata('verify_msg'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>User Registration Form</h4>
                </div>
                <div class="panel-body">
                    <?php $attributes = array("name" => "registrationform");
                    echo form_open("User/registration", $attributes);?>
                    <div class="form-group">
                        <label for="name">login</label>
                        <input class="form-control" name="login" placeholder="Your First Name" type="text" value="<?php echo set_value('login'); ?>" />
                        <span class="text-danger"><?php echo form_error('login'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email ID</label>
                        <input class="form-control" name="email" placeholder="Email-ID" type="text" value="<?php echo set_value('email'); ?>" />
                        <span class="text-danger"><?php echo form_error('email'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="subject">Password</label>
                        <input class="form-control" name="password" placeholder="Password" type="password" />
                        <span class="text-danger"><?php echo form_error('password'); ?></span>
                    </div>

                    <div class="form-group">
                        <label for="subject">Confirm Password</label>
                        <input class="form-control" name="cpassword" placeholder="Confirm Password" type="password" />
                        <span class="text-danger"><?php echo form_error('cpassword'); ?></span>
                    </div>

                    <div class="form-group">
                        <button name="submit" type="submit" class="btn btn-default">Signup</button>
                        <button name="cancel" type="reset" class="btn btn-default">Cancel</button>
                    </div>
                    <?php echo form_close(); ?>
                    <?php echo $this->session->flashdata('msg'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>