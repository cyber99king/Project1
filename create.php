<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $email = $question = "";
$name_err = $email_err = $question_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter your name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email address.";     
    } else{
        $email = $input_email;
    }
    
    // Validate question
    $input_question = trim($_POST["question"]);
    if(empty($input_question)){
        $question_err = "Please enter your question.";     
    } 
     else{
        $question = $input_question;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($email_err) && empty($question_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO questions (name, email, question) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_question);
            
            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_question = $question;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: sign.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 800px;
            margin: 0 auto;
            background-color: antiquewhite;
        }
    </style>
</head>
<body style="background-color: cornflowerblue;">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">নুুতুন প্রশ্ন</h2>
                    <p>আপনার প্রশ্নটি লিপিবদ্ধ করুন!</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>নাম</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>ইমেইল</label>
                            <textarea name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"><?php echo $email; ?></textarea>
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>প্রশ্নসমুহ</label>
                            <input type="text" name="question" class="form-control <?php echo (!empty($question_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $question; ?>">
                            <span class="invalid-feedback"><?php echo $question_err;?></span>
                        </div>
                        <div class="p-2 ">
                            <input type="submit" class="btn btn-primary p-2" value="Submit">
                            <a href="sign.php" class="btn btn-secondary ml-2 p-2">Cancel</a>

                        </div>
                        
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>